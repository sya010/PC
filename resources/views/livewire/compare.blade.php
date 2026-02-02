<div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8 py-8">
    
    <!-- Header -->
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold mb-2 text-gray-900">
            {{ __('messages.nav.compare') }}
        </h1>
        <p class="text-gray-500 text-base max-w-2xl mx-auto">{{ __('messages.compare.subtitle') }}</p>
    </div>

    <!-- Product Picker Toggle / Status -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-gray-100 gap-4">
        <div class="flex items-center gap-4">
            <div class="flex -space-x-3">
                @foreach($selectedProducts as $p)
                    <img src="{{ $p['image'] }}" class="w-10 h-10 rounded-full border-2 border-white bg-gray-50 object-cover" title="{{ $p['name'] }}">
                @endforeach
                @for($i = count($selectedProducts); $i < 3; $i++)
                    <div class="w-10 h-10 rounded-full border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center text-gray-300 font-bold text-xs">
                        {{ $i + 1 }}
                    </div>
                @endfor
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-sm text-gray-900">{{ count($selectedProducts) }} / 3 Selected</span>
                @if(count($selectedProducts) < 3)
                    <span class="text-[10px] text-indigo-600 font-medium">Add {{ 3 - count($selectedProducts) }} more</span>
                @else
                    <span class="text-[10px] text-amber-600 font-medium">Comparison full</span>
                @endif
            </div>
        </div>
        
        <div class="flex gap-2">
             @if(count($selectedProducts) > 0)
                <button 
                    wire:click="clearComparison"
                    class="px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors"
                >
                    Clear All
                </button>
            @endif

            @if(count($selectedProducts) < 3)
                <button 
                    wire:click="togglePicker"
                    class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-sm rounded-lg shadow-md hover:shadow-lg transition-all flex items-center gap-2 transform active:scale-95"
                >
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ $showPicker ? 'Hide Products' : 'Add Product' }}
                </button>
            @endif
        </div>
    </div>

    <!-- Collapsible Mini Shop Picker -->
    <div 
        x-data="{ show: @entangle('showPicker') }"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-y-4"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-4"
        class="mb-8 border-b border-gray-100 pb-8"
    >
        <!-- Top Filter Bar (Matching Shop Design) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center gap-4 justify-between">
                
                <!-- Left Side: Search & Filter Dropdowns -->
                <div class="flex flex-col lg:flex-row gap-4 flex-1">
                    
                    <!-- Search -->
                    <div class="relative flex-1 min-w-[200px]">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input 
                            wire:model.live.debounce.300ms="search" 
                            type="text" 
                            placeholder="Search products..." 
                            class="block w-full pl-10 pr-4 py-2.5 text-sm border border-gray-200 rounded-xl bg-gray-50/50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all placeholder:text-gray-400"
                        >
                    </div>

                    <!-- Category Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button" class="flex items-center justify-between gap-2 px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-white hover:border-gray-300 transition-all min-w-[150px]">
                            <span class="truncate">{{ $category ?: 'All Categories' }}</span>
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 max-h-64 overflow-y-auto">
                            <button wire:click="$set('category', '')" @click="open = false" class="w-full px-4 py-2 text-left text-sm hover:bg-indigo-50 hover:text-indigo-600 transition-colors {{ !$category ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-700' }}">
                                All Categories
                            </button>
                            @foreach($categories as $key => $label)
                                <button wire:click="$set('category', '{{ $key }}')" @click="open = false" class="w-full px-4 py-2 text-left text-sm hover:bg-indigo-50 hover:text-indigo-600 transition-colors {{ $category === $key ? 'bg-indigo-50 text-indigo-600 font-medium' : 'text-gray-700' }}">
                                    {{ $label }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <!-- Brands Multi-Select Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button" class="flex items-center justify-between gap-2 px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-white hover:border-gray-300 transition-all min-w-[140px]">
                            <span class="truncate">
                                @if(count($selectedBrands) > 0)
                                    {{ count($selectedBrands) }} Brand{{ count($selectedBrands) > 1 ? 's' : '' }}
                                @else
                                    All Brands
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 mt-2 w-56 bg-white rounded-xl shadow-xl border border-gray-100 py-2 max-h-64 overflow-y-auto">
                            @foreach(['ASUS', 'MSI', 'Gigabyte', 'Corsair', 'Samsung', 'Intel', 'AMD', 'NVIDIA', 'Logitech', 'Razer', 'SteelSeries', 'HyperX'] as $brand)
                                <label class="flex items-center gap-3 px-4 py-2 hover:bg-indigo-50 cursor-pointer transition-colors">
                                    <input type="checkbox" wire:model.live="selectedBrands" value="{{ $brand }}" class="rounded text-indigo-600 border-gray-300 focus:ring-indigo-500 focus:ring-offset-0">
                                    <span class="text-sm text-gray-700">{{ $brand }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" type="button" class="flex items-center justify-between gap-2 px-4 py-2.5 bg-gray-50/50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-white hover:border-gray-300 transition-all min-w-[140px]">
                            <span class="truncate">
                                @if($minPrice > 0 || $maxPrice < 5000000)
                                    {{ number_format($minPrice) }} - {{ number_format($maxPrice) }}
                                @else
                                    Price Range
                                @endif
                            </span>
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute z-50 mt-2 w-72 bg-white rounded-xl shadow-xl border border-gray-100 p-4">
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-[10px] uppercase font-bold text-gray-400 mb-1 block">Minimum (IQD)</label>
                                    <input wire:model.live.debounce.500ms="minPrice" type="number" placeholder="0" class="w-full text-sm py-2 px-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                </div>
                                <div>
                                    <label class="text-[10px] uppercase font-bold text-gray-400 mb-1 block">Maximum (IQD)</label>
                                    <input wire:model.live.debounce.500ms="maxPrice" type="number" placeholder="Any" class="w-full text-sm py-2 px-3 rounded-lg border border-gray-200 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Grid -->
        <div class="relative">
            <div wire:loading.flex wire:target="search, category, minPrice, maxPrice, selectedBrands" class="absolute inset-0 bg-white/80 z-10 items-center justify-center rounded-2xl">
                <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4">
                @forelse($this->availableProducts as $product)
                    <div class="bg-white rounded-xl p-3 shadow-sm border border-gray-100 hover:border-indigo-200 hover:shadow-md transition-all group flex flex-col relative overflow-hidden">
                        <div class="aspect-square bg-gray-50 rounded-lg mb-3 overflow-hidden p-2 relative">
                            <img src="{{ $product->image }}" class="w-full h-full object-contain mix-blend-multiply group-hover:scale-105 transition-transform">
                            <button 
                                wire:click="addProduct({{ $product->id }})"
                                class="absolute bottom-2 right-2 bg-indigo-600 hover:bg-indigo-700 text-white p-2 rounded-full shadow-lg transition-transform hover:scale-110 active:scale-95"
                                title="Add to Compare"
                            >
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                </svg>
                            </button>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">{{ $product->category }}</span>
                            <h4 class="text-xs font-bold text-gray-900 line-clamp-2 leading-tight mb-2" title="{{ $product->name }}">{{ $product->name }}</h4>
                            <div class="font-bold text-indigo-600 text-sm">{{ number_format($product->price) }} IQD</div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-10 text-gray-500">
                        No products found matching filters.
                    </div>
                @endforelse
            </div>
            <div class="mt-6">
                {{ $this->availableProducts->links() }}
            </div>
        </div>
    </div>


    <!-- Comparison Table -->
    @if(count($selectedProducts) > 0)
        <!-- Compatibility Alert -->
        @if(count($compatibilityWarnings) > 0)
            <div class="bg-red-50/50 backdrop-blur-sm border border-red-100 p-4 mb-8 rounded-2xl shadow-sm">
                <div class="flex gap-3">
                    <div class="flex-shrink-0 bg-red-100 p-1.5 rounded-lg h-fit">
                        <svg class="h-5 w-5 text-red-600" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-gray-900 mb-1">
                            Compatibility Issues Found
                        </h3>
                        <ul class="space-y-1 text-red-600 text-sm font-medium">
                            @foreach($compatibilityWarnings as $warning)
                                <li class="flex items-start gap-2">
                                    <span class="mt-1.5 w-1 h-1 bg-red-400 rounded-full flex-shrink-0"></span>
                                    {{ $warning['message'] }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="overflow-x-auto pb-8 relative -mx-4 px-4 sm:mx-0 sm:px-0">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr>
                        <th class="p-4 w-48 min-w-[12rem] bg-white z-20 sticky left-0 top-0 border-b border-gray-100 shadow-sm align-bottom pb-6">
                            <div class="flex flex-col gap-2">
                                <span class="text-xl font-bold text-gray-900">Specs</span>
                                <label class="inline-flex items-center cursor-pointer group">
                                    <input type="checkbox" wire:model.live="highlightDifferences" class="sr-only peer">
                                    <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-indigo-600 transition-colors"></div>
                                    <span class="ms-2 text-xs font-semibold text-gray-500 group-hover:text-indigo-600 transition-colors">Highlight Diff</span>
                                </label>
                            </div>
                        </th>
                        @foreach($selectedProducts as $product)
                            <th class="p-4 w-72 min-w-[18rem] align-top sticky top-0 z-10 bg-white/95 backdrop-blur-sm border-b border-gray-100 transition-all duration-300">
                                <div class="bg-white rounded-2xl p-4 shadow-[0_2px_15px_-3px_rgba(0,0,0,0.05)] border border-gray-100 hover:border-indigo-300 hover:shadow-lg transition-all duration-300 flex flex-col h-full relative group hover:-translate-y-1">
                                    
                                    <button 
                                        wire:click="removeProduct('{{ $product['id'] }}')"
                                        class="absolute top-2 right-2 p-1.5 bg-white shadow-sm text-gray-400 hover:text-red-500 rounded-full hover:bg-red-50 transition-all opacity-0 group-hover:opacity-100 z-30"
                                    >
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>

                                    <div class="aspect-[4/3] w-full bg-gray-50 rounded-xl overflow-hidden relative mb-4 flex items-center justify-center">
                                        <img src="{{ $product['image'] }}" class="w-full h-full object-contain p-2 mix-blend-multiply group-hover:scale-105 transition-transform duration-500">
                                    </div>

                                    <div class="text-center flex-1 flex flex-col">
                                        <span class="inline-flex self-center px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-600 uppercase tracking-wide mb-2">{{ $product['category'] }}</span>
                                        <h3 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors line-clamp-2 text-sm mb-2 h-10 flex items-center justify-center leading-tight">{{ $product['name'] }}</h3>
                                        
                                        <div class="mt-auto pt-4 w-full space-y-3">
                                            <div class="text-xl font-bold text-gray-900">
                                                {{ number_format($product['price']) }} IQD
                                            </div>
                                            
                                            <button 
                                                wire:click.prevent="addToCart({{ $product['id'] }})" 
                                                class="w-full bg-gray-900 hover:bg-indigo-600 text-white font-bold text-sm py-2.5 rounded-xl shadow-md transition-all active:scale-95 flex items-center justify-center gap-2"
                                            >
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                Add to Cart
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Price Visual Bar -->
                                    <div class="mt-4 pt-3 border-t border-gray-50/50">
                                         @php
                                            $maxPrice = max(array_column($selectedProducts, 'price'));
                                            $percent = ($product['price'] / $maxPrice) * 100;
                                            $tier = $this->winnerSpecs['price_rank'][$product['id']] ?? 'black';
                                            
                                            $barColor = match($tier) {
                                                'green' => 'bg-emerald-500',
                                                'blue' => 'bg-blue-500',
                                                'red' => 'bg-rose-500',
                                                default => 'bg-slate-300'
                                            };
                                        @endphp
                                        <div class="flex justify-between text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-1">
                                            <span>Value</span>
                                            <span class="{{ $tier === 'green' ? 'text-emerald-500' : '' }}">{{ round(100 - $percent) }}%</span>
                                        </div>
                                        <div class="w-full bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="h-full rounded-full {{ $barColor }}" style="width: {{ $percent }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </th>
                        @endforeach
                        
                        <!-- Empty Slots -->
                        @for($i = count($selectedProducts); $i < 3; $i++)
                            <th class="p-4 w-72 min-w-[18rem] align-top bg-transparent">
                                <button 
                                    wire:click="$set('showPicker', true)"
                                    class="w-full h-full min-h-[350px] border-2 border-dashed border-gray-200 hover:border-indigo-400 hover:bg-indigo-50/20 rounded-2xl flex flex-col items-center justify-center gap-4 text-gray-400 hover:text-indigo-600 transition-all group"
                                >
                                    <div class="w-16 h-16 rounded-full bg-white shadow-sm border border-gray-100 group-hover:border-indigo-200 group-hover:shadow-md flex items-center justify-center transition-all group-hover:scale-110">
                                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                    </div>
                                    <span class="font-bold text-sm">Add Product</span>
                                </button>
                            </th>
                        @endfor
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($allSpecKeys as $key)
                        @php
                            $values = [];
                            foreach($selectedProducts as $p) {
                                $val = $p['specs'][$key] ?? '';
                                if (is_array($val)) $values[] = json_encode($val);
                                else $values[] = $val;
                            }
                            $uniqueValues = array_unique($values);
                            $isDifferent = count($uniqueValues) > 1 && count(array_filter($values)) > 0;
                            
                            $rowClass = $highlightDifferences && $isDifferent ? 'bg-amber-50/40' : 'hover:bg-slate-50/50';
                        @endphp
                        <tr class="{{ $rowClass }} transition-colors group">
                            <!-- Label Column -->
                            <td class="p-4 font-bold text-gray-500 text-[11px] uppercase tracking-wider bg-white sticky left-0 group-hover:bg-gray-50/80 border-r border-gray-50/50 shadow-[4px_0_10px_-4px_rgba(0,0,0,0.01)] backdrop-blur-sm z-10 transition-colors">
                                {{ str_replace('_', ' ', $key) }}
                            </td>
                            
                            <!-- Product Columns -->
                            @foreach($selectedProducts as $product)
                                @php
                                    $val = $product['specs'][$key] ?? '-';
                                    $displayVal = is_array($val) ? implode(', ', $val) : $val;
                                    
                                    $tier = $this->winnerSpecs[$key][$product['id']] ?? 'black';
                                    $textColor = match($tier) {
                                        'green' => 'text-emerald-700',
                                        'blue' => 'text-blue-700',
                                        'red' => 'text-rose-700',
                                        default => 'text-gray-700'
                                    };
                                    $bgColor = match($tier) {
                                        'green' => 'bg-emerald-50/50 border-emerald-100',
                                        'blue' => 'bg-blue-50/50 border-blue-100',
                                        'red' => 'bg-rose-50/50 border-rose-100',
                                        default => 'border-transparent'
                                    };
                                @endphp
                                <td class="p-4 relative group/cell border-r border-gray-50/30">
                                    <div class="flex flex-col gap-1 {{ $tier !== 'black' ? 'p-2 rounded-lg border ' . $bgColor : '' }} transition-all">
                                        <div class="flex items-center justify-between">
                                            <span class="text-xs font-bold {{ $textColor }}">
                                                {{ $displayVal }}
                                            </span>
                                            @if($tier === 'green')
                                                <div class="bg-emerald-100 text-emerald-600 p-0.5 rounded-full">
                                                    <svg class="w-2.5 h-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        
                                        <!-- Visual Spec Bar -->
                                        @if(preg_match('/^(\d+(\.\d+)?)\s*(GB|MB|TB|GHz|MHz|W|mm)$/i', $displayVal, $matches))
                                             @php
                                                $rowVals = [];
                                                foreach($selectedProducts as $sp) {
                                                    $raw = $sp['specs'][$key] ?? '';
                                                    if(preg_match('/^(\d+(\.\d+)?)/', $raw, $m)) $rowVals[] = (float)$m[1];
                                                }
                                                $maxRow = max($rowVals) > 0 ? max($rowVals) : 1;
                                                $currentVal = (float)$matches[1];
                                                $width = ($currentVal / $maxRow) * 100;
                                                
                                                $barColor = match($tier) {
                                                    'green' => 'bg-emerald-400',
                                                    'blue' => 'bg-blue-400',
                                                    'red' => 'bg-rose-400',
                                                    default => 'bg-slate-300'
                                                };
                                             @endphp
                                             <div class="h-1 w-full bg-white/50 rounded-full overflow-hidden shadow-inner mt-1">
                                                 <div class="h-full rounded-full {{ $barColor }}" style="width: {{ $width }}%"></div>
                                             </div>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                            
                            <!-- Spacer cells for empty slots -->
                            @for($i = count($selectedProducts); $i < 3; $i++)
                                <td class="p-4 bg-gray-50/10 border-r border-gray-50/30"></td>
                            @endfor
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16 bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('messages.compare.no_products') }}</h2>
            <p class="text-gray-500 mb-6 max-w-sm mx-auto">{{ __('messages.compare.select_products') }}</p>
            <button 
                wire:click="$toggle('showPicker')"
                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 transition-all hover:-translate-y-1 active:translate-y-0"
            >
                Start Comparing
            </button>
        </div>
    @endif
</div>
