@extends('layouts.app')

@section('title', 'Thanh toán - TOH fish')

@section('content')
<section class="checkout-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title text-center mb-5">Thanh toán đơn hàng</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="checkout-form bg-white p-4 rounded shadow-sm mb-4">
                    <h4 class="mb-4"><i class="fas fa-user"></i> Thông tin khách hàng</h4>
                    @php
                        $basePath = request()->getBasePath();
                    @endphp
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('orders.store') }}" method="POST" id="checkoutForm">
                        @csrf
                        @if(!empty($selectedIds))
                            <input type="hidden" name="selected_ids" value="{{ implode(',', $selectedIds) }}">
                        @endif
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fullname" class="form-label">Họ và tên *</label>
                                <input type="text" class="form-control @error('fullname') is-invalid @enderror" id="fullname" name="fullname" required value="{{ old('fullname', auth()->user()->name ?? '') }}">
                                @error('fullname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại *</label>
                                <input type="tel" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" required value="{{ old('phone', auth()->user()->phone ?? '') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="address" class="form-label">Địa chỉ giao hàng *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3" required>{{ old('address', auth()->user()->address ?? '') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="city" class="form-label">Tỉnh/Thành phố *</label>
                                <select class="form-select @error('city') is-invalid @enderror" id="city" name="city" required>
                                    <option value="">Chọn tỉnh/thành phố</option>
                                    @php
                                        $cities = ['TP. Hồ Chí Minh', 'Hà Nội', 'Đồng Nai', 'Bình Dương', 'Khác'];
                                        $userCity = old('city', auth()->user()->city ?? '');
                                    @endphp
                                    @foreach ($cities as $city)
                                        <option value="{{ $city }}" @selected($userCity === $city)>{{ $city }}</option>
                                    @endforeach
                                </select>
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="district" class="form-label">Quận/Huyện *</label>
                                <input type="text" class="form-control @error('district') is-invalid @enderror" id="district" name="district" required value="{{ old('district', auth()->user()->district ?? '') }}">
                                @error('district')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="note" class="form-label">Ghi chú</label>
                            <textarea class="form-control @error('note') is-invalid @enderror" id="note" name="note" rows="2" placeholder="Ghi chú thêm về đơn hàng (tùy chọn)">{{ old('note') }}</textarea>
                            @error('note')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Phương thức thanh toán -->
                        <div class="payment-method mt-4 pt-4 border-top">
                            <h4 class="mb-4"><i class="fas fa-credit-card"></i> Phương thức thanh toán</h4>
                            <div class="form-check mb-3">
                                <input class="form-check-input @error('payment') is-invalid @enderror" type="radio" name="payment" id="cash" value="cash" {{ old('payment', 'cash') === 'cash' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="cash">
                                    <strong>Thanh toán khi nhận hàng (COD)</strong>
                                </label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input @error('payment') is-invalid @enderror" type="radio" name="payment" id="bank" value="bank" {{ old('payment') === 'bank' ? 'checked' : '' }}>
                                <label class="form-check-label" for="bank">
                                    <strong>Chuyển khoản ngân hàng</strong>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('payment') is-invalid @enderror" type="radio" name="payment" id="momo" value="momo" {{ old('payment') === 'momo' ? 'checked' : '' }}>
                                <label class="form-check-label" for="momo">
                                    <strong>MoMo</strong>
                                </label>
                            </div>
                            @error('payment')
                                <div class="text-danger small mt-2">{{ $message }}</div>
                            @enderror
                            
                            <!-- Hiển thị QR code khi chọn chuyển khoản ngân hàng -->
                            <div id="qrPaymentSection" class="mt-4 p-3 bg-light rounded border" style="display: none;">
                                <h5 class="mb-3"><i class="fas fa-qrcode"></i> Quét mã QR để thanh toán</h5>
                                <div class="text-center mb-3">
                                    <img src="{{ asset('images/home/QR_Thanhtoan.jpg') }}" alt="QR Code Thanh toán" class="img-fluid" style="max-width: 300px; border: 2px solid #ddd; border-radius: 8px;">
                                </div>
                                <div class="alert alert-info mb-0">
                                    <small>
                                        <i class="fas fa-info-circle"></i> <strong>Hướng dẫn:</strong><br>
                                        1. Quét mã QR bằng ứng dụng ngân hàng hoặc ví điện tử<br>
                                        2. Kiểm tra số tiền và nội dung chuyển khoản<br>
                                        3. Hoàn tất thanh toán và quay lại trang này để đặt hàng<br>
                                        4. Đơn hàng sẽ được xử lý sau khi chúng tôi xác nhận thanh toán
                                    </small>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-4">
                <div class="order-summary bg-white p-4 rounded shadow-sm sticky-top" style="top: 100px;">
                    <h4 class="mb-4">Tóm tắt đơn hàng</h4>
                    <div class="order-items mb-3">
                        @forelse ($items as $item)
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                                <span>{{ number_format($item['price'] * $item['quantity']) }}₫</span>
                            </div>
                        @empty
                            <p class="text-muted">Chưa có sản phẩm trong giỏ hàng.</p>
                        @endforelse
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tạm tính</span>
                            <span>{{ number_format($totals['subtotal'] ?? 0) }}₫</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Phí vận chuyển</span>
                            <span>{{ number_format($totals['shipping'] ?? 0) }}₫</span>
                        </div>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-4">
                        <strong>Tổng cộng:</strong>
                        <strong class="text-danger fs-5">{{ number_format($totals['total'] ?? 0) }}₫</strong>
                    </div>
                    <button type="submit" form="checkoutForm" class="btn btn-primary btn-lg w-100" id="placeOrderBtn">
                      Đặt hàng 
                    </button>
                    <a href="{{ route('cart') }}" class="btn btn-outline-secondary w-100 mt-2">
                        <i class="fas fa-arrow-left"></i> Quay lại giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal hiển thị QR Code MoMo -->
<div class="modal fade" id="momoQRModal" tabindex="-1" aria-labelledby="momoQRModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="momoQRModalLabel">
                    <i class="fas fa-mobile-alt"></i> Quét mã QR MoMo để thanh toán
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div id="momoQRLoading" class="py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Đang tải...</span>
                    </div>
                    <p class="mt-3">Đang tạo mã QR...</p>
                </div>
                <div id="momoQRContent" style="display: none;">
                    <div id="momoQRCode" class="mb-3"></div>
                    <div class="alert alert-info">
                        <small>
                            <i class="fas fa-info-circle"></i> <strong>Hướng dẫn:</strong><br>
                            1. Mở ứng dụng MoMo trên điện thoại<br>
                            2. Quét mã QR bên trên<br>
                            3. Xác nhận thanh toán trong ứng dụng MoMo<br>
                            4. Đơn hàng sẽ được xử lý sau khi thanh toán thành công
                        </small>
                    </div>
                </div>
                <div id="momoQRError" style="display: none;">
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span id="momoQRErrorMessage">Có lỗi xảy ra khi tạo mã QR. Vui lòng thử lại.</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<style>
.checkout-form label,
.payment-method label {
    font-weight: 600;
    color: var(--dark-color);
}

.form-check-label {
    cursor: pointer;
    margin-left: 10px;
}

.sticky-top {
    position: sticky;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkoutForm');
    const placeOrderBtn = document.getElementById('placeOrderBtn');
    const qrPaymentSection = document.getElementById('qrPaymentSection');
    const paymentInputs = form.querySelectorAll('input[name="payment"]');
    let lastSelectedPayment = null;
    
    // Hiển thị/ẩn QR code dựa trên phương thức thanh toán
    function toggleQRPayment() {
        const selectedPayment = form.querySelector('input[name="payment"]:checked');
        if (!selectedPayment) return;
        
        if (selectedPayment.value === 'bank') {
            qrPaymentSection.style.display = 'block';
        } else {
            qrPaymentSection.style.display = 'none';
        }
        
        // Nếu chọn MoMo và đây là lần đầu chọn (không phải đang chọn lại), hiển thị modal
        if (selectedPayment.value === 'momo' && lastSelectedPayment !== 'momo') {
            showMomoQRModal();
        }
        
        // Lưu lại phương thức thanh toán đã chọn
        lastSelectedPayment = selectedPayment.value;
    }
    
    // Hàm hiển thị modal QR MoMo
    function showMomoQRModal() {
        const modalEl = document.getElementById('momoQRModal');
        if (!modalEl) return;
        
        const modal = new bootstrap.Modal(modalEl);
        const loadingEl = document.getElementById('momoQRLoading');
        const contentEl = document.getElementById('momoQRContent');
        const errorEl = document.getElementById('momoQRError');
        const qrCodeEl = document.getElementById('momoQRCode');
        
        // Reset trạng thái
        loadingEl.style.display = 'block';
        contentEl.style.display = 'none';
        errorEl.style.display = 'none';
        qrCodeEl.innerHTML = '';
        
        // Hiển thị modal
        modal.show();
        
        // Lấy tổng tiền từ form
        const totalAmount = {{ $totals['total'] ?? 0 }};
        
        // Tạo QR code tạm thời (sẽ được tạo sau khi đặt hàng)
        // Hiện tại chỉ hiển thị thông báo
        setTimeout(() => {
            loadingEl.style.display = 'none';
            qrCodeEl.innerHTML = `
                <div class="alert alert-info">
                    <p class="mb-2"><strong><i class="fas fa-info-circle"></i> Thông tin thanh toán MoMo</strong></p>
                    <p class="mb-2">Tổng tiền: <strong>{{ number_format($totals['total'] ?? 0) }}₫</strong></p>
                    <p class="mb-0 small">Sau khi đặt hàng, mã QR MoMo sẽ được hiển thị để bạn quét và thanh toán.</p>
                </div>
            `;
            contentEl.style.display = 'block';
        }, 500);
    }
    
    // Lắng nghe sự kiện thay đổi phương thức thanh toán
    paymentInputs.forEach(input => {
        input.addEventListener('change', toggleQRPayment);
    });
    
    // Kiểm tra trạng thái ban đầu
    toggleQRPayment();
    
    if (form && placeOrderBtn) {
        // Validation ban đầu: disable nút nếu giỏ hàng trống
        const cartItems = @json($items);
        let hasValidItems = false;
        if (cartItems && typeof cartItems === 'object') {
            for (const key in cartItems) {
                if (cartItems.hasOwnProperty(key)) {
                    const quantity = parseInt(cartItems[key].quantity) || 0;
                    if (quantity > 0) {
                        hasValidItems = true;
                        break;
                    }
                }
            }
        }
        
        if (!hasValidItems) {
            placeOrderBtn.disabled = true;
            placeOrderBtn.classList.add('disabled');
        }
        
        form.addEventListener('submit', function(e) {
            const cartItems = @json($items);
            // Validation: Đảm bảo có ít nhất 1 sản phẩm với số lượng >= 1
            if (!cartItems || typeof cartItems !== 'object') {
                e.preventDefault();
                alert('Giỏ hàng trống. Vui lòng thêm ít nhất 1 sản phẩm vào giỏ hàng trước khi thanh toán.');
                return false;
            }
            
            // Kiểm tra số lượng - cho phép mua từ 1 sản phẩm trở lên
            let validItems = 0;
            let totalQuantity = 0;
            for (const key in cartItems) {
                if (cartItems.hasOwnProperty(key)) {
                    const quantity = parseInt(cartItems[key].quantity) || 0;
                    if (quantity > 0) {
                        validItems++;
                        totalQuantity += quantity;
                    }
                }
            }
            
            // Cho phép mua từ 1 sản phẩm trở lên
            if (validItems < 1 || totalQuantity < 1) {
                e.preventDefault();
                alert('Giỏ hàng trống. Vui lòng thêm ít nhất 1 sản phẩm vào giỏ hàng trước khi thanh toán.');
                return false;
            }
            
            // Kiểm tra phương thức thanh toán đã được chọn chưa
            const paymentMethod = form.querySelector('input[name="payment"]:checked');
            if (!paymentMethod) {
                e.preventDefault();
                alert('Vui lòng chọn phương thức thanh toán.');
                return false;
            }
            
            // Disable button to prevent double submit
            placeOrderBtn.disabled = true;
            placeOrderBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        });
    }
});
</script>
@endsection

