<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\MoMoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Kiểm tra trạng thái thanh toán của đơn hàng
     */
    public function check($orderId)
    {
        try {
            $order = Order::where('id', $orderId)
                ->where('user_id', Auth::id())
                ->first();
            
            // Nếu không tìm thấy order, trả về JSON error thay vì throw exception
            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Không tìm thấy đơn hàng',
                ], 404);
            }

            $paymentTransaction = PaymentTransaction::where('order_id', $order->id)
                ->latest()
                ->first();

            if (!$paymentTransaction) {
                return response()->json([
                    'status' => 'pending',
                    'message' => 'Chưa có giao dịch thanh toán',
                ]);
            }

            // Nếu đã quá 3 phút kể từ khi tạo và vẫn pending, đánh dấu là expired
            // CHỈ đánh dấu expired nếu thực sự quá 3 phút (không đánh dấu sớm)
            if ($paymentTransaction->status === 'pending') {
                $createdAt = $paymentTransaction->created_at;
                $expiresAt = $createdAt->copy()->addMinutes(3);
                $now = now();
                
                // Chỉ đánh dấu expired nếu đã quá 3 phút (thêm 1 giây buffer để tránh lỗi timing)
                if ($now->gt($expiresAt) || $now->diffInSeconds($expiresAt, false) <= 0) {
                    // Tự động đánh dấu là expired nếu quá 3 phút
                    $paymentTransaction->refresh(); // Lấy trạng thái mới nhất từ DB
                    
                    // Chỉ update nếu vẫn còn pending (tránh race condition)
                    if ($paymentTransaction->status === 'pending') {
                        $paymentTransaction->update([
                            'status' => 'expired'
                        ]);
                    }
                    
                    return response()->json([
                        'status' => $paymentTransaction->fresh()->status,
                        'message' => $this->getStatusMessage($paymentTransaction->fresh()->status),
                    ]);
                }
            }

            return response()->json([
                'status' => $paymentTransaction->status,
                'message' => $this->getStatusMessage($paymentTransaction->status),
            ]);
        } catch (\Exception $e) {
            Log::error('Error checking payment status', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra khi kiểm tra trạng thái thanh toán',
            ], 500);
        }
    }

    /**
     * Webhook để nhận callback từ VietQR/ngân hàng khi có thanh toán
     * Endpoint này có thể được gọi bởi VietQR API hoặc admin để tự động xác nhận thanh toán
     */
    public function webhook(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'order_code' => 'required|string',
                'amount' => 'required|numeric',
                // 'transaction_id' => 'nullable|string', // ID giao dịch từ ngân hàng
                // 'signature' => 'nullable|string', // Chữ ký để xác thực (nếu có)
            ]);

            $orderCode = $request->order_code;
            $amount = $request->amount;

            // Tìm đơn hàng theo mã
            $order = Order::where('code', $orderCode)->first();

            if (!$order) {
                Log::warning('Payment webhook: Order not found', [
                    'order_code' => $orderCode,
                    'amount' => $amount,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đơn hàng',
                ], 404);
            }

            // Tìm payment transaction đang pending
            $paymentTransaction = PaymentTransaction::where('order_id', $order->id)
                ->where('status', 'pending')
                ->latest()
                ->first();

            if (!$paymentTransaction) {
                Log::warning('Payment webhook: Payment transaction not found or already processed', [
                    'order_id' => $order->id,
                    'order_code' => $orderCode,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy giao dịch thanh toán đang chờ',
                ], 404);
            }

            // Kiểm tra số tiền khớp (có thể có sai số nhỏ do phí chuyển khoản)
            $amountDiff = abs($paymentTransaction->amount - $amount);
            $amountTolerance = 1000; // Cho phép sai số 1000₫ (để tính phí chuyển khoản)

            if ($amountDiff > $amountTolerance) {
                Log::warning('Payment webhook: Amount mismatch', [
                    'order_id' => $order->id,
                    'expected_amount' => $paymentTransaction->amount,
                    'received_amount' => $amount,
                    'difference' => $amountDiff,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Số tiền thanh toán không khớp',
                ], 400);
            }

            // Kiểm tra xem còn trong thời hạn 3 phút không (cho phép thêm 1 phút buffer)
            $createdAt = $paymentTransaction->created_at;
            $expiresAt = $createdAt->copy()->addMinutes(4); // 3 phút + 1 phút buffer

            if (now()->gt($expiresAt)) {
                Log::info('Payment webhook: Payment received but outside time window', [
                    'order_id' => $order->id,
                    'payment_transaction_id' => $paymentTransaction->id,
                    'created_at' => $createdAt->format('Y-m-d H:i:s'),
                    'now' => now()->format('Y-m-d H:i:s'),
                ]);

                // Vẫn chấp nhận thanh toán nhưng log lại
                // Có thể đánh dấu là completed hoặc yêu cầu admin xác nhận
            }

            // Tự động xác nhận thanh toán
            $paymentTransaction->update([
                'status' => 'completed',
                'paid_at' => now(),
            ]);

            // Cập nhật trạng thái đơn hàng nếu cần
            if ($order->status === 'pending') {
                $order->update([
                    'status' => 'processing', // Hoặc 'completed' tùy logic nghiệp vụ
                ]);
            }

            Log::info('Payment webhook: Payment auto-confirmed', [
                'order_id' => $order->id,
                'order_code' => $orderCode,
                'payment_transaction_id' => $paymentTransaction->id,
                'amount' => $amount,
                'paid_at' => $paymentTransaction->paid_at->format('Y-m-d H:i:s'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thanh toán đã được xác nhận tự động',
                'order_id' => $order->id,
                'payment_transaction_id' => $paymentTransaction->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Payment webhook error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý webhook',
            ], 500);
        }
    }

    /**
     * Khởi tạo thanh toán MoMo (trả về JSON để hiển thị QR code)
     */
    public function momoInitiate(Request $request)
    {
        // Kiểm tra nếu là AJAX request
        $isAjax = $request->expectsJson() || $request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest';
        
        try {
            // Validate với custom error response cho AJAX
            try {
                $request->validate([
                    'order_id' => 'required|exists:orders,id',
                ]);
            } catch (\Illuminate\Validation\ValidationException $e) {
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage(),
                        'errors' => $e->errors(),
                    ], 422);
                }
                throw $e;
            }

            $order = Order::where('id', $request->order_id)
                ->where('user_id', Auth::id())
                ->first();

            if (!$order) {
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Không tìm thấy đơn hàng',
                    ], 404);
                }
                return redirect()->route('orders.show', $request->order_id)
                    ->with('error', 'Không tìm thấy đơn hàng.');
            }

            // Kiểm tra xem đã có payment transaction chưa (cho phép cả pending và expired)
            $paymentTransaction = PaymentTransaction::where('order_id', $order->id)
                ->where('payment_method', 'momo')
                ->whereIn('status', ['pending', 'expired'])
                ->latest()
                ->first();

            // Nếu không tìm thấy, tạo mới payment transaction
            if (!$paymentTransaction) {
                // Kiểm tra xem order có payment_method là momo không
                if ($order->payment_method !== 'momo') {
                    if ($isAjax) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Đơn hàng này không sử dụng phương thức thanh toán MoMo.',
                        ], 400);
                    }
                    return redirect()->route('orders.show', $order->id)
                        ->with('error', 'Đơn hàng này không sử dụng phương thức thanh toán MoMo.');
                }

                // Tạo payment transaction mới
                $paymentTransaction = PaymentTransaction::create([
                    'order_id' => $order->id,
                    'payment_method' => 'momo',
                    'amount' => $order->total_amount,
                    'status' => 'pending',
                ]);

                Log::info('MoMo Payment Transaction created', [
                    'order_id' => $order->id,
                    'payment_transaction_id' => $paymentTransaction->id,
                ]);
            } elseif ($paymentTransaction->status === 'expired') {
                // Nếu đã expired, tạo transaction mới để thanh toán lại
                $paymentTransaction = PaymentTransaction::create([
                    'order_id' => $order->id,
                    'payment_method' => 'momo',
                    'amount' => $order->total_amount,
                    'status' => 'pending',
                ]);

                Log::info('MoMo Payment Transaction recreated after expired', [
                    'order_id' => $order->id,
                    'old_transaction_id' => $paymentTransaction->id,
                    'new_transaction_id' => $paymentTransaction->id,
                ]);
            }

            // Tạo thanh toán MoMo
            // Tạo orderId unique bằng cách kết hợp order code với payment transaction ID và timestamp
            // để đảm bảo không trùng lặp khi tạo lại payment transaction
            // Format: {order_code}_{payment_transaction_id}_{timestamp}
            $momoOrderId = $order->code . '_' . $paymentTransaction->id . '_' . time();
            
            $momoService = new MoMoService();
            $result = $momoService->createPayment(
                $momoOrderId, // Sử dụng order code + payment transaction ID + timestamp để đảm bảo unique
                $paymentTransaction->amount,
                "Thanh toán đơn hàng #{$order->code}",
                json_encode(['order_id' => $order->id, 'payment_transaction_id' => $paymentTransaction->id])
            );

            if ($result['success'] && isset($result['payUrl'])) {
                // Lưu requestId vào payment transaction để tracking
                $paymentTransaction->update([
                    'momo_request_id' => $result['requestId'] ?? null,
                ]);

                // Nếu request là AJAX, trả về JSON
                if ($isAjax) {
                    return response()->json([
                        'success' => true,
                        'payUrl' => $result['payUrl'],
                        'message' => 'Tạo thanh toán thành công',
                    ]);
                }

                // Redirect đến MoMo payment page (fallback cho non-AJAX)
                return redirect($result['payUrl']);
            } else {
                if ($isAjax) {
                    return response()->json([
                        'success' => false,
                        'message' => $result['message'] ?? 'Không thể tạo thanh toán MoMo. Vui lòng thử lại.',
                    ], 400);
                }
                return redirect()->route('orders.show', $order->id)
                    ->with('error', $result['message'] ?? 'Không thể tạo thanh toán MoMo. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            Log::error('MoMo Initiate Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            // Luôn trả về JSON nếu là AJAX request
            if ($isAjax) {
                return response()->json([
                    'success' => false,
                    'message' => 'Có lỗi xảy ra khi khởi tạo thanh toán MoMo: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi khởi tạo thanh toán MoMo.');
        }
    }

    /**
     * IPN (Instant Payment Notification) từ MoMo
     * MoMo sẽ gọi endpoint này để thông báo kết quả thanh toán
     */
    public function momoIpn(Request $request)
    {
        try {
            Log::info('MoMo IPN Received', ['data' => $request->all()]);

            $momoService = new MoMoService();

            // Xác thực signature
            if (!$momoService->verifySignature($request->all())) {
                Log::warning('MoMo IPN: Invalid signature', ['data' => $request->all()]);
                return response()->json([
                    'resultCode' => -1,
                    'message' => 'Invalid signature',
                ], 400);
            }

            $momoOrderId = $request->orderId; // Format: {order_code}_{payment_transaction_id}_{timestamp}
            $resultCode = $request->resultCode;
            $amount = $request->amount;
            $transId = $request->transId ?? null;

            // Parse orderId để lấy order code và payment transaction ID
            // Format: {order_code}_{payment_transaction_id}_{timestamp}
            $parts = explode('_', $momoOrderId);
            $orderCode = $parts[0]; // Lấy phần đầu là order code
            
            // Tìm đơn hàng theo code
            $order = Order::where('code', $orderCode)->first();

            if (!$order) {
                Log::warning('MoMo IPN: Order not found', ['momo_order_id' => $momoOrderId, 'order_code' => $orderCode]);
                return response()->json([
                    'resultCode' => -1,
                    'message' => 'Order not found',
                ], 404);
            }

            // Tìm payment transaction theo ID nếu có trong orderId
            // Format: {order_code}_{payment_transaction_id}_{timestamp}
            // Phần giữa (index 1) là payment_transaction_id, phần cuối (index 2) là timestamp
            $paymentTransaction = null;
            if (count($parts) >= 2 && is_numeric($parts[1])) {
                // Có payment transaction ID trong orderId (phần giữa)
                $paymentTransactionId = (int) $parts[1];
                $paymentTransaction = PaymentTransaction::where('id', $paymentTransactionId)
                    ->where('order_id', $order->id)
                    ->where('payment_method', 'momo')
                    ->first();
            }
            
            // Nếu không tìm thấy theo ID, tìm pending transaction
            if (!$paymentTransaction) {
                $paymentTransaction = PaymentTransaction::where('order_id', $order->id)
                    ->where('payment_method', 'momo')
                    ->where('status', 'pending')
                    ->latest()
                    ->first();
            }

            if (!$paymentTransaction) {
                Log::warning('MoMo IPN: Payment transaction not found', [
                    'order_id' => $order->id,
                    'order_code' => $orderCode,
                    'momo_order_id' => $momoOrderId,
                ]);
                return response()->json([
                    'resultCode' => -1,
                    'message' => 'Payment transaction not found',
                ], 404);
            }

            // Kiểm tra số tiền
            if (abs($paymentTransaction->amount - $amount) > 1000) {
                Log::warning('MoMo IPN: Amount mismatch', [
                    'expected' => $paymentTransaction->amount,
                    'received' => $amount,
                ]);
                return response()->json([
                    'resultCode' => -1,
                    'message' => 'Amount mismatch',
                ], 400);
            }

            // Xử lý kết quả thanh toán
            if ($resultCode == 0) {
                // Thanh toán thành công
                DB::beginTransaction();
                try {
                    $paymentTransaction->update([
                        'status' => 'completed',
                        'paid_at' => now(),
                        'momo_trans_id' => $transId,
                        'momo_response_data' => json_encode($request->all()),
                    ]);

                    // Cập nhật trạng thái đơn hàng
                    if ($order->status === 'pending') {
                        $order->update([
                            'status' => 'processing',
                        ]);
                    }

                    DB::commit();

                    Log::info('MoMo IPN: Payment confirmed', [
                        'order_id' => $order->id,
                        'payment_transaction_id' => $paymentTransaction->id,
                        'trans_id' => $transId,
                    ]);

                    return response()->json([
                        'resultCode' => 0,
                        'message' => 'Success',
                    ]);
                } catch (\Exception $e) {
                    DB::rollBack();
                    Log::error('MoMo IPN: Database error', [
                        'error' => $e->getMessage(),
                    ]);
                    return response()->json([
                        'resultCode' => -1,
                        'message' => 'Database error',
                    ], 500);
                }
            } else {
                // Thanh toán thất bại
                $paymentTransaction->update([
                    'status' => 'failed',
                    'momo_response_data' => json_encode($request->all()),
                ]);

                Log::info('MoMo IPN: Payment failed', [
                    'order_id' => $order->id,
                    'result_code' => $resultCode,
                    'message' => $request->message ?? '',
                ]);

                return response()->json([
                    'resultCode' => 0, // Vẫn trả về 0 để MoMo biết đã nhận được
                    'message' => 'Payment failed recorded',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('MoMo IPN Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'resultCode' => -1,
                'message' => 'Server error',
            ], 500);
        }
    }

    /**
     * Redirect sau khi thanh toán MoMo thành công
     */
    public function momoRedirect(Request $request)
    {
        try {
            $resultCode = $request->resultCode;
            $momoOrderId = $request->orderId; // Format: {order_code}_{payment_transaction_id}_{timestamp}

            // Parse orderId để lấy order code
            // Format: {order_code}_{payment_transaction_id}_{timestamp}
            $parts = explode('_', $momoOrderId);
            $orderCode = $parts[0]; // Lấy phần đầu là order code

            // Tìm đơn hàng
            $order = Order::where('code', $orderCode)
                ->where('user_id', Auth::id())
                ->firstOrFail();

            // Xử lý cả resultCode == 0 (code mới) và resultCode == 1006 (code cũ)
            if ($resultCode == 0 || $resultCode == 1006) {
                // Thanh toán thành công
                return redirect()->route('orders.success', $order->id)
                    ->with('success', 'Thanh toán MoMo thành công!');
            } else {
                // Thanh toán thất bại hoặc hủy
                return redirect()->route('orders.show', $order->id)
                    ->with('error', 'Thanh toán MoMo không thành công. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            Log::error('MoMo Redirect Error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all(),
            ]);

            return redirect()->route('orders.index')
                ->with('error', 'Có lỗi xảy ra khi xử lý kết quả thanh toán.');
        }
    }

    /**
     * Xác nhận thanh toán MoMo từ form (tích hợp từ code cũ)
     * Nhận số tiền từ form và tạo payment request
     */
    public function confirmMomo(Request $request)
    {
        try {
            $request->validate([
                'sotien' => 'required|numeric|min:1000', // Tối thiểu 1000 VND
            ]);

            $amount = (int) $request->sotien;
            $userId = Auth::id();

            if (!$userId) {
                return redirect()->back()
                    ->with('error', 'Vui lòng đăng nhập để thanh toán.');
            }

            // Tạo payment transaction mới
            $paymentTransaction = PaymentTransaction::create([
                'order_id' => null, // Có thể null nếu là nạp tiền
                'payment_method' => 'momo',
                'amount' => $amount,
                'status' => 'pending',
            ]);

            // Tạo orderId unique
            $orderId = time() . '_' . $paymentTransaction->id;

            // Lấy redirect URL - trỏ về momo_post (theo code cũ)
            // Sử dụng http://localhost:8080/ như yêu cầu
            $baseUrl = config('app.url', 'http://localhost:8080');
            $redirectUrl = rtrim($baseUrl, '/') . '/payment/momo/post';
            $ipnUrl = rtrim($baseUrl, '/') . '/payment/momo/ipn';

            // Sử dụng MoMoService để tạo payment với redirect URL tùy chỉnh
            $momoService = new MoMoService();
            
            // Tạm thời override redirect URL trong service
            // Hoặc tạo method mới trong service để nhận redirect URL
            $result = $momoService->createPaymentWithUrls(
                $orderId,
                $amount,
                "Thanh toán qua mã QR MoMo",
                json_encode(['user_id' => $userId, 'payment_transaction_id' => $paymentTransaction->id]),
                $redirectUrl,
                $ipnUrl
            );

            if ($result['success'] && isset($result['payUrl'])) {
                // Lưu requestId vào payment transaction
                $paymentTransaction->update([
                    'momo_request_id' => $result['requestId'] ?? null,
                ]);

                // Redirect đến MoMo payment page
                return redirect($result['payUrl']);
            } else {
                return redirect()->back()
                    ->with('error', $result['message'] ?? 'Không thể tạo thanh toán MoMo. Vui lòng thử lại.');
            }
        } catch (\Exception $e) {
            Log::error('MoMo Confirm Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tạo thanh toán MoMo.');
        }
    }

    /**
     * Xử lý redirect sau khi thanh toán MoMo (tích hợp từ code cũ)
     * resultCode == 1006 là thanh toán thành công
     */
    public function momoPost(Request $request)
    {
        try {
            $resultCode = $request->resultCode;
            $orderId = $request->orderId ?? '';
            $userId = Auth::id();

            Log::info('MoMo Post Redirect', [
                'result_code' => $resultCode,
                'order_id' => $orderId,
                'all_params' => $request->all(),
            ]);

            // Kiểm tra resultCode == 1006 (thanh toán thành công theo code cũ)
            if ($resultCode == 1006 || $resultCode == 0) {
                // Parse orderId để lấy payment transaction ID
                $parts = explode('_', $orderId);
                $paymentTransactionId = null;
                
                if (count($parts) >= 2 && is_numeric($parts[count($parts) - 1])) {
                    // Format: {timestamp}_{payment_transaction_id}
                    $paymentTransactionId = (int) $parts[count($parts) - 1];
                } elseif (count($parts) >= 2 && is_numeric($parts[1])) {
                    // Format: {timestamp}_{payment_transaction_id}_{timestamp}
                    $paymentTransactionId = (int) $parts[1];
                }

                if ($paymentTransactionId) {
                    $paymentTransaction = PaymentTransaction::find($paymentTransactionId);
                    
                    if ($paymentTransaction) {
                        // Cập nhật trạng thái thanh toán
                        $paymentTransaction->update([
                            'status' => 'completed',
                            'paid_at' => now(),
                            'momo_response_data' => json_encode($request->all()),
                        ]);

                        Log::info('MoMo Post: Payment confirmed', [
                            'payment_transaction_id' => $paymentTransactionId,
                            'result_code' => $resultCode,
                        ]);
                    }
                }

                // Lưu thông tin vào session hoặc database nếu cần
                // Tương tự như code cũ: storeMomoInfo($customer_id, $momo_status, $link_data)
                if ($userId) {
                    // Có thể lưu vào bảng riêng hoặc payment_transactions
                    // Ở đây chúng ta đã lưu vào payment_transactions rồi
                }

                // Redirect với thông báo thành công
                return redirect()->route('orders.index')
                    ->with('success', 'Nạp momo thành công, vui lòng chờ Admin duyệt đơn nhé.');
            } else {
                // Thanh toán thất bại hoặc hủy
                return redirect()->route('orders.index')
                    ->with('error', 'Lỗi trong quá trình nạp Momo.');
            }
        } catch (\Exception $e) {
            Log::error('MoMo Post Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
            ]);

            return redirect()->route('orders.index')
                ->with('error', 'Có lỗi xảy ra khi xử lý kết quả thanh toán.');
        }
    }

    /**
     * Lấy thông báo theo trạng thái
     */
    private function getStatusMessage($status)
    {
        return match($status) {
            'pending' => 'Đang chờ thanh toán',
            'completed' => 'Thanh toán thành công',
            'expired' => 'Thời gian thanh toán đã hết hạn',
            'failed' => 'Thanh toán thất bại',
            default => 'Trạng thái không xác định',
        };
    }
}
