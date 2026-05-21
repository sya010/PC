<div class="max-w-5xl mx-auto">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-dark-100 flex items-center gap-3">
                {{ $user->name }}
                @if($user->is_admin)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-base/10 text-brand-dark dark:bg-brand-base/20 dark:text-brand-light border border-brand-base/20 align-middle">
                        {{ __('messages.admin.users.admin_role') }}
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-600 dark:bg-dark-850 dark:text-dark-300 border border-gray-200 dark:border-dark-800 align-middle">
                        {{ __('messages.admin.users.user_role') }}
                    </span>
                @endif
            </h1>
            <p class="text-gray-500 dark:text-dark-300 mt-1 font-medium">{{ $user->email }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.users') }}" wire:navigate class="px-4 py-2 text-gray-500 dark:text-dark-300 font-medium hover:text-gray-900 dark:hover:text-dark-100 transition-colors">
                {{ __('messages.admin.user_view.back') }}
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Info -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 p-6">
                <h3 class="text-lg font-bold text-gray-800 dark:text-dark-100 mb-4 border-b border-gray-100 dark:border-dark-800 pb-2">{{ __('messages.admin.user_view.account_details') }}</h3>
                
                <div class="space-y-4">
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 dark:text-dark-400 uppercase tracking-wide">{{ __('messages.admin.user_view.user_id') }}</span>
                        <span class="text-gray-900 dark:text-dark-100 font-medium">#{{ $user->id }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 dark:text-dark-400 uppercase tracking-wide">{{ __('messages.admin.user_view.joined_on') }}</span>
                        <span class="text-gray-900 dark:text-dark-100 font-medium">{{ $user->created_at->format('M d, Y, h:i A') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold text-gray-400 dark:text-dark-400 uppercase tracking-wide">{{ __('messages.admin.user_view.last_updated') }}</span>
                        <span class="text-gray-900 dark:text-dark-100 font-medium">{{ $user->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Log (Only for Admins) -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-dark-100 flex items-center gap-2">
                        <svg class="w-5 h-5 text-brand-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        {{ __('messages.admin.user_view.activity_log') }}
                    </h3>
                    @if(!$user->is_admin)
                        <span class="text-xs text-orange-500 bg-orange-50 dark:bg-orange-950/20 dark:text-orange-300 px-2 py-1 rounded font-medium">{{ __('messages.admin.user_view.not_admin_note') }}</span>
                    @endif
                </div>

                @if($user->is_admin)
                    <div class="flow-root">
                        <ul role="list" class="-mb-8">
                            @forelse($logs as $log)
                                <li>
                                    <div class="relative pb-8">
                                        @if(!$loop->last)
                                            <span class="absolute top-4 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right-4 -mr-px' : 'left-4 -ml-px' }} h-full w-0.5 bg-gray-200 dark:bg-dark-800" aria-hidden="true"></span>
                                        @endif
                                        <div class="relative flex {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'space-x-reverse space-x-3' : 'space-x-3' }}">
                                            <div>
                                                @if($log->action == 'created_product')
                                                    <span class="h-8 w-8 rounded-full bg-green-100 dark:bg-green-950/30 flex items-center justify-center ring-8 ring-white dark:ring-dark-900">
                                                        <svg class="h-4 w-4 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
                                                    </span>
                                                @elseif($log->action == 'deleted_product')
                                                    <span class="h-8 w-8 rounded-full bg-red-100 dark:bg-red-950/30 flex items-center justify-center ring-8 ring-white dark:ring-dark-900">
                                                       <svg class="h-4 w-4 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                                    </span>
                                                @elseif(str_contains($log->action, 'update'))
                                                     <span class="h-8 w-8 rounded-full bg-blue-100 dark:bg-blue-950/30 flex items-center justify-center ring-8 ring-white dark:ring-dark-900">
                                                        <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                                    </span>
                                                @else
                                                    <span class="h-8 w-8 rounded-full bg-gray-100 dark:bg-dark-800 flex items-center justify-center ring-8 ring-white dark:ring-dark-900">
                                                        <svg class="h-4 w-4 text-gray-500 dark:text-dark-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'space-x-reverse space-x-4' : 'space-x-4' }}">
                                                <div>
                                                    <p class="text-sm text-gray-500 dark:text-dark-300">
                                                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}: 
                                                        <span class="font-medium text-gray-900 dark:text-dark-100">{{ $log->description }}</span>
                                                    </p>
                                                </div>
                                                <div class="text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'left' : 'right' }} text-sm whitespace-nowrap text-gray-500 dark:text-dark-400">
                                                    <time datetime="{{ $log->created_at }}">{{ $log->created_at->format('M d, H:i') }}</time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            @empty
                                <li class="text-center py-4 text-gray-500 dark:text-dark-400 text-sm">{{ __('messages.admin.user_view.no_activity') }}</li>
                            @endforelse
                        </ul>
                    </div>
                @else
                    <div class="text-center py-12 bg-gray-50 dark:bg-dark-900/50 rounded-xl border border-dashed border-gray-200 dark:border-dark-800">
                        <p class="text-gray-500 dark:text-dark-300">{{ __('messages.admin.user_view.not_admin_message') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
