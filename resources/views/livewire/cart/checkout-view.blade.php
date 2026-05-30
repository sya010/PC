<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" x-data="{ showConfirm: false }">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Review & Payment -->
        <div class="flex-1 space-y-8">
            <h1 class="text-3xl font-bold text-content-primary dark:text-dark-100">{{ __('messages.checkout.review_pay') }}</h1>
            
            @if(session()->has('error'))
                <div class="p-4 mb-4 text-status-danger-strong bg-status-danger-soft rounded-xl flex items-center">
                    <svg class="w-5 h-5 me-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            <!-- Delivery Info Review -->
            <div class="neu-card p-6 bg-surface-primary/80 dark:bg-dark-900/80 backdrop-blur-md">
                <div class="flex justify-between items-start mb-4">
                    <h2 class="text-xl font-bold text-content-primary dark:text-dark-100">{{ __('messages.checkout.delivery_address') }}</h2>
                    <a href="{{ route('delivery') }}" wire:navigate class="text-brand-base hover:text-brand-accent text-sm font-medium">{{ __('messages.checkout.edit') }}</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-content-secondary dark:text-dark-300">
                    <div>
                        <p class="font-medium text-content-primary dark:text-dark-100">{{ $address['full_name'] ?? '' }}</p>
                        <p>{{ $address['street_address'] ?? '' }}</p>
                        <p>{{ $address['city'] ?? '' }}, {{ $address['state'] ?? '' }} {{ $address['zip_code'] ?? '' }}</p>
                    </div>
                    <div>
                        <p>{{ $address['email'] ?? '' }}</p>
                        <p>{{ $address['phone'] ?? '' }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="neu-card p-6 bg-surface-primary/80 dark:bg-dark-900/80 backdrop-blur-md">
                <h2 class="text-xl font-bold text-content-primary dark:text-dark-100 mb-4">{{ __('messages.checkout.payment_method') }}</h2>
                <div class="space-y-3">
                    <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-surface-secondary dark:hover:bg-dark-800 transition-colors {{ $paymentMethod === 'cod' ? 'border-brand-accent bg-brand-base/5' : 'border-border-subtle dark:border-dark-700' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="cod" class="h-4 w-4 text-brand-base focus:ring-brand-accent border-border-subtle dark:border-dark-600">
                        <div class="ms-4">
                            <span class="block text-sm font-medium text-content-primary dark:text-dark-100">{{ __('messages.checkout.cod') }}</span>
                            <span class="block text-sm text-content-muted dark:text-dark-500">{{ __('messages.checkout.cod_desc') }}</span>
                        </div>
                    </label>

                    <label class="flex items-center p-4 border rounded-xl cursor-pointer hover:bg-surface-secondary dark:hover:bg-dark-800 transition-colors {{ $paymentMethod === 'wayl' ? 'border-brand-accent bg-brand-base/5' : 'border-border-subtle dark:border-dark-700' }}">
                        <input type="radio" wire:model.live="paymentMethod" value="wayl" class="h-4 w-4 text-brand-base focus:ring-brand-accent border-border-subtle dark:border-dark-600">
                        <div class="ms-4">
                            <span class="block text-sm font-medium text-content-primary dark:text-dark-100">{{ __('messages.checkout.online') }}</span>
                            <span class="block text-sm text-content-muted dark:text-dark-500">{{ __('messages.checkout.online_desc') }}</span>
                        </div>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-between items-center">
                 <a href="{{ route('delivery') }}" wire:navigate class="text-content-secondary dark:text-dark-300 hover:text-brand-base font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    {{ __('messages.checkout.back_delivery') }}
                </a>
                <button @click="showConfirm = true" type="button" class="btn-gradient btn-animate px-8 py-3 rounded-xl text-content-inverse font-bold shadow-lg hover:shadow-2xl active:scale-95 flex items-center gap-2">
                    <span wire:loading.remove wire:target="placeOrder">
                        {{ __('messages.checkout.place_order') }} ({{ number_format($total, 0) }} {{ __('messages.currency') }})
                    </span>
                    <span wire:loading wire:target="placeOrder" class="flex items-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-content-inverse" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        {{ __('messages.checkout.processing') }}
                    </span>
                </button>
            </div>
        </div>

        <!-- Order Summary Side -->
        <div class="lg:w-96">
            <div class="neu-card p-6 sticky top-24">
                <h2 class="text-xl font-bold mb-6 text-content-primary dark:text-dark-100">{{ __('messages.cart_page.order_summary') }}</h2>
                
                <div class="space-y-4 mb-6 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($cartItems as $item)
                        <div class="flex gap-4 text-sm">
                            <img src="{{ $item['image'] }}" class="w-12 h-12 rounded-lg object-cover bg-surface-secondary dark:bg-dark-950">
                            <div class="flex-1">
                                <p class="font-medium text-content-primary dark:text-dark-100 line-clamp-1">{{ $item['name'] }}</p>
                                <p class="text-content-muted dark:text-dark-500">{{ __('messages.checkout.qty') }}: {{ $item['quantity'] }}</p>
                            </div>
                            <p class="font-bold text-content-primary dark:text-dark-100">{{ number_format($item['price'] * $item['quantity'], 0) }} {{ __('messages.currency') }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-border-subtle dark:border-dark-800 pt-4 space-y-2">
                    <div class="flex justify-between text-content-secondary dark:text-dark-300">
                        <span>{{ __('messages.cart_page.subtotal') }}</span>
                        <span>{{ number_format($total, 0) }} {{ __('messages.currency') }}</span>
                    </div>

                    <div class="flex justify-between font-bold text-lg text-content-primary dark:text-dark-100 pt-2 border-t border-border-subtle dark:border-dark-800 mt-2">
                        <span>{{ __('messages.cart_page.total') }}</span>
                        <span class="text-brand-base">{{ number_format($total, 0) }} {{ __('messages.currency') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Beautiful Confirmation Modal -->
    <div x-show="showConfirm" 
         class="fixed inset-0 z-50 overflow-y-auto"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         style="display: none;">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/60 dark:bg-black/80 backdrop-blur-sm transition-opacity" @click="showConfirm = false"></div>

        <!-- Modal Wrapper -->
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div x-show="showConfirm"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative transform overflow-hidden rounded-3xl bg-surface-primary dark:bg-dark-900 p-6 sm:p-8 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }} shadow-2xl border border-border-subtle dark:border-dark-800 transition-all max-w-lg w-full">
                
                <!-- Close Button -->
                <button type="button" @click="showConfirm = false" class="absolute top-4 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left-4' : 'right-4' }} text-content-muted dark:text-dark-400 hover:text-content-primary dark:hover:text-dark-100 transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Warning Content -->
                <div class="flex flex-col items-center text-center">
                    <!-- Elegant Glowing Orange/Amber Shield Icon -->
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-amber-500/10 dark:bg-amber-500/20 text-amber-500 mb-6 shadow-inner ring-1 ring-amber-500/20">
                        <svg class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>

                    <h3 class="text-2xl font-extrabold text-content-primary dark:text-dark-100 tracking-tight mb-3">
                        {{ __('messages.checkout.confirm_title') }}
                    </h3>
                    
                    <p class="text-content-secondary dark:text-dark-300 text-sm leading-relaxed max-w-sm mb-8">
                        {{ __('messages.checkout.confirm_message') }}
                    </p>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 w-full justify-center">
                        <button type="button" @click="showConfirm = false" class="w-full sm:w-auto px-6 py-3 rounded-xl font-bold border border-border-subtle dark:border-dark-700 text-content-primary dark:text-dark-300 hover:bg-surface-secondary dark:hover:bg-dark-800 transition-all">
                            {{ __('messages.checkout.confirm_no') }}
                        </button>
                        <button type="button" @click="showConfirm = false; $wire.placeOrder()" class="w-full sm:w-auto px-8 py-3 rounded-xl font-bold bg-brand-base hover:bg-brand-accent text-white shadow-lg shadow-brand-base/20 hover:shadow-brand-accent/30 hover:-translate-y-0.5 active:translate-y-0 transition-all">
                            {{ __('messages.checkout.confirm_yes') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
