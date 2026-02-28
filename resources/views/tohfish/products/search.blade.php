@extends('layouts.app')

@section('title', 'Tìm kiếm sản phẩm - TOH fish')

@section('content')
@php
    $basePath = request()->getBasePath();
@endphp
<section class="products-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title text-center mb-3">Kết quả tìm kiếm</h1>
                @if(!empty($query))
                    <p class="text-center text-muted mb-3">
                        Tìm thấy <strong>{{ method_exists($products, 'total') ? $products->total() : 0 }}</strong> sản phẩm cho từ khóa: <strong>"{{ $query }}"</strong>
                    </p>
                @else
                    <p class="text-center text-muted mb-3">Vui lòng nhập từ khóa tìm kiếm</p>
                @endif
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
                        <form method="GET" action="{{ route('products.search') }}" id="filterForm">
                            <input type="hidden" name="q" value="{{ $query }}">
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
                                    <a href="{{ route('products.search', ['q' => $query]) }}" class="btn btn-outline-secondary w-100">
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
                @endphp
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-card">
                        <div class="product-image" style="position: relative; width: 100%; aspect-ratio: 1 / 1; overflow: hidden; background-color: #f8f9fa;">
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
                            <h5 class="product-name"><a href="{{ route('product.detail', $product->images_id ?? ($index + 1)) }}" style="text-decoration: none; color: inherit;">{{ $product->content ?? 'Sản phẩm ' . ($index + 1) }}</a></h5>
                            @if(isset($product->product_type) && $product->product_type)
                                <p class="product-type mb-1" style="color: #999; font-size: 0.875rem; margin: 0;">Sản phẩm {{ strtolower($product->product_type) }}</p>
                            @endif
                            <p class="product-price">{{ number_format($product->price ?? 135000, 0, ',', '.') }}₫</p>
                            <div class="d-flex align-items-center gap-2">
                                <input type="number" 
                                       class="form-control quantity-input" 
                                       style="max-width: 80px;" 
                                       min="1" 
                                       value="1"
                                       data-product-id="{{ $product->images_id ?? ($index + 1) }}">
                                <form action="{{ route('cart.add') }}" method="POST" class="d-inline flex-grow-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->images_id ?? ($index + 1) }}">
                                    <input type="hidden" name="name" value="{{ $product->content ?? 'Sản phẩm ' . ($index + 1) }}">
                                    <input type="hidden" name="price" value="{{ $product->price ?? 135000 }}">
                                    <input type="hidden" name="quantity" value="1" class="quantity-hidden-input">
                                    @if(isset($product->display_url) && $product->display_url)
                                        <input type="hidden" name="image" value="{{ $product->display_url }}">
                                    @endif
                                    <button class="btn btn-primary btn-sm w-100">
                                        THÊM
                                    </button>
                                </form>
                                <div class="favorite-display ms-1 {{ $isFavorite ? 'active' : '' }}" 
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
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Không tìm thấy sản phẩm nào</h4>
                    <p class="text-muted">Vui lòng thử lại với từ khóa khác</p>
                    <a href="{{ route('home') }}" class="btn btn-primary mt-3">
                        <i class="fas fa-home"></i> Về trang chủ
                    </a>
                </div>
            </div>
            @endif
        </div>
        @if(isset($products) && method_exists($products, 'hasPages') && $products->hasPages())
        <div class="row">
            <div class="col-12">
                <nav aria-label="Page navigation">
                    {{ $products->appends(request()->query())->links() }}
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

