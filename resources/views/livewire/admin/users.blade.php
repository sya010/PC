<div>
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-content-primary dark:text-dark-100 bg-clip-text text-transparent bg-gradient-to-r from-brand-base to-brand-accent">
                {{ __('messages.admin.users.title') }}
            </h1>
            <p class="text-content-secondary dark:text-dark-400 mt-1">{{ __('messages.admin.users.subtitle') }}</p>
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
                class="w-full {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'pr-12 pl-4' : 'pl-12 pr-4' }} py-3.5 rounded-xl border-none focus:ring-0 text-content-primary dark:text-dark-100 placeholder-content-muted dark:placeholder-dark-500 bg-transparent"
            >
            <div class="absolute inset-y-0 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right-0 pr-4' : 'left-0 pl-4' }} flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-content-muted dark:text-dark-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="absolute inset-y-0 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left-0 pl-4' : 'right-0 pr-4' }} flex items-center">
                <div wire:loading wire:target="search" class="animate-spin rounded-full h-5 w-5 border-b-2 border-brand-base"></div>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-brand-base/10 dark:bg-brand-base/20 border border-brand-base/20 dark:border-brand-base/30 text-brand-base dark:text-brand-light rounded-xl flex items-center gap-3 shadow-sm" role="alert">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    
    @if (session('error'))
        <div class="mb-6 p-4 bg-brand-accent/10 dark:bg-brand-accent/20 border border-brand-accent/20 dark:border-brand-accent/30 text-brand-accent dark:text-brand-light rounded-xl flex items-center gap-3 shadow-sm" role="alert">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Users Table -->
    <div class="bg-surface-primary dark:bg-dark-800 rounded-2xl shadow-sm border border-border-subtle dark:border-dark-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }} text-sm text-content-secondary dark:text-dark-400">
                <thead class="bg-surface-secondary dark:bg-dark-900/50 text-xs uppercase font-bold text-content-muted dark:text-dark-500 tracking-wider">
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
                                        <div class="text-xs text-content-muted dark:text-dark-500 font-medium">{{ $user->email }}</div>
                                    </div>
                                </a>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->is_blocked)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-accent/10 text-brand-accent dark:text-brand-light border border-brand-accent/20">
                                        {{ __('messages.admin.users.blocked') }}
                                    </span>
                                @elseif($user->is_admin)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-base/10 text-brand-dark border border-brand-base/20">
                                        {{ __('messages.admin.users.admin_role') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-surface-secondary dark:bg-dark-700 text-content-secondary dark:text-dark-400 border border-border-subtle dark:border-dark-600">
                                        {{ __('messages.admin.users.user_role') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-medium text-content-secondary dark:text-dark-400">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }}">
                                <div class="flex items-center {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'justify-start' : 'justify-end' }} gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                    @if($user->id !== auth()->id())
                                        @if($user->is_admin)
                                            <button wire:click="demote({{ $user->id }})" wire:confirm="{{ str_replace(':name', $user->name, __('messages.admin.users.confirm_demote')) }}" class="px-3 py-1.5 text-xs font-bold text-brand-light bg-brand-light/20 dark:text-brand-light dark:bg-brand-light/30 rounded-lg hover:bg-brand-light/30 dark:hover:bg-brand-light/40 transition-colors">
                                                {{ __('messages.admin.users.demote') }}
                                            </button>
                                        @else
                                            <button wire:click="promote({{ $user->id }})" wire:confirm="{{ str_replace(':name', $user->name, __('messages.admin.users.confirm_promote')) }}" class="px-3 py-1.5 text-xs font-bold text-brand-accent bg-brand-base/10 rounded-lg hover:bg-brand-base/20 transition-colors">
                                                {{ __('messages.admin.users.make_admin') }}
                                            </button>
                                        @endif

                                        @if($user->is_blocked)
                                            <button wire:click="unblock({{ $user->id }})" wire:confirm="{{ str_replace(':name', $user->name, __('messages.admin.users.confirm_unblock')) }}" class="px-3 py-1.5 text-xs font-bold text-brand-base bg-brand-base/20 dark:text-brand-light dark:bg-brand-base/30 rounded-lg hover:bg-brand-base/30 dark:hover:bg-brand-base/40 transition-colors">
                                                {{ __('messages.admin.users.unblock') }}
                                            </button>
                                        @else
                                            <button wire:click="block({{ $user->id }})" wire:confirm="{{ str_replace(':name', $user->name, __('messages.admin.users.confirm_block')) }}" class="px-3 py-1.5 text-xs font-bold text-brand-accent bg-brand-accent/20 dark:text-brand-light dark:bg-brand-accent/30 rounded-lg hover:bg-brand-accent/30 dark:hover:bg-brand-accent/40 transition-colors">
                                                {{ __('messages.admin.users.block') }}
                                            </button>
                                        @endif

                                        <button wire:click="delete({{ $user->id }})" wire:confirm="{{ __('messages.admin.users.confirm_delete') }}" class="p-1.5 text-content-muted dark:text-dark-500 hover:text-brand-accent transition-colors hover:bg-brand-accent/10 dark:hover:bg-brand-accent/20 rounded-lg">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    @else
                                        <span class="text-xs text-content-muted dark:text-dark-500 italic">{{ __('messages.admin.users.you') }}</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-content-muted dark:text-dark-500 font-medium">
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
</div>
