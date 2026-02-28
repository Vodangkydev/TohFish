<nav class="navbar navbar-expand-lg navbar-light toh-navbar">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="sanphamDropdown" role="button" data-bs-toggle="dropdown">
                        Sản Phẩm
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('products.soche') }}">Sản Phẩm Sơ Chế</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.chebien') }}">Sản Phẩm Chế Biến</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.chebiensan') }}">Sản Phẩm Chế Biến Sẵn</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('products.khac') }}">SẢN PHẨM KHÁC</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.bunca') }}">BÚN CÁ TOH</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.raugiavi') }}">RAU GIA VỊ</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('order-guide') }}">Cách Đặt Hàng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">Giới Thiệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('contact') }}">Liên hệ</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="blogDropdown" role="button" data-bs-toggle="dropdown">
                        TOH Blog
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('blog.congthuc') }}">Công Thức Món Cá</a></li>
                        <li><a class="dropdown-item" href="{{ route('blog.monngon') }}">Công Thức Món Ngon</a></li>
                        <li><a class="dropdown-item" href="{{ route('blog.tanman') }}">Tản Mạn Cùng TOH fish</a></li>
                        <li><a class="dropdown-item" href="{{ route('blog.farm') }}">TOH Farm - Nông Trại</a></li>
                        <li><a class="dropdown-item" href="{{ route('blog.tour') }}">TOH Tour - Tham Quan</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('promotion') }}">Khuyến Mãi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('recruitment') }}">Tuyển Dụng</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

