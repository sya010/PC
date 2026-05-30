<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-brand-base dark:text-brand-light">
                {{ __('messages.admin.orders.title') }}
            </h1>
            <p class="text-content-muted dark:text-dark-200 mt-1">{{ __('messages.admin.orders.subtitle') }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-surface-primary dark:bg-dark-900 p-2 rounded-2xl border border-border-subtle dark:border-dark-800 shadow-sm mb-8 flex flex-col md:flex-row gap-2 transition-all hover:shadow-md">
        <!-- Search -->
        <div class="relative flex-1">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="{{ __('messages.admin.orders.search_placeholder') }}" 
                maxlength="100"
                class="w-full ps-12 pe-4 py-3.5 rounded-xl border-none focus:ring-0 text-content-primary dark:text-dark-100 placeholder-content-muted dark:placeholder-dark-400 bg-transparent"
            >
            <div class="absolute inset-y-0 start-0 ps-4 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-content-muted dark:text-dark-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="absolute inset-y-0 end-0 pe-4 flex items-center">
                <div wire:loading wire:target="search" class="animate-spin rounded-full h-5 w-5 border-b-2 border-brand-base"></div>
            </div>
        </div>

        <!-- Custom Status Filter -->
        @php
            $currentFilterLabel = match($statusFilter) {
                'pending' => __('messages.admin.orders.pending'),
                'processing' => __('messages.admin.orders.processing'),
                'completed' => __('messages.admin.orders.completed'),
                'cancelled' => __('messages.admin.orders.cancelled'),
                default => __('messages.admin.orders.all_statuses'),
            };
            $currentFilterDot = match($statusFilter) {
                'pending' => 'bg-status-warning',
                'processing' => 'bg-status-info',
                'completed' => 'bg-status-success',
                'cancelled' => 'bg-status-danger',
                default => 'bg-content-muted',
            };
        @endphp
        <div class="md:w-56 border-s border-border-subtle dark:border-dark-800 md:ps-2" x-data="{ open: false }">
            <div class="relative">
                <button type="button" @click="open = !open" @click.away="open = false"
                    class="w-full flex items-center justify-between gap-2 px-4 py-3.5 rounded-xl text-sm font-semibold cursor-pointer hover:bg-surface-secondary dark:hover:bg-dark-800 transition-colors text-content-secondary dark:text-dark-200">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $currentFilterDot }}"></span>
                        {{ $currentFilterLabel }}
                    </span>
                    <svg class="w-4 h-4 text-content-muted dark:text-dark-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="absolute z-50 w-full mt-2 bg-surface-primary dark:bg-dark-900 rounded-xl shadow-xl border border-border-subtle dark:border-dark-800 overflow-hidden" style="display: none;">
                    @foreach(['' => ['label' => __('messages.admin.orders.all_statuses'), 'dot' => 'bg-content-muted', 'hover' => 'hover:bg-surface-secondary dark:hover:bg-dark-800'], 'pending' => ['label' => __('messages.admin.orders.pending'), 'dot' => 'bg-status-warning', 'hover' => 'hover:bg-status-warning/10'], 'processing' => ['label' => __('messages.admin.orders.processing'), 'dot' => 'bg-status-info', 'hover' => 'hover:bg-status-info/10'], 'completed' => ['label' => __('messages.admin.orders.completed'), 'dot' => 'bg-status-success', 'hover' => 'hover:bg-status-success/10'], 'cancelled' => ['label' => __('messages.admin.orders.cancelled'), 'dot' => 'bg-status-danger', 'hover' => 'hover:bg-status-danger/10']] as $val => $opt)
                        <button type="button" wire:click="$set('statusFilter', '{{ $val }}')" @click="open = false" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium transition-colors {{ $opt['hover'] }} {{ $statusFilter === $val ? 'bg-brand-base/5 text-brand-accent' : 'text-content-secondary dark:text-dark-300' }}">
                            <span class="w-2 h-2 rounded-full {{ $opt['dot'] }}"></span>
                            {{ $opt['label'] }}
                            @if($statusFilter === $val)
                                <svg class="w-4 h-4 ms-auto text-brand-base" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800">
        <div class="overflow-x-auto md:overflow-x-visible min-h-[400px]">
            <table class="w-full text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }} text-sm text-gray-600 dark:text-dark-300">
                <thead class="bg-gray-50/50 dark:bg-dark-800/50 text-xs uppercase font-bold text-gray-500 dark:text-dark-400 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">{{ __('messages.admin.orders.order_id') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.orders.product') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.orders.customer') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.orders.date') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.orders.total') }}</th>
                        <th class="px-6 py-4">Payment</th>
                        <th class="px-6 py-4">{{ __('messages.admin.orders.status') }}</th>
                        <th class="px-6 py-4 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }}">{{ __('messages.admin.orders.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-800">
                    @forelse($orders as $order)
                        @php
                            $colors = [
                                'pending' => 'bg-amber-100 text-amber-800 border-amber-200 dark:bg-amber-900/20 dark:text-amber-300 dark:border-amber-800/30',
                                'processing' => 'bg-blue-100 text-blue-800 border-blue-200 dark:bg-blue-900/20 dark:text-blue-300 dark:border-blue-800/30',
                                'completed' => 'bg-emerald-100 text-emerald-800 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-300 dark:border-emerald-800/30',
                                'cancelled' => 'bg-rose-100 text-rose-800 border-rose-200 dark:bg-rose-900/20 dark:text-rose-300 dark:border-rose-800/30',
                            ];
                            $color = $colors[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200 dark:bg-dark-800 dark:text-dark-300 dark:border-dark-700';
                            $statusKeys = [
                                'pending' => __('messages.admin.orders.pending'),
                                'processing' => __('messages.admin.orders.processing'),
                                'completed' => __('messages.admin.orders.completed'),
                                'cancelled' => __('messages.admin.orders.cancelled'),
                            ];
                        @endphp
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-dark-800/30 transition-colors">
                            <td class="px-6 py-4 font-bold text-brand-base">#{{ $order->id }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2 flex-wrap max-w-[200px]">
                                    @foreach($order->items as $item)
                                        <div class="relative group cursor-pointer" title="{{ $item->product_name }} (x{{ $item->quantity }})">
                                            <div class="w-10 h-10 rounded-lg overflow-hidden bg-gray-50 dark:bg-dark-950 border border-gray-100 dark:border-dark-800 flex-shrink-0 flex items-center justify-center transition-transform hover:scale-105 shadow-sm">
                                                @if($item->product && $item->product->image_url)
                                                    <img src="{{ $item->product->image_url }}" class="w-full h-full object-contain p-0.5 dark:bg-white" alt="{{ $item->product_name }}">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center bg-gray-100 text-gray-400 dark:bg-dark-800 dark:text-dark-500">
                                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            @if($item->quantity > 1)
                                                <span class="absolute -top-1.5 -end-1.5 bg-brand-base text-white text-[9px] font-bold px-1.5 rounded-full border border-white dark:border-dark-900 leading-none py-0.5 shadow-sm">
                                                    {{ $item->quantity }}
                                                </span>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900 dark:text-dark-100">{{ $order->full_name }}</div>
                                <div class="text-xs text-gray-500 dark:text-dark-400 font-medium">{{ $order->email }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-500 dark:text-dark-400">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900 dark:text-dark-100">{{ number_format($order->total_amount) }} {{ __('messages.currency') }}</td>
                            <td class="px-6 py-4">
                                <div class="text-xs font-bold text-gray-400 dark:text-dark-400 uppercase mb-0.5">{{ $order->payment_method }}</div>
                                @php
                                    $pCol = match($order->payment_status) {
                                        'paid' => 'text-emerald-600 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-950/30 px-2 py-0.5 rounded',
                                        'failed' => 'text-rose-600 bg-rose-50 dark:text-rose-400 dark:bg-rose-950/30 px-2 py-0.5 rounded',
                                        default => 'text-amber-600 bg-amber-50 dark:text-amber-400 dark:bg-amber-950/30 px-2 py-0.5 rounded'
                                    };
                                @endphp
                                <span class="text-[10px] font-bold {{ $pCol }} uppercase tracking-wider">{{ $order->payment_status }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $color }} shadow-sm">
                                    {{ $statusKeys[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }}">
                                <div class="flex items-center gap-2 justify-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'start' : 'end' }}">
                                    <!-- View Button -->
                                    <a href="{{ route('admin.orders.view', $order->id) }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-brand-accent bg-brand-base/5 rounded-lg hover:bg-brand-base/10 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        {{ __('messages.admin.orders.view') }}
                                    </a>

                                    <!-- Status Dropdown -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" @click.away="open = false" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-gray-600 dark:text-dark-300 bg-gray-50 dark:bg-dark-800 border border-gray-200 dark:border-dark-700 rounded-lg hover:bg-gray-100 dark:hover:bg-dark-700 hover:text-gray-800 dark:hover:text-dark-100 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                            {{ __('messages.admin.orders.update_status') }}
                                        </button>
                                        <div x-show="open" 
                                            x-transition:enter="transition ease-out duration-200" 
                                            x-transition:enter-start="opacity-0 translate-y-1 scale-95" 
                                            x-transition:enter-end="opacity-100 translate-y-0 scale-100" 
                                            x-transition:leave="transition ease-in duration-150" 
                                            x-transition:leave-start="opacity-100 translate-y-0 scale-100" 
                                            x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                                            class="absolute z-50 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left-0' : 'right-0' }} mt-2 w-48 bg-white/95 dark:bg-dark-900/95 backdrop-blur-md rounded-2xl shadow-2xl border border-gray-150 dark:border-dark-750 py-1.5 overflow-hidden" 
                                            style="display: none;">
                                            @foreach(['pending' => ['label' => __('messages.admin.orders.pending'), 'dot' => 'bg-amber-400', 'hover' => 'hover:bg-amber-50 hover:text-amber-700 dark:hover:bg-amber-950/30 dark:hover:text-amber-300'], 'processing' => ['label' => __('messages.admin.orders.processing'), 'dot' => 'bg-blue-400', 'hover' => 'hover:bg-blue-50 hover:text-blue-700 dark:hover:bg-blue-950/30 dark:hover:text-blue-300'], 'completed' => ['label' => __('messages.admin.orders.completed'), 'dot' => 'bg-emerald-400', 'hover' => 'hover:bg-emerald-50 hover:text-emerald-700 dark:hover:bg-emerald-950/30 dark:hover:text-emerald-300'], 'cancelled' => ['label' => __('messages.admin.orders.cancelled'), 'dot' => 'bg-rose-400', 'hover' => 'hover:bg-rose-50 hover:text-rose-700 dark:hover:bg-rose-950/30 dark:hover:text-rose-300']] as $sVal => $sOpt)
                                                <button wire:click="updateStatus({{ $order->id }}, '{{ $sVal }}')" @click="open = false" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium transition-colors {{ $sOpt['hover'] }} {{ $order->status === $sVal ? 'bg-gray-50 dark:bg-dark-800 font-bold text-gray-900 dark:text-dark-100' : 'text-gray-700 dark:text-dark-300' }}">
                                                    <span class="w-2 h-2 rounded-full {{ $sOpt['dot'] }}"></span>
                                                    {{ $sOpt['label'] }}
                                                    @if($order->status === $sVal)
                                                        <svg class="w-4 h-4 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'mr-auto' : 'ml-auto' }} text-brand-base" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                                    @endif
                                                </button>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-400 dark:text-dark-500 font-medium">
                                {{ __('messages.admin.orders.no_orders') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 dark:border-dark-800 bg-gray-50/30 dark:bg-dark-900/30">
            {{ $orders->links() }}
        </div>
    </div>
</div>
