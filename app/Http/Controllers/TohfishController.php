<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TohfishService;
use App\Services\PostService;
use App\Services\CartService;
use App\Models\JobPosition;
use App\Models\Slider;
use App\Models\Favorite;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TohfishController extends Controller
{
    /**
     * @var TohfishService
     */
    protected $tohfishService;

    /**
     * @var PostService
     */
    protected $postService;

    protected CartService $cartService;

    /**
     * Constructor - Dependency Injection
     */
    public function __construct(TohfishService $tohfishService, PostService $postService, CartService $cartService)
    {
        $this->tohfishService = $tohfishService;
        $this->postService = $postService;
        $this->cartService = $cartService;
    }

    /**
     * Hiển thị trang chủ TOH fish
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        try {
            // Sử dụng Service để lấy dữ liệu
            // Tham số đầu tiên: lấy 10 sản phẩm mới nhất để kiểm tra (view sẽ chỉ hiển thị 4)
            // Tham số thứ 2: số lượng bài viết tin tức
            // Tham số thứ 3: lấy 10 sản phẩm bán chạy để kiểm tra (view sẽ chỉ hiển thị 8)
            $data = $this->tohfishService->getHomePageData(10, 6, 10);
            
            // Lấy slider cho trang chủ
            $homeSliders = Slider::active()->byType('home')->ordered()->get();
            $data['homeSliders'] = $homeSliders;
            
            return view('tohfish.home', $data);
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@index', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return view('tohfish.home', [
                'latestPosts' => collect(),
                'latestImages' => collect(),
                'featuredPosts' => collect(),
                'bestSellingImages' => collect(),
                'homeSliders' => collect()
            ])->with('error', 'Đã xảy ra lỗi khi tải dữ liệu.');
        }
    }
    
    /**
     * Trang sản phẩm mới nhất
     * 
     * @return \Illuminate\View\View
     */
    public function productsLatest(Request $request)
    {
        try {
            // Lấy các tham số lọc từ request
            $filters = [
                'product_type' => $request->get('product_type'),
                'min_price' => $request->get('min_price'),
                'max_price' => $request->get('max_price'),
                'sort_by' => $request->get('sort_by', 'created_at'),
                'sort_order' => $request->get('sort_order', 'desc'),
            ];
            
            // Loại bỏ các giá trị rỗng
            $filters = array_filter($filters, function($value) {
                return $value !== null && $value !== '';
            });
            
            $products = $this->tohfishService->getLatestImagesPaginated(8, $filters);
            
            // Lấy danh sách các loại sản phẩm để hiển thị trong filter
            $productTypes = \App\Models\Image::select('product_type')
                ->whereNotNull('product_type')
                ->distinct()
                ->orderBy('product_type')
                ->pluck('product_type');
            
            return view('tohfish.products.latest', compact('products', 'productTypes', 'filters'));
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@productsLatest', [
                'error' => $e->getMessage()
            ]);
            $products = \App\Models\Image::whereRaw('1 = 0')->paginate(8);
            $productTypes = collect();
            $filters = [];
            return view('tohfish.products.latest', compact('products', 'productTypes', 'filters'));
        }
    }
    
    /**
     * Trang sản phẩm bán chạy
     * 
     * @return \Illuminate\View\View
     */
    public function productsBestSelling(Request $request)
    {
        try {
            // Lấy các tham số lọc từ request
            $filters = [
                'product_type' => $request->get('product_type'),
                'min_price' => $request->get('min_price'),
                'max_price' => $request->get('max_price'),
                'sort_by' => $request->get('sort_by', 'created_at'),
                'sort_order' => $request->get('sort_order', 'desc'),
            ];
            
            // Loại bỏ các giá trị rỗng
            $filters = array_filter($filters, function($value) {
                return $value !== null && $value !== '';
            });
            
            $products = $this->tohfishService->getBestSellingImagesPaginated(8, $filters);
            
            // Lấy danh sách các loại sản phẩm để hiển thị trong filter
            $productTypes = \App\Models\Image::select('product_type')
                ->whereNotNull('product_type')
                ->distinct()
                ->orderBy('product_type')
                ->pluck('product_type');
            
            return view('tohfish.products.best_selling', compact('products', 'productTypes', 'filters'));
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@productsBestSelling', [
                'error' => $e->getMessage()
            ]);
            $products = \App\Models\Image::whereRaw('1 = 0')->paginate(8);
            $productTypes = collect();
            $filters = [];
            return view('tohfish.products.best_selling', compact('products', 'productTypes', 'filters'));
        }
    }
    
    /**
     * Trang sản phẩm sơ chế
     * 
     * @return \Illuminate\View\View
     */
    public function productsSoche()
    {
        try {
            $products = $this->tohfishService->getImagesByProductTypePaginated('Sơ chế', 8);
            return view('tohfish.products.soche', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@productsSoche', [
                'error' => $e->getMessage()
            ]);
            $products = \App\Models\Image::whereRaw('1 = 0')->paginate(8);
            return view('tohfish.products.soche', compact('products'));
        }
    }
    
    /**
     * Trang sản phẩm chế biến
     * 
     * @return \Illuminate\View\View
     */
    public function productsChebien()
    {
        try {
            $products = $this->tohfishService->getImagesByProductTypePaginated('Chế biến', 8);
            return view('tohfish.products.chebien', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@productsChebien', [
                'error' => $e->getMessage()
            ]);
            $products = \App\Models\Image::whereRaw('1 = 0')->paginate(8);
            return view('tohfish.products.chebien', compact('products'));
        }
    }
    
    /**
     * Trang sản phẩm chế biến sẵn
     * 
     * @return \Illuminate\View\View
     */
    public function productsChebiensan()
    {
        try {
            $products = $this->tohfishService->getImagesByProductTypePaginated('Chế biến sẵn', 8);
            return view('tohfish.products.chebiensan', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@productsChebiensan', [
                'error' => $e->getMessage()
            ]);
            $products = \App\Models\Image::whereRaw('1 = 0')->paginate(8);
            return view('tohfish.products.chebiensan', compact('products'));
        }
    }
    
    /**
     * Trang sản phẩm khác
     * 
     * @return \Illuminate\View\View
     */
    public function productsKhac()
    {
        try {
            $products = $this->tohfishService->getImagesByProductTypePaginated('Khác', 8);
            return view('tohfish.products.khac', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@productsKhac', [
                'error' => $e->getMessage()
            ]);
            $products = \App\Models\Image::whereRaw('1 = 0')->paginate(8);
            return view('tohfish.products.khac', compact('products'));
        }
    }
    
    /**
     * Trang bún cá TOH
     * 
     * @return \Illuminate\View\View
     */
    public function productsBunca()
    {
        try {
            $products = $this->tohfishService->getImagesByProductTypePaginated('Bún cá TOH', 8);
            return view('tohfish.products.bunca', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@productsBunca', [
                'error' => $e->getMessage()
            ]);
            $products = \App\Models\Image::whereRaw('1 = 0')->paginate(8);
            return view('tohfish.products.bunca', compact('products'));
        }
    }
    
    /**
     * Trang rau gia vị
     * 
     * @return \Illuminate\View\View
     */
    public function productsRaugiavi()
    {
        try {
            $products = $this->tohfishService->getImagesByProductTypePaginated('Rau gia vị', 8);
            return view('tohfish.products.raugiavi', compact('products'));
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@productsRaugiavi', [
                'error' => $e->getMessage()
            ]);
            $products = \App\Models\Image::whereRaw('1 = 0')->paginate(8);
            return view('tohfish.products.raugiavi', compact('products'));
        }
    }
    
    /**
     * Trang giới thiệu
     */
    public function about()
    {
        return view('tohfish.about');
    }
    
    /**
     * Trang liên hệ
     */
    public function contact()
    {
        return view('tohfish.contact');
    }
    
    /**
     * Trang cách đặt hàng
     */
    public function orderGuide()
    {
        return view('tohfish.order-guide');
    }
    
    /**
     * Trang blog index
     * 
     * @return \Illuminate\View\View
     */
    public function blogIndex()
    {
        try {
            $posts = $this->tohfishService->getBlogPosts(9);
            return view('tohfish.blog.index', compact('posts'));
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@blogIndex', [
                'error' => $e->getMessage()
            ]);

            return view('tohfish.blog.index', [
                'posts' => collect()
            ])->with('error', 'Đã xảy ra lỗi khi tải dữ liệu.');
        }
    }
    
    /**
     * Trang công thức món cá
     * 
     * @return \Illuminate\View\View
     */
    public function blogCongthuc()
    {
        try {
            $posts = $this->tohfishService->getBlogPostsByCategory('congthuc', 12);
            return view('tohfish.blog.congthuc', compact('posts'));
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@blogCongthuc', [
                'error' => $e->getMessage()
            ]);
            return view('tohfish.blog.congthuc', ['posts' => collect()]);
        }
    }
    
    /**
     * Trang công thức món ngon
     * 
     * @return \Illuminate\View\View
     */
    public function blogMonngon()
    {
        try {
            $posts = $this->tohfishService->getFeaturedPosts(12);
            return view('tohfish.blog.monngon', compact('posts'));
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@blogMonngon', [
                'error' => $e->getMessage()
            ]);
            return view('tohfish.blog.monngon', ['posts' => collect()]);
        }
    }
    
    /**
     * Trang tản mạn
     * 
     * @return \Illuminate\View\View
     */
    public function blogTanman()
    {
        try {
            $posts = $this->tohfishService->getLatestPosts(12);
            return view('tohfish.blog.tanman', compact('posts'));
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@blogTanman', [
                'error' => $e->getMessage()
            ]);
            return view('tohfish.blog.tanman', ['posts' => collect()]);
        }
    }
    
    /**
     * Trang TOH Farm
     * 
     * @return \Illuminate\View\View
     */
    public function blogFarm()
    {
        try {
            $posts = $this->tohfishService->getLatestPosts(9);
            return view('tohfish.blog.farm', compact('posts'));
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@blogFarm', [
                'error' => $e->getMessage()
            ]);
            return view('tohfish.blog.farm', ['posts' => collect()]);
        }
    }
    
    /**
     * Trang TOH Tour
     * 
     * @return \Illuminate\View\View
     */
    public function blogTour()
    {
        try {
            $posts = $this->tohfishService->getLatestPosts(9);
            return view('tohfish.blog.tour', compact('posts'));
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@blogTour', [
                'error' => $e->getMessage()
            ]);
            return view('tohfish.blog.tour', ['posts' => collect()]);
        }
    }
    
    /**
     * Trang khuyến mãi
     * 
     * @return \Illuminate\View\View
     */
    public function promotion()
    {
        try {
            // Lấy sản phẩm có discount_percent > 0 hoặc product_type = 'Khuyến mãi'
            $promoProducts = \App\Models\Image::where(function($query) {
                    $query->where('discount_percent', '>', 0)
                          ->orWhere('product_type', 'Khuyến mãi');
                })
                ->orderBy('discount_percent', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(8);
            
            // Xử lý URL hình ảnh cho mỗi sản phẩm
            $imageService = app(\App\Services\ImageService::class);
            $promoProducts->getCollection()->transform(function ($product) use ($imageService) {
                $imageInfo = $imageService->getImageUrl($product);
                $product->display_url = $imageInfo['url'];
                $product->image_exists = $imageInfo['exists'];
                $product->sub_images_urls = $product->getSubImagesDisplayUrls();
                return $product;
            });
            
            // Lấy slider cho trang khuyến mãi
            $promotionSliders = Slider::active()->byType('promotion')->ordered()->get();
            return view('tohfish.promotion', compact('promoProducts', 'promotionSliders'));
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@promotion', [
                'error' => $e->getMessage()
            ]);
            // Trả về paginator rỗng thay vì collection
            $promoProducts = \App\Models\Image::whereRaw('1 = 0')->paginate(8);
            return view('tohfish.promotion', [
                'promoProducts' => $promoProducts,
                'promotionSliders' => collect()
            ]);
        }
    }
    
    /**
     * Trang tuyển dụng
     * 
     * @return \Illuminate\View\View
     */
    public function recruitment()
    {
        $jobPositions = JobPosition::orderBy('published_at', 'desc')
                                  ->orderBy('created_at', 'desc')
                                  ->get();
        return view('tohfish.recruitment', compact('jobPositions'));
    }

    /**
     * Trang chi tiết chức vụ
     * 
     * @return \Illuminate\View\View
     */
    public function jobPositionDetail($id)
    {
        $position = JobPosition::findOrFail($id);
        $otherPositions = JobPosition::where('id', '!=', $id)
                                    ->orderBy('published_at', 'desc')
                                    ->take(5)
                                    ->get();
        return view('tohfish.job-position-detail', compact('position', 'otherPositions'));
    }
    
    /**
     * Trang chính sách
     * 
     * @return \Illuminate\View\View
     */
    public function policy()
    {
        return view('tohfish.policy');
    }
    
    /**
     * Trang bảo mật
     * 
     * @return \Illuminate\View\View
     */
    public function privacy()
    {
        return view('tohfish.privacy');
    }
    
    /**
     * Trang thanh toán
     */
    public function checkout(Request $request)
    {
        $allItems = $this->cartService->all();
        
        // Xử lý các sản phẩm đã chọn từ query parameter
        $selectedIds = [];
        if ($request->has('selected') && !empty($request->selected)) {
            $selectedIds = array_map('intval', explode(',', $request->selected));
        }
        
        // Nếu có selected IDs, chỉ lấy các sản phẩm đã chọn
        if (!empty($selectedIds)) {
            $items = [];
            foreach ($allItems as $item) {
                if (in_array((int)$item['id'], $selectedIds)) {
                    $items[$item['id']] = $item;
                }
            }
        } else {
            // Nếu không có selected, lấy tất cả
            $items = $allItems;
        }
        
        // Kiểm tra có ít nhất 1 sản phẩm với số lượng >= 1 (cho phép mua từ 1 sản phẩm trở lên)
        $hasValidItems = false;
        foreach ($items as $item) {
            $quantity = (int)($item['quantity'] ?? 0);
            if ($quantity > 0) {
                $hasValidItems = true;
                break;
            }
        }
        
        if (!$hasValidItems) {
            return redirect()->route('cart')
                ->with('error', 'Giỏ hàng trống. Vui lòng thêm ít nhất 1 sản phẩm vào giỏ hàng trước khi thanh toán.');
        }
        
        // Tính tổng tiền chỉ cho các sản phẩm đã chọn
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }
        $shipping = 0;
        $total = $subtotal + $shipping;
        
        $totals = [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
        ];

        return view('tohfish.checkout', [
            'items'  => $items,
            'totals' => $totals,
            'selectedIds' => $selectedIds,
        ]);
    }

    /**
     * Tìm kiếm sản phẩm
     * 
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function searchProducts(Request $request)
    {
        try {
            $query = $request->get('q', '');
            
            // Lấy các tham số lọc từ request
            $filters = [
                'product_type' => $request->get('product_type'),
                'min_price' => $request->get('min_price'),
                'max_price' => $request->get('max_price'),
                'sort_by' => $request->get('sort_by', 'created_at'),
                'sort_order' => $request->get('sort_order', 'desc'),
            ];
            
            // Loại bỏ các giá trị rỗng
            $filters = array_filter($filters, function($value) {
                return $value !== null && $value !== '';
            });
            
            // Luôn sử dụng paginate để trả về Paginator, ngay cả khi query rỗng
            if (!empty($query)) {
                $productQuery = \App\Models\Image::where(function($q) use ($query) {
                    $q->where('content', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhere('product_type', 'LIKE', "%{$query}%");
                });
            } else {
                // Trả về query rỗng khi không có query
                $productQuery = \App\Models\Image::whereRaw('1 = 0');
            }
            
            // Áp dụng bộ lọc
            if (!empty($filters['product_type'])) {
                $productQuery->where('product_type', $filters['product_type']);
            }
            if (!empty($filters['min_price'])) {
                $productQuery->where('price', '>=', $filters['min_price']);
            }
            if (!empty($filters['max_price'])) {
                $productQuery->where('price', '<=', $filters['max_price']);
            }
            
            // Sắp xếp theo lựa chọn trong dropdown "sort_order"
            // Các giá trị nhận từ form:
            // - default: mặc định (mới nhất)
            // - popularity: mức độ phổ biến
            // - rating: điểm đánh giá
            // - newest: sản phẩm mới nhất
            // - price_asc: giá tăng dần
            // - price_desc: giá giảm dần
            $sortOption = $filters['sort_order'] ?? 'default';

            switch ($sortOption) {
                case 'price_asc':
                    // Giá từ thấp đến cao
                    $productQuery->orderBy('price', 'asc');
                    break;

                case 'price_desc':
                    // Giá từ cao xuống thấp
                    $productQuery->orderBy('price', 'desc');
                    break;

                case 'newest':
                    // Mới nhất trước
                    $productQuery->orderBy('created_at', 'desc');
                    break;

                case 'popularity':
                    // TODO: Nếu có cột/thông tin về độ phổ biến thì sắp xếp tại đây
                    // Tạm thời fallback theo ngày tạo
                    $productQuery->orderBy('created_at', 'desc');
                    break;

                case 'rating':
                    // TODO: Nếu có cột điểm đánh giá thì sắp xếp tại đây
                    // Tạm thời fallback theo ngày tạo
                    $productQuery->orderBy('created_at', 'desc');
                    break;

                case 'default':
                default:
                    // Thứ tự mặc định: mới nhất trước
                    $productQuery->orderBy('created_at', 'desc');
                    break;
            }
            
            $products = $productQuery->paginate(8);
            
            // Xử lý URL hình ảnh
            $products->getCollection()->transform(function ($product) {
                $imageInfo = app(\App\Services\ImageService::class)->getImageUrl($product);
                $product->display_url = $imageInfo['url'];
                $product->image_exists = $imageInfo['exists'];
                return $product;
            });
            
            // Lấy danh sách các loại sản phẩm để hiển thị trong filter
            $productTypes = \App\Models\Image::select('product_type')
                ->whereNotNull('product_type')
                ->distinct()
                ->orderBy('product_type')
                ->pluck('product_type');
            
            return view('tohfish.products.search', compact('products', 'query', 'productTypes', 'filters'));
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@searchProducts', [
                'error' => $e->getMessage()
            ]);
            // Trả về paginator rỗng thay vì collection
            $products = \App\Models\Image::whereRaw('1 = 0')
                ->paginate(12);
            $productTypes = collect();
            $filters = [];
            return view('tohfish.products.search', [
                'products' => $products,
                'query' => $request->get('q', ''),
                'productTypes' => $productTypes,
                'filters' => $filters
            ])->with('error', 'Đã xảy ra lỗi khi tìm kiếm.');
        }
    }

    /**
     * Trang chi tiết sản phẩm
     * 
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function productDetail($id)
    {
        try {
            $product = \App\Models\Image::findOrFail($id);
            
            // Xử lý URL hình ảnh
            $imageInfo = app(\App\Services\ImageService::class)->getImageUrl($product);
            $product->display_url = $imageInfo['url'];
            $product->image_exists = $imageInfo['exists'];
            $product->sub_images_urls = $product->getSubImagesDisplayUrls();
            
            // Lấy các sản phẩm liên quan
            $relatedProducts = \App\Models\Image::where('images_id', '!=', $id)
                ->where('product_type', $product->product_type)
                ->orderBy('created_at', 'desc')
                ->limit(4)
                ->get()
                ->map(function($item) {
                    $imageInfo = app(\App\Services\ImageService::class)->getImageUrl($item);
                    $item->display_url = $imageInfo['url'];
                    $item->image_exists = $imageInfo['exists'];
                    return $item;
                });
            
            // Lấy số lượng yêu thích và trạng thái yêu thích
            $favoriteCount = Favorite::where('product_id', $id)->count();
            $isFavorite = false;
            if (Auth::check()) {
                $isFavorite = Favorite::where('user_id', Auth::id())
                    ->where('product_id', $id)
                    ->exists();
            }
            
            return view('tohfish.products.detail', compact('product', 'relatedProducts', 'favoriteCount', 'isFavorite'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Product not found', ['id' => $id]);
            return redirect()->route('home')
                ->with('error', 'Không tìm thấy sản phẩm.');
        } catch (\Exception $e) {
            Log::error('Error in TohfishController@productDetail', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);
            return redirect()->route('home')
                ->with('error', 'Đã xảy ra lỗi khi tải sản phẩm.');
        }
    }
}

