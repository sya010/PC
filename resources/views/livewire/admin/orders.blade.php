<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
                {{ __('messages.admin.orders.title') }}
            </h1>
            <p class="text-gray-500 mt-1">{{ __('messages.admin.orders.subtitle') }}</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white p-2 rounded-2xl border border-gray-100 shadow-sm mb-8 flex flex-col md:flex-row gap-2 transition-all hover:shadow-md">
        <!-- Search -->
        <div class="relative flex-1">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="{{ __('messages.admin.orders.search_placeholder') }}" 
                maxlength="100"
                class="w-full {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'pr-12 pl-4' : 'pl-12 pr-4' }} py-3.5 rounded-xl border-none focus:ring-0 text-gray-900 placeholder-gray-400 bg-transparent"
            >
            <div class="absolute inset-y-0 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right-0 pr-4' : 'left-0 pl-4' }} flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="absolute inset-y-0 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left-0 pl-4' : 'right-0 pr-4' }} flex items-center">
                <div wire:loading wire:target="search" class="animate-spin rounded-full h-5 w-5 border-b-2 border-indigo-600"></div>
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
                'pending' => 'bg-amber-400',
                'processing' => 'bg-blue-400',
                'completed' => 'bg-emerald-400',
                'cancelled' => 'bg-rose-400',
                default => 'bg-gray-400',
            };
        @endphp
        <div class="md:w-56 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'border-r border-gray-100 md:pr-2' : 'border-l border-gray-100 md:pl-2' }}" x-data="{ open: false }">
            <div class="relative">
                <button type="button" @click="open = !open" @click.away="open = false"
                    class="w-full flex items-center justify-between gap-2 px-4 py-3.5 rounded-xl text-sm font-semibold cursor-pointer hover:bg-gray-50 transition-colors text-gray-700">
                    <span class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full {{ $currentFilterDot }}"></span>
                        {{ $currentFilterLabel }}
                    </span>
                    <svg class="w-4 h-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="absolute z-50 w-full mt-2 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden" style="display: none;">
                    @foreach(['' => ['label' => __('messages.admin.orders.all_statuses'), 'dot' => 'bg-gray-400', 'hover' => 'hover:bg-gray-50'], 'pending' => ['label' => __('messages.admin.orders.pending'), 'dot' => 'bg-amber-400', 'hover' => 'hover:bg-amber-50'], 'processing' => ['label' => __('messages.admin.orders.processing'), 'dot' => 'bg-blue-400', 'hover' => 'hover:bg-blue-50'], 'completed' => ['label' => __('messages.admin.orders.completed'), 'dot' => 'bg-emerald-400', 'hover' => 'hover:bg-emerald-50'], 'cancelled' => ['label' => __('messages.admin.orders.cancelled'), 'dot' => 'bg-rose-400', 'hover' => 'hover:bg-rose-50']] as $val => $opt)
                        <button type="button" wire:click="$set('statusFilter', '{{ $val }}')" @click="open = false" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium transition-colors {{ $opt['hover'] }} {{ $statusFilter === $val ? 'bg-indigo-50/50 text-indigo-700' : 'text-gray-700' }}">
                            <span class="w-2 h-2 rounded-full {{ $opt['dot'] }}"></span>
                            {{ $opt['label'] }}
                            @if($statusFilter === $val)
                                <svg class="w-4 h-4 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'mr-auto' : 'ml-auto' }} text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }} text-sm text-gray-600">
                <thead class="bg-gray-50/50 text-xs uppercase font-bold text-gray-500 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">{{ __('messages.admin.orders.order_id') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.orders.customer') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.orders.date') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.orders.total') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.orders.status') }}</th>
                        <th class="px-6 py-4 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }}">{{ __('messages.admin.orders.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($orders as $order)
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
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-4 font-bold text-indigo-600">#{{ $order->id }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $order->full_name }}</div>
                                <div class="text-xs text-gray-500 font-medium">{{ $order->email }}</div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900">{{ number_format($order->total_amount) }} {{ __('messages.currency') }}</td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $color }} shadow-sm">
                                    {{ $statusKeys[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }}">
                                <div class="flex items-center gap-2 justify-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'start' : 'end' }}">
                                    <!-- View Button -->
                                    <a href="{{ route('admin.orders.view', $order->id) }}" wire:navigate class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-indigo-700 bg-indigo-50 rounded-lg hover:bg-indigo-100 transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        {{ __('messages.admin.orders.view') }}
                                    </a>

                                    <!-- Status Dropdown -->
                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" @click.away="open = false" type="button" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-bold text-gray-600 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 hover:text-gray-800 transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                                            {{ __('messages.admin.orders.update_status') }}
                                        </button>
                                        <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
                                            class="absolute z-50 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left-0' : 'right-0' }} mt-2 w-44 bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden" style="display: none;">
                                            @foreach(['pending' => ['label' => __('messages.admin.orders.pending'), 'dot' => 'bg-amber-400', 'hover' => 'hover:bg-amber-50 hover:text-amber-700'], 'processing' => ['label' => __('messages.admin.orders.processing'), 'dot' => 'bg-blue-400', 'hover' => 'hover:bg-blue-50 hover:text-blue-700'], 'completed' => ['label' => __('messages.admin.orders.completed'), 'dot' => 'bg-emerald-400', 'hover' => 'hover:bg-emerald-50 hover:text-emerald-700'], 'cancelled' => ['label' => __('messages.admin.orders.cancelled'), 'dot' => 'bg-rose-400', 'hover' => 'hover:bg-rose-50 hover:text-rose-700']] as $sVal => $sOpt)
                                                <button wire:click="updateStatus({{ $order->id }}, '{{ $sVal }}')" @click="open = false" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-medium transition-colors {{ $sOpt['hover'] }} {{ $order->status === $sVal ? 'bg-gray-50 font-bold' : 'text-gray-700' }}">
                                                    <span class="w-2 h-2 rounded-full {{ $sOpt['dot'] }}"></span>
                                                    {{ $sOpt['label'] }}
                                                    @if($order->status === $sVal)
                                                        <svg class="w-4 h-4 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'mr-auto' : 'ml-auto' }} text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
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
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400 font-medium">
                                {{ __('messages.admin.orders.no_orders') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/30">
            {{ $orders->links() }}
        </div>
    </div>
</div>
