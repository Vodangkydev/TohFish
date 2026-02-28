@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<div class="admin-header">
    <h2><i class="fas fa-tachometer-alt"></i> Dashboard</h2>
</div>

<div class="row mb-4">
    <div class="col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-number">{{ $postsCount }}</div>
            <div class="stat-label">Tổng số Bài Post</div>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-primary mt-3">
                <i class="fas fa-eye"></i> Xem tất cả
            </a>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="stat-card">
            <div class="stat-number">{{ $imagesCount }}</div>
            <div class="stat-label">Tổng số Sản Phẩm</div>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-primary mt-3">
                <i class="fas fa-eye"></i> Xem tất cả
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="admin-card">
            <h5><i class="fas fa-newspaper"></i> Bài Post Mới Nhất</h5>
            <hr>
            @if($latestPosts->isEmpty())
                <p class="text-muted">Chưa có bài post nào.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nội dung</th>
                                <th>Ngày tạo</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestPosts as $post)
                            <tr>
                                <td>{{ Str::limit($post->content ?? $post->description, 50) }}</td>
                                <td>{{ $post->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <a href="{{ route('admin.posts.edit', $post->post_id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-primary">Xem tất cả</a>
                </div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <div class="admin-card">
            <h5><i class="fas fa-box"></i> Sản Phẩm Mới Nhất</h5>
            <hr>
            @if($latestImages->isEmpty())
                <p class="text-muted">Chưa có sản phẩm nào.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Hình ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($latestImages as $image)
                            <tr>
                                <td>
                                    @if(isset($image->display_url) && $image->display_url)
                                        @if($image->image_exists ?? false)
                                            <img src="{{ $image->display_url }}" alt="" class="img-preview">
                                        @else
                                            <span class="text-danger" title="File không tồn tại">
                                                <i class="fas fa-exclamation-triangle"></i>
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">Không có ảnh</span>
                                    @endif
                                </td>
                                <td>{{ Str::limit($image->content, 30) }}</td>
                                <td>
                                    <a href="{{ route('admin.products.edit', $image->images_id) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">Xem tất cả</a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

