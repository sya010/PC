<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-brand-base dark:text-brand-light">
                {{ __('messages.admin.orders.order_id') }} #{{ $order->id }}
            </h1>
            <p class="text-gray-500 dark:text-dark-300 mt-1">{{ $order->created_at->format('F d, Y - h:i A') }}</p>
        </div>
        <a href="{{ route('admin.orders') }}" wire:navigate class="flex items-center gap-2 text-gray-500 dark:text-dark-300 hover:text-gray-900 dark:hover:text-dark-100 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'M14 5l7 7m0 0l-7 7m7-7H3' : 'M10 19l-7-7m0 0l7-7m-7 7h18' }}" /></svg>
            {{ __('messages.admin.orders.back_to_orders') }}
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 dark:border-dark-800 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <h3 class="text-lg font-bold text-gray-800 dark:text-dark-100">{{ __('messages.admin.orders.order_items') }}</h3>
                </div>
                <table class="w-full text-sm text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }}">
                    <thead class="bg-gray-50/50 dark:bg-dark-800/50 text-xs uppercase text-gray-500 dark:text-dark-400 font-bold tracking-wider">
                        <tr>
                            <th class="px-6 py-3">{{ __('messages.admin.orders.product') }}</th>
                            <th class="px-6 py-3 text-center">{{ __('messages.admin.orders.qty') }}</th>
                            <th class="px-6 py-3 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }}">{{ __('messages.admin.orders.price') }}</th>
                            <th class="px-6 py-3 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }}">{{ __('messages.admin.orders.subtotal') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50 dark:divide-dark-800">
                        @foreach($order->items as $item)
                            <tr class="hover:bg-gray-50/50 dark:hover:bg-dark-800/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-xl overflow-hidden bg-gray-50 dark:bg-dark-950 border border-gray-100 dark:border-dark-800 flex-shrink-0 flex items-center justify-center shadow-sm">
                                            @if($item->product && $item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" class="w-full h-full object-contain p-1 dark:bg-white" alt="{{ $item->product_name }}">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 dark:bg-dark-800 dark:text-dark-500">
                                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <div class="font-bold text-gray-900 dark:text-dark-100">{{ $item->product_name }}</div>
                                            @if($item->product)
                                                <div class="text-xs text-brand-muted dark:text-dark-400 font-medium">{{ $item->product->category }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center font-medium text-gray-600 dark:text-dark-300">{{ $item->quantity }}</td>
                                <td class="px-6 py-4 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }} text-gray-600 dark:text-dark-300">{{ number_format($item->price) }} {{ __('messages.currency') }}</td>
                                <td class="px-6 py-4 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }} font-bold text-gray-900 dark:text-dark-100">{{ number_format($item->price * $item->quantity) }} {{ __('messages.currency') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4 bg-brand-base/5 border-t border-brand-base/10 dark:border-dark-800 flex items-center justify-between">
                    <span class="font-bold text-gray-700 dark:text-dark-300 uppercase text-xs tracking-wider">{{ __('messages.admin.orders.total') }}</span>
                    <span class="text-xl font-bold text-brand-base dark:text-brand-light">{{ number_format($order->total_amount) }} {{ __('messages.currency') }}</span>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="space-y-6">
            <!-- Status Card -->
            @php
                $statusStyles = [
                    'pending' => ['bg' => 'bg-amber-100 dark:bg-amber-900/20', 'text' => 'text-amber-800 dark:text-amber-300', 'border' => 'border-amber-200 dark:border-amber-800/30', 'dot' => 'bg-amber-500'],
                    'processing' => ['bg' => 'bg-blue-100 dark:bg-blue-900/20', 'text' => 'text-blue-800 dark:text-blue-300', 'border' => 'border-blue-200 dark:border-blue-800/30', 'dot' => 'bg-blue-500'],
                    'completed' => ['bg' => 'bg-emerald-100 dark:bg-emerald-900/20', 'text' => 'text-emerald-800 dark:text-emerald-300', 'border' => 'border-emerald-200 dark:border-emerald-800/30', 'dot' => 'bg-emerald-500'],
                    'cancelled' => ['bg' => 'bg-rose-100 dark:bg-rose-900/20', 'text' => 'text-rose-800 dark:text-rose-300', 'border' => 'border-rose-200 dark:border-rose-800/30', 'dot' => 'bg-rose-500'],
                ];
                $cs = $statusStyles[$order->status] ?? $statusStyles['pending'];
                $statusLabels = [
                    'pending' => __('messages.admin.orders.pending'),
                    'processing' => __('messages.admin.orders.processing'),
                    'completed' => __('messages.admin.orders.completed'),
                    'cancelled' => __('messages.admin.orders.cancelled'),
                ];
                $btnStyles = [
                    'pending' => [
                        'active' => 'bg-amber-100 text-amber-800 ring-2 ring-amber-300 dark:bg-amber-900/30 dark:text-amber-300 dark:ring-amber-800/50 shadow-sm',
                        'inactive' => 'bg-gray-50 text-gray-500 hover:bg-amber-50 hover:text-amber-600 border border-gray-100 dark:bg-dark-800 dark:text-dark-400 dark:border-dark-700 dark:hover:bg-amber-950/20 dark:hover:text-amber-300'
                    ],
                    'processing' => [
                        'active' => 'bg-blue-100 text-blue-800 ring-2 ring-blue-300 dark:bg-blue-900/30 dark:text-blue-300 dark:ring-blue-800/50 shadow-sm',
                        'inactive' => 'bg-gray-50 text-gray-500 hover:bg-blue-50 hover:text-blue-600 border border-gray-100 dark:bg-dark-800 dark:text-dark-400 dark:border-dark-700 dark:hover:bg-blue-950/20 dark:hover:text-blue-300'
                    ],
                    'completed' => [
                        'active' => 'bg-emerald-100 text-emerald-800 ring-2 ring-emerald-300 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-800/50 shadow-sm',
                        'inactive' => 'bg-gray-50 text-gray-500 hover:bg-emerald-50 hover:text-emerald-600 border border-gray-100 dark:bg-dark-800 dark:text-dark-400 dark:border-dark-700 dark:hover:bg-emerald-950/20 dark:hover:text-emerald-300'
                    ],
                    'cancelled' => [
                        'active' => 'bg-rose-100 text-rose-800 ring-2 ring-rose-300 dark:bg-rose-900/30 dark:text-rose-300 dark:ring-rose-800/50 shadow-sm',
                        'inactive' => 'bg-gray-50 text-gray-500 hover:bg-rose-50 hover:text-rose-600 border border-gray-100 dark:bg-dark-800 dark:text-dark-400 dark:border-dark-700 dark:hover:bg-rose-950/20 dark:hover:text-rose-300'
                    ],
                ];
            @endphp
            <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 p-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-dark-100 mb-4">{{ __('messages.admin.orders.status') }}</h3>
                <div class="flex items-center gap-2 mb-5">
                    <span class="w-3 h-3 rounded-full {{ $cs['dot'] }} animate-pulse"></span>
                    <span class="px-3 py-1.5 rounded-full text-sm font-bold border {{ $cs['bg'] }} {{ $cs['text'] }} {{ $cs['border'] }}">
                        {{ $statusLabels[$order->status] ?? $order->status }}
                    </span>
                </div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-dark-200 mb-2">{{ __('messages.admin.orders.update_status') }}</label>
                <div class="grid grid-cols-2 gap-2">
                    @foreach(['pending' => ['label' => __('messages.admin.orders.pending'), 'color' => 'amber'], 'processing' => ['label' => __('messages.admin.orders.processing'), 'color' => 'blue'], 'completed' => ['label' => __('messages.admin.orders.completed'), 'color' => 'emerald'], 'cancelled' => ['label' => __('messages.admin.orders.cancelled'), 'color' => 'rose']] as $sKey => $sData)
                        <button wire:click="updateStatus('{{ $sKey }}')" 
                            class="px-3 py-2 rounded-xl text-xs font-bold transition-all {{ $order->status === $sKey ? $btnStyles[$sKey]['active'] : $btnStyles[$sKey]['inactive'] }}">
                            {{ $sData['label'] }}
                        </button>
                    @endforeach
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 p-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-dark-100 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    {{ __('messages.admin.orders.customer_info') }}
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-dark-400">{{ __('messages.admin.orders.name') }}</span>
                        <span class="font-semibold text-gray-900 dark:text-dark-100">{{ $order->full_name }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-dark-400">{{ __('messages.admin.orders.email') }}</span>
                        <span class="font-semibold text-gray-900 dark:text-dark-100">{{ $order->email }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-dark-400">{{ __('messages.admin.orders.phone') }}</span>
                        <span class="font-semibold text-gray-900 dark:text-dark-100">{{ $order->phone }}</span>
                    </div>
                </div>
            </div>

            <!-- Shipping Info -->
            <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 p-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-dark-100 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-brand-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    {{ __('messages.admin.orders.shipping_info') }}
                </h3>
                <div class="space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-dark-400">{{ __('messages.admin.orders.address') }}</span>
                        <span class="font-semibold text-gray-900 dark:text-dark-100">{{ $order->address }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-dark-400">{{ __('messages.admin.orders.city') }}</span>
                        <span class="font-semibold text-gray-900 dark:text-dark-100">{{ $order->city }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-dark-400">{{ __('messages.admin.orders.payment_method') }}</span>
                        <span class="font-semibold text-gray-900 dark:text-dark-100">{{ strtoupper($order->payment_method) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 dark:text-dark-400">Payment Status</span>
                        @php
                            $pColors = [
                                'pending' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/20 dark:text-amber-300',
                                'paid' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-300',
                                'failed' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/20 dark:text-rose-300',
                            ];
                            $pColor = $pColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800 dark:bg-dark-800 dark:text-dark-300';
                        @endphp
                        <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $pColor }}">
                            {{ strtoupper($order->payment_status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
