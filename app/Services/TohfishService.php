<?php

namespace App\Services;

use App\Models\Image;
use App\Models\Post;
use App\Models\Favorite;
use App\Models\OrderItem;
use App\Services\ImageService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TohfishService
{
    /**
     * Lấy dữ liệu cho trang chủ
     * 
     * @param int $imagesLimit
     * @param int $postsLimit
     * @param int $featuredLimit
     * @return array
     */
    public function getHomePageData($imagesLimit = 8, $postsLimit = 6, $bestSellingLimit = 8)
    {
        try {
            return [
                'latestImages' => $this->getLatestImages($imagesLimit),
                'latestPosts' => $this->getLatestPosts($postsLimit),
                'featuredPosts' => $this->getFeaturedPosts(4), // Giữ nguyên 4 cho featured posts
                'bestSellingImages' => $this->getBestSellingImages($bestSellingLimit),
            ];
        } catch (\Exception $e) {
            Log::error('Error in TohfishService@getHomePageData', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Lấy hình ảnh mới nhất
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLatestImages($limit = 8)
    {
        $images = Image::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        
        // Lấy số lượng yêu thích cho tất cả sản phẩm
        $imageIds = $images->pluck('images_id')->toArray();
        $favoriteCounts = Favorite::whereIn('product_id', $imageIds)
            ->selectRaw('product_id, COUNT(*) as count')
            ->groupBy('product_id')
            ->pluck('count', 'product_id')
            ->toArray();
        
        // Xử lý URL hình ảnh theo MVC
        return $images->map(function ($image) use ($favoriteCounts) {
            $imageInfo = app(ImageService::class)->getImageUrl($image);
            $image->display_url = $imageInfo['url'];
            $image->image_exists = $imageInfo['exists'];
            $image->favorite_count = $favoriteCounts[$image->images_id] ?? 0;
            $image->sub_images_urls = $image->getSubImagesDisplayUrls();
            return $image;
        });
    }

    /**
     * Lấy hình ảnh mới nhất với phân trang
     * 
     * @param int $perPage
     * @param array $filters Các bộ lọc: product_type, min_price, max_price, sort_by
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getLatestImagesPaginated($perPage = 8, $filters = [])
    {
        $query = Image::query();
        
        // Lọc theo loại sản phẩm
        if (!empty($filters['product_type'])) {
            $query->where('product_type', $filters['product_type']);
        }
        
        // Lọc theo giá tối thiểu
        if (!empty($filters['min_price'])) {
            $query->where('price', '>=', $filters['min_price']);
        }
        
        // Lọc theo giá tối đa
        if (!empty($filters['max_price'])) {
            $query->where('price', '<=', $filters['max_price']);
        }
        
        // Sắp xếp
        // Hỗ trợ cả kiểu cũ (sort_by + sort_order = asc/desc)
        // và kiểu mới dùng sort_order với các giá trị: default, popularity, rating, newest, price_asc, price_desc
        $sortOption = $filters['sort_order'] ?? null;
        $advancedSortValues = ['default', 'popularity', 'rating', 'newest', 'price_asc', 'price_desc'];

        if ($sortOption !== null && in_array($sortOption, $advancedSortValues, true)) {
            switch ($sortOption) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                case 'default':
                case 'popularity': // tạm thời fallback theo ngày tạo
                case 'rating':     // tạm thời fallback theo ngày tạo
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            // Backward-compatible: dùng sort_by + sort_order (asc/desc)
            $sortBy = $filters['sort_by'] ?? 'created_at';
            $sortOrder = $filters['sort_order'] ?? 'desc';

            switch ($sortBy) {
                case 'price':
                    $query->orderBy('price', $sortOrder);
                    break;
                case 'name':
                    $query->orderBy('content', $sortOrder);
                    break;
                case 'created_at':
                default:
                    $query->orderBy('created_at', $sortOrder);
                    break;
            }
        }
        
        $images = $query->paginate($perPage);
        
        // Lấy số lượng yêu thích cho tất cả sản phẩm trong trang hiện tại
        $imageIds = $images->pluck('images_id')->toArray();
        $favoriteCounts = Favorite::whereIn('product_id', $imageIds)
            ->selectRaw('product_id, COUNT(*) as count')
            ->groupBy('product_id')
            ->pluck('count', 'product_id')
            ->toArray();
        
        // Xử lý URL hình ảnh theo MVC
        $images->getCollection()->transform(function ($image) use ($favoriteCounts) {
            $imageInfo = app(ImageService::class)->getImageUrl($image);
            $image->display_url = $imageInfo['url'];
            $image->image_exists = $imageInfo['exists'];
            $image->favorite_count = $favoriteCounts[$image->images_id] ?? 0;
            $image->sub_images_urls = $image->getSubImagesDisplayUrls();
            return $image;
        });
        
        return $images;
    }

    /**
     * Lấy bài viết mới nhất
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getLatestPosts($limit = 6)
    {
        $posts = Post::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        
        // Xử lý URL hình ảnh theo MVC
        return $posts->map(function ($post) {
            if ($post->image_url) {
                $post->display_url = $post->getImageDisplayUrl();
                $post->image_exists = $post->imageExists();
            }
            return $post;
        });
    }

    /**
     * Lấy bài viết nổi bật (theo lượt xem)
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFeaturedPosts($limit = 4)
    {
        $posts = Post::orderBy('view', 'desc')
            ->limit($limit)
            ->get();
        
        // Xử lý URL hình ảnh theo MVC
        return $posts->map(function ($post) {
            if ($post->image_url) {
                $post->display_url = $post->getImageDisplayUrl();
                $post->image_exists = $post->imageExists();
            }
            return $post;
        });
    }

    /**
     * Lấy sản phẩm bán chạy dựa vào số lượng đã bán trong order_items
     * 
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBestSellingImages($limit = 8)
    {
        // Lấy sản phẩm bán chạy dựa vào tổng số lượng đã bán
        // CHỈ tính các đơn hàng đã thanh toán thành công
        $bestSellingIds = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function($query) {
                $query->where('status', 'completed');
            })
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->pluck('product_id')
            ->toArray();

        // Nếu có sản phẩm đã bán, lấy theo thứ tự bán chạy
        if (!empty($bestSellingIds)) {
            // Tạo thứ tự theo mảng bestSellingIds
            $images = Image::whereIn('images_id', $bestSellingIds)
                ->get()
                ->sortBy(function($image) use ($bestSellingIds) {
                    return array_search($image->images_id, $bestSellingIds);
                })
                ->take($limit);
        } else {
            // Nếu chưa có sản phẩm nào bán được, lấy sản phẩm mới nhất
            $images = Image::orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        }
        
        // Xử lý URL hình ảnh theo MVC
        return $images->map(function ($image) {
            $imageInfo = app(ImageService::class)->getImageUrl($image);
            $image->display_url = $imageInfo['url'];
            $image->image_exists = $imageInfo['exists'];
            $image->sub_images_urls = $image->getSubImagesDisplayUrls();
            return $image;
        });
    }

    /**
     * Lấy sản phẩm bán chạy với phân trang dựa vào số lượng đã bán
     * 
     * @param int $perPage
     * @param array $filters Các bộ lọc: product_type, min_price, max_price, sort_by
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getBestSellingImagesPaginated($perPage = 8, $filters = [])
    {
        // Lấy danh sách sản phẩm bán chạy với tổng số lượng đã bán
        // CHỈ tính các đơn hàng đã thanh toán thành công
        $bestSellingQuery = OrderItem::select('product_id', DB::raw('SUM(quantity) as total_sold'))
            ->whereHas('order', function($query) {
                $query->where('status', 'completed');
            });
        
        // Áp dụng bộ lọc nếu có - join với bảng images để lọc
        if (!empty($filters['product_type']) || !empty($filters['min_price']) || !empty($filters['max_price'])) {
            $bestSellingQuery->join('images', 'order_items.product_id', '=', 'images.images_id');
            
            if (!empty($filters['product_type'])) {
                $bestSellingQuery->where('images.product_type', $filters['product_type']);
            }
            if (!empty($filters['min_price'])) {
                $bestSellingQuery->where('images.price', '>=', $filters['min_price']);
            }
            if (!empty($filters['max_price'])) {
                $bestSellingQuery->where('images.price', '<=', $filters['max_price']);
            }
        }
        
        $bestSelling = $bestSellingQuery
            ->groupBy('product_id')
            ->orderBy('total_sold', 'desc')
            ->paginate($perPage);

        // Lấy chi tiết sản phẩm
        $productIds = $bestSelling->pluck('product_id')->toArray();
        $soldCounts = $bestSelling->pluck('total_sold', 'product_id')->toArray();

        if (!empty($productIds)) {
            $imageQuery = Image::whereIn('images_id', $productIds);
            
            // Áp dụng bộ lọc trực tiếp trên Image query
            if (!empty($filters['product_type'])) {
                $imageQuery->where('product_type', $filters['product_type']);
            }
            if (!empty($filters['min_price'])) {
                $imageQuery->where('price', '>=', $filters['min_price']);
            }
            if (!empty($filters['max_price'])) {
                $imageQuery->where('price', '<=', $filters['max_price']);
            }
            
            $images = $imageQuery->get()->keyBy('images_id');

            // Sắp xếp theo lựa chọn sort_order (nếu có)
            // Các giá trị hỗ trợ: default, popularity, rating, newest, price_asc, price_desc
            $sortOption = $filters['sort_order'] ?? 'default';
            $advancedSortValues = ['default', 'popularity', 'rating', 'newest', 'price_asc', 'price_desc'];

            // Tạo collection theo thứ tự bán chạy mặc định trước
            $sortedImages = collect($productIds)
                ->map(function($id) use ($images) {
                    return $images->get($id);
                })
                ->filter();

            if (in_array($sortOption, $advancedSortValues, true)) {
                switch ($sortOption) {
                    case 'price_asc':
                        $sortedImages = $sortedImages->sortBy('price')->values();
                        break;
                    case 'price_desc':
                        $sortedImages = $sortedImages->sortByDesc('price')->values();
                        break;
                    case 'newest':
                        $sortedImages = $sortedImages->sortByDesc('created_at')->values();
                        break;
                    case 'default':
                    case 'popularity':
                    case 'rating':
                    default:
                        // giữ nguyên thứ tự bán chạy (total_sold desc)
                        break;
                }
            }

            // Tạo paginator tùy chỉnh
            $currentPage = $bestSelling->currentPage();
            $items = $sortedImages->forPage($currentPage, $perPage)->values();
        } else {
            // Nếu chưa có sản phẩm nào bán được, lấy sản phẩm theo bộ lọc + sắp xếp giống trang mới nhất
            $imageQuery = Image::query();
            
            if (!empty($filters['product_type'])) {
                $imageQuery->where('product_type', $filters['product_type']);
            }
            if (!empty($filters['min_price'])) {
                $imageQuery->where('price', '>=', $filters['min_price']);
            }
            if (!empty($filters['max_price'])) {
                $imageQuery->where('price', '<=', $filters['max_price']);
            }

            $sortOption = $filters['sort_order'] ?? 'default';
            switch ($sortOption) {
                case 'price_asc':
                    $imageQuery->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $imageQuery->orderBy('price', 'desc');
                    break;
                case 'newest':
                case 'default':
                case 'popularity':
                case 'rating':
                default:
                    $imageQuery->orderBy('created_at', 'desc');
                    break;
            }

            $images = $imageQuery->paginate($perPage);
            
            // Lấy số lượng yêu thích cho tất cả sản phẩm trong trang hiện tại
            $imageIds = $images->pluck('images_id')->toArray();
            $favoriteCounts = Favorite::whereIn('product_id', $imageIds)
                ->selectRaw('product_id, COUNT(*) as count')
                ->groupBy('product_id')
                ->pluck('count', 'product_id')
                ->toArray();
            
            // Xử lý URL hình ảnh theo MVC
            $images->getCollection()->transform(function ($image) use ($favoriteCounts) {
                $imageInfo = app(ImageService::class)->getImageUrl($image);
                $image->display_url = $imageInfo['url'];
                $image->image_exists = $imageInfo['exists'];
                $image->favorite_count = $favoriteCounts[$image->images_id] ?? 0;
                $image->sub_images_urls = $image->getSubImagesDisplayUrls();
                return $image;
            });
            
            return $images;
        }

        // Lấy số lượng yêu thích cho tất cả sản phẩm trong trang hiện tại
        $imageIds = $items->pluck('images_id')->toArray();
        $favoriteCounts = Favorite::whereIn('product_id', $imageIds)
            ->selectRaw('product_id, COUNT(*) as count')
            ->groupBy('product_id')
            ->pluck('count', 'product_id')
            ->toArray();
        
        // Xử lý URL hình ảnh theo MVC và thêm thông tin bán chạy
        $items->transform(function ($image) use ($favoriteCounts, $soldCounts) {
            $imageInfo = app(ImageService::class)->getImageUrl($image);
            $image->display_url = $imageInfo['url'];
            $image->image_exists = $imageInfo['exists'];
            $image->favorite_count = $favoriteCounts[$image->images_id] ?? 0;
            $image->total_sold = $soldCounts[$image->images_id] ?? 0;
            $image->sub_images_urls = $image->getSubImagesDisplayUrls();
            return $image;
        });

        // Tạo paginator từ collection
        $total = count($productIds);
        $paginator = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return $paginator;
    }

    /**
     * Lấy bài viết cho blog với phân trang
     * 
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getBlogPosts($perPage = 9)
    {
        $posts = Post::orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        // Xử lý URL hình ảnh theo MVC
        $posts->getCollection()->transform(function ($post) {
            if ($post->image_url) {
                $post->display_url = $post->getImageDisplayUrl();
                $post->image_exists = $post->imageExists();
            }
            return $post;
        });
        
        return $posts;
    }

    /**
     * Lấy bài viết theo danh mục blog
     * 
     * @param string $category
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getBlogPostsByCategory($category, $limit = 10)
    {
        // Có thể mở rộng để filter theo category trong tương lai
        $posts = Post::orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        
        // Xử lý URL hình ảnh theo MVC
        return $posts->map(function ($post) {
            if ($post->image_url) {
                $post->display_url = $post->getImageDisplayUrl();
                $post->image_exists = $post->imageExists();
            }
            return $post;
        });
    }

    /**
     * Lấy sản phẩm theo loại (product_type)
     * 
     * @param string $productType
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getImagesByProductType($productType, $limit = 12)
    {
        $images = Image::where('product_type', $productType)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        
        // Lấy số lượng yêu thích cho tất cả sản phẩm
        $imageIds = $images->pluck('images_id')->toArray();
        $favoriteCounts = Favorite::whereIn('product_id', $imageIds)
            ->selectRaw('product_id, COUNT(*) as count')
            ->groupBy('product_id')
            ->pluck('count', 'product_id')
            ->toArray();
        
        // Xử lý URL hình ảnh theo MVC
        return $images->map(function ($image) use ($favoriteCounts) {
            $imageInfo = app(ImageService::class)->getImageUrl($image);
            $image->display_url = $imageInfo['url'];
            $image->image_exists = $imageInfo['exists'];
            $image->favorite_count = $favoriteCounts[$image->images_id] ?? 0;
            $image->sub_images_urls = $image->getSubImagesDisplayUrls();
            return $image;
        });
    }

    /**
     * Lấy sản phẩm theo loại (product_type) với phân trang
     * 
     * @param string $productType
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getImagesByProductTypePaginated($productType, $perPage = 8)
    {
        $images = Image::where('product_type', $productType)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
        
        // Lấy số lượng yêu thích cho tất cả sản phẩm trong trang hiện tại
        $imageIds = $images->pluck('images_id')->toArray();
        $favoriteCounts = Favorite::whereIn('product_id', $imageIds)
            ->selectRaw('product_id, COUNT(*) as count')
            ->groupBy('product_id')
            ->pluck('count', 'product_id')
            ->toArray();
        
        // Xử lý URL hình ảnh theo MVC
        $images->getCollection()->transform(function ($image) use ($favoriteCounts) {
            $imageInfo = app(ImageService::class)->getImageUrl($image);
            $image->display_url = $imageInfo['url'];
            $image->image_exists = $imageInfo['exists'];
            $image->favorite_count = $favoriteCounts[$image->images_id] ?? 0;
            $image->sub_images_urls = $image->getSubImagesDisplayUrls();
            return $image;
        });
        
        return $images;
    }
}

