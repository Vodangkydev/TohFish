@extends('layouts.app')

@section('title', 'Lịch sử mua hàng - TOH fish')

@section('content')
<section class="orders-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title mb-4"><i class="fas fa-shopping-cart"></i> Lịch sử mua hàng</h1>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-lg-12">
                @forelse($orders as $order)
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Mã đơn: {{ $order->code }}</strong>
                            <br>
                            <small class="text-muted">Ngày đặt: {{ $order->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <div class="text-end">
                            <div class="mb-2">
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
                            <div>
                                <strong class="text-danger">Tổng tiền: {{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h5 class="mb-3">Sản phẩm đã đặt:</h5>
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Sản phẩm</th>
                                                <th>Giá</th>
                                                <th>Số lượng</th>
                                                <th>Thành tiền</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($order->items as $item)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($item->product_image)
                                                            <img src="{{ asset($item->product_image) }}" alt="" class="me-2" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                                        @else
                                                            <img src="{{ asset('images/home/1.png') }}" alt="" class="me-2" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                                        @endif
                                                        <span>{{ $item->product_name }}</span>
                                                    </div>
                                                </td>
                                                <td>{{ number_format($item->product_price, 0, ',', '.') }}₫</td>
                                                <td>{{ $item->quantity }}</td>
                                                <td><strong>{{ number_format($item->subtotal, 0, ',', '.') }}₫</strong></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                                <div class="mb-3">
                                    <strong>Địa chỉ giao hàng:</strong><br>
                                    <small>{{ $order->shipping_address }}, {{ $order->district }}, {{ $order->city }}</small>
                                </div>
                                @if($order->note)
                                <div class="mb-3">
                                    <strong>Ghi chú:</strong><br>
                                    <small>{{ $order->note }}</small>
                                </div>
                                @endif
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm w-100">
                                    <i class="fas fa-eye"></i> Xem chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">Chưa có đơn hàng nào</h4>
                    <p class="text-muted">Bạn chưa đặt đơn hàng nào. Hãy <a href="{{ route('home') }}">mua sắm ngay</a>!</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="row mt-4">
            <div class="col-lg-12">
                {{ $orders->links() }}
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

