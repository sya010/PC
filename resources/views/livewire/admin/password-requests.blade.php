<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-brand-base dark:text-brand-light">
                {{ __('messages.admin.sidebar.password_requests') }}
            </h1>
            <p class="text-gray-500 dark:text-dark-300 mt-1">
                {{ __('messages.admin.password_requests.subtitle') }}
            </p>
        </div>
    </div>

    <!-- Alert Success Notification -->
    @if (session()->has('success'))
        <div class="mb-6 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-600 dark:text-green-400 font-semibold flex items-center gap-3 animate-in fade-in duration-300">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            {{ session('success') }}
        </div>
    @endif

    <!-- Filters & Search -->
    <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search -->
            <div class="relative group">
                <input 
                    type="text" 
                    wire:model.live="search" 
                    placeholder="{{ __('messages.admin.password_requests.search_placeholder') }}"
                    class="w-full pl-10 pr-4 py-2.5 rounded-xl border border-gray-200 dark:border-dark-700 bg-gray-50/50 dark:bg-dark-950 text-gray-900 dark:text-dark-100 placeholder-gray-400 dark:placeholder-dark-500 focus:outline-none focus:ring-4 focus:ring-brand-accent/10 focus:border-brand-accent transition-all duration-200"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>

            <!-- Status Filter (Custom Alpine Dropdown) -->
            <div class="relative" x-data="{ 
                open: false, 
                selected: @entangle('status'),
                options: {
                    '': '{{ __('messages.admin.password_requests.all_statuses') }}',
                    'pending': '{{ __('messages.admin.password_requests.pending') }}',
                    'completed': '{{ __('messages.admin.password_requests.completed') }}',
                    'cancelled': '{{ __('messages.admin.password_requests.cancelled') }}'
                }
            }">
                <button type="button" @click="open = !open" @click.away="open = false" 
                    class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-dark-700 bg-gray-50/50 dark:bg-dark-950 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }} text-gray-900 dark:text-dark-100 cursor-pointer focus:outline-none focus:ring-4 focus:ring-brand-accent/10 focus:border-brand-accent transition-all duration-200 flex items-center justify-between shadow-sm">
                    <span x-text="options[selected] || '{{ __('messages.admin.password_requests.all_statuses') }}'" class="font-medium text-gray-900 dark:text-dark-100"></span>
                    <svg class="w-5 h-5 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                </button>
                
                <!-- Dropdown options list -->
                <div x-show="open" 
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute z-50 w-full mt-2 bg-white dark:bg-dark-800 rounded-xl shadow-xl border border-gray-100 dark:border-dark-700 overflow-hidden py-1 max-h-60 overflow-y-auto"
                    style="display: none;">
                    
                    <template x-for="(label, value) in options" :key="value">
                        <div @click="selected = value; open = false" 
                            class="px-4 py-3 hover:bg-brand-base/5 dark:hover:bg-dark-700/50 cursor-pointer flex items-center justify-between group/item transition-colors">
                            <span x-text="label" class="font-medium text-gray-900 dark:text-dark-200 group-hover/item:text-brand-base"></span>
                            <span x-show="selected === value" class="text-brand-base">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                            </span>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Data Grid Table -->
    <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }} border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 dark:bg-dark-950 border-b border-gray-100 dark:border-dark-800 text-xs font-bold text-gray-500 dark:text-dark-400 uppercase tracking-wider">
                        <th class="px-6 py-4">{{ __('messages.admin.password_requests.email') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.password_requests.reason') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.password_requests.date_submitted') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.password_requests.status') }}</th>
                        <th class="px-6 py-4 text-center">{{ __('messages.admin.password_requests.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-dark-800 text-sm font-medium text-gray-700 dark:text-dark-200">
                    @forelse($requests as $req)
                        <tr class="hover:bg-gray-50/50 dark:hover:bg-dark-900/50 transition-colors">
                            <td class="px-6 py-4">
                                <span class="font-bold text-gray-900 dark:text-dark-100 select-all">{{ $req->email }}</span>
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-dark-400 text-xs">
                                {{ __('messages.auth.reason_' . strtolower(explode(' ', trim($req->reason))[0])) ?: $req->reason }}
                            </td>
                            <td class="px-6 py-4 text-gray-500 dark:text-dark-400">
                                {{ $req->created_at->format('Y-m-d H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                @if($req->status === 'pending')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-900/30">
                                        {{ __('messages.admin.password_requests.pending') }}
                                    </span>
                                @elseif($req->status === 'completed')
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 dark:bg-green-950/30 text-green-600 dark:text-green-400 border border-green-100 dark:border-green-900/30">
                                        {{ __('messages.admin.password_requests.completed') }}
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400 border border-rose-100 dark:border-rose-900/30">
                                        {{ __('messages.admin.password_requests.cancelled') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center flex items-center justify-center gap-2">
                                <a href="{{ route('admin.password-requests.review', $req->id) }}" wire:navigate class="px-3 py-1.5 bg-brand-base/10 dark:bg-brand-base/20 text-brand-base dark:text-brand-light rounded-lg hover:bg-brand-base hover:text-white font-bold transition-all text-xs">
                                    {{ __('messages.admin.password_requests.review_request') }}
                                </a>
                                <button onclick="confirm('{{ __('messages.admin.password_requests.delete_request_confirm') }}') || event.stopImmediatePropagation()" wire:click="deleteRequest({{ $req->id }})" class="p-2 text-gray-400 hover:text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400 dark:text-dark-500">
                                <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                {{ __('messages.admin.password_requests.no_requests') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 dark:border-dark-800">
                {{ $requests->links() }}
            </div>
        @endif
    </div>
</div>
