<div>
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-content-primary dark:text-dark-100 bg-clip-text text-transparent bg-gradient-to-r from-brand-base to-brand-light">
                {{ __('messages.admin.products.title') }}
            </h1>
            <p class="text-content-secondary dark:text-dark-400 mt-1">{{ __('messages.admin.products.subtitle') }}</p>
        </div>
        <a href="{{ route('admin.products.create') }}" wire:navigate class="flex items-center gap-2 px-6 py-3 bg-brand-accent text-white font-bold rounded-xl shadow-lg shadow-brand-base/20 hover:bg-brand-base hover:shadow-brand-base/30 transform hover:-translate-y-0.5 transition-all">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
            {{ __('messages.admin.products.add_product') }}
        </a>
    </div>

    <!-- Search & Filters -->
    <div class="mb-8">
        <div class="relative max-w-md">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="{{ __('messages.admin.products.search_placeholder') }}" 
                maxlength="100"
                class="w-full {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'pr-12 pl-4' : 'pl-12 pr-4' }} py-3.5 rounded-2xl border border-border-subtle dark:border-dark-700 bg-surface-primary/50 dark:bg-dark-800/50 text-content-primary dark:text-dark-100 placeholder-content-muted dark:placeholder-dark-500 focus:outline-none focus:ring-4 focus:ring-brand-base/10 focus:border-brand-base focus:bg-surface-primary dark:focus:bg-dark-700 transition-all shadow-sm hover:border-brand-light"
            >
            <div class="absolute inset-y-0 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right-0 pr-4' : 'left-0 pl-4' }} flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-content-muted dark:text-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <div class="absolute inset-y-0 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left-0 pl-4' : 'right-0 pr-4' }} flex items-center">
                <div wire:loading wire:target="search" class="animate-spin rounded-full h-5 w-5 border-b-2 border-brand-base"></div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
            <div class="group bg-surface-primary dark:bg-dark-800 rounded-2xl p-4 border border-border-subtle dark:border-dark-700 shadow-sm hover:shadow-xl hover:border-brand-base/20 transition-all duration-300 relative flex flex-col">
                <!-- Image -->
                <div class="relative aspect-[4/3] rounded-xl overflow-hidden mb-4 bg-surface-secondary dark:bg-dark-900 flex-shrink-0">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                    
                    <div class="absolute top-2 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left-2' : 'right-2' }}">
                        <span class="px-2 py-1 text-xs font-bold rounded-lg shadow-sm {{ $product->is_active ? 'bg-brand-base/20 text-brand-base dark:text-brand-light backdrop-blur-md' : 'bg-brand-accent/20 text-brand-accent dark:text-brand-light backdrop-blur-md' }}">
                            {{ $product->is_active ? __('messages.admin.products.active') : __('messages.admin.products.inactive') }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="space-y-2 flex-grow flex flex-col">
                    <div class="flex items-center justify-between text-xs font-bold text-content-muted dark:text-dark-500 uppercase tracking-wide">
                        <span>{{ $product->category }}</span>
                        <span>{{ __('messages.admin.products.stock') }}: {{ $product->stock }}</span>
                    </div>
                    
                    <h3 class="font-bold text-content-primary dark:text-dark-100 line-clamp-1 group-hover:text-brand-base transition-colors text-lg">
                        {{ $product->name }}
                    </h3>
                    
                    <div class="mt-auto pt-2 flex items-end justify-between">
                        <div class="text-xl font-bold text-content-primary dark:text-dark-100">
                            ${{ number_format($product->price, 2) }}
                        </div>
                    </div>
                </div>

                <!-- Actions Overlay (On Hover) -->
                <div class="absolute inset-x-4 bottom-4 pt-4 bg-surface-primary/95 dark:bg-dark-800/95 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-200 flex items-center justify-between gap-2 translate-y-2 group-hover:translate-y-0 shadow-[0_-10px_20px_rgba(0,0,0,0.1)] dark:shadow-[0_-10px_20px_rgba(0,0,0,0.3)] border-t border-border-subtle dark:border-dark-700 rounded-b-2xl">
                    <a href="{{ route('admin.products.edit', $product->id) }}" wire:navigate class="flex-1 py-2.5 text-center text-sm font-bold text-brand-base bg-brand-base/10 rounded-lg hover:bg-brand-base/20 transition-colors">
                        {{ __('messages.admin.products.edit') }}
                    </a>
                    <button wire:click="delete({{ $product->id }})" wire:confirm="{{ __('messages.admin.products.are_you_sure') }}" class="p-2.5 text-brand-accent dark:text-brand-light bg-brand-accent/20 dark:bg-brand-accent/30 rounded-lg hover:bg-brand-accent/30 dark:hover:bg-brand-accent/40 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                    </button>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-8 px-2">
        {{ $products->links() }}
    </div>
</div>
