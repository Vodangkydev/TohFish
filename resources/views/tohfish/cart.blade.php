@extends('layouts.app')

@section('title', 'Giỏ hàng - TOH fish')

@section('content')
<!-- Toast Notification Container -->
<div class="toast-container position-fixed top-0 start-50 translate-middle-x p-3" style="z-index: 9999; margin-top: 20px;">
    <div id="toastNotification" class="toast shadow-lg" role="alert" aria-live="assertive" aria-atomic="true" style="min-width: 350px;">
        <div class="toast-header">
            <i class="fas fa-info-circle me-2" id="toast-icon"></i>
            <strong class="me-auto" id="toast-title">Thông báo</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toast-message">
        </div>
    </div>
</div>

<style>
.toast-container {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        transform: translate(-50%, -100%);
        opacity: 0;
    }
    to {
        transform: translate(-50%, 0);
        opacity: 1;
    }
}

.toast {
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
}

.toast-header {
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    font-weight: 600;
}

.toast-body {
    padding: 12px 16px;
    font-size: 14px;
    line-height: 1.5;
}

.text-bg-success .toast-header,
.text-bg-success .toast-body {
    color: white;
}

.text-bg-danger .toast-header,
.text-bg-danger .toast-body {
    color: white;
}

.text-bg-warning .toast-header,
.text-bg-warning .toast-body {
    color: #000;
}

.text-bg-info .toast-header,
.text-bg-info .toast-body {
    color: white;
}

/* Modal xác nhận xóa - Hiển thị ở trên đầu, nằm giữa */
#confirmDeleteModal .modal-dialog {
    margin: 100px auto 0 !important;
    max-width: 450px;
}

#confirmDeleteModal .modal-content {
    border-radius: 12px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.25);
    border: none;
}

#confirmDeleteModal .modal-header {
    background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
    border-radius: 12px 12px 0 0;
    padding: 20px 24px 16px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

#confirmDeleteModal .modal-body {
    padding: 24px;
    text-align: center;
}

#confirmDeleteModal .modal-footer {
    background: #f8f9fa;
    border-radius: 0 0 12px 12px;
    padding: 16px 24px 20px;
    border-top: 1px solid rgba(0, 0, 0, 0.1);
    justify-content: center;
}

#confirmDeleteModal .btn-danger {
    padding: 10px 24px;
    font-weight: 600;
    border-radius: 8px;
    min-width: 140px;
}

#confirmDeleteModal .btn-secondary {
    padding: 10px 24px;
    font-weight: 600;
    border-radius: 8px;
    min-width: 140px;
}
</style>

<section class="cart-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title text-center mb-5">Giỏ hàng của bạn</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="cart-items bg-white p-4 rounded shadow-sm">
                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div>
                        <div class="mb-3 d-flex align-items-center justify-content-end">
                            <div>
                                <span id="cartSelectedCount">{{ count($items) }}</span> sản phẩm đã chọn
                            </div>
                        </div>
                        <table class="table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;"></th>
                                    <th>Sản phẩm</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Tổng</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($items as $item)
                                    <tr class="cart-item-row" data-product-id="{{ $item['id'] }}">
                                        <td>
                                            <input class="form-check-input cart-product-checkbox" 
                                                   type="checkbox" 
                                                   id="cart_product_{{ $item['id'] }}"
                                                   data-product-id="{{ $item['id'] }}"
                                                   checked>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if(!empty($item['image']))
                                                    <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="me-2" style="width: 60px; height: 60px; object-fit: cover;">
                                                @endif
                                                <span>{{ $item['name'] }}</span>
                                            </div>
                                        </td>
                                        <td class="item-price" data-price="{{ $item['price'] }}">{{ number_format($item['price']) }}₫</td>
                                        <td style="width: 120px;">
                                            <input type="number" 
                                                   name="items[{{ $item['id'] }}][quantity]" 
                                                   min="1" 
                                                   class="form-control form-control-sm cart-quantity-input" 
                                                   value="{{ $item['quantity'] }}"
                                                   data-product-id="{{ $item['id'] }}"
                                                   data-price="{{ $item['price'] }}">
                                            <input type="hidden" name="items[{{ $item['id'] }}][product_id]" value="{{ $item['id'] }}">
                                        </td>
                                        <td class="item-total" data-product-id="{{ $item['id'] }}">{{ number_format($item['price'] * $item['quantity']) }}₫</td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger cart-remove-btn" 
                                                    data-product-id="{{ $item['id'] }}"
                                                    title="Xóa sản phẩm">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                            <p class="text-muted">Hiện chưa có sản phẩm trong giỏ hàng</p>
                                            <a href="{{ route('home') }}" class="btn btn-primary">Tiếp tục mua hàng</a>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="cart-summary bg-white p-4 rounded shadow-sm">
                    <h4 class="mb-4">Tổng tiền</h4>
                    <table class="table">
                        <tr>
                            <td>Tạm tính:</td>
                            <td class="text-end" id="cart-subtotal">0₫</td>
                        </tr>
                        <tr>
                            <td>Phí vận chuyển:</td>
                            <td class="text-end" id="cart-shipping">0₫</td>
                        </tr>
                        <tr class="border-top">
                            <th>TỔNG TIỀN:</th>
                            <th class="text-end text-danger" id="cart-total">0₫</th>
                        </tr>
                    </table>
                    <div class="promo-code mt-3">
                        <label class="form-label">Mã khuyến mãi</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="promo-code-input" placeholder="Nhập mã TOH3" value="">
                            <button class="btn btn-primary" type="button" id="apply-promo-btn">Áp dụng</button>
                        </div>
                        <small class="text-muted" id="promo-message">Mã TOH3: Giảm 10% cho đơn hàng từ 600k</small>
                        <div id="promo-applied" class="mt-2" style="display: none;">
                            <span class="badge bg-success">Đã áp dụng mã: <span id="applied-code"></span></span>
                            <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2" id="remove-promo-btn">Xóa</button>
                        </div>
                    </div>
                    <button type="button" id="cartCheckoutBtn" class="btn btn-primary btn-lg w-100 mt-4 {{ empty($items) ? 'disabled' : '' }}" {{ empty($items) ? 'disabled' : '' }}>Đặt hàng</button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Modal xác nhận xóa sản phẩm -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="margin: 100px auto 0; max-width: 450px;">
        <div class="modal-content shadow-lg" style="border-radius: 12px; border: none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" id="confirmDeleteModalLabel">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Xác nhận xóa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <i class="fas fa-trash-alt fa-3x text-danger mb-3"></i>
                <p class="mb-0">Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?</p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteBtn">
                    <i class="fas fa-trash-alt me-2"></i>Xóa sản phẩm
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Hàm format số tiền
    function formatMoney(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
    }
    
    // Hàm hiển thị toast notification đẹp
    function showToast(message, type = 'info') {
        const toastEl = document.getElementById('toastNotification');
        const toastIcon = document.getElementById('toast-icon');
        const toastTitle = document.getElementById('toast-title');
        const toastMessage = document.getElementById('toast-message');
        
        // Xóa các class cũ
        toastEl.classList.remove('text-bg-primary', 'text-bg-success', 'text-bg-danger', 'text-bg-warning', 'text-bg-info');
        
        // Thiết lập màu và icon theo type
        let iconClass = 'fa-info-circle';
        let bgClass = 'text-bg-info';
        let title = 'Thông báo';
        
        switch(type) {
            case 'success':
                iconClass = 'fa-check-circle';
                bgClass = 'text-bg-success';
                title = 'Thành công';
                break;
            case 'error':
            case 'danger':
                iconClass = 'fa-exclamation-circle';
                bgClass = 'text-bg-danger';
                title = 'Lỗi';
                break;
            case 'warning':
                iconClass = 'fa-exclamation-triangle';
                bgClass = 'text-bg-warning';
                title = 'Cảnh báo';
                break;
            default:
                iconClass = 'fa-info-circle';
                bgClass = 'text-bg-info';
                title = 'Thông báo';
        }
        
        toastEl.classList.add(bgClass);
        toastIcon.className = `fas ${iconClass} me-2`;
        toastTitle.textContent = title;
        toastMessage.textContent = message;
        
        // Hiển thị toast
        const toast = new bootstrap.Toast(toastEl, {
            autohide: true,
            delay: 4000
        });
        toast.show();
    }
    
    // Biến lưu mã khuyến mãi
    let appliedPromoCode = null;
    let discountPercent = 0;
    
    // Hàm lưu trạng thái checkbox vào database qua API
    function saveSelectedItems() {
        const selections = {};
        const checkboxes = document.querySelectorAll('.cart-product-checkbox');
        
        checkboxes.forEach(checkbox => {
            const productId = parseInt(checkbox.getAttribute('data-product-id'));
            if (productId) {
                selections[productId] = checkbox.checked;
            }
        });
        
        // Gọi API để cập nhật trạng thái chọn vào database
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!csrfToken) {
            console.error('CSRF token not found');
            calculateTotals();
            return;
        }
        
        fetch('{{ route("cart.update_selections") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                selections: selections
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.totals) {
                // Cập nhật tổng tiền từ server
                const subtotalEl = document.getElementById('cart-subtotal');
                const shippingEl = document.getElementById('cart-shipping');
                const totalEl = document.getElementById('cart-total');
                
                if (subtotalEl) subtotalEl.textContent = formatMoney(data.totals.subtotal || 0);
                if (shippingEl) shippingEl.textContent = formatMoney(data.totals.shipping || 0);
                if (totalEl) totalEl.textContent = formatMoney(data.totals.total || 0);
            }
            updateCartCount();
        })
        .catch(error => {
            console.error('Error updating selection:', error);
            calculateTotals();
        });
    }
    
    // Hàm khôi phục trạng thái checkbox từ database (đã được render từ server)
    function restoreSelectedItems() {
        // Checkbox đã được set checked từ server dựa trên is_selected trong database
        // Chỉ cần cập nhật UI
        updateSelectedCount();
        calculateTotals();
    }
    
    
    // Hàm cập nhật số lượng sản phẩm đã chọn
    function updateSelectedCount() {
        const selectedCount = document.querySelectorAll('.cart-product-checkbox:checked').length;
        const countEl = document.getElementById('cartSelectedCount');
        if (countEl) {
            countEl.textContent = selectedCount;
        }
        updateCartCount();
    }
    
    // Hàm cập nhật cart count trong header
    // Đồng bộ định nghĩa với backend/sidebar: tổng số lượng sản phẩm trong giỏ (sum quantity),
    // KHÔNG phải số dòng sản phẩm được chọn.
    function updateCartCount() {
        let totalQuantity = 0;
        const quantityInputs = document.querySelectorAll('.cart-quantity-input');
        quantityInputs.forEach(input => {
            totalQuantity += parseInt(input.value) || 0;
        });

        const cartCountBadge = document.querySelector('.cart-count');
        if (cartCountBadge) {
            cartCountBadge.textContent = totalQuantity;
        }
    }
    
    // Hàm tính lại tổng tiền (chỉ tính các sản phẩm đã chọn)
    function calculateTotals() {
        let subtotal = 0;
        const rows = document.querySelectorAll('tbody tr.cart-item-row');
        
        rows.forEach(row => {
            const checkbox = row.querySelector('.cart-product-checkbox');
            const quantityInput = row.querySelector('.cart-quantity-input');
            const priceCell = row.querySelector('.item-price');
            const totalCell = row.querySelector('.item-total');
            
            if (quantityInput && priceCell && totalCell) {
                const quantity = parseInt(quantityInput.value) || 0;
                const price = parseFloat(priceCell.getAttribute('data-price')) || 0;
                const total = price * quantity;
                
                totalCell.textContent = formatMoney(total);
                
                // Chỉ cộng vào subtotal nếu sản phẩm được chọn
                if (checkbox && checkbox.checked) {
                    subtotal += total;
                }
            }
        });
        
        const shipping = 0; // Phí vận chuyển
        const minAmount = 600000; // 600k
        
        // Kiểm tra nếu đã áp dụng mã TOH3 nhưng đơn hàng dưới 600k thì tự động gỡ mã
        if (appliedPromoCode === 'TOH3' && subtotal < minAmount) {
            appliedPromoCode = null;
            discountPercent = 0;
            
            // Ẩn thông báo đã áp dụng
            document.getElementById('promo-applied').style.display = 'none';
            document.getElementById('promo-message').textContent = 'Mã TOH3: Giảm 10% cho đơn hàng từ 600k';
            document.getElementById('promo-message').classList.remove('text-success');
            document.getElementById('promo-code-input').disabled = false;
            document.getElementById('apply-promo-btn').disabled = false;
        }
        
        // Tính giảm giá nếu có mã khuyến mãi và đơn hàng đủ điều kiện
        let discount = 0;
        if (appliedPromoCode === 'TOH3' && discountPercent > 0 && subtotal >= minAmount) {
            discount = subtotal * (discountPercent / 100);
        }
        
        const total = subtotal - discount + shipping;
        
        // Cập nhật tổng tiền
        const subtotalEl = document.getElementById('cart-subtotal');
        const shippingEl = document.getElementById('cart-shipping');
        const totalEl = document.getElementById('cart-total');
        
        if (subtotalEl) subtotalEl.textContent = formatMoney(subtotal);
        if (shippingEl) shippingEl.textContent = formatMoney(shipping);
        if (totalEl) totalEl.textContent = formatMoney(total);
        
        // Hiển thị dòng giảm giá nếu có
        let discountRow = document.getElementById('discount-row');
        if (discount > 0) {
            if (!discountRow) {
                discountRow = document.createElement('tr');
                discountRow.id = 'discount-row';
                discountRow.className = 'text-success';
                discountRow.innerHTML = `
                    <td>Giảm giá (${discountPercent}%):</td>
                    <td class="text-end">-${formatMoney(discount)}</td>
                `;
                const shippingRow = shippingEl.closest('tr');
                if (shippingRow) {
                    shippingRow.insertAdjacentElement('afterend', discountRow);
                }
            } else {
                discountRow.innerHTML = `
                    <td>Giảm giá (${discountPercent}%):</td>
                    <td class="text-end">-${formatMoney(discount)}</td>
                `;
            }
        } else if (discountRow) {
            discountRow.remove();
        }
        
        // Cập nhật số lượng sản phẩm đã chọn
        updateSelectedCount();
    }
    
    // Debounce timer để tự động lưu sau khi người dùng ngừng nhập
    let saveTimeout = null;
    
    // Hàm tự động lưu giỏ hàng
    function autoSaveCart() {
        const items = [];
        const quantityInputs = document.querySelectorAll('.cart-quantity-input');
        
        quantityInputs.forEach(input => {
            const productId = input.getAttribute('data-product-id');
            const quantity = parseInt(input.value) || 0;
            if (productId && quantity > 0) {
                items.push({
                    product_id: parseInt(productId),
                    quantity: quantity
                });
            }
        });
        
        if (items.length === 0) return;
        
        // Gửi AJAX request để lưu
        fetch('{{ route('cart.update') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                items: items
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.totals) {
                // Cập nhật tổng tiền từ server
                const subtotalEl = document.getElementById('cart-subtotal');
                const shippingEl = document.getElementById('cart-shipping');
                const totalEl = document.getElementById('cart-total');
                
                if (subtotalEl) subtotalEl.textContent = formatMoney(data.totals.subtotal || 0);
                if (shippingEl) shippingEl.textContent = formatMoney(data.totals.shipping || 0);
                if (totalEl) totalEl.textContent = formatMoney(data.totals.total || 0);

                // Đồng bộ sidebar cart (nếu đang dùng)
                try {
                    if (typeof rebuildCartSidebarFromData === 'function') {
                        rebuildCartSidebarFromData(data.items || {}, data.totals || {});
                    }
                    if (typeof updateCartTotals === 'function') {
                        updateCartTotals();
                    }
                } catch (e) {
                    console.warn('Không thể sync sidebar sau autoSaveCart:', e);
                }
            }
        })
        .catch(error => {
            console.error('Error auto-saving cart:', error);
        });
    }
    
    // Xử lý thay đổi số lượng
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('cart-quantity-input')) {
            const quantity = parseInt(e.target.value) || 1;
            if (quantity < 1) {
                e.target.value = 1;
            }

            // Khi người dùng thay đổi số lượng ở trang giỏ hàng, tự động chọn sản phẩm đó
            const row = e.target.closest('tr.cart-item-row');
            if (row) {
                const checkbox = row.querySelector('.cart-product-checkbox');
                if (checkbox && !checkbox.checked) {
                    checkbox.checked = true;
                    // Lưu trạng thái chọn vào database (không chặn UI)
                    if (typeof saveSelectedItems === 'function') {
                        saveSelectedItems();
                    }
                }
            }
            
            // Tính lại tổng tiền ngay lập tức
            calculateTotals();
            
            // Xóa timer cũ nếu có
            if (saveTimeout) {
                clearTimeout(saveTimeout);
            }
            
            // Tự động lưu sau 300ms khi người dùng ngừng nhập (nhanh hơn, cảm giác realtime)
            saveTimeout = setTimeout(() => {
                autoSaveCart();
            }, 300);
        }
    });
    
    // Xử lý nút xóa sản phẩm với modal đẹp
    let deleteProductId = null;
    const confirmDeleteModal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
    
    document.addEventListener('click', function(e) {
        if (e.target.closest('.cart-remove-btn')) {
            const btn = e.target.closest('.cart-remove-btn');
            deleteProductId = btn.getAttribute('data-product-id');
            
            if (!deleteProductId) return;
            
            // Hiển thị modal xác nhận
            confirmDeleteModal.show();
        }
    });
    
    // Xử lý nút xác nhận xóa trong modal
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (!deleteProductId) return;

        // --------- OPTIMISTIC UPDATE ----------
        // Xóa ngay dòng sản phẩm trên trang giỏ hàng + tính lại tổng tiền
        // để người dùng thấy số tiền đổi tức thì, không phải chờ mạng.
        (function immediateRemoveFromCartPage(productId) {
            const btnInPage = document.querySelector(`.cart-remove-btn[data-product-id="${productId}"]`);
            if (btnInPage) {
                const row = btnInPage.closest('tr.cart-item-row');
                if (row) {
                    row.remove();
                }
            }
            // Tính lại tổng tiền trên trang giỏ hàng
            if (typeof calculateTotals === 'function') {
                calculateTotals();
            }
        })(deleteProductId);

        // Đồng bộ ngay sidebar (nếu đang mở) để số tiền / số lượng trùng nhau
        (function immediateSyncSidebarAfterDelete(productId) {
            try {
                // Xóa item tương ứng trong sidebar nếu tồn tại
                const sidebarItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
                if (sidebarItem) {
                    sidebarItem.remove();
                }
                if (typeof updateCartTotals === 'function') {
                    updateCartTotals();
                }
            } catch (e) {
                console.warn('Không thể sync sidebar ngay sau khi xóa, sẽ sync lại từ server:', e);
            }
        })(deleteProductId);

        // Gửi AJAX request để xóa trên server (đồng bộ dữ liệu thật)
        fetch('{{ route('cart.remove') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                product_id: parseInt(deleteProductId)
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Đóng modal
                confirmDeleteModal.hide();

                // Cập nhật tổng tiền từ server response
                if (data.totals) {
                    const subtotalEl = document.getElementById('cart-subtotal');
                    const shippingEl = document.getElementById('cart-shipping');
                    const totalEl = document.getElementById('cart-total');
                    
                    if (subtotalEl) subtotalEl.textContent = formatMoney(data.totals.subtotal || 0);
                    if (shippingEl) shippingEl.textContent = formatMoney(data.totals.shipping || 0);
                    if (totalEl) totalEl.textContent = formatMoney(data.totals.total || 0);
                } else {
                    // Tính lại tổng tiền từ client nếu server không trả về
                    calculateTotals();
                }

                // Đồng bộ sidebar cart (nếu đang dùng)
                try {
                    if (typeof rebuildCartSidebarFromData === 'function') {
                        rebuildCartSidebarFromData(data.items || {}, data.totals || {});
                    }
                    if (typeof updateCartTotals === 'function') {
                        updateCartTotals();
                    }
                } catch (e) {
                    console.warn('Không thể sync sidebar sau khi xóa trong trang giỏ hàng:', e);
                }
                
                // Cập nhật số lượng
                updateSelectedCount();
                
                // Hiển thị thông báo thành công
                showToast('Đã xóa sản phẩm khỏi giỏ hàng thành công!', 'success');
                
                // Kiểm tra nếu giỏ hàng trống (KHÔNG reload trang)
                const tbody = document.querySelector('tbody');
                const hasAnyItemRow = !!document.querySelector('tr.cart-item-row');
                if (tbody && !hasAnyItemRow) {
                    // Render trạng thái giỏ hàng trống ngay trên client
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Hiện chưa có sản phẩm trong giỏ hàng</p>
                                <a href="{{ route('home') }}" class="btn btn-primary">Tiếp tục mua hàng</a>
                            </td>
                        </tr>
                    `;

                    // Set totals về 0 ngay (server response sẽ sync lại nếu cần)
                    const subtotalEl = document.getElementById('cart-subtotal');
                    const shippingEl = document.getElementById('cart-shipping');
                    const totalEl = document.getElementById('cart-total');
                    if (subtotalEl) subtotalEl.textContent = formatMoney(0);
                    if (shippingEl) shippingEl.textContent = formatMoney(0);
                    if (totalEl) totalEl.textContent = formatMoney(0);

                    // Disable nút đặt hàng
                    const cartCheckoutBtn = document.getElementById('cartCheckoutBtn');
                    if (cartCheckoutBtn) {
                        cartCheckoutBtn.classList.add('disabled');
                        cartCheckoutBtn.disabled = true;
                        cartCheckoutBtn.style.pointerEvents = 'none';
                        cartCheckoutBtn.style.opacity = '0.6';
                    }

                    // Đồng bộ sidebar về empty nếu đang dùng
                    try {
                        if (typeof rebuildCartSidebarFromData === 'function') {
                            rebuildCartSidebarFromData({}, { subtotal: 0, shipping: 0, total: 0 });
                        }
                        if (typeof updateCartTotals === 'function') {
                            updateCartTotals();
                        }
                    } catch (e) {
                        console.warn('Không thể sync sidebar khi giỏ hàng trống:', e);
                    }
                }
            } else {
                showToast('Có lỗi xảy ra khi xóa sản phẩm', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Có lỗi xảy ra khi xóa sản phẩm', 'error');
        });
        
        deleteProductId = null;
    });
    
    // Hàm tính tổng tiền tạm tính (không có giảm giá) - chỉ tính các sản phẩm đã chọn
    function getSubtotal() {
        let subtotal = 0;
        const rows = document.querySelectorAll('tbody tr.cart-item-row');
        
        rows.forEach(row => {
            const checkbox = row.querySelector('.cart-product-checkbox');
            const quantityInput = row.querySelector('.cart-quantity-input');
            const priceCell = row.querySelector('.item-price');
            
            // Chỉ tính nếu sản phẩm được chọn
            if (checkbox && checkbox.checked && quantityInput && priceCell) {
                const quantity = parseInt(quantityInput.value) || 0;
                const price = parseFloat(priceCell.getAttribute('data-price')) || 0;
                subtotal += price * quantity;
            }
        });
        
        return subtotal;
    }
    
    // Hàm áp dụng mã khuyến mãi
    function applyPromoCode() {
        const promoInput = document.getElementById('promo-code-input');
        const promoCode = promoInput.value.trim().toUpperCase();
        
        if (!promoCode) {
            showToast('Vui lòng nhập mã khuyến mãi', 'warning');
            return;
        }
        
        // Kiểm tra mã TOH3
        if (promoCode === 'TOH3') {
            // Kiểm tra đơn hàng có trên 600k không
            const subtotal = getSubtotal();
            const minAmount = 600000; // 600k
            
            if (subtotal < minAmount) {
                showToast(`Mã TOH3 chỉ áp dụng cho đơn hàng từ ${formatMoney(minAmount)} trở lên. Tổng tiền hiện tại: ${formatMoney(subtotal)}`, 'warning');
                return;
            }
            
            appliedPromoCode = 'TOH3';
            discountPercent = 10;
            
            // Hiển thị thông báo đã áp dụng
            document.getElementById('promo-applied').style.display = 'block';
            document.getElementById('applied-code').textContent = 'TOH3';
            document.getElementById('promo-message').textContent = 'Mã TOH3: Giảm 10% cho đơn hàng từ 600k';
            document.getElementById('promo-message').classList.add('text-success');
            promoInput.value = '';
            promoInput.disabled = true;
            document.getElementById('apply-promo-btn').disabled = true;
            
            // Tính lại tổng tiền
            calculateTotals();
            showToast('Đã áp dụng mã khuyến mãi TOH3 thành công! Giảm 10% cho đơn hàng.', 'success');
        } else {
            showToast('Mã khuyến mãi không hợp lệ', 'error');
        }
    }
    
    // Xử lý nút áp dụng mã khuyến mãi
    document.getElementById('apply-promo-btn').addEventListener('click', applyPromoCode);
    
    // Xử lý nhấn Enter trong ô nhập mã khuyến mãi
    document.getElementById('promo-code-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            applyPromoCode();
        }
    });
    
    // Xử lý xóa mã khuyến mãi
    document.getElementById('remove-promo-btn').addEventListener('click', function() {
        appliedPromoCode = null;
        discountPercent = 0;
        
        // Ẩn thông báo đã áp dụng
        document.getElementById('promo-applied').style.display = 'none';
        document.getElementById('promo-message').textContent = 'Mã TOH3: Giảm 10% cho đơn hàng';
        document.getElementById('promo-message').classList.remove('text-success');
        document.getElementById('promo-code-input').disabled = false;
        document.getElementById('apply-promo-btn').disabled = false;
        
        // Xóa dòng giảm giá
        const discountRow = document.getElementById('discount-row');
        if (discountRow) {
            discountRow.remove();
        }
        
        // Tính lại tổng tiền
        calculateTotals();
    });
    
    // Xử lý checkbox từng sản phẩm
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('cart-product-checkbox')) {
            saveSelectedItems();
            updateSelectedCount();
            calculateTotals();
        }
    });
    
    // Logic hiện đại: Tất cả state được lưu vào database, không cần sync qua events
    
    // Khôi phục trạng thái checkbox khi load trang
    // Hàm restoreSelectedItems() sẽ tự gọi calculateTotals() sau khi restore xong
    restoreSelectedItems();
    
    // Xử lý nút "Đặt hàng" - kiểm tra có ít nhất 1 sản phẩm đã chọn
    const cartCheckoutBtn = document.getElementById('cartCheckoutBtn');
    if (cartCheckoutBtn) {
        cartCheckoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const selectedCheckboxes = document.querySelectorAll('.cart-product-checkbox:checked');
            if (selectedCheckboxes.length === 0) {
                alert('Vui lòng chọn ít nhất một sản phẩm để thanh toán');
                return;
            }
            
            // Lấy danh sách sản phẩm đã chọn
            const selectedIds = [];
            selectedCheckboxes.forEach(checkbox => {
                selectedIds.push(parseInt(checkbox.getAttribute('data-product-id')));
            });
            
            // Redirect đến checkout với danh sách sản phẩm đã chọn
            window.location.href = '{{ route("checkout") }}?selected=' + selectedIds.join(',');
        });
    }
    
    // Cập nhật trạng thái nút "Đặt hàng" dựa trên số sản phẩm đã chọn
    function updateCheckoutButton() {
        const cartCheckoutBtn = document.getElementById('cartCheckoutBtn');
        if (cartCheckoutBtn) {
            const selectedCount = document.querySelectorAll('.cart-product-checkbox:checked').length;
            
            // Disable nút nếu không có sản phẩm nào được chọn
            if (selectedCount === 0) {
                cartCheckoutBtn.classList.add('disabled');
                cartCheckoutBtn.disabled = true;
                cartCheckoutBtn.style.pointerEvents = 'none';
                cartCheckoutBtn.style.opacity = '0.6';
            } else {
                cartCheckoutBtn.classList.remove('disabled');
                cartCheckoutBtn.disabled = false;
                cartCheckoutBtn.style.pointerEvents = '';
                cartCheckoutBtn.style.opacity = '';
            }
        }
    }
    
    // Gọi updateCheckoutButton khi có thay đổi checkbox
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('cart-product-checkbox')) {
            updateCheckoutButton();
        }
    });
    
    // Gọi updateCheckoutButton sau khi tính tổng tiền
    const originalCalculateTotals = calculateTotals;
    calculateTotals = function() {
        originalCalculateTotals();
        updateCheckoutButton();
    };
    
    // Gọi lần đầu khi load trang
    setTimeout(updateCheckoutButton, 100);
});
</script>
@endpush
@endsection

