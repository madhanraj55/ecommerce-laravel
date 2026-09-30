@extends('layouts.app')

@section('title', 'My Account | ' . config('app.name', 'Madhan Store'))

@section('content')
<div class="account-page py-12">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="breadcrumbs" aria-label="Breadcrumbs">
            <a href="{{ route('home') }}" class="breadcrumb-link">Home</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">My Account</span>
        </nav>

        <div class="account-grid">
            <!-- Account Info Card -->
            <div class="account-sidebar-card">
                <div class="account-avatar">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <h2 class="account-user-name">{{ $user->name }}</h2>
                <p class="account-user-email">{{ $user->email }}</p>

                <div class="account-stats">
                    <div class="stat-box">
                        <span class="stat-num">{{ $ordersCount }}</span>
                        <span class="stat-label">Total Orders</span>
                    </div>
                </div>

                <div class="account-menu">
                    <a href="{{ route('orders.index') }}" class="account-menu-item">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="21" x2="16" y2="21"></line>
                            <line x1="12" y1="17" x2="12" y2="21"></line>
                        </svg>
                        <span>My Orders</span>
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit" class="account-menu-item logout-btn">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                            <span>Sign Out</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Main Content: Recent Orders -->
            <div class="account-main-content">
                <div class="dashboard-header">
                    <div>
                        <h1 class="page-title">Recent Orders</h1>
                        <p class="page-subtitle">Track your recent purchases and delivery updates.</p>
                    </div>
                    <a href="{{ route('orders.index') }}" class="btn btn-outline btn-sm">View All Orders</a>
                </div>

                @if($recentOrders->isNotEmpty())
                    <div class="orders-list">
                        @foreach($recentOrders as $order)
                            <div class="order-card">
                                <div class="order-card-header">
                                    <div class="order-meta-left">
                                        <span class="order-number-badge">{{ $order->order_number }}</span>
                                        <span class="order-date">{{ $order->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <div class="order-meta-right">
                                        <span class="status-badge status-{{ strtolower($order->status) }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="order-card-items">
                                    @foreach($order->items as $item)
                                        <div class="order-item-inline">
                                            <span class="order-item-title">{{ $item->product->name ?? 'Product' }}</span>
                                            <span class="order-item-qty">Qty: {{ $item->quantity }}</span>
                                            <span class="order-item-price">₹{{ number_format($item->subtotal, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="order-card-footer">
                                    <span class="order-total-label">Total Amount:</span>
                                    <span class="order-total-amount">₹{{ number_format($order->total, 2) }}</span>
                                </div>
                            </div>
                        @endforeach
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
                        <h3 class="empty-state-title">No Orders Yet</h3>
                        <p class="empty-state-desc">You haven't placed any orders yet. Start exploring our collections today!</p>
                        <div class="empty-state-actions" style="margin-top: 1.5rem;">
                            <a href="{{ route('products.index') }}" class="btn btn-primary">Start Shopping</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
