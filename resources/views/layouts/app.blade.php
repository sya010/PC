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
    <link rel="icon" href="{{ asset('favicon.ico') }}">
    
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
<body class="min-h-screen antialiased bg-white pb-20 lg:pb-0">
    <!-- Subtle Background Pattern -->
    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-indigo-50 via-white to-white opacity-70"></div>
    </div>

    <!-- Main Application Container -->
    <div class="relative z-10 flex flex-col min-h-screen">
        <!-- Navbar -->
        <x-navbar />
        
        <!-- Main Content -->
        <main class="flex-1">
            {{ $slot }}
        </main>
        
        <!-- Footer -->
        <x-footer />
        <!-- Footer -->
        <x-footer />
    </div>

    <!-- Mobile Bottom Navigation -->
    <x-mobile-bottom-nav />

    <!-- Toast Notification -->
    <div x-data="{ show: false, message: '' }" 
         x-on:toast-message.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
         class="fixed bottom-4 right-4 z-50">
        <div x-show="show" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="translate-y-2 opacity-0"
             x-transition:enter-end="translate-y-0 opacity-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="translate-y-0 opacity-100"
             x-transition:leave-end="translate-y-2 opacity-0"
             class="bg-indigo-600 text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-3">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span x-text="message" class="font-medium"></span>
        </div>
    </div>

    <!-- PHP Session Flash to JS Event -->
    @if(session('success'))
        <script>
            document.addEventListener('livewire:navigated', () => {
                window.dispatchEvent(new CustomEvent('toast-message', { detail: { message: "{{ session('success') }}" } }));
            }, { once: true });
             // Fallback for initial load
            document.addEventListener('DOMContentLoaded', () => {
                window.dispatchEvent(new CustomEvent('toast-message', { detail: { message: "{{ session('success') }}" } }));
            });
        </script>
    @endif

    <!-- Livewire Scripts -->
    @livewireScripts
    
    <!-- Additional Scripts -->
    @stack('scripts')
</body>
</html>
