<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Inventaris KAI') }}</title>
        <meta name="description" content="Sistem Inventaris PT Kereta Api Indonesia">

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/kai-logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- PWA Meta Tags -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="KAI Inventaris">
        <link rel="apple-touch-icon" href="{{ asset('images/kai-logo.png') }}">
        <link rel="manifest" href="{{ asset('manifest.json') }}">
        <meta name="turbo-cache-control" content="no-preview">
        <meta name="turbo-prefetch" content="false">

        <!-- KAI Theme CSS -->
        <link rel="stylesheet" href="{{ asset('css/kai-theme.css') }}" data-turbo-track="reload">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" data-turbo-track="reload"></script>
        
        <!-- Hotwire Turbo -->
        <script src="https://unpkg.com/@hotwired/turbo@8.0.0-beta.2/dist/turbo.es2017-umd.js"></script>
        <style>
            .turbo-progress-bar {
                background-color: #ff6b00 !important; /* KAI Orange */
                height: 3px !important;
            }
            
            /* Seamless Fade-in Animation - Subtler to avoid flash */
            @keyframes fadeIn {
                from { opacity: 0.95; }
                to { opacity: 1; }
            }
            
            #main-content {
                animation: fadeIn 0.2s ease-out;
            }
        </style>
    </head>
    <body class="font-sans antialiased mobile-app-shell">
        <div class="app-wrapper">
            <!-- Background Layer -->
            <div class="app-background"></div>

            <div x-data="{
                    open: true,
                    mobileMenuOpen: false,
                    sidebarWidth: 220,
                    updateWidth() {
                        if (window.innerWidth < 1024) {
                            this.sidebarWidth = 0;
                            this.open = false;
                        } else {
                            this.sidebarWidth = this.open ? 220 : 70;
                        }
                    }
                 }"
                 x-init="updateWidth(); window.addEventListener('resize', () => updateWidth())"
                 x-effect="updateWidth()"
                 :style="'--sidebar-width: ' + sidebarWidth + 'px'"
                 class="flex min-h-screen relative z-10">

                <style>
                    [x-cloak] { display: none !important; }
                    .sidebar-width-var { width: var(--sidebar-width); }
                    .content-margin-var { margin-left: var(--sidebar-width); }
                    .transition-custom { transition: all 0.3s ease; }

                    @media (max-width: 1023px) {
                        .content-margin-var { margin-left: 0; }
                    }
                </style>

                <!-- Mobile Overlay -->
                <div x-show="mobileMenuOpen"
                     @click="mobileMenuOpen = false"
                     x-transition:enter="transition-opacity ease-linear duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="transition-opacity ease-linear duration-300"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     class="fixed inset-0 bg-gray-900 bg-opacity-50 z-40 lg:hidden backdrop-blur-sm"></div>

                <!-- Sidebar (Mainly for Desktop) -->
                <div :class="[
                        'fixed left-0 top-0 h-screen kai-sidebar text-white shadow-2xl z-50 flex flex-col overflow-hidden transition-custom sidebar-width-var',
                        mobileMenuOpen ? 'translate-x-0 !w-72' : '-translate-x-full lg:translate-x-0',
                        !open ? 'sidebar-collapsed' : ''
                     ]"
                     class="w-72">

                    <!-- Logo Section -->
                    <div class="flex items-center h-16 border-b sidebar-divider flex-shrink-0 px-4"
                         :class="open ? 'justify-between' : 'justify-center'">
                        <a href="{{ route('dashboard') }}" class="flex items-center min-w-0 sidebar-logo">
                            <img src="{{ asset('images/kai-logo.png') }}" alt="KAI Logo" class="h-10 w-auto">
                            <span x-show="open" x-cloak class="ms-3 sidebar-brand-text truncate">Inventaris</span>
                        </a>
                        <button @click="open = !open" class="hidden lg:block p-1.5 rounded-lg hover:bg-white/10 transition flex-shrink-0" x-show="open" x-cloak>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button @click="open = !open" class="hidden lg:block p-1.5 rounded-lg hover:bg-white/10 transition" x-show="!open" x-cloak>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>

                    <!-- Navigation -->
                    <nav id="sidebar-nav" class="px-3 py-6 space-y-1.5 overflow-y-auto flex-1 relative z-10">
                        <a href="{{ route('dashboard') }}"
                           :title="!open ? 'Dashboard' : ''"
                           class="sidebar-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg class="sidebar-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-4 7 4M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span x-show="open" x-cloak class="sidebar-nav-text">Dashboard</span>
                        </a>

                        <a href="{{ route('inventories.index') }}"
                           :title="!open ? 'Inventaris' : ''"
                           class="sidebar-nav-item {{ request()->routeIs('inventories.*') ? 'active' : '' }}">
                            <svg class="sidebar-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span x-show="open" x-cloak class="sidebar-nav-text">Inventaris</span>
                        </a>

                        <a href="{{ route('units.scan') }}"
                           data-no-spa="true"
                           :title="!open ? 'Scan QR' : ''"
                           class="sidebar-nav-item {{ request()->routeIs('units.scan') ? 'active' : '' }}">
                            <svg class="sidebar-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h2M4 12h2m16 0h2M4 20h2m9-16V4m2 2h2M4 8h2m2-4h2" />
                            </svg>
                            <span x-show="open" x-cloak class="sidebar-nav-text">Scan QR</span>
                        </a>

                        @can('viewAny', App\Models\ActivityLog::class)
                        <a href="{{ route('activity-logs.index') }}"
                           :title="!open ? 'Activity Logs' : ''"
                           class="sidebar-nav-item {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                            <svg class="sidebar-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span x-show="open" x-cloak class="sidebar-nav-text">Activity Logs</span>
                        </a>
                        @endcan
                    </nav>

                    <!-- Divider -->
                    <div class="px-3 flex-shrink-0">
                        <div class="border-t sidebar-divider"></div>
                    </div>

                    <!-- User Section -->
                    <div class="px-3 py-4 flex-shrink-0 relative z-10">
                        <div class="space-y-2">
                            <div class="sidebar-user-card" :class="open ? '' : 'flex justify-center'">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div x-show="open" x-cloak class="overflow-hidden">
                                        <div class="sidebar-user-name truncate">{{ Auth::user()->name }}</div>
                                        <div class="sidebar-user-role truncate">{{ Auth::user()->role ?? 'User' }}</div>
                                    </div>
                                </div>
                            </div>

                            <a href="{{ route('profile.edit') }}"
                               :title="!open ? 'Profile' : ''"
                               class="sidebar-nav-item"
                               :class="open ? '' : 'justify-center'">
                                <svg class="sidebar-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span x-show="open" x-cloak class="sidebar-nav-text">Profile</span>
                            </a>

                            <form method="POST" action="{{ route('logout') }}" class="w-full">
                                @csrf
                                <button type="submit"
                                        :title="!open ? 'Logout' : ''"
                                        class="w-full sidebar-nav-item hover:bg-red-500/20"
                                        :class="open ? '' : 'justify-center'">
                                    <svg class="sidebar-nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span x-show="open" x-cloak class="sidebar-nav-text">Logout</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Bottom Navigation (Mobile Only) - Symmetrical 5-Slot Layout -->
                <nav class="fixed bottom-0 left-0 right-0 lg:hidden bg-white border-t border-gray-100 z-[60] safe-area-bottom shadow-[0_-8px_30px_rgba(0,0,0,0.08)]">
                    <div class="mobile-nav-container">
                        <!-- Slot 1: Beranda -->
                        <a href="{{ route('dashboard') }}"
                           class="mobile-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-4 7 4M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            <span>Beranda</span>
                        </a>

                        <!-- Slot 2: Inventaris -->
                        <a href="{{ route('inventories.index') }}"
                           class="mobile-nav-item {{ request()->routeIs('inventories.*') ? 'active' : '' }}">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                            <span>Inventaris</span>
                        </a>

                        <!-- Slot 3: Center Scan Button -->
                        <div class="mobile-scan-btn-wrapper">
                            <a href="{{ route('units.scan') }}" data-no-spa="true" class="mobile-scan-btn">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h2M4 12h2m16 0h2M4 20h2m9-16V4m2 2h2M4 8h2m2-4h2" />
                                </svg>
                            </a>
                        </div>

                        <!-- Slot 4: Log (Admin/Supervisor) or Manual (User) -->
                        @can('viewAny', App\Models\ActivityLog::class)
                            <a href="{{ route('activity-logs.index') }}"
                               class="mobile-nav-item {{ request()->routeIs('activity-logs.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <span>Log</span>
                            </a>
                        @else
                            <a href="{{ route('profile.edit') }}"
                               class="mobile-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Profil</span>
                            </a>
                        @endcan

                        <!-- Slot 5: Logout (Symmetry and utility) -->
                        @can('viewAny', App\Models\ActivityLog::class)
                            <a href="{{ route('profile.edit') }}"
                               class="mobile-nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span>Profil</span>
                            </a>
                        @else
                            <form method="POST" action="{{ route('logout') }}" class="mobile-nav-item">
                                @csrf
                                <button type="submit" class="w-full h-full flex flex-col items-center justify-center gap-1">
                                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    <span>Keluar</span>
                                </button>
                            </form>
                        @endcan
                    </div>
                </nav>

                <!-- Main Content -->
                <div id="main-content"
                     class="main-content relative z-0 flex-1 w-full flex flex-col transition-custom content-margin-var min-h-screen">

                    <!-- Mobile Top Bar (Enlarged) -->
                    <div class="lg:hidden sticky top-0 bg-gradient-to-r from-[#1a365d] to-[#0f2540] z-40 border-b border-white/10 flex items-center justify-center px-4 h-16 shadow-lg">
                        <div class="flex items-center gap-4">
                            <img src="{{ asset('images/kai-logo.png') }}" alt="KAI Logo" class="h-10 w-auto">
                            <span class="font-bold text-white text-2xl tracking-tight">Inventaris KAI</span>
                        </div>
                    </div>

                    @isset($header)
                        <header class="page-header flex-shrink-0 hidden lg:block">
                            <div class="max-w-7xl mx-auto">
                                {{ $header }}
                            </div>
                        </header>
                        <!-- Mobile Header -->
                        <div class="lg:hidden px-4 py-3 pb-1">
                            {{ $header }}
                        </div>
                    @endisset

                    <main class="flex-1 p-3 sm:p-4 md:p-6 pb-24 lg:pb-6">
                        {{ $slot }}
                    </main>

                    <!-- Footer (Only for Desktop) -->
                    <footer class="kai-footer hidden lg:block">
                        &copy; {{ date('Y') }} PT Kereta Api Indonesia (Persero). All rights reserved.
                    </footer>
                </div>
            </div>
        </div>

        <script data-turbo-eval="false">
            // Register Service Worker for PWA
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/sw.js').then(registration => {
                        console.log('SW registered: ', registration);
                    }).catch(registrationError => {
                        console.log('SW registration failed: ', registrationError);
                    });
                });
            }
            const swalConfig = {
                reverseButtons: true,
                buttonsStyling: false,
                customClass: {
                    confirmButton: 'kai-btn kai-btn-success',
                    cancelButton: 'kai-btn kai-btn-danger'
                }
            };

            function confirmDelete(title, text, formId) {
                Swal.fire({
                    ...swalConfig,
                    title: title || 'Apakah Anda yakin?',
                    text: text || "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal',
                    customClass: {
                        confirmButton: 'kai-btn kai-btn-success',
                        cancelButton: 'kai-btn kai-btn-danger'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Mohon Tunggu',
                            html: 'Sedang memproses penghapusan...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                        document.getElementById(formId).submit();
                    }
                });
            }

            document.addEventListener('submit', function(e) {
                if (e.target && e.target.classList.contains('swal-delete')) {
                    e.preventDefault();
                    const form = e.target;
                    const itemName = form.dataset.itemName || 'data ini';

                    Swal.fire({
                        ...swalConfig,
                        title: 'Konfirmasi Hapus',
                        text: `Apakah Anda yakin ingin menghapus ${itemName}?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        focusCancel: true,
                        customClass: {
                            confirmButton: 'kai-btn kai-btn-success',
                            cancelButton: 'kai-btn kai-btn-danger'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire({
                                title: 'Memproses...',
                                allowOutsideClick: false,
                                didOpen: () => {
                                    Swal.showLoading();
                                }
                            });
                            form.submit();
                        }
                    });
                }
            });

            // Global Login Success Alert
            @if(session('login_success'))
                Swal.fire({
                    ...swalConfig,
                    title: 'Login Berhasil!',
                    text: "{{ session('login_success') }}",
                    icon: 'success',
                    timer: 3000,
                    timerProgressBar: true,
                    confirmButtonText: 'Mulai',
                    customClass: {
                        confirmButton: 'kai-btn kai-btn-success',
                        cancelButton: 'hidden'
                    }
                });
            @endif

            // Global Success Alert

            // Global Error Alert
            @if(session('error'))
                Swal.fire({
                    ...swalConfig,
                    title: 'Gagal!',
                    text: "{{ session('error') }}",
                    icon: 'error',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'kai-btn kai-btn-danger',
                        cancelButton: 'hidden'
                    }
                });
            @endif
        </script>
    </body>
</html>
