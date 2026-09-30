@extends('layouts.app')

@section('title', 'My Orders | ' . config('app.name', 'Madhan Store'))

@section('content')
<div class="orders-page py-12">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="breadcrumbs" aria-label="Breadcrumbs">
            <a href="{{ route('home') }}" class="breadcrumb-link">Home</a>
            <span class="breadcrumb-separator">/</span>
            <a href="{{ route('account.index') }}" class="breadcrumb-link">My Account</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">My Orders</span>
        </nav>

        <div class="section-header">
            <div>
                <h1 class="page-title">My Orders</h1>
                <p class="page-subtitle">View and track all orders placed with your account.</p>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-outline btn-sm">
                Continue Shopping
            </a>
        </div>

        @if($orders->isNotEmpty())
            <div class="customer-orders-grid">
                @foreach($orders as $order)
                    <div class="customer-order-card">
                        <div class="order-card-header">
                            <div class="order-header-main">
                                <span class="order-badge-tag">{{ $order->order_number }}</span>
                                <span class="order-date-text">Placed on {{ $order->created_at->format('M d, Y h:i A') }}</span>
                            </div>
                            <div class="order-header-status">
                                <span class="status-badge status-{{ strtolower($order->status) }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>

                        <div class="order-card-body">
                            <div class="order-items-table">
                                @foreach($order->items as $item)
                                    <div class="order-item-row">
                                        <div class="order-item-thumb">
                                            @if($item->product && !empty($item->product->image))
                                                <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                            @else
                                                <div class="order-thumb-placeholder">
                                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                        <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                        <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                        <polyline points="21 15 16 10 5 21"></polyline>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="order-item-info">
                                            <h4 class="item-title">{{ $item->product->name ?? 'Product' }}</h4>
                                            <span class="item-meta">₹{{ number_format($item->price, 2) }} × {{ $item->quantity }}</span>
                                        </div>
                                        <div class="order-item-total">
                                            ₹{{ number_format($item->subtotal, 2) }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="order-card-footer">
                            <div class="order-delivery-snapshot">
                                <strong>Delivery to:</strong> {{ $order->name }} — {{ $order->address }}
                            </div>
                            <div class="order-final-total">
                                <span class="total-label">Order Total:</span>
                                <span class="total-price">₹{{ number_format($order->total, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="pagination-wrapper">
                {{ $orders->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                        <line x1="8" y1="21" x2="16" y2="21"></line>
                        <line x1="12" y1="17" x2="12" y2="21"></line>
                    </svg>
                </div>
                <h3 class="empty-state-title">No Orders Found</h3>
                <p class="empty-state-desc">You haven't placed any orders yet. Check out our store to find great items!</p>
                <div class="empty-state-actions" style="margin-top: 1.5rem;">
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Browse Catalog</a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
