@extends('layouts.app')

@section('title', 'Home - Shop Everything You Need | ' . config('app.name', 'NovaStore'))

@section('content')
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="hero-grid">
                <div class="hero-content">
                    <div class="hero-badge">
                        <span>✨ New Season Collections</span>
                    </div>
                    <h1 class="hero-title">
                        Shop <span>Everything</span> You Need
                    </h1>
                    <p class="hero-description">
                        Explore our handpicked collection of trending products, electronics, fashion, and lifestyle essentials with fast worldwide delivery and unbeatable prices.
                    </p>
                    <div class="hero-actions">
                        <a href="{{ url('/products') }}" class="btn btn-primary btn-lg">Shop Now</a>
                        <a href="{{ url('/categories') }}" class="btn btn-outline btn-lg" style="color: #ffffff; border-color: rgba(255,255,255,0.2);">Browse Categories</a>
                    </div>
                </div>

                <div class="hero-visual">
                    <div class="hero-banner-card">
                        <div class="hero-card-header">
                            <span class="hero-card-tag">Featured Deal</span>
                            <span class="hero-card-rating">★★★★★ (4.9)</span>
                        </div>
                        <div class="hero-card-image-box">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                <line x1="8" y1="21" x2="16" y2="21"></line>
                                <line x1="12" y1="17" x2="12" y2="21"></line>
                            </svg>
                        </div>
                        <div class="hero-card-details">
                            <h3>Next-Gen Wireless Headphones</h3>
                            <div class="hero-card-price">$149.00</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features Bar -->
            <div class="hero-features">
                <div class="feature-item">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                    </div>
                    <div class="feature-text">
                        <h4>Free Express Delivery</h4>
                        <p>On all orders over $50</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>
                    <div class="feature-text">
                        <h4>Secure Checkout</h4>
                        <p>100% protected payments</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="23 4 23 10 17 10"></polyline>
                            <polyline points="1 20 1 14 7 14"></polyline>
                            <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                        </svg>
                    </div>
                    <div class="feature-text">
                        <h4>30-Day Easy Returns</h4>
                        <p>Hassle-free guarantee</p>
                    </div>
                </div>

                <div class="feature-item">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                    </div>
                    <div class="feature-text">
                        <h4>24/7 Expert Support</h4>
                        <p>Ready to help anytime</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop by Category Section -->
    <section class="section">
        <div class="container">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Shop by Category</h2>
                    <p class="section-subtitle">Find what you're looking for across our wide selection</p>
                </div>
                <a href="{{ url('/categories') }}" class="section-link">
                    View All Categories &rarr;
                </a>
            </div>

            @if($categories->isNotEmpty())
                <div class="category-grid">
                    @foreach($categories as $category)
                        <a href="{{ url('/categories/' . $category->slug) }}" class="category-card">
                            <div class="category-media">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" loading="lazy">
                                @else
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="3" width="7" height="7"></rect>
                                        <rect x="14" y="14" width="7" height="7"></rect>
                                        <rect x="3" y="14" width="7" height="7"></rect>
                                    </svg>
                                @endif
                            </div>
                            <h3 class="category-name">{{ $category->name }}</h3>
                            <span class="category-count">Shop Collection</span>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="3" y="3" width="7" height="7"></rect>
                            <rect x="14" y="3" width="7" height="7"></rect>
                            <rect x="14" y="14" width="7" height="7"></rect>
                            <rect x="3" y="14" width="7" height="7"></rect>
                        </svg>
                    </div>
                    <h3 class="empty-state-title">No Categories Available</h3>
                    <p class="empty-state-desc">Categories will appear here once added in the admin panel.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="section section-alt">
        <div class="container">
            <div class="section-header">
                <div>
                    <h2 class="section-title">Featured Products</h2>
                    <p class="section-subtitle">Top quality items in stock and ready to ship</p>
                </div>
                <a href="{{ url('/products') }}" class="section-link">
                    Explore All Products &rarr;
                </a>
            </div>

            @if($products->isNotEmpty())
                <div class="product-grid">
                    @foreach($products as $product)
                        <div class="product-card">
                            <div class="product-image-container">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="product-badge sale">Sale</span>
                                @endif

                                <span class="product-stock-badge">
                                    {{ $product->stock }} in stock
                                </span>

                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy">
                                @else
                                    <div class="product-image-placeholder">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="product-info">
                                @if($product->category)
                                    <span class="product-category">{{ $product->category->name }}</span>
                                @endif

                                <h3 class="product-title">{{ $product->name }}</h3>

                                <div class="product-pricing">
                                    @if($product->sale_price && $product->sale_price < $product->price)
                                        <span class="product-price">${{ number_format($product->sale_price, 2) }}</span>
                                        <span class="product-sale-price">${{ number_format($product->price, 2) }}</span>
                                    @else
                                        <span class="product-price">${{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>

                                <div class="product-card-actions">
                                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline btn-block">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                            <line x1="3" y1="6" x2="21" y2="6"></line>
                            <path d="M16 10a4 4 0 0 1-8 0"></path>
                        </svg>
                    </div>
                    <h3 class="empty-state-title">No Products Found</h3>
                    <p class="empty-state-desc">Products will be displayed here as soon as active inventory is added.</p>
                </div>
            @endif
        </div>
    </section>

    <!-- Newsletter CTA -->
    <section class="container">
        <div class="cta-section">
            <h2 class="cta-title">Subscribe to Exclusive Offers</h2>
            <p class="cta-description">Join our VIP membership list and get 15% off your first purchase plus early access to new collections.</p>
            <form class="cta-form" onsubmit="event.preventDefault(); alert('Thank you for subscribing!');">
                <input type="email" class="cta-input" placeholder="Enter your email address" required>
                <button type="submit" class="btn btn-primary">Subscribe</button>
            </form>
        </div>
    </section>
@endsection
