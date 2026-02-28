@extends('layouts.app')

@section('title', 'Công Thức Món Cá - TOH Blog')

@section('content')
<section class="blog-category-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title text-center mb-5">Công Thức Món Cá</h1>
                <p class="text-center text-muted mb-5">Khám phá các công thức nấu món cá ngon và bổ dưỡng</p>
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
                                <i class="fas fa-utensils"></i> Xem công thức
                            </a>
                        </div>
                    </div>
                    <div class="news-content">
                        <div class="news-meta">
                            <span class="news-date"><i class="far fa-calendar"></i> {{ $post->created_at ? $post->created_at->format('d/m/Y') : '' }}</span>
                        </div>
                        <h5>{{ Str::limit($post->title ?? $post->description ?? 'Công thức món cá', 60) }}</h5>
                        <p class="text-muted">{{ Str::limit($post->content ?? $post->description ?? '', 120) }}</p>
                        <a href="{{ route('chi-tiet-bai-viet', $post->post_id) }}" class="btn btn-link p-0">
                            Xem công thức <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="fas fa-info-circle"></i> Chưa có công thức nào.
                </div>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection

