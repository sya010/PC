<div class="max-w-7xl mx-auto space-y-8 animate-in fade-in duration-300" wire:poll.60s>

    <!-- Header & Period Filter -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-brand-base dark:text-brand-light">
                {{ __('messages.admin.reports.title') }}
            </h1>
            <p class="text-gray-500 dark:text-dark-300 mt-1">
                {{ __('messages.admin.reports.subtitle') }}
            </p>
        </div>

        <!-- Live badge -->
        <div class="flex items-center gap-1.5 text-xs font-semibold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 px-3 py-1.5 rounded-full border border-emerald-200 dark:border-emerald-900/30">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
            {{ __('messages.admin.reports.live_refresh') }}
        </div>
    </div>

    <!-- Period Selector Toolbar -->
    <div class="bg-white dark:bg-dark-900 rounded-2xl border border-gray-100 dark:border-dark-800 shadow-sm p-4 flex flex-col sm:flex-row gap-4 items-start sm:items-center flex-wrap">
        <!-- Quick Buttons -->
        <div class="flex flex-wrap gap-2">
            @foreach(['7' => __('messages.admin.reports.period_7'), '30' => __('messages.admin.reports.period_30'), '90' => __('messages.admin.reports.period_90'), '365' => __('messages.admin.reports.period_365'), 'all' => __('messages.admin.reports.period_all')] as $val => $label)
                <button wire:click="setPeriod('{{ $val }}')"
                    class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all
                        {{ $period === $val
                            ? 'bg-brand-base text-white shadow-md shadow-brand-base/20'
                            : 'bg-gray-100 dark:bg-dark-800 text-gray-600 dark:text-dark-300 hover:bg-gray-200 dark:hover:bg-dark-700' }}">
                    {{ $label }}
                </button>
            @endforeach
            <button wire:click="setPeriod('custom')"
                class="px-3 py-1.5 rounded-xl text-xs font-bold transition-all
                    {{ $period === 'custom'
                        ? 'bg-brand-base text-white shadow-md shadow-brand-base/20'
                        : 'bg-gray-100 dark:bg-dark-800 text-gray-600 dark:text-dark-300 hover:bg-gray-200 dark:hover:bg-dark-700' }}">
                {{ __('messages.admin.reports.custom_range') }}
            </button>
        </div>

        <!-- Custom date pickers (visible only in custom mode) -->
        @if($period === 'custom' || $period !== 'all')
            <div class="flex items-center gap-2 ms-auto">
                <input type="date" wire:model.live="dateFrom"
                    class="text-xs rounded-xl border border-gray-200 dark:border-dark-700 bg-gray-50 dark:bg-dark-800 text-gray-700 dark:text-dark-200 px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-brand-base/30">
                <span class="text-gray-400 text-xs font-bold">→</span>
                <input type="date" wire:model.live="dateTo"
                    class="text-xs rounded-xl border border-gray-200 dark:border-dark-700 bg-gray-50 dark:bg-dark-800 text-gray-700 dark:text-dark-200 px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-brand-base/30">
            </div>
        @endif
    </div>

    <!-- Stats Grid: 2 rows of 4 cards (8 cards total) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Revenue -->
        <div class="bg-white dark:bg-dark-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-16 h-16 bg-brand-base/5 rounded-bl-full pointer-events-none group-hover:scale-110 transition-transform"></div>
            <div class="w-12 h-12 rounded-xl bg-brand-base/10 text-brand-base flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ __('messages.admin.reports.total_revenue') }}</span>
                <span class="text-2xl font-black text-gray-900 dark:text-dark-100">{{ number_format($totalRevenue, 0) }} {{ __('messages.currency') }}</span>
            </div>
        </div>

        <!-- Card 2: Profit -->
        <div class="bg-white dark:bg-dark-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-500/5 rounded-bl-full pointer-events-none group-hover:scale-110 transition-transform"></div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ __('messages.admin.reports.estimated_profit') }}</span>
                <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ number_format($estimatedProfit, 0) }} {{ __('messages.currency') }}</span>
            </div>
        </div>

        <!-- Card 3: Active Deliveries (Clickable) -->
        <button wire:click="toggleActiveDeliveriesModal"
            class="bg-white dark:bg-dark-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 flex items-center gap-5 hover:shadow-lg hover:border-amber-200 dark:hover:border-amber-800/40 transition-all relative overflow-hidden group text-start cursor-pointer w-full">
            <div class="absolute top-0 right-0 w-16 h-16 bg-amber-500/5 rounded-bl-full pointer-events-none group-hover:scale-125 transition-transform"></div>
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center flex-shrink-0 group-hover:bg-amber-500/20 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
            </div>
            <div class="flex-1 min-w-0">
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ __('messages.admin.reports.active_deliveries') }}</span>
                <span class="text-2xl font-black text-amber-600 dark:text-amber-400">{{ $activeDeliveries }} {{ __('messages.admin.reports.orders') }}</span>
            </div>
            <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-5 h-5 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </button>

        <!-- Card 4: Out of Stock (Clickable) -->
        <button wire:click="toggleOutOfStockModal"
            class="bg-white dark:bg-dark-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 flex items-center gap-5 hover:shadow-lg hover:border-rose-200 dark:hover:border-rose-800/40 transition-all relative overflow-hidden group text-start cursor-pointer w-full">
            <div class="absolute top-0 right-0 w-16 h-16 bg-rose-500/5 rounded-bl-full pointer-events-none group-hover:scale-125 transition-transform"></div>
            <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center flex-shrink-0 group-hover:bg-rose-500/20 transition-colors">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <div class="flex-1 min-w-0">
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ __('messages.admin.reports.out_of_stock_items') }}</span>
                <span class="text-2xl font-black text-rose-600 dark:text-rose-400">{{ $outOfStockCount }} {{ __('messages.admin.reports.products') }}</span>
            </div>
            <div class="flex-shrink-0 opacity-0 group-hover:opacity-100 transition-opacity">
                <svg class="w-5 h-5 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </div>
        </button>

        <!-- Card 5: Total Orders -->
        <div class="bg-white dark:bg-dark-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-16 h-16 bg-indigo-500/5 rounded-bl-full pointer-events-none group-hover:scale-110 transition-transform"></div>
            <div class="w-12 h-12 rounded-xl bg-indigo-500/10 text-indigo-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ __('messages.admin.reports.total_orders') }}</span>
                <span class="text-2xl font-black text-indigo-600 dark:text-indigo-400">{{ $totalOrders }}</span>
            </div>
        </div>

        <!-- Card 6: Completed Orders -->
        <div class="bg-white dark:bg-dark-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-16 h-16 bg-emerald-500/5 rounded-bl-full pointer-events-none group-hover:scale-110 transition-transform"></div>
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 text-emerald-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ __('messages.admin.reports.completed_orders') }}</span>
                <span class="text-2xl font-black text-emerald-600 dark:text-emerald-400">{{ $completedDeliveries }}</span>
            </div>
        </div>

        <!-- Card 7: Cancelled Orders -->
        <div class="bg-white dark:bg-dark-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-16 h-16 bg-rose-500/5 rounded-bl-full pointer-events-none group-hover:scale-110 transition-transform"></div>
            <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ __('messages.admin.reports.cancelled_orders') }}</span>
                <span class="text-2xl font-black text-rose-600 dark:text-rose-400">{{ $cancelledDeliveries }}</span>
            </div>
        </div>

        <!-- Card 8: Payment Statistics (Mini Split) -->
        <div class="bg-white dark:bg-dark-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 flex flex-col justify-between hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-16 h-16 bg-violet-500/5 rounded-bl-full pointer-events-none group-hover:scale-110 transition-transform"></div>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-violet-500/10 text-violet-500 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ __('messages.admin.reports.payment_split') }}</span>
                    <div class="flex items-center gap-2 text-xs font-bold">
                        <span class="text-brand-base">COD: {{ $codOrders }}</span>
                        <span class="text-gray-300">|</span>
                        <span class="text-emerald-500">Online: {{ $waylOrders }}</span>
                    </div>
                </div>
            </div>
            @php
                $totalPm = $codOrders + $waylOrders;
                $codPmPct = $totalPm > 0 ? ($codOrders / $totalPm) * 100 : 0;
            @endphp
            <div class="mt-3.5 w-full h-1.5 bg-gray-100 dark:bg-dark-800 rounded-full overflow-hidden">
                <div class="h-full bg-brand-base" style="width: {{ $codPmPct }}%"></div>
            </div>
        </div>
    </div>

    <!-- ============================== -->
    <!-- OUT OF STOCK MODAL / SLIDE-OVER -->
    <!-- ============================== -->
    @if($showOutOfStockModal)
    <div class="fixed inset-0 z-50 flex items-start justify-center pt-[5vh]" x-data x-transition>
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="toggleOutOfStockModal"></div>

        <!-- Modal Panel -->
        <div class="relative w-full max-w-2xl mx-4 bg-white dark:bg-dark-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-dark-700 overflow-hidden max-h-[80vh] flex flex-col animate-in slide-in-from-top-4 fade-in duration-300">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 dark:border-dark-800 bg-gradient-to-r from-rose-50 to-white dark:from-rose-950/20 dark:to-dark-900">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-dark-100">{{ __('messages.admin.reports.out_of_stock_items') }}</h3>
                        <p class="text-xs text-gray-500 dark:text-dark-400">{{ $outOfStockCount }} {{ __('messages.admin.reports.products') }} {{ __('messages.admin.reports.need_restocking') }}</p>
                    </div>
                </div>
                <button wire:click="toggleOutOfStockModal"
                    class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-dark-800 flex items-center justify-center text-gray-500 dark:text-dark-400 hover:bg-gray-200 dark:hover:bg-dark-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto p-6">
                @if($outOfStockProducts->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach($outOfStockProducts as $product)
                            <div class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 dark:bg-dark-950 border border-gray-100 dark:border-dark-800 hover:border-rose-200 dark:hover:border-rose-800/30 transition-colors">
                                <div class="w-12 h-12 rounded-lg bg-white dark:bg-dark-900 border border-gray-100 dark:border-dark-800 p-1 flex items-center justify-center shrink-0 overflow-hidden">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-contain">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-sm text-gray-900 dark:text-dark-100 truncate">{{ $product->name }}</h4>
                                    <p class="text-xs text-gray-400 dark:text-dark-500">{{ $product->category }}</p>
                                    <p class="text-xs text-gray-400 dark:text-dark-500">{{ number_format($product->price, 0) }} {{ __('messages.currency') }}</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-100 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-900/30 whitespace-nowrap">
                                    {{ __('messages.admin.reports.out_of_stock') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-16 text-center">
                        <div class="w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-950/20 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-dark-100 mb-1">{{ __('messages.admin.reports.all_stocked') }}</h4>
                        <p class="text-sm text-gray-400 dark:text-dark-500">{{ __('messages.admin.reports.all_stocked_desc') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- ================================== -->
    <!-- ACTIVE DELIVERIES MODAL / SLIDE-OVER -->
    <!-- ================================== -->
    @if($showActiveDeliveriesModal)
    <div class="fixed inset-0 z-50 flex items-start justify-center pt-[5vh]" x-data x-transition>
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm" wire:click="toggleActiveDeliveriesModal"></div>

        <!-- Modal Panel -->
        <div class="relative w-full max-w-3xl mx-4 bg-white dark:bg-dark-900 rounded-2xl shadow-2xl border border-gray-200 dark:border-dark-700 overflow-hidden max-h-[80vh] flex flex-col animate-in slide-in-from-top-4 fade-in duration-300">
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-gray-100 dark:border-dark-800 bg-gradient-to-r from-amber-50 to-white dark:from-amber-950/20 dark:to-dark-900">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-dark-100">{{ __('messages.admin.reports.active_deliveries') }}</h3>
                        <p class="text-xs text-gray-500 dark:text-dark-400">{{ $activeDeliveries }} {{ __('messages.admin.reports.active_transit') }}</p>
                    </div>
                </div>
                <button wire:click="toggleActiveDeliveriesModal"
                    class="w-8 h-8 rounded-lg bg-gray-100 dark:bg-dark-800 flex items-center justify-center text-gray-500 dark:text-dark-400 hover:bg-gray-200 dark:hover:bg-dark-700 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto">
                @if($activeDeliveryOrders->count() > 0)
                    <div class="divide-y divide-gray-100 dark:divide-dark-800">
                        @foreach($activeDeliveryOrders as $order)
                            <div class="px-6 py-4 hover:bg-gray-50/50 dark:hover:bg-dark-950/30 transition-colors">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-black text-gray-900 dark:text-dark-100">#{{ $order->id }}</span>
                                        <span class="text-sm font-medium text-gray-600 dark:text-dark-300">{{ $order->full_name }}</span>
                                    </div>
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 dark:bg-yellow-950/30 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-900/30',
                                            'processing' => 'bg-blue-100 dark:bg-blue-950/30 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-900/30',
                                            'shipped' => 'bg-purple-100 dark:bg-purple-950/30 text-purple-700 dark:text-purple-400 border-purple-200 dark:border-purple-900/30',
                                        ];
                                        $statusIcons = [
                                            'pending' => '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
                                            'processing' => '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>',
                                            'shipped' => '<svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-xs font-bold border {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-600 border-gray-200' }}">
                                        {!! $statusIcons[$order->status] ?? '' !!}
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-dark-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        {{ $order->city ?? 'N/A' }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        {{ ucfirst($order->payment_method) }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        {{ $order->created_at->diffForHumans() }}
                                    </span>
                                    <span class="ms-auto font-bold text-gray-900 dark:text-dark-100">
                                        {{ number_format($order->total_amount, 0) }} {{ __('messages.currency') }}
                                    </span>
                                </div>

                                <!-- Delivery Progress -->
                                @php
                                    $steps = ['pending', 'processing', 'shipped'];
                                    $currentStep = array_search($order->status, $steps);
                                @endphp
                                <div class="mt-3 flex items-center gap-1">
                                    @foreach($steps as $idx => $step)
                                        <div class="flex-1 h-1.5 rounded-full {{ $idx <= $currentStep ? 'bg-amber-400 dark:bg-amber-500' : 'bg-gray-200 dark:bg-dark-700' }} transition-all"></div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="py-16 text-center">
                        <div class="w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-950/20 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-dark-100 mb-1">{{ __('messages.admin.reports.no_active_deliveries') }}</h4>
                        <p class="text-sm text-gray-400 dark:text-dark-500">{{ __('messages.admin.reports.all_delivered_cancelled') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- Revenue Sparkline Chart (shown when period is not 'all') — Full Width -->
    @if($period !== 'all' && count($dailyRevenue) > 0)
    <div class="bg-white dark:bg-dark-900 rounded-2xl border border-gray-100 dark:border-dark-800 shadow-sm p-6 col-span-full">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-dark-100">{{ __('messages.admin.reports.revenue_over_time') }}</h3>
            <span class="text-xs text-gray-400 dark:text-dark-500">
                {{ $dateFrom }} → {{ $dateTo }}
            </span>
        </div>
        @php
            $values = array_values($dailyRevenue);
            $labels = array_keys($dailyRevenue);
            $max = max(array_merge($values, [1]));
            $chartH = 150;
            $totalPts = count($values);
        @endphp
        <div class="relative h-44 w-full overflow-hidden">
            <svg viewBox="0 0 {{ max($totalPts - 1, 1) * 10 }} {{ $chartH }}" preserveAspectRatio="none"
                 class="w-full h-full" xmlns="http://www.w3.org/2000/svg">
                <!-- Gradient fill -->
                <defs>
                    <linearGradient id="revGrad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="var(--color-brand-base)" stop-opacity="0.25"/>
                        <stop offset="100%" stop-color="var(--color-brand-base)" stop-opacity="0.02"/>
                    </linearGradient>
                </defs>
                @php
                    $pts = [];
                    foreach ($values as $i => $v) {
                        $x = $i * 10;
                        $y = $chartH - (($v / $max) * ($chartH - 8)) - 4;
                        $pts[] = "$x,$y";
                    }
                    $polyline = implode(' ', $pts);
                    $fillPath = "M0,{$chartH} " . implode(' ', array_map(fn($p) => "L$p", $pts)) . " L" . (($totalPts-1)*10) . ",{$chartH} Z";
                @endphp
                <path d="{{ $fillPath }}" fill="url(#revGrad)"/>
                <polyline points="{{ $polyline }}" fill="none" stroke="var(--color-brand-base)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </div>
        <!-- Summary badges -->
        <div class="flex flex-wrap gap-4 mt-6 pt-4 border-t border-gray-100 dark:border-dark-800 text-xs font-bold">
            <div class="bg-gray-50 dark:bg-dark-950 border border-gray-100 dark:border-dark-800 px-3 py-2 rounded-xl flex items-center gap-2">
                <span class="text-gray-400">{{ __('messages.admin.reports.total_orders') }}:</span>
                <span class="text-gray-900 dark:text-dark-100">{{ $totalOrders }}</span>
            </div>
            <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 px-3 py-2 rounded-xl flex items-center gap-2">
                <span class="text-emerald-500">{{ __('messages.admin.reports.completed_orders') }}:</span>
                <span class="text-emerald-600 dark:text-emerald-400">{{ $completedDeliveries }}</span>
            </div>
            <div class="bg-rose-50 dark:bg-rose-950/20 border border-rose-100 dark:border-rose-900/30 px-3 py-2 rounded-xl flex items-center gap-2">
                <span class="text-rose-500">{{ __('messages.admin.reports.cancelled_orders') }}:</span>
                <span class="text-rose-600 dark:text-rose-400">{{ $cancelledDeliveries }}</span>
            </div>
            <div class="bg-indigo-50 dark:bg-indigo-950/20 border border-indigo-100 dark:border-indigo-900/30 px-3 py-2 rounded-xl flex items-center gap-2">
                <span class="text-indigo-500">{{ __('messages.admin.reports.cod') }}:</span>
                <span class="text-indigo-600 dark:text-indigo-400">{{ $codOrders }}</span>
            </div>
            <div class="bg-brand-base/5 border border-brand-base/10 px-3 py-2 rounded-xl flex items-center gap-2">
                <span class="text-brand-base">{{ __('messages.admin.reports.wayl') }}:</span>
                <span class="text-brand-base dark:text-brand-light">{{ $waylOrders }}</span>
            </div>
        </div>
    </div>
    @endif

    <!-- Top Selling Items (Full Width) -->
    <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 overflow-hidden flex flex-col justify-between">
        <div>
            <div class="p-6 border-b border-gray-100 dark:border-dark-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-dark-100">{{ __('messages.admin.reports.top_selling') }}</h3>
                <p class="text-xs text-gray-400 mt-1">{{ __('messages.admin.reports.top_selling_desc') }}</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }} border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-dark-950 border-b border-gray-100 dark:border-dark-800 text-xs font-bold text-gray-500 dark:text-dark-400 uppercase tracking-wider">
                            <th class="px-6 py-4 text-center w-12">#</th>
                            <th class="px-6 py-4">{{ __('messages.admin.reports.product') }}</th>
                            <th class="px-6 py-4 text-center">{{ __('messages.admin.reports.qty_sold') }}</th>
                            <th class="px-6 py-4">{{ __('messages.admin.reports.revenue') }}</th>
                            <th class="px-6 py-4 text-center">{{ __('messages.admin.reports.remaining_stock') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-dark-800 font-medium text-gray-700 dark:text-dark-200">
                        @forelse($topSellingItems as $item)
                            <tr class="hover:bg-gray-50/30 dark:hover:bg-dark-900/30 transition-colors">
                                <td class="px-6 py-4 text-center font-black text-gray-400">
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-lg text-xs bg-gray-100 dark:bg-dark-800">
                                        {{ $loop->iteration }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-dark-950 border border-gray-100 dark:border-dark-800 p-1 flex items-center justify-center shrink-0">
                                        @if($item->image_url)
                                            <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-contain">
                                        @else
                                            <svg class="w-5 h-5 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        @endif
                                    </div>
                                    <span class="font-bold text-gray-900 dark:text-dark-100 line-clamp-1">{{ $item->product_name }}</span>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-900 dark:text-dark-100 font-extrabold">{{ $item->total_qty }}</td>
                                <td class="px-6 py-4 text-brand-base dark:text-brand-light font-bold">{{ number_format($item->total_sales, 0) }} {{ __('messages.currency') }}</td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1.5">
                                        @if($item->current_stock <= 0)
                                            <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30">
                                                {{ __('messages.admin.reports.out_of_stock') }}
                                            </span>
                                        @elseif($item->current_stock < 5)
                                            <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/20 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30">
                                                {{ __('messages.admin.reports.low_stock') }} ({{ $item->current_stock }})
                                            </span>
                                            <div class="w-16 h-1 bg-gray-100 dark:bg-dark-800 rounded-full overflow-hidden">
                                                <div class="h-full bg-amber-400" style="width: {{ ($item->current_stock / 5) * 100 }}%"></div>
                                            </div>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-xs font-bold bg-green-50 dark:bg-green-950/20 text-green-600 dark:text-green-400 border border-green-100 dark:border-green-900/30">
                                                {{ $item->current_stock }} {{ __('messages.admin.reports.in_stock') }}
                                            </span>
                                            <div class="w-16 h-1 bg-gray-100 dark:bg-dark-800 rounded-full overflow-hidden">
                                                <div class="h-full bg-green-500" style="width: {{ min(($item->current_stock / 20) * 100, 100) }}%"></div>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-400 dark:text-dark-500">
                                    {{ __('messages.admin.reports.no_data') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Stats & Breakdown Layout (Horizontal Grid Under the Table) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Order Overview -->
        <div class="bg-white dark:bg-dark-900 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-dark-800">
            <h3 class="text-lg font-bold text-gray-900 dark:text-dark-100 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-base" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                {{ __('messages.admin.reports.order_overview') }}
            </h3>
            <div class="space-y-4">
                <!-- Active -->
                <div class="flex items-center justify-between p-3 rounded-xl bg-amber-50/50 dark:bg-amber-950/10 border border-amber-100/50 dark:border-amber-900/10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-dark-300">{{ __('messages.admin.reports.active_shipments') }}</span>
                    </div>
                    <span class="font-black text-amber-600 dark:text-amber-400 text-base">{{ $activeDeliveries }}</span>
                </div>

                <!-- Completed -->
                <div class="flex items-center justify-between p-3 rounded-xl bg-emerald-50/50 dark:bg-emerald-950/10 border border-emerald-100/50 dark:border-emerald-900/10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 text-emerald-500 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-dark-300">{{ __('messages.admin.reports.completed_shipments') }}</span>
                    </div>
                    <span class="font-black text-emerald-600 dark:text-emerald-400 text-base">{{ $completedDeliveries }}</span>
                </div>

                <!-- Cancelled -->
                <div class="flex items-center justify-between p-3 rounded-xl bg-rose-50/50 dark:bg-rose-950/10 border border-rose-100/50 dark:border-rose-900/10">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-rose-500/10 text-rose-500 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 dark:text-dark-300">{{ __('messages.admin.reports.cancelled_shipments') }}</span>
                    </div>
                    <span class="font-black text-rose-600 dark:text-rose-400 text-base">{{ $cancelledDeliveries }}</span>
                </div>
            </div>
        </div>

        <!-- Payment Method Split -->
        <div class="bg-white dark:bg-dark-900 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-dark-800">
            <h3 class="text-lg font-bold text-gray-900 dark:text-dark-100 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-brand-base" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                {{ __('messages.admin.reports.payment_split') }}
            </h3>
            @php
                $total = $codOrders + $waylOrders;
                $codPct  = $total > 0 ? round(($codOrders  / $total) * 100) : 0;
                $waylPct = $total > 0 ? round(($waylOrders / $total) * 100) : 0;
            @endphp
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-xs font-bold text-gray-600 dark:text-dark-300 mb-1.5">
                        <span>{{ __('messages.admin.reports.cod') }}</span>
                        <span>{{ $codOrders }} ({{ $codPct }}%)</span>
                    </div>
                    <div class="h-2 rounded-full bg-gray-100 dark:bg-dark-800 overflow-hidden">
                        <div class="h-full rounded-full bg-brand-base transition-all duration-700" style="width: {{ $codPct }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs font-bold text-gray-600 dark:text-dark-300 mb-1.5">
                        <span>{{ __('messages.admin.reports.wayl') }}</span>
                        <span>{{ $waylOrders }} ({{ $waylPct }}%)</span>
                    </div>
                    <div class="h-2 rounded-full bg-gray-100 dark:bg-dark-800 overflow-hidden">
                        <div class="h-full rounded-full bg-emerald-500 transition-all duration-700" style="width: {{ $waylPct }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low Stock Alerts (Full Width Grid) -->
    <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 overflow-hidden">
        <!-- Header with gradient accent -->
        <div class="px-6 py-5 border-b border-gray-100 dark:border-dark-800 bg-gradient-to-r from-amber-50/80 to-transparent dark:from-amber-950/10 dark:to-transparent">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-500/10 text-amber-500 flex items-center justify-center">
                        <svg class="w-4.5 h-4.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-dark-100">{{ __('messages.admin.reports.low_stock_alerts') }}</h3>
                </div>
                @if($lowStockProducts->count() > 0)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400">
                        {{ $lowStockProducts->count() }}
                    </span>
                @endif
            </div>
        </div>

        <div class="p-6">
            @if($lowStockProducts->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach($lowStockProducts as $prod)
                        <div class="p-4 rounded-xl bg-gray-50 dark:bg-dark-950 border border-gray-100 dark:border-dark-800 hover:border-amber-200 dark:hover:border-amber-900/30 transition-all flex flex-col justify-between">
                            <div class="flex gap-3 items-start">
                                <div class="w-12 h-12 rounded-xl bg-white dark:bg-dark-900 border border-gray-100 dark:border-dark-800 p-1 flex items-center justify-center shrink-0 overflow-hidden">
                                    <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-full h-full object-contain">
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-xs text-gray-900 dark:text-dark-100 line-clamp-2 leading-snug">{{ $prod->name }}</h4>
                                    <span class="text-[10px] text-gray-400 dark:text-dark-500 font-medium block mt-0.5">{{ $prod->category }}</span>
                                </div>
                            </div>

                            <div class="mt-4 flex items-center justify-between">
                                <div class="flex-1 me-4">
                                    <!-- Stock bar -->
                                    @php
                                        $stockPercent = min(($prod->stock / 5) * 100, 100);
                                        $barColor = $prod->stock <= 2 ? 'bg-rose-500' : 'bg-amber-500';
                                    @endphp
                                    <div class="h-1 w-full bg-gray-200 dark:bg-dark-800 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $barColor }} transition-all duration-500" style="width: {{ $stockPercent }}%"></div>
                                    </div>
                                </div>
                                <span class="px-2.5 py-0.5 rounded-lg text-xs font-bold whitespace-nowrap {{ $prod->stock <= 2 ? 'bg-rose-100 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400' : 'bg-amber-100 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400' }}">
                                    {{ $prod->stock }} {{ __('messages.admin.reports.left') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-12 text-center">
                    <div class="w-16 h-16 rounded-full bg-emerald-50 dark:bg-emerald-950/20 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-8 h-8 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                    </div>
                    <h4 class="font-bold text-gray-900 dark:text-dark-100 mb-1">{{ __('messages.admin.reports.all_stocked') }}</h4>
                    <p class="text-sm text-gray-400 dark:text-dark-500">{{ __('messages.admin.reports.all_stocked_desc') }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
