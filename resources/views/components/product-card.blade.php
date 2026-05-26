@props(['product', 'action' => null, 'compatibility' => null])

<div class="group bg-surface-primary dark:bg-dark-900 rounded-[2rem] border {{ ($compatibility && !$compatibility['compatible']) ? 'border-rose-500/30 bg-rose-500/[0.01]' : 'border-border-subtle dark:border-dark-700' }} p-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full relative overflow-hidden {{ ($compatibility && !$compatibility['compatible']) ? 'opacity-80 hover:opacity-100' : '' }}">
    
    <!-- Badge -->
    <div class="absolute top-5 left-5 z-20 flex flex-col gap-1.5">
        <span class="px-3 py-1 bg-surface-primary/90 dark:bg-dark-800/90 backdrop-blur border border-border-subtle dark:border-dark-700 rounded-full text-[10px] font-bold text-content-secondary dark:text-dark-400 uppercase tracking-wider shadow-sm">
            {{ Str::limit($product->category, 10) }}
        </span>
        @if(!$action && $product->stock <= 0)
            <span class="px-3 py-1 bg-rose-500/10 backdrop-blur border border-rose-500/20 rounded-full text-[10px] font-black text-rose-600 dark:text-rose-400 uppercase tracking-wider shadow-sm">
                {{ __('messages.product.out_of_stock') ?? 'Out of Stock' }}
            </span>
        @endif
    </div>

    @if($compatibility)
        <!-- Compatibility Status Badge -->
        <div class="absolute top-5 right-5 z-20">
            @if($compatibility['status'] === 'success')
                <span class="px-2.5 py-1 bg-emerald-500/10 dark:bg-emerald-500/20 border border-emerald-500/30 rounded-full text-[10px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider shadow-sm flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Compatible
                </span>
            @elseif($compatibility['status'] === 'warning')
                <span class="px-2.5 py-1 bg-amber-500/10 dark:bg-amber-500/20 border border-amber-500/30 rounded-full text-[10px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider shadow-sm flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                    Warning
                </span>
            @elseif($compatibility['status'] === 'error')
                <span class="px-2.5 py-1 bg-rose-500/10 dark:bg-rose-500/20 border border-rose-500/30 rounded-full text-[10px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider shadow-sm flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-pulse"></span>
                    Incompatible
                </span>
            @endif
        </div>
    @elseif($action)
        {{-- Selected indicator overlay --}}
        <div class="absolute top-5 right-5 z-20 opacity-0 group-hover:opacity-100 transition-opacity">
            <span class="px-3 py-1.5 bg-emerald-500 text-white rounded-full text-[10px] font-bold uppercase tracking-wider shadow-lg flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                Click to Select
            </span>
        </div>
    @endif

    @if($action)
        {{-- SELECTOR MODE: Clicking the card selects the component for the build --}}
        <button wire:click="{{ $action }}" class="absolute inset-0 z-10 cursor-pointer"></button>
    @else
        {{-- SHOP MODE: Clicking the card goes to product detail --}}
        <a href="{{ route('product.view', $product->id) }}" wire:navigate class="absolute inset-0 z-10 cursor-pointer"></a>
    @endif

    <!-- Image -->
    <div class="relative w-full aspect-[4/3] mb-4 flex items-center justify-center bg-surface-secondary/50 dark:bg-dark-800/50 rounded-3xl overflow-hidden group-hover:bg-surface-secondary dark:group-hover:bg-dark-800 transition-colors">
        <img 
            src="{{ $product->image_url }}" 
            alt="{{ $product->name }}" 
            loading="lazy" 
            class="w-3/4 h-3/4 object-contain mix-blend-multiply dark:mix-blend-normal transition-transform duration-500 group-hover:scale-110 {{ ($product->stock <= 0 && !$action) ? 'opacity-50' : '' }}"
        >
    </div>

    <!-- Content -->
    <div class="flex-1 flex flex-col">
        <h3 class="font-bold text-content-primary dark:text-dark-100 text-lg leading-tight mb-2 line-clamp-2 group-hover:text-brand-base transition-colors">
            {{ $product->name }}
        </h3>

        @if($compatibility && $compatibility['reason'] && $compatibility['reason'] !== 'Compatible with your build')
            <div class="mt-1 flex items-start gap-1.5 p-2.5 rounded-xl border {{ $compatibility['status'] === 'error' ? 'bg-rose-500/5 text-rose-600 dark:text-rose-400 border-rose-500/10' : 'bg-amber-500/5 text-amber-600 dark:text-amber-400 border-amber-500/10' }}">
                <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    @if($compatibility['status'] === 'error')
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    @else
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    @endif
                </svg>
                <p class="text-xs font-semibold leading-normal line-clamp-3">
                    {{ $compatibility['reason'] }}
                </p>
            </div>
        @endif

        <!-- Footer -->
        <div class="flex items-end justify-between mt-6">
            <div>
                <p class="text-[10px] font-bold text-content-muted dark:text-dark-500 uppercase tracking-wider mb-0.5">Price</p>
                <p class="text-xl font-black text-content-primary dark:text-dark-100 leading-none">
                    {{ number_format($product->price, 0) }} <span class="text-xs font-bold text-content-muted dark:text-dark-500">IQD</span>
                </p>
            </div>

            <!-- Action Button -->
            <div class="relative z-20">
                @if($action)
                    <a href="{{ route('product.view', $product->id) }}" wire:navigate
                        class="w-12 h-12 bg-dark-900 dark:bg-dark-800 hover:bg-brand-base text-white rounded-2xl flex items-center justify-center transition-all shadow-lg shadow-black/5 dark:shadow-black/20 hover:shadow-brand-base/30 active:scale-95 group/btn"
                        title="More Info">
                        <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </a>
                @else
                    @if($product->stock > 0)
                        <button wire:click="addToCart({{ $product->id }})"
                            class="w-12 h-12 bg-dark-900 dark:bg-dark-800 hover:bg-brand-base text-white rounded-2xl flex items-center justify-center transition-all shadow-lg shadow-black/5 dark:shadow-black/20 hover:shadow-brand-base/30 active:scale-95 group/btn">
                            <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                        </button>
                    @else
                        <span class="inline-flex items-center justify-center px-2 py-1.5 bg-rose-500/10 text-rose-500 text-[10px] font-bold rounded-lg border border-rose-500/20 select-none">
                            {{ __('messages.product.out_of_stock') ?? 'Out of Stock' }}
                        </span>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
