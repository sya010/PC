<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <h1 class="text-3xl font-bold mb-8 text-[var(--color-text-primary)]">{{ __('messages.cart') }}</h1>

    @if(count($cartItems) > 0)
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Cart Items List -->
            <div class="flex-1 space-y-4">
                @foreach($cartItems as $id => $item)
                    <div class="neu-card p-4 flex flex-col sm:flex-row gap-4 items-center">
                        <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="w-24 h-24 object-cover rounded-lg bg-gray-100">
                        
                        <div class="flex-1 text-center sm:text-left rtl:sm:text-right">
                            <h3 class="font-bold text-lg text-[var(--color-text-primary)]">{{ $item['name'] }}</h3>
                            <p class="text-sm text-[var(--color-text-secondary)] capitalize">{{ $item['category'] ?? __('messages.cart_page.product') }}</p>
                            <p class="font-bold text-indigo-600 mt-1">{{ number_format($item['price'], 0) }} {{ __('messages.currency') }}</p>
                        </div>

                        <!-- Quantity Controls -->
                        <div class="flex items-center gap-3">
                            <button 
                                wire:click="updateQuantity('{{ $id }}', {{ $item['quantity'] - 1 }})"
                                class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors text-gray-600"
                            >
                                -
                            </button>
                            <span class="font-medium text-[var(--color-text-primary)] w-8 text-center">{{ $item['quantity'] }}</span>
                            <button 
                                wire:click="updateQuantity('{{ $id }}', {{ $item['quantity'] + 1 }})"
                                class="w-8 h-8 rounded-full bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors text-gray-600"
                            >
                                +
                            </button>
                        </div>

                        <!-- Remove Button -->
                        <button 
                            wire:click="removeItem('{{ $id }}')"
                            class="p-2 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
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
                <div class="neu-card p-6 sticky top-24">
                    <h2 class="text-xl font-bold mb-6 text-[var(--color-text-primary)]">{{ __('messages.cart_page.order_summary') }}</h2>
                    
                    <div class="space-y-4 mb-6">
                        <div class="flex justify-between text-[var(--color-text-secondary)]">
                            <span>{{ __('messages.cart_page.subtotal') }}</span>
                            <span>{{ number_format($total + $discount, 0) }} {{ __('messages.currency') }}</span>
                        </div>
                        @if($discount > 0)
                            <div class="flex justify-between text-green-600 font-medium">
                                <span>{{ __('messages.cart_page.discount') }}</span>
                                <span>-{{ number_format($discount, 0) }} {{ __('messages.currency') }}</span>
                            </div>
                        @endif
                        <div class="flex flex-col gap-2 pt-2 pb-2">
                             <div class="flex gap-2">
                                <input type="text" wire:model="couponCode" placeholder="{{ __('messages.cart_page.promo_code') }}" class="flex-1 px-3 py-2 border border-gray-200 rounded-lg text-sm focus:outline-none focus:border-indigo-500">
                                <button wire:click="applyCoupon" class="px-3 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition-colors">{{ __('messages.cart_page.apply') }}</button>
                             </div>
                        </div>
                        <div class="flex justify-between text-[var(--color-text-secondary)]">
                            <span>{{ __('messages.cart_page.shipping') }}</span>
                            <span class="text-green-600">{{ __('messages.cart_page.free') }}</span>
                        </div>
                        <div class="border-t border-[var(--glass-border)] pt-4 flex justify-between font-bold text-lg text-[var(--color-text-primary)]">
                            <span>{{ __('messages.cart_page.total') }}</span>
                            <span class="text-indigo-600">{{ number_format($total, 0) }} {{ __('messages.currency') }}</span>
                        </div>
                    </div>

                    <a href="{{ route('delivery') }}" wire:navigate class="btn-gradient btn-animate w-full py-4 rounded-xl text-white font-bold text-lg shadow-xl hover:shadow-2xl active:scale-95 block text-center flex items-center justify-center gap-3">
                        <span>{{ __('messages.cart_page.proceed_delivery') }}</span>
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                    
                    <div class="mt-6 flex items-center justify-center gap-2 text-sm text-[var(--color-text-muted)]">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        {{ __('messages.cart_page.secure_checkout') }}
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-20 bg-[var(--color-bg-secondary)] rounded-3xl">
            <div class="inline-block p-6 bg-white rounded-full mb-6 shadow-sm">
                <svg class="w-16 h-16 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-[var(--color-text-primary)] mb-2">{{ __('messages.cart_page.empty_title') }}</h2>
            <p class="text-[var(--color-text-secondary)] mb-8">{{ __('messages.cart_page.empty_desc') }}</p>
            <a href="{{ route('shop') }}" class="inline-block px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-colors shadow-lg shadow-indigo-200">
                {{ __('messages.cart_page.start_shopping') }}
            </a>
        </div>
    @endif
</div>
