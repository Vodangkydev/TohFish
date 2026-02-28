<?php

namespace App\Services;

use App\Models\Post;
use App\Models\PostDetail;
use Illuminate\Support\Facades\Log;

class ArticleService
{
    /**
     * Lấy chi tiết bài viết theo ID
     * 
     * @param int $id
     * @return array
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getArticleDetail($id)
    {
        try {
            $postDetail = PostDetail::where('post_id', $id)->firstOrFail();
            $relatedPosts = $this->getRelatedPosts($id, 4);
            $post = $postDetail->post ?? Post::find($id);
            
            // Xử lý sub_images cho post
            if ($post) {
                $post->sub_images_urls = $post->getSubImagesDisplayUrls();
                if ($post->image_url) {
                    $post->display_url = $post->getImageDisplayUrl();
                    $post->image_exists = $post->imageExists();
                }
            }
            
            return [
                'postDetail' => $postDetail,
                'relatedPosts' => $relatedPosts,
                'post' => $post
            ];
        } catch (\Exception $e) {
            Log::error('Error in ArticleService@getArticleDetail', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Lấy các bài viết liên quan
     * 
     * @param int $postId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedPosts($postId, $limit = 4)
    {
        return Post::where('post_id', '!=', $postId)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function($post) {
                if ($post->image_url) {
                    $post->display_url = $post->getImageDisplayUrl();
                    $post->image_exists = $post->imageExists();
                }
                return $post;
            });
    }

    /**
     * Tăng lượt xem bài viết
     * 
     * @param int $postId
     * @return void
     */
    public function incrementView($postId)
    {
        try {
            Post::where('post_id', $postId)->increment('view');
        } catch (\Exception $e) {
            Log::error('Error incrementing view', [
                'post_id' => $postId,
                'error' => $e->getMessage()
            ]);
        }
    }
}

