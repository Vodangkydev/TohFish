@extends('layouts.app')

@section('title', $product->content . ' - TOH fish')

@section('content')
<section class="product-detail-section py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.soche') }}">Sản phẩm</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $product->content }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-6 mb-4">
                <!-- Ảnh chính -->
                <div class="main-image mb-3 position-relative" style="overflow: hidden; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    <div id="mainImageContainer" style="position: relative; width: 100%; height: 450px; background: #f0f0f0; border-radius: 8px; overflow: hidden;">
                        @if($product->display_url && ($product->image_exists ?? false))
                            <img id="mainProductImage" src="{{ $product->display_url }}" 
                                 alt="{{ $product->content }}" 
                                 class="img-fluid w-100 main-product-image"
                                 style="max-height: 450px; width: 100%; height: 450px; object-fit: cover; border-radius: 8px;">
                        @else
                            <img id="mainProductImage" src="{{ asset('images/home/home-1.jpg') }}" 
                                 alt="{{ $product->content }}" 
                                 class="img-fluid w-100 main-product-image"
                                 style="max-height: 450px; width: 100%; height: 450px; object-fit: cover; border-radius: 8px;">
                        @endif
                    </div>
                    @if($product->sub_images_urls && count($product->sub_images_urls) > 0)
                        <div class="mt-2 text-center">
                            <small class="text-muted"><i class="fas fa-images"></i> Click vào ảnh phụ bên dưới để xem chi tiết</small>
                        </div>
                    @endif
                </div>

                <!-- Ảnh phụ (3 ảnh) + Ảnh chính -->
                @if($product->display_url && ($product->image_exists ?? false))
                    <div class="sub-images mt-4">
                        <h6 class="mb-3"><i class="fas fa-th"></i> Tất cả ảnh ({{ ($product->sub_images_urls ? count($product->sub_images_urls) : 0) + 1 }} ảnh):</h6>
                        <div class="row g-2">
                            <!-- Ảnh chính -->
                            <div class="col-3">
                                <div class="sub-image-wrapper position-relative sub-thumb-main active" 
                                     onclick="changeMainImage('{{ $product->display_url }}', -1)"
                                     data-image-url="{{ $product->display_url }}"
                                     data-index="-1"
                                     style="cursor: pointer; border-radius: 8px; overflow: hidden; border: 3px solid #007bff; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative; box-shadow: 0 4px 12px rgba(0,123,255,0.4);">
                                            <img src="{{ $product->display_url }}" 
                                         alt="Ảnh chính" 
                                         class="img-fluid sub-image-thumbnail"
                                             style="width: 100%; height: 100px; object-fit: cover; display: block; transition: transform 0.3s ease;">
                                    <div class="sub-image-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to top, rgba(0,123,255,0.6), transparent); opacity: 0; transition: opacity 0.3s ease; display: flex; align-items: center; justify-content: center; pointer-events: none;">
                                        <div style="background: rgba(255,255,255,0.9); border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; transform: scale(0.8); transition: transform 0.3s ease;">
                                            <i class="fas fa-check text-primary" style="font-size: 16px;"></i>
                                        </div>
                                    </div>
                                    <span class="badge bg-primary position-absolute top-0 start-0 m-2" style="font-size: 10px;">Chính</span>
                                </div>
                            </div>
                            
                            <!-- Ảnh phụ -->
                            @if($product->sub_images_urls && count($product->sub_images_urls) > 0)
                                @foreach($product->sub_images_urls as $index => $subImageUrl)
                                    <div class="col-3">
                                        <div class="sub-image-wrapper position-relative sub-thumb-{{ $index }}" 
                                             onclick="changeMainImage('{{ $subImageUrl }}', {{ $index }})"
                                             data-image-url="{{ $subImageUrl }}"
                                             data-index="{{ $index }}"
                                             style="cursor: pointer; border-radius: 8px; overflow: hidden; border: 2px solid #ddd; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative;">
                                            <img src="{{ $subImageUrl }}" 
                                                 alt="Ảnh phụ {{ $index + 1 }}" 
                                                 class="img-fluid sub-image-thumbnail"
                                                 style="width: 100%; height: 100px; object-fit: cover; display: block; transition: transform 0.3s ease;">
                                            <div class="sub-image-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to top, rgba(0,123,255,0.6), transparent); opacity: 0; transition: opacity 0.3s ease; display: flex; align-items: center; justify-content: center; pointer-events: none;">
                                                <div style="background: rgba(255,255,255,0.9); border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; transform: scale(0.8); transition: transform 0.3s ease;">
                                                    <i class="fas fa-search-plus text-primary" style="font-size: 18px;"></i>
                                                </div>
                                            </div>
                                            <span class="badge bg-secondary position-absolute top-0 start-0 m-2" style="font-size: 10px;">{{ $index + 1 }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @elseif($product->sub_images_urls && count($product->sub_images_urls) > 0)
                    <div class="sub-images mt-4">
                        <h6 class="mb-3"><i class="fas fa-th"></i> Ảnh phụ ({{ count($product->sub_images_urls) }} ảnh):</h6>
                                <div class="row g-2">
                            @foreach($product->sub_images_urls as $index => $subImageUrl)
                                <div class="col-3">
                                    <div class="sub-image-wrapper position-relative sub-thumb-{{ $index }}" 
                                         onclick="changeMainImage('{{ $subImageUrl }}', {{ $index }})"
                                         data-image-url="{{ $subImageUrl }}"
                                         data-index="{{ $index }}"
                                         style="cursor: pointer; border-radius: 8px; overflow: hidden; border: 2px solid #ddd; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative;">
                                        <img src="{{ $subImageUrl }}" 
                                             alt="Ảnh phụ {{ $index + 1 }}" 
                                             class="img-fluid sub-image-thumbnail"
                                             style="width: 100%; height: 100px; object-fit: cover; display: block; transition: transform 0.3s ease;">
                                        <div class="sub-image-overlay" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to top, rgba(0,123,255,0.6), transparent); opacity: 0; transition: opacity 0.3s ease; display: flex; align-items: center; justify-content: center; pointer-events: none;">
                                            <div style="background: rgba(255,255,255,0.9); border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; transform: scale(0.8); transition: transform 0.3s ease;">
                                                <i class="fas fa-search-plus text-primary" style="font-size: 18px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="col-lg-6">
                <div class="product-info">
                    <div class="d-flex align-items-center mb-3 flex-wrap">
                        <h1 class="product-title me-3 mb-0">{{ $product->content }}</h1>
                        @if($product->product_type)
                            <span class="badge bg-primary fs-6">{{ $product->product_type }}</span>
                        @endif
                    </div>
                    
                    @if($product->price)
                        <div class="product-price mb-4">
                            <span class="price-label">Giá:</span>
                            <span class="price-value h3 text-danger fw-bold">
                                {{ number_format($product->price, 0, ',', '.') }}₫
                            </span>
                        </div>
                    @endif

                    <div class="product-favorite mb-4">
                        @php
                            $favoriteCount = \App\Models\Favorite::where('product_id', $product->images_id)->count();
                            $isFavorite = false;
                            if (Auth::check()) {
                                $isFavorite = \App\Models\Favorite::where('user_id', Auth::id())
                                    ->where('product_id', $product->images_id)
                                    ->exists();
                            }
                        @endphp
                        @auth
                        <div class="favorite-display {{ $isFavorite ? 'active' : '' }}" 
                             data-product-id="{{ $product->images_id }}"
                             onclick="toggleFavoriteDetail({{ $product->images_id }}, this)"
                             title="{{ $isFavorite ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}">
                            <i class="{{ $isFavorite ? 'fas' : 'far' }} fa-heart"></i>
                            <span class="favorite-count">{{ $favoriteCount }}</span>
                        </div>
                        @else
                        <div class="favorite-display">
                            <i class="far fa-heart"></i>
                            <span class="favorite-count">{{ $favoriteCount }}</span>
                        </div>
                        @endauth
                    </div>

                    @php
                        $sizeOptions = [];
                        if (!empty($product->size)) {
                            $sizeOptions = array_filter(array_map('trim', explode(',', $product->size)));
                        }
                    @endphp

                    @if(!empty($sizeOptions))
                        <div class="product-size mb-3">
                            <label class="form-label fw-bold mb-2">Kích thước:</label>
                            <div class="d-flex align-items-center">
                                <select id="selectedSize" class="form-select w-auto">
                                    @foreach($sizeOptions as $option)
                                        <option value="{{ $option }}">{{ $option }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    @endif

                    <div class="product-quantity mb-4">
                        <label class="form-label fw-bold mb-2">Số lượng:</label>
                        <div class="d-flex align-items-center">
                            <input type="number" id="selectedQuantity" class="form-control" style="max-width: 120px;" min="1" value="1">
                        </div>
                    </div>

                    <div class="product-actions mb-4">
                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->images_id }}">
                            <input type="hidden" name="name" value="{{ $product->content }}">
                            <input type="hidden" name="price" value="{{ $product->price ?? 0 }}">
                            @if($product->display_url)
                                <input type="hidden" name="image" value="{{ $product->display_url }}">
                            @endif
                            <!-- size & quantity sẽ được đồng bộ bằng JavaScript -->
                            <button type="submit" class="btn btn-primary btn-lg me-2">
                                <i class="fas fa-cart-plus"></i> Thêm vào giỏ hàng
                            </button>
                        </form>
                        
                        <form action="{{ route('cart.buy_now') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->images_id }}">
                            <input type="hidden" name="name" value="{{ $product->content }}">
                            <input type="hidden" name="price" value="{{ $product->price ?? 0 }}">
                            @if($product->display_url)
                                <input type="hidden" name="image" value="{{ $product->display_url }}">
                            @endif
                            <!-- size & quantity sẽ được đồng bộ bằng JavaScript -->
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-shopping-cart"></i> Mua ngay
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mô tả sản phẩm - Di chuyển xuống dưới -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="product-description">
                    <h5 class="mb-3"><i class="fas fa-info-circle"></i> Mô tả sản phẩm:</h5>
                    <div class="description-box p-4 bg-white rounded shadow-sm" style="border-left: 4px solid #007bff;">
                        @if($product->description)
                            <div class="description-content" style="white-space: pre-wrap; line-height: 1.8; color: #333; font-size: 15px;">
                                {{ $product->description }}
                            </div>
                        @else
                            <p class="text-muted mb-0">{{ $product->content }}</p>
                        @endif
                    </div>
                    <p class="mt-3 text-end pe-3">
                        <small class="text-muted">
                            <i class="fas fa-calendar"></i> Ngày đăng: {{ $product->created_at->format('d/m/Y') }}
                        </small>
                    </p>
                </div>
            </div>
        </div>

        @if($relatedProducts && $relatedProducts->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="product-description">
                        <h5 class="mb-3"><i class="fas fa-th-large"></i> Sản phẩm liên quan:</h5>
                    </div>
                    <div class="row g-4 justify-content-start">
                        @foreach($relatedProducts as $relatedProduct)
                            <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                <div class="card product-card h-100 related-product-card">
                                    <div class="product-image">
                                        @if($relatedProduct->display_url && ($relatedProduct->image_exists ?? false))
                                            <img src="{{ $relatedProduct->display_url }}" 
                                                 alt="{{ $relatedProduct->content }}" 
                                                 class="card-img-top"
                                                 style="height: 170px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('images/home/home-1.jpg') }}" 
                                                 alt="{{ $relatedProduct->content }}" 
                                                 class="card-img-top"
                                                 style="height: 170px; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="card-body">
                                        <h6 class="card-title">{{ Str::limit($relatedProduct->content, 50) }}</h6>
                                        @if($relatedProduct->price)
                                            <p class="text-danger fw-bold">{{ number_format($relatedProduct->price, 0, ',', '.') }}₫</p>
                                        @endif
                                        <a href="{{ route('product.detail', $relatedProduct->images_id) }}" class="btn btn-sm btn-outline-primary w-100">
                                            Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

@push('scripts')
<script>
    let currentImageIndex = -1; // -1 = ảnh chính, 0-2 = ảnh phụ
    let isTransitioning = false;
    const mainImageUrl = '{{ $product->display_url ?? "" }}';
    
    function changeMainImage(imageUrl, index) {
        if (isTransitioning) return; // Tránh spam click
        if (currentImageIndex === index) return; // Nếu đã là ảnh hiện tại
        
        isTransitioning = true;
        const mainImage = document.getElementById('mainProductImage');
        
        // Thay đổi ảnh ngay lập tức không có fade effect
        mainImage.src = imageUrl;
        currentImageIndex = index;
        
        // Cập nhật active state cho các thumbnail
        updateActiveThumbnail(index);
        
        // Reset flag ngay
        setTimeout(function() {
            isTransitioning = false;
        }, 100);
    }
    
    function updateActiveThumbnail(activeIndex) {
        // Reset tất cả các thumbnail
        const thumbnails = document.querySelectorAll('.sub-image-wrapper');
        thumbnails.forEach(function(thumb) {
            const index = parseInt(thumb.dataset.index);
            thumb.style.borderColor = '#ddd';
            thumb.style.borderWidth = '2px';
            thumb.style.boxShadow = 'none';
            const overlay = thumb.querySelector('.sub-image-overlay');
            if (overlay) {
                overlay.style.opacity = '0';
            }
            // Remove active class
            thumb.classList.remove('active');
        });
        
        // Highlight thumbnail được chọn
        const selectedThumb = document.querySelector('.sub-thumb-' + (activeIndex === -1 ? 'main' : activeIndex));
        if (selectedThumb) {
            selectedThumb.style.borderColor = '#007bff';
            selectedThumb.style.borderWidth = '3px';
            selectedThumb.style.boxShadow = '0 4px 12px rgba(0,123,255,0.4)';
            selectedThumb.classList.add('active');
        }
    }
    
    // Hover effect cho thumbnail với animation mượt mà + đồng bộ kích thước & số lượng
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.sub-image-wrapper');
        thumbnails.forEach(function(thumb) {
            const overlay = thumb.querySelector('.sub-image-overlay');
            const overlayIcon = overlay ? overlay.querySelector('div') : null;
            
            thumb.addEventListener('mouseenter', function() {
                if (overlay) {
                    overlay.style.opacity = '1';
                }
                if (overlayIcon) {
                    setTimeout(function() {
                        overlayIcon.style.transform = 'scale(1)';
                    }, 50);
                }
                this.style.borderColor = '#007bff';
                this.style.transform = 'scale(1.05) translateY(-3px)';
                this.style.boxShadow = '0 6px 15px rgba(0,123,255,0.4)';
            });
            
            thumb.addEventListener('mouseleave', function() {
                if (overlay) {
                    overlay.style.opacity = '0';
                }
                if (overlayIcon) {
                    overlayIcon.style.transform = 'scale(0.8)';
                }
                // Chỉ reset về mặc định nếu không phải là ảnh đang được chọn
                if (currentImageIndex !== parseInt(this.dataset.index)) {
                    this.style.borderColor = '#ddd';
                    this.style.transform = 'scale(1)';
                    this.style.boxShadow = 'none';
                }
            });
        });

        // Đồng bộ giá trị kích thước & số lượng sang các form hành động
        const sizeSelect = document.getElementById('selectedSize');
        const quantityInput = document.getElementById('selectedQuantity');
        const actionForms = document.querySelectorAll('.product-actions form');

        function syncOptionsToForms() {
            actionForms.forEach(function(form) {
                let sizeInput = form.querySelector('input[name="size"]');
                if (!sizeInput) {
                    sizeInput = document.createElement('input');
                    sizeInput.type = 'hidden';
                    sizeInput.name = 'size';
                    form.appendChild(sizeInput);
                }

                let qtyInput = form.querySelector('input[name="quantity"]');
                if (!qtyInput) {
                    qtyInput = document.createElement('input');
                    qtyInput.type = 'hidden';
                    qtyInput.name = 'quantity';
                    form.appendChild(qtyInput);
                }

                if (sizeSelect) {
                    sizeInput.value = sizeSelect.value || '';
                }

                const qtyValue = quantityInput && quantityInput.value ? parseInt(quantityInput.value, 10) : 1;
                qtyInput.value = isNaN(qtyValue) || qtyValue < 1 ? 1 : qtyValue;
            });
        }

        if (quantityInput) {
            quantityInput.addEventListener('input', function() {
                if (this.value === '' || parseInt(this.value, 10) < 1) {
                    this.value = 1;
                }
                syncOptionsToForms();
            });
        }

        if (sizeSelect) {
            sizeSelect.addEventListener('change', syncOptionsToForms);
        }

        actionForms.forEach(function(form) {
            form.addEventListener('submit', function() {
                syncOptionsToForms();
            });
        });

        syncOptionsToForms();
    });

</script>
@endpush

<style>
    .product-detail-section {
        background-color: #f8f9fa;
        min-height: 100vh;
    }
    .product-card {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s ease;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .sub-image-thumbnail {
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .sub-image-wrapper {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }
    .sub-image-wrapper:hover .sub-image-thumbnail {
        transform: scale(1.1);
    }
    .sub-image-wrapper:hover .sub-image-overlay {
        opacity: 1 !important;
    }
    .sub-image-wrapper:hover .sub-image-overlay > div {
        transform: scale(1) !important;
    }
    .main-product-image {
        transition: opacity 0.6s cubic-bezier(0.4, 0, 0.2, 1), transform 0.6s cubic-bezier(0.4, 0, 0.2, 1) !important;
        will-change: opacity, transform;
    }
    .description-box {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border-left: 4px solid #007bff;
    }
    .description-box:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transform: translateY(-3px);
        border-left-color: #0056b3;
    }
    .main-image {
        animation: fadeInUp 0.6s ease-out;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .product-info {
        animation: fadeInRight 0.6s ease-out;
    }
    @keyframes fadeInRight {
        from {
            opacity: 0;
            transform: translateX(-20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    .related-product-card {
        display: flex;
        flex-direction: column;
    }
    .related-product-card .card-body {
        display: flex;
        flex-direction: column;
    }
    .related-product-card .card-body .btn {
        margin-top: auto;
    }
</style>
@endsection

