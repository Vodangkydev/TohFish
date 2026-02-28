<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Image;
use App\Models\PaymentTransaction;
use App\Services\CartService;
use App\Services\MoMoService;
use App\Jobs\CheckPaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }
    
    /**
     * Tính tổng tiền cho các sản phẩm đã chọn
     */
    private function calculateSelectedItemsTotal(array $items): int
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += ($item['price'] ?? 0) * ($item['quantity'] ?? 1);
        }
        $shipping = 0;
        return $subtotal + $shipping;
    }

    /**
     * Xử lý đặt hàng
     */
    public function store(CheckoutRequest $request)
    {
        try {
            $allCartItems = $this->cartService->all();
            
            // Xử lý các sản phẩm đã chọn từ hidden input hoặc request
            $selectedIds = [];
            if ($request->has('selected_ids') && !empty($request->selected_ids)) {
                $selectedIds = is_array($request->selected_ids) 
                    ? array_map('intval', $request->selected_ids)
                    : array_map('intval', explode(',', $request->selected_ids));
            }
            
            // Nếu có selected IDs, chỉ lấy các sản phẩm đã chọn
            if (!empty($selectedIds)) {
                $cartItems = [];
                foreach ($allCartItems as $item) {
                    if (in_array((int)$item['id'], $selectedIds)) {
                        $cartItems[$item['id']] = $item;
                    }
                }
            } else {
                // Nếu không có selected, lấy tất cả
                $cartItems = $allCartItems;
            }
            
            // Validation: Đảm bảo có ít nhất 1 sản phẩm với số lượng >= 1
            if (empty($cartItems) || !is_array($cartItems)) {
                return redirect()->route('cart')
                    ->with('error', 'Giỏ hàng trống. Vui lòng chọn ít nhất 1 sản phẩm để thanh toán.');
            }
            
            // Kiểm tra số lượng các sản phẩm - cho phép mua từ 1 sản phẩm trở lên
            $validItems = 0;
            $totalQuantity = 0;
            foreach ($cartItems as $item) {
                $quantity = (int)($item['quantity'] ?? 0);
                if ($quantity > 0) {
                    $validItems++;
                    $totalQuantity += $quantity;
                }
            }
            
            // Cho phép mua từ 1 sản phẩm trở lên
            if ($validItems < 1 || $totalQuantity < 1) {
                return redirect()->route('cart')
                    ->with('error', 'Giỏ hàng trống. Vui lòng chọn ít nhất 1 sản phẩm để thanh toán.');
            }

            if (!Auth::check()) {
                return redirect()->route('login')
                    ->with('error', 'Vui lòng đăng nhập để đặt hàng.');
            }

            // Validate products exist (optional check, but helpful for user feedback)
            $invalidProducts = [];
            foreach ($cartItems as $item) {
                $productId = $item['id'];
                if (!Image::where('images_id', $productId)->exists()) {
                    $invalidProducts[] = $item['name'] ?? "ID: {$productId}";
                }
            }

            if (!empty($invalidProducts)) {
                Log::warning('Attempted to order non-existent products', [
                    'products' => $invalidProducts,
                    'user_id' => Auth::id()
                ]);
                // Continue anyway since we store all product info in order_items
                // This is just for logging purposes
            }

            DB::beginTransaction();

            // Tạo đơn hàng
            $order = Order::create([
                'user_id' => Auth::id(),
                'code' => Order::generateCode(),
                'shipping_name' => $request->fullname,
                'shipping_address' => $request->address,
                'shipping_phone' => $request->phone,
                'email' => $request->email,
                'city' => $request->city,
                'district' => $request->district,
                'note' => $request->note,
                'payment_method' => $request->payment,
                'total_amount' => $this->calculateSelectedItemsTotal($cartItems),
                'status' => 'pending',
            ]);

            // Tạo order items
            foreach ($cartItems as $item) {
                try {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['id'],
                        'product_name' => $item['name'] ?? 'Sản phẩm không xác định',
                        'product_price' => (int)($item['price'] ?? 0),
                        'quantity' => (int)($item['quantity'] ?? 1),
                        'subtotal' => (int)(($item['price'] ?? 0) * ($item['quantity'] ?? 1)),
                        'product_image' => $item['image'] ?? null,
                    ]);
                } catch (\Exception $itemException) {
                    Log::error('Error creating order item', [
                        'order_id' => $order->id,
                        'product_id' => $item['id'] ?? null,
                        'error' => $itemException->getMessage(),
                        'item_data' => $item
                    ]);
                    throw $itemException;
                }
            }

            // Tạo payment transaction nếu là thanh toán online
            if (in_array($request->payment, ['bank', 'momo'])) {
                try {
                    $paymentTransaction = PaymentTransaction::create([
                        'order_id' => $order->id,
                        'payment_method' => $request->payment,
                        'amount' => $this->calculateSelectedItemsTotal($cartItems),
                        'status' => 'pending',
                    ]);

                    // Schedule job để kiểm tra thanh toán sau 3 phút
                    try {
                        CheckPaymentStatus::dispatch($paymentTransaction)
                            ->delay(now()->addMinutes(3));
                    } catch (\Exception $jobException) {
                        // Nếu queue không hoạt động, log nhưng không fail đơn hàng
                        Log::warning('Failed to dispatch CheckPaymentStatus job', [
                            'error' => $jobException->getMessage(),
                            'order_id' => $order->id,
                            'payment_transaction_id' => $paymentTransaction->id,
                        ]);
                    }
                    
                    // Nếu là thanh toán MoMo, tự động redirect đến thanh toán luôn
                    if ($request->payment === 'momo') {
                        DB::commit();
                        
                        // Xóa giỏ hàng trước khi redirect
                        $this->cartService->clear();
                        
                        // Tạo MoMo payment và redirect thẳng
                        try {
                            $momoService = new MoMoService();
                            $momoOrderId = $order->code . '_' . $paymentTransaction->id . '_' . time();
                            
                            $result = $momoService->createPayment(
                                $momoOrderId,
                                $paymentTransaction->amount,
                                "Thanh toán đơn hàng #{$order->code}",
                                json_encode(['order_id' => $order->id, 'payment_transaction_id' => $paymentTransaction->id])
                            );
                            
                            if ($result['success'] && isset($result['payUrl'])) {
                                // Lưu requestId vào payment transaction
                                $paymentTransaction->update([
                                    'momo_request_id' => $result['requestId'] ?? null,
                                ]);
                                
                                // Redirect thẳng đến thanh toán MoMo
                                return redirect($result['payUrl']);
                            } else {
                                // Nếu không tạo được payment, vẫn redirect đến trang success
                                Log::error('Failed to create MoMo payment', [
                                    'order_id' => $order->id,
                                    'result' => $result
                                ]);
                                return redirect()->route('orders.success', $order->id)
                                    ->with('error', 'Không thể tạo thanh toán MoMo. Vui lòng thử lại từ trang đơn hàng.');
                            }
                        } catch (\Exception $momoException) {
                            Log::error('Error creating MoMo payment', [
                                'error' => $momoException->getMessage(),
                                'order_id' => $order->id,
                            ]);
                            // Nếu có lỗi, vẫn redirect đến trang success
                            return redirect()->route('orders.success', $order->id)
                                ->with('error', 'Có lỗi xảy ra khi tạo thanh toán MoMo. Vui lòng thử lại từ trang đơn hàng.');
                        }
                    }
                } catch (\Exception $paymentException) {
                    // Nếu không tạo được payment transaction, log nhưng không fail đơn hàng
                    Log::error('Failed to create payment transaction', [
                        'error' => $paymentException->getMessage(),
                        'order_id' => $order->id,
                        'payment_method' => $request->payment,
                    ]);
                    // Tiếp tục xử lý đơn hàng dù không tạo được payment transaction
                }
            }

            // Xóa giỏ hàng
            $this->cartService->clear();

            DB::commit();

            return redirect()->route('orders.success', $order->id)
                ->with('success', 'Đặt hàng thành công!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return redirect()->route('checkout')
                ->withErrors($e->errors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in OrderController@store', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'cart_items' => $cartItems ?? []
            ]);

            return redirect()->route('checkout')
                ->with('error', 'Đã xảy ra lỗi khi đặt hàng. Vui lòng thử lại. Nếu lỗi vẫn tiếp tục, vui lòng liên hệ với chúng tôi.')
                ->withInput();
        }
    }

    /**
     * Trang thành công đặt hàng
     */
    public function success($id)
    {
        $order = Order::with(['items', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $paymentTransaction = PaymentTransaction::where('order_id', $order->id)
            ->latest()
            ->first();

        return view('tohfish.orders.success', compact('order', 'paymentTransaction'));
    }

    /**
     * Lịch sử mua hàng
     */
    public function index()
    {
        $orders = Order::with('items')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('tohfish.orders.index', compact('orders'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function show($id)
    {
        $order = Order::with(['items.product', 'user'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $paymentTransaction = PaymentTransaction::where('order_id', $order->id)
            ->latest()
            ->first();

        return view('tohfish.orders.show', compact('order', 'paymentTransaction'));
    }

    /**
     * Hủy đơn hàng (chỉ cho phép khi status = 'pending')
     */
    public function cancel($id)
    {
        try {
            $order = Order::where('user_id', Auth::id())
                ->findOrFail($id);

            // Chỉ cho phép hủy khi đơn hàng ở trạng thái "Chờ xử lý"
            if ($order->status !== 'pending') {
                return redirect()->route('orders.show', $id)
                    ->with('error', 'Chỉ có thể hủy đơn hàng khi đơn hàng đang ở trạng thái "Chờ xử lý".');
            }

            $order->update([
                'status' => 'cancelled'
            ]);

            Log::info('Order cancelled by user', [
                'order_id' => $order->id,
                'order_code' => $order->code,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('orders.show', $id)
                ->with('success', 'Đơn hàng đã được hủy thành công.');
        } catch (\Exception $e) {
            Log::error('Error cancelling order: ' . $e->getMessage());
            return redirect()->route('orders.show', $id)
                ->with('error', 'Có lỗi xảy ra khi hủy đơn hàng. Vui lòng thử lại.');
        }
    }

    /**
     * Mua lại đơn hàng đã hủy (thêm tất cả sản phẩm vào giỏ hàng)
     */
    public function reorder($id)
    {
        try {
            $order = Order::with('items')
                ->where('user_id', Auth::id())
                ->findOrFail($id);

            // Chỉ cho phép mua lại khi đơn hàng đã bị hủy
            if ($order->status !== 'cancelled') {
                return redirect()->route('orders.show', $id)
                    ->with('error', 'Chỉ có thể mua lại đơn hàng đã bị hủy.');
            }

            // Thêm tất cả sản phẩm từ đơn hàng vào giỏ hàng
            foreach ($order->items as $item) {
                $this->cartService->add(
                    $item->product_id,
                    $item->product_name,
                    $item->product_price,
                    $item->quantity,
                    $item->product_image
                );
            }

            Log::info('Order reordered by user', [
                'order_id' => $order->id,
                'order_code' => $order->code,
                'user_id' => Auth::id(),
                'items_count' => $order->items->count()
            ]);

            return redirect()->route('cart')
                ->with('success', 'Đã thêm tất cả sản phẩm từ đơn hàng vào giỏ hàng. Bạn có thể tiếp tục đặt hàng.');
        } catch (\Exception $e) {
            Log::error('Error reordering: ' . $e->getMessage());
            return redirect()->route('orders.show', $id)
                ->with('error', 'Có lỗi xảy ra khi mua lại đơn hàng. Vui lòng thử lại.');
        }
    }
}
