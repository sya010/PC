<nav class="fixed top-0 inset-x-0 z-50 bg-surface-primary/80 dark:bg-dark-900/80 backdrop-blur-xl border-b border-border-subtle dark:border-dark-700 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="{{ route('home') }}" wire:navigate class="flex items-center gap-3 group">
                    <img src="{{ asset('favicon/favicon.svg') }}" alt="Logo" class="w-8 h-8 object-contain dark:invert transition-transform duration-300 group-hover:scale-105">
                    <span class="font-bold text-xl tracking-tight text-content-primary dark:text-white">{{ __('messages.site_name') }}</span>
                </a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden lg:flex items-center gap-8">
                @foreach([
                    'home' => ['label' => 'messages.nav.home', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    'shop' => ['label' => 'messages.nav.shop', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                    'build-pc' => ['label' => 'messages.nav.build_pc', 'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    'compare' => ['label' => 'messages.nav.compare', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    'support' => ['label' => 'messages.nav.support', 'icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z']
                ] as $route => $item)
                    <a href="{{ route($route) }}" wire:navigate class="relative flex items-center gap-2 py-2 text-sm font-medium text-content-secondary dark:text-dark-400 hover:text-brand-base transition-colors group">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" />
                        </svg>
                        {{ __($item['label']) }}
                        <span class="absolute bottom-0 start-0 w-full h-0.5 bg-brand-base transform scale-x-0 group-hover:scale-x-100 transition-transform duration-300 origin-left rtl:origin-right"></span>
                    </a>
                @endforeach
            </div>

            <!-- Right Side Actions -->
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2 md:gap-4">
                    <livewire:cart-counter />
                    <div class="hidden md:block">
                        <livewire:language-switcher />
                    </div>
                    <!-- Theme Picker (Desktop) -->
                    <div x-data="{ open: false }" class="relative hidden md:block">
                        <button @click="open = !open" class="relative w-9 h-9 rounded-xl flex items-center justify-center text-content-secondary dark:text-dark-400 hover:text-brand-base hover:bg-surface-secondary dark:hover:bg-dark-700 transition-all" title="{{ __('messages.theme.label') }}" aria-label="{{ __('messages.theme.label') }}">
                            <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.098 19.902a3.75 3.75 0 005.304 0l6.401-6.402M6.75 21A3.75 3.75 0 013 17.25V4.125C3 3.504 3.504 3 4.125 3h5.25c.621 0 1.125.504 1.125 1.125v4.072M6.75 21a3.75 3.75 0 003.75-3.75V8.197M6.75 21h13.125c.621 0 1.125-.504 1.125-1.125v-5.25c0-.621-.504-1.125-1.125-1.125h-4.072M10.5 8.197l2.88-2.88c.438-.439 1.15-.439 1.59 0l3.712 3.713c.44.44.44 1.152 0 1.59l-2.879 2.88M6.75 17.25h.008v.008H6.75v-.008z" />
                            </svg>
                        </button>
                        <div x-show="open" @click.outside="open = false" x-cloak
                            x-transition:enter="transition ease-out duration-150"
                            x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-100"
                            x-transition:leave-start="opacity-100 scale-100"
                            x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 rtl:right-auto rtl:left-0 mt-2 w-56 rounded-2xl bg-surface-primary dark:bg-dark-700 border border-border-subtle/80 dark:border-dark-600 shadow-xl p-2 z-[110] max-h-[420px] overflow-y-auto">
                            <p class="text-[10px] font-bold uppercase tracking-widest text-content-muted px-3 pt-1 pb-2">{{ __('messages.theme.choose_theme') }}</p>
                            <template x-for="t in $store.theme.themes" :key="t.key">
                                <button @click="$store.theme.setTheme(t.key); open = false"
                                    class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium transition-all"
                                    :class="$store.theme.current === t.key ? 'bg-surface-secondary dark:bg-dark-600 text-content-primary dark:text-white' : 'text-content-secondary dark:text-dark-400 hover:bg-surface-secondary/50 dark:hover:bg-dark-800'">
                                    <span class="relative w-5 h-5 rounded-full border-2 shrink-0 shadow-sm" :style="'background:' + t.swatch + ';border-color:' + t.swatch">
                                        <span x-show="t.dark" class="absolute -bottom-0.5 -right-0.5 w-2.5 h-2.5 rounded-full bg-dark-850 border border-content-inverse flex items-center justify-center">
                                            <svg class="w-1.5 h-1.5 text-status-warning-soft" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z" /></svg>
                                        </span>
                                    </span>
                                    <span x-text="$store.theme.label(t)" class="flex-1 text-start"></span>
                                    <svg x-show="$store.theme.current === t.key" class="w-4 h-4 text-brand-base shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- User Profile -->
                <div class="relative hidden md:block" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-3 ps-4 border-s border-border-subtle dark:border-dark-600 hover:opacity-80 transition-opacity group">
                        <div class="w-10 h-10 rounded-full bg-surface-secondary dark:bg-dark-700 flex items-center justify-center text-content-secondary dark:text-dark-400 group-hover:bg-brand-base/10 group-hover:text-brand-base transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                    </button>
                    <div x-show="open" @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
                        class="absolute end-0 mt-4 w-56 bg-surface-primary dark:bg-dark-700 rounded-2xl shadow-xl border border-border-subtle dark:border-dark-600 overflow-hidden py-2" style="display: none;">
                        @auth
                            <div class="px-5 py-3 border-b border-border-subtle dark:border-dark-600 bg-surface-secondary/50 dark:bg-dark-800/50">
                                <p class="text-sm font-bold text-content-primary dark:text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-content-secondary dark:text-dark-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="p-2 space-y-1">
                                @if(auth()->user()->is_admin)
                                    <a href="{{ route('admin.dashboard') }}" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-content-secondary dark:text-dark-400 hover:bg-surface-secondary dark:hover:bg-dark-600 hover:text-brand-base transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        {{ __('messages.admin.panel_title') }}
                                    </a>
                                @endif
                                <a href="{{ route('profile') }}" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-content-secondary dark:text-dark-400 hover:bg-surface-secondary dark:hover:bg-dark-600 hover:text-brand-base transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                    {{ __('messages.user_orders.my_profile') }}
                                </a>
                                <a href="{{ route('my-orders') }}" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-content-secondary dark:text-dark-400 hover:bg-surface-secondary dark:hover:bg-dark-600 hover:text-brand-base transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                                    {{ __('messages.user_orders.title') }}
                                </a>
                                <form method="POST" action="{{ route('logout') }}" class="w-full">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-content-secondary dark:text-dark-400 hover:bg-surface-secondary dark:hover:bg-dark-600 hover:text-brand-base transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                        {{ __('messages.logout') }}
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="p-2 space-y-1">
                                <a href="{{ route('login') }}" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-medium text-content-secondary dark:text-dark-400 hover:bg-surface-secondary dark:hover:bg-dark-600 hover:text-brand-base transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                                    {{ __('messages.login') }}
                                </a>
                                <a href="{{ route('register') }}" wire:navigate class="flex items-center gap-3 px-3 py-2 rounded-xl text-sm font-bold text-brand-base bg-brand-base/10 hover:bg-brand-base/20 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    {{ __('messages.register') }}
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>

                <!-- Mobile Menu Button -->
                <button class="lg:hidden p-2 text-content-secondary dark:text-dark-400 hover:bg-surface-secondary dark:hover:bg-dark-700 rounded-xl transition-colors" x-data @click="$dispatch('toggle-mobile-menu')">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div x-data="{ mobileMenuOpen: false }" @toggle-mobile-menu.window="mobileMenuOpen = !mobileMenuOpen" x-show="mobileMenuOpen"
        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4"
        class="lg:hidden bg-surface-primary dark:bg-dark-900 border-t border-border-subtle dark:border-dark-700 shadow-xl" style="display: none;">
        <div class="px-4 py-6 space-y-2">
            @foreach([
                'home' => ['label' => 'messages.nav.home', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                'shop' => ['label' => 'messages.nav.shop', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                'build-pc' => ['label' => 'messages.nav.build_pc', 'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                'compare' => ['label' => 'messages.nav.compare', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                'support' => ['label' => 'messages.nav.support', 'icon' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z']
            ] as $route => $item)
                <a href="{{ route($route) }}" wire:navigate class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium text-content-secondary dark:text-dark-400 hover:bg-surface-secondary dark:hover:bg-dark-800 hover:text-brand-base transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}" /></svg>
                    {{ __($item['label']) }}
                </a>
            @endforeach
            
            <div class="pt-4 mt-4 border-t border-border-subtle dark:border-dark-700 flex flex-col gap-4">
                <div class="flex items-center justify-between px-4">
                    <span class="text-sm font-medium text-content-secondary dark:text-dark-400">{{ __('messages.language') }}</span>
                    <livewire:language-switcher />
                </div>
                <!-- Mobile Theme Picker -->
                <div class="px-4" x-data="{ showThemes: false }">
                    <button @click="showThemes = !showThemes" class="flex items-center justify-between w-full text-sm font-medium text-content-secondary dark:text-dark-400">
                        <span>{{ __('messages.theme.label') }}</span>
                        <svg class="w-4 h-4 transition-transform" :class="showThemes ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="showThemes" x-collapse class="mt-2 grid grid-cols-4 gap-2">
                        <template x-for="t in $store.theme.themes" :key="t.key">
                            <button @click="$store.theme.setTheme(t.key)" class="flex flex-col items-center gap-1 p-2 rounded-xl transition-all" :class="$store.theme.current === t.key ? 'bg-surface-secondary dark:bg-dark-700' : 'hover:bg-surface-secondary/50 dark:hover:bg-dark-800'">
                                <span class="w-6 h-6 rounded-full border-2 shadow-sm" :style="'background:' + t.swatch + ';border-color:' + t.swatch"></span>
                                <span class="text-[9px] text-content-muted dark:text-dark-500 truncate w-full text-center" x-text="$store.theme.label(t)"></span>
                            </button>
                        </template>
                    </div>
                </div>
                
                @auth
                    <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium text-content-secondary dark:text-dark-400 hover:bg-surface-secondary dark:hover:bg-dark-800 hover:text-brand-base transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        {{ __('messages.user_orders.my_profile') }}
                    </a>
                    @if(auth()->user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium text-content-secondary dark:text-dark-400 hover:bg-surface-secondary dark:hover:bg-dark-800 hover:text-brand-base transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            {{ __('messages.admin.panel_title') }}
                        </a>
                    @endif
                    <div class="px-4">
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium text-content-secondary dark:text-dark-400 hover:bg-surface-secondary dark:hover:bg-dark-800 hover:text-brand-base transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013 3h4a3 3 0 013 3v1" /></svg>
                                {{ __('messages.logout') }}
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-medium text-content-secondary dark:text-dark-400 hover:bg-surface-secondary dark:hover:bg-dark-800 hover:text-brand-base transition-colors">{{ __('messages.login') }}</a>
                    <a href="{{ route('register') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl text-base font-bold text-brand-base bg-brand-base/10 hover:bg-brand-base/20 transition-colors">{{ __('messages.register') }}</a>
                @endauth
            </div>
    </div>
</nav>
