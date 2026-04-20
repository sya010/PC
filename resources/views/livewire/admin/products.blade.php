<div>
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
                {{ __('messages.admin.products.title') }}
            </h1>
            <p class="text-gray-500 mt-1">{{ __('messages.admin.products.subtitle') }}</p>
        </div>
        <a href="{{ route('admin.products.create') }}" wire:navigate class="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:shadow-indigo-300 transform hover:-translate-y-0.5 transition-all">
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
                class="w-full {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'pr-12 pl-4' : 'pl-12 pr-4' }} py-3.5 rounded-2xl border border-gray-200 bg-white/50 text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-500 focus:bg-white transition-all shadow-sm hover:border-indigo-300"
            >
            <div class="absolute inset-y-0 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right-0 pr-4' : 'left-0 pl-4' }} flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
            <div class="absolute inset-y-0 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left-0 pl-4' : 'right-0 pr-4' }} flex items-center">
                <div wire:loading wire:target="search" class="animate-spin rounded-full h-5 w-5 border-b-2 border-indigo-600"></div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
            <div class="group bg-white rounded-2xl p-4 border border-gray-100 shadow-sm hover:shadow-xl hover:border-indigo-100 transition-all duration-300 relative flex flex-col">
                <!-- Image -->
                <div class="relative aspect-[4/3] rounded-xl overflow-hidden mb-4 bg-gray-50 flex-shrink-0">
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500">
                    
                    <div class="absolute top-2 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left-2' : 'right-2' }}">
                        <span class="px-2 py-1 text-xs font-bold rounded-lg shadow-sm {{ $product->is_active ? 'bg-green-100 text-green-700 backdrop-blur-md bg-opacity-90' : 'bg-red-100 text-red-700 backdrop-blur-md bg-opacity-90' }}">
                            {{ $product->is_active ? __('messages.admin.products.active') : __('messages.admin.products.inactive') }}
                        </span>
                    </div>
                </div>

                <!-- Content -->
                <div class="space-y-2 flex-grow flex flex-col">
                    <div class="flex items-center justify-between text-xs font-bold text-gray-500 uppercase tracking-wide">
                        <span>{{ $product->category }}</span>
                        <span>{{ __('messages.admin.products.stock') }}: {{ $product->stock }}</span>
                    </div>
                    
                    <h3 class="font-bold text-gray-900 line-clamp-1 group-hover:text-indigo-600 transition-colors text-lg">
                        {{ $product->name }}
                    </h3>
                    
                    <div class="mt-auto pt-2 flex items-end justify-between">
                        <div class="text-xl font-bold text-gray-900">
                            ${{ number_format($product->price, 2) }}
                        </div>
                    </div>
                </div>

                <!-- Actions Overlay (On Hover) -->
                <div class="absolute inset-x-4 bottom-4 pt-4 bg-white/95 backdrop-blur-sm opacity-0 group-hover:opacity-100 transition-all duration-200 flex items-center justify-between gap-2 translate-y-2 group-hover:translate-y-0 shadow-[0_-10px_20px_rgba(255,255,255,0.8)] border-t border-gray-50 rounded-b-2xl">
                    <a href="{{ route('admin.products.edit', $product->id) }}" wire:navigate class="flex-1 py-2.5 text-center text-sm font-bold text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                        {{ __('messages.admin.products.edit') }}
                    </a>
                    <button wire:click="delete({{ $product->id }})" wire:confirm="{{ __('messages.admin.products.are_you_sure') }}" class="p-2.5 text-rose-500 bg-rose-50 rounded-lg hover:bg-rose-100 transition-colors">
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
