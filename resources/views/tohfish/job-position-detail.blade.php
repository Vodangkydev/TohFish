@extends('layouts.app')

@section('title', $position->title . ' - Tuyển Dụng - TOH fish')

@section('content')
<section class="recruitment-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <nav aria-label="breadcrumb" class="mb-4">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('recruitment') }}">Tuyển dụng</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $position->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="recruitment-info bg-light p-5 rounded shadow mb-4">
                    <div class="mb-3">
                        <a href="{{ route('recruitment') }}" class="btn btn-sm btn-outline-secondary mb-3">
                            <i class="fas fa-arrow-left"></i> Quay lại danh sách
                        </a>
                    </div>
                    
                    <h1 class="mb-4">{{ $position->title }}</h1>
                    
                    <div class="position-content mb-4">
                        {!! $position->content !!}
                    </div>
                    
                    @if($position->published_at)
                        <p class="text-muted mb-0">
                            <i class="fas fa-calendar"></i> Ngày đăng: {{ $position->published_at->format('d/m/Y') }}
                        </p>
                    @endif
                </div>

                @if($otherPositions && $otherPositions->count() > 0)
                <div class="recruitment-info bg-light p-4 rounded shadow">
                    <h4 class="mb-3">Các vị trí khác:</h4>
                    <ul class="list-unstyled">
                        @foreach($otherPositions as $otherPosition)
                            <li class="mb-2">
                                <a href="{{ route('job-position.detail', $otherPosition->id) }}" class="text-decoration-none d-flex align-items-center justify-content-between p-2 bg-white rounded">
                                    <span><strong>{{ $otherPosition->title }}</strong></span>
                                    <i class="fas fa-chevron-right text-muted"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .position-content {
        font-size: 16px;
        line-height: 1.8;
        color: #333;
    }
    
    .position-content strong,
    .position-content b {
        font-weight: 700;
        color: #0066cc;
    }
    
    .position-content p {
        margin-bottom: 15px;
    }
    
    .position-content ul,
    .position-content ol {
        margin-left: 25px;
        margin-bottom: 15px;
    }
    
    .position-content li {
        margin-bottom: 8px;
    }
    
    .position-content h1,
    .position-content h2,
    .position-content h3,
    .position-content h4 {
        color: #0066cc;
        margin-top: 20px;
        margin-bottom: 15px;
    }
</style>
@endpush

