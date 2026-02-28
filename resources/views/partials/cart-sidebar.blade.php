@php
    // Sử dụng CartService để lấy dữ liệu đúng (từ database nếu đã login, từ session nếu guest)
    $cartService = app(\App\Services\CartService::class);
    $cartItems = $cartService->all();
    
    // Tính tổng tiền từ CartService
    $totals = $cartService->totals();
    $subtotal = $totals['subtotal'];
    $shipping = $totals['shipping'];
    $total = $totals['total'];
@endphp

<!-- Cart Sidebar Overlay -->
<div class="cart-sidebar-overlay" id="cartSidebarOverlay" style="display: none;"></div>

<!-- Cart Sidebar -->
<div class="cart-sidebar" id="cartSidebar">
    <div class="cart-sidebar-header">
        <h5 class="mb-0">GIỎ HÀNG</h5>
        <div class="d-flex align-items-center gap-2">
            @if(count($cartItems) > 0)
            <button type="button" class="btn btn-sm btn-outline-danger" id="clearAllCartBtn" title="Xóa tất cả">
                <i class="fas fa-trash-alt"></i>
            </button>
            @endif
            <button type="button" class="btn-close" id="closeCartSidebar" aria-label="Close"></button>
        </div>
    </div>
    
    
    <div class="cart-sidebar-body" id="cartSidebarBody">
        @if(count($cartItems) > 0)
            <div class="cart-items-list">
                @foreach($cartItems as $item)
                <div class="cart-item" data-product-id="{{ $item['id'] }}">
                    <div class="cart-item-image">
                        @if(!empty($item['image']))
                            <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}">
                        @else
                            <img src="{{ asset('images/home/1.png') }}" alt="{{ $item['name'] }}">
                        @endif
                    </div>
                    <div class="cart-item-info">
                        <h6 class="cart-item-name">{{ $item['name'] }}</h6>
                        <p class="cart-item-price" data-price="{{ $item['price'] }}">{{ number_format($item['price'], 0, ',', '.') }}₫</p>
                        <div class="cart-item-actions">
                            <div class="quantity-control">
                                <button type="button" class="btn-quantity btn-minus" data-product-id="{{ $item['id'] }}">-</button>
                                <input type="number" class="quantity-input-sidebar" value="{{ $item['quantity'] }}" min="1" data-product-id="{{ $item['id'] }}">
                                <button type="button" class="btn-quantity btn-plus" data-product-id="{{ $item['id'] }}">+</button>
                            </div>
                            <button type="button" class="btn-remove-item" data-product-id="{{ $item['id'] }}">
                                <i class="fas fa-trash"></i> Xóa
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="cart-empty">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <p class="text-muted">Giỏ hàng trống</p>
            </div>
        @endif
    </div>
    
    <div class="cart-sidebar-footer">
        <div class="cart-totals">
            <div class="cart-total-row">
                <span>Tạm tính:</span>
                <span id="cartSubtotal">{{ number_format($subtotal, 0, ',', '.') }}₫</span>
            </div>
            <div class="cart-total-row">
                <span>Phí vận chuyển:</span>
                <span id="cartShipping">{{ number_format($shipping, 0, ',', '.') }}₫</span>
            </div>
            <div class="cart-total-row cart-total-final">
                <strong>Tổng tiền:</strong>
                <strong id="cartTotal" class="text-danger">{{ number_format($total, 0, ',', '.') }}₫</strong>
            </div>
        </div>
        <a href="{{ request()->getBasePath() }}{{ route('cart', [], false) }}" class="btn btn-primary w-100 mb-2">
            Xem giỏ hàng
        </a>
        <button type="button" class="btn btn-success w-100 {{ count($cartItems) == 0 ? 'disabled' : '' }}" id="checkoutBtn" {{ count($cartItems) == 0 ? 'disabled' : '' }}>
            THANH TOÁN: <span id="cartTotalButton">{{ number_format($total, 0, ',', '.') }}</span>₫
        </button>
    </div>
</div>

<!-- Confirm Modal (Bootstrap) -->
<div class="modal fade" id="cartConfirmModal" tabindex="-1" aria-labelledby="cartConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cartConfirmModalLabel">Xác nhận</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="cartConfirmModalMessage">
                Bạn có chắc chắn?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="cartConfirmModalOkBtn">Xóa</button>
            </div>
        </div>
    </div>
</div>

<style>
    .cart-sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040;
        transition: opacity 0.3s ease;
    }
    
    .cart-sidebar {
        position: fixed;
        top: 0;
        right: -450px;
        width: 450px;
        max-width: 90vw;
        height: 100vh;
        background: #fff;
        box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
        z-index: 1050;
        display: flex;
        flex-direction: column;
        transition: right 0.3s ease;
        overflow: hidden;
    }
    
    .cart-sidebar.active {
        right: 0;
    }
    
    .cart-sidebar-header {
        padding: 20px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fff;
    }
    
    #clearAllCartBtn {
        padding: 5px 10px;
        font-size: 14px;
    }
    
    #clearAllCartBtn:hover {
        background-color: #dc3545;
        color: #fff;
        border-color: #dc3545;
    }

    /* Confirm modal: show near top instead of vertically centered */
    #cartConfirmModal .modal-dialog {
        margin-top: 8vh;
    }

    @media (max-width: 768px) {
        #cartConfirmModal .modal-dialog {
            margin-top: 4vh;
        }
    }
    
    .cart-sidebar-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
    }
    
    .cart-item {
        display: flex;
        gap: 15px;
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
        align-items: flex-start;
    }
    
    .cart-item-image {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
    }
    
    .cart-item-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }
    
    .cart-item-info {
        flex: 1;
    }
    
    .cart-item-name {
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 5px;
        color: #333;
    }
    
    .cart-item-price {
        font-size: 16px;
        font-weight: 600;
        color: #0066cc;
        margin-bottom: 10px;
    }
    
    .cart-item-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
    }
    
    .quantity-control {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    .btn-quantity {
        width: 30px;
        height: 30px;
        border: 1px solid #ddd;
        background: #fff;
        border-radius: 4px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-quantity:hover {
        background: #f0f0f0;
    }
    
    .quantity-input-sidebar {
        width: 50px;
        text-align: center;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 5px;
    }
    
    .btn-remove-item {
        background: none;
        border: none;
        color: #dc3545;
        font-size: 12px;
        cursor: pointer;
        padding: 5px 10px;
    }
    
    .btn-remove-item:hover {
        color: #c82333;
    }
    
    .cart-empty {
        text-align: center;
        padding: 60px 20px;
    }
    
    .cart-sidebar-footer {
        padding: 20px;
        border-top: 1px solid #e0e0e0;
        background: #fff;
    }
    
    .cart-totals {
        margin-bottom: 15px;
    }
    
    .cart-total-row {
        display: flex;
        justify-content: space-between;
        padding: 8px 0;
        font-size: 14px;
    }
    
    .cart-total-final {
        border-top: 1px solid #e0e0e0;
        padding-top: 15px;
        margin-top: 10px;
        font-size: 18px;
    }
    
    @media (max-width: 768px) {
        .cart-sidebar {
            width: 100%;
            max-width: 100vw;
        }
    }
</style>

<script>
// Helper function để format giá
function formatPrice(price) {
    return new Intl.NumberFormat('vi-VN').format(price) + '₫';
}

// Confirm modal helper (Bootstrap 5). Fallback to window.confirm if Bootstrap isn't available.
function showCartConfirm(message, onConfirm) {
    const modalEl = document.getElementById('cartConfirmModal');
    const msgEl = document.getElementById('cartConfirmModalMessage');
    const okBtn = document.getElementById('cartConfirmModalOkBtn');

    if (!modalEl || !msgEl || !okBtn || typeof bootstrap === 'undefined') {
        if (window.confirm(message)) onConfirm();
        return;
    }

    msgEl.textContent = message;

    // Replace handler each time to avoid stacking listeners
    const newOkBtn = okBtn.cloneNode(true);
    okBtn.parentNode.replaceChild(newOkBtn, okBtn);

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    newOkBtn.addEventListener('click', () => {
        modal.hide();
        onConfirm();
    });

    modal.show();
}

// Logic hiện đại: Tất cả state được lưu vào database qua API, không dùng localStorage hay window events

// Base path để chạy đúng khi app nằm trong subfolder (vd: /Tohfish)
// route(..., false) trả về path (vd: /gio-hang) thay vì absolute URL.
const CART_BASE_PATH = '{{ request()->getBasePath() }}';
const CART_ROUTE_INDEX = '{{ route('cart', [], false) }}';
const CART_ROUTE_UPDATE = '{{ route('cart.update', [], false) }}';
const CART_ROUTE_REMOVE = '{{ route('cart.remove', [], false) }}';
const CART_ROUTE_CLEAR_ALL = '{{ route('cart.clear_all', [], false) }}';

function withBasePath(path) {
    const base = (CART_BASE_PATH || '').replace(/\/+$/, '');
    const p = (path || '').startsWith('/') ? path : `/${path || ''}`;
    return `${base}${p}`;
}

// Debounce timer cần dùng ở nhiều nơi (kể cả ngoài DOMContentLoaded trong rebuildCartSidebarFromData)
let updateQuantityTimeout = null;
let pendingUpdate = null;

document.addEventListener('DOMContentLoaded', function() {
    
    const cartSidebar = document.getElementById('cartSidebar');
    const cartSidebarOverlay = document.getElementById('cartSidebarOverlay');
    const closeCartSidebar = document.getElementById('closeCartSidebar');
    
    function openCartSidebar() {
        cartSidebar.classList.add('active');
        cartSidebarOverlay.style.display = 'block';
        document.body.style.overflow = 'hidden';
        
        // Khôi phục trạng thái giỏ hàng khi mở sidebar - Lấy từ API /Tohfish/gio-hang
        restoreSelectedItems();
        
        // Cập nhật UI ngay lập tức (không delay, không requestAnimationFrame)
        if (typeof updateCartTotals === 'function') {
            updateCartTotals();
        }
    }
    
    function closeCartSidebarFunc() {
        cartSidebar.classList.remove('active');
        cartSidebarOverlay.style.display = 'none';
        document.body.style.overflow = '';
    }
    
    // Đóng sidebar khi click overlay
    cartSidebarOverlay.addEventListener('click', closeCartSidebarFunc);
    
    // Đóng sidebar khi click nút đóng
    closeCartSidebar.addEventListener('click', closeCartSidebarFunc);
    
    // Không còn cần lưu trạng thái checkbox nữa vì đã xóa checkbox
    
    // Hàm cập nhật tổng tiền từ server response
    function updateTotalsFromServer(totals) {
        const subtotalEl = document.getElementById('cartSubtotal');
        const totalEl = document.getElementById('cartTotal');
        const totalButtonEl = document.getElementById('cartTotalButton');
        
        if (subtotalEl && totals.subtotal !== undefined) {
            subtotalEl.textContent = formatPrice(totals.subtotal);
        }
        if (totalEl && totals.total !== undefined) {
            totalEl.textContent = formatPrice(totals.total);
        }
        if (totalButtonEl && totals.total !== undefined) {
            totalButtonEl.textContent = new Intl.NumberFormat('vi-VN').format(totals.total);
        }
    }
    
    // Hàm khôi phục trạng thái giỏ hàng - Lấy từ API /Tohfish/gio-hang
    function restoreSelectedItems() {
        // Cập nhật tổng tiền ngay lập tức từ DOM
        if (typeof updateCartTotals === 'function') {
            updateCartTotals();
        }
        
        // Gọi API để lấy dữ liệu giỏ hàng (AJAX JSON) từ route giỏ hàng
        fetch(withBasePath(CART_ROUTE_INDEX), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                // Cập nhật dữ liệu giỏ hàng từ API
                if (data.items && data.totals) {
                    // Rebuild sidebar với dữ liệu mới từ API
                    if (typeof rebuildCartSidebarFromData === 'function') {
                        rebuildCartSidebarFromData(data.items, data.totals);
                    }
                    // Cập nhật tổng tiền từ server
                    updateTotalsFromServer(data.totals);
                    // Cập nhật số lượng trong header
                    if (data.cart_count !== undefined) {
                        updateCartCount(data.cart_count);
                    }
                } else if (data.totals) {
                    // Chỉ có totals, cập nhật tổng tiền
                    updateTotalsFromServer(data.totals);
                }
            } else {
                // Fallback: tính lại từ client
                if (typeof updateCartTotals === 'function') {
                    updateCartTotals();
                }
            }
        })
        .catch(error => {
            console.error('Error fetching cart data:', error);
            // Nếu có lỗi, vẫn tính từ client để đảm bảo UI luôn hiển thị
            if (typeof updateCartTotals === 'function') {
                updateCartTotals();
            }
        });
    }
    
    // Hàm cập nhật số lượng sản phẩm trong header - Đồng bộ với API
    function updateCartCount(totalQuantity) {
        if (totalQuantity === undefined) {
            // Tính tổng số lượng nếu không được truyền vào
            totalQuantity = 0;
            const quantityInputs = document.querySelectorAll('.quantity-input-sidebar');
            quantityInputs.forEach(input => {
                totalQuantity += parseInt(input.value) || 0;
            });
        }
        
        const cartCountBadge = document.querySelector('.cart-count');
        if (cartCountBadge) {
            cartCountBadge.textContent = totalQuantity;
        }
        
        // Đồng bộ số lượng với trang cart chính nếu đang ở trang đó
        if (window.location.pathname.includes('gio-hang')) {
            const cartSelectedCount = document.getElementById('cartSelectedCount');
            if (cartSelectedCount) {
                // Tính số sản phẩm đã chọn từ sidebar
                const selectedItems = document.querySelectorAll('.cart-item');
                cartSelectedCount.textContent = selectedItems.length;
            }
        }
    }
    
    
    // Cache DOM elements để tránh query lại nhiều lần
    let cachedElements = {
        subtotalEl: null,
        totalEl: null,
        totalButtonEl: null,
        checkoutBtn: null,
        cartCountBadge: null
    };
    
    // Hàm lấy cached elements (chỉ query một lần)
    function getCachedElements() {
        if (!cachedElements.subtotalEl) {
            cachedElements.subtotalEl = document.getElementById('cartSubtotal');
            cachedElements.totalEl = document.getElementById('cartTotal');
            cachedElements.totalButtonEl = document.getElementById('cartTotalButton');
            cachedElements.checkoutBtn = document.getElementById('checkoutBtn');
            cachedElements.cartCountBadge = document.querySelector('.cart-count');
        }
        return cachedElements;
    }

    // Expose ra global để các script ngoài (nếu có) sử dụng mà không lỗi ReferenceError
    window.getCachedElements = getCachedElements;
    
    // Hàm tính tổng tiền cho tất cả sản phẩm trong giỏ hàng - TỐI ƯU TỐC ĐỘ
    // Đảm bảo tính toán CHÍNH XÁC: giá × số lượng
    function updateCartTotals() {
        let subtotal = 0;
        let totalQuantity = 0;
        const cartItems = document.querySelectorAll('.cart-item');
        
        // Dùng for loop thay vì forEach để nhanh hơn
        for (let i = 0; i < cartItems.length; i++) {
            const cartItem = cartItems[i];
            const quantityInput = cartItem.querySelector('.quantity-input-sidebar');
            const priceEl = cartItem.querySelector('.cart-item-price');
            
            if (quantityInput && priceEl) {
                // Lấy số lượng từ input (đảm bảo là số nguyên)
                const quantity = parseInt(quantityInput.value) || 0;
                if (quantity > 0) {
                    totalQuantity += quantity;
                }
                
                // Ưu tiên lấy từ data attribute (chính xác nhất và nhanh nhất)
                let price = parseFloat(priceEl.getAttribute('data-price'));
                
                // Nếu không có data-price, parse từ text (chỉ một lần)
                if (!price || isNaN(price) || price <= 0) {
                    const priceText = priceEl.textContent || priceEl.innerText || '';
                    const priceNumber = priceText.replace(/[^0-9]/g, '');
                    price = parseFloat(priceNumber) || 0;
                    
                    // Lưu lại vào data-price để lần sau không cần parse lại
                    if (price > 0) {
                        priceEl.setAttribute('data-price', price);
                    }
                }
                
                // Tính tổng: giá × số lượng
                if (price > 0 && quantity > 0) {
                    subtotal += price * quantity;
                }
            }
        }
        
        const shipping = 0;
        const total = subtotal + shipping;
        
        // Cập nhật UI - sử dụng cached elements
        const elements = getCachedElements();
        
        if (elements.subtotalEl) {
            elements.subtotalEl.textContent = formatPrice(subtotal);
        }
        if (elements.totalEl) {
            elements.totalEl.textContent = formatPrice(total);
        }
        if (elements.totalButtonEl) {
            elements.totalButtonEl.textContent = new Intl.NumberFormat('vi-VN').format(total);
        }
        
        // Cập nhật số lượng trong header
        if (elements.cartCountBadge) {
            elements.cartCountBadge.textContent = totalQuantity;
        }
        
        // Đồng bộ số tiền với trang cart chính nếu đang ở trang đó
        if (window.location.pathname.includes('gio-hang')) {
            const cartSubtotalMain = document.getElementById('cart-subtotal');
            const cartTotalMain = document.getElementById('cart-total');
            if (cartSubtotalMain) {
                cartSubtotalMain.textContent = formatPrice(subtotal);
            }
            if (cartTotalMain) {
                cartTotalMain.textContent = formatPrice(total);
            }
        }
        
        // Cập nhật nút thanh toán
        if (elements.checkoutBtn) {
            if (totalQuantity === 0) {
                elements.checkoutBtn.classList.add('disabled');
                elements.checkoutBtn.disabled = true;
                elements.checkoutBtn.style.pointerEvents = 'none';
                elements.checkoutBtn.style.opacity = '0.6';
            } else {
                elements.checkoutBtn.classList.remove('disabled');
                elements.checkoutBtn.disabled = false;
                elements.checkoutBtn.style.pointerEvents = '';
                elements.checkoutBtn.style.opacity = '';
            }
        }
    }
    
    
    // Không còn checkbox nữa, không cần xử lý checkbox
    
    // Xử lý nút thanh toán - gửi tất cả sản phẩm trong giỏ hàng
    const checkoutBtn = document.getElementById('checkoutBtn');
    if (checkoutBtn) {
        checkoutBtn.addEventListener('click', function(e) {
            // Kiểm tra nếu nút bị disabled thì không làm gì
            if (this.disabled || this.classList.contains('disabled')) {
                e.preventDefault();
                return false;
            }
            
            e.preventDefault();
            
            // Lấy tất cả sản phẩm trong giỏ hàng
            const cartItems = document.querySelectorAll('.cart-item');
            if (cartItems.length === 0) {
                alert('Giỏ hàng trống. Vui lòng thêm sản phẩm vào giỏ hàng.');
                return false;
            }
            
            const productIds = [];
            cartItems.forEach(item => {
                const productId = parseInt(item.getAttribute('data-product-id'));
                if (productId) {
                    productIds.push(productId);
                }
            });
            
            // Redirect đến checkout với tất cả sản phẩm
            window.location.href = '{{ route("checkout") }}?selected=' + productIds.join(',');
        });
    }
    
    // Debounce cho refresh API để tránh gọi quá nhiều lần
    let refreshTimeout = null;
    let isRefreshing = false;
    
    // Hàm refresh dữ liệu giỏ hàng từ API /Tohfish/gio-hang - TỐI ƯU
    function refreshCartFromAPI(force = false) {
        // Nếu đang refresh thì không gọi lại
        if (isRefreshing && !force) {
            return;
        }
        
        // Debounce: chỉ refresh sau 300ms nếu không force
        if (!force) {
            if (refreshTimeout) {
                clearTimeout(refreshTimeout);
            }
            refreshTimeout = setTimeout(() => {
                refreshCartFromAPI(true);
            }, 300);
            return;
        }
        
        isRefreshing = true;
        
        fetch(withBasePath(CART_ROUTE_INDEX), {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data && data.success) {
                // Cập nhật dữ liệu giỏ hàng từ API
                if (data.items && data.totals) {
                    // Rebuild sidebar với dữ liệu mới từ API
                    if (typeof rebuildCartSidebarFromData === 'function') {
                        rebuildCartSidebarFromData(data.items, data.totals);
                    }
                    // Cập nhật tổng tiền từ server
                    if (typeof updateTotalsFromServer === 'function') {
                        updateTotalsFromServer(data.totals);
                    }
                    // Cập nhật số lượng trong header
                    if (data.cart_count !== undefined) {
                        const badge = getCachedElements().cartCountBadge;
                        if (badge) {
                            badge.textContent = data.cart_count;
                        }
                    }
                }
            }
            isRefreshing = false;
        })
        .catch(error => {
            console.error('Error refreshing cart from API:', error);
            isRefreshing = false;
            // Nếu có lỗi, tính lại từ client
            if (typeof updateCartTotals === 'function') {
                updateCartTotals();
            }
        });
    }
    
    // Khôi phục trạng thái khi load trang (từ database) - Refresh từ API
    // Đảm bảo dữ liệu luôn được load từ API khi trang được reset/refresh
    restoreSelectedItems();
    
    // Refresh dữ liệu từ API khi trang được load hoặc focus lại (debounced)
    window.addEventListener('focus', function() {
        // Khi người dùng quay lại tab, refresh dữ liệu (debounced)
        if (document.getElementById('cartSidebar')?.classList.contains('active')) {
            refreshCartFromAPI();
        }
    });
    
    // Refresh dữ liệu khi visibility change (tab được hiển thị lại) - debounced
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && document.getElementById('cartSidebar')?.classList.contains('active')) {
            refreshCartFromAPI();
        }
    });

    // Debounce timer đã khai báo global phía trên để dùng được cả trong rebuildCartSidebarFromData
    
    // Update cart sidebar khi quantity input thay đổi - Cập nhật NGAY LẬP TỨC UI, debounce API
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input-sidebar')) {
            const productId = e.target.getAttribute('data-product-id');
            if (!productId) return;

            let quantity = parseInt(e.target.value) || 1;
            if (quantity < 1) {
                quantity = 1;
                e.target.value = 1;
            }

            // Cập nhật tổng tiền NGAY LẬP TỨC với số lượng mới
            if (typeof updateCartTotals === 'function') {
                updateCartTotals();
            }

            // Nếu đang ở trang /gio-hang, đồng bộ luôn ô số lượng + tổng tiền bên trái
            if (window.location.pathname.includes('gio-hang')) {
                const mainInput = document.querySelector(`.cart-quantity-input[data-product-id="${productId}"]`);
                if (mainInput) {
                    mainInput.value = quantity;
                }
                if (typeof calculateTotals === 'function') {
                    calculateTotals();
                }
            }

            // Không debounce nữa: gửi request cập nhật ngay lập tức
            if (updateQuantityTimeout) {
                clearTimeout(updateQuantityTimeout);
                updateQuantityTimeout = null;
            }
            pendingUpdate = null;

            updateCartQuantity(productId, quantity);
        }
    });
    
    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('quantity-input-sidebar')) {
            const productId = e.target.getAttribute('data-product-id');
            if (!productId) return;

            let quantity = parseInt(e.target.value) || 1;
            if (quantity < 1) {
                quantity = 1;
                e.target.value = 1;
            }

            // Clear timeout và gọi ngay khi change (người dùng đã xong nhập)
            if (updateQuantityTimeout) {
                clearTimeout(updateQuantityTimeout);
                updateQuantityTimeout = null;
            }
            
            // Cập nhật tổng tiền NGAY LẬP TỨC
            if (typeof updateCartTotals === 'function') {
                updateCartTotals();
            }

            if (window.location.pathname.includes('gio-hang')) {
                const mainInput = document.querySelector(`.cart-quantity-input[data-product-id="${productId}"]`);
                if (mainInput) {
                    mainInput.value = quantity;
                }
                if (typeof calculateTotals === 'function') {
                    calculateTotals();
                }
            }

            // Gửi request để cập nhật số lượng vào server ngay lập tức
            updateCartQuantity(productId, quantity);
        }
    });

    // Xử lý nút xóa tất cả
    const clearAllBtn = document.getElementById('clearAllCartBtn');
    if (clearAllBtn) {
        clearAllBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            showCartConfirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm khỏi giỏ hàng?', () => clearAllCart());
        });
    }
});

        // Rebuild sidebar từ data - Function global để có thể gọi từ app.blade.php
function rebuildCartSidebarFromData(items, totals) {
        const cartBody = document.getElementById('cartSidebarBody');
        if (!cartBody) return;
        
        // Kiểm tra và disable/enable nút thanh toán
        const checkoutBtn = document.getElementById('checkoutBtn');
        const hasItems = items && Object.keys(items).length > 0;
        
        if (!items || Object.keys(items).length === 0) {
            cartBody.innerHTML = `
                <div class="cart-empty">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Giỏ hàng trống</p>
                </div>
            `;
            // Ẩn nút xóa tất cả
            const clearAllBtn = document.getElementById('clearAllCartBtn');
            if (clearAllBtn) {
                clearAllBtn.style.display = 'none';
            }
            
            // Disable nút thanh toán khi giỏ hàng trống
            if (checkoutBtn) {
                checkoutBtn.classList.add('disabled');
                checkoutBtn.disabled = true;
                checkoutBtn.style.pointerEvents = 'none';
                checkoutBtn.style.opacity = '0.6';
            }
        } else {
            let html = '<div class="cart-items-list">';
            
            Object.values(items).forEach(item => {
                // Đảm bảo sản phẩm vừa thêm sẽ được tích chọn (is_selected = true)
                // Mặc định tích nếu không có giá trị hoặc giá trị là true
                const isChecked = (item.is_selected === undefined || item.is_selected === true || item.is_selected === null);
                html += `
                    <div class="cart-item" data-product-id="${item.id}">
                        <div class="cart-item-image">
                            <img src="${item.image || '{{ asset("images/home/1.png") }}'}" alt="${item.name}">
                        </div>
                        <div class="cart-item-info">
                            <h6 class="cart-item-name">${item.name}</h6>
                            <p class="cart-item-price" data-price="${parseFloat(item.price) || 0}">${formatPrice(item.price)}</p>
                            <div class="cart-item-actions">
                                <div class="quantity-control">
                                    <button type="button" class="btn-quantity btn-minus" data-product-id="${item.id}">-</button>
                                    <input type="number" class="quantity-input-sidebar" value="${item.quantity}" min="1" data-product-id="${item.id}">
                                    <button type="button" class="btn-quantity btn-plus" data-product-id="${item.id}">+</button>
                                </div>
                                <button type="button" class="btn-remove-item" data-product-id="${item.id}" title="Xóa sản phẩm">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            });
            html += '</div>';
            cartBody.innerHTML = html;
            
            // Hiển thị nút xóa tất cả nếu có sản phẩm
            const clearAllBtn = document.getElementById('clearAllCartBtn');
            if (clearAllBtn) {
                clearAllBtn.style.display = 'block';
            }
            
            // Enable nút thanh toán khi có sản phẩm
            if (checkoutBtn) {
                checkoutBtn.classList.remove('disabled');
                checkoutBtn.disabled = false;
                checkoutBtn.style.pointerEvents = '';
                checkoutBtn.style.opacity = '';
            }
            
            // Không còn cần hiển thị số lượng sản phẩm đã chọn nữa
        }
        
        // Cập nhật giá tiền NGAY LẬP TỨC từ server (không cần đợi DOM)
        if (totals && typeof updateTotalsFromServer === 'function') {
            updateTotalsFromServer(totals);
        }
        
        // Tính lại tổng tiền từ DOM sau khi rebuild (chỉ một lần, không delay)
        if (typeof updateCartTotals === 'function') {
            updateCartTotals();
        }
    }
    
    // Xử lý quantity buttons trong sidebar - Cập nhật NGAY LẬP TỨC UI, gọi API ngay
    document.addEventListener('click', function(e) {
        // Xử lý nút + và -: mô phỏng đúng logic "mũi tên lên/xuống" của input number
        // (sử dụng stepUp/stepDown), sau đó phát sự kiện input để gom toàn bộ xử lý
        if (e.target.classList.contains('btn-plus') || e.target.classList.contains('btn-minus')) {
            e.preventDefault();
            e.stopPropagation();

            const productId = e.target.getAttribute('data-product-id');
            if (!productId) return;

            const input = document.querySelector(`.quantity-input-sidebar[data-product-id="${productId}"]`);
            if (!input) return;

            // Dùng API native của input number để tôn trọng min/max/step
            if (e.target.classList.contains('btn-plus')) {
                if (typeof input.stepUp === 'function') {
                    input.stepUp();
                } else {
                    input.value = (parseInt(input.value) || 0) + 1;
                }
            } else {
                if (typeof input.stepDown === 'function') {
                    input.stepDown();
                } else {
                    input.value = Math.max(1, (parseInt(input.value) || 1) - 1);
                }
            }

            // Kích hoạt lại handler 'input' để xử lý đồng bộ (UI + API + bên trái)
            const evt = new Event('input', { bubbles: true });
            input.dispatchEvent(evt);
        }
        
        // Xử lý nút xóa sản phẩm
        if (e.target.classList.contains('btn-remove-item') || e.target.closest('.btn-remove-item')) {
            e.preventDefault();
            e.stopPropagation();
            const btn = e.target.classList.contains('btn-remove-item') ? e.target : e.target.closest('.btn-remove-item');
            if (!btn) return;
            
            const productId = btn.getAttribute('data-product-id');
            if (!productId) return;

            showCartConfirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?', () => removeFromCart(productId));
        }
        
        // Xử lý icon trong nút xóa
        if (e.target.classList.contains('fa-trash') && e.target.closest('.btn-remove-item')) {
            e.preventDefault();
            e.stopPropagation();
            const btn = e.target.closest('.btn-remove-item');
            if (!btn) return;
            
            const productId = btn.getAttribute('data-product-id');
            if (!productId) return;

            showCartConfirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?', () => removeFromCart(productId));
        }
    });
    
    // Update quantity - TỐI ƯU: Chỉ cập nhật từ server response, không refresh lại
    function updateCartQuantity(productId, quantity) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(withBasePath(CART_ROUTE_UPDATE), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                items: [{
                    product_id: parseInt(productId),
                    quantity: quantity
                }]
            })
        })
        .then(response => {
            if (response.status === 419) {
                throw new Error('Session/CSRF hết hạn (419). Vui lòng F5 lại trang.');
            }
            if (!response.ok) {
                return response.text().then(t => {
                    console.error('updateCartQuantity non-OK:', response.status, t?.substring?.(0, 300));
                    throw new Error('Cập nhật số lượng thất bại. Status: ' + response.status);
                });
            }
            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                return response.text().then(t => {
                    console.error('updateCartQuantity non-JSON:', t?.substring?.(0, 300));
                    throw new Error('Server trả về không phải JSON khi cập nhật số lượng.');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Chỉ cập nhật tổng tiền trong sidebar từ server response (đã tính sẵn)
                if (data.totals && typeof updateTotalsFromServer === 'function') {
                    updateTotalsFromServer(data.totals);
                }

                // Cập nhật số lượng trong header nếu có
                if (data.cart_count !== undefined) {
                    const badge = getCachedElements().cartCountBadge;
                    if (badge) {
                        badge.textContent = data.cart_count;
                    }
                }
                
                // Đồng bộ với trang cart chính nếu đang ở đó:
                // dùng lại calculateTotals() để logic bên trái (chọn/bỏ chọn, mã giảm giá) tự tính.
                if (window.location.pathname.includes('gio-hang') && typeof calculateTotals === 'function') {
                    calculateTotals();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Nếu có lỗi, tính lại từ client
            if (typeof updateCartTotals === 'function') {
                updateCartTotals();
            }
        });
    }
    
    // Remove from cart - Đồng bộ với API
    function removeFromCart(productId) {
        // Cập nhật giá tiền NGAY LẬP TỨC trước khi xóa (tính lại không có sản phẩm này)
        const cartItem = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
        if (cartItem) {
            // Xóa item khỏi DOM ngay lập tức để updateCartTotals tính đúng
            cartItem.remove();
            // Cập nhật giá tiền ngay lập tức
            if (typeof updateCartTotals === 'function') {
                updateCartTotals();
            }

            // Nếu đang ở trang giỏ hàng chính, xóa luôn dòng bên trái và tính lại
            if (window.location.pathname.includes('gio-hang')) {
                const mainRowBtn = document.querySelector(`.cart-remove-btn[data-product-id="${productId}"]`);
                if (mainRowBtn) {
                    const row = mainRowBtn.closest('tr');
                    if (row) {
                        row.remove();
                    }
                }
                if (typeof calculateTotals === 'function') {
                    calculateTotals();
                }
            }
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(withBasePath(CART_ROUTE_REMOVE), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                product_id: parseInt(productId)
            })
        })
        .then(response => {
            if (response.status === 419) {
                throw new Error('Session/CSRF hết hạn (419). Vui lòng F5 lại trang.');
            }
            if (!response.ok) {
                return response.text().then(t => {
                    console.error('removeFromCart non-OK:', response.status, t?.substring?.(0, 300));
                    throw new Error('Xóa sản phẩm thất bại. Status: ' + response.status);
                });
            }
            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                return response.text().then(t => {
                    console.error('removeFromCart non-JSON:', t?.substring?.(0, 300));
                    throw new Error('Server trả về không phải JSON khi xóa sản phẩm.');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Cập nhật UI với data mới từ server
                updateCartDisplay(data);
                
                // Cập nhật lại tổng tiền từ server để đảm bảo chính xác
                if (data.totals && typeof updateTotalsFromServer === 'function') {
                    updateTotalsFromServer(data.totals);
                } else if (typeof updateCartTotals === 'function') {
                    updateCartTotals();
                }
            } else {
                // Nếu có lỗi, vẫn cập nhật từ client
                if (typeof updateCartTotals === 'function') {
                    updateCartTotals();
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Nếu có lỗi, vẫn cập nhật từ client
            if (typeof updateCartTotals === 'function') {
                updateCartTotals();
            }
            alert(error?.message || 'Có lỗi xảy ra khi xóa sản phẩm.');
        });
    }
    
    // Update cart display - Đồng bộ với API và trang cart chính
    function updateCartDisplay(data) {
        // Update cart count in header - sử dụng cart_count từ server hoặc tính từ items
        const cartCountBadge = document.querySelector('.cart-count');
        if (cartCountBadge) {
            if (data.cart_count !== undefined) {
                cartCountBadge.textContent = data.cart_count;
            } else if (data.items) {
                // Tính tổng quantity từ items
                let totalQuantity = 0;
                Object.values(data.items).forEach(item => {
                    totalQuantity += item.quantity || 0;
                });
                cartCountBadge.textContent = totalQuantity;
            }
        }
        
        // Cập nhật tổng tiền NGAY LẬP TỨC từ server response (trước khi rebuild)
        if (data.totals && typeof updateTotalsFromServer === 'function') {
            updateTotalsFromServer(data.totals);
            
            // Đồng bộ số tiền với trang cart chính nếu đang ở trang đó
            if (window.location.pathname.includes('gio-hang')) {
                const cartSubtotalMain = document.getElementById('cart-subtotal');
                const cartShippingMain = document.getElementById('cart-shipping');
                const cartTotalMain = document.getElementById('cart-total');
                if (cartSubtotalMain) {
                    cartSubtotalMain.textContent = formatPrice(data.totals.subtotal || 0);
                }
                if (cartShippingMain) {
                    cartShippingMain.textContent = formatPrice(data.totals.shipping || 0);
                }
                if (cartTotalMain) {
                    cartTotalMain.textContent = formatPrice(data.totals.total || 0);
                }
                
                // Gọi hàm tính lại tổng tiền trong trang cart nếu có
                if (typeof calculateTotals === 'function') {
                    calculateTotals();
                }
            }
        }
        
        // Rebuild sidebar với data mới
        rebuildCartSidebarFromData(data.items, data.totals);
        
        // Reload sidebar if empty
        if (data.cart_count == 0) {
            const cartBody = document.getElementById('cartSidebarBody');
            if (cartBody) {
                cartBody.innerHTML = `
                    <div class="cart-empty">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Giỏ hàng trống</p>
                    </div>
                `;
            }
            
            // Ẩn nút xóa tất cả
            const clearAllBtn = document.getElementById('clearAllCartBtn');
            if (clearAllBtn) {
                clearAllBtn.style.display = 'none';
            }
            
            // Disable checkout button khi giỏ hàng trống
            const checkoutBtn = document.getElementById('checkoutBtn');
            if (checkoutBtn) {
                checkoutBtn.classList.add('disabled');
                checkoutBtn.disabled = true;
                checkoutBtn.style.pointerEvents = 'none';
                checkoutBtn.style.opacity = '0.6';
            }
        } else {
            // Hiển thị nút xóa tất cả
            const clearAllBtn = document.getElementById('clearAllCartBtn');
            if (clearAllBtn) {
                clearAllBtn.style.display = 'block';
            }
            
            // Enable checkout button khi có sản phẩm
            const checkoutBtn = document.getElementById('checkoutBtn');
            if (checkoutBtn) {
                checkoutBtn.classList.remove('disabled');
                checkoutBtn.disabled = false;
                checkoutBtn.style.pointerEvents = '';
                checkoutBtn.style.opacity = '';
            }
        }
    }

    // Function để xóa tất cả sản phẩm - Đồng bộ với API
    function clearAllCart() {
        // Cập nhật giá tiền về 0 NGAY LẬP TỨC
        const subtotalEl = document.getElementById('cartSubtotal');
        const totalEl = document.getElementById('cartTotal');
        const totalButtonEl = document.getElementById('cartTotalButton');
        
        if (subtotalEl) subtotalEl.textContent = formatPrice(0);
        if (totalEl) totalEl.textContent = formatPrice(0);
        if (totalButtonEl) totalButtonEl.textContent = new Intl.NumberFormat('vi-VN').format(0);
        
        // Xóa tất cả items khỏi DOM ngay lập tức
        const cartBody = document.getElementById('cartSidebarBody');
        if (cartBody) {
            cartBody.innerHTML = `
                <div class="cart-empty">
                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Giỏ hàng trống</p>
                </div>
            `;
        }
        
        // Ẩn nút xóa tất cả
        const clearAllBtn = document.getElementById('clearAllCartBtn');
        if (clearAllBtn) {
            clearAllBtn.style.display = 'none';
        }
        
        // Disable nút thanh toán
        const checkoutBtn = document.getElementById('checkoutBtn');
        if (checkoutBtn) {
            checkoutBtn.classList.add('disabled');
            checkoutBtn.disabled = true;
            checkoutBtn.style.pointerEvents = 'none';
            checkoutBtn.style.opacity = '0.6';
        }
        
        // Cập nhật cart count về 0
        const cartCountBadge = document.querySelector('.cart-count');
        if (cartCountBadge) {
            cartCountBadge.textContent = '0';
        }
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            alert('Lỗi: Không tìm thấy CSRF token');
            return;
        }

        fetch(withBasePath(CART_ROUTE_CLEAR_ALL), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            credentials: 'same-origin'
        })
        .then(response => {
            if (response.status === 419) {
                throw new Error('Session/CSRF hết hạn (419). Vui lòng F5 lại trang.');
            }
            if (!response.ok) {
                return response.text().then(t => {
                    console.error('clearAllCart non-OK:', response.status, t?.substring?.(0, 300));
                    throw new Error('Xóa giỏ hàng thất bại. Status: ' + response.status);
                });
            }
            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                return response.text().then(t => {
                    console.error('clearAllCart non-JSON:', t?.substring?.(0, 300));
                    throw new Error('Server trả về không phải JSON khi xóa giỏ hàng.');
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                // Cập nhật UI từ server response
                if (data.totals && typeof updateTotalsFromServer === 'function') {
                    updateTotalsFromServer(data.totals);
                }
                updateCartDisplay(data);
            }
        })
        .catch(error => {
            console.error('Error clearing cart:', error);
            alert(error?.message || 'Có lỗi xảy ra khi xóa giỏ hàng.');
            // Vẫn giữ UI đã cập nhật ở trên
        });
    }
</script>


