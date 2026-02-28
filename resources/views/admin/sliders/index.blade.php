@extends('admin.layout')

@section('title', 'Quản lý Slider')

@section('content')
<div class="admin-header">
    <h2><i class="fas fa-images"></i> Quản lý Slider</h2>
    <a href="{{ route('admin.sliders.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Thêm Slider Mới
    </a>
</div>

<div class="admin-card">
    <!-- Filter và Search Form -->
    <form method="GET" action="{{ route('admin.sliders.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <select name="type" class="form-select">
                    <option value="">Tất cả loại</option>
                    <option value="home" {{ request('type') == 'home' ? 'selected' : '' }}>Trang chủ</option>
                    <option value="promotion" {{ request('type') == 'promotion' ? 'selected' : '' }}>Khuyến mãi</option>
                </select>
            </div>
            <div class="col-md-6">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm slider..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
        </div>
    </form>

    <!-- Sliders Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tiêu đề</th>
                    <th>Loại</th>
                    <th>Thứ tự</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sliders as $slider)
                <tr>
                    <td>{{ $slider->id }}</td>
                    <td>
                        @if($slider->image_url)
                            <img src="{{ route('storage.serve', ['path' => $slider->image_url]) }}" 
                                 alt="{{ $slider->title }}" 
                                 class="img-preview" 
                                 style="max-width: 100px; max-height: 60px; object-fit: cover; border-radius: 4px;">
                        @else
                            <span class="text-muted">Không có ảnh</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $slider->title }}</strong>
                        @if($slider->description)
                            <br><small class="text-muted">{{ Str::limit($slider->description, 50) }}</small>
                        @endif
                    </td>
                    <td>
                        @if($slider->type == 'home')
                            <span class="badge bg-info">Trang chủ</span>
                        @else
                            <span class="badge bg-warning">Khuyến mãi</span>
                        @endif
                    </td>
                    <td>{{ $slider->order }}</td>
                    <td>
                        @if($slider->status)
                            <span class="badge bg-success">Hiển thị</span>
                        @else
                            <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </td>
                    <td>{{ $slider->created_at->format('d/m/Y') }}</td>
                    <td>
                        <a href="{{ route('admin.sliders.edit', $slider->id) }}" class="btn btn-sm btn-primary" title="Chỉnh sửa">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('admin.sliders.destroy', $slider->id) }}" method="POST" class="d-inline" 
                              onsubmit="return confirm('Bạn có chắc chắn muốn xóa slider này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger" title="Xóa">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center py-4">
                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                        <p class="text-muted">Chưa có slider nào.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($sliders->hasPages())
    <div class="mt-4">
        {{ $sliders->appends(request()->query())->links() }}
    </div>
    @endif
</div>
@endsection

