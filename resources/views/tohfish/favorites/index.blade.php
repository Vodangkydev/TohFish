@extends('layouts.app')

@section('title', 'Sản phẩm yêu thích - TOH fish')

@section('content')
<section class="products-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title text-center mb-5">Sản phẩm yêu thích</h1>
                <p class="text-center text-muted mb-5">Danh sách sản phẩm bạn đã yêu thích</p>
            </div>
        </div>
        <div class="row">
            @if(isset($favorites) && $favorites->count() > 0)
                @foreach($favorites as $favorite)
                    @if($favorite->product)
                        @php
                            $product = $favorite->product;
                            $favoriteCount = \App\Models\Favorite::where('product_id', $product->images_id)->count();
                            $isFavorite = true; // Đã là favorite rồi
                        @endphp
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="product-card">
                                <div class="product-image" style="position: relative; width: 100%; aspect-ratio: 1 / 1; overflow: hidden; background-color: #f8f9fa;">
                                    <a href="{{ route('product.detail', $product->images_id) }}" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                                        @if($product->display_url && ($product->image_exists ?? false))
                                            <img src="{{ $product->display_url }}" alt="{{ $product->content }}" class="img-fluid product-main-image" style="width: 100%; height: 100%; object-fit: cover; display: block;" data-product-id="{{ $product->images_id }}" data-original-src="{{ $product->display_url }}" data-sub-image="{{ isset($product->sub_images_urls) && is_array($product->sub_images_urls) && count($product->sub_images_urls) > 0 ? $product->sub_images_urls[0] : '' }}">
                                        @else
                                            <img src="{{ asset('images/home/1.png') }}" alt="{{ $product->content }}" class="img-fluid product-main-image" style="width: 100%; height: 100%; object-fit: cover; display: block;" data-product-id="{{ $product->images_id }}" data-original-src="{{ asset('images/home/1.png') }}" data-sub-image="">
                                        @endif
                                    </a>
                                    {{-- Ẩn ảnh phụ - chỉ hiển thị khi hover vào ảnh chính --}}
                                </div>
                                <div class="product-info" style="margin-top: 12px;">
                                    <h5 class="product-name"><a href="{{ route('product.detail', $product->images_id) }}" style="text-decoration: none; color: inherit;">{{ $product->content }}</a></h5>
                                    @if(isset($product->product_type) && $product->product_type)
                                        <p class="product-type mb-1" style="color: #999; font-size: 0.875rem; margin: 0;">Sản phẩm {{ strtolower($product->product_type) }}</p>
                                    @endif
                                    <p class="product-price">{{ number_format($product->price ?? 0, 0, ',', '.') }}₫</p>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="favorite-display active" 
                                             data-product-id="{{ $product->images_id }}"
                                             onclick="toggleFavoriteDetail({{ $product->images_id }}, this)"
                                             title="Bỏ yêu thích">
                                            <i class="fas fa-heart"></i>
                                            <span class="favorite-count">{{ $favoriteCount }}</span>
                                        </div>
                                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="product_id" value="{{ $product->images_id }}">
                                            <input type="hidden" name="name" value="{{ $product->content }}">
                                            <input type="hidden" name="price" value="{{ $product->price ?? 0 }}">
                                            @if($product->display_url)
                                                <input type="hidden" name="image" value="{{ $product->display_url }}">
                                            @endif
                                            <button class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            @else
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-heart" style="font-size: 64px; color: #ddd; margin-bottom: 20px;"></i>
                        <h4 class="text-muted mb-3">Chưa có sản phẩm yêu thích</h4>
                        <p class="text-muted mb-4">Hãy yêu thích các sản phẩm bạn quan tâm để dễ dàng tìm lại sau này.</p>
                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="fas fa-shopping-bag me-2"></i> Xem sản phẩm
                        </a>
                    </div>
                </div>
            @endif
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

