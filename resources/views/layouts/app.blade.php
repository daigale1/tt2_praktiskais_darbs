{{--
    layouts/app.blade.php
    ──────────────────────
    The main layout shell that wraps every authenticated page in the app.
    All other views extend this file with @extends('layouts.app') and inject
    their content via @yield('content').
--}}

<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- CSRF token in meta tag — available to JS fetch calls if needed --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Neighbors Helping Neighbors') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;600&family=DM+Sans:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- Extra per-page styles can be pushed here with @push('styles') --}}
    @stack('styles')
</head>
<body>

{{-- Main two-column layout: fixed sidebar + scrollable content area --}}
<div class="app-layout">
    @include('partials.sidebar')
    <main class="main-content">
        @yield('content')
    </main>
</div>

{{--
    Poster popup — global floating card for viewing another user's profile.
    Hidden by default (no 'open' class). Shown by showPosterPopup() called
    from feed.blade.php when a poster's name is clicked.
    Fields are populated with JavaScript before the popup is displayed.
--}}
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

{{-- Toast notification bar — shown briefly via showToast() in app.js --}}
<div class="toast" id="toast"></div>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            showToast(@json(session('success')));
        });
    </script>
@endif

<script src="{{ asset('js/app.js') }}"></script>
{{-- Per-page scripts pushed here with @push('scripts') --}}
@stack('scripts')

</body>
</html>