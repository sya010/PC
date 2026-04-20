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
    <div class="bg-white p-2 rounded-2xl border border-gray-100 shadow-sm mb-8 flex flex-col md:flex-row gap-2 transform transition-all hover:shadow-md">
        <div class="relative flex-1">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="{{ __('messages.admin.orders.search_placeholder') }}" 
                maxlength="100"
                class="w-full {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'pr-12 pl-4' : 'pl-12 pr-4' }} py-3.5 rounded-xl border-none focus:ring-0 text-gray-900 placeholder-gray-400 bg-transparent"
            >
            <div class="absolute inset-y-0 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right-0 pr-4' : 'left-0 pl-4' }} flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="absolute inset-y-0 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left-0 pl-4' : 'right-0 pr-4' }} flex items-center">
                <div wire:loading wire:target="search" class="animate-spin rounded-full h-5 w-5 border-b-2 border-indigo-600"></div>
            </div>
        </div>
        <div class="md:w-56 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'border-r border-gray-100 md:pr-2' : 'border-l border-gray-100 md:pl-2' }}">
            <select wire:model.live="statusFilter" class="w-full px-4 py-3.5 rounded-xl border-none focus:ring-0 text-gray-700 font-medium bg-transparent cursor-pointer hover:bg-gray-50 transition-colors">
                <option value="">{{ __('messages.admin.orders.all_statuses') }}</option>
                <option value="pending">{{ __('messages.admin.orders.pending') }}</option>
                <option value="processing">{{ __('messages.admin.orders.processing') }}</option>
                <option value="completed">{{ __('messages.admin.orders.completed') }}</option>
                <option value="cancelled">{{ __('messages.admin.orders.cancelled') }}</option>
            </select>
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
                        <tr class="hover:bg-gray-50 transition-colors group">
                            <td class="px-6 py-4 font-bold text-indigo-600">#{{ $order->id }}</td>
                            <td class="px-6 py-4">
                                <div>
                                    <div class="font-bold text-gray-900">{{ $order->full_name }}</div>
                                    <div class="text-xs text-gray-500 font-medium">{{ $order->email }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                            <td class="px-6 py-4 font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
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
                            <td class="px-6 py-4 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }}">
                                <div class="relative inline-block text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }}" x-data="{ open: false }">
                                    <button @click="open = !open" @click.away="open = false" type="button" class="inline-flex items-center justify-center rounded-lg border border-gray-200 px-3 py-1.5 text-xs font-bold text-gray-700 bg-white hover:bg-gray-50 hover:text-indigo-600 transition-colors">
                                        {{ __('messages.admin.orders.update_status') }}
                                        <svg class="{{ in_array(app()->getLocale(), ['ar', 'ku']) ? '-ml-1 mr-1' : '-mr-1 ml-1' }} h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                          <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                  
                                    <div x-show="open" class="origin-top-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }} absolute {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left-0' : 'right-0' }} mt-2 w-48 rounded-xl shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10 overflow-hidden" style="display: none;" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95">
                                      <div class="py-1">
                                        <button wire:click="updateStatus({{ $order->id }}, 'pending')" @click="open = false" class="group flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700">
                                            <span class="w-2 h-2 rounded-full bg-amber-400 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'ml-2' : 'mr-2' }}"></span>
                                            {{ __('messages.admin.orders.pending') }}
                                        </button>
                                        <button wire:click="updateStatus({{ $order->id }}, 'processing')" @click="open = false" class="group flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700">
                                            <span class="w-2 h-2 rounded-full bg-blue-400 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'ml-2' : 'mr-2' }}"></span>
                                            {{ __('messages.admin.orders.processing') }}
                                        </button>
                                        <button wire:click="updateStatus({{ $order->id }}, 'completed')" @click="open = false" class="group flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-emerald-50 hover:text-emerald-700">
                                            <span class="w-2 h-2 rounded-full bg-emerald-400 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'ml-2' : 'mr-2' }}"></span>
                                            {{ __('messages.admin.orders.completed') }}
                                        </button>
                                        <button wire:click="updateStatus({{ $order->id }}, 'cancelled')" @click="open = false" class="group flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-rose-50 hover:text-rose-700">
                                            <span class="w-2 h-2 rounded-full bg-rose-400 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'ml-2' : 'mr-2' }}"></span>
                                            {{ __('messages.admin.orders.cancelled') }}
                                        </button>
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
