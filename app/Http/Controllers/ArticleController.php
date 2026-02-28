<?php

namespace App\Http\Controllers;

use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    /**
     * @var ArticleService
     */
    protected $articleService;

    /**
     * Constructor - Dependency Injection
     */
    public function __construct(ArticleService $articleService)
    {
        $this->articleService = $articleService;
    }

    /**
     * Hiển thị chi tiết bài viết
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        try {
            // Tăng lượt xem
            $this->articleService->incrementView($id);
            
            // Lấy dữ liệu bài viết chi tiết
            $data = $this->articleService->getArticleDetail($id);
            
            return view('bai-viet.chi-tiet-bai-viet', [
                'postDetail' => $data['postDetail'],
                'posts' => $data['relatedPosts'],
                'post' => $data['post']
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Article not found', ['id' => $id]);
            
            return redirect()->route('bai-viet')
                ->with('error', 'Không tìm thấy bài viết.');
        } catch (\Exception $e) {
            Log::error('Error in ArticleController@show', [
                'id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('bai-viet')
                ->with('error', 'Đã xảy ra lỗi khi tải bài viết.');
        }
    }
}
