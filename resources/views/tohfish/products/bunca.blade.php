@extends('layouts.app')

@section('title', 'Bún Cá TOH - TOH fish')

@section('content')
@php
    $basePath = request()->getBasePath();
@endphp
<section class="products-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title text-center mb-5">Bún Cá TOH</h1>
                <p class="text-center text-muted mb-5">Bún cá thơm ngon, đậm đà từ TOH fish</p>
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
                            <h5 class="product-name"><a href="{{ route('product.detail', $product->images_id ?? ($index + 1)) }}" style="text-decoration: none; color: inherit;">{{ $product->content ?? 'Bún cá TOH ' . ($index + 1) }}</a></h5>
                            @if(isset($product->product_type) && $product->product_type)
                                <p class="product-type mb-1" style="color: #999; font-size: 0.875rem; margin: 0;">Sản phẩm {{ strtolower($product->product_type) }}</p>
                            @endif
                            <p class="product-price">{{ number_format($product->price ?? 98000, 0, ',', '.') }}₫</p>
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
                                    <input type="hidden" name="name" value="{{ $product->content ?? 'Bún cá TOH ' . ($index + 1) }}">
                                    <input type="hidden" name="price" value="{{ $product->price ?? 98000 }}">
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
            @for($i = 1; $i <= 12; $i++)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="product-card">
                    <div class="product-image" style="position: relative; width: 100%; aspect-ratio: 1 / 1; overflow: hidden; background-color: #f8f9fa;">
                        <a href="{{ route('product.detail', $i) }}" style="display: block; position: absolute; top: 0; left: 0; width: 100%; height: 100%;">
                            <img src="{{ asset('images/home/' . (($i % 14) + 1) . '.png') }}" alt="Bún cá {{ $i }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: cover; display: block;">
                        </a>
                    </div>
                    <div class="product-info" style="margin-top: 12px;">
                        <h5 class="product-name"><a href="{{ route('product.detail', $i) }}" style="text-decoration: none; color: inherit;">Bún cá TOH {{ $i }}</a></h5>
                        <p class="product-price">98,000₫</p>
                        <form action="{{ route('cart.add') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $i }}">
                            <input type="hidden" name="name" value="Bún cá TOH {{ $i }}">
                            <input type="hidden" name="price" value="98000">
                            <button class="btn btn-outline-primary btn-sm w-100">Thêm vào giỏ</button>
                        </form>
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
                                <a class="page-link" href="{{ $products->previousPageUrl() }}">Trước</a>
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
                                <a class="page-link" href="{{ $products->url(1) }}">1</a>
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
                                    <a class="page-link" href="{{ $products->url($page) }}">{{ $page }}</a>
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
                                <a class="page-link" href="{{ $products->url($lastPage) }}">{{ $lastPage }}</a>
                            </li>
                        @endif

                        {{-- Nút Sau --}}
                        @if($products->hasMorePages())
                            <li class="page-item">
                                <a class="page-link" href="{{ $products->nextPageUrl() }}">Sau</a>
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
