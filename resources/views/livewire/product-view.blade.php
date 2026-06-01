<div class="min-h-screen bg-surface-secondary dark:bg-dark-950 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-content-secondary dark:text-dark-400 mb-8 overflow-x-auto whitespace-nowrap">
            <a href="{{ route('home') }}" class="hover:text-brand-base transition-colors">{{ __('messages.nav.home') }}</a>
            <span class="mx-3">/</span>
            <a href="{{ route('shop') }}" class="hover:text-brand-base transition-colors">{{ __('messages.nav.shop') }}</a>
            <span class="mx-3">/</span>
            <span class="font-bold text-content-primary dark:text-dark-100">{{ $product->name }}</span>
        </nav>

        <div class="bg-surface-primary dark:bg-dark-900 rounded-[2.5rem] p-8 lg:p-12 shadow-xl shadow-slate-200/10 dark:shadow-dark-950/20 ring-1 ring-border-subtle dark:ring-dark-800">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20">
                
                <!-- Gallery -->
                <div class="space-y-6">
                    <div class="relative aspect-square bg-surface-secondary dark:bg-dark-950/50 rounded-3xl overflow-hidden flex items-center justify-center p-8 group border border-border-subtle dark:border-dark-800">
                        <img 
                            src="{{ $activeImage }}" 
                            alt="{{ $product->name }}" 
                            class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal transition-transform duration-500 hover:scale-110 drop-shadow-xl"
                        >
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-surface-primary/90 dark:bg-dark-900/90 backdrop-blur rounded-full text-xs font-bold text-content-secondary dark:text-dark-300 uppercase tracking-wider border border-border-subtle dark:border-dark-700 shadow-sm">
                                {{ $product->category }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Thumbnails Gallery -->
                    <div class="grid grid-cols-4 gap-4">
                        <button wire:click="selectImage('{{ $product->image_url }}')" class="aspect-square rounded-xl border-2 {{ $activeImage === $product->image_url ? 'border-brand-base ring-2 ring-brand-base/20' : 'border-border-subtle dark:border-dark-800 hover:border-slate-300 dark:hover:border-dark-600' }} overflow-hidden p-2 bg-surface-primary dark:bg-dark-900 transition-all">
                            <img src="{{ $product->image_url }}" class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal">
                        </button>
                        @if(is_array($product->images) && count($product->images) > 0)
                            @foreach($product->images as $img)
                                <button wire:click="selectImage('{{ $img }}')" class="aspect-square rounded-xl border-2 {{ $activeImage === $img ? 'border-brand-base ring-2 ring-brand-base/20' : 'border-border-subtle dark:border-dark-800 hover:border-slate-300 dark:hover:border-dark-600' }} overflow-hidden p-2 bg-surface-primary dark:bg-dark-900 transition-all">
                                    <img src="{{ $img }}" class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal">
                                </button>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Product Info -->
                <div class="flex flex-col justify-center">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-content-primary dark:text-dark-100 mb-4 leading-tight">{{ $product->name }}</h1>
                    
                    <div class="flex items-center gap-4 mb-8">
                        <div class="flex items-center text-yellow-500 text-sm">
                            @for($i=0; $i<5; $i++)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <span class="text-content-muted dark:text-dark-400 font-medium text-sm">4.8 (124 {{ __('messages.product.reviews') }})</span>
                    </div>

                    <p class="text-3xl sm:text-4xl font-black text-content-primary dark:text-dark-100 mb-8 flex items-baseline gap-2">
                        {{ number_format($product->price, 0) }}
                        <span class="text-lg font-bold text-content-muted dark:text-dark-400">{{ __('messages.currency') }}</span>
                    </p>

                    <div class="prose prose-slate dark:prose-invert mb-10 text-content-secondary dark:text-dark-300 leading-relaxed max-w-none">
                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-content-primary dark:text-dark-100 mb-4">{{ __('messages.product.description') }}</h3>
                            <p>{{ $product->description }}</p>
                        </div>

                        @if($product->has_specs && is_array($product->specs) && count($product->specs) > 0)
                        <div>
                            <h3 class="text-xl font-bold text-content-primary dark:text-dark-100 mb-4">{{ __('messages.product.specifications') }}</h3>
                            <div class="space-y-4">
                                @foreach($product->specs as $key => $value)
                                    @if(is_scalar($value))
                                        <div class="flex items-center justify-between py-2 border-b border-border-subtle dark:border-dark-800 hover:bg-surface-secondary dark:hover:bg-dark-950/30 px-2 rounded-lg transition-colors">
                                            <span class="font-bold text-content-muted dark:text-dark-400 uppercase text-xs tracking-wider">{{ str_replace('_', ' ', $key) }}</span>
                                            <span class="font-bold text-content-primary dark:text-dark-100">{{ $value }}</span>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-4 pt-6 border-t border-border-subtle dark:border-dark-800">
                        <!-- Quantity Input could go here -->
                        
                        <button 
                            wire:click="addToCart" 
                            @disabled($product->stock < 1)
                            class="flex-1 {{ $product->stock < 1 ? 'bg-slate-300 dark:bg-dark-800 text-slate-500 dark:text-dark-500 cursor-not-allowed' : 'bg-dark-950 dark:bg-dark-800 hover:bg-brand-base dark:hover:bg-brand-accent text-white hover:shadow-brand-base/30 dark:hover:shadow-brand-accent/30 active:scale-95' }} font-bold py-5 px-8 rounded-2xl shadow-xl dark:shadow-dark-950/20 transition-all flex items-center justify-center gap-3 group"
                        >
                            @if($product->stock > 0)
                                <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                {{ __('messages.product.add_to_cart') }}
                            @else
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                </svg>
                                {{ __('messages.product.out_of_stock') ?? 'Out of Stock' }}
                            @endif
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-24">
                <h2 class="text-2xl font-black text-content-primary dark:text-dark-100 mb-8">{{ __('messages.product.related') }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($relatedProducts as $related)
                        <x-product-card :product="$related" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
