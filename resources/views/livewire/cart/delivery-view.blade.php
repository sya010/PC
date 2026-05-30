<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Delivery Form -->
        <div class="flex-1">
            <h1 class="text-3xl font-bold mb-8 text-content-primary dark:text-dark-100">{{ __('messages.delivery.title') }}</h1>
            
            <div class="neu-card p-8 bg-surface-primary/80 dark:bg-dark-900/80 backdrop-blur-md">
                <form wire:submit="saveDeliveryInfo" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-content-secondary dark:text-dark-300 mb-1">{{ __('messages.delivery.full_name') }}</label>
                            <input type="text" wire:model.blur="address.full_name" maxlength="255" class="input-neu w-full px-4 py-3 rounded-xl border border-border-subtle dark:border-dark-700 focus:ring-2 focus:ring-brand-accent/50 outline-none transition-all">
                            @error('address.full_name') <span class="text-status-danger text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-medium text-content-secondary dark:text-dark-300 mb-1">{{ __('messages.delivery.email') }}</label>
                            <input type="email" wire:model.blur="address.email" maxlength="255" class="input-neu w-full px-4 py-3 rounded-xl border border-border-subtle dark:border-dark-700 focus:ring-2 focus:ring-brand-accent/50 outline-none transition-all">
                            @error('address.email') <span class="text-status-danger text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Phone -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-medium text-content-secondary dark:text-dark-300 mb-1">{{ __('messages.delivery.phone') }}</label>
                            <input type="tel" wire:model.blur="address.phone" maxlength="20" class="input-neu w-full px-4 py-3 rounded-xl border border-border-subtle dark:border-dark-700 focus:ring-2 focus:ring-brand-accent/50 outline-none transition-all">
                            @error('address.phone') <span class="text-status-danger text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Street Address -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-content-secondary dark:text-dark-300 mb-1">{{ __('messages.delivery.street') }}</label>
                            <input type="text" wire:model.blur="address.street_address" maxlength="255" class="input-neu w-full px-4 py-3 rounded-xl border border-border-subtle dark:border-dark-700 focus:ring-2 focus:ring-brand-accent/50 outline-none transition-all">
                            @error('address.street_address') <span class="text-status-danger text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label class="block text-sm font-medium text-content-secondary dark:text-dark-300 mb-1">{{ __('messages.delivery.city') }}</label>
                            <input type="text" wire:model.blur="address.city" maxlength="100" class="input-neu w-full px-4 py-3 rounded-xl border border-border-subtle dark:border-dark-700 focus:ring-2 focus:ring-brand-accent/50 outline-none transition-all">
                            @error('address.city') <span class="text-status-danger text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- State -->
                        <div>
                            <label class="block text-sm font-medium text-content-secondary dark:text-dark-300 mb-1">{{ __('messages.delivery.state') }}</label>
                            <input type="text" wire:model.blur="address.state" maxlength="100" class="input-neu w-full px-4 py-3 rounded-xl border border-border-subtle dark:border-dark-700 focus:ring-2 focus:ring-brand-accent/50 outline-none transition-all">
                            @error('address.state') <span class="text-status-danger text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- ZIP Code -->
                        <div>
                            <label class="block text-sm font-medium text-content-secondary dark:text-dark-300 mb-1">{{ __('messages.delivery.zip') }}</label>
                            <input type="text" wire:model.blur="address.zip_code" maxlength="20" class="input-neu w-full px-4 py-3 rounded-xl border border-border-subtle dark:border-dark-700 focus:ring-2 focus:ring-brand-accent/50 outline-none transition-all">
                            @error('address.zip_code') <span class="text-status-danger text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between items-center">
                        <a href="{{ route('cart') }}" wire:navigate class="text-content-secondary dark:text-dark-300 hover:text-brand-base font-medium flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            {{ __('messages.delivery.back_to_cart') }}
                        </a>
                        <button type="submit" class="px-8 py-3 bg-brand-base hover:bg-brand-accent text-content-inverse font-bold rounded-xl shadow-lg shadow-brand-base/20 transition-all transform hover:-translate-y-1">
                            {{ __('messages.delivery.continue') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Summary Side -->
        <div class="lg:w-96">
            <div class="neu-card p-6 sticky top-24">
                <h2 class="text-xl font-bold mb-6 text-content-primary dark:text-dark-100">{{ __('messages.delivery.order_summary') }}</h2>
                
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
</div>
