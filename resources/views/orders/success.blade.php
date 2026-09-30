@extends('layouts.app')

@section('title', 'Order Placed Successfully | ' . config('app.name', 'NovaStore'))

@section('content')
<div class="order-success-page py-16">
    <div class="container">
        <div class="order-success-card">
            <!-- Checkmark Animation Icon -->
            <div class="success-icon-wrapper">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>

            <h1 class="success-title">Order Placed Successfully!</h1>
            <p class="success-subtitle">Thank you for your order. We have received your order details and our team is preparing it for dispatch.</p>

            <!-- Order Details Box -->
            <div class="success-details-box">
                <div class="details-row">
                    <span class="details-label">Order Number:</span>
                    <span class="details-value order-badge">{{ $order->order_number }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Customer Name:</span>
                    <span class="details-value">{{ $order->name }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Email Address:</span>
                    <span class="details-value">{{ $order->email }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Contact Phone:</span>
                    <span class="details-value">{{ $order->phone }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Delivery Address:</span>
                    <span class="details-value">{{ $order->address }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Order Status:</span>
                    <span class="details-value status-badge status-{{ strtolower($order->status) }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="details-row total-highlight">
                    <span class="details-label">Order Total:</span>
                    <span class="details-value price-highlight">₹{{ number_format($order->total, 2) }}</span>
                </div>
            </div>

            <!-- Ordered Items Summary Table -->
            @if($order->items && $order->items->isNotEmpty())
                <div class="order-items-summary">
                    <h3 class="items-summary-heading">Ordered Items</h3>
                    <div class="items-summary-list">
                        @foreach($order->items as $item)
                            <div class="order-summary-item">
                                <div class="order-summary-thumb">
                                    @if($item->product && !empty($item->product->image))
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}">
                                    @else
                                        <div class="order-summary-placeholder">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                                <circle cx="8.5" cy="8.5" r="1.5"></circle>
                                                <polyline points="21 15 16 10 5 21"></polyline>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="order-summary-info">
                                    <h4>{{ $item->product->name ?? 'Product' }}</h4>
                                    <span>Quantity: {{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}</span>
                                </div>
                                <div class="order-summary-subtotal">
                                    ₹{{ number_format($item->subtotal, 2) }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Next Steps Actions -->
            <div class="success-actions">
                <a href="{{ route('home') }}" class="btn btn-primary btn-lg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="19" y1="12" x2="5" y2="12"></line>
                        <polyline points="12 19 5 12 12 5"></polyline>
                    </svg>
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
