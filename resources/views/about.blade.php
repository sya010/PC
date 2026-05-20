<x-layouts.app>
    <!-- About Hero -->
    <section class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-3xl lg:text-4xl font-bold mb-4">{{ __('messages.about.title') }}</h1>
                <p class="text-content-secondary dark:text-dark-300 max-w-2xl mx-auto">
                    {{ __('messages.about.subtitle') }}
                </p>
            </div>

            <!-- Story Section -->
            <div class="grid lg:grid-cols-2 gap-12 items-center mb-16">
                <div>
                    <h2 class="text-2xl font-bold mb-4">{{ __('messages.about.story_title') }}</h2>
                    <p class="text-content-secondary dark:text-dark-300 mb-4">
                        {{ __('messages.about.story_p1') }}
                    </p>
                    <p class="text-content-secondary dark:text-dark-300">
                        {{ __('messages.about.story_p2') }}
                    </p>
                </div>
                <div class="neu-card p-8 flex items-center justify-center">
                    <div class="w-32 h-32 rounded-3xl bg-brand-base flex items-center justify-center">
                        <svg class="w-16 h-16 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Values -->
            <div class="grid md:grid-cols-3 gap-6 mb-16">
                <div class="neu-card p-6 text-center">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-xl bg-brand-base flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('messages.about.value1_title') }}</h3>
                    <p class="text-sm text-content-muted dark:text-dark-400">{{ __('messages.about.value1_desc') }}</p>
                </div>

                <div class="neu-card p-6 text-center">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-xl bg-brand-accent flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('messages.about.value2_title') }}</h3>
                    <p class="text-sm text-content-muted dark:text-dark-400">{{ __('messages.about.value2_desc') }}</p>
                </div>

                <div class="neu-card p-6 text-center">
                    <div class="w-14 h-14 mx-auto mb-4 rounded-xl bg-brand-base flex items-center justify-center">
                        <svg class="w-7 h-7 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('messages.about.value3_title') }}</h3>
                    <p class="text-sm text-content-muted dark:text-dark-400">{{ __('messages.about.value3_desc') }}</p>
                </div>
            </div>

            <!-- Stats -->
            <div class="neu-card p-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
                    <div>
                        <div class="text-3xl font-bold text-brand-light mb-1">5,000+</div>
                        <div class="text-sm text-content-muted dark:text-dark-400">{{ __('messages.about.stat1') }}</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-brand-light mb-1">1,000+</div>
                        <div class="text-sm text-content-muted dark:text-dark-400">{{ __('messages.about.stat2') }}</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-brand-light mb-1">50+</div>
                        <div class="text-sm text-content-muted dark:text-dark-400">{{ __('messages.about.stat3') }}</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold text-brand-light mb-1">24/7</div>
                        <div class="text-sm text-content-muted dark:text-dark-400">{{ __('messages.about.stat4') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
