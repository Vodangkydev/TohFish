<?php

namespace App\Services;

use App\Models\Content;
use Illuminate\Support\Facades\Log;

class ContentService
{
    /**
     * Lấy tất cả nội dung
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllContents()
    {
        return Content::orderBy('created_at', 'desc')->get();
    }

    /**
     * Lấy N nội dung mới nhất
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLatestContents($limit = 10)
    {
        return Content::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Lấy nội dung theo ID
     * 
     * @param int $id
     * @return Content|null
     */
    public function getContentById($id)
    {
        return Content::find($id);
    }

    /**
     * Tìm kiếm nội dung
     * 
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchContents($query)
    {
        if (empty($query)) {
            return collect();
        }

        return Content::where('content', 'LIKE', "%{$query}%")
            ->orWhere('description', 'LIKE', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->get();
    }
}

