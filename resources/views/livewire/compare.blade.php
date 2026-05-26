<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold text-content-primary dark:text-dark-100 tracking-tight">{{ __('messages.nav.compare') }}</h1>
        <p class="mt-2 text-content-secondary dark:text-dark-300 text-lg max-w-xl mx-auto">{{ __('messages.compare.subtitle') }}</p>
    </div>

    @if(!$selectedCategory)
        <h2 class="text-lg font-bold text-content-primary dark:text-dark-100 mb-2 text-center">{{ __('messages.compare.choose_category') }}</h2>
        <p class="text-sm text-content-muted dark:text-dark-500 text-center mb-6">{{ __('messages.compare.enhanced_comparison') }}</p>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-4">
            @foreach($categories as $key => $label)
                @php $isPC = in_array($key, $pcPartCategories); @endphp
                <button wire:click="selectCategory('{{ $key }}')" class="group flex flex-col items-center gap-3 p-5 bg-surface-primary dark:bg-dark-900 rounded-2xl border {{ $isPC ? 'border-brand-base/10 dark:border-brand-base/20' : 'border-border-subtle dark:border-dark-800' }} shadow-sm hover:shadow-lg hover:border-brand-light hover:-translate-y-1 transition-all duration-200">
                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $isPC ? 'from-brand-base/5 to-brand-base/10 group-hover:from-brand-accent group-hover:to-brand-accent' : 'from-surface-secondary to-surface-secondary dark:from-dark-800 dark:to-dark-800 group-hover:from-dark-700 group-hover:to-dark-700' }} flex items-center justify-center transition-all">
                        <svg class="w-6 h-6 {{ $isPC ? 'text-brand-base' : 'text-content-secondary dark:text-dark-300' }} group-hover:text-content-inverse transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $categoryIcons[$key] ?? 'M4 6h16M4 12h16M4 18h16' }}" /></svg>
                    </div>
                    <span class="font-semibold text-sm text-content-primary dark:text-dark-200">{{ $label }}</span>
                    @if($isPC)<span class="text-[9px] font-bold text-brand-accent bg-brand-base/5 px-2 py-0.5 rounded-full">{{ __('messages.compare.pc_part') }}</span>@endif
                </button>
            @endforeach
        </div>
    @else
        {{-- Category Bar --}}
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-center bg-surface-primary dark:bg-dark-900 p-4 rounded-2xl shadow-sm border border-border-subtle dark:border-dark-800 gap-4">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-brand-accent to-brand-accent flex items-center justify-center">
                    <svg class="w-5 h-5 text-content-inverse" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $categoryIcons[$selectedCategory] ?? 'M4 6h16' }}" /></svg>
                </div>
                <div>
                    <span class="font-bold text-content-primary dark:text-dark-100 text-sm">{{ $categories[$selectedCategory] ?? $selectedCategory }}</span>
                    <div class="text-xs text-content-muted dark:text-dark-500">{{ __('messages.compare.selected_count', ['count' => count($selectedProducts)]) }}</div>
                </div>
            </div>
            <div class="flex gap-2">
                <button wire:click="changeCategory" class="px-3 py-1.5 text-xs font-medium text-content-secondary dark:text-dark-300 bg-surface-secondary dark:bg-dark-800 hover:bg-surface-secondary/80 dark:hover:bg-dark-750 rounded-lg transition-colors">{{ __('messages.compare.change_category') }}</button>
                @if(count($selectedProducts) > 0)
                    <button wire:click="clearComparison" class="px-3 py-1.5 text-xs font-medium text-status-danger bg-status-danger/10 hover:bg-status-danger/20 rounded-lg transition-colors">{{ __('messages.compare.clear_all') }}</button>
                @endif
                @if(count($selectedProducts) < 2)
                    <button wire:click="togglePicker" class="px-4 py-2 bg-brand-base hover:bg-brand-accent text-content-inverse font-bold text-xs rounded-lg shadow transition-all flex items-center gap-1.5 active:scale-95">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                        {{ $showPicker ? __('messages.compare.hide') : __('messages.compare.add_product') }}
                    </button>
                @endif
            </div>
        </div>

        {{-- Picker --}}
        <div x-data="{ show: @entangle('showPicker') }" x-show="show" x-transition class="mb-8 pb-8 border-b border-border-subtle dark:border-dark-800">
            <div class="bg-surface-primary dark:bg-dark-900 rounded-2xl shadow-sm border border-border-subtle dark:border-dark-800 p-4 mb-6">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 start-0 ps-3 flex items-center pointer-events-none"><svg class="w-4 h-4 text-content-muted dark:text-dark-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg></div>
                        <input wire:model.live.debounce.300ms="search" type="text" placeholder="{{ __('messages.compare.search_placeholder', ['category' => $categories[$selectedCategory] ?? '']) }}" class="block w-full ps-10 pe-4 py-2.5 text-sm border border-border-subtle dark:border-dark-700 rounded-xl bg-surface-secondary dark:bg-dark-950 focus:bg-surface-primary focus:outline-none focus:ring-2 focus:ring-brand-accent/20 focus:border-brand-accent text-content-primary dark:text-dark-100 transition-all">
                    </div>
                    <select wire:model.live="sort" class="px-4 py-2.5 text-sm border border-border-subtle dark:border-dark-700 rounded-xl bg-surface-secondary dark:bg-dark-950 text-content-primary dark:text-dark-100">
                        <option value="newest">{{ __('messages.compare.sort_newest') }}</option>
                        <option value="price_low">{{ __('messages.compare.sort_price_low') }}</option>
                        <option value="price_high">{{ __('messages.compare.sort_price_high') }}</option>
                    </select>
                </div>
            </div>
            <div class="relative">
                <div wire:loading.flex wire:target="search, sort" class="absolute inset-0 bg-surface-primary/80 dark:bg-dark-900/80 z-10 items-center justify-center rounded-2xl"><svg class="animate-spin h-8 w-8 text-brand-base" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @forelse($this->availableProducts as $product)
                        <div class="bg-surface-primary dark:bg-dark-900 rounded-xl p-3 shadow-sm border border-border-subtle dark:border-dark-800 hover:border-brand-base/20 hover:shadow-md transition-all group flex flex-col">
                            <div class="aspect-square bg-surface-secondary dark:bg-dark-950 rounded-lg mb-3 overflow-hidden p-2 relative">
                                <img src="{{ $product->image_url }}" class="w-full h-full object-contain mix-blend-multiply dark:mix-blend-normal group-hover:scale-105 transition-transform">
                                <button wire:click="addProduct({{ $product->id }})" class="absolute bottom-2 end-2 bg-brand-base hover:bg-brand-accent text-content-inverse p-2 rounded-full shadow-lg hover:scale-110 active:scale-95 transition-transform"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg></button>
                            </div>
                            <h4 class="text-xs font-bold text-content-primary dark:text-dark-100 line-clamp-2 leading-tight mb-2">{{ $product->name }}</h4>
                            <div class="font-bold text-brand-base text-sm">{{ number_format($product->price) }} {{ __('messages.currency') }}</div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-10 text-content-muted dark:text-dark-500">{{ __('messages.compare.no_products_found') }}</div>
                    @endforelse
                </div>
                <div class="mt-6">{{ $this->availableProducts->links() }}</div>
            </div>
        </div>

        {{-- COMPARISON --}}
        @if(count($selectedProducts) === 2)
            @if($productsAreIdentical)
                <div class="text-center py-16 bg-gradient-to-br from-status-info/10 to-brand-base/5 rounded-2xl border border-status-info/20">
                    <div class="w-20 h-20 bg-status-info-soft dark:bg-status-info/20 rounded-full flex items-center justify-center mx-auto mb-5"><svg class="w-10 h-10 text-status-info dark:text-status-info-soft" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4" /></svg></div>
                    <h2 class="text-2xl font-bold text-content-primary dark:text-dark-100 mb-2">{{ __('messages.compare.same_product_title') }}</h2>
                    <p class="text-content-secondary dark:text-dark-300 max-w-md mx-auto mb-6">{{ __('messages.compare.same_product_desc') }}</p>
                    <button wire:click="clearComparison" class="px-6 py-3 bg-brand-base hover:bg-brand-accent text-content-inverse font-bold rounded-xl shadow-lg transition-all">{{ __('messages.compare.choose_different') }}</button>
                </div>
            @else
                @php
                    $p1 = $selectedProducts[0]; $p2 = $selectedProducts[1];
                    $v = $overallVerdict;
                    $isEnhanced = $isPcPart && in_array($selectedCategory, ['cpu', 'gpu']);
                @endphp

                {{-- HERO: Product VS Product --}}
                <div class="bg-gradient-to-br from-dark-950 via-dark-850 to-dark-950 rounded-3xl overflow-hidden shadow-2xl mb-8">
                    <div class="grid grid-cols-3 items-center p-8">
                        {{-- Product 1 --}}
                        <div class="text-center group">
                            <div class="w-32 h-32 mx-auto bg-content-inverse/5 dark:bg-dark-950/20 backdrop-blur rounded-2xl p-3 mb-4 border border-content-inverse/10 group-hover:border-content-inverse/30 transition-all">
                                <img src="{{ $p1['image'] }}" class="w-full h-full object-contain">
                            </div>
                            <h3 class="text-content-inverse font-bold text-sm leading-tight mb-1">{{ $p1['name'] }}</h3>
                            <div class="text-brand-base/30 font-bold text-lg">{{ number_format($p1['price']) }} {{ __('messages.currency') }}</div>
                            @if(!empty($v) && $v['verdict'] === 'product1')
                                <span class="inline-block mt-2 px-3 py-1 bg-status-success/20 text-status-success-soft text-[10px] font-bold rounded-full border border-status-success/30">{{ __('messages.compare.winner') }}</span>
                            @endif
                        </div>
                        {{-- VS --}}
                        <div class="text-center">
                            <div class="w-16 h-16 mx-auto rounded-full bg-content-inverse/5 border border-content-inverse/10 flex items-center justify-center mb-4">
                                <span class="text-2xl font-black text-content-inverse/60">VS</span>
                            </div>
                            @if(!empty($v))
                                <div class="flex h-2.5 rounded-full overflow-hidden bg-content-inverse/10 max-w-xs mx-auto gap-px">
                                    @if($v['wins1'] > 0)<div class="bg-status-success rounded-s-full" style="width:{{ $v['pct1'] }}%"></div>@endif
                                    @if($v['ties'] > 0)<div class="bg-status-info" style="width:{{ round(($v['ties']/$v['total'])*100) }}%"></div>@endif
                                    @if($v['wins2'] > 0)<div class="bg-status-danger rounded-e-full" style="width:{{ $v['pct2'] }}%"></div>@endif
                                </div>
                                <div class="flex justify-between mt-2 text-[10px] font-bold max-w-xs mx-auto">
                                    <span class="text-status-success-soft">{{ __('messages.compare.wins', ['count' => $v['wins1']]) }}</span>
                                    @if($v['ties'] > 0)<span class="text-status-info-soft">{{ __('messages.compare.tied', ['count' => $v['ties']]) }}</span>@endif
                                    <span class="text-status-danger-soft">{{ __('messages.compare.wins', ['count' => $v['wins2']]) }}</span>
                                </div>
                            @endif
                        </div>
                        {{-- Product 2 --}}
                        <div class="text-center group">
                            <div class="w-32 h-32 mx-auto bg-content-inverse/5 dark:bg-dark-950/20 backdrop-blur rounded-2xl p-3 mb-4 border border-content-inverse/10 group-hover:border-content-inverse/30 transition-all">
                                <img src="{{ $p2['image'] }}" class="w-full h-full object-contain">
                            </div>
                            <h3 class="text-content-inverse font-bold text-sm leading-tight mb-1">{{ $p2['name'] }}</h3>
                            <div class="text-brand-base/30 font-bold text-lg">{{ number_format($p2['price']) }} {{ __('messages.currency') }}</div>
                            @if(!empty($v) && $v['verdict'] === 'product2')
                                <span class="inline-block mt-2 px-3 py-1 bg-status-success/20 text-status-success-soft text-[10px] font-bold rounded-full border border-status-success/30">{{ __('messages.compare.winner') }}</span>
                            @endif
                        </div>
                    </div>

                    {{-- Verdict Summary --}}
                    @if(!empty($v) && $isPcPart)
                        <div class="bg-content-inverse/5 border-t border-content-inverse/10 px-8 py-4 text-center">
                            @if($v['verdict'] === 'tie')
                                <p class="text-content-inverse/80 text-sm font-medium">{{ __('messages.compare.equally_matched') }}</p>
                            @else
                                @php $winnerName = $v['verdict'] === 'product1' ? $p1['name'] : $p2['name']; $winPct = $v['verdict'] === 'product1' ? $v['pct1'] : $v['pct2']; @endphp
                                <p class="text-content-inverse/80 text-sm font-medium">{{ __('messages.compare.recommended_choice', ['product' => $winnerName, 'percent' => $winPct]) }}</p>
                            @endif
                        </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="grid grid-cols-2 gap-4 mb-8">
                    @foreach($selectedProducts as $product)
                        @php $outOfStock = ($product['stock'] ?? 0) <= 0; @endphp
                        <button 
                            wire:click.prevent="addToCart({{ $product['id'] }})" 
                            @if($outOfStock) disabled @endif
                            class="flex items-center justify-center gap-2 py-3 {{ $outOfStock ? 'bg-slate-350 dark:bg-dark-800 text-slate-500 dark:text-dark-500 cursor-not-allowed' : 'bg-brand-base hover:bg-brand-accent text-content-inverse active:scale-[0.98]' }} font-bold text-sm rounded-xl shadow-md transition-all"
                        >
                            @if(!$outOfStock)
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                                {{ __('messages.compare.add_named_to_cart', ['product' => \Illuminate\Support\Str::limit($product['name'], 25)]) }}
                            @else
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
                                {{ __('messages.product.out_of_stock') ?? 'Out of Stock' }}
                            @endif
                        </button>
                    @endforeach
                </div>

                {{-- SPEC ROWS (technical.city style) --}}
                <div class="bg-surface-primary dark:bg-dark-900 rounded-2xl shadow-sm border border-border-subtle dark:border-dark-800 overflow-hidden">
                    <div class="p-5 border-b border-border-subtle dark:border-dark-800 flex items-center justify-between">
                        <h3 class="font-bold text-content-primary dark:text-dark-100 text-lg">{{ __('messages.compare.detailed_specs') }}</h3>
                        <label class="inline-flex items-center cursor-pointer group">
                            <input type="checkbox" wire:model.live="highlightDifferences" class="sr-only peer">
                            <div class="relative w-9 h-5 bg-surface-secondary dark:bg-dark-800 rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-content-inverse after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-content-inverse dark:after:bg-dark-200 after:border-border-subtle dark:after:border-dark-600 after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-brand-base transition-colors"></div>
                            <span class="ms-2 text-xs font-semibold text-content-secondary dark:text-dark-300">{{ __('messages.compare.highlight_differences') }}</span>
                        </label>
                    </div>

                    {{-- Table header --}}
                    <div class="grid grid-cols-3 text-center text-xs font-bold text-content-muted dark:text-dark-500 uppercase tracking-wider px-5 py-3 bg-surface-secondary dark:bg-dark-950 border-b border-border-subtle dark:border-dark-800">
                        <div class="text-start">{{ $p1['name'] }}</div>
                        <div>{{ __('messages.compare.specification') }}</div>
                        <div class="text-end">{{ $p2['name'] }}</div>
                    </div>

                    {{-- Spec Rows --}}
                    @foreach($allSpecKeys as $key)
                        @php
                            $val1 = $p1['specs'][$key] ?? '-'; $val2 = $p2['specs'][$key] ?? '-';
                            $dv1 = is_array($val1) ? implode(', ', $val1) : $val1;
                            $dv2 = is_array($val2) ? implode(', ', $val2) : $val2;
                            $tier1 = $winnerSpecs[$key][$p1['id']] ?? 'black';
                            $tier2 = $winnerSpecs[$key][$p2['id']] ?? 'black';
                            $isDiff = $dv1 !== $dv2;
                            $rowBg = $highlightDifferences && $isDiff ? 'bg-status-warning/10 dark:bg-status-warning/20' : '';

                            // Bar widths for numeric and normalized values
                            $num1 = $this->normalizeValue($val1, $key);
                            $num2 = $this->normalizeValue($val2, $key);
                            $bar1 = 0; $bar2 = 0;
                            if ($num1 !== null && $num2 !== null && max($num1, $num2) > 0) {
                                $maxN = max($num1, $num2);
                                $bar1 = ($num1 / $maxN) * 100;
                                $bar2 = ($num2 / $maxN) * 100;
                            }
                        @endphp
                        <div class="grid grid-cols-3 items-center px-5 py-3 border-b border-border-subtle dark:border-dark-800/40 {{ $rowBg }} hover:bg-surface-secondary dark:hover:bg-dark-950/20 transition-colors group">
                            {{-- Left value --}}
                            <div class="text-start">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-semibold {{ $tier1 === 'green' ? 'text-status-success dark:text-status-success-soft' : ($tier1 === 'red' ? 'text-status-danger dark:text-status-danger-soft' : ($tier1 === 'tie' ? 'text-status-info dark:text-status-info-soft' : 'text-content-primary dark:text-dark-200')) }}">{{ $dv1 }}</span>
                                    @if($tier1 === 'green')<span class="text-status-success text-xs">✓</span>@endif
                                </div>
                                @if($bar1 > 0)
                                    <div class="mt-1 flex justify-start"><div class="h-1 rounded-full {{ $tier1 === 'green' ? 'bg-status-success' : ($tier1 === 'red' ? 'bg-status-danger' : ($tier1 === 'tie' ? 'bg-status-info' : 'bg-surface-secondary dark:bg-dark-800')) }}" style="width:{{ $bar1 }}%"></div></div>
                                @endif
                            </div>
                            {{-- Center label --}}
                            <div class="text-center text-[11px] font-bold text-content-muted dark:text-dark-500 uppercase tracking-wider">{{ str_replace('_', ' ', $key) }}</div>
                            {{-- Right value --}}
                            <div class="text-end">
                                <div class="flex items-center gap-2 justify-end">
                                    @if($tier2 === 'green')<span class="text-status-success text-xs">✓</span>@endif
                                    <span class="text-sm font-semibold {{ $tier2 === 'green' ? 'text-status-success dark:text-status-success-soft' : ($tier2 === 'red' ? 'text-status-danger dark:text-status-danger-soft' : ($tier2 === 'tie' ? 'text-status-info dark:text-status-info-soft' : 'text-content-primary dark:text-dark-200')) }}">{{ $dv2 }}</span>
                                </div>
                                @if($bar2 > 0)
                                    <div class="mt-1 flex justify-end"><div class="h-1 rounded-full {{ $tier2 === 'green' ? 'bg-status-success' : ($tier2 === 'red' ? 'bg-status-danger' : ($tier2 === 'tie' ? 'bg-status-info' : 'bg-surface-secondary dark:bg-dark-800')) }}" style="width:{{ $bar2 }}%"></div></div>
                                @endif
                            </div>
                        </div>
                    @endforeach

                    {{-- Price Row --}}
                    @php $pt1 = $winnerSpecs['price_rank'][$p1['id']] ?? 'black'; $pt2 = $winnerSpecs['price_rank'][$p2['id']] ?? 'black'; @endphp
                    <div class="grid grid-cols-3 items-center px-5 py-4 bg-surface-secondary dark:bg-dark-950 border-t border-border-subtle dark:border-dark-800">
                        <div class="text-start">
                            <span class="text-base font-extrabold {{ $pt1 === 'green' ? 'text-status-success dark:text-status-success-soft' : ($pt1 === 'red' ? 'text-status-danger dark:text-status-danger-soft' : 'text-content-primary dark:text-dark-100') }}">{{ number_format($p1['price']) }} {{ __('messages.currency') }}</span>
                            @if($pt1 === 'green')<span class="ms-1 text-status-success text-xs font-bold">{{ __('messages.compare.better_price') }} ✓</span>@endif
                        </div>
                        <div class="text-center text-xs font-bold text-content-secondary dark:text-dark-300 uppercase">{{ __('messages.compare.price') }}</div>
                        <div class="text-end">
                            @if($pt2 === 'green')<span class="me-1 text-status-success text-xs font-bold">✓ {{ __('messages.compare.better_price') }}</span>@endif
                            <span class="text-base font-extrabold {{ $pt2 === 'green' ? 'text-status-success dark:text-status-success-soft' : ($pt2 === 'red' ? 'text-status-danger dark:text-status-danger-soft' : 'text-content-primary dark:text-dark-100') }}">{{ number_format($p2['price']) }} {{ __('messages.currency') }}</span>
                        </div>
                    </div>
                </div>
            @endif
        @elseif(count($selectedProducts) < 2 && !$showPicker)
            <div class="text-center py-16 bg-surface-primary dark:bg-dark-900 rounded-2xl shadow-sm border border-border-subtle dark:border-dark-800">
                <div class="w-16 h-16 bg-brand-base/5 rounded-full flex items-center justify-center mx-auto mb-4"><svg class="w-8 h-8 text-brand-base" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg></div>
                <h2 class="text-xl font-bold text-content-primary dark:text-dark-100 mb-2">{{ __('messages.compare.select_more_products', ['count' => 2 - count($selectedProducts)]) }}</h2>
                <p class="text-content-muted dark:text-dark-500 mb-6">{{ __('messages.compare.pick_items') }}</p>
                <button wire:click="togglePicker" class="px-6 py-3 bg-brand-base hover:bg-brand-accent text-content-inverse font-bold rounded-xl shadow-lg transition-all">{{ __('messages.compare.browse_category') }}</button>
            </div>
        @endif
    @endif
</div>
