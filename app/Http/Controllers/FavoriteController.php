<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FavoriteController extends Controller
{
    /**
     * Thêm/xóa sản phẩm yêu thích
     */
    public function toggle($productId)
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Vui lòng đăng nhập để thêm sản phẩm yêu thích'
            ], 401);
        }

        try {
            $userId = Auth::id();
            $favorite = Favorite::where('user_id', $userId)
                ->where('product_id', $productId)
                ->first();

            if ($favorite) {
                // Xóa khỏi yêu thích
                $favorite->delete();
                $isFavorite = false;
                $message = 'Đã xóa khỏi danh sách yêu thích';
            } else {
                // Thêm vào yêu thích
                Favorite::create([
                    'user_id' => $userId,
                    'product_id' => $productId,
                ]);
                $isFavorite = true;
                $message = 'Đã thêm vào danh sách yêu thích';
            }

            // Lấy số lượng yêu thích mới
            $favoriteCount = Favorite::where('product_id', $productId)->count();

            return response()->json([
                'success' => true,
                'is_favorite' => $isFavorite,
                'favorite_count' => $favoriteCount,
                'message' => $message
            ]);
        } catch (\Exception $e) {
            Log::error('Error toggling favorite: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'product_id' => $productId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kiểm tra sản phẩm có trong yêu thích không
     */
    public function check($productId)
    {
        if (!Auth::check()) {
            return response()->json(['is_favorite' => false]);
        }

        $isFavorite = Favorite::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->exists();

        return response()->json(['is_favorite' => $isFavorite]);
    }

    /**
     * Hiển thị danh sách sản phẩm yêu thích
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $favorites = Favorite::where('user_id', Auth::id())
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->get();

        // Xử lý URL hình ảnh cho mỗi sản phẩm
        $imageService = app(\App\Services\ImageService::class);
        $favorites = $favorites->map(function ($favorite) use ($imageService) {
            if ($favorite->product) {
                $imageInfo = $imageService->getImageUrl($favorite->product);
                $favorite->product->display_url = $imageInfo['url'];
                $favorite->product->image_exists = $imageInfo['exists'];
                $favorite->product->sub_images_urls = $favorite->product->getSubImagesDisplayUrls();
            }
            return $favorite;
        });

        return view('tohfish.favorites.index', compact('favorites'));
    }

    /**
     * Lấy danh sách sản phẩm yêu thích dạng JSON (cho dropdown)
     */
    public function list()
    {
        if (!Auth::check()) {
            return response()->json(['favorites' => []]);
        }

        $favorites = Favorite::where('user_id', Auth::id())
            ->with('product')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $imageService = app(\App\Services\ImageService::class);
        $favoritesData = $favorites->map(function ($favorite) use ($imageService) {
            if ($favorite->product) {
                $imageInfo = $imageService->getImageUrl($favorite->product);
                return [
                    'id' => $favorite->product->images_id,
                    'name' => $favorite->product->content,
                    'image_url' => $imageInfo['url'],
                    'price' => $favorite->product->price,
                ];
            }
            return null;
        })->filter();

        return response()->json(['favorites' => $favoritesData]);
    }
}

