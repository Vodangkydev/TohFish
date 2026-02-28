@extends('layouts.app')

@section('title', 'TOH Farm - Nông Trại - TOH Blog')

@section('content')
<section class="blog-category-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title text-center mb-5">TOH Farm - Nông Trại</h1>
                <p class="text-center text-muted mb-5">Khám phá nông trại TOH fish - Nơi nuôi dưỡng cá lóc bông sạch</p>
            </div>
        </div>
        
        <div class="row mb-5">
            <div class="col-lg-12">
                <div class="farm-intro bg-light p-5 rounded">
                    <h3 class="mb-4">Giới thiệu về TOH Farm</h3>
                    <p>TOH Farm là nông trại chăn nuôi cá lóc bông sạch của chúng tôi, nằm tại Đồng Nai. Với quy trình chăn nuôi hiện đại và an toàn, chúng tôi cam kết cung cấp những sản phẩm cá chất lượng cao, đảm bảo an toàn vệ sinh thực phẩm.</p>
                </div>
            </div>
        </div>
        
        <div class="row">
            @forelse($posts as $post)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="news-card">
                    <div class="news-image-container">
                        @if(isset($post->display_url) && $post->display_url && ($post->image_exists ?? false))
                        <img src="{{ $post->display_url }}" alt="{{ $post->title ?? $post->description }}" class="img-fluid">
                        @else
                        <img src="{{ asset('images/bai-viet/bai-viet-' . (($loop->index % 16) + 1) . '.png') }}" alt="{{ $post->title ?? $post->description }}" class="img-fluid">
                        @endif
                        <div class="news-overlay">
                            <a href="{{ route('chi-tiet-bai-viet', $post->post_id) }}" class="btn btn-light btn-sm">
                                <i class="fas fa-tractor"></i> Xem thêm
                            </a>
                        </div>
                    </div>
                    <div class="news-content">
                        <div class="news-meta">
                            <span class="news-date"><i class="far fa-calendar"></i> {{ $post->created_at ? $post->created_at->format('d/m/Y') : '' }}</span>
                        </div>
                        <h5>{{ Str::limit($post->title ?? $post->description ?? 'TOH Farm', 60) }}</h5>
                        <p class="text-muted">{{ Str::limit($post->content ?? $post->description ?? '', 120) }}</p>
                        <a href="{{ route('chi-tiet-bai-viet', $post->post_id) }}" class="btn btn-link p-0">
                            Đọc thêm <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Chưa có bài viết về nông trại.
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

