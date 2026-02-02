<div class="space-y-24 pb-24">
    <!-- Hero Section -->
    <section class="relative min-h-[700px] flex items-center justify-center overflow-hidden">
        <!-- Background Image with Overlay -->
        <div class="absolute inset-0 z-0">
            <img 
                src="https://images.unsplash.com/photo-1593640408182-31c70c8268f5?q=80&w=2054&auto=format&fit=crop" 
                alt="Gaming Setup" 
                class="w-full h-full object-cover"
            >
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900/90 via-slate-900/70 to-slate-900/30"></div>
            <!-- Animated Gradient Orb -->
            <div class="absolute -top-40 -left-40 w-[600px] h-[600px] bg-indigo-600/30 rounded-full blur-[100px] animate-pulse"></div>
        </div>

        <!-- Content -->
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl">
                <span class="inline-flex items-center gap-2 py-2 px-4 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-white font-medium text-sm mb-8 animate-fade-in ring-1 ring-white/10 shadow-lg shadow-indigo-900/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                    New Arrivals 2024
                </span>
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white mb-8 leading-[0.9] tracking-tight animate-slide-up">
                    {{ __('messages.home.hero_title') }}
                </h1>
                <p class="text-xl text-slate-300 mb-10 max-w-xl leading-relaxed animate-slide-up font-light" style="animation-delay: 100ms;">
                    {{ __('messages.home.hero_subtitle') }}
                </p>
                <div class="flex flex-wrap gap-4 animate-slide-up" style="animation-delay: 200ms;">
                    <a href="{{ route('shop') }}" class="px-8 py-4 bg-indigo-600 hover:bg-indigo-500 text-white rounded-2xl font-bold transition-all transform hover:-translate-y-1 shadow-lg shadow-indigo-600/30 ring-4 ring-indigo-600/20">
                        {{ __('messages.home.shop_now') }}
                    </a>
                    <a href="{{ route('build-pc') }}" class="px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/20 rounded-2xl font-bold transition-all transform hover:-translate-y-1 hover:shadow-lg">
                        {{ __('messages.home.build_now') }}
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="container mx-auto px-4">
        <div class="flex items-end justify-between mb-12">
            <div>
                <h2 class="text-4xl font-bold text-slate-900 mb-2 tracking-tight">{{ __('messages.home.categories') }}</h2>
                <div class="w-20 h-1.5 bg-indigo-600 rounded-full"></div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('shop') }}?category={{ $category['id'] }}" class="group relative h-80 rounded-3xl overflow-hidden cursor-pointer shadow-sm hover:shadow-2xl transition-all duration-500">
                    <img src="{{ $category['image'] }}" alt="{{ $category['name'] }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/20 to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>
                    
                    <div class="absolute bottom-0 left-0 p-8 w-full group-hover:translate-y-[-8px] transition-transform duration-500">
                        <span class="inline-block py-1 px-3 rounded-lg bg-white/20 backdrop-blur text-white text-xs font-bold mb-3 border border-white/10">{{ $category['count'] }} Items</span>
                        <h3 class="text-white text-3xl font-bold mb-2">{{ $category['name'] }}</h3>
                        <div class="flex items-center gap-2 text-indigo-300 font-semibold text-sm opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">
                            Explore Category 
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Featured Products -->
    <section class="container mx-auto px-4">
        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="text-indigo-600 font-bold tracking-wider uppercase text-sm mb-2 block">Premium Selection</span>
                <h2 class="text-4xl font-bold text-slate-900 mb-3 tracking-tight">{{ __('messages.home.featured') }}</h2>
                <div class="w-20 h-1.5 bg-indigo-600 rounded-full"></div>
            </div>
            <a href="{{ route('shop') }}" class="hidden md:flex items-center gap-2 text-slate-600 font-bold hover:text-indigo-600 transition-colors group">
                View All Products
                <div class="bg-slate-100 p-2 rounded-full group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($featuredProducts as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>

    <!-- Gaming Gear Section -->
    <section class="container mx-auto px-4">
        <div class="flex justify-between items-end mb-12">
            <div>
                <span class="text-indigo-600 font-bold tracking-wider uppercase text-sm mb-2 block">Level Up Your Setup</span>
                <h2 class="text-4xl font-bold text-slate-900 mb-3 tracking-tight">Gaming Gear</h2>
                <div class="w-20 h-1.5 bg-indigo-600 rounded-full"></div>
            </div>
            <a href="{{ route('shop') }}?category=peripherals" class="hidden md:flex items-center gap-2 text-slate-600 font-bold hover:text-indigo-600 transition-colors group">
                Browse Peripherals 
                <div class="bg-slate-100 p-2 rounded-full group-hover:bg-indigo-100 group-hover:text-indigo-600 transition-colors">
                    <svg class="w-5 h-5 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Big Feature Card -->
            <div class="group relative h-[500px] overflow-hidden bg-slate-900 rounded-3xl shadow-xl">
                <img src="https://images.unsplash.com/photo-1542751371-adc38448a05e?w=800&q=80" alt="Gaming Setup" class="absolute inset-0 w-full h-full object-cover opacity-60 group-hover:opacity-70 transition-opacity duration-500 group-hover:scale-105">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/40 to-transparent"></div>
                <div class="absolute bottom-0 left-0 p-12 max-w-lg">
                    <span class="inline-block py-1 px-3 rounded-lg bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 font-bold text-xs mb-6 backdrop-blur-md">
                        PREMIUM COLLECTION
                    </span>
                    <h3 class="text-4xl font-bold text-white mb-4 leading-tight">Complete Your Battlestation</h3>
                    <p class="text-lg text-slate-300 mb-8 leading-relaxed">Discover our curated selection of high-performance peripherals designed for competitive gaming and maximum comfort.</p>
                    <a href="{{ route('shop') }}" class="inline-flex items-center gap-3 bg-white text-slate-900 px-8 py-4 rounded-2xl font-bold hover:bg-indigo-50 transition-all hover:scale-105 shadow-lg shadow-white/10">
                        Shop Collection
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-2 gap-6">
                @foreach($peripheralProducts as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
        </div>
    </section>

    <!-- Features / Why Us -->
    <section class="container mx-auto px-4">
        <div class="grid md:grid-cols-3 gap-8">
            <div class="bg-white rounded-3xl p-8 text-center hover:-translate-y-2 transition-transform duration-300 border border-slate-100 shadow-sm hover:shadow-xl group">
                <div class="w-20 h-20 bg-indigo-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Compatibility Guarantee</h3>
                <p class="text-slate-500 leading-relaxed">Our smart builder automatically checks if your selected parts fit together perfectly.</p>
            </div>
            
            <div class="bg-white rounded-3xl p-8 text-center hover:-translate-y-2 transition-transform duration-300 border border-slate-100 shadow-sm hover:shadow-xl group">
                <div class="w-20 h-20 bg-purple-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Fast Shipping</h3>
                <p class="text-slate-500 leading-relaxed">Get your parts delivered quickly and safely anywhere in Iraq within 24-48 hours.</p>
            </div>

            <div class="bg-white rounded-3xl p-8 text-center hover:-translate-y-2 transition-transform duration-300 border border-slate-100 shadow-sm hover:shadow-xl group">
                <div class="w-20 h-20 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-slate-900 mb-3">Expert Support</h3>
                <p class="text-slate-500 leading-relaxed">Our team of PC experts is here to help you build your dream machine via chat or phone.</p>
            </div>
        </div>
    </section>
</div>
