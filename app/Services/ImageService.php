<?php

namespace App\Services;

use App\Models\Image;
use Illuminate\Support\Facades\Log;

class ImageService
{
    /**
     * Lấy tất cả hình ảnh
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllImages()
    {
        return Image::orderBy('created_at', 'desc')->get();
    }

    /**
     * Lấy N hình ảnh mới nhất
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLatestImages($limit = 10)
    {
        return Image::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Lấy hình ảnh theo ID
     * 
     * @param int $id
     * @return Image|null
     */
    public function getImageById($id)
    {
        return Image::find($id);
    }

    /**
     * Tìm kiếm hình ảnh theo nội dung
     * 
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchImages($query)
    {
        if (empty($query)) {
            return collect();
        }

        return Image::where('content', 'LIKE', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Lấy URL hình ảnh từ database và xử lý hiển thị
     * 
     * @param Image $image
     * @param string $fallback Đường dẫn fallback nếu không có ảnh
     * @return array ['url' => string, 'exists' => bool, 'raw_path' => string]
     */
    public function getImageUrl($image, $fallback = '')
    {
        $result = [
            'url' => $fallback ? asset($fallback) : null,
            'exists' => false,
            'raw_path' => null
        ];

        // Nếu không có image hoặc không có image_url
        if (!$image || !$image->image_url) {
            return $result;
        }

        $result['raw_path'] = $image->image_url;
        
        // Kiểm tra file có tồn tại không
        $result['exists'] = $image->imageExists();
        
        // Lấy URL để hiển thị
        $result['url'] = $image->getImageDisplayUrl();

        return $result;
    }

    /**
     * Xử lý danh sách images và thêm thông tin URL cho mỗi image
     * 
     * @param \Illuminate\Database\Eloquent\Collection $images
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function processImagesForDisplay($images)
    {
        return $images->map(function ($image) {
            $imageInfo = $this->getImageUrl($image);
            $image->display_url = $imageInfo['url'];
            $image->image_exists = $imageInfo['exists'];
            $image->raw_image_path = $imageInfo['raw_path'];
            return $image;
        });
    }
}

