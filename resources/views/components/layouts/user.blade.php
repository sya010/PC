<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'User Dashboard' }} | {{ __('messages.site_name') }}</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
</head>
<body class="min-h-screen antialiased bg-gray-50">
    <!-- Navbar -->
    <x-navbar />

    <div class="container mx-auto px-4 py-8">
        <div class="flex flex-col md:flex-row gap-6">
            <!-- Sidebar -->
            <aside class="w-full md:w-64 flex-shrink-0">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 p-4 sticky top-24">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                            {{ substr(auth()->user()->name ?? 'U', 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <p class="font-medium text-gray-900 truncate">{{ auth()->user()->name ?? 'Guest' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                        </div>
                    </div>
                    
                    <nav class="space-y-1">
                        <a href="{{ route('my-orders') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('my-orders') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3"y1="6"x2="21"y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                            {{ __('My Orders') }}
                        </a>
                        <a href="{{ route('profile') }}" 
                           class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('profile') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12"cy="7"r="4"/></svg>
                            {{ __('My Profile') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="mt-4 pt-4 border-t border-gray-100">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-md text-red-600 hover:bg-red-50 transition-colors text-left">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21"y1="12"x2="9"y2="12"/></svg>
                                {{ __('Logout') }}
                            </button>
                        </form>
                    </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 min-w-0">
                <div class="bg-white rounded-lg shadow-sm border border-gray-100 min-h-[500px] p-6">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <x-footer />
    
    <!-- Custom Cursor -->
    <x-cursor />

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>
