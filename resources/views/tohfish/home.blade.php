@extends('layouts.app')

@section('title', 'TOH fish - Cá lóc bông sạch')

@section('content')
<!-- Hero Slider Section -->
<section class="hero-slider-section">
    <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000" data-bs-pause="false">
        <div class="carousel-inner">
            @if(isset($homeSliders) && $homeSliders->isNotEmpty())
                @foreach($homeSliders as $index => $slider)
                <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
                    <div class="hero-slide-img" style="
                        @if($slider->image_url)
                            background-image: url('{{ route('storage.serve', ['path' => $slider->image_url]) }}');
                        @elseif($slider->background_color)
                            background: {{ $slider->background_color }};
                        @else
                            background-image: url('{{ url('images/home/home-1.jpg') }}');
                        @endif
                    "></div>
                    <div class="hero-overlay"></div>
                    <div class="carousel-caption">
                        @if($slider->icon)
                        <div class="hero-icon mb-3 animate-fade-in">
                            <i class="{{ $slider->icon }} fa-3x" style="color: #fff;"></i>
                        </div>
                        @endif
                        <h1 class="hero-title animate-fade-in">{{ $slider->title }}</h1>
                        @if($slider->description)
                        <p class="hero-subtitle animate-fade-in-delay">{{ $slider->description }}</p>
                        @endif
                        @if(($slider->link && $slider->button_text) || ($slider->link_2 && $slider->button_text_2))
                        <div class="hero-buttons animate-fade-in-delay-2">
                            @if($slider->link && $slider->button_text)
                            <a href="{{ $slider->link }}" class="btn btn-primary btn-lg me-3">
                                {{ $slider->button_text }}
                            </a>
                            @endif
                            @if($slider->link_2 && $slider->button_text_2)
                            <a href="{{ $slider->link_2 }}" class="btn btn-outline-light btn-lg">
                                {{ $slider->button_text_2 }}
                            </a>
                            @endif
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            @else
                <!-- Fallback slides cũ nếu chưa có slider -->
                <div class="carousel-item active">
                    @if($latestImages->isNotEmpty() && $latestImages->first() && isset($latestImages->first()->display_url) && $latestImages->first()->display_url && ($latestImages->first()->image_exists ?? false))
                    <div class="hero-slide-img" style="background-image: url('{{ $latestImages->first()->display_url }}');"></div>
                    @else
                    <div class="hero-slide-img" style="background-image: url('{{ asset('images/home/home-1.jpg') }}');"></div>
                    @endif
                    <div class="hero-overlay"></div>
                    <div class="carousel-caption">
                        <h1 class="hero-title animate-fade-in">Cá Lóc Bông Sạch Từ Chăn Nuôi</h1>
                        <p class="hero-subtitle animate-fade-in-delay">Nhà cung cấp cá lóc bông sạch chất lượng cao</p>
                        <div class="hero-buttons animate-fade-in-delay-2">
                            <a href="{{ route('products.soche') }}" class="btn btn-primary btn-lg me-3">
                                <i class="fas fa-fish"></i> Xem Sản Phẩm
                            </a>
                            <a href="{{ route('about') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-info-circle"></i> Về Chúng Tôi
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="carousel-item">
                    @if($latestImages->count() > 1 && $latestImages->skip(1)->first() && isset($latestImages->skip(1)->first()->display_url) && $latestImages->skip(1)->first()->display_url && ($latestImages->skip(1)->first()->image_exists ?? false))
                    <div class="hero-slide-img" style="background-image: url('{{ $latestImages->skip(1)->first()->display_url }}');"></div>
                    @else
                    <div class="hero-slide-img" style="background-image: url('{{ url('images/home/1.png') }}');"></div>
                    @endif
                    <div class="hero-overlay" style="background: linear-gradient(135deg, rgba(255, 107, 0, 0.8) 0%, rgba(255, 152, 0, 0.7) 100%);"></div>
                    <div class="carousel-caption">
                        <h1 class="hero-title">
                            <i class="fas fa-gift"></i> Khuyến Mãi Đặc Biệt
                        </h1>
                        <p class="hero-subtitle">Giảm 3% khi đặt hàng qua WEB với mã <strong>TOH3</strong></p>
                        <p class="hero-subtitle mb-4">Áp dụng cho đơn hàng từ 600.000₫ trở lên</p>
                        <div class="hero-buttons">
                            <a href="{{ route('promotion') }}" class="btn btn-danger btn-lg me-3">
                                <i class="fas fa-tags"></i> Xem Khuyến Mãi
                            </a>
                            <a href="{{ route('products.soche') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-shopping-cart"></i> Mua Ngay
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="carousel-item">
                    @if($latestPosts->isNotEmpty() && $latestPosts->first() && $latestPosts->first()->image_url)
                    <div class="hero-slide-img" style="background-image: url('{{ $latestPosts->first()->image_url }}');"></div>
                    @elseif($latestImages->count() > 2 && $latestImages->skip(2)->first() && isset($latestImages->skip(2)->first()->display_url) && $latestImages->skip(2)->first()->display_url && ($latestImages->skip(2)->first()->image_exists ?? false))
                    <div class="hero-slide-img" style="background-image: url('{{ $latestImages->skip(2)->first()->display_url }}');"></div>
                    @else
                    <div class="hero-slide-img" style="background-image: url('{{ url('images/bai-viet/bai-viet-1.png') }}');"></div>
                    @endif
                    <div class="hero-overlay" style="background: linear-gradient(135deg, rgba(0, 168, 89, 0.8) 0%, rgba(0, 102, 204, 0.7) 100%);"></div>
                    <div class="carousel-caption">
                        <h1 class="hero-title">
                            <i class="fas fa-newspaper"></i> Tin Tức & Công Thức
                        </h1>
                        @if($latestPosts->isNotEmpty())
                        <p class="hero-subtitle">{{ Str::limit($latestPosts->first()->title ?? $latestPosts->first()->description ?? 'Cập nhật tin tức mới nhất từ TOH fish', 80) }}</p>
                        @else
                        <p class="hero-subtitle">Cập nhật tin tức, công thức nấu ăn và câu chuyện từ TOH fish</p>
                        @endif
                        <div class="hero-buttons">
                            <a href="{{ route('blog.index') }}" class="btn btn-success btn-lg me-3">
                                <i class="fas fa-book-open"></i> Đọc Tin Tức
                            </a>
                            <a href="{{ route('blog.congthuc') }}" class="btn btn-outline-light btn-lg">
                                <i class="fas fa-utensils"></i> Công Thức Món Ngon
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</section>

<!-- Product Categories -->
<section class="product-categories py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">
            <span class="title-decoration">Sản Phẩm Của Chúng Tôi</span>
        </h2>
        <div class="row g-4">
            <div class="col-md-3 mb-4">
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-fish"></i>
                    </div>
                    <h4>Sản Phẩm Sơ Chế</h4>
                    <p>Cá tươi sống, đảm bảo chất lượng</p>
                    <a href="{{ route('products.soche') }}" class="btn btn-outline-primary">Xem thêm</a>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <h4>Sản Phẩm Chế Biến</h4>
                    <p>Các món cá đã chế biến thơm ngon</p>
                    <a href="{{ route('products.chebien') }}" class="btn btn-outline-primary">Xem thêm</a>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-hamburger"></i>
                    </div>
                    <h4>Sản Phẩm Chế Biến Sẵn</h4>
                    <p>Thực phẩm chế biến sẵn tiện lợi</p>
                    <a href="{{ route('products.chebiensan') }}" class="btn btn-outline-primary">Xem thêm</a>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="category-card">
                    <div class="category-icon">
                        <i class="fas fa-shopping-bag"></i>
                    </div>
                    <h4>Sản Phẩm Khác</h4>
                    <p>Bún cá, rau gia vị và nhiều hơn nữa</p>
                    <a href="{{ route('products.khac') }}" class="btn btn-outline-primary">Xem thêm</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products - Sản phẩm mới nhất với Slider -->
<section class="featured-products py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-5">
                <span class="title-decoration">Sản phẩm mới nhất</span>
        </h2>
        
        @php
            // Chỉ hiển thị 4 sản phẩm mới nhất ở trang chủ
            $displayProducts = $latestImages->isNotEmpty() ? $latestImages->take(4) : collect();
            $totalProducts = $latestImages->count(); // Tổng số sản phẩm có sẵn
        @endphp
        
        <div class="row g-4">
            @if($displayProducts->isNotEmpty())
                @foreach($displayProducts as $itemIndex => $image)
                @php
                $favoriteCount = \App\Models\Favorite::where('product_id', $image->images_id)->count();
                $isFavorite = false;
                if (Auth::check()) {
                    $isFavorite = \App\Models\Favorite::where('user_id', Auth::id())
                        ->where('product_id', $image->images_id)
                        ->exists();
                }
                // Tính toán giá và giảm giá
                $originalPrice = $image->price ?? 135000;
                $discountPercent = $image->discount_percent ?? 0;
                if ($discountPercent <= 0 || $discountPercent > 100) {
                    $discountPercent = 0;
                }
                $discountPrice = $discountPercent > 0 
                    ? $originalPrice * (1 - $discountPercent / 100) 
                    : $originalPrice;
                // Sản phẩm mới: hiển thị badge MỚI
                $isNewProduct = $itemIndex < 4;
                $hasDiscount = $discountPercent > 0;
                $showNewBadge = $isNewProduct;
                @endphp
                <div class="col-lg-3 col-md-6">
                    <div class="product-card">
                        <div class="product-image" style="position: relative; width: 100%; aspect-ratio: 1 / 1; overflow: hidden; background-color: #f8f9fa;">
                            @if($showNewBadge)
                                <div style="position: absolute; top: 10px; right: 10px; background-color: #dc3545; color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                    MỚI
                                </div>
                            @elseif($hasDiscount)
                                <div style="position: absolute; top: 10px; right: 10px; background-color: #ffc107; color: #000; padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                    GIẢM {{ $discountPercent }}%
                                </div>
                            @endif
                            <a href="{{ route('product.detail', $image->images_id ?? ($itemIndex + 1)) }}" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                                @if(isset($image->display_url) && $image->display_url && ($image->image_exists ?? false))
                                <img src="{{ $image->display_url }}" alt="{{ $image->content ?? 'Sản phẩm' }}" class="img-fluid product-main-image" style="width: 100%; height: 100%; object-fit: cover; display: block;" data-product-id="{{ $image->images_id ?? ($itemIndex + 1) }}" data-original-src="{{ $image->display_url }}" data-sub-image="{{ isset($image->sub_images_urls) && is_array($image->sub_images_urls) && count($image->sub_images_urls) > 0 ? $image->sub_images_urls[0] : '' }}">
                                @else
                                <img src="{{ url('images/home/' . (($itemIndex % 14) + 1) . '.png') }}" alt="{{ $image->content ?? 'Sản phẩm' }}" class="img-fluid product-main-image" style="width: 100%; height: 100%; object-fit: cover; display: block;" data-product-id="{{ $image->images_id ?? ($itemIndex + 1) }}" data-original-src="{{ url('images/home/' . (($itemIndex % 14) + 1) . '.png') }}" data-sub-image="">
                                @endif
                            </a>
                            {{-- Ẩn ảnh phụ - chỉ hiển thị khi hover vào ảnh chính --}}
                        </div>
                        <div class="product-info" style="margin-top: 12px;">
                            <h5 class="product-name"><a href="{{ route('product.detail', $image->images_id ?? ($itemIndex + 1)) }}" style="text-decoration: none; color: inherit;">{{ $image->content ?? 'Sản phẩm mới nhất' }}</a></h5>
                            @if(isset($image->product_type) && $image->product_type)
                                <p class="product-type mb-1" style="color: #999; font-size: 0.875rem; margin: 0;">Sản phẩm {{ strtolower($image->product_type) }}</p>
                            @endif
                            @if($hasDiscount)
                                {{-- Sản phẩm có giảm giá: hiển thị cả giá gốc và giá giảm --}}
                                <div class="price-group" style="display: flex; align-items: center; margin-bottom: 10px;">
                                    <span class="product-price" style="font-size:1.25rem; font-weight:700; color:#ff6b00; margin-right:10px;">
                                        {{ number_format($discountPrice, 0, ',', '.') }}₫
                                    </span>
                                    <span class="old-price" style="text-decoration: line-through; color: #999; font-size: 0.875rem;">
                                        {{ number_format($originalPrice, 0, ',', '.') }}₫
                                    </span>
                                </div>
                            @else
                                {{-- Sản phẩm không có giảm giá --}}
                                <p class="product-price">{{ number_format($originalPrice, 0, ',', '.') }}₫</p>
                            @endif
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <input type="number" 
                                       class="form-control quantity-input" 
                                       style="max-width: 80px;" 
                                       min="1" 
                                       value="1"
                                       data-product-id="{{ $image->images_id }}">
                                <form action="{{ route('cart.add') }}" method="POST" class="d-inline flex-grow-1 add-to-cart-form" onsubmit="return false;">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $image->images_id }}">
                                    <input type="hidden" name="name" value="{{ $image->content ?? 'Sản phẩm mới nhất' }}">
                                    <input type="hidden" name="price" value="{{ round($discountPrice) }}">
                                    <input type="hidden" name="quantity" value="1" class="quantity-hidden-input">
                                    @if(isset($image->display_url) && $image->display_url)
                                        <input type="hidden" name="image" value="{{ $image->display_url }}">
                                    @endif
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        THÊM
                                    </button>
                                </form>
                                <div class="favorite-display ms-1 {{ $isFavorite ? 'active' : '' }}" 
                                     data-product-id="{{ $image->images_id }}"
                                     onclick="toggleFavoriteDetail({{ $image->images_id }}, this)"
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
                @for($i = 1; $i <= 4; $i++)
                <div class="col-lg-3 col-md-6">
                    <div class="product-card">
                        <div class="product-image" style="position: relative; width: 100%; aspect-ratio: 1 / 1; overflow: hidden; background-color: #f8f9fa;">
                            <a href="{{ route('product.detail', $i) }}" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                                <img src="{{ asset('images/home/' . (($i % 14) + 1) . '.png') }}" alt="Sản phẩm {{ $i }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                            </a>
                        </div>
                        <div class="product-info" style="margin-top: 12px;">
                            <h5 class="product-name"><a href="{{ route('product.detail', $i) }}" style="text-decoration: none; color: inherit;">Sản phẩm mới nhất {{ $i }}</a></h5>
                            <p class="product-price">135,000₫</p>
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <input type="number" 
                                       class="form-control quantity-input" 
                                       style="max-width: 80px;" 
                                       min="1" 
                                       value="1"
                                       data-product-id="{{ $i }}">
                                <form action="{{ route('cart.add') }}" method="POST" class="d-inline flex-grow-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $i }}">
                                    <input type="hidden" name="name" value="Sản phẩm mới nhất {{ $i }}">
                                    <input type="hidden" name="price" value="135000">
                                    <input type="hidden" name="quantity" value="1" class="quantity-hidden-input">
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        THÊM
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endfor
            @endif
        </div>
        
        {{-- Chỉ hiển thị nút "Xem thêm sản phẩm" khi có nhiều hơn 4 sản phẩm --}}
        @if($totalProducts > 4)
        <div class="text-center mt-5">
            <a href="{{ route('products.latest') }}" class="btn btn-primary btn-lg px-5">
                Xem thêm sản phẩm <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        @endif
    </div>
</section>

<!-- Featured Products - Sản phẩm bán chạy -->
<section class="featured-products py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">
                <span class="title-decoration">Sản phẩm bán chạy</span>
        </h2>
        
        @php
            // Chỉ hiển thị tối đa 8 sản phẩm bán chạy ở trang chủ với bố cục 4 sản phẩm mỗi hàng
            $displayBestSelling = isset($bestSellingImages) && $bestSellingImages->isNotEmpty() ? $bestSellingImages->take(8) : collect();
            $totalBestSelling = isset($bestSellingImages) ? $bestSellingImages->count() : 0; // Tổng số sản phẩm bán chạy có sẵn
        @endphp
        
        <div class="row g-4">
            @if($displayBestSelling->isNotEmpty())
                @foreach($displayBestSelling as $itemIndex => $image)
                @php
                    $favoriteCountBestSelling = \App\Models\Favorite::where('product_id', $image->images_id)->count();
                    $isFavoriteBestSelling = false;
                    if (Auth::check()) {
                        $isFavoriteBestSelling = \App\Models\Favorite::where('user_id', Auth::id())
                            ->where('product_id', $image->images_id)
                            ->exists();
                    }
                    // Tính toán giá và giảm giá cho sản phẩm bán chạy
                    $originalPriceBestSelling = $image->price ?? 139000;
                    $discountPercentBestSelling = $image->discount_percent ?? 0;
                    if ($discountPercentBestSelling <= 0 || $discountPercentBestSelling > 100) {
                        $discountPercentBestSelling = 0;
                    }
                    $discountPriceBestSelling = $discountPercentBestSelling > 0 
                        ? $originalPriceBestSelling * (1 - $discountPercentBestSelling / 100) 
                        : $originalPriceBestSelling;
                    $hasDiscountBestSelling = $discountPercentBestSelling > 0;
                @endphp
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="product-card product-card-processed">
                        <div class="product-image" style="position: relative; width: 100%; aspect-ratio: 1 / 1; overflow: hidden; background-color: #f8f9fa;">
                            @if($hasDiscountBestSelling)
                                <div style="position: absolute; top: 10px; right: 10px; background-color: #ffc107; color: #000; padding: 4px 10px; border-radius: 4px; font-size: 0.75rem; font-weight: bold; z-index: 10; box-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                                    GIẢM {{ $discountPercentBestSelling }}%
                                </div>
                            @endif
                            <a href="{{ route('product.detail', $image->images_id ?? ($itemIndex + 1)) }}" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                                @if(isset($image->display_url) && $image->display_url && ($image->image_exists ?? false))
                                <img src="{{ $image->display_url }}" alt="{{ $image->content ?? 'Sản phẩm' }}" class="img-fluid product-main-image" style="width: 100%; height: 100%; object-fit: cover; display: block;" data-product-id="{{ $image->images_id ?? ($itemIndex + 1) }}" data-original-src="{{ $image->display_url }}" data-sub-image="{{ isset($image->sub_images_urls) && is_array($image->sub_images_urls) && count($image->sub_images_urls) > 0 ? $image->sub_images_urls[0] : '' }}">
                                @else
                                <img src="{{ url('images/home/' . (($itemIndex % 14) + 1) . '.png') }}" alt="{{ $image->content ?? 'Sản phẩm' }}" class="img-fluid product-main-image" style="width: 100%; height: 100%; object-fit: cover; display: block;" data-product-id="{{ $image->images_id ?? ($itemIndex + 1) }}" data-original-src="{{ url('images/home/' . (($itemIndex % 14) + 1) . '.png') }}" data-sub-image="">
                                @endif
                            </a>
                            {{-- Ẩn ảnh phụ - chỉ hiển thị khi hover vào ảnh chính --}}
                        </div>
                        <div class="product-info" style="margin-top: 12px;">
                            <h5 class="product-name"><a href="{{ route('product.detail', $image->images_id ?? ($itemIndex + 1)) }}" style="text-decoration: none; color: inherit;">{{ $image->content ?? 'Sản phẩm bán chạy' }}</a></h5>
                            @if(isset($image->product_type) && $image->product_type)
                                <p class="product-type mb-1" style="color: #999; font-size: 0.875rem; margin: 0;">Sản phẩm {{ strtolower($image->product_type) }}</p>
                            @endif
                            @if($hasDiscountBestSelling)
                                {{-- Sản phẩm bán chạy có giảm giá: hiển thị cả giá gốc và giá giảm --}}
                                <div class="price-group" style="display: flex; align-items: center; margin-bottom: 10px;">
                                    <span class="product-price" style="font-size:1.25rem; font-weight:700; color:#ff6b00; margin-right:10px;">
                                        {{ number_format($discountPriceBestSelling, 0, ',', '.') }}₫
                                    </span>
                                    <span class="old-price" style="text-decoration: line-through; color: #999; font-size: 0.875rem;">
                                        {{ number_format($originalPriceBestSelling, 0, ',', '.') }}₫
                                    </span>
                                </div>
                            @else
                                {{-- Sản phẩm bán chạy không có giảm giá --}}
                                <p class="product-price">{{ number_format($originalPriceBestSelling, 0, ',', '.') }}₫</p>
                            @endif
                            <div class="d-flex align-items-center gap-2">
                                <input type="number" 
                                       class="form-control quantity-input" 
                                       style="max-width: 80px;" 
                                       min="1" 
                                       value="1"
                                       data-product-id="{{ $image->images_id }}">
                                <form action="{{ route('cart.add') }}" method="POST" class="d-inline flex-grow-1 me-1">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $image->images_id }}">
                                    <input type="hidden" name="name" value="{{ $image->content ?? 'Sản phẩm bán chạy' }}">
                                    <input type="hidden" name="price" value="{{ round($discountPriceBestSelling) }}">
                                    <input type="hidden" name="quantity" value="1" class="quantity-hidden-input">
                                    @if(isset($image->display_url) && $image->display_url)
                                        <input type="hidden" name="image" value="{{ $image->display_url }}">
                                    @endif
                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                        THÊM
                                    </button>
                                </form>
                                <div class="favorite-display {{ $isFavoriteBestSelling ? 'active' : '' }}" 
                                     data-product-id="{{ $image->images_id }}"
                                     onclick="toggleFavoriteDetail({{ $image->images_id }}, this)"
                                     title="{{ $isFavoriteBestSelling ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}">
                                    <i class="{{ $isFavoriteBestSelling ? 'fas' : 'far' }} fa-heart"></i>
                                    <span class="favorite-count">{{ $favoriteCountBestSelling }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                @for($i = 1; $i <= 8; $i++)
                <div class="col-lg-3 col-md-3 col-sm-6">
                    <div class="product-card product-card-processed">
                        <div class="product-image" style="position: relative; width: 100%; aspect-ratio: 1 / 1; overflow: hidden; background-color: #f8f9fa;">
                            <a href="{{ route('product.detail', $i) }}" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                                <img src="{{ asset('images/home/' . (($i % 14) + 1) . '.png') }}" alt="Sản phẩm {{ $i }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                            </a>
                        </div>
                        <div class="product-info" style="margin-top: 12px;">
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
        
        {{-- Chỉ hiển thị nút "Xem thêm sản phẩm" khi có nhiều hơn 8 sản phẩm bán chạy --}}
        @if($totalBestSelling > 8)
        <div class="text-center mt-5">
            <a href="{{ route('products.best_selling') }}" class="btn btn-primary btn-lg px-5">
                Xem thêm sản phẩm <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        @endif
    </div>
</section>

<!-- News Section -->
<section class="news-section py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-5">Tin tức</h2>
        <div class="row">
            @forelse($latestPosts->take(6) as $post)
            <div class="col-md-4 mb-4">
                <div class="news-card">
                    <div class="news-image-container">
                        @if(isset($post->display_url) && $post->display_url && ($post->image_exists ?? false))
                        <img src="{{ $post->display_url }}" alt="{{ $post->title ?? $post->description }}" class="img-fluid">
                        @else
                        <img src="{{ asset('images/bai-viet/bai-viet-' . (($loop->index % 16) + 1) . '.png') }}" alt="{{ $post->title ?? $post->description }}" class="img-fluid">
                        @endif
                        <div class="news-overlay">
                            <a href="{{ route('chi-tiet-bai-viet', $post->post_id) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-book-open"></i> Đọc ngay
                            </a>
                        </div>
                    </div>
                    <div class="news-content">
                        <div class="news-meta">
                            <span class="news-date"><i class="far fa-calendar"></i> {{ $post->created_at ? $post->created_at->format('d/m/Y') : '' }}</span>
                            @if($post->view)
                            <span class="news-views"><i class="far fa-eye"></i> {{ $post->view }}</span>
                            @endif
                        </div>
                        <h5>{{ Str::limit($post->title ?? $post->description ?? $post->content ?? 'Bài viết', 60) }}</h5>
                        <p class="text-muted">{{ Str::limit($post->content ?? $post->description ?? '', 120) }}</p>
                        <a href="{{ route('chi-tiet-bai-viet', $post->post_id) }}" class="btn btn-link p-0">
                            Đọc thêm <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            @for($i = 1; $i <= 6; $i++)
            <div class="col-md-4 mb-4">
                <div class="news-card">
                    <div class="news-image-container">
                        <img src="{{ asset('images/bai-viet/bai-viet-' . (($i % 16) + 1) . '.png') }}" alt="Tin tức {{ $i }}" class="img-fluid">
                        <div class="news-overlay">
                            <a href="#" class="btn btn-light btn-sm">
                                <i class="fas fa-book-open"></i> Đọc ngay
                            </a>
                        </div>
                    </div>
                    <div class="news-content">
                        <div class="news-meta">
                            <span class="news-date"><i class="far fa-calendar"></i> {{ date('d/m/Y') }}</span>
                            <span class="news-views"><i class="far fa-eye"></i> {{ rand(100, 999) }}</span>
                        </div>
                        <h5>Cá lóc nấu gì ngon? Gợi ý các món ăn hấp dẫn từ cá lóc bông mới nhất</h5>
                        <p class="text-muted">Cá lóc là một loại thực phẩm quen thuộc và giàu dinh dưỡng trong ẩm thực Việt Nam. Với thịt cá chắc, ngọt và ít xương...</p>
                        <a href="#" class="btn btn-link p-0">
                            Đọc thêm <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endfor
            @endforelse
        </div>
        <div class="text-center mt-4">
            <a href="{{ route('blog.index') }}" class="btn btn-primary">Xem thêm</a>
        </div>
    </div>
</section>

<!-- Partners Section -->
<section class="partners-section py-5 bg-light">
    <div class="container">
        <h2 class="section-title text-center mb-5">
                <span class="title-decoration">Đối Tác Cùng TOH fish</span>
        </h2>
        <div class="row justify-content-center align-items-center g-4">
            @for($i = 1; $i <= 5; $i++)
            <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                <div class="partner-logo">
                    <img src="{{ url('images/home/' . $i . '.png') }}" alt="Đối tác {{ $i }}" class="img-fluid">
                </div>
            </div>
            @endfor
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý hover vào ảnh phụ để đổi ảnh chính
    const subThumbs = document.querySelectorAll('.product-sub-thumb');
    
    subThumbs.forEach(function(thumb) {
        const productId = thumb.getAttribute('data-product-id');
        const subImageSrc = thumb.getAttribute('data-image-src');
        const mainImage = document.querySelector(`.product-main-image[data-product-id="${productId}"]`);
        
        if (mainImage) {
            const originalSrc = mainImage.getAttribute('data-original-src');
            
            // Khi hover vào ảnh phụ
            thumb.addEventListener('mouseenter', function() {
                mainImage.src = subImageSrc;
            });
            
            // Khi rời khỏi ảnh phụ
            thumb.addEventListener('mouseleave', function() {
                mainImage.src = originalSrc;
            });
        }
    });
});
</script>
@endsection
