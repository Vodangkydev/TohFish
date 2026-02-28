<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        $items = $this->cartService->all();
        $totals = $this->cartService->totals();

        // Nếu là AJAX request, trả về JSON
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $cartCount = collect($items)->sum('quantity');
            return response()->json([
                'success' => true,
                'items' => $items,
                'totals' => $totals,
                'cart_count' => $cartCount
            ]);
        }

        return view('tohfish.cart', [
            'items'  => $items,
            'totals' => $totals,
        ]);
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'name'       => ['required', 'string', 'max:255'],
            'price'      => ['required', 'numeric', 'min:0'],
            'quantity'   => ['nullable', 'integer', 'min:1'],
            'image'      => ['nullable', 'string', 'max:255'],
        ]);

        $quantity = $data['quantity'] ?? 1;

        $this->cartService->add(
            $data['product_id'],
            $data['name'],
            $data['price'],
            $quantity,
            $data['image'] ?? null
        );

        // Nếu là AJAX request hoặc có header X-Requested-With, trả về JSON
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            $items = $this->cartService->all();
            $totals = $this->cartService->totals();
            $cartCount = collect($items)->sum('quantity');
            
            return response()->json([
                'success' => true,
                'message' => 'Đã thêm sản phẩm vào giỏ hàng.',
                'cart_count' => $cartCount,
                'items' => $items,
                'totals' => $totals
            ]);
        }

        return redirect()->route('cart')->with('status', 'Đã thêm sản phẩm vào giỏ hàng.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'items'               => ['required', 'array'],
            'items.*.product_id'  => ['required', 'integer'],
            'items.*.quantity'    => ['required', 'integer', 'min:0'],
        ]);

        foreach ($data['items'] as $item) {
            $this->cartService->updateQuantity($item['product_id'], $item['quantity']);
        }

        // Nếu là AJAX request, trả về JSON
        if ($request->ajax() || $request->wantsJson()) {
            $items = $this->cartService->all();
            $totals = $this->cartService->totals();
            $cartCount = collect($items)->sum('quantity');
            
            return response()->json([
                'success' => true,
                'message' => 'Cập nhật giỏ hàng thành công.',
                'cart_count' => $cartCount,
                'items' => $items,
                'totals' => $totals
            ]);
        }

        return redirect()->route('cart')->with('status', 'Cập nhật giỏ hàng thành công.');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
        ]);

        $this->cartService->remove($data['product_id']);

        // Nếu là AJAX request, trả về JSON
        if ($request->ajax() || $request->wantsJson()) {
            $items = $this->cartService->all();
            $totals = $this->cartService->totals();
            $cartCount = collect($items)->sum('quantity');
            
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa sản phẩm khỏi giỏ hàng.',
                'cart_count' => $cartCount,
                'items' => $items,
                'totals' => $totals
            ]);
        }

        return redirect()->route('cart')->with('status', 'Đã xóa sản phẩm khỏi giỏ hàng.');
    }

    public function buyNow(Request $request)
    {
        // Mua ngay: thêm vào giỏ (1 sản phẩm) rồi chuyển sang trang thanh toán
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'name'       => ['required', 'string', 'max:255'],
            'price'      => ['required', 'numeric', 'min:0'],
            'quantity'   => ['nullable', 'integer', 'min:1'],
            'image'      => ['nullable', 'string', 'max:255'],
        ]);

        $quantity = $data['quantity'] ?? 1;

        $this->cartService->add(
            $data['product_id'],
            $data['name'],
            $data['price'],
            $quantity,
            $data['image'] ?? null
        );

        return redirect()->route('checkout');
    }

    public function clearAll(Request $request)
    {
        $this->cartService->clear();

        // Nếu là AJAX request, trả về JSON
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'message' => 'Đã xóa tất cả sản phẩm khỏi giỏ hàng.',
                'cart_count' => 0,
                'items' => [],
                'totals' => [
                    'subtotal' => 0,
                    'shipping' => 0,
                    'total' => 0
                ]
            ]);
        }

        return redirect()->route('cart')->with('status', 'Đã xóa tất cả sản phẩm khỏi giỏ hàng.');
    }


    /**
     * Cập nhật trạng thái chọn của một sản phẩm
     */
    public function updateSelection(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer'],
            'is_selected' => ['required', 'boolean'],
        ]);

        $this->cartService->updateSelection($data['product_id'], $data['is_selected']);

        $items = $this->cartService->all();
        $totals = $this->cartService->totals();
        $cartCount = collect($items)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái chọn thành công.',
            'cart_count' => $cartCount,
            'items' => $items,
            'totals' => $totals
        ]);
    }

    /**
     * Cập nhật trạng thái chọn cho nhiều sản phẩm
     */
    public function updateSelections(Request $request)
    {
        $data = $request->validate([
            'selections' => ['required', 'array'],
            'selections.*' => ['required', 'boolean'],
        ]);

        $this->cartService->updateSelections($data['selections']);

        $items = $this->cartService->all();
        $totals = $this->cartService->totals();
        $cartCount = collect($items)->sum('quantity');

        return response()->json([
            'success' => true,
            'message' => 'Cập nhật trạng thái chọn thành công.',
            'cart_count' => $cartCount,
            'items' => $items,
            'totals' => $totals
        ]);
    }

}


