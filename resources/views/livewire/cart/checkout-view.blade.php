<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
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
                <button wire:click="placeOrder" class="btn-gradient btn-animate px-8 py-3 rounded-xl text-content-inverse font-bold shadow-lg hover:shadow-2xl active:scale-95 flex items-center gap-2">
                    <span wire:loading.remove>
                        {{ __('messages.checkout.place_order') }} ({{ number_format($total, 0) }} {{ __('messages.currency') }})
                    </span>
                    <span wire:loading class="flex items-center gap-2">
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
                    <div class="flex justify-between text-content-secondary dark:text-dark-300">
                        <span>{{ __('messages.cart_page.shipping') }}</span>
                        <span class="text-status-success">{{ __('messages.cart_page.free') }}</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg text-content-primary dark:text-dark-100 pt-2 border-t border-border-subtle dark:border-dark-800 mt-2">
                        <span>{{ __('messages.cart_page.total') }}</span>
                        <span class="text-brand-base">{{ number_format($total, 0) }} {{ __('messages.currency') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
