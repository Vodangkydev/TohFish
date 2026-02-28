@extends('layouts.app')

@section('title', 'Đặt hàng thành công - TOH fish')

@section('content')
<section class="order-success-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0">
                    <div class="card-body text-center p-5">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                        </div>
                        <h2 class="mb-3 text-success">Đặt hàng thành công!</h2>
                        <p class="lead mb-4">Cảm ơn bạn đã đặt hàng tại TOH fish. Đơn hàng của bạn đã được ghi nhận.</p>
                        
                        <div class="alert alert-info text-start">
                            <h5 class="mb-3"><i class="fas fa-info-circle"></i> Thông tin đơn hàng:</h5>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <strong>Mã đơn hàng:</strong><br>
                                    <code class="fs-5">{{ $order->code }}</code>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Tổng tiền:</strong><br>
                                    <span class="text-danger fs-5">{{ number_format($order->total_amount, 0, ',', '.') }}₫</span>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Phương thức thanh toán:</strong><br>
                                    @if($order->payment_method == 'cash')
                                        <span class="badge bg-secondary">COD</span>
                                    @elseif($order->payment_method == 'bank')
                                        <span class="badge bg-info">Chuyển khoản</span>
                                    @elseif($order->payment_method == 'momo')
                                        <span class="badge bg-success">Ví điện tử</span>
                                    @endif
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Ngày đặt:</strong><br>
                                    {{ $order->created_at->format('d/m/Y H:i:s') }}
                                </div>
                            </div>
                        </div>

                        @if($order->payment_method == 'bank' || $order->payment_method == 'momo')
                        @php
                            // Kiểm tra xem payment transaction có thực sự hết hạn không
                            $isExpired = false;
                            $showPaymentInfo = false;
                            
                            if ($paymentTransaction) {
                                $createdAt = $paymentTransaction->created_at;
                                $expiresAt = $createdAt->copy()->addMinutes(3);
                                // Chỉ đánh dấu hết hạn nếu status là expired hoặc đã quá thời gian
                                $isExpired = ($paymentTransaction->status === 'expired') || now()->gt($expiresAt);
                                // Hiển thị thông tin thanh toán nếu chưa completed (pending, expired, failed)
                                // Ngay cả khi expired, vẫn hiển thị để user có thể thanh toán (hệ thống vẫn nhận được)
                                $showPaymentInfo = ($paymentTransaction->status !== 'completed');
                            } else {
                                // Nếu chưa có payment transaction, vẫn hiển thị để người dùng thanh toán
                                $showPaymentInfo = true;
                            }
                            
                            // Thông tin tài khoản ngân hàng
                            $bankAccount = '050122494737';
                            $bankName = 'VO DANG KY';
                            $amount = $order->total_amount;
                            $content = $order->code;
                        @endphp
                        <!-- Hiển thị QR code cho thanh toán online -->
                        <div class="mt-4 p-4 bg-light rounded border" id="paymentSection">
                            <!-- Trạng thái thanh toán -->
                            <div id="paymentStatus" class="mb-3">
                                @if($paymentTransaction && $paymentTransaction->status === 'completed')
                                    <div class="alert alert-success">
                                        <h5><i class="fas fa-check-circle"></i> Thanh toán thành công!</h5>
                                        <p class="mb-0">Đơn hàng của bạn đã được xác nhận thanh toán và sẽ được xử lý sớm nhất.</p>
                                        @if($paymentTransaction->paid_at)
                                            <small>Thời gian thanh toán: {{ $paymentTransaction->paid_at->format('d/m/Y H:i:s') }}</small>
                                        @endif
                                    </div>
                                @elseif($isExpired || ($paymentTransaction && $paymentTransaction->status === 'expired'))
                                    <div class="alert alert-warning">
                                        <h5><i class="fas fa-exclamation-triangle"></i> Thời gian thanh toán đã hết hạn</h5>
                                        <p class="mb-0">Thời gian thanh toán đã hết. Bạn vẫn có thể thanh toán theo thông tin bên dưới. Thời gian còn lại: <strong id="countdown">0:00</strong> phút. Sau khi thanh toán, vui lòng liên hệ với chúng tôi để xác nhận.</p>
                                    </div>
                                @elseif($paymentTransaction && $paymentTransaction->status === 'failed')
                                    <div class="alert alert-warning">
                                        <h5><i class="fas fa-exclamation-triangle"></i> Thanh toán thất bại</h5>
                                        <p class="mb-0">Thanh toán không thành công. Vui lòng thử lại hoặc liên hệ với chúng tôi.</p>
                                    </div>
                                @else
                                    <div class="alert alert-info" id="pendingPaymentAlert">
                                        <h5><i class="fas fa-clock"></i> Đang chờ thanh toán</h5>
                                        <p class="mb-0">Vui lòng quét mã QR và thanh toán trong vòng <strong id="countdown">3:00</strong> phút.</p>
                                    </div>
                                @endif
                            </div>

                            @if($showPaymentInfo)
                            @if($order->payment_method == 'momo')
                            <!-- Thanh toán MoMo -->
                            <div class="alert alert-success mb-3">
                                <h5 class="mb-3"><i class="fas fa-mobile-alt"></i> Thanh toán qua MoMo</h5>
                                <p class="mb-3">Nhấn nút bên dưới để thanh toán qua ứng dụng MoMo:</p>
                                <button type="button" class="btn btn-danger btn-lg w-100" id="momoPaymentBtn" data-order-id="{{ $order->id }}">
                                    <i class="fas fa-mobile-alt"></i> Thanh toán QR MOMO
                                </button>
                            </div>

                            <!-- Modal hiển thị QR Code -->
                            <div class="modal fade" id="momoQRModal" tabindex="-1" aria-labelledby="momoQRModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="momoQRModalLabel">
                                                <i class="fas fa-qrcode"></i> Quét mã QR để thanh toán
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body text-center">
                                            <div id="momoQRLoading" class="mb-3">
                                                <div class="spinner-border text-danger" role="status">
                                                    <span class="visually-hidden">Đang tải...</span>
                                                </div>
                                                <p class="mt-2">Đang tạo mã QR...</p>
                                            </div>
                                            <div id="momoQRContent" style="display: none;">
                                                <div class="mb-3">
                                                    <iframe id="momoQRFrame" src="" style="width: 100%; height: 500px; border: none; border-radius: 8px;"></iframe>
                                                </div>
                                                <div class="alert alert-info mb-0">
                                                    <small>
                                                        <i class="fas fa-info-circle"></i> <strong>Hướng dẫn:</strong><br>
                                                        1. Quét mã QR bằng ứng dụng MoMo<br>
                                                        2. Kiểm tra số tiền và xác nhận thanh toán<br>
                                                        3. Sau khi thanh toán, trang sẽ tự động cập nhật
                                                    </small>
                                                </div>
                                            </div>
                                            <div id="momoQRError" style="display: none;">
                                                <div class="alert alert-danger">
                                                    <i class="fas fa-exclamation-triangle"></i>
                                                    <p class="mb-0" id="momoQRErrorMessage"></p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @else
                            <!-- Thông tin chuyển khoản thủ công -->
                            <div class="alert alert-primary mb-3">
                                <h5 class="mb-3"><i class="fas fa-university"></i> Thông tin chuyển khoản thủ công:</h5>
                                <div class="text-start">
                                    <p class="mb-2"><strong>Số tài khoản:</strong> <code class="fs-5">{{ $bankAccount }}</code></p>
                                    <p class="mb-2"><strong>Chủ tài khoản:</strong> <code class="fs-5">{{ $bankName }}</code></p>
                                    <p class="mb-2"><strong>Số tiền cần chuyển:</strong> <span class="text-danger fs-5">{{ number_format($amount, 0, ',', '.') }}₫</span></p>
                                    <p class="mb-0"><strong>Nội dung chuyển khoản:</strong> <code class="fs-5">{{ $content }}</code></p>
                                </div>
                            </div>
                            
                            <h5 class="mb-3"><i class="fas fa-qrcode"></i> Hoặc quét mã QR để thanh toán</h5>
                            <div class="text-center mb-3">
                                <div id="qrcode" style="display: inline-block; padding: 10px; background: white; border: 2px solid #ddd; border-radius: 8px;"></div>
                            </div>
                            @endif
                            <div class="alert alert-warning mb-0">
                                <small>
                                    <i class="fas fa-exclamation-triangle"></i> <strong>Lưu ý quan trọng:</strong><br>
                                    • Vui lòng thanh toán đúng số tiền: <strong class="text-danger">{{ number_format($amount, 0, ',', '.') }}₫</strong><br>
                                    • Nội dung chuyển khoản bắt buộc: <strong>{{ $content }}</strong><br>
                                    @if($paymentTransaction && ($paymentTransaction->status === 'expired' || $isExpired))
                                    • Thời gian thanh toán đã hết. Sau khi chuyển khoản, vui lòng liên hệ với chúng tôi để xác nhận thanh toán.<br>
                                    @else
                                    • Sau khi thanh toán, <strong>trạng thái sẽ được cập nhật khi admin xác nhận</strong>. Vui lòng kiểm tra lại sau vài phút hoặc làm mới trang này.<br>
                                    @endif
                                    • Vui lòng giữ lại biên lai thanh toán để đối chiếu nếu cần<br>
                                    • <strong>Cách kiểm tra trạng thái:</strong> Trang này sẽ tự động cập nhật khi admin xác nhận thanh toán, hoặc bạn có thể vào "Xem chi tiết đơn hàng" để kiểm tra
                                </small>
                            </div>
                            @endif
                        </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-lg me-2">
                                <i class="fas fa-eye"></i> Xem chi tiết đơn hàng
                            </a>
                            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary btn-lg me-2">
                                <i class="fas fa-list"></i> Lịch sử mua hàng
                            </a>
                            <a href="{{ route('home') }}" class="btn btn-outline-primary btn-lg">
                                <i class="fas fa-home"></i> Về trang chủ
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


@if($order->payment_method == 'bank' || $order->payment_method == 'momo')
@php
    $paymentStatus = $paymentTransaction ? $paymentTransaction->status : 'pending';
    if ($paymentTransaction) {
        $createdAt = $paymentTransaction->created_at;
    } else {
        $createdAt = $order->created_at;
    }
    $expiresAt = $createdAt->copy()->addMinutes(3);
    $now = now();
    if ($now->gt($expiresAt)) {
        $secondsRemaining = 0;
    } else {
        $secondsRemaining = $now->diffInSeconds($expiresAt, false);
    }
    
    // Thông tin tài khoản ngân hàng
    $bankAccount = '050122494737';
    $bankName = 'VO DANG KY';
    $amount = $order->total_amount;
    $content = $order->code;
    
    // Format VietQR - Có thể dùng format text đơn giản hoặc JSON
    // Format text: Số tài khoản|Tên|Số tiền|Nội dung (đơn giản)
    // Hoặc format JSON để app có thể parse
    $vietqrData = $bankAccount . '|' . $bankName . '|' . $amount . '|' . $content;
@endphp
@if($order->payment_method == 'bank')
<!-- QRCode.js Library - Sử dụng CDN từ unpkg.com -->
<script src="https://unpkg.com/qrcodejs@1.0.0/qrcode.min.js"></script>
@endif
<script>
@if($order->payment_method == 'bank')
// Tạo QR Code động với thông tin thanh toán
function initQRCode() {
    // Kiểm tra xem QRCode đã được load chưa
    if (typeof QRCode === 'undefined') {
        console.log('QRCode library chưa load, đợi thêm...');
        setTimeout(initQRCode, 300); // Thử lại sau 300ms
        return;
    }
    
    // Tìm element #qrcode
    const qrcodeContainer = document.getElementById('qrcode');
    if (!qrcodeContainer) {
        console.warn('Không tìm thấy element #qrcode. Đang thử lại...');
        setTimeout(initQRCode, 300);
        return;
    }
    
    // Kiểm tra xem đã có QR code chưa (tránh tạo nhiều lần)
    if (qrcodeContainer.querySelector('canvas') || qrcodeContainer.querySelector('img')) {
        console.log('QR Code đã được tạo trước đó');
        return;
    }
    
    // Xóa nội dung cũ nếu có
    qrcodeContainer.innerHTML = '';
    
    // Lấy dữ liệu VietQR
    const vietqrData = @json($vietqrData);
    
    console.log('Đang tạo QR Code với dữ liệu:', vietqrData);
    console.log('Container element:', qrcodeContainer);
    
    try {
        // Tạo QR code bằng QRCode.js
        const qrcode = new QRCode(qrcodeContainer, {
            text: vietqrData,
            width: 300,
            height: 300,
            colorDark: '#000000',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
        });
        
        console.log('QR Code đã tạo thành công!');
    } catch (error) {
        console.error('Lỗi khi tạo QR Code:', error);
        qrcodeContainer.innerHTML = '<div class="alert alert-danger">Không thể tạo mã QR. Vui lòng làm mới trang hoặc liên hệ hỗ trợ.</div>';
    }
}
@endif

// Countdown timer
function initCountdown() {
    const orderId = {{ $order->id }};
    const paymentStatus = '{{ $paymentStatus }}';
    let countdownInterval;
    let checkStatusInterval;
    
    @php
        // Tính toán lại secondsRemaining để đảm bảo chính xác
        // QUAN TRỌNG: Luôn sử dụng created_at của payment transaction (nếu có)
        // để tính thời gian còn lại, KHÔNG BAO GIỜ tính lại từ now()
        $paymentStatusForJS = $paymentTransaction ? $paymentTransaction->status : 'pending';
        
        if ($paymentTransaction) {
            // Sử dụng created_at của payment transaction hiện tại
            $createdAtForCountdown = $paymentTransaction->created_at;
        } else {
            // Nếu chưa có payment transaction, sử dụng created_at của order
            $createdAtForCountdown = $order->created_at;
        }
        
        // Thời gian hết hạn là 3 phút sau khi tạo payment transaction (hoặc order)
        $expiresAtForCountdown = $createdAtForCountdown->copy()->addMinutes(3);
        $nowForCountdown = now();
        
        // Tính số giây còn lại (KHÔNG reset về 3 phút)
        // Sử dụng diffInSeconds với tham số false để có thể trả về số âm nếu đã hết hạn
        $secondsRemainingForJS = max(0, $nowForCountdown->diffInSeconds($expiresAtForCountdown, false));
        
        // Luôn hiển thị countdown cho tất cả trường hợp (pending, expired, failed)
        // Khi expired, secondsRemaining sẽ là 0 và hiển thị 0:00
        $shouldShowCountdown = ($paymentStatusForJS !== 'completed');
    @endphp
    
    const countdownElement = document.getElementById('countdown');
    
    @if($shouldShowCountdown)
    // QUAN TRỌNG: secondsRemaining này được tính từ PHP dựa trên created_at của payment transaction
    // KHÔNG BAO GIỜ reset về 3 phút khi reload trang
    // Chỉ reset về 3 phút khi tạo payment transaction MỚI
    let secondsRemaining = {{ $secondsRemainingForJS }};
    
    // Lưu thời gian khởi tạo để debug
    const countdownStartTime = new Date().getTime();
    const initialSecondsRemaining = secondsRemaining;
    
    console.log('Khởi tạo countdown:', {
        secondsRemaining: secondsRemaining,
        initialSecondsRemaining: initialSecondsRemaining,
        minutes: Math.floor(secondsRemaining / 60),
        countdownElement: !!countdownElement,
        paymentStatus: paymentStatus,
        paymentTransactionId: @json($paymentTransaction ? $paymentTransaction->id : null),
        createdAt: @json($paymentTransaction ? $paymentTransaction->created_at->format('Y-m-d H:i:s') : $order->created_at->format('Y-m-d H:i:s'))
    });
    
    if (countdownElement) {
        function updateCountdown() {
            if (secondsRemaining <= 0) {
                if (countdownInterval) clearInterval(countdownInterval);
                if (countdownElement) {
                    countdownElement.textContent = '0:00';
                }
                // Chỉ reload khi đang pending và hết thời gian (để kiểm tra trạng thái expired)
                // Nếu đã expired rồi thì không reload nữa
                if (paymentStatus === 'pending') {
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                }
                return;
            }
            
            const minutes = Math.floor(secondsRemaining / 60);
            const secs = secondsRemaining % 60;
            countdownElement.textContent = `${minutes}:${secs.toString().padStart(2, '0')}`;
            secondsRemaining--;
        }
        
        // Cập nhật ngay lập tức (không đợi 1 giây đầu tiên)
        updateCountdown();
        // Sau đó cập nhật mỗi giây
        countdownInterval = setInterval(updateCountdown, 1000);
    } else {
        console.warn('Countdown không khởi tạo được - không tìm thấy element:', {
            countdownElement: !!countdownElement,
            secondsRemaining: {{ $secondsRemainingForJS }}
        });
    }
    @else
    console.log('Countdown không cần khởi tạo:', {
        paymentStatus: paymentStatus,
        paymentStatusForJS: '{{ $paymentStatusForJS }}',
        secondsRemainingForJS: {{ $secondsRemainingForJS }},
        shouldShowCountdown: {{ $shouldShowCountdown ? 'true' : 'false' }}
    });
    @endif
    
    // Kiểm tra trạng thái thanh toán mỗi 5 giây
    function checkPaymentStatus() {
        fetch(`/payment/check/${orderId}`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                // Kiểm tra xem response có phải JSON không
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    // Nếu không phải JSON, có thể là HTML error page
                    return response.text().then(text => {
                        console.warn('Payment check returned non-JSON response:', text.substring(0, 200));
                        throw new Error('Invalid response format');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.status === 'completed') {
                    // Thanh toán thành công, reload trang
                    if (checkStatusInterval) clearInterval(checkStatusInterval);
                    if (countdownInterval) clearInterval(countdownInterval);
                    location.reload();
                } else if (data.status === 'expired' && paymentStatus === 'pending') {
                    // Chỉ reload khi chuyển từ pending sang expired lần đầu tiên
                    // Nếu đã expired rồi thì không reload (để tiếp tục check khi admin xác nhận)
                    if (checkStatusInterval) clearInterval(checkStatusInterval);
                    if (countdownInterval) clearInterval(countdownInterval);
                    location.reload();
                } else if (data.status === 'failed') {
                    // Thanh toán thất bại, reload trang
                    if (checkStatusInterval) clearInterval(checkStatusInterval);
                    if (countdownInterval) clearInterval(countdownInterval);
                    location.reload();
                }
                // Nếu status vẫn là 'expired' (đã expired trước đó), tiếp tục check để phát hiện khi admin xác nhận
            })
            .catch(error => {
                // Chỉ log error, không làm gì thêm để tránh spam console
                // Có thể là lỗi tạm thời, sẽ thử lại ở lần check tiếp theo
                if (error.message !== 'Invalid response format') {
                    console.error('Error checking payment status:', error);
                }
            });
    }
    
    // Kiểm tra nếu đang pending hoặc expired (để phát hiện khi admin xác nhận sau khi expired)
    if (paymentStatus === 'pending' || paymentStatus === 'expired') {
        checkStatusInterval = setInterval(checkPaymentStatus, 5000); // Kiểm tra mỗi 5 giây
    }
}

// Xử lý thanh toán MoMo - hiển thị QR code trong modal
@if($order->payment_method == 'momo')
document.addEventListener('DOMContentLoaded', function() {
    const momoPaymentBtn = document.getElementById('momoPaymentBtn');
    const momoQRModal = new bootstrap.Modal(document.getElementById('momoQRModal'));
    const momoQRLoading = document.getElementById('momoQRLoading');
    const momoQRContent = document.getElementById('momoQRContent');
    const momoQRError = document.getElementById('momoQRError');
    const momoQRFrame = document.getElementById('momoQRFrame');
    const momoQRErrorMessage = document.getElementById('momoQRErrorMessage');

    if (momoPaymentBtn) {
        momoPaymentBtn.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            
            // Disable button
            this.disabled = true;
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang chuyển đến thanh toán...';
            
            // Gọi API để lấy payUrl và redirect thẳng
            const formData = new FormData();
            formData.append('order_id', orderId);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '');
            
            fetch('{{ route("payment.momo.initiate") }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                // Kiểm tra content-type trước khi parse JSON
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    // Nếu không phải JSON, có thể là HTML error page
                    return response.text().then(text => {
                        console.error('Server returned non-JSON response:', text.substring(0, 200));
                        throw new Error('Server trả về phản hồi không hợp lệ. Vui lòng thử lại.');
                    });
                }
                
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Có lỗi xảy ra');
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success && data.payUrl) {
                    // Redirect thẳng đến thanh toán MoMo
                    window.location.href = data.payUrl;
                } else {
                    throw new Error(data.message || 'Không thể tạo mã QR');
                }
            })
            .catch(error => {
                console.error('MoMo Payment Error:', error);
                
                // Hiển thị lỗi bằng alert
                alert(error.message || 'Có lỗi xảy ra khi tạo mã QR. Vui lòng thử lại.');
                
                // Re-enable button
                momoPaymentBtn.disabled = false;
                momoPaymentBtn.innerHTML = originalText;
            });
        });
    }
});
@endif

// Khởi tạo khi DOM đã sẵn sàng
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing...');
        @if($order->payment_method == 'bank')
        initQRCode();
        @endif
        initCountdown();
    });
} else {
    // DOM đã sẵn sàng
    console.log('DOM already ready, initializing...');
    @if($order->payment_method == 'bank')
    initQRCode();
    @endif
    initCountdown();
}

@if($order->payment_method == 'bank')
// Thử lại sau 1 giây nếu vẫn chưa thấy QR code
setTimeout(function() {
    const qrcodeContainer = document.getElementById('qrcode');
    if (qrcodeContainer && !qrcodeContainer.querySelector('canvas') && !qrcodeContainer.querySelector('img')) {
        console.log('QR code chưa được tạo, thử lại...');
        initQRCode();
    }
}, 1000);
@endif
</script>
@endif
@endsection

