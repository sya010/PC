@props(['product', 'action' => null])

<div class="group bg-surface-primary dark:bg-dark-900 rounded-[2rem] border border-border-subtle dark:border-dark-700 p-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full relative overflow-hidden">
    
    <!-- Badge -->
    <div class="absolute top-5 left-5 z-20">
        <span class="px-3 py-1 bg-surface-primary/90 dark:bg-dark-800/90 backdrop-blur border border-border-subtle dark:border-dark-700 rounded-full text-[10px] font-bold text-content-secondary dark:text-dark-400 uppercase tracking-wider shadow-sm">
            {{ Str::limit($product->category, 10) }}
        </span>
    </div>

    @if($action)
        {{-- SELECTOR MODE: Clicking the card selects the component for the build --}}
        <button wire:click="{{ $action }}" class="absolute inset-0 z-10 cursor-pointer"></button>

        {{-- Selected indicator overlay --}}
        <div class="absolute top-5 right-5 z-20 opacity-0 group-hover:opacity-100 transition-opacity">
            <span class="px-3 py-1.5 bg-emerald-500 text-white rounded-full text-[10px] font-bold uppercase tracking-wider shadow-lg flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
                Click to Select
            </span>
        </div>
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
            class="w-3/4 h-3/4 object-contain mix-blend-multiply dark:mix-blend-normal transition-transform duration-500 group-hover:scale-110"
        >
    </div>

    <!-- Content -->
    <div class="flex-1 flex flex-col">
        <h3 class="font-bold text-content-primary dark:text-dark-100 text-lg leading-tight mb-auto line-clamp-2 group-hover:text-brand-base transition-colors">
            {{ $product->name }}
        </h3>

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
                    <button wire:click="addToCart({{ $product->id }})"
                        class="w-12 h-12 bg-dark-900 dark:bg-dark-800 hover:bg-brand-base text-white rounded-2xl flex items-center justify-center transition-all shadow-lg shadow-black/5 dark:shadow-black/20 hover:shadow-brand-base/30 active:scale-95 group/btn">
                        <svg class="w-5 h-5 group-hover/btn:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
