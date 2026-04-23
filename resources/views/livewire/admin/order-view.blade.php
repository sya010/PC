<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
                {{ __('messages.admin.orders.order_id') }} #{{ $order->id }}
            </h1>
            <p class="text-gray-500 mt-1">{{ $order->created_at->format('F d, Y - h:i A') }}</p>
        </div>
        <a href="{{ route('admin.orders') }}" wire:navigate class="flex items-center gap-2 text-gray-500 hover:text-gray-900 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'M14 5l7 7m0 0l-7 7m7-7H3' : 'M10 19l-7-7m0 0l7-7m-7 7h18' }}" /></svg>
            {{ __('messages.admin.orders.back_to_orders') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <h3 class="text-lg font-bold text-gray-800">{{ __('messages.admin.orders.order_items') }}</h3>
                </div>
                <table class="w-full text-sm text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }}">
                    <thead class="bg-gray-50/50 text-xs uppercase text-gray-500 font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-3">{{ __('messages.admin.orders.product') }}</th>
                            <th class="px-6 py-3 text-center">{{ __('messages.admin.orders.qty') }}</th>
                            <th class="px-6 py-3 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }}">{{ __('messages.admin.orders.price') }}</th>
                            <th class="px-6 py-3 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }}">{{ __('messages.admin.orders.subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($order->items as $item)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-bold text-gray-900">{{ $item->product_name }}</div>
                                </td>
                                <td class="px-6 py-4 text-center font-medium text-gray-600">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }} text-gray-600">{{ number_format($item->price) }} {{ __('messages.currency') }}</td>
                                <td class="px-6 py-4 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }} font-bold text-gray-900">{{ number_format($item->price * $item->quantity) }} {{ __('messages.currency') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-50 to-purple-50 border-t border-indigo-100 flex items-center justify-between">
                    <span class="font-bold text-gray-700 uppercase text-xs tracking-wider">{{ __('messages.admin.orders.total') }}</span>
                    <span class="text-xl font-bold text-indigo-600">{{ number_format($order->total_amount) }} {{ __('messages.currency') }}</span>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Status Card -->
            @php
                $statusStyles = [
                    'pending' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-800', 'border' => 'border-amber-200', 'dot' => 'bg-amber-500'],
                    'processing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800', 'border' => 'border-blue-200', 'dot' => 'bg-blue-500'],
                    'completed' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-800', 'border' => 'border-emerald-200', 'dot' => 'bg-emerald-500'],
                    'cancelled' => ['bg' => 'bg-rose-100', 'text' => 'text-rose-800', 'border' => 'border-rose-200', 'dot' => 'bg-rose-500'],
                ];
                $cs = $statusStyles[$order->status] ?? $statusStyles['pending'];
                $statusLabels = [
                    'pending' => __('messages.admin.orders.pending'),
                    'processing' => __('messages.admin.orders.processing'),
                    'completed' => __('messages.admin.orders.completed'),
                    'cancelled' => __('messages.admin.orders.cancelled'),
                ];
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">{{ __('messages.admin.orders.status') }}</h3>
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-3 h-3 rounded-full {{ $cs['dot'] }} animate-pulse"></span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-bold border {{ $cs['bg'] }} {{ $cs['text'] }} {{ $cs['border'] }}">
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </span>
                </div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">{{ __('messages.admin.orders.update_status') }}</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach(['pending' => ['label' => __('messages.admin.orders.pending'), 'color' => 'amber'], 'processing' => ['label' => __('messages.admin.orders.processing'), 'color' => 'blue'], 'completed' => ['label' => __('messages.admin.orders.completed'), 'color' => 'emerald'], 'cancelled' => ['label' => __('messages.admin.orders.cancelled'), 'color' => 'rose']] as $sKey => $sData)
                        <button wire:click="updateStatus('{{ $sKey }}')" 
                            class="px-3 py-2 rounded-xl text-xs font-bold transition-all {{ $order->status === $sKey ? 'bg-'.$sData['color'].'-100 text-'.$sData['color'].'-700 ring-2 ring-'.$sData['color'].'-300 shadow-sm' : 'bg-gray-50 text-gray-500 hover:bg-'.$sData['color'].'-50 hover:text-'.$sData['color'].'-600 border border-gray-100' }}">
                            {{ $sData['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    {{ __('messages.admin.orders.customer_info') }}
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ __('messages.admin.orders.name') }}</span>
                        <span class="font-semibold text-gray-900">{{ $order->full_name }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ __('messages.admin.orders.email') }}</span>
                        <span class="font-semibold text-gray-900">{{ $order->email }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ __('messages.admin.orders.phone') }}</span>
                        <span class="font-semibold text-gray-900">{{ $order->phone }}</span>
                    </div>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    {{ __('messages.admin.orders.shipping_info') }}
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ __('messages.admin.orders.address') }}</span>
                        <span class="font-semibold text-gray-900">{{ $order->address }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ __('messages.admin.orders.city') }}</span>
                        <span class="font-semibold text-gray-900">{{ $order->city }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500">{{ __('messages.admin.orders.payment_method') }}</span>
                        <span class="font-semibold text-gray-900">{{ ucfirst($order->payment_method) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
