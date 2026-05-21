<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? __('messages.site_name') }} | {{ __('messages.site_tagline') }}</title>
    <meta name="description" content="{{ $description ?? __('messages.site_description') }}">
    
    <!-- Canonical & SEO alternates -->
    <link rel="canonical" href="{{ request()->url() }}">
    <link rel="alternate" hreflang="x-default" href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}">
    <link rel="alternate" hreflang="en" href="{{ request()->fullUrlWithQuery(['lang' => 'en']) }}">
    <link rel="alternate" hreflang="ar" href="{{ request()->fullUrlWithQuery(['lang' => 'ar']) }}">
    <link rel="alternate" hreflang="ku" href="{{ request()->fullUrlWithQuery(['lang' => 'ku']) }}">

    <!-- Open Graph Metadata -->
    <meta property="og:title" content="{{ $title ?? __('messages.site_name') }} | {{ __('messages.site_tagline') }}">
    <meta property="og:description" content="{{ $description ?? __('messages.site_description') }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:image" content="{{ asset('favicon/web-app-manifest-512x512.png') }}">
    <meta property="og:locale" content="{{ app()->getLocale() }}">

    <!-- Twitter Metadata -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $title ?? __('messages.site_name') }} | {{ __('messages.site_tagline') }}">
    <meta name="twitter:description" content="{{ $description ?? __('messages.site_description') }}">
    <meta name="twitter:image" content="{{ asset('favicon/web-app-manifest-512x512.png') }}">

    <!-- Favicons & Manifest -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon/favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon/favicon.svg') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <meta name="theme-color" content="#6366f1">
    
    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

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
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <!-- Additional Head Content -->
    @stack('styles')
</head>
<body class="min-h-screen antialiased bg-surface-primary dark:bg-dark-950 text-content-primary dark:text-dark-100 transition-colors duration-300">
    <!-- Subtle Background Pattern -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-brand-base/5 via-surface-primary to-surface-primary dark:via-dark-950 dark:to-dark-950 opacity-70"></div>
    </div>

    <!-- Main Application Container -->
    <div class="relative z-10 flex flex-col min-h-screen">
        <!-- Navbar -->
        <x-navbar />
        
        <!-- Main Content -->
        <main class="flex-1 pt-20">
            {{ $slot }}
        </main>
        
        <!-- Footer -->
        <x-footer />
    </div>

    <!-- Custom Cursor -->
    <x-cursor />
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>
