@php
    $productId = $product->images_id ?? ($index ?? 0);
    $productName = $product->content ?? 'Sản phẩm ' . ($index ?? 0);
    $productPrice = $product->price ?? 135000;
    $productImage = $product->display_url ?? asset('images/home/' . ((($index ?? 0) % 14) + 1) . '.png');
    $imageExists = $product->image_exists ?? false;
@endphp

<div class="col-lg-3 col-md-4 col-sm-6 mb-4">
    <div class="product-card">
        <div class="product-image">
            <a href="{{ route('product.detail', $productId) }}" style="display: block; position: relative;">
                @if($productImage && $imageExists)
                <img src="{{ $productImage }}" alt="{{ $productName }}" class="img-fluid">
                @else
                <img src="{{ asset('images/home/' . ((($index ?? 0) % 14) + 1) . '.png') }}" alt="{{ $productName }}" class="img-fluid">
                @endif
            </a>
        </div>
        <div class="product-info">
            <h5 class="product-name">{{ $productName }}</h5>
            <p class="product-price">{{ number_format($productPrice, 0, ',', '.') }}₫</p>
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <input type="hidden" name="product_id" value="{{ $productId }}">
                <input type="hidden" name="name" value="{{ $productName }}">
                <input type="hidden" name="price" value="{{ $productPrice }}">
                @if($productImage)
                    <input type="hidden" name="image" value="{{ $productImage }}">
                @endif
                <button class="btn btn-outline-primary btn-sm w-100">
                    <i class="fas fa-cart-plus"></i> Thêm vào giỏ
                </button>
            </form>
        </div>
    </div>
</div>

