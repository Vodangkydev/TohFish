<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    // Tên bảng trong cơ sở dữ liệu
    protected $table = 'images';

    // Khóa chính của bảng
    protected $primaryKey = 'images_id';

    // Cho phép Laravel tự động quản lý các trường `created_at` và `updated_at`
    public $timestamps = true;

    // Các thuộc tính có thể được gán hàng loạt (mass assignable)
    protected $fillable = [
        'image_url',
        'sub_images',
        'content',
        'description',
        'price',
        'size',
        'product_type',
        'discount_percent',
    ];

    // Cast sub_images thành array
    protected $casts = [
        'sub_images' => 'array',
    ];

    /**
     * Kiểm tra xem file hình ảnh có tồn tại không
     * 
     * @return bool
     */
    public function imageExists()
    {
        if (!$this->image_url) {
            return false;
        }

        $path = ltrim($this->image_url, '/');
        $fullPath = storage_path('app/public/' . $path);
        
        // Kiểm tra file có tồn tại không
        if (file_exists($fullPath)) {
            return true;
        }

        // Nếu không tìm thấy, thử tìm file tương tự (case-insensitive)
        $directory = dirname($fullPath);
        $filename = basename($fullPath);
        
        if (is_dir($directory)) {
            $files = scandir($directory);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    // So sánh không phân biệt hoa thường
                    if (strcasecmp($file, $filename) === 0) {
                        // Cập nhật lại đường dẫn trong database nếu tìm thấy
                        $this->image_url = dirname($path) . '/' . $file;
                        $this->saveQuietly(); // Lưu không trigger events
                        return true;
                    }
                }
            }
        }
        
        return false;
    }

    /**
     * Lấy đường dẫn đầy đủ để hiển thị ảnh
     * 
     * @return string|null
     */
    public function getImageDisplayUrl()
    {
        if (!$this->image_url) {
            return null;
        }

        // Loại bỏ dấu / ở đầu nếu có
        $path = ltrim($this->image_url, '/');
        
        // Sử dụng route storage.serve để serve ảnh trực tiếp từ storage
        // Không cần symlink, route sẽ xử lý việc đọc file từ storage/app/public
        try {
            return route('storage.serve', ['path' => $path]);
        } catch (\Exception $e) {
            // Fallback nếu route không tồn tại
            return url('/storage/' . $path);
        }
    }

    /**
     * Lấy danh sách URL của các ảnh phụ
     * 
     * @return array
     */
    public function getSubImagesDisplayUrls()
    {
        if (!$this->sub_images || !is_array($this->sub_images)) {
            return [];
        }

        $urls = [];
        foreach ($this->sub_images as $path) {
            if ($path) {
                $path = ltrim($path, '/');
                try {
                    $urls[] = route('storage.serve', ['path' => $path]);
                } catch (\Exception $e) {
                    $urls[] = url('/storage/' . $path);
                }
            }
        }

        return $urls;
    }

}