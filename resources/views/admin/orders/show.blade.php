@extends('admin.layout')

@section('title', 'Chi tiết Đơn Hàng')

@section('content')
<div class="admin-header">
    <h2><i class="fas fa-shopping-cart"></i> Chi tiết Đơn Hàng: {{ $order->code }}</h2>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Order Items -->
        <div class="admin-card mb-4">
            <h4 class="mb-3"><i class="fas fa-box"></i> Sản phẩm trong đơn hàng</h4>
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
                                    <img src="{{ asset($item->product_image) }}" alt="" class="img-preview" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <img src="{{ asset('images/home/1.png') }}" alt="" class="img-preview" style="width: 60px; height: 60px; object-fit: cover;">
                                @endif
                            </td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ number_format($item->product_price, 0, ',', '.') }}₫</td>
                            <td>{{ $item->quantity }}</td>
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

        <!-- Customer Information -->
        <div class="admin-card">
            <h4 class="mb-3"><i class="fas fa-user"></i> Thông tin khách hàng</h4>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Họ và tên:</strong><br>
                    {{ $order->shipping_name ?? $order->user->name ?? 'N/A' }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Email:</strong><br>
                    {{ $order->email ?? $order->user->email ?? 'N/A' }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Số điện thoại:</strong><br>
                    {{ $order->shipping_phone }}
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

    <div class="col-md-4">
        <!-- Order Information -->
        <div class="admin-card mb-4">
            <h4 class="mb-3"><i class="fas fa-info-circle"></i> Thông tin đơn hàng</h4>
            <div class="mb-3">
                <strong>Mã đơn hàng:</strong><br>
                <code>{{ $order->code }}</code>
            </div>
            <div class="mb-3">
                <strong>Ngày đặt hàng:</strong><br>
                {{ $order->created_at->format('d/m/Y H:i:s') }}
            </div>
            <div class="mb-3">
                <strong>Cập nhật lần cuối:</strong><br>
                {{ $order->updated_at->format('d/m/Y H:i:s') }}
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
                <strong>Tổng tiền:</strong><br>
                <h4 class="text-danger mb-0">{{ number_format($order->total_amount, 0, ',', '.') }}₫</h4>
            </div>
            @if($paymentTransaction)
            <div class="mb-3">
                <strong>Trạng thái thanh toán:</strong><br>
                @if($paymentTransaction->status == 'pending')
                    <span class="badge bg-warning">Đang chờ thanh toán</span>
                @elseif($paymentTransaction->status == 'completed')
                    <span class="badge bg-success">Đã thanh toán</span>
                    @if($paymentTransaction->paid_at)
                        <br><small class="text-muted">Thời gian: {{ $paymentTransaction->paid_at->format('d/m/Y H:i:s') }}</small>
                    @endif
                @elseif($paymentTransaction->status == 'expired')
                    <span class="badge bg-danger">Hết hạn</span>
                @elseif($paymentTransaction->status == 'failed')
                    <span class="badge bg-danger">Thất bại</span>
                @endif
            </div>
            @endif
        </div>

        <!-- Confirm Payment (hiển thị nếu có payment transaction và chưa completed) -->
        @if($paymentTransaction && $paymentTransaction->status != 'completed')
        <div class="admin-card mb-4">
            <h4 class="mb-3"><i class="fas fa-check-circle"></i> Xác nhận thanh toán</h4>
            @if($paymentTransaction->status == 'expired')
                <div class="alert alert-warning mb-3">
                    <i class="fas fa-exclamation-triangle"></i> Thời gian thanh toán đã hết hạn, nhưng vẫn có thể xác nhận nếu khách hàng đã thanh toán.
                </div>
            @else
                <p class="text-muted mb-3">Nếu khách hàng đã thanh toán, vui lòng xác nhận để cập nhật trạng thái.</p>
            @endif
            <form action="{{ route('admin.orders.confirm-payment', $order->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn đã nhận được thanh toán từ khách hàng?');">
                @csrf
                <button type="submit" class="btn btn-success w-100">
                    <i class="fas fa-check"></i> Xác nhận đã thanh toán
                </button>
            </form>
        </div>
        @endif

        <!-- Update Status -->
        <div class="admin-card">
            <h4 class="mb-3"><i class="fas fa-edit"></i> Cập nhật trạng thái</h4>
            <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <select name="status" class="form-select" required>
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
            </form>
        </div>
    </div>
</div>
@endsection

