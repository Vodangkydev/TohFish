@extends('layouts.app')

@section('title', 'Chi tiết đơn hàng - TOH fish')

@section('content')
<section class="order-detail-section py-5">
    <div class="container">
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

        <div class="row">
            <div class="col-lg-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="page-title mb-0"><i class="fas fa-shopping-cart"></i> Chi tiết đơn hàng</h1>
                    <button onclick="window.print()" class="btn btn-outline-primary" title="In đơn hàng">
                        <i class="fas fa-print"></i> In đơn hàng
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <!-- Order Items -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-box"></i> Sản phẩm trong đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Hình ảnh</th>
                                        <th>Tên sản phẩm</th>
                                        <th>Giá</th>
                                        <th>Số lượng</th>
                                        <th>Thành tiền</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            @if($item->product_image)
                                                <img src="{{ asset($item->product_image) }}" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                            @else
                                                <img src="{{ asset('images/home/1.png') }}" alt="" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                            @endif
                                        </td>
                                        <td>{{ $item->product_name }}</td>
                                        <td>{{ number_format($item->product_price, 0, ',', '.') }}₫</td>
                                        <td>
                                            <span class="badge bg-primary rounded-pill">x {{ $item->quantity }}</span>
                                        </td>
                                        <td><strong>{{ number_format($item->subtotal, 0, ',', '.') }}₫</strong></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="4" class="text-end"><strong>Tổng cộng:</strong></td>
                                        <td><strong class="text-danger fs-5">{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-user"></i> Thông tin giao hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <strong>Họ và tên:</strong><br>
                                {{ $order->shipping_name ?? $order->user->name ?? 'N/A' }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Số điện thoại:</strong><br>
                                {{ $order->shipping_phone }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Email:</strong><br>
                                {{ $order->email ?? $order->user->email ?? 'N/A' }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Thành phố/Tỉnh:</strong><br>
                                {{ $order->city ?? 'N/A' }}
                            </div>
                            <div class="col-md-6 mb-3">
                                <strong>Quận/Huyện:</strong><br>
                                {{ $order->district ?? 'N/A' }}
                            </div>
                            <div class="col-md-12 mb-3">
                                <strong>Địa chỉ giao hàng:</strong><br>
                                {{ $order->shipping_address }}
                            </div>
                            @if($order->note)
                            <div class="col-md-12 mb-3">
                                <strong>Ghi chú:</strong><br>
                                {{ $order->note }}
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <!-- Order Summary -->
                <div class="card shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-header bg-light">
                        <h5 class="mb-0"><i class="fas fa-info-circle"></i> Thông tin đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Mã đơn hàng:</strong><br>
                            <code>{{ $order->code }}</code>
                        </div>
                        <div class="mb-3">
                            <strong>Ngày đặt hàng:</strong><br>
                            {{ $order->created_at->format('d/m/Y H:i:s') }}
                        </div>
                        <div class="mb-3">
                            <strong>Trạng thái:</strong><br>
                            @if($order->status == 'pending')
                                <span class="badge bg-warning">Chờ xử lý</span>
                            @elseif($order->status == 'processing')
                                <span class="badge bg-info">Đang xử lý</span>
                            @elseif($order->status == 'completed')
                                <span class="badge bg-success">Hoàn thành</span>
                            @elseif($order->status == 'cancelled')
                                <span class="badge bg-danger">Đã hủy</span>
                            @endif
                        </div>
                        <div class="mb-3">
                            <strong>Phương thức thanh toán:</strong><br>
                            @if($order->payment_method == 'cash')
                                <span class="badge bg-secondary">Thanh toán khi nhận hàng (COD)</span>
                            @elseif($order->payment_method == 'bank')
                                <span class="badge bg-info">Chuyển khoản ngân hàng</span>
                            @elseif($order->payment_method == 'momo')
                                <span class="badge bg-success">Ví điện tử (MoMo, ZaloPay)</span>
                            @endif
                        </div>
                        @if($order->payment_method == 'bank' || $order->payment_method == 'momo')
                        <div class="mb-3">
                            <strong>Trạng thái thanh toán:</strong><br>
                            @if($paymentTransaction)
                                @if($paymentTransaction->status == 'pending')
                                    <span class="badge bg-warning">Đang chờ thanh toán</span>
                                    <br><small class="text-muted">Sau khi chuyển khoản, admin sẽ xác nhận trong vài phút. Trạng thái sẽ tự động cập nhật.</small>
                                @elseif($paymentTransaction->status == 'completed')
                                    <span class="badge bg-success"><i class="fas fa-check-circle"></i> Đã thanh toán</span>
                                    @if($paymentTransaction->paid_at)
                                        <br><small class="text-success">Thời gian xác nhận: {{ $paymentTransaction->paid_at->format('d/m/Y H:i:s') }}</small>
                                    @endif
                                @elseif($paymentTransaction->status == 'expired')
                                    <span class="badge bg-danger">Hết hạn thanh toán</span>
                                    <br><small class="text-muted">Thời gian thanh toán đã hết. Nếu đã chuyển khoản, vui lòng liên hệ admin để xác nhận.</small>
                                @elseif($paymentTransaction->status == 'failed')
                                    <span class="badge bg-danger">Thanh toán thất bại</span>
                                @endif
                            @else
                                <span class="badge bg-secondary">Chưa có thông tin</span>
                            @endif
                        </div>
                        @endif
                        <hr>
                        <div class="mb-3">
                            <strong>Tổng tiền:</strong><br>
                            <h4 class="text-danger mb-0">{{ number_format($order->total_amount, 0, ',', '.') }}₫</h4>
                        </div>
                        @if($order->status == 'pending')
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="mb-2" onsubmit="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này?');">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100">
                                <i class="fas fa-times-circle"></i> Hủy đơn hàng
                            </button>
                        </form>
                        @endif
                        @if($order->status == 'cancelled')
                        <form action="{{ route('orders.reorder', $order->id) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100">
                                <i class="fas fa-shopping-cart"></i> Mua lại
                            </button>
                        </form>
                        @endif
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary w-100">
                            <i class="fas fa-arrow-left"></i> Quay lại lịch sử
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    @page {
        size: A4;
        margin: 8mm;
    }
    
    @media print {
        /* Ẩn header, footer, navigation */
        header, footer, nav, .navbar, .header, .footer, main::before, main::after {
            display: none !important;
        }
        
        /* Ẩn giỏ hàng (cart sidebar) - bỏ hoàn toàn khi in */
        .cart-sidebar, 
        .cart-sidebar-overlay, 
        #cartSidebar, 
        #cartSidebarOverlay,
        .cart-sidebar-header,
        .cart-sidebar-body,
        .cart-sidebar-footer,
        .cart-items-list,
        .cart-item,
        [class*="cart"],
        [id*="cart"] {
            display: none !important;
            visibility: hidden !important;
        }
        
        /* Ẩn các nút và alert */
        .btn, .alert, .d-flex.justify-content-between, form, a.btn {
            display: none !important;
        }
        
        /* Ẩn phần sticky */
        .sticky-top {
            position: relative !important;
            top: 0 !important;
        }
        
        /* Ẩn icon trong tiêu đề */
        .page-title i {
            display: none !important;
        }
        
        /* Ẩn icon trong card header */
        .card-header i {
            display: none !important;
        }
        
        /* Ẩn hình ảnh sản phẩm để tiết kiệm không gian */
        .table img {
            display: none !important;
        }
        
        /* Chỉ in 1 trang - tối ưu layout */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        
        body {
            padding: 0 !important;
            margin: 0 !important;
            font-size: 11px !important;
            line-height: 1.3 !important;
        }
        
        .container {
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .order-detail-section {
            padding: 0 !important;
            margin: 0 !important;
        }
        
        .row {
            margin: 0 !important;
            display: flex !important;
            flex-wrap: wrap !important;
        }
        
        .col-lg-8 {
            width: 60% !important;
            padding: 3px !important;
        }
        
        .col-lg-4 {
            width: 40% !important;
            padding: 3px !important;
        }
        
        .page-title {
            font-size: 16px !important;
            margin-bottom: 5px !important;
            padding: 3px 0 !important;
            font-weight: bold !important;
        }
        
        .card {
            border: 1px solid #ddd !important;
            box-shadow: none !important;
            page-break-inside: avoid !important;
            margin-bottom: 5px !important;
            break-inside: avoid !important;
        }
        
        .card-header {
            background: #f8f9fa !important;
            padding: 5px 8px !important;
            font-size: 12px !important;
            border-bottom: 1px solid #ddd !important;
        }
        
        .card-header h5 {
            font-size: 12px !important;
            margin: 0 !important;
            font-weight: bold !important;
        }
        
        .card-body {
            padding: 6px 8px !important;
            font-size: 11px !important;
        }
        
        .table {
            font-size: 10px !important;
            margin-bottom: 0 !important;
            width: 100% !important;
        }
        
        .table th {
            padding: 4px 5px !important;
            font-size: 10px !important;
            font-weight: bold !important;
            background: #f8f9fa !important;
            border: 1px solid #ddd !important;
        }
        
        .table td {
            padding: 4px 5px !important;
            font-size: 10px !important;
            border: 1px solid #ddd !important;
        }
        
        .table tfoot td {
            font-weight: bold !important;
            background: #f8f9fa !important;
        }
        
        .mb-3 {
            margin-bottom: 4px !important;
        }
        
        .mb-4 {
            margin-bottom: 5px !important;
        }
        
        h4 {
            font-size: 16px !important;
            margin: 0 !important;
        }
        
        code {
            font-size: 10px !important;
            background: #f8f9fa !important;
            padding: 2px 4px !important;
        }
        
        .badge {
            font-size: 9px !important;
            padding: 2px 6px !important;
        }
        
        strong {
            font-weight: bold !important;
        }
        
        /* Tối ưu thông tin giao hàng */
        .col-md-6 {
            width: 50% !important;
            padding: 2px 5px !important;
        }
        
        .col-md-12 {
            width: 100% !important;
            padding: 2px 5px !important;
        }
        
        /* Ẩn phần không cần thiết */
        .col-lg-4 .card-body hr {
            display: none !important;
        }
        
        /* Đảm bảo không bị chia trang */
        .card, .table, .row {
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        
        /* Tối ưu table responsive */
        .table-responsive {
            overflow: visible !important;
        }
    }
</style>
@endpush
@endsection

