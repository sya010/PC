<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold mb-8 text-content-primary dark:text-dark-100">{{ __('messages.cart') }}</h1>

    @if(count($cartItems) > 0)
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items List -->
            <div class="flex-1 space-y-4">
                @foreach($cartItems as $id => $item)
                    <div class="neu-card bg-surface-primary dark:bg-dark-900 border border-border-subtle dark:border-dark-800 p-4 flex flex-col sm:flex-row gap-4 items-center rounded-2xl">
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-24 h-24 object-cover rounded-lg bg-surface-secondary dark:bg-dark-950">
                        
                        <div class="flex-1 text-center sm:text-left rtl:sm:text-right">
                            <h3 class="font-bold text-lg text-content-primary dark:text-dark-100">{{ $item['name'] }}</h3>
                            <p class="text-sm text-content-secondary dark:text-dark-300 capitalize">{{ $item['category'] ?? __('messages.cart_page.product') }}</p>
                            <p class="font-bold text-brand-base mt-1">{{ number_format($item['price'], 0) }} {{ __('messages.currency') }}</p>
                        </div>

                        <!-- Quantity Controls -->
                        <div class="flex items-center gap-3">
                            <button 
                                wire:click="updateQuantity('{{ $id }}', {{ $item['quantity'] - 1 }})"
                                class="w-8 h-8 rounded-full bg-surface-secondary hover:bg-surface-secondary/80 dark:bg-dark-800 dark:hover:bg-dark-700 flex items-center justify-center transition-colors text-content-secondary dark:text-dark-300 font-bold"
                            >
                                -
                            </button>
                            <span class="font-medium text-content-primary dark:text-dark-100 w-8 text-center">{{ $item['quantity'] }}</span>
                            <button 
                                wire:click="updateQuantity('{{ $id }}', {{ $item['quantity'] + 1 }})"
                                class="w-8 h-8 rounded-full bg-surface-secondary hover:bg-surface-secondary/80 dark:bg-dark-800 dark:hover:bg-dark-700 flex items-center justify-center transition-colors text-content-secondary dark:text-dark-300 font-bold"
                            >
                                +
                            </button>
                        </div>

                        <!-- Remove Button -->
                        <button 
                            wire:click="removeItem('{{ $id }}')"
                            class="p-2 text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-500/10 rounded-lg transition-colors"
                            title="Remove"
                        >
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                @endforeach
            </div>

            <!-- Summary -->
            <div class="lg:w-96">
                <div class="neu-card bg-surface-primary dark:bg-dark-900 border border-border-subtle dark:border-dark-800 p-6 sticky top-24 rounded-2xl">
                    <h2 class="text-xl font-bold mb-6 text-content-primary dark:text-dark-100">{{ __('messages.cart_page.order_summary') }}</h2>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-content-secondary dark:text-dark-300">
                            <span>{{ __('messages.cart_page.subtotal') }}</span>
                            <span>{{ number_format($total + $discount, 0) }} {{ __('messages.currency') }}</span>
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between text-emerald-600 dark:text-emerald-400 font-medium">
                                <span>{{ __('messages.cart_page.discount') }}</span>
                                <span>-{{ number_format($discount, 0) }} {{ __('messages.currency') }}</span>
                            </div>
                        @endif
                        <div class="flex flex-col gap-2 pt-2 pb-2">
                             <div class="flex gap-2">
                                <input type="text" wire:model="couponCode" placeholder="{{ __('messages.cart_page.promo_code') }}" class="flex-1 px-3 py-2 border border-border-subtle dark:border-dark-700 rounded-lg text-sm bg-surface-secondary dark:bg-dark-950 text-content-primary dark:text-dark-100 focus:outline-none focus:border-brand-accent">
                                <button wire:click="applyCoupon" class="px-3 py-2 bg-dark-950 dark:bg-dark-800 text-white text-sm font-medium rounded-lg hover:bg-dark-900 dark:hover:bg-dark-700 transition-colors">{{ __('messages.cart_page.apply') }}</button>
                             </div>
                        </div>
                        <div class="flex justify-between text-content-secondary dark:text-dark-300">
                            <span>{{ __('messages.cart_page.shipping') }}</span>
                            <span class="text-emerald-600 dark:text-emerald-400">{{ __('messages.cart_page.free') }}</span>
                        </div>
                        <div class="border-t border-border-subtle dark:border-dark-800 pt-4 flex justify-between font-bold text-lg text-content-primary dark:text-dark-100">
                            <span>{{ __('messages.cart_page.total') }}</span>
                            <span class="text-brand-base">{{ number_format($total, 0) }} {{ __('messages.currency') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('delivery') }}" wire:navigate class="btn-gradient btn-animate w-full py-4 rounded-xl text-white font-bold text-lg shadow-xl hover:shadow-2xl active:scale-95 block text-center flex items-center justify-center gap-3">
                        <span>{{ __('messages.cart_page.proceed_delivery') }}</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                    
                    <div class="mt-6 flex items-center justify-center gap-2 text-sm text-content-muted dark:text-dark-500">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        {{ __('messages.cart_page.secure_checkout') }}
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-20 bg-surface-secondary dark:bg-dark-950 rounded-3xl">
            <div class="inline-block p-6 bg-surface-primary dark:bg-dark-900 border border-border-subtle dark:border-dark-800 rounded-full mb-6 shadow-sm">
                <svg class="w-16 h-16 text-brand-base/30" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-content-primary dark:text-dark-100 mb-2">{{ __('messages.cart_page.empty_title') }}</h2>
            <p class="text-content-secondary dark:text-dark-300 mb-8">{{ __('messages.cart_page.empty_desc') }}</p>
            <a href="{{ route('shop') }}" class="inline-block px-8 py-3 bg-brand-base text-white font-bold rounded-xl hover:bg-brand-accent transition-colors shadow-lg shadow-brand-base/20">
                {{ __('messages.cart_page.start_shopping') }}
            </a>
        </div>
    @endif
</div>
