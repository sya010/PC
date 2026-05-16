<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-[var(--color-bg-secondary)] relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-gradient-to-br from-indigo-400/20 to-purple-400/20 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[500px] h-[500px] bg-gradient-to-tr from-blue-400/10 to-teal-400/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="max-w-md w-full relative">
        <div class="bg-white rounded-3xl shadow-xl shadow-slate-200/50 p-8 sm:p-10 border border-slate-100 backdrop-blur-xl relative z-10 transform transition-all hover:scale-[1.005] duration-500">
            <!-- Header -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-600 text-white shadow-lg shadow-indigo-200 mb-6">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-slate-900 tracking-tight">{{ __('messages.auth.welcome_back') }}</h2>
                <p class="mt-2 text-slate-500 font-medium">{{ __('messages.auth.enter_details') }}</p>
            </div>

            <div class="space-y-6">
                <form class="space-y-5" wire:submit="login">
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">{{ __('messages.auth.email') }}</label>
                        <div class="relative group">
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                autocomplete="email" 
                                required 
                                class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200 @error('email') border-red-300 bg-red-50 text-red-900 focus:ring-red-200 focus:border-red-400 @enderror" 
                                placeholder="name@company.com"
                                wire:model.blur="email"
                            >
                            @error('email')
                            <div class="absolute inset-y-0 end-0 pe-3 flex items-center pointer-events-none animate-in fade-in zoom-in duration-200">
                                <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            @enderror
                        </div>
                        @error('email') <p class="mt-1.5 text-xs font-medium text-red-600 animate-in slide-in-from-top-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">{{ __('messages.auth.password') }}</label>
                        <div class="relative">
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                autocomplete="current-password" 
                                required 
                                class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all duration-200" 
                                placeholder="••••••••"
                                wire:model="password"
                            >
                        </div>
                        @error('password') <p class="mt-1.5 text-xs font-medium text-red-600 animate-in slide-in-from-top-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox" wire:model="remember" class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                            <label for="remember-me" class="ms-2 block text-sm text-slate-600 cursor-pointer font-medium">{{ __('messages.auth.remember_me') }}</label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">
                                {{ __('messages.auth.forgot_password') }}
                            </a>
                        </div>
                    </div>

                    <button type="submit" class="relative w-full h-12 flex justify-center items-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 transition-all duration-200 transform active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="login">{{ __('messages.auth.sign_in') }}</span>
                        <div wire:loading wire:target="login" class="flex items-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="font-medium">{{ __('messages.auth.signing_in') }}</span>
                        </div>
                    </button>
                </form>

                <p class="text-center text-sm text-slate-600">
                    {{ __('messages.auth.no_account') }} 
                    <a href="{{ route('register') }}" wire:navigate class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">
                        {{ __('messages.auth.create_account') }}
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Trust Badge / Footer text -->
        <div class="mt-6 text-center">
            <p class="text-xs text-slate-400 font-medium flex items-center justify-center gap-2">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                {{ __('messages.auth.secure_auth') }}
            </p>
        </div>
    </div>
</div>
