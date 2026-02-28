<?php

namespace App\Services;

use App\Models\Post;
use App\Models\ParentModel;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PostService
{
    /**
     * Lấy các bài viết cũ nhất
     */
    public function getOldestPosts($limit = 4)
    {
        return Post::orderBy('created_at', 'asc')->take($limit)->get();
    }

    /**
     * Lấy tất cả bài viết với relationships
     */
    public function getAllPostsWithRelations()
    {
        return Post::with(['roleCategory', 'positionCategory', 'locationCategory'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Tìm kiếm bài viết theo nội dung
     */
    public function searchPosts($query)
    {
        if (empty($query)) {
            return collect();
        }

        return Post::where('content', 'LIKE', "%{$query}%")->get();
    }

    /**
     * Lấy bài viết theo parent_id
     */
    public function getPostsByParentId($parentId)
    {
        return Post::with(['roleCategory', 'positionCategory', 'locationCategory'])
            ->where(function ($query) use ($parentId) {
                $query->whereHas('roleCategory', function ($q) use ($parentId) {
                    $q->where('parent_id', $parentId);
                })
                ->orWhereHas('positionCategory', function ($q) use ($parentId) {
                    $q->where('parent_id', $parentId);
                })
                ->orWhereHas('locationCategory', function ($q) use ($parentId) {
                    $q->where('parent_id', $parentId);
                });
            })
            ->get();
    }

    /**
     * Lấy bài viết theo khoảng thời gian
     */
    public function getPostsByDateRange($startDate, $endDate)
    {
        try {
            $startDate = Carbon::createFromFormat('Y-m-d', $startDate, 'UTC')->format('Y-m-d');
            $endDate = Carbon::createFromFormat('Y-m-d', $endDate, 'UTC')->format('Y-m-d');

            return Post::with(['roleCategory', 'positionCategory', 'locationCategory'])
                ->whereDate('created_at', '>=', $startDate)
                ->whereDate('created_at', '<=', $endDate)
                ->get();
        } catch (\Exception $e) {
            Log::error('Error filtering posts by date range', [
                'start_date' => $startDate,
                'end_date' => $endDate,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Lấy các parent categories theo IDs
     */
    public function getParentCategories(array $parentIds)
    {
        return ParentModel::whereIn('parent_id', $parentIds)
            ->orderBy('created_at', 'asc')
            ->get();
    }
}

