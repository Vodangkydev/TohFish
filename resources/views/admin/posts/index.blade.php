@extends('admin.layout')

@section('title', 'Quản lý Bài Post')

@section('content')
<div class="admin-header">
    <h2><i class="fas fa-newspaper"></i> Quản lý Bài Post</h2>
    <a href="{{ route('admin.posts.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Thêm Bài Post Mới
    </a>
</div>

<div class="admin-card">
    <!-- Search Form -->
    <form method="GET" action="{{ route('admin.posts.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm bài post..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
        </div>
    </form>

    <!-- Posts Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Nội dung</th>
                    <th>Mô tả</th>
                    <th>Lượt xem</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($posts as $post)
                <tr>
                    <td>{{ $post->post_id }}</td>
                    <td>
                        @if($post->image_url)
                            <img src="{{ route('storage.serve', ['path' => $post->image_url]) }}" alt="" class="img-preview">
                        @else
                            <span class="text-muted">Không có ảnh</span>
                        @endif
                    </td>
                    <td>{{ Str::limit($post->content ?? 'N/A', 50) }}</td>
                    <td>{{ Str::limit($post->description ?? 'N/A', 50) }}</td>
                    <td>{{ $post->view ?? 0 }}</td>
                    <td>
                        @if($post->status ?? 0)
                            <span class="badge bg-success">Hiển thị</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td>{{ $post->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.posts.edit', $post->post_id) }}" class="btn btn-sm btn-primary" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.posts.destroy', $post->post_id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete('Bạn có chắc chắn muốn xóa bài post này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p>Không có bài post nào.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($posts->hasPages())
        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection

