@extends('layouts.app')

@section('title', e($product->name) . ' | ' . config('app.name', 'Madhan Store'))

@section('content')
<div class="product-details-page py-12">
    <div class="container">
        <!-- Breadcrumbs & Navigation -->
        <nav class="breadcrumb-nav" aria-label="Breadcrumb">
            <a href="{{ route('home') }}" class="breadcrumb-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                Home
            </a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('products.index') }}" class="breadcrumb-link">Products</a>
            <span class="breadcrumb-separator">/</span>
            @if($product->category)
                <a href="{{ route('categories.show', $product->category->slug) }}" class="breadcrumb-link">{{ $product->category->name }}</a>
                <span class="breadcrumb-separator">/</span>
            @endif
            <span class="breadcrumb-current">{{ $product->name }}</span>
        </nav>

        <div class="product-details-grid">
            <!-- Product Media Gallery -->
            <div class="product-gallery">
                <div class="product-main-image-box">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="product-badge sale">Sale</span>
                    @endif

                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-detail-img">
                    @else
                        <div class="product-detail-placeholder">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                <polyline points="21 15 16 10 5 21"></polyline>
                            </svg>
                            <span>No Image Available</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Product Purchase & Details Column -->
            <div class="product-summary">
                @if($product->category)
                    <a href="{{ route('categories.show', $product->category->slug) }}" class="product-summary-category">
                        {{ $product->category->name }}
                    </a>
                @endif

                <h1 class="product-summary-title">{{ $product->name }}</h1>

                <!-- Pricing Section -->
                <div class="product-summary-pricing">
                    @if($product->sale_price && $product->sale_price < $product->price)
                        <span class="product-summary-price-sale">₹{{ number_format($product->sale_price, 2) }}</span>
                        <span class="product-summary-price-old">₹{{ number_format($product->price, 2) }}</span>
                        <span class="product-discount-tag">
                            Save {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}%
                        </span>
                    @else
                        <span class="product-summary-price">₹{{ number_format($product->price, 2) }}</span>
                    @endif
                </div>

                <!-- Stock Availability Badge -->
                <div class="product-stock-status">
                    @if($product->stock > 0)
                        <span class="stock-indicator in-stock">
                            <span class="stock-dot"></span>
                            In Stock ({{ $product->stock }} available)
                        </span>
                    @else
                        <span class="stock-indicator out-of-stock">
                            <span class="stock-dot"></span>
                            Out of Stock
                        </span>
                    @endif
                </div>

                <!-- Description -->
                <div class="product-summary-description">
                    <h3>Description</h3>
                    <div class="description-text">
                        {!! nl2br(e($product->description ?? 'No detailed description provided for this product.')) !!}
                    </div>
                </div>

                <!-- Purchase / Cart Action Form -->
                <div class="product-actions-card">
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product) }}" method="POST" id="addToCartForm" class="product-purchase-form">
                            @csrf
                            <div class="product-action-row">
                                <div class="quantity-picker">
                                    <button type="button" class="qty-btn" id="qtyMinus" aria-label="Decrease quantity">-</button>
                                    <input 
                                        type="number" 
                                        id="quantityInput" 
                                        name="quantity" 
                                        value="1" 
                                        min="1" 
                                        max="{{ $product->stock }}" 
                                        class="qty-input"
                                        readonly
                                    >
                                    <button type="button" class="qty-btn" id="qtyPlus" aria-label="Increase quantity">+</button>
                                </div>

                                <button type="submit" class="btn btn-primary add-to-cart-btn">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="9" cy="21" r="1"></circle>
                                        <circle cx="20" cy="21" r="1"></circle>
                                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                                    </svg>
                                    Add to Cart
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="product-action-row">
                            <button type="button" class="btn btn-outline btn-block" disabled style="opacity: 0.6; cursor: not-allowed;">
                                Out of Stock
                            </button>
                        </div>
                    @endif

                    <div class="product-back-nav">
                        <a href="{{ route('products.index') }}" class="back-link">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                            Back to Products
                        </a>
                    </div>
                </div>

                <!-- Product Value Props -->
                <div class="product-perks">
                    <div class="perk-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="1" y="3" width="15" height="13"></rect>
                            <polygon points="16 8 20 8 23 11 23 16 16 16 8"></polygon>
                            <circle cx="5.5" cy="18.5" r="2.5"></circle>
                            <circle cx="18.5" cy="18.5" r="2.5"></circle>
                        </svg>
                        <span>Fast & reliable delivery available</span>
                    </div>
                    <div class="perk-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                        <span>100% genuine product guarantee</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Remaining / Related Products Section -->
        @if(isset($remainingProducts) && $remainingProducts->isNotEmpty())
            <div class="related-products-section" style="margin-top: 4rem; padding-top: 3rem; border-top: 1px solid var(--border-color);">
                <div class="section-header">
                    <div>
                        <h2 class="section-title">More Products You Might Like</h2>
                        <p class="section-subtitle">Explore other quality items from our catalog</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="section-link">
                        View All Products &rarr;
                    </a>
                </div>

                <div class="product-grid">
                    @foreach($remainingProducts as $otherProduct)
                        <div class="product-card">
                            <div class="product-media">
                                @if($otherProduct->sale_price && $otherProduct->sale_price < $otherProduct->price)
                                    <span class="product-badge badge-sale">Sale</span>
                                @endif

                                <div class="product-stock-pill {{ $otherProduct->stock > 0 ? 'pill-in-stock' : 'pill-out-stock' }}">
                                    {{ $otherProduct->stock > 0 ? $otherProduct->stock . ' in stock' : 'Out of stock' }}
                                </div>

                                @if($otherProduct->image)
                                    <img src="{{ asset('storage/' . $otherProduct->image) }}" alt="{{ $otherProduct->name }}" loading="lazy">
                                @else
                                    <div class="product-placeholder-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                            <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                            <polyline points="21 15 16 10 5 21"></polyline>
                                        </svg>
                                    </div>
                                @endif
                            </div>

                            <div class="product-info">
                                @if($otherProduct->category)
                                    <span class="product-category">{{ $otherProduct->category->name }}</span>
                                @endif

                                <h3 class="product-title">{{ $otherProduct->name }}</h3>

                                <div class="product-pricing">
                                    @if($otherProduct->sale_price && $otherProduct->sale_price < $otherProduct->price)
                                        <span class="product-price">₹{{ number_format($otherProduct->sale_price, 2) }}</span>
                                        <span class="product-sale-price">₹{{ number_format($otherProduct->price, 2) }}</span>
                                    @else
                                        <span class="product-price">₹{{ number_format($otherProduct->price, 2) }}</span>
                                    @endif
                                </div>

                                <div class="product-card-actions">
                                    <a href="{{ route('products.show', $otherProduct->slug) }}" class="btn btn-outline btn-block">
                                        View Product
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>

@if($product->stock > 0)
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const minusBtn = document.getElementById('qtyMinus');
        const plusBtn = document.getElementById('qtyPlus');
        const input = document.getElementById('quantityInput');
        const maxStock = parseInt("{{ $product->stock }}", 10) || 1;

        if (minusBtn && plusBtn && input) {
            minusBtn.addEventListener('click', function () {
                let current = parseInt(input.value, 10) || 1;
                if (current > 1) {
                    input.value = current - 1;
                }
            });

            plusBtn.addEventListener('click', function () {
                let current = parseInt(input.value, 10) || 1;
                if (current < maxStock) {
                    input.value = current + 1;
                }
            });
        }
    });
</script>
@endif
@endsection
