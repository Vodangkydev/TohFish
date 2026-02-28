@extends('admin.layout')

@section('title', 'Quản lý Đơn Hàng')

@section('content')
<div class="admin-header">
    <h2><i class="fas fa-shopping-cart"></i> Quản lý Đơn Hàng</h2>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Tổng đơn</h5>
                <h3 class="text-primary">{{ $stats['total'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Chờ xử lý</h5>
                <h3 class="text-warning">{{ $stats['pending'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Đang xử lý</h5>
                <h3 class="text-info">{{ $stats['processing'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Hoàn thành</h5>
                <h3 class="text-success">{{ $stats['completed'] }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card text-center">
            <div class="card-body">
                <h5 class="card-title">Đã hủy</h5>
                <h3 class="text-danger">{{ $stats['cancelled'] }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="admin-card">
    <!-- Search and Filter Form -->
    <form method="GET" action="{{ route('admin.orders.index') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4"> 
                
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm mã đơn, tên, SĐT, email..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-3">
                <select name="payment_method" class="form-select">
                    <option value="">Tất cả phương thức</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>COD</option>
                    <option value="bank" {{ request('payment_method') == 'bank' ? 'selected' : '' }}>Chuyển khoản</option>
                    <option value="momo" {{ request('payment_method') == 'momo' ? 'selected' : '' }}>Ví điện tử</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-search"></i> Tìm kiếm
                </button>
            </div>
        </div>
    </form>

    <!-- Orders Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Mã đơn</th>
                    <th>Khách hàng</th>
                    <th>Số điện thoại</th>
                    <th>Số lượng SP</th>
                    <th>Tổng tiền</th>
                    <th>Thanh toán</th>
                    <th>Trạng thái</th>
                    <th>Ngày đặt</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td><strong>{{ $order->code }}</strong></td>
                    <td>{{ $order->shipping_name ?? $order->user->name ?? 'N/A' }}</td>
                    <td>{{ $order->shipping_phone }}</td>
                    <td>{{ $order->items->sum('quantity') }}</td>
                    <td><strong class="text-danger">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong></td>
                    <td>
                        @if($order->payment_method == 'cash')
                            <span class="badge bg-secondary">COD</span>
                        @elseif($order->payment_method == 'bank')
                            <span class="badge bg-info">Chuyển khoản</span>
                        @elseif($order->payment_method == 'momo')
                            <span class="badge bg-success">Ví điện tử</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST" class="d-inline status-update-form">
                            @csrf
                            @method('PUT')
                             <select name="status" class="form-select form-select-sm status-select" onchange="this.form.submit()">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </form>
                    </td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary" title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center text-muted py-4">
                        <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                        <p>Chưa có đơn hàng nào.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->links('partials.admin-pagination') }}
    </div>
</div>

@push('styles')
<style>
    .status-select {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 1px 4px;
        font-size: 11px;
        cursor: pointer;
        transition: all 0.3s ease;
        width: 100px;
        height: 24px;
        max-width: 100px;
    }
    
    .status-select:hover {
        border-color: #007bff;
    }
    
    /* Bỏ màu cho các option khi dropdown mở */
    .status-select option {
        background-color: #fff !important;
        color: #000 !important;
    }
    
    .status-update-form {
        margin: 0;
        display: inline-block;
    }
    
    /* Giảm kích thước icon mũi tên trong phân trang ở trang Đơn Hàng */
    .admin-card nav[role="navigation"] svg {
        width: 0.75rem;
        height: 0.75rem;
    }
    
    .admin-card .pagination .page-link i {
        font-size: 0.75rem;
    }
</style>
@endpush
@endsection

