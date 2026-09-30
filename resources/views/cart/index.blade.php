@extends('layouts.app')

@section('title', 'Shopping Cart | ' . config('app.name', 'NovaStore'))

@section('content')
<div class="cart-page">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="breadcrumb-nav" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="breadcrumb-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Home
            </a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">Shopping Cart</span>
        </nav>

        <div class="cart-header-row">
            <h1 class="cart-title">Your Shopping Cart</h1>
            @if(count($cart) > 0)
                <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear your entire cart?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline btn-sm clear-cart-btn">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="3 6 5 6 21 6"></polyline>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                        </svg>
                        Clear Cart
                    </button>
                </form>
            @endif
        </div>

        <!-- Flash Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if(count($cart) > 0)
            <div class="cart-grid">
                <!-- Cart Items Table Section -->
                <div class="cart-items-wrapper">
                    <div class="cart-table-card">
                        <table class="cart-table" aria-label="Shopping Cart Items">
                            <thead>
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Subtotal</th>
                                    <th scope="col"><span class="sr-only">Actions</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($cart as $id => $item)
                                    <tr class="cart-row">
                                        <!-- Product info -->
                                        <td class="cart-product-col" data-label="Product">
                                            <div class="cart-product-item">
                                                <a href="{{ isset($item['slug']) ? route('products.show', $item['slug']) : url('/products') }}" class="cart-thumb">
                                                    @if(!empty($item['image']))
                                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                                    @else
                                                        <div class="cart-thumb-placeholder">
                                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                                <polyline points="21 15 16 10 5 21"></polyline>
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </a>
                                                <div class="cart-product-meta">
                                                    <a href="{{ isset($item['slug']) ? route('products.show', $item['slug']) : url('/products') }}" class="cart-product-name">
                                                        {{ $item['name'] }}
                                                    </a>
                                                    <span class="cart-product-stock-hint">Max available: {{ $item['stock'] }}</span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Unit Price -->
                                        <td class="cart-price-col" data-label="Price">
                                            <span class="cart-price">₹{{ number_format($item['price'], 2) }}</span>
                                        </td>

                                        <!-- Quantity update form -->
                                        <td class="cart-qty-col" data-label="Quantity">
                                            <form action="{{ route('cart.update', $id) }}" method="POST" class="cart-qty-form">
                                                @csrf
                                                @method('PATCH')
                                                <div class="cart-qty-picker">
                                                    <input 
                                                        type="number" 
                                                        name="quantity" 
                                                        value="{{ $item['quantity'] }}" 
                                                        min="1" 
                                                        max="{{ $item['stock'] }}" 
                                                        class="cart-qty-input"
                                                        aria-label="Quantity for {{ $item['name'] }}"
                                                        onchange="this.form.submit()"
                                                        required
                                                    >
                                                </div>
                                            </form>
                                        </td>

                                        <!-- Subtotal -->
                                        <td class="cart-subtotal-col" data-label="Subtotal">
                                            <span class="cart-subtotal">₹{{ number_format($item['subtotal'], 2) }}</span>
                                        </td>

                                        <!-- Remove item -->
                                        <td class="cart-action-col" data-label="Remove">
                                            <form action="{{ route('cart.remove', $id) }}" method="POST" onsubmit="return confirm('Remove {{ addslashes($item['name']) }} from your cart?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="cart-remove-btn" title="Remove Item" aria-label="Remove {{ $item['name'] }}">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                        <line x1="18" y1="6" x2="6" y2="18"></line>
                                                        <line x1="6" y1="6" x2="18" y2="18"></line>
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="cart-footer-actions">
                        <a href="{{ route('home') }}" class="btn btn-outline">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            Continue Shopping
                        </a>
                    </div>
                </div>

                <!-- Order Summary Sidebar -->
                <div class="cart-summary-wrapper">
                    <div class="cart-summary-card">
                        <h2 class="cart-summary-title">Order Summary</h2>

                        <div class="summary-line">
                            <span class="summary-label">Subtotal</span>
                            <span class="summary-value">₹{{ number_format($total, 2) }}</span>
                        </div>

                        <div class="summary-line">
                            <span class="summary-label">Estimated Shipping</span>
                            <span class="summary-value text-free">FREE</span>
                        </div>

                        <div class="summary-line">
                            <span class="summary-label">Tax (GST)</span>
                            <span class="summary-value">₹0.00</span>
                        </div>

                        <div class="summary-divider"></div>

                        <div class="summary-total-line">
                            <span class="total-label">Total</span>
                            <span class="total-value">₹{{ number_format($total, 2) }}</span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="btn btn-primary btn-lg btn-block checkout-btn">
                            Proceed to Checkout
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </a>

                        <div class="cart-trust-badges">
                            <div class="trust-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                </svg>
                                <span>SSL Encrypted Checkout</span>
                            </div>
                            <div class="trust-item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="23 4 23 10 17 10"></polyline>
                                    <polyline points="1 20 1 14 7 14"></polyline>
                                    <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                                </svg>
                                <span>30-Day Money Back Guarantee</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Empty Cart State -->
            <div class="empty-cart-card">
                <div class="empty-cart-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                </div>
                <h2 class="empty-cart-title">Your Cart is Currently Empty</h2>
                <p class="empty-cart-desc">
                    Looks like you haven't added anything to your cart yet. Discover our wide selection of quality products and find something you love.
                </p>
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                    Start Shopping Now
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
