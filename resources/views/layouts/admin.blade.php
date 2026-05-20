<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? __('messages.admin.layout_title') }} - {{ __('messages.site_name') }}</title>
    <script>
        window.TechBuildTranslations = {
            theme: @json(__('messages.theme')),
        };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-surface-secondary dark:bg-dark-900 text-content-primary dark:text-dark-100 font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-surface-primary dark:bg-dark-800 border-r border-border-subtle dark:border-dark-700 hidden md:flex flex-col fixed inset-y-0 z-50">
            <div class="p-6 flex items-center gap-2 border-b border-border-subtle dark:border-dark-700">
                <div class="w-8 h-8 bg-brand-accent rounded-lg flex items-center justify-center text-white font-bold">TB</div>
                <span class="font-bold text-xl text-content-primary dark:text-dark-100">TechBuild</span>
            </div>
            
            <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
                <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-content-secondary dark:text-dark-400 hover:bg-brand-base/10 hover:text-brand-base transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-brand-base/10 text-brand-base font-medium' : '' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                    </svg>
                    {{ __('messages.admin.sidebar.dashboard') }}
                </a>
                <a href="{{ route('admin.products') }}" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-content-secondary dark:text-dark-400 hover:bg-brand-base/10 hover:text-brand-base transition-colors {{ request()->routeIs('admin.products') ? 'bg-brand-base/10 text-brand-base font-medium' : '' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                    {{ __('messages.admin.sidebar.products') }}
                </a>
                <a href="{{ route('admin.orders') }}" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-content-secondary dark:text-dark-400 hover:bg-brand-base/10 hover:text-brand-base transition-colors {{ request()->routeIs('admin.orders') ? 'bg-brand-base/10 text-brand-base font-medium' : '' }}">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    {{ __('messages.admin.sidebar.orders') }}
                </a>
                {{-- Users Link Placeholder --}}
                {{-- <a href="#" class="flex items-center gap-3 px-3 py-2 rounded-lg text-slate-600 hover:bg-brand-base/5 hover:text-brand-base transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Users
                </a> --}}
            </nav>

            <div class="p-4 border-t border-border-subtle dark:border-dark-700">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-lg text-content-secondary dark:text-dark-400 hover:bg-surface-secondary dark:hover:bg-dark-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    {{ __('messages.admin.go_to_site') }}
                </a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 md:ml-64 transition-all w-full">
            <!-- Topbar -->
            <header class="bg-surface-primary dark:bg-dark-800 border-b border-border-subtle dark:border-dark-700 h-16 flex items-center justify-between px-6 sticky top-0 z-40">
                <h1 class="text-xl font-bold text-content-primary dark:text-dark-100">{{ $title ?? __('messages.admin.sidebar.dashboard') }}</h1>
                <div class="flex items-center gap-4">
                    <button class="p-2 text-content-muted dark:text-dark-500 hover:bg-surface-secondary dark:hover:bg-dark-700 rounded-full relative">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        <span class="absolute top-2 right-2 w-2 h-2 bg-brand-accent rounded-full border border-content-inverse"></span>
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-brand-base/10 flex items-center justify-center text-brand-base font-bold text-sm">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}
                        </div>
                        <span class="text-sm font-medium text-content-primary dark:text-dark-100 hidden sm:block">{{ auth()->user()->name ?? 'Admin' }}</span>
                    </div>
                </div>
            </header>

            <div class="p-6">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>
</html>
