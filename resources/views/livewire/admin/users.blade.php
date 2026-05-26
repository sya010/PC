<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-brand-base dark:text-brand-light">
                {{ __('messages.admin.users.title') }}
            </h1>
            <p class="text-content-secondary dark:text-dark-200 mt-1">{{ __('messages.admin.users.subtitle') }}</p>
        </div>
        <div>
            <a href="{{ route('admin.users.create') }}" wire:navigate class="px-5 py-3 bg-brand-base text-white font-bold rounded-xl shadow-lg shadow-brand-base/20 hover:bg-brand-accent transition-all flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" y1="11" x2="19" y2="17"/><line x1="16" y1="14" x2="22" y2="14"/></svg>
                {{ __('messages.admin.users.create_admin') }}
            </a>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-surface-primary dark:bg-dark-800 p-2 rounded-2xl border border-border-subtle dark:border-dark-700 shadow-sm mb-8 max-w-2xl transform transition-all hover:shadow-md">
        <div class="relative">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="{{ __('messages.admin.users.search_placeholder') }}" 
                maxlength="100"
                class="w-full {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'pr-12 pl-4' : 'pl-12 pr-4' }} py-3.5 rounded-xl border-none focus:ring-0 text-content-primary dark:text-dark-100 placeholder-content-muted dark:placeholder-dark-400 bg-transparent"
            >
            <div class="absolute inset-y-0 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right-0 pr-4' : 'left-0 pl-4' }} flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-content-muted dark:text-dark-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="absolute inset-y-0 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left-0 pl-4' : 'right-0 pr-4' }} flex items-center">
                <div wire:loading wire:target="search" class="animate-spin rounded-full h-5 w-5 border-b-2 border-brand-base"></div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-500/10 dark:bg-green-500/20 border border-green-500/20 dark:border-green-500/30 text-green-600 dark:text-green-400 rounded-xl flex items-center gap-3 shadow-md shadow-green-500/5 animate-in fade-in slide-in-from-top-4 duration-300" role="alert">
            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-green-500/20 flex items-center justify-center text-green-600 dark:text-green-400 animate-bounce">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
            </div>
            <div>
                <span class="font-bold text-sm tracking-wide">{{ session('success') }}</span>
            </div>
        </div>
    @endif
    
    @if (session('error'))
        <div class="mb-6 p-4 bg-rose-500/10 dark:bg-rose-500/20 border border-rose-500/20 dark:border-rose-500/30 text-rose-600 dark:text-rose-400 rounded-xl flex items-center gap-3 shadow-md shadow-rose-500/5 animate-in fade-in slide-in-from-top-4 duration-300" role="alert">
            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-rose-500/20 flex items-center justify-center text-rose-600 dark:text-rose-400 animate-pulse">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div>
                <span class="font-bold text-sm tracking-wide">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    <!-- Users Table -->
    <div class="bg-surface-primary dark:bg-dark-800 rounded-2xl shadow-sm border border-border-subtle dark:border-dark-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }} text-sm text-content-secondary dark:text-dark-200">
                <thead class="bg-surface-secondary dark:bg-dark-900/50 text-xs uppercase font-bold text-content-muted dark:text-dark-300 tracking-wider">
                    <tr>
                        <th class="px-6 py-4">{{ __('messages.admin.users.user') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.users.role') }}</th>
                        <th class="px-6 py-4">{{ __('messages.admin.users.joined_date') }}</th>
                        <th class="px-6 py-4 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }}">{{ __('messages.admin.users.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-border-subtle dark:divide-dark-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-surface-secondary dark:hover:bg-dark-700 transition-colors group">
                            <td class="px-6 py-4">
                                <a href="{{ route('admin.users.view', $user->id) }}" wire:navigate class="flex items-center gap-4 group/user">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-brand-base/10 to-brand-base/10 flex items-center justify-center text-brand-accent font-bold text-lg shadow-sm border border-brand-base/5 group-hover/user:scale-110 transition-transform">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-content-primary dark:text-dark-100 group-hover/user:text-brand-base transition-colors">{{ $user->name }}</div>
                                        <div class="text-xs text-content-muted dark:text-dark-300 font-medium">{{ $user->email }}</div>
                                    </div>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->is_blocked)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-accent/10 text-brand-accent dark:text-brand-light border border-brand-accent/20">
                                        {{ __('messages.admin.users.blocked') }}
                                    </span>
                                @elseif($user->is_admin)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-base/10 text-brand-dark dark:text-brand-light border border-brand-base/20">
                                        {{ __('messages.admin.users.admin_role') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-surface-secondary dark:bg-dark-700 text-content-secondary dark:text-dark-200 border border-border-subtle dark:border-dark-600">
                                        {{ __('messages.admin.users.user_role') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-content-secondary dark:text-dark-200">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }}">
                                <div class="flex items-center {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'justify-start' : 'justify-end' }} gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    @if($user->id !== auth()->id())
                                        @if($user->is_admin)
                                            <button wire:click="startConfirmation({{ $user->id }}, 'demote')" class="px-3 py-1.5 text-xs font-bold text-brand-light bg-brand-light/20 dark:text-brand-light dark:bg-brand-light/30 rounded-lg hover:bg-brand-light/30 dark:hover:bg-brand-light/40 transition-colors">
                                                {{ __('messages.admin.users.demote') }}
                                            </button>
                                            <a href="{{ route('admin.users.edit', $user->id) }}" wire:navigate class="px-3 py-1.5 text-xs font-bold text-amber-600 bg-amber-50 hover:bg-amber-100 dark:text-amber-400 dark:bg-amber-950/30 rounded-lg transition-colors flex items-center gap-1 border border-amber-100 dark:border-amber-900/30">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                {{ __('messages.admin.products.edit') }}
                                            </a>
                                        @else
                                            <a href="{{ route('admin.users.edit', $user->id) }}" wire:navigate class="px-3 py-1.5 text-xs font-bold text-amber-600 bg-amber-50 hover:bg-amber-100 dark:text-amber-400 dark:bg-amber-950/30 rounded-lg transition-colors flex items-center gap-1 border border-amber-100 dark:border-amber-900/30">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                {{ __('messages.admin.products.edit') }}
                                            </a>
                                            <button wire:click="startConfirmation({{ $user->id }}, 'promote')" class="px-3 py-1.5 text-xs font-bold text-brand-accent bg-brand-base/10 rounded-lg hover:bg-brand-base/20 transition-colors">
                                                {{ __('messages.admin.users.make_admin') }}
                                            </button>
                                        @endif

                                        @if($user->is_blocked)
                                            <button wire:click="startConfirmation({{ $user->id }}, 'unblock')" class="px-3 py-1.5 text-xs font-bold text-brand-base bg-brand-base/20 dark:text-brand-light dark:bg-brand-base/30 rounded-lg hover:bg-brand-base/30 dark:hover:bg-brand-base/40 transition-colors">
                                                {{ __('messages.admin.users.unblock') }}
                                            </button>
                                        @else
                                            <button wire:click="startConfirmation({{ $user->id }}, 'block')" class="px-3 py-1.5 text-xs font-bold text-brand-accent bg-brand-accent/20 dark:text-brand-light dark:bg-brand-accent/30 rounded-lg hover:bg-brand-accent/30 dark:hover:bg-brand-accent/40 transition-colors">
                                                {{ __('messages.admin.users.block') }}
                                            </button>
                                        @endif

                                        <button wire:click="startConfirmation({{ $user->id }}, 'delete')" class="p-1.5 text-content-muted dark:text-dark-300 hover:text-brand-accent transition-colors hover:bg-brand-accent/10 dark:hover:bg-brand-accent/20 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    @else
                                        <span class="text-xs text-content-muted dark:text-dark-300 italic">{{ __('messages.admin.users.you') }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-content-muted dark:text-dark-300 font-medium">
                                {{ __('messages.admin.users.no_users') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-border-subtle dark:border-dark-700 bg-surface-secondary/30 dark:bg-dark-900/30">
            {{ $users->links() }}
        </div>
    </div>

    <!-- Unified Alpine/Livewire Dynamic Action Confirmation Modal -->
    <div 
        x-data="{ show: @entangle('confirmingAction') }" 
        x-show="show" 
        class="fixed inset-0 z-50 overflow-y-auto"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display: none;"
    >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity"></div>

        <!-- Modal Dialog -->
        <div class="flex min-h-full items-center justify-center p-4 text-center">
            <div 
                x-show="show"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-dark-800 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }} shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-border-subtle dark:border-dark-700"
                @click.away="$wire.cancelConfirmation()"
            >
                <!-- Close Button -->
                <button type="button" @click="$wire.cancelConfirmation()" class="absolute top-4 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left-4' : 'right-4' }} p-1.5 text-gray-400 hover:text-gray-500 hover:bg-gray-100 dark:hover:bg-dark-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>

                <!-- Modal Content -->
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <!-- Dynamic Icon based on Action -->
                        @if(in_array($confirmingAction, ['delete', 'block', 'demote']))
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-brand-accent/10 text-brand-accent">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                            </div>
                        @else
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-brand-base/10 text-brand-base">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        @endif
                        
                        <div class="flex-1 mt-0">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-dark-100">
                                @if($confirmingAction === 'promote')
                                    {{ __('messages.admin.users.confirm_promote_title') }}
                                @elseif($confirmingAction === 'demote')
                                    {{ __('messages.admin.users.confirm_demote_title') }}
                                @elseif($confirmingAction === 'block')
                                    {{ __('messages.admin.users.confirm_block_title') }}
                                @elseif($confirmingAction === 'unblock')
                                    {{ __('messages.admin.users.confirm_unblock_title') }}
                                @elseif($confirmingAction === 'delete')
                                    {{ __('messages.admin.users.confirm_delete_title') }}
                                @endif
                            </h3>
                            <p class="mt-2 text-sm text-gray-500 dark:text-dark-200 leading-relaxed">
                                @if($confirmingAction === 'promote')
                                    {{ str_replace(':name', $confirmingUserName, __('messages.admin.users.confirm_promote')) }}
                                @elseif($confirmingAction === 'demote')
                                    {{ str_replace(':name', $confirmingUserName, __('messages.admin.users.confirm_demote')) }}
                                @elseif($confirmingAction === 'block')
                                    {{ str_replace(':name', $confirmingUserName, __('messages.admin.users.confirm_block')) }}
                                @elseif($confirmingAction === 'unblock')
                                    {{ str_replace(':name', $confirmingUserName, __('messages.admin.users.confirm_unblock')) }}
                                @elseif($confirmingAction === 'delete')
                                    {{ str_replace(':name', $confirmingUserName, __('messages.admin.users.confirm_delete')) }}
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="bg-gray-50 dark:bg-dark-900/50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-2xl border-t border-border-subtle dark:border-dark-700">
                    <button 
                        type="button" 
                        wire:click="executeAction" 
                        class="px-5 py-2.5 font-bold rounded-xl shadow-md transition-all text-sm text-white {{ in_array($confirmingAction, ['delete', 'block', 'demote']) ? 'bg-brand-accent hover:bg-brand-accent/90' : 'bg-brand-base hover:bg-brand-base/90' }}"
                    >
                        @if($confirmingAction === 'promote')
                            {{ __('messages.admin.users.confirm_promote_btn') }}
                        @elseif($confirmingAction === 'demote')
                            {{ __('messages.admin.users.confirm_demote_btn') }}
                        @elseif($confirmingAction === 'block')
                            {{ __('messages.admin.users.confirm_block_btn') }}
                        @elseif($confirmingAction === 'unblock')
                            {{ __('messages.admin.users.confirm_unblock_btn') }}
                        @elseif($confirmingAction === 'delete')
                            {{ __('messages.admin.users.confirm_delete_btn') }}
                        @endif
                    </button>
                    <button 
                        type="button" 
                        wire:click="cancelConfirmation" 
                        class="px-5 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl text-sm hover:bg-gray-50 hover:border-gray-300 transition-all dark:bg-dark-800 dark:border-dark-700 dark:text-dark-200 dark:hover:bg-dark-700"
                    >
                        {{ __('messages.admin.product_form.cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
