<x-layouts.app>
    <!-- Support Hero -->
    <section class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-3xl lg:text-4xl font-bold mb-4">{{ __('messages.nav.support') }}</h1>
                <p class="text-content-secondary dark:text-dark-300 max-w-2xl mx-auto">
                    {{ __('messages.support_page.subtitle') }}
                </p>
            </div>

            <!-- Search Support -->
            <div class="max-w-2xl mx-auto mb-12">
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="{{ __('messages.support_page.search') }}"
                        class="input-neu w-full ps-12 pe-4 py-4 text-lg"
                    >
                    <svg class="absolute start-4 top-1/2 -translate-y-1/2 w-6 h-6 text-content-muted dark:text-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Support Options -->
            <div class="grid md:grid-cols-2 gap-6 mb-16 max-w-2xl mx-auto">
                <div class="neu-card p-6 text-center hover:scale-105 transition-transform cursor-pointer">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-brand-accent flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('messages.support_page.email_support') }}</h3>
                    <p class="text-sm text-content-muted dark:text-dark-400">{{ __('messages.support_page.email_support_desc') }}</p>
                </div>

                <div class="neu-card p-6 text-center hover:scale-105 transition-transform cursor-pointer">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-brand-base flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('messages.support_page.phone_support') }}</h3>
                    <p class="text-sm text-content-muted dark:text-dark-400">{{ __('messages.support_page.phone_support_desc') }}</p>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="max-w-3xl mx-auto">
                <h2 class="text-2xl font-bold text-center mb-8">{{ __('messages.support_page.faq') }}</h2>
                
                <div class="space-y-4">
                    @php
                        $faqs = [
                            ['q' => __('messages.support_page.faq_1'), 'a' => __('messages.support_page.faq_1_a')],
                            ['q' => __('messages.support_page.faq_2'), 'a' => __('messages.support_page.faq_2_a')],
                            ['q' => __('messages.support_page.faq_3'), 'a' => __('messages.support_page.faq_3_a')],
                            ['q' => __('messages.support_page.faq_4'), 'a' => __('messages.support_page.faq_4_a')],
                        ];
                    @endphp
 
                    @foreach($faqs as $faq)
                        <div class="neu-card p-4" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full flex items-center justify-between text-start">
                                <span class="font-semibold text-content-primary dark:text-dark-100">{{ $faq['q'] }}</span>
                                <svg class="w-5 h-5 text-content-muted dark:text-dark-500 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="mt-4 text-content-secondary dark:text-dark-300">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
