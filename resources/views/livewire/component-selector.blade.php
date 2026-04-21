<div class="min-h-screen bg-[#F3F4F6] pb-12 font-sans text-slate-900">
    <!-- Header -->
    <div class="sticky top-0 z-40 bg-white/80 backdrop-blur-xl border-b border-slate-200/60 shadow-sm transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-20">
                <div class="flex items-center gap-4">
                    <a href="{{ route('build-pc') }}" class="w-10 h-10 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm active:scale-95" wire:navigate title="Back to Builder">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-2xl font-black text-slate-900 tracking-tight">{{ $title }}</h1>
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-wider hidden sm:block">Select Component</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Filter Bar -->
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-sm border border-slate-100 p-6 mb-10 relative z-30">
            <div class="flex flex-col lg:flex-row lg:items-center gap-6 justify-between">
                
                <!-- Left Side: Search & Filters -->
                <div class="flex flex-col lg:flex-row gap-4 flex-1">
                    
                    <!-- Search -->
                    <div class="relative w-full lg:w-72">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            wire:model.live.debounce.300ms="search" 
                            type="text" 
                            class="block w-full pl-11 pr-4 py-3 border border-slate-200 rounded-xl text-sm bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all font-medium text-slate-900 placeholder-slate-400" 
                            placeholder="Search {{ strtolower(explode(' ', $title)[1] ?? 'component') }}..."
                        >
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        
                        <!-- Brand Dropdown -->
                        <div x-data="{ open: false }" class="relative" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 px-5 py-3 bg-white hover:bg-slate-50 border border-slate-200 hover:border-indigo-300 rounded-xl text-sm font-semibold text-slate-700 transition-all shadow-sm">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                <span>Brands {{ count($selectedBrands) > 0 ? '('.count($selectedBrands).')' : '' }}</span>
                                <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                 class="absolute left-0 mt-2 w-64 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 p-5"
                                 style="display: none;">
                                
                                <div class="mb-4 flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Filter by Brand</span>
                                    @if(count($selectedBrands) > 0)
                                        <button wire:click="$set('selectedBrands', [])" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Reset</button>
                                    @endif
                                </div>

                                <div class="max-h-60 overflow-y-auto custom-scrollbar space-y-1">
                                    @foreach(['ASUS', 'MSI', 'Gigabyte', 'Corsair', 'Samsung', 'Intel', 'AMD', 'NVIDIA', 'Logitech', 'Razer', 'HyperX', 'SteelSeries'] as $brand)
                                        <label class="flex items-center gap-3 p-2 rounded-lg hover:bg-slate-50 cursor-pointer transition-colors group">
                                            <div class="relative flex items-center">
                                                <input type="checkbox" wire:model.live="selectedBrands" value="{{ $brand }}" 
                                                    class="peer h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500/20 transition-all cursor-pointer">
                                            </div>
                                            <span class="text-sm font-medium text-slate-600 group-hover:text-slate-900">{{ $brand }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Price Dropdown -->
                        <div x-data="{ open: false }" class="relative" @click.outside="open = false">
                            <button @click="open = !open" class="flex items-center gap-2 px-5 py-3 bg-white hover:bg-slate-50 border border-slate-200 hover:border-indigo-300 rounded-xl text-sm font-semibold text-slate-700 transition-all shadow-sm">
                                <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Price Range</span>
                                <svg class="w-3 h-3 text-slate-400 transition-transform duration-200" :class="{'rotate-180': open}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
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
                                 class="absolute left-0 mt-2 w-72 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 p-6"
                                 style="display: none;">
                                
                                <div class="mb-4 flex items-center justify-between">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Set Price Budget</span>
                                    @if($minPrice > 0 || $maxPrice < 10000000)
                                        <button wire:click="$set('minPrice', 0); $set('maxPrice', 10000000)" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Reset</button>
                                    @endif
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label class="text-[10px] uppercase font-bold text-slate-400 mb-1.5 block">Minimum (IQD)</label>
                                        <x-input-iqd wire:model.live.debounce.500ms="minPrice" placeholder="0" class="w-full text-sm py-2.5 border-slate-200 rounded-lg focus:ring-indigo-500/20" />
                                    </div>
                                    <div>
                                        <label class="text-[10px] uppercase font-bold text-slate-400 mb-1.5 block">Maximum (IQD)</label>
                                        <x-input-iqd wire:model.live.debounce.500ms="maxPrice" placeholder="Any" class="w-full text-sm py-2.5 border-slate-200 rounded-lg focus:ring-indigo-500/20" />
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Right Side: Sort -->
                <div class="flex items-center gap-3 w-full lg:w-auto mt-4 lg:mt-0 pt-4 lg:pt-0 border-t lg:border-none border-slate-100 px-1 lg:px-0">
                   
                   <div x-data="{ open: false }" class="relative w-full lg:w-auto" @click.outside="open = false">
                       <button @click="open = !open" class="w-full lg:w-auto flex items-center justify-between gap-3 px-5 py-3 bg-white border border-slate-200 hover:border-indigo-300 rounded-xl text-sm font-semibold text-slate-700 transition-all shadow-sm">
                           <span class="flex items-center gap-2">
                               <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                   <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12" />
                               </svg>
                               <span class="text-slate-400 font-normal">Sort by:</span>
                               <span class="text-slate-900">
                                   @if($sort === 'newest') Newest Arrivals
                                   @elseif($sort === 'price_low') Price: Low to High
                                   @elseif($sort === 'price_high') Price: High to Low
                                   @elseif($sort === 'name_asc') Name: A-Z
                                   @elseif($sort === 'name_desc') Name: Z-A
                                   @endif
                               </span>
                           </span>
                           <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                           </svg>
                       </button>
                       
                       <div x-show="open" 
                            class="absolute right-0 mt-2 w-full lg:w-64 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 py-2"
                            style="display: none;">
                           <button wire:click="$set('sort', 'newest'); open = false" class="w-full text-left px-4 py-2.5 text-sm font-medium hover:bg-slate-50 {{ $sort === 'newest' ? 'text-indigo-600 bg-indigo-50/50' : 'text-slate-600' }}">Newest Arrivals</button>
                           <button wire:click="$set('sort', 'price_low'); open = false" class="w-full text-left px-4 py-2.5 text-sm font-medium hover:bg-slate-50 {{ $sort === 'price_low' ? 'text-indigo-600 bg-indigo-50/50' : 'text-slate-600' }}">Price: Low to High</button>
                           <button wire:click="$set('sort', 'price_high'); open = false" class="w-full text-left px-4 py-2.5 text-sm font-medium hover:bg-slate-50 {{ $sort === 'price_high' ? 'text-indigo-600 bg-indigo-50/50' : 'text-slate-600' }}">Price: High to Low</button>
                           <div class="h-px bg-slate-100 my-1"></div>
                           <button wire:click="$set('sort', 'name_asc'); open = false" class="w-full text-left px-4 py-2.5 text-sm font-medium hover:bg-slate-50 {{ $sort === 'name_asc' ? 'text-indigo-600 bg-indigo-50/50' : 'text-slate-600' }}">Name: A-Z</button>
                           <button wire:click="$set('sort', 'name_desc'); open = false" class="w-full text-left px-4 py-2.5 text-sm font-medium hover:bg-slate-50 {{ $sort === 'name_desc' ? 'text-indigo-600 bg-indigo-50/50' : 'text-slate-600' }}">Name: Z-A</button>
                       </div>
                   </div>

                </div>
            </div>
        </div>

        <!-- Product Grid -->
        <div>
            <!-- Loading State -->
            <div wire:loading.flex wire:target="search, page, minPrice, maxPrice, selectedBrands, sort" class="justify-center py-20 w-full">
                <div class="flex flex-col items-center">
                    <div class="relative w-16 h-16 mb-4">
                        <div class="absolute inset-0 bg-indigo-100 rounded-full animate-ping opacity-75"></div>
                        <div class="relative w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center">
                            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                    <span class="text-sm font-bold text-slate-500 animate-pulse">Loading components...</span>
                </div>
            </div>

            @if(count($products) > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" wire:loading.class="opacity-50 pointer-events-none">
                    @foreach($products as $product)
                        <x-product-card :product="$product" action="select({{ $product->id }})" />
                    @endforeach
                </div>
                
                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-24 text-center bg-white rounded-3xl border border-slate-100 shadow-sm">
                     <div class="relative w-24 h-24 mb-6">
                        <div class="absolute inset-0 bg-indigo-50 rounded-full animate-ping opacity-20"></div>
                        <div class="relative w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center">
                            <svg class="w-10 h-10 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">No components found</h3>
                    <p class="text-slate-500 max-w-sm mx-auto mb-8">We couldn't find any parts matching your filters. Try adjusting your search or price range.</p>
                    <button wire:click="resetFilters" 
                       class="inline-flex items-center gap-2 px-6 py-3 bg-slate-900 hover:bg-indigo-600 text-white rounded-xl font-bold transition-all shadow-lg shadow-slate-200 hover:shadow-indigo-200">
                       <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                           <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                       </svg>
                       <span>Clear All Filters</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
