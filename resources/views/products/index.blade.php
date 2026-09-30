@extends('layouts.app')

@section('title', 'All Products | ' . config('app.name', 'NovaStore'))

@section('content')
<div class="products-listing-page py-12">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="breadcrumbs" aria-label="Breadcrumbs">
            <a href="{{ route('home') }}" class="breadcrumb-link">Home</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">All Products</span>
        </nav>

        <div class="section-header">
            <div>
                <h1 class="page-title">Explore All Products</h1>
                <p class="page-subtitle">Browse through our complete catalog of quality items across all categories.</p>
            </div>
        </div>

        <!-- Filter bar -->
        <div class="products-filter-bar">
            <form action="{{ route('products.index') }}" method="GET" class="filter-form">
                <div class="filter-search-input">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ $search }}" 
                        placeholder="Search products by name..." 
                        aria-label="Search products"
                    >
                </div>

                <div class="filter-category-select">
                    <select name="category" onchange="this.form.submit()" aria-label="Filter by Category">
                        <option value="">All Categories ({{ $categories->sum('products_count') }})</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->slug }}" {{ $categorySlug === $cat->slug ? 'selected' : '' }}>
                                {{ $cat->name }} ({{ $cat->products_count }})
                            </option>
                        @endforeach
                    </select>
                </div>

                @if($search || $categorySlug)
                    <a href="{{ route('products.index') }}" class="btn btn-outline btn-sm reset-filter-btn">
                        Reset Filters
                    </a>
                @endif
            </form>
        </div>

        @if($products->isNotEmpty())
            <div class="product-grid">
                @foreach($products as $product)
                    <div class="product-card">
                        <div class="product-media">
                            @if($product->sale_price && $product->sale_price < $product->price)
                                <span class="product-badge badge-sale">Sale</span>
                            @endif

                            <div class="product-stock-pill {{ $product->stock > 0 ? 'pill-in-stock' : 'pill-out-stock' }}">
                                {{ $product->stock > 0 ? $product->stock . ' in stock' : 'Out of stock' }}
                            </div>

                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" loading="lazy">
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
                            @if($product->category)
                                <span class="product-category">{{ $product->category->name }}</span>
                            @endif

                            <h3 class="product-title">{{ $product->name }}</h3>

                            <div class="product-pricing">
                                @if($product->sale_price && $product->sale_price < $product->price)
                                    <span class="product-price">₹{{ number_format($product->sale_price, 2) }}</span>
                                    <span class="product-sale-price">₹{{ number_format($product->price, 2) }}</span>
                                @else
                                    <span class="product-price">₹{{ number_format($product->price, 2) }}</span>
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

            <!-- Pagination -->
            <div class="pagination-wrapper">
                {{ $products->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                        <polyline points="21 15 16 10 5 21"></polyline>
                    </svg>
                </div>
                <h3 class="empty-state-title">No Products Found</h3>
                <p class="empty-state-desc">Try clearing your search query or selecting a different category.</p>
                <div class="empty-state-actions" style="margin-top: 1.5rem;">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Clear Filters</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
