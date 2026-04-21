<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
            {{ __('messages.admin.dashboard.title') }}
        </h1>
        <p class="text-gray-500 mt-1">{{ __('messages.admin.dashboard.welcome') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Stats Cards -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative">
                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">{{ __('messages.admin.dashboard.total_sales') }}</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($totalSales, 2) }}</h3>
                <span class="text-green-500 text-xs font-bold flex items-center mt-3 bg-green-50 px-2 py-1 rounded-lg w-fit">
                    <svg class="w-3 h-3 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'ml-1' : 'mr-1' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    {{ __('messages.admin.dashboard.lifetime_revenue') }}
                </span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-blue-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative">
                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">{{ __('messages.admin.dashboard.active_orders') }}</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $activeOrders }}</h3>
                <span class="text-blue-500 text-xs font-bold flex items-center mt-3 bg-blue-50 px-2 py-1 rounded-lg w-fit">
                    {{ __('messages.admin.dashboard.pending_processing') }}
                </span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
             <div class="absolute right-0 top-0 w-32 h-32 bg-orange-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative">
                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">{{ __('messages.admin.dashboard.total_products') }}</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $totalProducts }}</h3>
                <span class="text-orange-500 text-xs font-bold flex items-center mt-3 bg-orange-50 px-2 py-1 rounded-lg w-fit">
                    {{ __('messages.admin.dashboard.in_catalog') }}
                </span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-purple-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative">
                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">{{ __('messages.admin.dashboard.new_users') }}</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $newUsers }}</h3>
                <span class="text-purple-500 text-xs font-bold flex items-center mt-3 bg-purple-50 px-2 py-1 rounded-lg w-fit">
                    {{ __('messages.admin.dashboard.last_30_days') }}
                </span>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-lg text-gray-900">{{ __('messages.admin.dashboard.recent_orders') }}</h3>
            <a href="{{ route('admin.orders') }}" wire:navigate class="text-sm text-indigo-600 font-bold hover:text-indigo-800 transition-colors">{{ __('messages.admin.dashboard.view_all') }} &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }} text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">{{ __('messages.admin.dashboard.order_id') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.dashboard.customer') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.dashboard.status') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.dashboard.date') }}</th>
                        <th class="px-6 py-4 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }}">{{ __('messages.admin.dashboard.total') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($recentOrders as $order)
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4 font-bold text-indigo-600 group-hover:text-indigo-800 transition-colors">#{{ $order->id }}</td>
                            <td class="px-6 py-4">
                                <span class="font-semibold text-gray-900">{{ $order->full_name }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $colors = [
                                        'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                                        'processing' => 'bg-blue-100 text-blue-800 border-blue-200',
                                        'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                        'cancelled' => 'bg-rose-100 text-rose-800 border-rose-200',
                                    ];
                                    $color = $colors[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200';
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
                            <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }} font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">{{ __('messages.admin.dashboard.no_recent_orders') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
