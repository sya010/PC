<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? __('messages.admin.layout_title') }} | {{ __('messages.site_name') }}</title>
    <meta name="description" content="{{ $description ?? __('messages.site_description') }}">

    <!-- Canonical & SEO alternates -->
    <link rel="canonical" href="{{ request()->url() }}">
    <link rel="alternate" hreflang="x-default" href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}">
    <link rel="alternate" hreflang="en" href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}">
    <link rel="alternate" hreflang="ar" href="{{ request()->fullUrlWithQuery(['lang' => 'ar']) }}">
    <link rel="alternate" hreflang="ku" href="{{ request()->fullUrlWithQuery(['lang' => 'ku']) }}">

    <!-- Open Graph Metadata -->
    <meta property="og:title" content="{{ $title ?? __('messages.admin.layout_title') }} | {{ __('messages.site_name') }}">
    <meta property="og:description" content="{{ $description ?? __('messages.site_description') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:image" content="{{ asset('favicon/web-app-manifest-512x512.png') }}">
    <meta property="og:locale" content="{{ app()->getLocale() }}">

    <!-- Twitter Metadata -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? __('messages.admin.layout_title') }} | {{ __('messages.site_name') }}">
    <meta name="twitter:description" content="{{ $description ?? __('messages.site_description') }}">
    <meta name="twitter:image" content="{{ asset('favicon/web-app-manifest-512x512.png') }}">

    <!-- Favicons & Manifest -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <meta name="theme-color" content="#6366f1">

    <!-- FOUC Prevention: Apply theme + dark class before paint -->
    <script>
    (function () {
        var darkThemes = ['dark-red', 'midnight', 'forest', 'cosmic', 'sunset', 'deep-teal', 'cherry', 'charcoal'];
        var themeClasses = ['theme-ocean', 'theme-midnight', 'theme-emerald', 'theme-forest', 'theme-royal', 'theme-cosmic', 'theme-amber', 'theme-sunset', 'theme-teal', 'theme-deep-teal', 'theme-rose', 'theme-cherry', 'theme-slate', 'theme-charcoal'];
        function initTheme() {
            var t = localStorage.siteTheme || 'light-red';
            themeClasses.forEach(function (c) { document.documentElement.classList.remove(c); });
            var map = { ocean: 'theme-ocean', midnight: 'theme-midnight', emerald: 'theme-emerald', forest: 'theme-forest', royal: 'theme-royal', cosmic: 'theme-cosmic', amber: 'theme-amber', sunset: 'theme-sunset', teal: 'theme-teal', 'deep-teal': 'theme-deep-teal', rose: 'theme-rose', cherry: 'theme-cherry', slate: 'theme-slate', charcoal: 'theme-charcoal' };
            if (map[t]) document.documentElement.classList.add(map[t]);
            if (darkThemes.indexOf(t) > -1) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        initTheme();
        document.addEventListener('livewire:navigated', initTheme);
    })();
    </script>

    <script>
        window.TechBuildTranslations = {
            theme: @json(__('messages.theme')),
        };
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-surface-secondary dark:bg-dark-950 text-content-primary dark:text-dark-100 font-sans antialiased transition-colors duration-300">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside class="w-64 bg-dark-900 text-dark-100 flex-shrink-0 hidden md:flex flex-col border-r border-dark-800">
            <div class="p-4 border-b border-dark-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('favicon/favicon.svg') }}" alt="Logo" class="w-8 h-8 object-contain dark:invert">
                    <span class="text-xl font-bold tracking-wider text-white">{{ __('messages.admin.panel_title') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <livewire:language-switcher />
                    <a href="{{ route('home') }}" class="text-dark-200 hover:text-white" title="{{ __('messages.admin.go_to_site') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                    </a>
                </div>
            </div>
            
            <nav class="flex-1 overflow-y-auto py-4 px-2 space-y-1">
                <a href="{{ route('admin.dashboard') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-brand-base text-white' : 'text-dark-200 hover:bg-dark-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                    {{ __('messages.admin.sidebar.dashboard') }}
                </a>
                
                <div class="pt-4 pb-2 px-3 text-xs font-semibold text-dark-500 uppercase tracking-wider">{{ __('messages.admin.sidebar.management') }}</div>
                
                <a href="{{ route('admin.products') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.products') ? 'bg-brand-base text-white' : 'text-dark-200 hover:bg-dark-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                    {{ __('messages.admin.sidebar.products') }}
                </a>
                
                <a href="{{ route('admin.orders') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.orders') ? 'bg-brand-base text-white' : 'text-dark-200 hover:bg-dark-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                    {{ __('messages.admin.sidebar.orders') }}
                </a>

                <a href="{{ route('admin.users') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors {{ request()->routeIs('admin.users') ? 'bg-brand-base text-white' : 'text-dark-200 hover:bg-dark-800 hover:text-white' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                    {{ __('messages.admin.sidebar.users') }}
                </a>

                <div class="pt-4 pb-2 px-3 text-xs font-semibold text-dark-500 uppercase tracking-wider">{{ __('messages.admin.sidebar.personal') }}</div>

                <a href="{{ route('profile') }}" 
                   class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors text-dark-200 hover:bg-dark-800 hover:text-white">
                   <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    {{ __('messages.admin.sidebar.my_profile') }}
                </a>

            </nav>
            
            <div class="p-4 border-t border-dark-800">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-md text-red-400 hover:bg-red-900/20 hover:text-red-300 transition-colors text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        {{ __('messages.admin.sidebar.sign_out') }}
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-surface-secondary dark:bg-dark-950">
             <!-- Mobile Header -->
            <div class="md:hidden flex items-center justify-between bg-dark-900 text-white p-4">
                <div class="flex items-center gap-2">
                    <img src="{{ asset('favicon/favicon.svg') }}" alt="Logo" class="w-6 h-6 object-contain dark:invert">
                    <span class="font-bold text-white">{{ __('messages.admin.panel_title_mobile') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <livewire:language-switcher />
                    <button class="text-dark-300 hover:text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="18" x2="21" y2="18"/></svg>
                    </button>
                </div>
            </div>

            <div class="p-6">
                {{ $slot }}
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>
