@extends('layouts.app')

@section('title', ($post->content ?? $post->description ?? 'Tin tức') . ' - TOH fish')

@section('content')
<section class="article-detail-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('blog.index') }}">Blog</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->content ?? $post->description ?? 'Tin tức', 50) }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12">
                <div class="article-content bg-light p-4 p-md-5 rounded shadow mb-4">
                    <div class="mb-3">
                        <a href="{{ route('blog.index') }}" class="btn btn-sm btn-outline-secondary mb-3">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                    </div>

                    <!-- Ảnh chính -->
                    @if(isset($post->display_url) && $post->display_url && ($post->image_exists ?? false))
                    <div class="article-main-image mb-4">
                        <img src="{{ $post->display_url }}" 
                             alt="{{ $post->content ?? $post->description }}" 
                             class="img-fluid w-100 rounded"
                             style="max-height: 500px; object-fit: cover; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                    </div>
                    @endif

                    <!-- Tiêu đề -->
                    <h1 class="mb-4">{{ $post->content ?? $post->description ?? 'Tin tức' }}</h1>
                    
                    <!-- Meta information -->
                    <div class="article-meta mb-4 pb-3 border-bottom">
                        <span class="me-4 text-muted">
                            <i class="far fa-calendar"></i> {{ $post->created_at ? $post->created_at->format('d/m/Y') : '' }}
                        </span>
                        @if($post->view)
                        <span class="text-muted">
                            <i class="far fa-eye"></i> {{ number_format($post->view) }} lượt xem
                        </span>
                        @endif
                    </div>

                    <!-- Mô tả ngắn -->
                    @if($post->description && $post->description !== $post->content)
                    <div class="article-description mb-4">
                        <p class="lead text-muted">{{ $post->description }}</p>
                    </div>
                    @endif

                    <!-- Nội dung chi tiết -->
                    <div class="article-detail-content mb-4">
                        {!! $postDetail->content ?? '' !!}
                    </div>
                </div>

                <!-- Bài viết liên quan -->
                @if(isset($posts) && $posts->count() > 0)
                <div class="related-articles bg-light p-4 rounded shadow">
                    <h4 class="mb-4"><i class="fas fa-newspaper"></i> Bài viết liên quan</h4>
                    <div class="row g-3">
                        @foreach($posts as $relatedPost)
                        <div class="col-md-6">
                            <div class="related-article-card p-3 bg-white rounded border">
                                <div class="d-flex align-items-start">
                                    @if(isset($relatedPost->display_url) && $relatedPost->display_url && ($relatedPost->image_exists ?? false))
                                    <div class="related-article-image me-3" style="flex-shrink: 0; width: 100px; height: 80px; overflow: hidden; border-radius: 8px;">
                                        <img src="{{ $relatedPost->display_url }}" 
                                             alt="{{ $relatedPost->content ?? $relatedPost->description }}" 
                                             class="img-fluid w-100 h-100"
                                             style="object-fit: cover;">
                                    </div>
                                    @endif
                                    <div class="related-article-content flex-grow-1">
                                        <h6 class="mb-2">
                                            <a href="{{ route('chi-tiet-bai-viet', $relatedPost->post_id) }}" 
                                               class="text-decoration-none text-dark">
                                                {{ Str::limit($relatedPost->content ?? $relatedPost->description ?? 'Tin tức', 60) }}
                                            </a>
                                        </h6>
                                        <div class="text-muted small">
                                            <span class="me-3">
                                                <i class="far fa-calendar"></i> {{ $relatedPost->created_at ? $relatedPost->created_at->format('d/m/Y') : '' }}
                                            </span>
                                            @if($relatedPost->view)
                                            <span>
                                                <i class="far fa-eye"></i> {{ number_format($relatedPost->view) }}
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .article-detail-content {
        font-size: 16px;
        line-height: 1.8;
        color: #333;
    }
    
    .article-detail-content p {
        margin-bottom: 15px;
    }
    
    .article-detail-content h1,
    .article-detail-content h2,
    .article-detail-content h3,
    .article-detail-content h4,
    .article-detail-content h5,
    .article-detail-content h6 {
        color: #0066cc;
        margin-top: 25px;
        margin-bottom: 15px;
        font-weight: 600;
    }
    
    .article-detail-content strong,
    .article-detail-content b {
        font-weight: 700;
        color: #0066cc;
    }
    
    .article-detail-content ul,
    .article-detail-content ol {
        margin-left: 25px;
        margin-bottom: 15px;
    }
    
    .article-detail-content li {
        margin-bottom: 8px;
    }
    
    .article-detail-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 20px 0;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    
    .article-detail-content a {
        color: #0066cc;
        text-decoration: underline;
    }
    
    .article-detail-content a:hover {
        color: #0052a3;
    }
    
    .article-detail-content blockquote {
        border-left: 4px solid #0066cc;
        padding-left: 20px;
        margin: 20px 0;
        font-style: italic;
        color: #666;
    }
    
    .related-article-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .related-article-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1) !important;
    }
    
    .related-article-card a:hover {
        color: #0066cc !important;
    }
</style>
@endpush

