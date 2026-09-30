@extends('layouts.app')

@section('title', 'Checkout | ' . config('app.name', 'NovaStore'))

@section('content')
<div class="checkout-page py-12">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="breadcrumbs" aria-label="Breadcrumbs">
            <a href="{{ url('/') }}" class="breadcrumb-link">Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('cart.index') }}" class="breadcrumb-link">Cart</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">Checkout</span>
        </nav>

        <div class="checkout-header">
            <h1 class="page-title">Secure Checkout</h1>
            <p class="page-subtitle">Please provide your delivery details to complete your order.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" role="alert">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="8" x2="12" y2="12"></line>
                    <line x1="12" y1="16" x2="12.01" y2="16"></line>
                </svg>
                <div>
                    <strong>Please correct the errors below:</strong>
                    <ul class="error-list">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <form action="{{ route('checkout.store') }}" method="POST" id="checkoutForm" class="checkout-layout">
            @csrf

            <!-- LEFT: Customer & Shipping Information -->
            <div class="checkout-main">
                <div class="checkout-section-card">
                    <div class="card-header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                        <h2>Customer Information</h2>
                    </div>

                    <div class="form-grid">
                        <div class="form-group full-width">
                            <label for="name" class="form-label">Full Name <span class="required">*</span></label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                class="form-control @error('name') is-invalid @enderror" 
                                value="{{ old('name') }}" 
                                placeholder="e.g. Ramesh Kumar"
                                required
                                maxlength="255"
                            >
                            @error('name')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group half-width">
                            <label for="email" class="form-label">Email Address <span class="required">*</span></label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                value="{{ old('email') }}" 
                                placeholder="ramesh@example.com"
                                required
                                maxlength="255"
                            >
                            @error('email')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group half-width">
                            <label for="phone" class="form-label">Phone Number <span class="required">*</span></label>
                            <input 
                                type="tel" 
                                name="phone" 
                                id="phone" 
                                class="form-control @error('phone') is-invalid @enderror" 
                                value="{{ old('phone') }}" 
                                placeholder="+91 98765 43210"
                                required
                                maxlength="20"
                            >
                            @error('phone')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group full-width">
                            <label for="address" class="form-label">Shipping / Delivery Address <span class="required">*</span></label>
                            <textarea 
                                name="address" 
                                id="address" 
                                rows="3" 
                                class="form-control @error('address') is-invalid @enderror" 
                                placeholder="House / Flat No., Street Name, Area, Landmark, City, State, Pincode"
                                required
                            >{{ old('address') }}</textarea>
                            @error('address')
                                <span class="field-error">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="checkout-section-card">
                    <div class="card-header-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
                            <line x1="1" y1="10" x2="23" y2="10"></line>
                        </svg>
                        <h2>Payment Method</h2>
                    </div>

                    <div class="payment-method-badge">
                        <div class="payment-radio-group">
                            <input type="radio" id="cod" name="payment_method" value="cod" checked>
                            <label for="cod" class="payment-radio-label">
                                <span class="payment-title">Cash on Delivery / UPI on Delivery</span>
                                <span class="payment-desc">Pay with cash or scan QR upon delivery at your doorstep</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Order Summary Sidebar -->
            <div class="checkout-sidebar">
                <div class="checkout-summary-card">
                    <h2 class="summary-card-title">Order Summary</h2>

                    <!-- Items List -->
                    <div class="checkout-items-list">
                        @foreach($cart as $id => $item)
                            <div class="checkout-item">
                                <div class="checkout-item-thumb">
                                    @if(!empty($item['image']))
                                        <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                    @else
                                        <div class="checkout-item-placeholder">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                <polyline points="21 15 16 10 5 21"></polyline>
                                            </svg>
                                        </div>
                                    @endif
                                    <span class="checkout-item-qty-badge">{{ $item['quantity'] }}</span>
                                </div>
                                <div class="checkout-item-details">
                                    <h4 class="checkout-item-name">{{ $item['name'] }}</h4>
                                    <span class="checkout-item-unit">₹{{ number_format($item['price'], 2) }} × {{ $item['quantity'] }}</span>
                                </div>
                                <div class="checkout-item-subtotal">
                                    ₹{{ number_format($item['price'] * $item['quantity'], 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="summary-divider"></div>

                    <!-- Cost Breakdown -->
                    <div class="summary-line">
                        <span class="summary-label">Subtotal</span>
                        <span class="summary-value">₹{{ number_format($total, 2) }}</span>
                    </div>

                    <div class="summary-line">
                        <span class="summary-label">Shipping</span>
                        <span class="summary-value text-free">FREE</span>
                    </div>

                    <div class="summary-line">
                        <span class="summary-label">Tax (GST)</span>
                        <span class="summary-value">₹0.00</span>
                    </div>

                    <div class="summary-divider"></div>

                    <div class="summary-total-line">
                        <span class="total-label">Total Amount</span>
                        <span class="total-value">₹{{ number_format($total, 2) }}</span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary btn-lg btn-block place-order-btn" id="placeOrderBtn">
                        Place Order
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </button>

                    <div class="cart-trust-badges">
                        <div class="trust-item">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                            <span>100% Secure &amp; Verified Purchase</span>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
