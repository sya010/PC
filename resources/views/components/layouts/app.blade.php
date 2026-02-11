<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $title ?? __('messages.site_name') }} | {{ __('messages.site_tagline') }}</title>
    <meta name="description" content="{{ $description ?? __('messages.site_description') }}">
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    
    <!-- Preconnect to Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Vite Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Livewire Styles -->
    @livewireStyles
    
    <!-- Additional Head Content -->
    @stack('styles')
</head>
<body class="min-h-screen antialiased bg-white">
    <!-- Subtle Background Pattern -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-50 via-white to-white opacity-70"></div>
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

    <!-- Chatbot Widget -->
    <livewire:chatbot />
    
    <!-- Custom Cursor -->
    <x-cursor />
    
    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>
