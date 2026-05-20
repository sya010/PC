<x-layouts.app>
    <!-- Hero Section -->
    <section class="relative py-20 lg:py-32 overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                <!-- Hero Content -->
                <div class="text-center lg:text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }} animate-slide-up">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold leading-tight mb-6">
                        <span class="gradient-text">{{ __('messages.home.hero_title') }}</span>
                    </h1>
                    <p class="text-lg sm:text-xl text-content-secondary dark:text-dark-300 mb-10 max-w-xl mx-auto lg:mx-0">
                        {{ __('messages.home.hero_subtitle') }}
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'end' : 'start' }}">
                        <a href="#" class="btn-primary inline-flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            {{ __('messages.home.shop_now') }}
                        </a>
                        <a href="#" class="btn-secondary inline-flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                            </svg>
                            {{ __('messages.home.build_now') }}
                        </a>
                    </div>
                </div>

                <!-- Hero Image/Graphic -->
                <div class="hidden lg:flex justify-center animate-fade-in">
                    <div class="relative">
                        <!-- Glow Effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-brand-accent to-brand-accent blur-3xl opacity-20 rounded-full"></div>
                        
                        <!-- PC Component Illustration -->
                        <div class="relative neu-card p-8 lg:p-12">
                            <div class="grid grid-cols-2 gap-6">
                                <!-- CPU Card -->
                                <div class="glass-card p-6 text-center hover:scale-105 transition-transform">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gradient-to-br from-brand-accent to-brand-accent flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-content-secondary dark:text-dark-300">CPU</span>
                                </div>
                                
                                <!-- GPU Card -->
                                <div class="glass-card p-6 text-center hover:scale-105 transition-transform">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gradient-to-br from-brand-base to-brand-accent flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-content-secondary dark:text-dark-300">GPU</span>
                                </div>
                                
                                <!-- RAM Card -->
                                <div class="glass-card p-6 text-center hover:scale-105 transition-transform">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gradient-to-br from-brand-light to-brand-base flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-content-secondary dark:text-dark-300">RAM</span>
                                </div>
                                
                                <!-- Storage Card -->
                                <div class="glass-card p-6 text-center hover:scale-105 transition-transform">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-xl bg-gradient-to-br from-brand-accent to-brand-dark flex items-center justify-center">
                                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                        </svg>
                                    </div>
                                    <span class="text-sm font-medium text-content-secondary dark:text-dark-300">SSD</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 lg:py-24">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold mb-4">{{ __('messages.home.categories') }}</h2>
                <p class="text-content-secondary dark:text-dark-300 max-w-2xl mx-auto">
                    {{ __('messages.site_description') }}
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 lg:gap-6">
                @php
                    $categories = [
                        ['name' => 'CPU', 'icon' => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z', 'color' => 'from-brand-accent to-brand-accent'],
                        ['name' => 'GPU', 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'color' => 'from-brand-base to-brand-accent'],
                        ['name' => 'RAM', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'color' => 'from-brand-light to-brand-base'],
                        ['name' => 'SSD', 'icon' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4', 'color' => 'from-brand-accent to-brand-dark'],
                        ['name' => 'PSU', 'icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'color' => 'from-brand-base to-brand-dark'],
                        ['name' => 'Case', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'color' => 'from-brand-light to-brand-accent'],
                    ];
                @endphp

                @foreach($categories as $category)
                    <a href="#" class="neu-card-sm p-6 text-center group hover:scale-105 transition-all duration-300">
                        <div class="w-14 h-14 mx-auto mb-4 rounded-xl bg-gradient-to-br {{ $category['color'] }} flex items-center justify-center group-hover:animate-pulse-glow transition-all">
                            <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $category['icon'] }}" />
                            </svg>
                        </div>
                        <span class="font-semibold text-content-primary dark:text-dark-100 group-hover:text-brand-base transition-colors">
                            {{ $category['name'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Why Choose Us Section -->
    <section class="py-16 lg:py-24 bg-surface-secondary/30 dark:bg-dark-900/30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold mb-4">{{ __('messages.home.why_us') }}</h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                <!-- Feature 1 -->
                <div class="glass-card p-6 text-center">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-brand-accent to-brand-accent flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Compatibility Guarantee</h3>
                    <p class="text-sm text-content-muted dark:text-dark-400">Every build is checked for perfect compatibility</p>
                </div>

                <!-- Feature 2 -->
                <div class="glass-card p-6 text-center">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-brand-base to-brand-accent flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Fast Delivery</h3>
                    <p class="text-sm text-content-muted dark:text-dark-400">Quick delivery across Iraq</p>
                </div>

                <!-- Feature 3 -->
                <div class="glass-card p-6 text-center">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-brand-light to-brand-base flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Expert Support</h3>
                    <p class="text-sm text-content-muted dark:text-dark-400">AI-powered chatbot + human assistance</p>
                </div>

                <!-- Feature 4 -->
                <div class="glass-card p-6 text-center">
                    <div class="w-16 h-16 mx-auto mb-6 rounded-2xl bg-gradient-to-br from-brand-accent to-brand-dark flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Local Payments</h3>
                    <p class="text-sm text-content-muted dark:text-dark-400">FIB, FastPay, and Cash on Delivery</p>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
