<?php

namespace App\Http\Controllers;

use App\Services\PostService;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    /**
     * Hiển thị trang danh sách bài viết
     */
    public function index(Request $request)
    {
        try {
            // Lấy dữ liệu từ Service
            $postParentIds = config('app_constants.post_parent_ids');
            $posts_cu = $this->postService->getOldestPosts();
            $posts_roll = Post::all();
            $parents = $this->postService->getParentCategories($postParentIds);
            $posts_all = $this->postService->getAllPostsWithRelations();
            $posts_reseach = Post::all();
            $jobs = collect();

            // Xử lý AJAX requests
            if ($request->ajax()) {
                return $this->handleAjaxRequest($request, $posts_cu, $posts_roll, $parents, $posts_all, $posts_reseach);
            }

            // Trả về view mặc định
            return view('bai-viet.bai-viet', compact(
                'posts_cu',
                'posts_roll',
                'parents',
                'posts_all',
                'posts_reseach',
                'jobs'
            ));
        } catch (\Exception $e) {
            Log::error('Error in PostController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi tải dữ liệu.');
        }
    }

    /**
     * Xử lý các AJAX requests
     */
    protected function handleAjaxRequest(Request $request, $posts_cu, $posts_roll, $parents, $posts_all, $posts_reseach)
    {
        // Tìm kiếm theo query
        if ($request->has('query')) {
            $query = $request->input('query');
            $searchResults = $this->postService->searchPosts($query);
            
            if ($searchResults->isEmpty()) {
                return '<p>Không có kết quả tìm kiếm</p>';
            }

            $view = view('partials.recent-search-list-1', ['posts_reseach' => $searchResults])->render();
            return $view;
        }

        // Lọc theo parent_id
        if ($request->has('parent_id') && !is_null($request->parent_id)) {
            $jobs = $this->postService->getPostsByParentId($request->parent_id);
            return view('bai-viet.bai-viet', compact(
                'posts_cu',
                'posts_roll',
                'parents',
                'posts_all',
                'jobs',
                'posts_reseach'
            ))->render();
        }

        // Lọc theo khoảng thời gian
        if ($request->has('start_date') && $request->has('end_date')) {
            $startDate = $request->input('start_date');
            $endDate = $request->input('end_date');

            if (empty($startDate) || empty($endDate)) {
                return response()->json(['error' => 'Ngày bắt đầu hoặc ngày kết thúc không được để trống.']);
            }

            try {
                $posts_all = $this->postService->getPostsByDateRange($startDate, $endDate);
                $view = view('partials.list', ['posts_all' => $posts_all])->render();
                return response()->json(['view' => $view]);
            } catch (\Exception $e) {
                Log::error('Error filtering posts by date', [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'error' => $e->getMessage()
                ]);

                return response()->json([
                    'error' => 'Có lỗi xảy ra trong việc xử lý ngày tháng. Vui lòng thử lại.'
                ]);
            }
        }

        return response()->json(['error' => 'Yêu cầu không hợp lệ.']);
    }
}