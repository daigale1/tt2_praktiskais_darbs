<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Neighbors Helping Neighbors') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">

    <!-- Tabler Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body>

<div class="app-layout">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Main content --}}
    <main class="main-content">
        @yield('content')
    </main>

</div>

{{-- Poster popup (shared across task views) --}}
<div class="poster-popup" id="posterPopup">
    <div class="popup-tail"></div>
    <button class="popup-close" onclick="closePopup()" aria-label="Close">
        <i class="ti ti-x"></i>
    </button>
    <h4 id="popupName"></h4>
    <p id="popupDetails"></p>
    <p id="popupStats" style="margin-top:8px;"></p>
    <p id="popupSince" style="margin-top:8px;"></p>
</div>

{{-- Toast notification --}}
<div class="toast" id="toast"></div>

<!-- App JS -->
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')

</body>
</html>
