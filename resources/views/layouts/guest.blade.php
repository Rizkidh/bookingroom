<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KAI Inventaris') }}</title>
        <meta name="description" content="Sistem Inventaris PT Kereta Api Indonesia">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/kai-logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Auth Styles -->
        <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="auth-page">
        <!-- LEFT SIDE - Branding Panel -->
        <div class="auth-branding">
            <!-- Floating Elements -->
            <div class="floating-elements"></div>
            
            <!-- Branding Content -->
            <div class="auth-branding-content">
                <a href="/" class="auth-logo">
                    <img src="{{ asset('images/kai-logo.png') }}" alt="KAI Logo">
                </a>
                <h1 class="auth-branding-title">Sistem Inventaris</h1>
                <p class="auth-branding-subtitle">PT Kereta Api Indonesia (Persero)</p>
                
                <!-- Features -->
                <div class="auth-features">
                    <div class="auth-feature">
                        <span class="auth-feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                                <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                                <line x1="12" y1="22.08" x2="12" y2="12"></line>
                            </svg>
                        </span>
                        <span>Manajemen Aset & Inventaris</span>
                    </div>
                    <div class="auth-feature">
                        <span class="auth-feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="1" y="3" width="15" height="13"></rect>
                                <polygon points="16 8 20 8 23 11 23 16 16 16 16 8"></polygon>
                                <circle cx="5.5" cy="18.5" r="2.5"></circle>
                                <circle cx="18.5" cy="18.5" r="2.5"></circle>
                            </svg>
                        </span>
                        <span>Tracking & QR Code</span>
                    </div>
                    <div class="auth-feature">
                        <span class="auth-feature-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            </svg>
                        </span>
                        <span>Aman & Terpercaya</span>
                    </div>
                </div>
            </div>
            
            <!-- Railway Track Decoration -->
            <div class="auth-track-decoration"></div>
        </div>

        <!-- RIGHT SIDE - Form Panel -->
        <div class="auth-form-panel">
            <div class="auth-form-container">
                <!-- Mobile Logo (shown only on mobile) -->
                <div class="auth-mobile-logo">
                    <a href="/">
                        <img src="{{ asset('images/kai-logo.png') }}" alt="KAI Logo">
                    </a>
                    <h2>Sistem Inventaris</h2>
                    <p>PT Kereta Api Indonesia (Persero)</p>
                </div>

                <!-- Form Content -->
                {{ $slot }}
                
                <!-- Copyright -->
                <div class="auth-copyright">
                    &copy; {{ date('Y') }} PT Kereta Api Indonesia. All rights reserved.
                </div>
            </div>
        </div>
    </body>
</html>
