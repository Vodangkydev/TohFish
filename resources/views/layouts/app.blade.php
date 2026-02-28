<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="TOH fish - Cá lóc bông sạch, nhà cung cấp sỉ và lẻ cá sạch chất lượng cao">
    <meta name="keywords" content="cá lóc bông, cá sạch, TOH fish, thực phẩm sạch">
    <title>@yield('title', 'TOH fish - Cá lóc bông sạch')</title>
    
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/tohfish.css') }}">
    <style>
        /* Fallback styles để đảm bảo giao diện hiển thị */
        body {
            background-color: #ffffff !important;
            color: #333 !important;
            font-family: 'Roboto', sans-serif;
        }
        .container, .container-fluid {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }
        
        /* Hero Slider Styles */
        .hero-slider-section {
            position: relative;
            height: 70vh;
            min-height: 600px;
            overflow: hidden;
        }
        
        .carousel-item {
            height: 70vh;
            min-height: 600px;
        }
        
        .hero-slide-img {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: 1;
        }
        
        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0, 102, 204, 0.7) 0%, rgba(0, 168, 89, 0.6) 100%);
            z-index: 2;
        }
        
        .carousel-caption {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 3;
            text-align: center;
            width: 90%;
            max-width: 800px;
        }
        
        .hero-title {
            font-size: 3rem;
            font-weight: 700;
            color: white !important;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
            margin-bottom: 1.5rem;
        }
        
        .hero-subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.95) !important;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
        }
        
        /* Fix text colors */
        h1, h2, h3, h4, h5, h6 {
            color: #333 !important;
        }
        
        .section-title {
            color: #333 !important;
        }
        
        .product-name {
            color: #333 !important;
        }
        
        /* Carousel controls */
        .carousel-control-prev,
        .carousel-control-next {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            top: 50%;
            transform: translateY(-50%);
            opacity: 0.8;
        }
        
        .carousel-control-prev:hover,
        .carousel-control-next:hover {
            opacity: 1;
            background: rgba(255, 255, 255, 0.5);
        }
        
        .carousel-control-prev {
            left: 20px;
        }
        
        .carousel-control-next {
            right: 20px;
        }
        
        .carousel-indicators {
            bottom: 20px;
        }
        
        .carousel-indicators button {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            border: 2px solid white;
            margin: 0 5px;
        }
        
        .carousel-indicators button.active {
            background: white;
            width: 30px;
            border-radius: 6px;
        }
        
        /* Auto-play carousel */
        .carousel-fade .carousel-item {
            opacity: 0;
            transition-property: opacity;
            transform: none;
        }
        
        .carousel-fade .carousel-item.active,
        .carousel-fade .carousel-item-next.carousel-item-start,
        .carousel-fade .carousel-item-prev.carousel-item-end {
            opacity: 1;
        }
        
        .carousel-fade .active.carousel-item-start,
        .carousel-fade .active.carousel-item-end {
            opacity: 0;
        }
        
        /* Favorite Display - dùng chung cho tất cả trang (trang chủ, chi tiết, danh sách, tìm kiếm, v.v.) */
        .favorite-display {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #b0b0b0; /* trạng thái chưa yêu thích: xám nhạt */
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            padding: 4px 8px;
            border-radius: 4px;
        }
        .favorite-display:hover {
            color: #ff6b6b;
        }
        .favorite-display i {
            font-size: 16px;
            color: #bfbfbf; /* icon xám nhạt hơn nữa */
            transition: all 0.3s ease;
        }
        .favorite-display:hover i {
            color: #ff6b6b;
        }
        .favorite-display.active i {
            color: #ff6b6b;
        }
        .favorite-display.active {
            color: #ff6b6b;
        }
        .favorite-display .favorite-count {
            font-weight: 500;
            font-size: 14px;
            color: #b0b0b0;
            transition: all 0.3s ease;
        }
        .favorite-display:hover .favorite-count {
            color: #ff6b6b;
        }
        .favorite-display.active .favorite-count {
            color: #ff6b6b;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Header -->
    @include('partials.header')
    
    <!-- Navigation -->
    @include('partials.navigation')
    
    <!-- Main Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Footer -->
    @include('partials.footer')
    
    <!-- Cart Sidebar -->
    @include('partials.cart-sidebar')
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/tohfish.js') }}"></script>
    
    <script>
        // Đảm bảo carousel hoạt động và tự động chuyển
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize hero carousel với tự động chuyển
            var heroCarousel = document.getElementById('heroCarousel');
            if (heroCarousel && typeof bootstrap !== 'undefined') {
                var carousel = new bootstrap.Carousel(heroCarousel, {
                    interval: 4000, // 4 giây
                    wrap: true,
                    ride: 'carousel',
                    pause: false // Không dừng khi hover
                });
                
                // Đảm bảo tự động chuyển
                heroCarousel.addEventListener('mouseenter', function() {
                    carousel.pause();
                });
                
                heroCarousel.addEventListener('mouseleave', function() {
                    carousel.cycle();
                });
            }
            
            // Initialize other carousels
            var otherCarousels = document.querySelectorAll('.carousel:not(#heroCarousel)');
            otherCarousels.forEach(function(carousel) {
                if (typeof bootstrap !== 'undefined') {
                    new bootstrap.Carousel(carousel, {
                        interval: 5000,
                        wrap: true
                    });
                }
            });
        });
    </script>
    
    @stack('scripts')
    
    <script>
        // Xử lý hover vào ảnh phụ để đổi ảnh chính (dùng chung cho tất cả trang)
        document.addEventListener('DOMContentLoaded', function() {
            const subThumbs = document.querySelectorAll('.product-sub-thumb');
            
            subThumbs.forEach(function(thumb) {
                const productId = thumb.getAttribute('data-product-id');
                const subImageSrc = thumb.getAttribute('data-image-src');
                const mainImage = document.querySelector(`.product-main-image[data-product-id="${productId}"]`);
                
                if (mainImage) {
                    const originalSrc = mainImage.getAttribute('data-original-src');
                    
                    // Khi hover vào ảnh phụ
                    thumb.addEventListener('mouseenter', function() {
                        mainImage.src = subImageSrc;
                    });
                    
                    // Khi rời khỏi ảnh phụ
                    thumb.addEventListener('mouseleave', function() {
                        mainImage.src = originalSrc;
                    });
                }
            });
        });
    </script>
    
    <script>
        // Toggle Favorite cho trang chi tiết và trang chủ
        function toggleFavoriteDetail(productId, element) {
            @auth
            // Validate productId
            if (!productId || productId === '' || isNaN(productId) || productId <= 0) {
                console.error('Invalid product ID:', productId);
                alert('ID sản phẩm không hợp lệ.');
                return;
            }
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                alert('Không tìm thấy CSRF token. Vui lòng reload trang.');
                return;
            }
            
            const favoriteUrl = '{{ url("/yeu-thich") }}/' + productId;
            fetch(favoriteUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({}),
                credentials: 'same-origin'
            })
            .then(response => {
                // Kiểm tra status code
                if (response.status === 419) {
                    throw new Error('Session hết hạn. Vui lòng reload trang và thử lại.');
                }
                if (response.status === 401 || response.status === 403) {
                    throw new Error('Vui lòng đăng nhập để sử dụng chức năng này.');
                }
                
                // Kiểm tra Content-Type
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    // Nếu không phải JSON, thử đọc text để debug
                    return response.text().then(text => {
                        console.error('Server trả về không phải JSON:', text.substring(0, 200));
                        throw new Error('Lỗi kết nối với server. Vui lòng reload trang và thử lại.');
                    });
                }
            })
            .then(data => {
                if (!data || !data.success) {
                    throw new Error(data?.message || 'Có lỗi xảy ra');
                }
                return data;
            })
            .then(data => {
                if (data.success) {
                    const icon = element.querySelector('i');
                    const countSpan = element.querySelector('.favorite-count');
                    
                    if (data.is_favorite) {
                        element.classList.add('active');
                        if (icon) {
                            icon.classList.remove('far');
                            icon.classList.add('fas');
                        }
                        element.title = 'Bỏ yêu thích';
                    } else {
                        element.classList.remove('active');
                        if (icon) {
                            icon.classList.remove('fas');
                            icon.classList.add('far');
                        }
                        element.title = 'Thêm vào yêu thích';
                    }
                    
                    // Cập nhật số lượng
                    if (countSpan && data.favorite_count !== undefined) {
                        countSpan.textContent = data.favorite_count;
                    }
                    
                    // Show notification
                    if (typeof showNotification === 'function') {
                        showNotification(data.message, 'success');
                    }
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi cập nhật yêu thích: ' + error.message);
            });
            @else
            window.location.href = '{{ route("login") }}';
            @endauth
        }

        // Load favorite status for products on page load
        document.addEventListener('DOMContentLoaded', function() {
            @auth
            const favoriteDisplays = document.querySelectorAll('.favorite-display[data-product-id]');
            favoriteDisplays.forEach(element => {
                const productId = element.getAttribute('data-product-id');
                if (productId && productId !== '' && !isNaN(productId)) {
                    fetch(`{{ request()->getBasePath() }}/yeu-thich/check/${productId}`)
                        .then(response => {
                            const contentType = response.headers.get('content-type');
                            if (contentType && contentType.includes('application/json')) {
                                return response.json();
                            } else {
                                return response.text().then(text => {
                                    console.error('Server trả về không phải JSON:', text.substring(0, 200));
                                    throw new Error('Invalid response format');
                                });
                            }
                        })
                        .then(data => {
                            if (data && data.is_favorite) {
                                element.classList.add('active');
                                element.title = 'Bỏ yêu thích';
                                const icon = element.querySelector('i');
                                if (icon) {
                                    icon.classList.remove('far');
                                    icon.classList.add('fas');
                                }
                            }
                        })
                        .catch(error => {
                            // Chỉ log error, không hiển thị alert để tránh làm phiền user
                            if (error.message !== 'Invalid response format') {
                                console.error('Error checking favorite:', error);
                            }
                        });
                }
            });
            @endauth

            // Đồng bộ quantity input với hidden input trong form
            const quantityInputs = document.querySelectorAll('.quantity-input');
            quantityInputs.forEach(input => {
                // Tìm form cùng cấp với input
                const productInfo = input.closest('.product-info');
                const form = productInfo ? productInfo.querySelector('form[action*="cart.add"]') : null;
                
                if (form) {
                    const hiddenQuantity = form.querySelector('.quantity-hidden-input');
                    
                    // Cập nhật khi thay đổi số lượng
                    input.addEventListener('change', function() {
                        const quantity = parseInt(this.value) || 1;
                        if (this.value < 1) {
                            this.value = 1;
                        }
                        if (hiddenQuantity) {
                            hiddenQuantity.value = parseInt(this.value) || 1;
                        }
                    });

                    // Cập nhật khi form submit
                    form.addEventListener('submit', function(e) {
                        if (hiddenQuantity) {
                            hiddenQuantity.value = parseInt(input.value) || 1;
                        }
                    });
                }
            });
        });
    </script>
    
    <script>
        // Mở sidebar cart khi click icon cart trong header - Refresh dữ liệu từ API
        document.addEventListener('DOMContentLoaded', function() {
            const openCartBtn = document.getElementById('openCartSidebarBtn');
            if (openCartBtn) {
                openCartBtn.addEventListener('click', function() {
                    const cartSidebar = document.getElementById('cartSidebar');
                    const cartSidebarOverlay = document.getElementById('cartSidebarOverlay');
                    if (cartSidebar) {
                        cartSidebar.classList.add('active');
                        cartSidebarOverlay.style.display = 'block';
                        document.body.style.overflow = 'hidden';
                        
                        // Refresh dữ liệu từ API khi mở sidebar
                        if (typeof restoreSelectedItems === 'function') {
                            restoreSelectedItems();
                        }
                        // Cập nhật tổng tiền NGAY LẬP TỨC (không delay)
                        if (typeof updateCartTotals === 'function') {
                            updateCartTotals();
                        }
                    }
                });
            }
            
            // Xử lý form thêm vào giỏ hàng - Ngăn redirect và mở sidebar
            // Sử dụng event delegation để xử lý tất cả form, kể cả form được thêm động sau này
            document.addEventListener('submit', function(e) {
                const form = e.target;
                // Kiểm tra form có class add-to-cart-form hoặc action chứa "cart.add" hoặc route name
                const formAction = form.getAttribute('action') || form.action || '';
                const isCartAddForm = form.classList.contains('add-to-cart-form') || 
                                     formAction.includes('cart.add') || 
                                     formAction.includes('gio-hang/them');
                
                if (form.tagName === 'FORM' && isCartAddForm) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    
                    if (!csrfToken) {
                        console.error('CSRF token not found');
                        alert('Lỗi: Không tìm thấy CSRF token');
                        return false;
                    }
                    
                    // Lấy giá trị trực tiếp từ các input để đảm bảo không bị mất
                    const productIdInput = form.querySelector('input[name="product_id"]');
                    const nameInput = form.querySelector('input[name="name"]');
                    const priceInput = form.querySelector('input[name="price"]');
                    const imageInput = form.querySelector('input[name="image"]');
                    const quantityInput = form.querySelector('input[name="quantity"]') || form.querySelector('.quantity-input');
                    
                    if (!productIdInput || !nameInput || !priceInput) {
                        console.error('Missing required form fields');
                        alert('Lỗi: Form không hợp lệ. Vui lòng reload trang và thử lại.');
                        return false;
                    }
                    
                    const productId = productIdInput.value;
                    const name = nameInput.value;
                    const price = priceInput.value;
                    
                    // Validate
                    if (!productId || productId === '' || isNaN(productId) || parseInt(productId) <= 0) {
                        console.error('Invalid product_id:', productId);
                        alert('Lỗi: ID sản phẩm không hợp lệ. Vui lòng reload trang và thử lại.');
                        return false;
                    }
                    
                    if (!name || name === '') {
                        console.error('Missing name field');
                        alert('Lỗi: Tên sản phẩm không hợp lệ.');
                        return false;
                    }
                    
                    if (!price || price === '' || isNaN(price) || parseFloat(price) < 0) {
                        console.error('Invalid price:', price);
                        alert('Lỗi: Giá sản phẩm không hợp lệ.');
                        return false;
                    }
                    
                    // Tạo FormData và set các giá trị
                    const formData = new FormData(form);
                    
                    // Đảm bảo các field được set đúng (override nếu cần)
                    formData.set('product_id', productId);
                    formData.set('name', name);
                    formData.set('price', price);
                    
                    // Set quantity nếu có
                    let quantity = '1';
                    if (quantityInput) {
                        quantity = quantityInput.value || '1';
                        if (quantityInput.classList && quantityInput.classList.contains('quantity-input')) {
                            // Nếu là quantity-input, cập nhật hidden input nếu có
                            const hiddenQuantity = form.querySelector('.quantity-hidden-input');
                            if (hiddenQuantity) {
                                hiddenQuantity.value = quantity;
                            }
                        }
                        formData.set('quantity', quantity);
                    }
                    
                    // Set image nếu có
                    if (imageInput && imageInput.value) {
                        formData.set('image', imageInput.value);
                    }

                    // -----------------------------
                    // Cập nhật tiền NGAY LẬP TỨC trên UI (optimistic update)
                    // để người dùng không phải đợi request xong mới thấy tổng tiền đổi.
                    // Server response sau đó vẫn sẽ override lại số chính xác (nếu có khuyến mãi / phí ship).
                    // -----------------------------
                    try {
                        const qtyNumber   = parseInt(quantity, 10) || 1;
                        const priceNumber = parseFloat(price) || 0;
                        const delta       = qtyNumber > 0 && priceNumber > 0 ? qtyNumber * priceNumber : 0;
                        
                        if (delta > 0) {
                            const subtotalEl     = document.getElementById('cartSubtotal');
                            const totalEl        = document.getElementById('cartTotal');
                            const totalButtonEl  = document.getElementById('cartTotalButton');
                            const cartCountBadge = document.querySelector('.cart-count');

                            const parseMoneyText = (el) => {
                                if (!el) return 0;
                                const txt = el.textContent || el.innerText || '';
                                const num = parseInt(txt.replace(/[^0-9]/g, ''), 10);
                                return isNaN(num) ? 0 : num;
                            };

                            const newSubtotal = parseMoneyText(subtotalEl) + delta;
                            const newTotal    = parseMoneyText(totalEl) + delta;

                            if (typeof formatPrice === 'function') {
                                if (subtotalEl)    subtotalEl.textContent    = formatPrice(newSubtotal);
                                if (totalEl)       totalEl.textContent       = formatPrice(newTotal);
                            } else {
                                const fmt = (v) => new Intl.NumberFormat('vi-VN').format(v) + '₫';
                                if (subtotalEl)    subtotalEl.textContent    = fmt(newSubtotal);
                                if (totalEl)       totalEl.textContent       = fmt(newTotal);
                            }
                            if (totalButtonEl) {
                                totalButtonEl.textContent = new Intl.NumberFormat('vi-VN').format(newTotal);
                            }
                            if (cartCountBadge) {
                                const currentCount = parseInt(cartCountBadge.textContent || '0', 10) || 0;
                                cartCountBadge.textContent = currentCount + qtyNumber;
                            }

                            // Nếu có hàm updateCartTotals (đã tối ưu), gọi lại để đồng bộ các chỗ khác (trang /gio-hang,...)
                            if (typeof updateCartTotals === 'function') {
                                updateCartTotals();
                            }
                        }
                    } catch (instantErr) {
                        console.warn('Không thể cập nhật tiền ngay lập tức, sẽ dùng số từ server:', instantErr);
                    }
                    
                    // Disable button để tránh double submit
                    const submitButton = form.querySelector('button[type="submit"]');
                    if (submitButton) {
                        submitButton.disabled = true;
                        const originalText = submitButton.innerHTML;
                        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang thêm...';
                        
                            // Re-enable sớm để UX không bị chậm (500ms là đủ tránh double click)
                            setTimeout(() => {
                                submitButton.disabled = false;
                                submitButton.innerHTML = originalText;
                            }, 500);
                    }
                    
                    // Lấy URL từ form action
                    let actionUrl = form.action || form.getAttribute('action');
                    if (!actionUrl) {
                        console.error('Form action not found');
                        return false;
                    }
                    
                    // Đảm bảo URL là absolute nếu cần
                    // Tin tưởng action đã được Laravel sinh đúng (kể cả khi app chạy dưới /Tohfish)
                    if (actionUrl.startsWith('/')) {
                        actionUrl = window.location.origin + actionUrl;
                    }
                    
                    // Khi dùng FormData, không set Content-Type header - browser sẽ tự động set với boundary
                    fetch(actionUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                            // Không set Content-Type khi dùng FormData
                        },
                        body: formData,
                        credentials: 'same-origin'
                    })
                    .then(response => {
                        // Kiểm tra status code
                        if (response.status === 419) {
                            throw new Error('Session hết hạn. Vui lòng reload trang và thử lại.');
                        }
                        if (response.status === 422) {
                            // Validation error
                            return response.json().then(errors => {
                                const errorMessages = [];
                                if (errors.errors) {
                                    Object.keys(errors.errors).forEach(key => {
                                        errorMessages.push(errors.errors[key].join(', '));
                                    });
                                }
                                throw new Error(errorMessages.length > 0 ? errorMessages.join('\n') : 'Dữ liệu không hợp lệ.');
                            });
                        }
                        
                        // Kiểm tra content-type
                        const contentType = response.headers.get('content-type');
                        if (contentType && contentType.includes('application/json')) {
                            return response.json();
                        } else {
                            // Nếu không phải JSON, thử đọc text để debug
                            return response.text().then(text => {
                                console.error('Server trả về không phải JSON:', text.substring(0, 200));
                                throw new Error('Server trả về phản hồi không hợp lệ. Status: ' + response.status);
                            });
                        }
                    })
                    .then(data => {
                        if (data && data.success) {
                            // Cập nhật số lượng giỏ hàng trong header
                            const cartCountBadge = document.querySelector('.cart-count');
                            if (cartCountBadge) {
                                cartCountBadge.textContent = data.cart_count || 0;
                            }
                            
                            // Rebuild sidebar với data mới từ server
                            if (typeof rebuildCartSidebarFromData === 'function') {
                                rebuildCartSidebarFromData(data.items, data.totals);
                                
                                // Cập nhật tổng tiền NGAY LẬP TỨC từ server response (không cần đợi DOM)
                                if (data.totals && typeof updateTotalsFromServer === 'function') {
                                    updateTotalsFromServer(data.totals);
                                }
                                
                                // Đảm bảo checkbox được tích ngay sau khi rebuild (chỉ sản phẩm mới thêm)
                                // Sử dụng requestAnimationFrame để đảm bảo DOM đã được render
                                requestAnimationFrame(() => {
                                    // Chỉ tích checkbox của sản phẩm vừa thêm (đã có is_selected = true từ server)
                                    const newCheckbox = document.querySelector(`.sidebar-product-checkbox[data-product-id="${productId}"]`);
                                    if (newCheckbox) {
                                        // Đảm bảo checkbox được tích nếu is_selected = true
                                        if (!newCheckbox.checked) {
                                            newCheckbox.checked = true;
                                        }
                                    }
                                    
                                    // Cập nhật UI
                                    if (typeof updateSelectedCount === 'function') {
                                        updateSelectedCount();
                                    }
                                    
                            // Tính lại tổng tiền từ DOM để đảm bảo chính xác (1 lần, không delay)
                            if (typeof updateCartTotals === 'function') {
                                updateCartTotals();
                            }
                                });
                            }
                            
                            // Mở sidebar
                            const cartSidebar = document.getElementById('cartSidebar');
                            const cartSidebarOverlay = document.getElementById('cartSidebarOverlay');
                            if (cartSidebar) {
                                cartSidebar.classList.add('active');
                                if (cartSidebarOverlay) {
                                    cartSidebarOverlay.style.display = 'block';
                                }
                                document.body.style.overflow = 'hidden';
                            }
                        } else {
                            alert(data?.message || 'Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng');
                        }
                    })
                    .catch(error => {
                        console.error('Error adding to cart:', error);
                        alert('Có lỗi xảy ra khi thêm sản phẩm vào giỏ hàng. Vui lòng thử lại.\n' + error.message);
                    });
                    
                    return false;
                }
            });
            
            // Logic hiện đại: Giỏ hàng được lưu trong database, không cần khôi phục từ localStorage
            // Khi user quay lại, giỏ hàng sẽ tự động được load từ database qua CartService
        });
    </script>
</body>
</html>

