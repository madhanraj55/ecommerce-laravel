<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Madhan Store') . ' - Modern Ecommerce')</title>

    <!-- Custom Storefront Stylesheet -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    @include('components.navbar')

    <main id="main-content">
        @yield('content')
    </main>

    @include('components.footer')

    @stack('scripts')
</body>
</html>
