<div>
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
            Dashboard Overview
        </h1>
        <p class="text-gray-500 mt-1">Welcome back! Here's what's happening with your store today.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Stats Cards -->
        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-indigo-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative">
                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Total Sales</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">${{ number_format($totalSales, 2) }}</h3>
                <span class="text-green-500 text-xs font-bold flex items-center mt-3 bg-green-50 px-2 py-1 rounded-lg w-fit">
                    <svg class="w-3 h-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" /></svg>
                    Lifetime Revenue
                </span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-blue-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative">
                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Active Orders</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $activeOrders }}</h3>
                <span class="text-blue-500 text-xs font-bold flex items-center mt-3 bg-blue-50 px-2 py-1 rounded-lg w-fit">
                    Pending Processing
                </span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
             <div class="absolute right-0 top-0 w-32 h-32 bg-orange-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative">
                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">Total Products</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $totalProducts }}</h3>
                <span class="text-orange-500 text-xs font-bold flex items-center mt-3 bg-orange-50 px-2 py-1 rounded-lg w-fit">
                    In Catalog
                </span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-shadow group relative overflow-hidden">
            <div class="absolute right-0 top-0 w-32 h-32 bg-purple-50 rounded-bl-full -mr-8 -mt-8 transition-transform group-hover:scale-110"></div>
            <div class="relative">
                <p class="text-sm text-gray-500 font-bold uppercase tracking-wider">New Users</p>
                <h3 class="text-3xl font-bold text-gray-900 mt-2">{{ $newUsers }}</h3>
                <span class="text-purple-500 text-xs font-bold flex items-center mt-3 bg-purple-50 px-2 py-1 rounded-lg w-fit">
                    Last 30 Days
                </span>
            </div>
        </div>
    </div>

    <!-- Recent Orders Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-50 flex justify-between items-center bg-gray-50/50">
            <h3 class="font-bold text-lg text-gray-900">Recent Orders</h3>
            <a href="{{ route('admin.orders') }}" wire:navigate class="text-sm text-indigo-600 font-bold hover:text-indigo-800 transition-colors">View All &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-600">
                <thead class="bg-gray-50 text-xs uppercase font-bold text-gray-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">Order ID</th>
                        <th class="px-6 py-4">Customer</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Date</th>
                        <th class="px-6 py-4 text-right">Total</th>
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
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $color }} capitalize shadow-sm">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 text-right font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 font-medium">No recent orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
