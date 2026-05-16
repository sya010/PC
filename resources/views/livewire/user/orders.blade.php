<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 bg-clip-text text-transparent bg-gradient-to-r from-indigo-600 to-purple-600">
                {{ __('messages.user_orders.title') }}
            </h1>
            <p class="text-gray-500 mt-1">{{ __('messages.user_orders.subtitle') }}</p>
        </div>
        <a href="{{ route('shop') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-100 transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
            {{ __('messages.user_orders.continue_shopping') }}
        </a>
    </div>

    @if(session()->has('error'))
        <div class="p-4 mb-6 text-rose-800 bg-rose-50 border border-rose-100 rounded-xl flex items-center shadow-sm">
            <svg class="w-5 h-5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="space-y-6">
        @forelse($orders as $order)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                <!-- Order Header -->
                <div class="bg-gray-50/50 px-6 py-4 border-b border-gray-100 flex flex-col md:flex-row justify-between md:items-center gap-4">
                    <div class="flex gap-8">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ __('messages.user_orders.order_placed') }}</p>
                            <p class="text-sm font-bold text-gray-900">{{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ __('messages.user_orders.total_amount') }}</p>
                            <p class="text-sm font-bold text-gray-900">{{ number_format($order->total_amount, 0) }} {{ __('messages.currency') }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">{{ __('messages.user_orders.order_id') }}</p>
                            <p class="text-sm font-bold text-gray-900">#{{ $order->id }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
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
                            {{ __('messages.status.' . $order->status) }}
                        </span>
                        
                        @if($order->payment_method === 'wayl' && $order->payment_status === 'pending' && $order->created_at->diffInMinutes(now()) < 58)
                            <button wire:click="retryWaylPayment({{ $order->id }})" wire:loading.attr="disabled" class="ms-2 px-4 py-1.5 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl text-xs font-bold shadow-sm hover:shadow-md transition-all flex items-center gap-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 active:scale-95">
                                <span wire:loading.remove wire:target="retryWaylPayment({{ $order->id }})">{{ __('messages.user_orders.pay_now') }}</span>
                                <span wire:loading wire:target="retryWaylPayment({{ $order->id }})" class="flex items-center gap-1">
                                    <svg class="animate-spin h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    {{ __('messages.user_orders.loading') }}
                                </span>
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Order Items -->
                <div class="p-6">
                    <div class="space-y-4">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-4 group">
                                <div class="w-16 h-16 bg-gray-50 rounded-xl flex-shrink-0 overflow-hidden border border-gray-100">
                                     @if($item->product)
                                        <img src="{{ $item->product->image }}" class="w-full h-full object-contain p-1 group-hover:scale-110 transition-transform duration-500 mix-blend-multiply">
                                     @else
                                        <div class="w-full h-full flex items-center justify-center text-gray-300">
                                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        </div>
                                     @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">{{ $item->product_name }}</h4>
                                    <p class="text-sm text-gray-500">{{ __('messages.checkout.qty') }}: {{ $item->quantity }} &times; {{ number_format($item->price, 0) }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-gray-900">{{ number_format($item->price * $item->quantity, 0) }} {{ __('messages.currency') }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-white rounded-3xl border border-gray-100 shadow-sm">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-indigo-50 rounded-full mb-6">
                    <svg class="w-12 h-12 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ __('messages.user_orders.no_orders') }}</h2>
                <p class="text-gray-500 mb-8 max-w-sm mx-auto">{{ __('messages.user_orders.no_orders_desc') }}</p>
                <a href="{{ route('shop') }}" class="inline-flex items-center px-8 py-3.5 bg-gradient-to-r from-indigo-600 to-purple-600 text-white font-bold rounded-xl shadow-lg hover:shadow-xl hover:-translate-y-1 transition-all">
                    {{ __('messages.user_orders.start_shopping') }}
                </a>
            </div>
        @endforelse

        <div class="mt-8">
            {{ $orders->links() }}
        </div>
    </div>
</div>
