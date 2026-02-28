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
                <h1 class="page-title text-center mb-5">Sản phẩm bán chạy</h1>
            </div>
        </div>
        <div class="row">
            @if(isset($products) && $products->isNotEmpty())
                @foreach($products as $index => $product)
                @php
                    $productId = $product->images_id ?? ($index + 1);
                    $favoriteCount = \App\Models\Favorite::where('product_id', $productId)->count();
                    $isFavorite = false;
                    if (Auth::check()) {
                        $isFavorite = \App\Models\Favorite::where('user_id', Auth::id())
                            ->where('product_id', $productId)
                            ->exists();
                    }
                @endphp
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="product-card product-card-processed">
                        <div class="product-image" style="position: relative; width: 100%; aspect-ratio: 1 / 1; overflow: hidden; background-color: #f8f9fa;">
                            <a href="{{ route('product.detail', $productId) }}" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                                @if(isset($product->display_url) && $product->display_url && ($product->image_exists ?? false))
                                <img src="{{ $product->display_url }}" alt="{{ $product->content ?? 'Sản phẩm ' . ($index + 1) }}" class="img-fluid product-main-image" style="width: 100%; height: 100%; object-fit: cover; display: block;" data-product-id="{{ $productId }}" data-original-src="{{ $product->display_url }}" data-sub-image="{{ isset($product->sub_images_urls) && is_array($product->sub_images_urls) && count($product->sub_images_urls) > 0 ? $product->sub_images_urls[0] : '' }}">
                                @else
                                <img src="{{ asset('images/home/' . (($index % 14) + 1) . '.png') }}" alt="{{ $product->content ?? 'Sản phẩm ' . ($index + 1) }}" class="img-fluid product-main-image" style="width: 100%; height: 100%; object-fit: cover; display: block;" data-product-id="{{ $productId }}" data-original-src="{{ asset('images/home/' . (($index % 14) + 1) . '.png') }}" data-sub-image="">
                                @endif
                            </a>
                            {{-- Ẩn ảnh phụ - chỉ hiển thị ảnh chính --}}
                        </div>
                        <div class="product-info" style="margin-top: 12px;">
                            <h5 class="product-name"><a href="{{ route('product.detail', $productId) }}" style="text-decoration: none; color: inherit;">{{ $product->content ?? 'Sản phẩm bán chạy ' . ($index + 1) }}</a></h5>
                            @if(isset($product->product_type) && $product->product_type)
                                <p class="product-type mb-1" style="color: #999; font-size: 0.875rem; margin: 0;">Sản phẩm {{ strtolower($product->product_type) }}</p>
                            @endif
                            <p class="product-price">{{ number_format($product->price ?? 139000, 0, ',', '.') }}₫</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <form action="{{ route('cart.add') }}" method="POST" class="flex-grow-1 me-2">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $productId }}">
                                    <input type="hidden" name="name" value="{{ $product->content ?? 'Sản phẩm bán chạy ' . ($index + 1) }}">
                                    <input type="hidden" name="price" value="{{ $product->price ?? 139000 }}">
                                    @if(isset($product->display_url) && $product->display_url)
                                        <input type="hidden" name="image" value="{{ $product->display_url }}">
                                    @endif
                                    <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                                        <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                                    </button>
                                </form>
                                @if($productId)
                                <div class="favorite-display {{ $isFavorite ? 'active' : '' }}" 
                                     data-product-id="{{ $productId }}"
                                     onclick="toggleFavoriteDetail({{ $productId }}, this)"
                                     title="{{ $isFavorite ? 'Bỏ yêu thích' : 'Thêm vào yêu thích' }}">
                                    <i class="{{ $isFavorite ? 'fas' : 'far' }} fa-heart"></i>
                                    <span class="favorite-count">{{ $favoriteCount }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
            @for($i = 1; $i <= 12; $i++)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="product-card product-card-processed">
                    <div class="product-image" style="position: relative; width: 100%; aspect-ratio: 1 / 1; overflow: hidden; background-color: #f8f9fa;">
                        <a href="{{ route('product.detail', $i) }}" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                            <img src="{{ asset('images/home/' . (($i % 14) + 1) . '.png') }}" alt="Sản phẩm {{ $i }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        </a>
                        <div class="product-badge bg-danger" style="position: absolute; top: 10px; right: 10px; z-index: 10;">
                            <i class="fas fa-fire"></i> Hot
                        </div>
                    </div>
                    <div class="product-info">
                        <h5 class="product-name"><a href="{{ route('product.detail', $i) }}" style="text-decoration: none; color: inherit;">Sản phẩm bán chạy {{ $i }}</a></h5>
                        <p class="product-price">139,000₫</p>
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $i }}">
                            <input type="hidden" name="name" value="Sản phẩm bán chạy {{ $i }}">
                            <input type="hidden" name="price" value="139000">
                            <button class="btn btn-outline-primary btn-sm w-100">Thêm vào giỏ</button>
                        </form>
                    </div>
                </div>
            </div>
            @endfor
            @endif
        </div>
        <div class="row">
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Trước</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Sau</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
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

