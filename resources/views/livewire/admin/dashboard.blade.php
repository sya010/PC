<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-content-primary dark:text-dark-100 bg-clip-text text-transparent bg-gradient-to-r from-brand-base to-brand-accent">
            {{ __('messages.admin.dashboard.title') }}
        </h1>
        <p class="text-content-secondary dark:text-dark-400 mt-1">{{ __('messages.admin.dashboard.welcome') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Stats Cards -->
        <div class="bg-surface-primary dark:bg-dark-800 p-6 rounded-2xl border border-border-subtle dark:border-dark-700 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-brand-base/5 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative">
                <p class="text-sm text-content-secondary dark:text-dark-400 font-bold uppercase tracking-wider">{{ __('messages.admin.dashboard.total_sales') }}</p>
                <h3 class="text-3xl font-bold text-content-primary dark:text-dark-100 mt-2">${{ number_format($totalSales, 2) }}</h3>
                <span class="text-brand-base dark:text-brand-light text-xs font-bold flex items-center mt-3 bg-brand-base/10 dark:bg-brand-base/20 px-2 py-1 rounded-lg w-fit">
                    <svg class="w-3 h-3 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'ml-1' : 'mr-1' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    {{ __('messages.admin.dashboard.lifetime_revenue') }}
                </span>
            </div>
        </div>

        <div class="bg-surface-primary dark:bg-dark-800 p-6 rounded-2xl border border-border-subtle dark:border-dark-700 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-brand-accent/10 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative">
                <p class="text-sm text-content-secondary dark:text-dark-400 font-bold uppercase tracking-wider">{{ __('messages.admin.dashboard.active_orders') }}</p>
                <h3 class="text-3xl font-bold text-content-primary dark:text-dark-100 mt-2">{{ $activeOrders }}</h3>
                <span class="text-brand-accent dark:text-brand-light text-xs font-bold flex items-center mt-3 bg-brand-accent/10 dark:bg-brand-accent/20 px-2 py-1 rounded-lg w-fit">
                    {{ __('messages.admin.dashboard.pending_processing') }}
                </span>
            </div>
        </div>

        <div class="bg-surface-primary dark:bg-dark-800 p-6 rounded-2xl border border-border-subtle dark:border-dark-700 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
             <div class="absolute right-0 top-0 w-32 h-32 bg-brand-light/10 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative">
                <p class="text-sm text-content-secondary dark:text-dark-400 font-bold uppercase tracking-wider">{{ __('messages.admin.dashboard.total_products') }}</p>
                <h3 class="text-3xl font-bold text-content-primary dark:text-dark-100 mt-2">{{ $totalProducts }}</h3>
                <span class="text-brand-light dark:text-brand-base text-xs font-bold flex items-center mt-3 bg-brand-light/10 dark:bg-brand-light/20 px-2 py-1 rounded-lg w-fit">
                    {{ __('messages.admin.dashboard.in_catalog') }}
                </span>
            </div>
        </div>

        <div class="bg-surface-primary dark:bg-dark-800 p-6 rounded-2xl border border-border-subtle dark:border-dark-700 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-brand-base/5 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative">
                <p class="text-sm text-content-secondary dark:text-dark-400 font-bold uppercase tracking-wider">{{ __('messages.admin.dashboard.new_users') }}</p>
                <h3 class="text-3xl font-bold text-content-primary dark:text-dark-100 mt-2">{{ $newUsers }}</h3>
                <span class="text-brand-accent dark:text-brand-light text-xs font-bold flex items-center mt-3 bg-brand-base/5 dark:bg-brand-base/20 px-2 py-1 rounded-lg w-fit">
                    {{ __('messages.admin.dashboard.last_30_days') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-surface-primary dark:bg-dark-800 rounded-2xl shadow-sm border border-border-subtle dark:border-dark-700 overflow-hidden">
        <div class="p-6 border-b border-border-subtle dark:border-dark-700 flex justify-between items-center bg-surface-secondary/50 dark:bg-dark-900/50">
            <h3 class="font-bold text-lg text-content-primary dark:text-dark-100">{{ __('messages.admin.dashboard.recent_orders') }}</h3>
            <a href="{{ route('admin.orders') }}" wire:navigate class="text-sm text-brand-base font-bold hover:text-brand-accent transition-colors">{{ __('messages.admin.dashboard.view_all') }} &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }} text-sm text-content-secondary dark:text-dark-400">
                <thead class="bg-surface-secondary dark:bg-dark-900 text-xs uppercase font-bold text-content-muted dark:text-dark-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">{{ __('messages.admin.dashboard.order_id') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.dashboard.customer') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.dashboard.status') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.dashboard.date') }}</th>
                        <th class="px-6 py-4 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }}">{{ __('messages.admin.dashboard.total') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-subtle dark:divide-dark-700">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-surface-secondary dark:hover:bg-dark-900 transition-colors group">
                            <td class="px-6 py-4 font-bold text-brand-base group-hover:text-brand-accent transition-colors">#{{ $order->id }}</td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-content-primary dark:text-dark-100">{{ $order->full_name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $colors = [
                                        'pending' => 'bg-brand-light/10 dark:bg-brand-light/20 text-content-primary dark:text-brand-light border-brand-light/30 dark:border-brand-light/40',
                                        'processing' => 'bg-brand-accent/10 dark:bg-brand-accent/20 text-content-primary dark:text-brand-accent border-brand-accent/30 dark:border-brand-accent/40',
                                        'completed' => 'bg-brand-base/10 dark:bg-brand-base/20 text-content-primary dark:text-brand-base border-brand-base/30 dark:border-brand-base/40',
                                        'cancelled' => 'bg-surface-secondary/50 dark:bg-dark-700 text-content-secondary dark:text-dark-300 border-border-subtle dark:border-dark-600',
                                    ];
                                    $color = $colors[$order->status] ?? 'bg-surface-secondary dark:bg-dark-700 text-content-secondary dark:text-dark-400 border-border-subtle dark:border-dark-600';
                                    $statusKeys = [
                                        'pending' => __('messages.admin.orders.pending'),
                                        'processing' => __('messages.admin.orders.processing'),
                                        'completed' => __('messages.admin.orders.completed'),
                                        'cancelled' => __('messages.admin.orders.cancelled'),
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $color }} shadow-sm">
                                    {{ $statusKeys[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-content-secondary dark:text-dark-400">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }} font-bold text-content-primary dark:text-dark-100">${{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-content-muted dark:text-dark-500 font-medium">{{ __('messages.admin.dashboard.no_recent_orders') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
