<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-brand-base dark:text-brand-light">
            {{ __('messages.profile.account_settings') }}
        </h1>
        <p class="text-gray-500 dark:text-dark-300 mt-1">{{ __('messages.profile.manage_info') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-12 gap-8">
        <!-- Sidebar Navigation -->
        <div class="md:col-span-4 lg:col-span-3 space-y-6">
            <div class="bg-white dark:bg-dark-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 flex flex-col items-center text-center">
                <div class="w-24 h-24 bg-brand-base/5 dark:bg-brand-base/20 rounded-full flex items-center justify-center text-brand-base dark:text-brand-light font-bold text-3xl mb-4 ring-8 ring-brand-base/10">
                    {{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <h3 class="font-bold text-lg text-gray-900 dark:text-dark-100">{{ auth()->user()->name }}</h3>
                <p class="text-sm text-gray-500 dark:text-dark-300">{{ auth()->user()->email }}</p>
                
                @if(auth()->user()->is_admin)
                    <span class="inline-flex items-center gap-1 mt-4 px-3 py-1 bg-brand-base/10 text-brand-dark dark:bg-brand-base/20 dark:text-brand-light text-xs font-bold rounded-full uppercase tracking-wider">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                        {{ __('messages.profile.admin') }}
                    </span>
                @else
                     <span class="inline-flex items-center gap-1 mt-4 px-3 py-1 bg-brand-base/10 text-brand-accent dark:bg-brand-base/20 dark:text-brand-light text-xs font-bold rounded-full uppercase tracking-wider">
                        {{ __('messages.profile.customer') }}
                    </span>
                @endif
            </div>

             <nav class="space-y-1">
                <a href="#profile" class="flex items-center gap-3 px-4 py-3 bg-brand-base/5 dark:bg-brand-base/20 text-brand-accent dark:text-brand-light font-bold rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                    {{ __('messages.profile.profile_info') }}
                </a>
                <a href="{{ route('my-orders') }}" class="flex items-center gap-3 px-4 py-3 text-gray-600 dark:text-dark-300 hover:bg-gray-50 dark:hover:bg-dark-800 hover:text-gray-900 dark:hover:text-dark-100 font-medium rounded-xl transition-colors">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" /></svg>
                    {{ __('messages.user_orders.title') }}
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="md:col-span-8 lg:col-span-9 space-y-8">
            <!-- Profile Information -->
            <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 p-8 relative overflow-hidden">
                 <div class="absolute top-0 right-0 w-32 h-32 bg-brand-base/5 rounded-bl-full -mr-8 -mt-8 pointer-events-none"></div>

                <div class="relative">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-dark-100 flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 text-brand-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        {{ __('messages.profile.profile_info') }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-dark-300 mb-8">{{ __('messages.profile.update_info_desc') }}</p>

                    @if (session('message'))
                        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-800/30 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm font-bold flex items-center gap-2 shadow-sm animate-pulse">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            {{ session('message') }}
                        </div>
                    @endif

                    <form wire:submit="updateProfile" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label class="block text-sm font-bold text-gray-700 dark:text-dark-200 mb-1.5 group-focus-within:text-brand-base transition-colors">{{ __('messages.profile.full_name') }}</label>
                                <input type="text" wire:model="name" maxlength="255" class="input-neu w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-800 bg-transparent dark:bg-dark-955 text-gray-900 dark:text-dark-100 placeholder-gray-400 dark:placeholder-dark-500 focus:ring-2 focus:ring-brand-accent/50 outline-none transition-all font-medium">
                                @error('name') <span class="text-red-500 text-sm font-bold mt-1 block flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</span> @enderror
                            </div>

                            <div class="group">
                                <label class="block text-sm font-bold text-gray-700 dark:text-dark-200 mb-1.5 group-focus-within:text-brand-base transition-colors">{{ __('messages.profile.email_address') }}</label>
                                <input type="email" wire:model="email" maxlength="255" class="input-neu w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-800 bg-transparent dark:bg-dark-955 text-gray-900 dark:text-dark-100 placeholder-gray-400 dark:placeholder-dark-500 focus:ring-2 focus:ring-brand-accent/50 outline-none transition-all font-medium">
                                @error('email') <span class="text-red-500 text-sm font-bold mt-1 block flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-8 py-3 bg-brand-base hover:bg-brand-accent text-white font-bold rounded-xl shadow-lg shadow-brand-base/20 transition-all transform hover:-translate-y-1">
                                {{ __('messages.profile.save_changes') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Password -->
            <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-brand-base/5 rounded-bl-full -mr-8 -mt-8 pointer-events-none"></div>

                <div class="relative">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-dark-100 flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 text-brand-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        {{ __('messages.profile.security') }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-dark-300 mb-8">{{ __('messages.profile.security_desc') }}</p>

                    @if (session('password_message'))
                        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-800/30 text-emerald-700 dark:text-emerald-300 rounded-xl text-sm font-bold flex items-center gap-2 shadow-sm animate-pulse">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            {{ session('password_message') }}
                        </div>
                    @endif

                    <form wire:submit="updatePassword" class="space-y-6">
                        <div class="group" x-data="{ showPassword: false }">
                            <label class="block text-sm font-bold text-gray-700 dark:text-dark-200 mb-1.5 group-focus-within:text-brand-accent transition-colors">{{ __('messages.profile.current_password') }}</label>
                            <div class="relative">
                                <input :type="showPassword ? 'text' : 'password'" wire:model="current_password" class="input-neu w-full px-4 pe-12 py-3 rounded-xl border border-gray-200 dark:border-dark-800 bg-transparent dark:bg-dark-955 text-gray-900 dark:text-dark-100 focus:ring-2 focus:ring-brand-accent/50 outline-none transition-all placeholder-gray-400 font-medium">
                                <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 end-0 pe-4 flex items-center text-content-muted hover:text-content-primary dark:text-dark-400 dark:hover:text-dark-200 transition-colors focus:outline-none">
                                    <svg x-show="showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                    <svg x-show="!showPassword" x-cloak class="w-5 h-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                </button>
                            </div>
                            @error('current_password') <span class="text-red-500 text-sm font-bold mt-1 block flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group" x-data="{ showPassword: false }">
                                <label class="block text-sm font-bold text-gray-700 dark:text-dark-200 mb-1.5 group-focus-within:text-brand-accent transition-colors">{{ __('messages.profile.new_password') }}</label>
                                <div class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" wire:model="password" maxlength="255" class="input-neu w-full px-4 pe-12 py-3 rounded-xl border border-gray-200 dark:border-dark-800 bg-transparent dark:bg-dark-955 text-gray-900 dark:text-dark-100 focus:ring-2 focus:ring-brand-accent/50 outline-none transition-all placeholder-gray-400 font-medium">
                                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 end-0 pe-4 flex items-center text-content-muted hover:text-content-primary dark:text-dark-400 dark:hover:text-dark-200 transition-colors focus:outline-none">
                                        <svg x-show="showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg x-show="!showPassword" x-cloak class="w-5 h-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                    </button>
                                </div>
                                @error('password') <span class="text-red-500 text-sm font-bold mt-1 block flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</span> @enderror
                            </div>

                            <div class="group" x-data="{ showPassword: false }">
                                <label class="block text-sm font-bold text-gray-700 dark:text-dark-200 mb-1.5 group-focus-within:text-brand-accent transition-colors">{{ __('messages.profile.confirm_password') }}</label>
                                <div class="relative">
                                    <input :type="showPassword ? 'text' : 'password'" wire:model="password_confirmation" maxlength="255" class="input-neu w-full px-4 pe-12 py-3 rounded-xl border border-gray-200 dark:border-dark-800 bg-transparent dark:bg-dark-955 text-gray-900 dark:text-dark-100 focus:ring-2 focus:ring-brand-accent/50 outline-none transition-all placeholder-gray-400 font-medium">
                                    <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 end-0 pe-4 flex items-center text-content-muted hover:text-content-primary dark:text-dark-400 dark:hover:text-dark-200 transition-colors focus:outline-none">
                                        <svg x-show="showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg x-show="!showPassword" x-cloak class="w-5 h-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="px-8 py-3 bg-brand-base hover:bg-brand-accent text-white font-bold rounded-xl shadow-lg shadow-brand-base/20 transition-all transform hover:-translate-y-1">
                                {{ __('messages.profile.update_password') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Password Restoration Requests Inbox -->
            <div class="bg-white dark:bg-dark-900 rounded-2xl shadow-sm border border-gray-100 dark:border-dark-800 p-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-brand-base/5 rounded-bl-full -mr-8 -mt-8 pointer-events-none"></div>

                <div class="relative">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-dark-100 flex items-center gap-2 mb-1">
                        <svg class="w-5 h-5 text-brand-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        {{ __('messages.admin.sidebar.password_requests') }} Inbox
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-dark-300 mb-8">View the status and administrator updates for your submitted password restoration requests.</p>

                    <div class="space-y-4">
                        @forelse($passwordRequests as $req)
                            <div class="p-5 rounded-xl border border-gray-100 dark:border-dark-800 bg-gray-50/50 dark:bg-dark-950 flex flex-col md:flex-row md:items-start justify-between gap-4">
                                <div class="space-y-2 flex-1">
                                    <div class="flex flex-wrap items-center gap-3">
                                        <span class="font-bold text-gray-900 dark:text-dark-100 text-sm">
                                            {{ __('messages.auth.reason_' . strtolower(explode(' ', trim($req->reason))[0])) ?: $req->reason }}
                                        </span>
                                        <span class="text-xs text-gray-400 dark:text-dark-500 font-medium">
                                            {{ $req->created_at->format('Y-m-d H:i') }}
                                        </span>
                                    </div>
                                    @if($req->admin_note)
                                        <div class="p-3 rounded-xl bg-white dark:bg-dark-900 border border-gray-100 dark:border-dark-800 text-xs text-gray-600 dark:text-dark-300 font-medium relative mt-2 leading-relaxed">
                                            <span class="block text-[10px] font-bold text-brand-base dark:text-brand-light uppercase tracking-wider mb-1">Response from Admin</span>
                                            {{ $req->admin_note }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-shrink-0">
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
                                </div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-gray-400 dark:text-dark-500 text-sm font-medium">
                                <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2-2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v2m4 4h4" /></svg>
                                No restoration requests found.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
