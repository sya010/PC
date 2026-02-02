<x-layouts.app>
    <!-- Support Hero -->
    <section class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-3xl lg:text-4xl font-bold mb-4">{{ __('messages.nav.support') }}</h1>
                <p class="text-[var(--color-text-secondary)] max-w-2xl mx-auto">
                    {{ __('messages.support.subtitle') }}
                </p>
            </div>

            <!-- Search Support -->
            <div class="max-w-2xl mx-auto mb-12">
                <div class="relative">
                    <input 
                        type="text" 
                        placeholder="{{ __('messages.support.search_help') }}"
                        class="input-neu w-full pl-12 pr-4 py-4 text-lg"
                    >
                    <svg class="absolute {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right-4' : 'left-4' }} top-1/2 -translate-y-1/2 w-6 h-6 text-[var(--color-text-muted)]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <!-- Support Options -->
            <div class="grid md:grid-cols-3 gap-6 mb-16">
                <div class="neu-card p-6 text-center hover:scale-105 transition-transform cursor-pointer">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-indigo-600 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('messages.support.live_chat') }}</h3>
                    <p class="text-sm text-[var(--color-text-muted)]">{{ __('messages.support.chat_description') }}</p>
                </div>

                <div class="neu-card p-6 text-center hover:scale-105 transition-transform cursor-pointer">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-purple-600 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('messages.support.email') }}</h3>
                    <p class="text-sm text-[var(--color-text-muted)]">{{ __('messages.support.email_description') }}</p>
                </div>

                <div class="neu-card p-6 text-center hover:scale-105 transition-transform cursor-pointer">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-emerald-600 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">{{ __('messages.support.phone') }}</h3>
                    <p class="text-sm text-[var(--color-text-muted)]">{{ __('messages.support.phone_description') }}</p>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="max-w-3xl mx-auto">
                <h2 class="text-2xl font-bold text-center mb-8">{{ __('messages.support.faq') }}</h2>
                
                <div class="space-y-4">
                    @php
                        $faqs = [
                            ['q' => 'How do I track my order?', 'a' => 'You can track your order using the tracking number sent to your email after shipping.'],
                            ['q' => 'What is your return policy?', 'a' => 'We offer a 14-day return policy for unopened items in original packaging.'],
                            ['q' => 'Do you offer warranty?', 'a' => 'Yes, all products come with manufacturer warranty. Extended warranty is available.'],
                            ['q' => 'How long does delivery take?', 'a' => 'Delivery within Iraq typically takes 2-5 business days depending on your location.'],
                        ];
                    @endphp

                    @foreach($faqs as $faq)
                        <div class="neu-card p-4" x-data="{ open: false }">
                            <button @click="open = !open" class="w-full flex items-center justify-between text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }}">
                                <span class="font-semibold text-[var(--color-text-primary)]">{{ $faq['q'] }}</span>
                                <svg class="w-5 h-5 text-[var(--color-text-muted)] transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="open" x-collapse class="mt-4 text-[var(--color-text-secondary)]">
                                {{ $faq['a'] }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
