<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Top Filter Bar -->
    <div class="bg-surface-primary/95 dark:bg-dark-900/95 backdrop-blur-xl rounded-2xl shadow-lg border border-border-subtle dark:border-dark-800 p-6 mb-8 relative z-30">
        <div class="flex flex-col lg:flex-row lg:items-center gap-6 justify-between">
            
            <!-- Left Side: Search & Filter Dropdowns -->
            <div class="flex flex-col lg:flex-row gap-4 flex-1">
                
                <!-- Search -->
                <div class="relative w-full lg:w-72">
                    <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-content-muted dark:text-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <input 
                        wire:model.live.debounce.300ms="search" 
                        type="text" 
                        class="block w-full ps-11 pe-4 py-3 border border-border-subtle dark:border-dark-700 rounded-xl text-sm bg-surface-secondary dark:bg-dark-950 focus:bg-surface-primary dark:focus:bg-dark-900 focus:outline-none focus:ring-2 focus:ring-brand-accent/20 focus:border-brand-accent transition-all font-medium text-content-primary dark:text-dark-100 placeholder-content-muted dark:placeholder-dark-500" 
                        placeholder="{{ __('messages.shop.search_product') }}"
                    >
                </div>

                <div class="flex flex-nowrap items-center gap-3 overflow-x-auto pb-2 custom-scrollbar">
                    
                    <!-- Category Dropdown -->
                    <div x-data="{ open: false }" class="relative" @click.outside="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 px-5 py-3 bg-surface-primary hover:bg-surface-secondary dark:bg-dark-900 dark:hover:bg-dark-800 border border-border-subtle dark:border-dark-700 hover:border-brand-base/30 rounded-xl text-sm font-semibold text-content-primary dark:text-dark-200 transition-all shadow-sm">
                            <svg class="w-4 h-4 text-content-muted dark:text-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <span>{{ $category ? ($categories[$category] ?? $category) : __('messages.shop.all_categories') }}</span>
                            <svg class="w-3 h-3 text-content-muted dark:text-dark-500 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute start-0 mt-2 w-64 bg-surface-primary dark:bg-dark-900 rounded-2xl shadow-xl border border-border-subtle dark:border-dark-800 z-[100] py-2 max-h-80 overflow-y-auto custom-scrollbar"
                             style="display: none;">
                            
                            <div class="px-4 py-2 border-b border-border-subtle dark:border-dark-800 mb-1">
                                <span class="text-xs font-bold text-content-muted dark:text-dark-500 uppercase tracking-wider">{{ __('messages.shop.select_category') }}</span>
                            </div>

                            <button wire:click="$set('category', null); open = false" class="w-full text-start px-4 py-2.5 text-sm font-medium hover:bg-brand-base/5 dark:hover:bg-brand-base/10 hover:text-brand-base dark:hover:text-brand-accent transition-colors {{ is_null($category) ? 'bg-brand-base/5 dark:bg-brand-base/10 text-brand-accent' : 'text-content-secondary dark:text-dark-300' }}">
                                {{ __('messages.shop.all_categories') }}
                            </button>
                            
                            @foreach($categories as $key => $label)
                                <button wire:click="$set('category', '{{ $key }}'); open = false" class="w-full text-start px-4 py-2.5 text-sm font-medium hover:bg-brand-base/5 dark:hover:bg-brand-base/10 hover:text-brand-base dark:hover:text-brand-accent transition-colors {{ $category === $key ? 'bg-brand-base/5 dark:bg-brand-base/10 text-brand-accent' : 'text-content-secondary dark:text-dark-300' }}">
                                    <div class="flex items-center justify-between">
                                        <span>{{ $label }}</span>
                                        <span class="text-xs font-bold {{ $category === $key ? 'text-brand-accent bg-brand-base/10' : 'text-content-muted dark:text-dark-400 bg-surface-secondary dark:bg-dark-800' }} px-2 py-0.5 rounded-full">{{ $categoryCounts[$key] ?? 0 }}</span>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Brand Dropdown -->
                    <div x-data="{ open: false }" class="relative" @click.outside="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 px-5 py-3 bg-surface-primary hover:bg-surface-secondary dark:bg-dark-900 dark:hover:bg-dark-800 border border-border-subtle dark:border-dark-700 hover:border-brand-base/30 rounded-xl text-sm font-semibold text-content-primary dark:text-dark-200 transition-all shadow-sm">
                            <svg class="w-4 h-4 text-content-muted dark:text-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            <span>{{ __('messages.shop.brands') }} {{ count($selectedBrands) > 0 ? '('.count($selectedBrands).')' : '' }}</span>
                            <svg class="w-3 h-3 text-content-muted dark:text-dark-500 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute start-0 mt-2 w-72 bg-surface-primary dark:bg-dark-900 rounded-2xl shadow-xl border border-border-subtle dark:border-dark-800 z-[100] p-5"
                             style="display: none;">
                            
                            <div class="mb-4 flex items-center justify-between">
                                <span class="text-xs font-bold text-content-muted dark:text-dark-500 uppercase tracking-wider">{{ __('messages.shop.filter_by_brand') }}</span>
                                @if(count($selectedBrands) > 0)
                                    <button wire:click="$set('selectedBrands', [])" class="text-xs font-bold text-brand-base hover:text-brand-accent dark:text-brand-accent dark:hover:text-brand-light">{{ __('messages.shop.reset') }}</button>
                                @endif
                            </div>

                            <div class="max-h-60 overflow-y-auto custom-scrollbar space-y-1">
                                @foreach(['ASUS', 'MSI', 'Gigabyte', 'Corsair', 'Samsung', 'Intel', 'AMD', 'NVIDIA', 'Logitech', 'Razer', 'HyperX', 'SteelSeries', 'Noctua', 'NZXT', 'Elgato', 'Audio-Technica', 'Shure', 'LG', 'BenQ', 'Keychron'] as $brand)
                                    <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-surface-secondary dark:hover:bg-dark-800 cursor-pointer transition-colors group">
                                        <div class="relative flex items-center">
                                            <input type="checkbox" wire:model.live="selectedBrands" value="{{ $brand }}" 
                                                class="peer h-4 w-4 rounded border-border-subtle dark:border-dark-600 text-brand-base focus:ring-brand-accent/20 transition-all cursor-pointer bg-surface-primary dark:bg-dark-950">
                                        </div>
                                        <span class="text-sm font-medium text-content-secondary dark:text-dark-300 group-hover:text-content-primary dark:group-hover:text-dark-100">{{ $brand }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Price Dropdown -->
                    <div x-data="{ open: false }" class="relative" @click.outside="open = false">
                        <button @click="open = !open" class="flex items-center gap-2 px-5 py-3 bg-surface-primary hover:bg-surface-secondary dark:bg-dark-900 dark:hover:bg-dark-800 border border-border-subtle dark:border-dark-700 hover:border-brand-base/30 rounded-xl text-sm font-semibold text-content-primary dark:text-dark-200 transition-all shadow-sm">
                            <svg class="w-4 h-4 text-content-muted dark:text-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>{{ __('messages.shop.price_range') }}</span>
                            <svg class="w-3 h-3 text-content-muted dark:text-dark-500 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="transform opacity-0 scale-95"
                             x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="transform opacity-100 scale-100"
                             x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute start-0 mt-2 w-72 bg-surface-primary dark:bg-dark-900 rounded-2xl shadow-xl border border-border-subtle dark:border-dark-800 z-[100] p-6"
                             style="display: none;">
                            
                            <div class="mb-4 flex items-center justify-between">
                                <span class="text-xs font-bold text-content-muted dark:text-dark-500 uppercase tracking-wider">{{ __('messages.shop.set_price_budget') }}</span>
                                @if($minPrice > 0 || $maxPrice < 10000000)
                                    <button wire:click="$set('minPrice', 0); $set('maxPrice', 10000000)" class="text-xs font-bold text-brand-base hover:text-brand-accent dark:text-brand-accent dark:hover:text-brand-light">{{ __('messages.shop.reset') }}</button>
                                @endif
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="text-[10px] uppercase font-bold text-content-muted dark:text-dark-500 mb-1.5 block">{{ __('messages.shop.minimum_iqd') }}</label>
                                    <x-input-iqd wire:model.live.debounce.500ms="minPrice" placeholder="0" class="w-full text-sm py-2.5 border-border-subtle dark:border-dark-700 rounded-lg focus:ring-brand-accent/20" />
                                </div>
                                <div>
                                    <label class="text-[10px] uppercase font-bold text-content-muted dark:text-dark-500 mb-1.5 block">{{ __('messages.shop.maximum_iqd') }}</label>
                                    <x-input-iqd wire:model.live.debounce.500ms="maxPrice" placeholder="{{ __('messages.shop.any') }}" class="w-full text-sm py-2.5 border-border-subtle dark:border-dark-700 rounded-lg focus:ring-brand-accent/20" />
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Right Side: Sort & View Toggle -->
            <div class="flex items-center gap-3 w-full lg:w-auto mt-4 lg:mt-0 pt-4 lg:pt-0 border-t lg:border-none border-border-subtle dark:border-dark-800 px-1 lg:px-0">
               
               <div x-data="{ open: false }" class="relative w-full lg:w-auto" @click.outside="open = false">
                   <button @click="open = !open" class="w-full lg:w-auto flex items-center justify-between gap-3 px-5 py-3 bg-surface-primary hover:bg-surface-secondary dark:bg-dark-900 dark:hover:bg-dark-800 border border-border-subtle dark:border-dark-700 hover:border-brand-base/30 rounded-xl text-sm font-semibold text-content-primary dark:text-dark-200 transition-all shadow-sm">
                       <span class="flex items-center gap-2">
                           <svg class="w-4 h-4 text-content-muted dark:text-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                           </svg>
                           <span class="text-content-muted dark:text-dark-500 font-normal">{{ __('messages.shop.sort_by') }}</span>
                           <span class="text-content-primary dark:text-dark-100">
                               @if($sort === 'featured') {{ __('messages.shop.sort_featured') }}
                               @elseif($sort === 'newest') {{ __('messages.shop.newest') }}
                               @elseif($sort === 'price_low') {{ __('messages.shop.price_low') }}
                               @elseif($sort === 'price_high') {{ __('messages.shop.price_high') }}
                               @elseif($sort === 'name_asc') {{ __('messages.shop.name_asc') }}
                               @elseif($sort === 'name_desc') {{ __('messages.shop.name_desc') }}
                               @endif
                           </span>
                       </span>
                       <svg class="w-4 h-4 text-content-muted dark:text-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                       </svg>
                   </button>
                   
                   <div x-show="open" 
                        class="absolute end-0 mt-2 w-full lg:w-64 bg-surface-primary dark:bg-dark-900 rounded-2xl shadow-xl border border-border-subtle dark:border-dark-800 z-[100] py-2"
                        style="display: none;">
                       <button wire:click="$set('sort', 'featured'); open = false" class="w-full text-start px-4 py-2.5 text-sm font-medium hover:bg-surface-secondary dark:hover:bg-dark-800 {{ $sort === 'featured' ? 'text-brand-base bg-brand-base/5 dark:text-brand-accent' : 'text-content-secondary dark:text-dark-300' }}">{{ __('messages.shop.sort_featured') }}</button>
                       <button wire:click="$set('sort', 'newest'); open = false" class="w-full text-start px-4 py-2.5 text-sm font-medium hover:bg-surface-secondary dark:hover:bg-dark-800 {{ $sort === 'newest' ? 'text-brand-base bg-brand-base/5 dark:text-brand-accent' : 'text-content-secondary dark:text-dark-300' }}">{{ __('messages.shop.newest') }}</button>
                       <button wire:click="$set('sort', 'price_low'); open = false" class="w-full text-start px-4 py-2.5 text-sm font-medium hover:bg-surface-secondary dark:hover:bg-dark-800 {{ $sort === 'price_low' ? 'text-brand-base bg-brand-base/5 dark:text-brand-accent' : 'text-content-secondary dark:text-dark-300' }}">{{ __('messages.shop.price_low') }}</button>
                       <button wire:click="$set('sort', 'price_high'); open = false" class="w-full text-start px-4 py-2.5 text-sm font-medium hover:bg-surface-secondary dark:hover:bg-dark-800 {{ $sort === 'price_high' ? 'text-brand-base bg-brand-base/5 dark:text-brand-accent' : 'text-content-secondary dark:text-dark-300' }}">{{ __('messages.shop.price_high') }}</button>
                       <div class="h-px bg-border-subtle dark:bg-dark-800 my-1"></div>
                       <button wire:click="$set('sort', 'name_asc'); open = false" class="w-full text-start px-4 py-2.5 text-sm font-medium hover:bg-surface-secondary dark:hover:bg-dark-800 {{ $sort === 'name_asc' ? 'text-brand-base bg-brand-base/5 dark:text-brand-accent' : 'text-content-secondary dark:text-dark-300' }}">{{ __('messages.shop.name_asc') }}</button>
                       <button wire:click="$set('sort', 'name_desc'); open = false" class="w-full text-start px-4 py-2.5 text-sm font-medium hover:bg-surface-secondary dark:hover:bg-dark-800 {{ $sort === 'name_desc' ? 'text-brand-base bg-brand-base/5 dark:text-brand-accent' : 'text-content-secondary dark:text-dark-300' }}">{{ __('messages.shop.name_desc') }}</button>
                   </div>
               </div>

            </div>
        </div>
    </div>

    <!-- Product Grid -->
    <div class="relative z-10">
        <!-- Loading State -->
        <div wire:loading.flex wire:target="search, page, minPrice, maxPrice, category, sort, selectedBrands" class="justify-center py-20 w-full">
            <div class="flex flex-col items-center">
                <div class="relative w-16 h-16 mb-4">
                    <div class="absolute inset-0 bg-brand-base/10 rounded-full animate-ping opacity-75"></div>
                    <div class="relative w-16 h-16 bg-brand-base/5 rounded-full flex items-center justify-center">
                        <svg class="animate-spin h-8 w-8 text-brand-base" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
                <span class="text-sm font-bold text-content-muted dark:text-dark-400 animate-pulse">{{ __('messages.shop.filtering') }}</span>
            </div>
        </div>

        @if(count($products) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8" wire:loading.class="opacity-50 pointer-events-none">
                @foreach($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>
            
            <div class="mt-16">
                {{ $products->links() }}
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-24 text-center bg-surface-primary dark:bg-dark-900 rounded-3xl border border-border-subtle dark:border-dark-800 shadow-sm">
                <div class="relative w-24 h-24 mb-6">
                    <div class="absolute inset-0 bg-brand-base/5 rounded-full animate-ping opacity-20"></div>
                    <div class="relative w-24 h-24 bg-brand-base/5 rounded-full flex items-center justify-center">
                        <svg class="w-10 h-10 text-brand-light" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-bold text-content-primary dark:text-dark-100 mb-2">{{ __('messages.shop.no_products_found') }}</h3>
                <p class="text-content-secondary dark:text-dark-300 max-w-sm mx-auto mb-8">{{ __('messages.shop.no_products_desc') }}</p>
                
                <button wire:click="resetFilters" 
                   class="inline-flex items-center gap-2 px-6 py-3 bg-brand-base hover:bg-brand-accent text-content-inverse rounded-xl font-bold transition-all shadow-lg hover:shadow-brand-base/20">
                   <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                   </svg>
                   <span>{{ __('messages.shop.clear_all_filters') }}</span>
                </button>
            </div>
        @endif
    </div>
</div>
