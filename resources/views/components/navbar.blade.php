<header class="site-header">
    <div class="container">
        <nav class="navbar" aria-label="Main Navigation">
            <a href="{{ url('/') }}" class="navbar-brand">
                <div class="brand-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <path d="M16 10a4 4 0 0 1-8 0"></path>
                    </svg>
                </div>
                <div class="brand-name">Madhan<span>Store</span></div>
            </a>

            <ul class="nav-menu" id="navMenu">
                <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                    <a href="{{ url('/') }}">Home</a>
                </li>
                <li class="nav-item {{ request()->is('categories*') ? 'active' : '' }}">
                    <a href="{{ url('/categories') }}">Categories</a>
                </li>
                <li class="nav-item {{ request()->is('products*') ? 'active' : '' }}">
                    <a href="{{ url('/products') }}">Products</a>
                </li>
            </ul>

            <div class="nav-actions">
                @php
                    $cartSession = session()->get('cart', []);
                    $cartCount = 0;
                    if (is_array($cartSession)) {
                        foreach ($cartSession as $cItem) {
                            $cartCount += isset($cItem['quantity']) ? (int)$cItem['quantity'] : 0;
                        }
                    }
                @endphp
                <a href="{{ route('cart.index') }}" class="cart-button" aria-label="Shopping Cart">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="cart-badge">{{ $cartCount }}</span>
                </a>

                <div class="auth-links">
                    @auth
                        <div class="user-nav-dropdown">
                            <a href="{{ route('account.index') }}" class="user-greeting-btn" title="My Account">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <a href="{{ route('orders.index') }}" class="btn btn-outline btn-sm">My Orders</a>
                            <form action="{{ route('logout') }}" method="POST" class="nav-logout-form">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-logout" title="Sign Out">Logout</button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
                    @endauth
                </div>

                <button class="mobile-toggle" id="mobileMenuBtn" aria-label="Toggle Navigation Menu" aria-expanded="false">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
            </div>
        </nav>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggleBtn = document.getElementById('mobileMenuBtn');
        const navMenu = document.getElementById('navMenu');

        if (toggleBtn && navMenu) {
            toggleBtn.addEventListener('click', function () {
                const isActive = navMenu.classList.toggle('is-active');
                toggleBtn.setAttribute('aria-expanded', isActive);
            });
        }
    });
</script>
