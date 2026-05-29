<div x-data="{ open: false }" @click.outside="open = false" class="relative">
    <!-- Cart Icon Button -->
    <button @click="open = !open" class="neu-button p-2.5 rounded-xl relative group hover:bg-brand-base/5 block transition-colors" aria-label="{{ __('messages.cart') }}">
        <svg class="w-5 h-5 text-content-secondary group-hover:text-brand-base transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <!-- Cart Count Badge -->
        @if($count > 0)
            <span class="absolute -top-1 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? '-left-1' : '-right-1' }} w-5 h-5 bg-brand-base text-white text-xs font-bold rounded-full flex items-center justify-center animate-bounce-short shadow-md">
                {{ $count }}
            </span>
        @endif
    </button>

    <!-- Mini Cart Modal -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left-0' : 'right-0' }} mt-3 w-80 bg-surface-primary dark:bg-dark-900 rounded-2xl shadow-2xl border border-border-subtle dark:border-dark-800 z-[110] overflow-hidden"
         style="display: none;">

        <!-- Header -->
        <div class="px-5 py-3.5 bg-surface-secondary/60 dark:bg-dark-950/60 border-b border-border-subtle dark:border-dark-800 flex items-center justify-between">
            <h3 class="text-sm font-bold text-content-primary dark:text-dark-100">{{ __('messages.cart') }}</h3>
            @if($count > 0)
                <span class="text-xs font-bold text-content-muted dark:text-dark-500">{{ $count }} {{ __('messages.cart_mini.items') }}</span>
            @endif
        </div>

        @if($count > 0)
            <!-- Cart Items -->
            <div class="max-h-64 overflow-y-auto custom-scrollbar divide-y divide-border-subtle dark:divide-dark-800">
                @foreach($cartItems as $id => $item)
                    <div class="px-5 py-3 flex items-center gap-3 hover:bg-surface-secondary/50 dark:hover:bg-dark-950/30 transition-colors">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-content-primary dark:text-dark-100 truncate">{{ $item['name'] }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <div class="flex items-center gap-2 bg-surface-primary dark:bg-dark-900 border border-border-subtle dark:border-dark-700 rounded-lg px-1.5 py-0.5">
                                    <button wire:click.prevent="decreaseQuantity({{ $id }})" class="text-content-muted hover:text-brand-base transition-colors p-0.5">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4" /></svg>
                                    </button>
                                    <span class="text-xs font-medium text-content-primary dark:text-white min-w-[12px] text-center">{{ $item['quantity'] }}</span>
                                    <button wire:click.prevent="increaseQuantity({{ $id }})" class="text-content-muted hover:text-brand-base transition-colors p-0.5">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                    </button>
                                </div>
                                <span class="text-xs font-medium text-content-muted dark:text-dark-500">×</span>
                                <span class="text-xs font-bold text-brand-base">{{ number_format($item['price'], 0) }} {{ __('messages.currency') }}</span>
                            </div>
                        </div>
                        <span class="text-sm font-bold text-content-primary dark:text-dark-200 whitespace-nowrap">{{ number_format($item['price'] * $item['quantity'], 0) }} {{ __('messages.currency') }}</span>
                    </div>
                @endforeach
            </div>

            <!-- Footer -->
            <div class="px-5 py-4 border-t border-border-subtle dark:border-dark-800 bg-surface-secondary/30 dark:bg-dark-950/30 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-bold text-content-secondary dark:text-dark-300">{{ __('messages.cart_mini.total') }}</span>
                    <span class="text-base font-black text-content-primary dark:text-dark-100">{{ number_format($total, 0) }} <span class="text-xs font-bold text-content-muted dark:text-dark-500">{{ __('messages.currency') }}</span></span>
                </div>
                <a href="{{ route('cart') }}" wire:navigate @click="open = false"
                   class="block w-full py-2.5 bg-brand-base hover:bg-brand-accent text-white text-sm font-bold rounded-xl text-center transition-all shadow-lg shadow-brand-base/20 hover:shadow-brand-base/30 active:scale-[0.98]">
                    {{ __('messages.cart_mini.view_cart') }}
                </a>
            </div>
        @else
            <!-- Empty State -->
            <div class="px-5 py-8 text-center">
                <div class="w-12 h-12 bg-surface-secondary dark:bg-dark-950 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-content-muted dark:text-dark-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <p class="text-sm font-medium text-content-muted dark:text-dark-500">{{ __('messages.cart_mini.empty') }}</p>
                <a href="{{ route('shop') }}" wire:navigate @click="open = false" class="inline-block mt-3 text-xs font-bold text-brand-base hover:text-brand-accent transition-colors">
                    {{ __('messages.cart_mini.browse_shop') }}
                </a>
            </div>
        @endif
    </div>
</div>
