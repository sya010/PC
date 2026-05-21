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
                        <div class="group">
                            <label class="block text-sm font-bold text-gray-700 dark:text-dark-200 mb-1.5 group-focus-within:text-brand-accent transition-colors">{{ __('messages.profile.current_password') }}</label>
                            <input type="password" wire:model="current_password" class="input-neu w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-800 bg-transparent dark:bg-dark-955 text-gray-900 dark:text-dark-100 focus:ring-2 focus:ring-brand-accent/50 outline-none transition-all placeholder-gray-400 font-medium">
                            @error('current_password') <span class="text-red-500 text-sm font-bold mt-1 block flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="group">
                                <label class="block text-sm font-bold text-gray-700 dark:text-dark-200 mb-1.5 group-focus-within:text-brand-accent transition-colors">{{ __('messages.profile.new_password') }}</label>
                                <input type="password" wire:model="password" maxlength="255" class="input-neu w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-800 bg-transparent dark:bg-dark-955 text-gray-900 dark:text-dark-100 focus:ring-2 focus:ring-brand-accent/50 outline-none transition-all placeholder-gray-400 font-medium">
                                @error('password') <span class="text-red-500 text-sm font-bold mt-1 block flex items-center gap-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</span> @enderror
                            </div>

                            <div class="group">
                                <label class="block text-sm font-bold text-gray-700 dark:text-dark-200 mb-1.5 group-focus-within:text-brand-accent transition-colors">{{ __('messages.profile.confirm_password') }}</label>
                                <input type="password" wire:model="password_confirmation" maxlength="255" class="input-neu w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-dark-800 bg-transparent dark:bg-dark-955 text-gray-900 dark:text-dark-100 focus:ring-2 focus:ring-brand-accent/50 outline-none transition-all placeholder-gray-400 font-medium">
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
        </div>
    </div>
</div>
