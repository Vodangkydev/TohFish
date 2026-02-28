<header class="toh-header">
    <div class="container-fluid">
        <div class="row align-items-center py-2">
            <div class="col-md-4 col-6">
                <div class="header-logo">
                    <a href="{{ route('home') }}">
                        <img src="{{ url('images/home/logo.png') }}" alt="TOH fish" class="logo-img">
                    </a>
                </div>
            </div>
            <div class="col-8 col-md-8 d-flex justify-content-end align-items-center gap-2 flex-wrap ms-auto">
                @php
                    $sessionName = session('user_name');
                    $sessionEmail = session('user_email');
                    $loginUrl = Route::has('login') ? route('login') : url('/login');
                    $registerUrl = Route::has('register') ? route('register') : url('/register');
                    $profileUrl = Route::has('profile') ? route('profile') : '#';

                    // Sử dụng CartService để lấy dữ liệu đúng (từ database nếu đã login, từ session nếu guest)
                    $cartService = app(\App\Services\CartService::class);
                    $cartItems = $cartService->all();
                    $cartCount = collect($cartItems)->sum('quantity');
                    $basePath = request()->getBasePath();

                @endphp

                {{-- Search Form --}}
                <div class="header-search me-3">
                    <form action="{{ route('products.search') }}" method="GET" class="search-form">
                        <div class="search-input-wrapper">
                            <input type="text" 
                                   name="q" 
                                   class="search-input" 
                                   placeholder="Tìm kiếm sản phẩm..." 
                                   value="{{ request('q') }}"
                                   autocomplete="off">
                            <button type="submit" class="search-btn">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <div class="header-actions me-1">
                    <a href="javascript:void(0)" class="cart-icon position-relative" id="openCartSidebarBtn">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="cart-count badge bg-primary rounded-pill position-absolute top-0 start-100 translate-middle">
                            {{ $cartCount }}
                        </span>
                    </a>
                </div>

                {{-- Dropdown cho icon người dùng --}}
                <div class="dropdown header-user">
                    @auth
                        <button class="btn auth-btn auth-btn-icon d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                            <span class="user-name">{{ Auth::user()->name ?? 'User' }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" style="min-width: 300px;">
                            <li><h6 class="dropdown-header">Xin chào, {{ Auth::user()->name ?? 'User' }}</h6></li>
                            <li><a class="dropdown-item" href="{{ $profileUrl }}"><i class="fas fa-user me-2"></i> Hồ sơ</a></li>
                            @if(Route::has('orders.index'))
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-shopping-bag me-2"></i> Lịch sử đơn hàng</a></li>
                            @endif
                            @if(Route::has('favorites.index'))
                            <li><a class="dropdown-item" href="{{ route('favorites.index') }}"><i class="fas fa-heart me-2"></i> Sản phẩm yêu thích</a></li>
                            @endif
                            @if(Route::has('logout'))
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Đăng xuất
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                            @endif
                        </ul>
                    @elseif($sessionName || $sessionEmail)
                        <button class="btn auth-btn auth-btn-icon d-flex align-items-center gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user"></i>
                            <span class="user-name">{{ $sessionName ?? $sessionEmail }}</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Xin chào, {{ $sessionName ?? $sessionEmail }}</h6></li>
                            <li><a class="dropdown-item" href="{{ $profileUrl }}"><i class="fas fa-user me-2"></i> Hồ sơ</a></li>
                            @if(Route::has('orders.index'))
                            <li><a class="dropdown-item" href="{{ route('orders.index') }}"><i class="fas fa-shopping-bag me-2"></i> Lịch sử đơn hàng</a></li>
                            @endif
                            @if(Route::has('favorites.index'))
                            <li><a class="dropdown-item" href="{{ route('favorites.index') }}"><i class="fas fa-heart me-2"></i> Sản phẩm yêu thích</a></li>
                            @endif
                            <li>
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form-session').submit();">
                                    Đăng xuất
                                </a>
                                <form id="logout-form-session" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    @else
                        {{-- Khi chưa đăng nhập: click icon đi thẳng đến trang đăng nhập --}}
                        <a href="{{ $loginUrl }}" class="btn auth-btn auth-btn-icon" title="Đăng nhập">
                            <i class="fas fa-user"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>

