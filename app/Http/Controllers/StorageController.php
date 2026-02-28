<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class StorageController extends Controller
{
    /**
     * Serve ảnh từ storage/app/public
     * Route: /storage/{path}
     * 
     * @param string $path
     * @return \Illuminate\Http\Response
     */
    public function serve($path)
    {
        try {
            // Loại bỏ các ký tự nguy hiểm
            $path = str_replace('..', '', $path);
            $path = ltrim($path, '/');
            
            // Đường dẫn đầy đủ đến file
            $fullPath = storage_path('app/public/' . $path);
            
            // Kiểm tra file có tồn tại không
            if (!File::exists($fullPath)) {
                abort(404, 'File not found');
            }
            
            // Kiểm tra file có phải là file hợp lệ không
            if (!File::isFile($fullPath)) {
                abort(404, 'Not a file');
            }
            
            // Lấy mime type
            $mimeType = File::mimeType($fullPath);
            
            // Đọc file
            $file = File::get($fullPath);
            
            // Trả về response với headers phù hợp
            return response($file, 200)
                ->header('Content-Type', $mimeType)
                ->header('Content-Length', File::size($fullPath))
                ->header('Cache-Control', 'public, max-age=31536000')
                ->header('Expires', gmdate('D, d M Y H:i:s', time() + 31536000) . ' GMT');
                
        } catch (\Exception $e) {
            Log::error('Error serving storage file: ' . $e->getMessage(), [
                'path' => $path,
                'trace' => $e->getTraceAsString()
            ]);
            abort(404, 'File not found');
        }
    }
}

