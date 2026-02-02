<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Delivery Form -->
        <div class="flex-1">
            <h1 class="text-3xl font-bold mb-8 text-[var(--color-text-primary)]">Delivery Information</h1>
            
            <div class="neu-card p-8 bg-white/80 backdrop-blur-md">
                <form wire:submit="saveDeliveryInfo" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Full Name -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                            <input type="text" wire:model.blur="address.full_name" maxlength="255" class="input-neu w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                            @error('address.full_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" wire:model.blur="address.email" maxlength="255" class="input-neu w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                            @error('address.email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Phone -->
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Phone</label>
                            <input type="tel" wire:model.blur="address.phone" maxlength="20" class="input-neu w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                            @error('address.phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- Street Address -->
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Street Address</label>
                            <input type="text" wire:model.blur="address.street_address" maxlength="255" class="input-neu w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                            @error('address.street_address') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- City -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City</label>
                            <input type="text" wire:model.blur="address.city" maxlength="100" class="input-neu w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                            @error('address.city') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- State -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State / Province</label>
                            <input type="text" wire:model.blur="address.state" maxlength="100" class="input-neu w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                            @error('address.state') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- ZIP Code -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">ZIP / Postal Code</label>
                            <input type="text" wire:model.blur="address.zip_code" maxlength="20" class="input-neu w-full px-4 py-3 rounded-xl border border-gray-200 focus:ring-2 focus:ring-indigo-500/50 outline-none transition-all">
                            @error('address.zip_code') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <div class="mt-8 flex justify-between items-center">
                        <a href="{{ route('cart') }}" wire:navigate class="text-gray-600 hover:text-indigo-600 font-medium flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                            Back to Cart
                        </a>
                        <button type="submit" class="px-8 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-1">
                            Continue to Checkout
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Order Summary Side -->
        <div class="lg:w-96">
            <div class="neu-card p-6 sticky top-24">
                <h2 class="text-xl font-bold mb-6 text-[var(--color-text-primary)]">Order Summary</h2>
                
                <div class="space-y-4 mb-6 max-h-60 overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($cartItems as $item)
                        <div class="flex gap-4 text-sm">
                            <img src="{{ $item['image'] }}" class="w-12 h-12 rounded-lg object-cover bg-gray-100">
                            <div class="flex-1">
                                <p class="font-medium text-gray-900 line-clamp-1">{{ $item['name'] }}</p>
                                <p class="text-gray-500">Qty: {{ $item['quantity'] }}</p>
                            </div>
                            <p class="font-bold text-gray-900">${{ number_format($item['price'] * $item['quantity'], 2) }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-gray-100 pt-4 space-y-2">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Shipping</span>
                        <span class="text-green-600">Free</span>
                    </div>
                    <div class="flex justify-between font-bold text-lg text-gray-900 pt-2 border-t border-gray-100 mt-2">
                        <span>Total</span>
                        <span class="text-indigo-600">${{ number_format($total, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
