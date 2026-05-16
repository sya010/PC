<div class="min-h-screen bg-slate-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb -->
        <nav class="flex items-center text-sm text-slate-500 mb-8 overflow-x-auto whitespace-nowrap">
            <a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">{{ __('messages.nav.home') }}</a>
            <span class="mx-3">/</span>
            <a href="{{ route('shop') }}" class="hover:text-indigo-600 transition-colors">{{ __('messages.nav.shop') }}</a>
            <span class="mx-3">/</span>
            <span class="font-bold text-slate-900">{{ $product->name }}</span>
        </nav>

        <div class="bg-white rounded-[2.5rem] p-8 lg:p-12 shadow-xl shadow-slate-200/50 ring-1 ring-slate-100">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-20">
                
                <!-- Gallery -->
                <div class="space-y-6">
                    <div class="relative aspect-square bg-slate-50 rounded-3xl overflow-hidden flex items-center justify-center p-8 group border border-slate-100">
                        <img 
                            src="{{ $activeImage }}" 
                            alt="{{ $product->name }}" 
                            class="w-full h-full object-contain mix-blend-multiply transition-transform duration-500 hover:scale-110 drop-shadow-xl"
                        >
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur rounded-full text-xs font-bold text-slate-500 uppercase tracking-wider border border-slate-200 shadow-sm">
                                {{ $product->category }}
                            </span>
                        </div>
                    </div>
                    
                    <!-- Thumbnails Gallery -->
                    <div class="grid grid-cols-4 gap-4">
                        <button wire:click="selectImage('{{ $product->image }}')" class="aspect-square rounded-xl border-2 {{ $activeImage === $product->image ? 'border-indigo-600 ring-2 ring-indigo-100' : 'border-slate-100 hover:border-slate-300' }} overflow-hidden p-2 bg-white transition-all">
                            <img src="{{ $product->image }}" class="w-full h-full object-contain mix-blend-multiply">
                        </button>
                        @if(is_array($product->images) && count($product->images) > 0)
                            @foreach($product->images as $img)
                                <button wire:click="selectImage('{{ $img }}')" class="aspect-square rounded-xl border-2 {{ $activeImage === $img ? 'border-indigo-600 ring-2 ring-indigo-100' : 'border-slate-100 hover:border-slate-300' }} overflow-hidden p-2 bg-white transition-all">
                                    <img src="{{ $img }}" class="w-full h-full object-contain mix-blend-multiply">
                                </button>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- Product Info -->
                <div class="flex flex-col justify-center">
                    <h1 class="text-3xl sm:text-4xl lg:text-5xl font-black text-slate-900 mb-4 leading-tight">{{ $product->name }}</h1>
                    
                    <div class="flex items-center gap-4 mb-8">
                        <div class="flex items-center text-yellow-500 text-sm">
                            @for($i=0; $i<5; $i++)
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            @endfor
                        </div>
                        <span class="text-slate-400 font-medium text-sm">4.8 (124 {{ __('messages.product.reviews') }})</span>
                    </div>

                    <p class="text-3xl sm:text-4xl font-black text-slate-900 mb-8 flex items-baseline gap-2">
                        {{ number_format($product->price, 0) }}
                        <span class="text-lg font-bold text-slate-400">{{ __('messages.currency') }}</span>
                    </p>

                    <div class="prose prose-slate mb-10 text-slate-600 leading-relaxed max-w-none">
                        <div class="mb-8">
                            <h3 class="text-xl font-bold text-slate-900 mb-4">{{ __('messages.product.description') }}</h3>
                            <p>{{ $product->description }}</p>
                        </div>

                        <div>
                            <h3 class="text-xl font-bold text-slate-900 mb-4">{{ __('messages.product.specifications') }}</h3>
                            @if(is_array($product->specs))
                                <div class="space-y-4">
                                    @foreach($product->specs as $key => $value)
                                        @if(is_scalar($value))
                                            <div class="flex items-center justify-between py-2 border-b border-slate-50 hover:bg-slate-50 px-2 rounded-lg transition-colors">
                                                <span class="font-bold text-slate-400 uppercase text-xs tracking-wider">{{ str_replace('_', ' ', $key) }}</span>
                                                <span class="font-bold text-slate-900">{{ $value }}</span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <p>{{ __('messages.product.no_specs') }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-4 pt-6 border-t border-slate-100">
                        <!-- Quantity Input could go here -->
                        
                        <button 
                            wire:click="addToCart" 
                            class="flex-1 bg-slate-900 hover:bg-indigo-600 text-white font-bold py-5 px-8 rounded-2xl shadow-xl shadow-slate-200 hover:shadow-indigo-200 transition-all active:scale-95 flex items-center justify-center gap-3 group"
                        >
                            <svg class="w-6 h-6 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            {{ __('messages.product.add_to_cart') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="mt-24">
                <h2 class="text-2xl font-black text-slate-900 mb-8">{{ __('messages.product.related') }}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($relatedProducts as $related)
                        <x-product-card :product="$related" />
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
