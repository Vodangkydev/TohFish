@extends('layouts.app')

@section('title', 'Sản phẩm bán chạy - TOH fish')

@section('content')
@php
    $basePath = request()->getBasePath();
@endphp
<section class="products-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title text-center mb-4">Sản phẩm bán chạy</h1>
            </div>
        </div>
        
        <!-- Bộ lọc sản phẩm -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center" style="cursor: pointer;" id="filterToggle">
                        <h5 class="mb-0">
                            <i class="fas fa-filter"></i> Bộ lọc sản phẩm
                        </h5>
                        <i class="fas fa-chevron-down" id="filterToggleIcon"></i>
                    </div>
                    <div class="card-body" id="filterContent" style="display: none;">
                        <form method="GET" action="{{ route('products.best_selling') }}" id="filterForm">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-3">
                                    <label for="product_type" class="form-label mb-1">Loại sản phẩm</label>
                                    <select class="form-select" id="product_type" name="product_type">
                                        <option value="">Tất cả</option>
                                        @foreach($productTypes ?? [] as $type)
                                            <option value="{{ $type }}" {{ (isset($filters['product_type']) && $filters['product_type'] == $type) ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="min_price" class="form-label mb-1">Giá từ (₫)</label>
                                    <input type="number" class="form-control" id="min_price" name="min_price" 
                                           placeholder="Ví dụ: 50000" value="{{ $filters['min_price'] ?? '' }}" 
                                           min="0" step="1000">
                                </div>
                                <div class="col-md-2">
                                    <label for="max_price" class="form-label mb-1">Đến (₫)</label>
                                    <input type="number" class="form-control" id="max_price" name="max_price" 
                                           placeholder="Ví dụ: 500000" value="{{ $filters['max_price'] ?? '' }}" 
                                           min="0" step="1000">
                                </div>
                                <div class="col-md-3">
                                    <label for="sort_order" class="form-label mb-1">Sắp xếp theo</label>
                                    <select class="form-select" id="sort_order" name="sort_order">
                                        <option value="default" {{ (isset($filters['sort_order']) && $filters['sort_order'] == 'default') ? 'selected' : (!isset($filters['sort_order']) ? 'selected' : '') }}>Thứ tự mặc định</option>
                                        <option value="popularity" {{ (isset($filters['sort_order']) && $filters['sort_order'] == 'popularity') ? 'selected' : '' }}>Thứ tự theo mức độ phổ biến</option>
                                        <option value="rating" {{ (isset($filters['sort_order']) && $filters['sort_order'] == 'rating') ? 'selected' : '' }}>Thứ tự theo điểm đánh giá</option>
                                        <option value="newest" {{ (isset($filters['sort_order']) && $filters['sort_order'] == 'newest') ? 'selected' : '' }}>Mới nhất</option>
                                        <option value="price_asc" {{ (isset($filters['sort_order']) && $filters['sort_order'] == 'price_asc') ? 'selected' : '' }}>Thứ tự theo giá: thấp đến cao</option>
                                        <option value="price_desc" {{ (isset($filters['sort_order']) && $filters['sort_order'] == 'price_desc') ? 'selected' : '' }}>Thứ tự theo giá: cao xuống thấp</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary w-100 mb-2">
                                        <i class="fas fa-filter"></i> Lọc
                                    </button>
                                    <a href="{{ route('products.best_selling') }}" class="btn btn-outline-secondary w-100">
                                        <i class="fas fa-redo"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            @if(isset($products) && $products->isNotEmpty())
                @foreach($products as $index => $product)
                @php
                    $favoriteCount = \App\Models\Favorite::where('product_id', $product->images_id)->count();
                    $isFavorite = false;
                    if (Auth::check()) {
                        $isFavorite = \App\Models\Favorite::where('user_id', Auth::id())
                            ->where('product_id', $product->images_id)
                            ->exists();
                    }
                    // Tính toán giá và giảm giá
                    $originalPrice = $product->price ?? 139000;
                    $discountPercent = $product->discount_percent ?? 0;
                    if ($discountPercent <= 0 || $discountPercent > 100) {
                        $discountPercent = 0;
                    }
                    $discountPrice = $discountPercent > 0 
                        ? $originalPrice * (1 - $discountPercent / 100) 
                        : $originalPrice;
                    // Kiểm tra sản phẩm mới (trong 30 ngày gần đây)
                    $isNew = $product->created_at && $product->created_at->diffInDays(now()) <= 30;
                    $currentPage = method_exists($products, 'currentPage') ? $products->currentPage() : 1;
                    $showNewBadge = $isNew && ($currentPage == 1 && $index < 8);
                @endphp
                <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                    <div class="product-card product-card-processed">
                        <div class="product-image" style="position: relative; width: 100%; aspect-ratio: 1 / 1; overflow: hidden; background-color: #f8f9fa;">
                            @if($discountPercent > 0)
                            <div class="discount-badge" style="position: absolute; top: 10px; right: 10px; background-color: #ffc107; color: #000; padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                GIẢM {{ $discountPercent }}%
                            </div>
                            @elseif($showNewBadge)
                            <div style="position: absolute; top: 10px; right: 10px; background-color: #dc3545; color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                MỚI
                            </div>
                            @endif
                            <a href="{{ route('product.detail', $product->images_id ?? ($index + 1)) }}" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                                @if(isset($product->display_url) && $product->display_url && ($product->image_exists ?? false))
                                <img src="{{ $product->display_url }}" alt="{{ $product->content ?? 'Sản phẩm ' . ($index + 1) }}" class="img-fluid product-main-image" style="width: 100%; height: 100%; object-fit: cover; display: block;" data-product-id="{{ $product->images_id ?? ($index + 1) }}" data-original-src="{{ $product->display_url }}" data-sub-image="{{ isset($product->sub_images_urls) && is_array($product->sub_images_urls) && count($product->sub_images_urls) > 0 ? $product->sub_images_urls[0] : '' }}">
                                @else
                                <img src="{{ asset('images/home/' . (($index % 14) + 1) . '.png') }}" alt="{{ $product->content ?? 'Sản phẩm ' . ($index + 1) }}" class="img-fluid product-main-image" style="width: 100%; height: 100%; object-fit: cover; display: block;" data-product-id="{{ $product->images_id ?? ($index + 1) }}" data-original-src="{{ asset('images/home/' . (($index % 14) + 1) . '.png') }}" data-sub-image="">
                                @endif
                            </a>
                            {{-- Ẩn ảnh phụ - chỉ hiển thị khi hover vào ảnh chính --}}
                        </div>
                        <div class="product-info" style="margin-top: 12px;">
                            <h5 class="product-name"><a href="{{ route('product.detail', $product->images_id ?? ($index + 1)) }}" style="text-decoration: none; color: inherit;">{{ $product->content ?? 'Sản phẩm bán chạy ' . ($index + 1) }}</a></h5>
                            @if(isset($product->product_type) && $product->product_type)
                                <p class="product-type mb-1" style="color: #999; font-size: 0.875rem; margin: 0;">Sản phẩm {{ strtolower($product->product_type) }}</p>
                            @endif
                            @if($discountPercent > 0)
                            <div class="price-group">
                                <span class="product-price" style="font-size:1.25rem; font-weight:700; color:#ff6b00; margin-right:10px;">
                                    {{ number_format($discountPrice, 0, ',', '.') }}₫
                                </span>
                                <span class="old-price" style="text-decoration: line-through; color: #999; font-size: 0.875rem;">
                                    {{ number_format($originalPrice, 0, ',', '.') }}₫
                                </span>
                            </div>
                            @else
                            <p class="product-price">{{ number_format($originalPrice, 0, ',', '.') }}₫</p>
                            @endif
                            <div class="d-flex align-items-center gap-2">
                                <input type="number" 
                                       class="form-control quantity-input" 
                                       style="max-width: 80px;" 
                                       min="1" 
                                       value="1"
                                       data-product-id="{{ $product->images_id ?? ($index + 1) }}">
                                <form action="{{ route('cart.add') }}" method="POST" class="d-inline flex-grow-1 me-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->images_id ?? ($index + 1) }}">
                                    <input type="hidden" name="name" value="{{ $product->content ?? 'Sản phẩm bán chạy ' . ($index + 1) }}">
                                    <input type="hidden" name="price" value="{{ round($discountPrice) }}">
                                    <input type="hidden" name="quantity" value="1" class="quantity-hidden-input">
                                    @if(isset($product->display_url) && $product->display_url)
                                        <input type="hidden" name="image" value="{{ $product->display_url }}">
                                    @endif
                                    <button class="btn btn-primary btn-sm w-100">
                                        THÊM
                                    </button>
                                </form>
                                <div class="favorite-display {{ $isFavorite ? 'active' : '' }}" 
                                     data-product-id="{{ $product->images_id }}"
                                     onclick="toggleFavoriteDetail({{ $product->images_id }}, this)"
                                     title="{{ $isFavorite ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}">
                                    <i class="{{ $isFavorite ? 'fas' : 'far' }} fa-heart"></i>
                                    <span class="favorite-count">{{ $favoriteCount }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
            @for($i = 1; $i <= 8; $i++)
            <div class="col-lg-3 col-md-3 col-sm-6 mb-4">
                <div class="product-card product-card-processed">
                    <div class="product-image" style="position: relative; width: 100%; aspect-ratio: 1 / 1; overflow: hidden; background-color: #f8f9fa;">
                        <a href="{{ route('product.detail', $i) }}" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                            <img src="{{ asset('images/home/' . (($i % 14) + 1) . '.png') }}" alt="Sản phẩm {{ $i }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        </a>
                    </div>
                    <div class="product-info">
                        <h5 class="product-name"><a href="{{ route('product.detail', $i) }}" style="text-decoration: none; color: inherit;">Sản phẩm bán chạy {{ $i }}</a></h5>
                        <p class="product-price">139,000₫</p>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" 
                                   class="form-control quantity-input" 
                                   style="max-width: 80px;" 
                                   min="1" 
                                   value="1"
                                   data-product-id="{{ $i }}">
                            <form action="{{ route('cart.add') }}" method="POST" class="d-inline flex-grow-1 me-1">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $i }}">
                                <input type="hidden" name="name" value="Sản phẩm bán chạy {{ $i }}">
                                <input type="hidden" name="price" value="139000">
                                <input type="hidden" name="quantity" value="1" class="quantity-hidden-input">
                                <button class="btn btn-primary btn-sm w-100">
                                    THÊM
                                </button>
                            </form>
                            <div class="favorite-display" 
                                 data-product-id="{{ $i }}"
                                 onclick="toggleFavoriteDetail({{ $i }}, this)"
                                 title="Thêm vào yêu thích">
                                <i class="far fa-heart"></i>
                                <span class="favorite-count">0</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endfor
            @endif
        </div>
        <!-- Phân trang -->
        @if(isset($products) && method_exists($products, 'hasPages') && $products->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        {{-- Nút Trước --}}
                        @if($products->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Trước</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $products->appends(request()->query())->previousPageUrl() }}">Trước</a>
                            </li>
                        @endif

                        {{-- Các số trang --}}
                        @php
                            $currentPage = $products->currentPage();
                            $lastPage = $products->lastPage();
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($lastPage, $currentPage + 2);
                        @endphp

                        {{-- Hiển thị trang đầu nếu không ở gần --}}
                        @if($startPage > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ $products->appends(request()->query())->url(1) }}">1</a>
                            </li>
                            @if($startPage > 2)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                        @endif

                        {{-- Hiển thị các trang xung quanh trang hiện tại --}}
                        @for($page = $startPage; $page <= $endPage; $page++)
                            @if($page == $currentPage)
                                <li class="page-item active">
                                    <span class="page-link">{{ $page }}</span>
                                </li>
                            @else
                                <li class="page-item">
                                    <a class="page-link" href="{{ $products->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                </li>
                            @endif
                        @endfor

                        {{-- Hiển thị trang cuối nếu không ở gần --}}
                        @if($endPage < $lastPage)
                            @if($endPage < $lastPage - 1)
                                <li class="page-item disabled">
                                    <span class="page-link">...</span>
                                </li>
                            @endif
                            <li class="page-item">
                                <a class="page-link" href="{{ $products->appends(request()->query())->url($lastPage) }}">{{ $lastPage }}</a>
                            </li>
                        @endif

                        {{-- Nút Sau --}}
                        @if($products->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $products->appends(request()->query())->nextPageUrl() }}">Sau</a>
                            </li>
                        @else
                            <li class="page-item disabled">
                                <span class="page-link">Sau</span>
                            </li>
                        @endif
                    </ul>
                </nav>
            </div>
        </div>
        @endif
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý toggle bộ lọc
    const filterToggle = document.getElementById('filterToggle');
    const filterContent = document.getElementById('filterContent');
    const filterToggleIcon = document.getElementById('filterToggleIcon');
    
    if (filterToggle && filterContent) {
        filterToggle.addEventListener('click', function() {
            if (filterContent.style.display === 'none') {
                filterContent.style.display = 'block';
                filterToggleIcon.classList.remove('fa-chevron-down');
                filterToggleIcon.classList.add('fa-chevron-up');
            } else {
                filterContent.style.display = 'none';
                filterToggleIcon.classList.remove('fa-chevron-up');
                filterToggleIcon.classList.add('fa-chevron-down');
            }
        });
    }
    
    
    // Xử lý hover vào ảnh chính để chuyển sang ảnh phụ
    const mainImages = document.querySelectorAll('.product-main-image');
    
    mainImages.forEach(function(mainImage) {
        const originalSrc = mainImage.getAttribute('data-original-src');
        const subImageSrc = mainImage.getAttribute('data-sub-image');
        
        // Chỉ xử lý nếu có ảnh phụ
        if (subImageSrc && subImageSrc !== '') {
            // Lắng nghe sự kiện trực tiếp trên ảnh chính
            mainImage.addEventListener('mouseenter', function() {
                if (mainImage.src !== subImageSrc) {
                    mainImage.src = subImageSrc;
                }
            });
            
            mainImage.addEventListener('mouseleave', function() {
                if (mainImage.src !== originalSrc) {
                    mainImage.src = originalSrc;
                }
            });
            
            // Cũng lắng nghe trên thẻ a chứa ảnh để đảm bảo hoạt động
            const parentLink = mainImage.closest('a');
            if (parentLink) {
                parentLink.addEventListener('mouseenter', function() {
                    if (mainImage.src !== subImageSrc) {
                        mainImage.src = subImageSrc;
                    }
                });
                
                parentLink.addEventListener('mouseleave', function() {
                    if (mainImage.src !== originalSrc) {
                        mainImage.src = originalSrc;
                    }
                });
            }
        }
    });
});
</script>
@endsection

