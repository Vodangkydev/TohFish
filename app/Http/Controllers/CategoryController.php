<?php

namespace App\Http\Controllers;

use App\Services\JobService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    protected $jobService;

    public function __construct(JobService $jobService)
    {
        $this->jobService = $jobService;
    }

    /**
     * Hiển thị trang tuyển dụng
     */
    public function index(Request $request)
    {
        try {
            // Xử lý AJAX search
            if ($request->ajax() && $request->has('query')) {
                return $this->handleSearchRequest($request);
            }

            // Lấy dữ liệu từ Service
            $jobParentIds = config('app_constants.job_parent_ids');
            $parents = $this->jobService->getParentCategories($jobParentIds);
            $parentCategories = $this->jobService->getCategoriesByParentIds($jobParentIds);

            // Lọc jobs theo category_id nếu có
            if ($request->has('category_id') && !is_null($request->category_id)) {
                $jobs = $this->jobService->getJobsByCategoryId($request->category_id);
            } else {
                $jobs = $this->jobService->getAllJobsWithRelations();
            }

            // Trả về AJAX response nếu là AJAX request
            if ($request->ajax()) {
                return view('tuyen-dung.tuyen-dung', compact('parents', 'parentCategories', 'jobs'))->render();
            }

            return view('tuyen-dung.tuyen-dung', compact('parents', 'parentCategories', 'jobs'));
        } catch (\Exception $e) {
            Log::error('Error in CategoryController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Đã xảy ra lỗi khi tải dữ liệu.');
        }
    }

    /**
     * Xử lý request tìm kiếm
     */
    protected function handleSearchRequest(Request $request)
    {
        $query = $request->input('query');

        if (empty($query)) {
            return '<p>Không có kết quả tìm kiếm</p>';
        }

        $searchResults = $this->jobService->searchJobsByCategoryName($query);

        if ($searchResults->isEmpty()) {
            return '<p>Không có kết quả tìm kiếm</p>';
        }

        $view = view('partials.recent-search-list', ['jobs' => $searchResults])->render();
        return $view;
    }
}