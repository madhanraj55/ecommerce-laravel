@extends('layouts.app')

@section('title', 'All Categories | ' . config('app.name', 'Madhan Store'))

@section('content')
<div class="category-listing-page py-12">
    <div class="container">
        <!-- Breadcrumbs -->
        <nav class="breadcrumbs" aria-label="Breadcrumbs">
            <a href="{{ route('home') }}" class="breadcrumb-link">Home</a>
            <span class="breadcrumb-separator">/</span>
            <span class="breadcrumb-current">Categories</span>
        </nav>

        <div class="section-header">
            <div>
                <h1 class="page-title">Explore All Categories</h1>
                <p class="page-subtitle">Browse through our curated collection of categories to find exactly what you're looking for.</p>
            </div>
        </div>

        @if($categories->isNotEmpty())
            <div class="category-grid">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="category-card">
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
                        <span class="category-count">
                            {{ $category->products_count }} {{ Str::plural('Product', $category->products_count) }}
                        </span>
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
                <h3 class="empty-state-title">No Categories Found</h3>
                <p class="empty-state-desc">Categories will appear here once created in the admin panel.</p>
            </div>
        @endif
    </div>
</div>
@endsection
