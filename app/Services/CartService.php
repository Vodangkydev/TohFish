<?php

namespace App\Services;

use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected string $sessionKey = 'cart';

    /**
     * Lấy tất cả items trong giỏ hàng
     * - Nếu user đã login: lấy từ database và đồng bộ giá từ sản phẩm
     * - Nếu guest: lấy từ session và đồng bộ giá từ sản phẩm
     */
    public function all(): array
    {
        if (Auth::check()) {
            // Lấy từ database
            $cartItems = CartItem::where('user_id', Auth::id())->get();
            $cart = [];
            foreach ($cartItems as $item) {
                // Đồng bộ giá từ sản phẩm thực tế trong database
                $product = \App\Models\Image::find($item->product_id);
                if ($product) {
                    // Tính giá sau khi giảm giá
                    $originalPrice = $product->price ?? 0;
                    $discountPercent = $product->discount_percent ?? 0;
                    if ($discountPercent > 0 && $discountPercent <= 100) {
                        $currentPrice = $originalPrice * (1 - $discountPercent / 100);
                    } else {
                        $currentPrice = $originalPrice;
                    }
                    
                    // Cập nhật giá nếu khác với giá hiện tại
                    if (abs($item->product_price - $currentPrice) > 0.01) {
                        $item->product_price = $currentPrice;
                        $item->save();
                    }
                    // Cập nhật tên và hình ảnh nếu cần
                    if (empty($item->product_name) && !empty($product->content)) {
                        $item->product_name = $product->content;
                        $item->save();
                    }
                }
                $cart[$item->product_id] = $item->toCartArray();
            }
            return $cart;
        }
        
        // Guest: lấy từ session và đồng bộ giá từ sản phẩm
        $cart = Session::get($this->sessionKey, []);
        foreach ($cart as $productId => &$item) {
            $product = \App\Models\Image::find($productId);
            if ($product) {
                // Tính giá sau khi giảm giá
                $originalPrice = $product->price ?? 0;
                $discountPercent = $product->discount_percent ?? 0;
                if ($discountPercent > 0 && $discountPercent <= 100) {
                    $currentPrice = $originalPrice * (1 - $discountPercent / 100);
                } else {
                    $currentPrice = $originalPrice;
                }
                
                // Cập nhật giá từ database
                $item['price'] = $currentPrice;
                // Cập nhật tên nếu cần
                if (empty($item['name']) && !empty($product->content)) {
                    $item['name'] = $product->content;
                }
            }
        }
        // Lưu lại session với giá đã cập nhật
        Session::put($this->sessionKey, $cart);
        return $cart;
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     * Tự động lấy giá từ database nếu không được truyền vào
     */
    public function add(int $productId, string $name, float|int|null $price = null, int $quantity = 1, ?string $image = null): void
    {
        // Lấy thông tin sản phẩm từ database để đảm bảo giá chính xác
        $product = \App\Models\Image::find($productId);
        if ($product) {
            // Tính giá sau khi giảm giá
            $originalPrice = $product->price ?? 0;
            $discountPercent = $product->discount_percent ?? 0;
            if ($discountPercent > 0 && $discountPercent <= 100) {
                $currentPrice = $originalPrice * (1 - $discountPercent / 100);
            } else {
                $currentPrice = $originalPrice;
            }
            
            // Sử dụng giá từ database nếu không truyền vào hoặc giá truyền vào khác
            if ($price === null || abs($price - $currentPrice) > 0.01) {
                $price = $currentPrice;
            }
            // Cập nhật tên nếu rỗng
            if (empty($name) && !empty($product->content)) {
                $name = $product->content;
            }
        }
        
        if (Auth::check()) {
            // Lưu vào database
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();
            
            if ($cartItem) {
                // Cập nhật quantity
                $cartItem->quantity += $quantity;
                $cartItem->product_name = $name;
                $cartItem->product_price = $price;
                $cartItem->is_selected = true; // Sản phẩm vừa thêm sẽ tự động được tích chọn
                if ($image) {
                    $cartItem->product_image = $image;
                }
                $cartItem->save();
            } else {
                // Tạo mới
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'product_name' => $name,
                    'product_price' => $price,
                    'quantity' => $quantity,
                    'product_image' => $image,
                    'is_selected' => true, // Mặc định chọn khi thêm vào giỏ
                ]);
            }
        } else {
            // Guest: lưu vào session
            $cart = Session::get($this->sessionKey, []);
            
            if (isset($cart[$productId])) {
                $cart[$productId]['quantity'] += $quantity;
                // Cập nhật giá nếu đã thay đổi
                $cart[$productId]['price'] = $price;
                $cart[$productId]['is_selected'] = true; // Sản phẩm vừa thêm sẽ tự động được tích chọn
            } else {
                $cart[$productId] = [
                    'id'          => $productId,
                    'name'        => $name,
                    'price'       => $price,
                    'quantity'    => $quantity,
                    'image'       => $image,
                    'is_selected' => true, // Mặc định chọn khi thêm vào giỏ
                ];
            }
            
            Session::put($this->sessionKey, $cart);
        }
    }

    /**
     * Cập nhật số lượng sản phẩm
     */
    public function updateQuantity(int $productId, int $quantity): void
    {
        if (Auth::check()) {
            // Cập nhật trong database
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();
            
            if ($cartItem) {
                if ($quantity <= 0) {
                    $cartItem->delete();
                } else {
                    $cartItem->quantity = $quantity;
                    $cartItem->save();
                }
            }
        } else {
            // Guest: cập nhật trong session
            $cart = Session::get($this->sessionKey, []);
            
            if (isset($cart[$productId])) {
                if ($quantity <= 0) {
                    unset($cart[$productId]);
                } else {
                    $cart[$productId]['quantity'] = $quantity;
                }
                Session::put($this->sessionKey, $cart);
            }
        }
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     */
    public function remove(int $productId): void
    {
        if (Auth::check()) {
            // Xóa trong database
            CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->delete();
        } else {
            // Guest: xóa trong session
            $cart = Session::get($this->sessionKey, []);
            
            if (isset($cart[$productId])) {
                unset($cart[$productId]);
                Session::put($this->sessionKey, $cart);
            }
        }
    }

    /**
     * Xóa tất cả sản phẩm khỏi giỏ hàng
     */
    public function clear(): void
    {
        if (Auth::check()) {
            // Xóa trong database
            CartItem::where('user_id', Auth::id())->delete();
        } else {
            // Guest: xóa trong session
            Session::forget($this->sessionKey);
        }
    }

    /**
     * Tính tổng tiền - chỉ tính các sản phẩm đã chọn
     */
    public function totals(): array
    {
        $cart = $this->all();

        $subtotal = 0;
        foreach ($cart as $item) {
            // Chỉ tính các sản phẩm đã chọn
            if (($item['is_selected'] ?? true)) {
                $subtotal += $item['price'] * $item['quantity'];
            }
        }

        $shipping = 0; // tạm thời 0, sau có thể tính theo khu vực
        $total = $subtotal + $shipping;

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total'    => $total,
        ];
    }

    /**
     * Cập nhật trạng thái chọn của sản phẩm
     */
    public function updateSelection(int $productId, bool $isSelected): void
    {
        if (Auth::check()) {
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();
            
            if ($cartItem) {
                $cartItem->is_selected = $isSelected;
                $cartItem->save();
            }
        } else {
            // Guest: lưu vào session
            $cart = Session::get($this->sessionKey, []);
            
            if (isset($cart[$productId])) {
                $cart[$productId]['is_selected'] = $isSelected;
                Session::put($this->sessionKey, $cart);
            }
        }
    }

    /**
     * Cập nhật trạng thái chọn cho nhiều sản phẩm
     */
    public function updateSelections(array $selections): void
    {
        foreach ($selections as $productId => $isSelected) {
            $this->updateSelection($productId, $isSelected);
        }
    }


    /**
     * Chuyển giỏ hàng từ session sang database khi user login
     */
    public function syncFromSession(): void
    {
        if (!Auth::check()) {
            return;
        }

        $sessionCart = Session::get($this->sessionKey, []);
        
        if (empty($sessionCart)) {
            return;
        }

        foreach ($sessionCart as $productId => $item) {
            $cartItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $productId)
                ->first();
            
            if ($cartItem) {
                // Nếu đã có trong database, cộng thêm quantity
                $cartItem->quantity += ($item['quantity'] ?? 1);
                $cartItem->save();
            } else {
                // Tạo mới
                CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'product_name' => $item['name'] ?? '',
                    'product_price' => $item['price'] ?? 0,
                    'quantity' => $item['quantity'] ?? 1,
                    'product_image' => $item['image'] ?? null,
                ]);
            }
        }

        // Xóa session cart sau khi sync
        Session::forget($this->sessionKey);
    }
}
