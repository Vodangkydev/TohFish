@extends('admin.layout')

@section('title', 'Quản lý Sản Phẩm')

@section('content')
<div class="admin-header">
    <h2><i class="fas fa-box"></i> Quản lý Sản Phẩm</h2>
    <a href="{{ route('admin.products.create') }}" class="btn btn-success">
        <i class="fas fa-plus"></i> Thêm Sản Phẩm Mới
    </a>
</div>

<div class="admin-card">
    <!-- Search Form -->
    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-8">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
        </div>
    </form>

    <!-- Products Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Loại hàng</th>
                    <th>Giá</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr>
                    <td>{{ $product->images_id }}</td>
                    <td>
                        @if(isset($product->display_url) && $product->display_url)
                            @if($product->image_exists ?? false)
                                <img src="{{ $product->display_url }}" alt="" class="img-preview" 
                                     onerror="console.error('Image failed to load:', '{{ $product->display_url }}'); this.style.border='2px solid red';">
                                @if(isset($product->raw_image_path))
                                    <br><small class="text-muted" style="font-size: 10px;">{{ $product->raw_image_path }}</small>
                                @endif
                            @else
                                <span class="text-danger" title="File không tồn tại: {{ $product->raw_image_path ?? 'N/A' }}">
                                    <i class="fas fa-exclamation-triangle"></i> File không tồn tại
                                </span>
                                @if(isset($product->raw_image_path))
                                    <br><small class="text-muted" style="font-size: 10px;">DB: {{ $product->raw_image_path }}</small>
                                @endif
                            @endif
                        @else
                            <span class="text-muted">Không có ảnh</span>
                        @endif
                    </td>
                    <td>{{ $product->content ?? 'N/A' }}</td>
                    <td>
                        @if($product->product_type)
                            <span class="badge bg-info">{{ $product->product_type }}</span>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>
                        @if($product->price)
                            <strong class="text-success">{{ number_format($product->price, 0, ',', '.') }}₫</strong>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <div class="btn-group" role="group">
                            <a href="{{ route('admin.products.edit', $product->images_id) }}" class="btn btn-sm btn-primary" title="Sửa">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->images_id) }}" method="POST" class="d-inline" onsubmit="return confirmDelete('Bạn có chắc chắn muốn xóa sản phẩm này?')">
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
                    <td colspan="7" class="text-center text-muted py-4">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p>Không có sản phẩm nào.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection

