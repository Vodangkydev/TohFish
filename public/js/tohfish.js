// TOH Fish JavaScript

// Add to cart function
function addToCart(productId) {
    // Legacy fallback: older templates called addToCart(productId).
    // Our cart is server-side (Laravel session) and should be added via POST forms to route('cart.add').
    console.warn('addToCart(productId) is deprecated. Use POST form to cart.add instead.', productId);
    showNotification('Vui lòng dùng nút thêm vào giỏ (form) để lưu giỏ hàng.');
}

// View product function
function viewProduct(productId) {
    // TODO: Implement product detail view
    console.log('View product:', productId);
}

$(document).ready(function() {
    // Active navigation item
    var currentUrl = window.location.href;
    
    $('.nav-link').each(function() {
        var link = $(this).attr('href');
        if (currentUrl.includes(link) && link !== '/') {
            $(this).addClass('active');
        }
    });
    
    // If on home page
    if (currentUrl === window.location.origin + '/' || currentUrl.includes('home')) {
        $('.nav-link[href="/"]').addClass('active');
    }
    
    // Smooth scroll
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if( target.length ) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 1000);
        }
    });
    
    // IMPORTANT: Do NOT hijack "Thêm vào giỏ" buttons.
    // Cart add/update/remove are handled by real POST forms (Laravel session).
    
    // Newsletter form
    $('.newsletter-form').on('submit', function(e) {
        e.preventDefault();
        var email = $(this).find('input[type="email"]').val();
        if (email) {
            showNotification('Cảm ơn bạn đã đăng ký nhận tin!');
            $(this).find('input[type="email"]').val('');
        }
    });
    
    // Product overlay buttons
    $('.product-overlay .btn').on('click', function(e) {
        e.stopPropagation();
    });
});

// Notification function
function showNotification(message) {
    var notification = $('<div class="notification">' + message + '</div>');
    $('body').append(notification);
    
    notification.css({
        position: 'fixed',
        top: '20px',
        right: '20px',
        background: '#28a745',
        color: 'white',
        padding: '15px 20px',
        borderRadius: '5px',
        zIndex: '10000',
        boxShadow: '0 3px 10px rgba(0,0,0,0.2)'
    });
    
    setTimeout(function() {
        notification.fadeOut(function() {
            $(this).remove();
        });
    }, 3000);
}

// Lazy load images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                if (img.dataset.src) {
                    img.src = img.dataset.src;
                    img.removeAttribute('data-src');
                    imageObserver.unobserve(img);
                }
            }
        });
    });
    
    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

