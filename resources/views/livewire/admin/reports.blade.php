<div class="max-w-7xl mx-auto space-y-8 animate-in fade-in duration-300">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-brand-base dark:text-brand-light">
                {{ __('messages.admin.reports.title') }}
            </h1>
            <p class="text-gray-500 dark:text-dark-300 mt-1">
                {{ __('messages.admin.reports.subtitle') }}
            </p>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Revenue Card -->
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

        <!-- Profit Card -->
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

        <!-- Deliveries Card -->
        <div class="bg-white dark:bg-dark-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-16 h-16 bg-amber-500/5 rounded-bl-full pointer-events-none group-hover:scale-110 transition-transform"></div>
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 text-amber-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" /></svg>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ __('messages.admin.reports.active_deliveries') }}</span>
                <span class="text-2xl font-black text-amber-600 dark:text-amber-400">{{ $activeDeliveries }} {{ __('messages.admin.reports.orders') }}</span>
            </div>
        </div>

        <!-- Out of Stock Card -->
        <div class="bg-white dark:bg-dark-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 flex items-center gap-5 hover:shadow-md transition-shadow relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-16 h-16 bg-rose-500/5 rounded-bl-full pointer-events-none group-hover:scale-110 transition-transform"></div>
            <div class="w-12 h-12 rounded-xl bg-rose-500/10 text-rose-500 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <div>
                <span class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-0.5">{{ __('messages.admin.reports.out_of_stock_items') }}</span>
                <span class="text-2xl font-black text-rose-600 dark:text-rose-400">{{ $outOfStockCount }} {{ __('messages.admin.reports.products') }}</span>
            </div>
        </div>
    </div>

    <!-- Layout Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Top Selling Items -->
        <div class="lg:col-span-8 bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 overflow-hidden">
            <div class="p-6 border-b border-gray-100 dark:border-dark-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-dark-100">{{ __('messages.admin.reports.top_selling') }}</h3>
                <p class="text-xs text-gray-400 mt-1">{{ __('messages.admin.reports.top_selling_desc') }}</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }} border-collapse text-sm">
                    <thead>
                        <tr class="bg-gray-50/50 dark:bg-dark-950 border-b border-gray-100 dark:border-dark-800 text-xs font-bold text-gray-500 dark:text-dark-400 uppercase tracking-wider">
                            <th class="px-6 py-4">{{ __('messages.admin.reports.product') }}</th>
                            <th class="px-6 py-4 text-center">{{ __('messages.admin.reports.qty_sold') }}</th>
                            <th class="px-6 py-4">{{ __('messages.admin.reports.revenue') }}</th>
                            <th class="px-6 py-4 text-center">{{ __('messages.admin.reports.remaining_stock') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-dark-800 font-medium text-gray-700 dark:text-dark-200">
                        @forelse($topSellingItems as $item)
                            <tr class="hover:bg-gray-50/30 dark:hover:bg-dark-900/30 transition-colors">
                                <td class="px-6 py-4 flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-gray-50 dark:bg-dark-950 border border-gray-100 dark:border-dark-800 p-1 flex items-center justify-center shrink-0">
                                        <img src="{{ $item->image_url }}" alt="{{ $item->product_name }}" class="w-full h-full object-contain">
                                    </div>
                                    <span class="font-bold text-gray-900 dark:text-dark-100 line-clamp-1">{{ $item->product_name }}</span>
                                </td>
                                <td class="px-6 py-4 text-center text-gray-900 dark:text-dark-100 font-extrabold">{{ $item->total_qty }}</td>
                                <td class="px-6 py-4 text-brand-base dark:text-brand-light font-bold">{{ number_format($item->total_sales, 0) }} {{ __('messages.currency') }}</td>
                                <td class="px-6 py-4 text-center">
                                    @if($item->current_stock <= 0)
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30">
                                            {{ __('messages.admin.reports.out_of_stock') }}
                                        </span>
                                    @elseif($item->current_stock < 5)
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/20 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30">
                                            {{ __('messages.admin.reports.low_stock') }} ({{ $item->current_stock }})
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-50 dark:bg-green-950/20 text-green-600 dark:text-green-400 border border-green-100 dark:border-green-900/30">
                                            {{ $item->current_stock }} {{ __('messages.admin.reports.in_stock') }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-gray-400 dark:text-dark-500">
                                    {{ __('messages.admin.reports.no_data') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Sidebar low stock alerts -->
        <div class="lg:col-span-4 space-y-6">
            <!-- Low stock alert list -->
            <div class="bg-white dark:bg-dark-900 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-dark-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-dark-100 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    {{ __('messages.admin.reports.low_stock_alerts') }}
                </h3>
                <div class="space-y-4 max-h-[400px] overflow-y-auto pr-1">
                    @forelse($lowStockProducts as $prod)
                        <div class="p-4 rounded-xl border border-amber-500/10 bg-amber-500/[0.02] flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-gray-50 dark:bg-dark-950 border border-gray-100 dark:border-dark-800 p-1 flex items-center justify-center shrink-0">
                                <img src="{{ $prod->image_url }}" alt="{{ $prod->name }}" class="w-full h-full object-contain">
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="font-bold text-sm text-gray-900 dark:text-dark-100 truncate leading-tight">{{ $prod->name }}</h4>
                                <span class="text-xs text-gray-400 dark:text-dark-500">{{ $prod->category }}</span>
                            </div>
                            <div class="text-right shrink-0">
                                <span class="px-2 py-1 rounded-lg text-xs font-bold bg-amber-100 dark:bg-amber-950/40 text-amber-700 dark:text-amber-400">
                                    {{ $prod->stock }} {{ __('messages.admin.reports.left') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-400 dark:text-dark-500 text-sm">
                            {{ __('messages.admin.reports.all_stocked') }}
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Deliveries Breakdown -->
            <div class="bg-white dark:bg-dark-900 rounded-2xl p-6 shadow-sm border border-gray-100 dark:border-dark-800">
                <h3 class="text-lg font-bold text-gray-900 dark:text-dark-100 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-base" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    {{ __('messages.admin.reports.delivery_audits') }}
                </h3>
                <div class="space-y-3 font-medium text-sm text-gray-600 dark:text-dark-300">
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-dark-850">
                        <span>{{ __('messages.admin.reports.active_shipments') }}</span>
                        <span class="font-bold text-gray-900 dark:text-dark-100">{{ $activeDeliveries }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-100 dark:border-dark-850">
                        <span>{{ __('messages.admin.reports.completed_shipments') }}</span>
                        <span class="font-bold text-emerald-600 dark:text-emerald-400">{{ $completedDeliveries }}</span>
                    </div>
                    <div class="flex justify-between py-2">
                        <span>{{ __('messages.admin.reports.cancelled_shipments') }}</span>
                        <span class="font-bold text-rose-600 dark:text-rose-400">{{ $cancelledDeliveries }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
