@extends('layouts.app')

@section('title', 'Khuyến Mãi - TOH fish')

@section('content')
<section class="promotion-section">
    <!-- Slider Khuyến Mãi - Full Width -->
    <div class="promo-carousel-fullwidth mb-5">
        <div class="container-fluid px-0">
            <div class="row g-0">
                <div class="col-12">
                    <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
                    <div class="carousel-inner">
                        @if(isset($promotionSliders) && $promotionSliders->isNotEmpty())
                            @foreach($promotionSliders as $index => $slider)
                            <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                                <div class="promo-slide promo-card text-white p-4 rounded shadow position-relative" 
                                     style="{{ $slider->background_color ? 'background: ' . $slider->background_color . ';' : 'background: linear-gradient(135deg, #ff6b00 0%, #ff9800 100%);' }}">
                                    <div class="promo-slide-body text-center">
                                        @if($slider->icon)
                                        <div class="promo-icon mb-3">
                                            <i class="{{ $slider->icon }} fa-3x"></i>
                                        </div>
                                        @endif
                                        @if($slider->image_url)
                                        <div class="mb-3">
                                            <img src="{{ route('storage.serve', ['path' => $slider->image_url]) }}" 
                                                 alt="{{ $slider->title }}" 
                                                 style="max-width: 200px; max-height: 200px; border-radius: 8px;">
                                        </div>
                                        @endif
                                        <h3 class="promo-slide-title text-white mb-3">{{ $slider->title }}</h3>
                                        @if($slider->description)
                                        <p class="promo-slide-desc text-white mb-4">{{ $slider->description }}</p>
                                        @endif
                                        <div class="hero-buttons">
                                            @if($slider->link && $slider->button_text)
                                            <a href="{{ $slider->link }}" class="btn btn-light btn-lg me-3">{{ $slider->button_text }}</a>
                                            @endif
                                            @if($slider->link_2 && $slider->button_text_2)
                                            <a href="{{ $slider->link_2 }}" class="btn btn-outline-light btn-lg">{{ $slider->button_text_2 }}</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <!-- Default slide nếu không có slider -->
                            <div class="carousel-item active">
                                <div class="promo-slide promo-card text-white p-4 rounded shadow position-relative" style="background: linear-gradient(135deg, #ff6b00 0%, #ff9800 100%);">
                                    <div class="promo-slide-body text-center">
                                        <div class="promo-icon mb-3">
                                            <i class="fas fa-gift fa-3x"></i>
                                        </div>
                                        <h3 class="promo-slide-title text-white mb-3">GIẢM 3% KHI ĐẶT HÀNG QUA WEB</h3>
                                        <p class="promo-slide-desc text-white mb-3">Với mã khuyến mãi: <strong class="promo-code-badge">TOH3</strong></p>
                                        <p class="promo-slide-desc text-white mb-4">Áp dụng cho đơn hàng từ 600.000₫ trở lên</p>
                                        <a href="{{ route('products.soche') }}" class="btn btn-light btn-lg">Mua Ngay</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Carousel Controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#promoCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#promoCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sản Phẩm Đang Khuyến Mãi -->
    <div class="container">
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="section-title text-center mb-5">Sản Phẩm Đang Khuyến Mãi</h2>
            </div>
        </div>
        <div class="row g-4">
            @if(isset($promoProducts) && $promoProducts->isNotEmpty())
                @foreach($promoProducts as $index => $product)
                @php
                    $productId = $product->images_id;
                    $originalPrice = $product->price ?? 139000;
                    $discountPercent = $product->discount_percent ?? 0;
                    if ($discountPercent <= 0 || $discountPercent > 100) {
                        $discountPercent = 0;
                    }
                    $discountPrice = $discountPercent > 0 
                        ? $originalPrice * (1 - $discountPercent / 100) 
                        : $originalPrice;
                    $favoriteCount = \App\Models\Favorite::where('product_id', $productId)->count();
                    $isFavorite = false;
                    if (Auth::check()) {
                        $isFavorite = \App\Models\Favorite::where('user_id', Auth::id())
                            ->where('product_id', $productId)
                            ->exists();
                    }
                @endphp
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="product-card product-card-promo">
                    <div class="product-image" style="position: relative; width: 100%; aspect-ratio: 1 / 1; overflow: hidden; background-color: #f8f9fa;">
                        @if($discountPercent > 0)
                        <div class="discount-badge">
                            GIẢM {{ $discountPercent }}%
                        </div>
                        @endif
                        <a href="{{ route('product.detail', $productId) }}" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                            @if(isset($product->display_url) && $product->display_url && ($product->image_exists ?? false))
                            <img src="{{ $product->display_url }}" alt="{{ $product->content ?? 'Sản phẩm khuyến mãi' }}" class="img-fluid product-main-image" style="width: 100%; height: 100%; object-fit: cover; display: block;" data-product-id="{{ $productId }}" data-original-src="{{ $product->display_url }}" data-sub-image="{{ isset($product->sub_images_urls) && is_array($product->sub_images_urls) && count($product->sub_images_urls) > 0 ? $product->sub_images_urls[0] : '' }}">
                            @else
                            <img src="{{ asset('images/home/' . (($index % 14) + 1) . '.png') }}" alt="{{ $product->content ?? 'Sản phẩm khuyến mãi' }}" class="img-fluid product-main-image" style="width: 100%; height: 100%; object-fit: cover; display: block;" data-product-id="{{ $productId }}" data-original-src="{{ asset('images/home/' . (($index % 14) + 1) . '.png') }}" data-sub-image="">
                            @endif
                        </a>
                    </div>
                    <div class="product-info">
                        <h5 class="product-name"><a href="{{ route('product.detail', $productId) }}" style="text-decoration: none; color: inherit;">{{ Str::limit($product->content ?? 'Sản phẩm khuyến mãi', 50) }}</a></h5>
                        @if(isset($product->product_type) && $product->product_type)
                            <p class="product-type mb-1" style="color: #999; font-size: 0.875rem; margin: 0;">Sản phẩm {{ strtolower($product->product_type) }}</p>
                        @endif
                        <div class="price-group">
                            <span class="product-price" style="font-size:1.25rem; font-weight:700; color:#ff6b00; margin-right:10px;">
                                {{ number_format($discountPrice, 0, ',', '.') }}₫
                            </span>
                            <span class="old-price">
                                {{ number_format($originalPrice, 0, ',', '.') }}₫
                            </span>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <input type="number" 
                                   class="form-control quantity-input" 
                                   style="max-width: 80px;" 
                                   min="1" 
                                   value="1"
                                   data-product-id="{{ $productId }}">
                            <form action="{{ route('cart.add') }}" method="POST" class="d-inline flex-grow-1 me-1">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $productId }}">
                                <input type="hidden" name="name" value="{{ $product->content ?? 'Sản phẩm khuyến mãi' }}">
                                <input type="hidden" name="price" value="{{ round($discountPrice) }}">
                                <input type="hidden" name="quantity" value="1" class="quantity-hidden-input">
                                @if(isset($product->display_url) && $product->display_url)
                                    <input type="hidden" name="image" value="{{ $product->display_url }}">
                                @endif
                                <button type="submit" class="btn btn-primary btn-sm w-100">
                                    THÊM
                                </button>
                            </form>
                            <div class="favorite-display {{ $isFavorite ? 'active' : '' }}" 
                                 data-product-id="{{ $productId }}"
                                 onclick="toggleFavoriteDetail({{ $productId }}, this)"
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
            @for($i = 1; $i <= 6; $i++)
            <div class="col-lg-3 col-md-6 col-sm-6">
                <div class="product-card product-card-promo">
                    <div class="product-image">
                        <img src="{{ asset('images/bai-viet/bai-viet-' . (($i % 16) + 1) . '.png') }}" alt="Sản phẩm {{ $i }}" class="img-fluid">
                    </div>
                    <div class="product-info">
                        <h5 class="product-name"><a href="#" style="text-decoration: none; color: inherit;">Chả cá riềng nghệ - Cá Lóc Bông 350g/gói</a></h5>
                        <div class="price-group">
                            <span class="old-price">139,000₫</span>
                            <span class="product-price">135,000₫</span>
                        </div>
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
                                <input type="hidden" name="name" value="Chả cá riềng nghệ - Cá Lóc Bông 350g/gói">
                                <input type="hidden" name="price" value="135000">
                                <input type="hidden" name="quantity" value="1" class="quantity-hidden-input">
                                <button type="submit" class="btn btn-primary btn-sm w-100">
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
        @if(isset($promoProducts) && method_exists($promoProducts, 'hasPages') && $promoProducts->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        {{-- Nút Trước --}}
                        @if($promoProducts->onFirstPage())
                            <li class="page-item disabled">
                                <span class="page-link">Trước</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ $promoProducts->previousPageUrl() }}">Trước</a>
                            </li>
                        @endif

                        {{-- Các số trang --}}
                        @php
                            $currentPage = $promoProducts->currentPage();
                            $lastPage = $promoProducts->lastPage();
                            $startPage = max(1, $currentPage - 2);
                            $endPage = min($lastPage, $currentPage + 2);
                        @endphp

                        {{-- Hiển thị trang đầu nếu không ở gần --}}
                        @if($startPage > 1)
                            <li class="page-item">
                                <a class="page-link" href="{{ $promoProducts->url(1) }}">1</a>
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
                                    <a class="page-link" href="{{ $promoProducts->url($page) }}">{{ $page }}</a>
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
                                <a class="page-link" href="{{ $promoProducts->url($lastPage) }}">{{ $lastPage }}</a>
                            </li>
                        @endif

                        {{-- Nút Sau --}}
                        @if($promoProducts->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $promoProducts->nextPageUrl() }}">Sau</a>
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

<style>

.promo-code-badge {
    background: white;
    color: #ff6b00;
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 1.1rem;
    font-weight: 700;
    display: inline-block;
    margin-left: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
    letter-spacing: 0.5px;
}


.promo-slide {
    height: 460px;
    width: 100%;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    color: #fff;
    border-radius: 12px;
    box-sizing: border-box;
}

.promo-slide-body {
    max-width: 760px;
    width: 100%;
    text-align: center;
    padding: 20px 15px;
    overflow: visible;
    margin: 0 auto;
}

.promo-slide-title {
    font-size: 1.6rem;
    font-weight: 700;
    color: #fff;
}

.promo-slide-desc {
    font-size: 1.05rem;
    color: #fff;
}

.product-card-promo .old-price {
    text-decoration: line-through;
    color: #999;
    font-size: 0.9rem;
    margin-right: 10px;
}

.price-group {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.discount-badge {
    position: absolute;
    top: 10px;
    right: 10px; /* Bên phải */
    z-index: 20;
    background: #ffc107;
    color: #000;
    padding: 6px 14px;
    border-radius: 4px;
    font-weight: bold;
    font-size: 14px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.3);
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.product-card-promo .product-image {
    position: relative;
    overflow: hidden;
}

/* Full Width Carousel Container */
.promo-carousel-fullwidth {
    width: 100vw;
    position: relative;
    left: 50%;
    right: 50%;
    margin-left: -50vw;
    margin-right: -50vw;
    padding-left: 80px;
    padding-right: 80px;
}

.promo-carousel-fullwidth .container-fluid {
    padding-left: 0;
    padding-right: 0;
    max-width: 100%;
}

/* Carousel Styles */
#promoCarousel {
    margin-bottom: 2rem;
    height: 460px;
}

#promoCarousel .carousel-inner {
    height: 460px;
    overflow: hidden;
}

#promoCarousel .carousel-item {
    position: relative;
    height: 460px;
    width: 100%;
    display: none;
    align-items: center;
    justify-content: center;
    transition: transform 0.6s ease-in-out;
}

#promoCarousel .carousel-item.active,
#promoCarousel .carousel-item-next,
#promoCarousel .carousel-item-prev {
    display: flex;
    min-height: unset !important;
}

#promoCarousel .carousel-control-prev,
#promoCarousel .carousel-control-next {
    width: 50px;
    height: 50px;
    background: rgba(0, 0, 0, 0.6);
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
    opacity: 0.8;
    z-index: 10;
    position: absolute;
}

#promoCarousel .carousel-control-prev {
    left: 10px;
}

#promoCarousel .carousel-control-next {
    right: 10px;
}

@media (max-width: 768px) {
    #promoCarousel .carousel-control-prev {
        left: 5px;
    }
    
    #promoCarousel .carousel-control-next {
        right: 5px;
    }
    
    #promoCarousel .carousel-control-prev,
    #promoCarousel .carousel-control-next {
        width: 40px;
        height: 40px;
    }
}

#promoCarousel .carousel-control-prev:hover,
#promoCarousel .carousel-control-next:hover {
    opacity: 1;
    background: rgba(0, 0, 0, 0.8);
}

#promoCarousel .carousel-indicators {
    display: none;
}

/* Giảm khoảng trắng, sát với slider */
.promotion-section {
    padding-top: 0;
}

.promotion-section .promo-carousel-fullwidth {
    margin-top: 0;
    padding-top: 0;
}

</style>
@endsection

