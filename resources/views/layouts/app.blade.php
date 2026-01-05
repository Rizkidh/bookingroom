<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>
    <body class="font-sans antialiased h-screen overflow-hidden">
        <div x-data="{ open: true, mobileMenuOpen: false }" class="flex h-full bg-gray-100">
            <div x-show="mobileMenuOpen"
                 @click="mobileMenuOpen = false"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-600 bg-opacity-75 z-40 lg:hidden"></div>

            <div :class="[
                'fixed left-0 top-0 h-screen bg-blue-900 text-white transition-all duration-300 shadow-lg z-50 flex flex-col overflow-hidden',
                open ? 'w-64' : 'w-20',
                mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
            ]">
                <div class="flex items-center justify-between h-16 px-4 border-b border-blue-800 flex-shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <x-application-logo class="block h-8 w-auto fill-current text-white" />
                        <span x-show="open" class="ms-3 font-bold text-lg">Inventory</span>
                    </a>
                    <button @click="open = !open" class="hidden lg:block p-1 rounded hover:bg-blue-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <button @click="mobileMenuOpen = false" class="lg:hidden p-1 rounded hover:bg-blue-800 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="px-3 py-6 space-y-2 overflow-y-auto flex-1">
                    <a href="{{ route('dashboard') }}" @class([
                        'flex items-center px-4 py-3 rounded-lg transition',
                        'bg-blue-800 text-white' => request()->routeIs('dashboard'),
                        'text-blue-100 hover:bg-blue-800' => !request()->routeIs('dashboard'),
                    ])>
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-3m0 0l7-4 7 4M5 9v10a1 1 0 001 1h12a1 1 0 001-1V9m-9 11l4-4m0 0l4 4m-4-4v4" />
                        </svg>
                        <span x-show="open" class="ms-3">Dashboard</span>
                    </a>

                    <a href="{{ route('inventories.index') }}" @class([
                        'flex items-center px-4 py-3 rounded-lg transition',
                        'bg-blue-800 text-white' => request()->routeIs('inventories.*'),
                        'text-blue-100 hover:bg-blue-800' => !request()->routeIs('inventories.*'),
                    ])>
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span x-show="open" class="ms-3">Inventaris</span>
                    </a>

                    <a href="{{ route('units.scan') }}" @class([
                        'flex items-center px-4 py-3 rounded-lg transition',
                        'bg-blue-800 text-white' => request()->routeIs('units.scan'),
                        'text-blue-100 hover:bg-blue-800' => !request()->routeIs('units.scan'),
                    ])>
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span x-show="open" class="ms-3">Scan QR</span>
                    </a>

                    @can('viewAny', App\Models\ActivityLog::class)
                    <a href="{{ route('activity-logs.index') }}" @class([
                        'flex items-center px-4 py-3 rounded-lg transition',
                        'bg-blue-800 text-white' => request()->routeIs('activity-logs.*'),
                        'text-blue-100 hover:bg-blue-800' => !request()->routeIs('activity-logs.*'),
                    ])>
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span x-show="open" class="ms-3">Activity Logs</span>
                    </a>
                    @endcan
                </nav>

                <div class="px-3 flex-shrink-0">
                    <div class="border-t border-blue-800"></div>
                </div>

                <div class="px-3 py-6 flex-shrink-0 border-t border-blue-800">
                    <div x-data="{ userMenuOpen: false }" class="space-y-3">
                        <div class="flex items-center px-4 py-3 rounded-lg bg-blue-800">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                            </svg>
                            <div x-show="open" class="ms-3">
                                <div class="text-sm font-semibold">{{ Auth::user()->name }}</div>
                                <div class="text-xs text-blue-200">{{ Auth::user()->role ?? 'User' }}</div>
                            </div>
                        </div>

                        <a href="{{ route('profile.edit') }}" class="flex items-center px-4 py-2 text-sm text-blue-100 hover:bg-blue-800 rounded-lg transition">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span x-show="open" class="ms-3">Profile</span>
                        </a>

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center px-4 py-2 text-sm text-blue-100 hover:bg-blue-800 rounded-lg transition">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                <span x-show="open" class="ms-3">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <button @click="mobileMenuOpen = !mobileMenuOpen" class="lg:hidden fixed top-4 right-4 z-30 p-2 bg-blue-900 text-white rounded-lg shadow-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>

            <div class="flex-1 w-full transition-all duration-300 overflow-y-auto" :class="{'lg:ml-64': open, 'lg:ml-20': !open}">
                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main class="p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
