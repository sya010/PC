<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-[var(--color-bg-secondary)] rounded-2xl m-4">
    <div class="max-w-md w-full space-y-8 p-10 bg-white/80 backdrop-blur-md rounded-3xl shadow-xl border border-white/20 relative overflow-hidden">
        <!-- Decorative background blob -->
        <div class="absolute top-0 left-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-purple-500/10 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>

        <div class="relative text-center">
            <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ __('messages.auth.create_your_account') }}</h2>
            <p class="mt-2 text-sm text-gray-600">
                {{ __('messages.auth.enter_details_register') }}
            </p>
        </div>
        
        <div class="relative mt-8 space-y-6">


            <form class="space-y-6" wire:submit="register">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">{{ __('messages.auth.full_name') }}</label>
                    <div class="mt-1 relative">
                        <input 
                            id="name" 
                            name="name" 
                            type="text" 
                            autocomplete="name" 
                            required 
                            maxlength="255"
                            class="input-neu block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm transition-all @error('name') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 @enderror" 
                            wire:model.blur="name"
                        >
                    </div>
                     @error('name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">{{ __('messages.auth.email') }}</label>
                    <div class="mt-1 relative">
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            required 
                            maxlength="255"
                            class="input-neu block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm transition-all @error('email') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 @enderror" 
                            wire:model.blur="email"
                        >
                    </div>
                     @error('email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">{{ __('messages.auth.password') }}</label>
                    <div class="mt-1 relative">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="new-password" 
                            required 
                            maxlength="255"
                            class="input-neu block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm transition-all @error('password') border-red-500 text-red-900 placeholder-red-300 focus:ring-red-500 @enderror" 
                            wire:model.blur="password"
                        >
                    </div>
                     @error('password') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">{{ __('messages.auth.confirm_password') }}</label>
                    <div class="mt-1">
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            autocomplete="new-password" 
                            required 
                            maxlength="255"
                            class="input-neu block w-full px-4 py-3 border border-gray-300 rounded-xl shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm transition-all" 
                            wire:model="password_confirmation"
                        >
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required wire:model="terms" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer transition-colors">
                    <label for="terms" class="ms-2 block text-sm text-gray-900 cursor-pointer">
                        {{ __('messages.auth.agree_to') }} <a href="#" class="text-indigo-600 hover:text-indigo-500">{{ __('messages.footer.terms') }}</a> {{ __('messages.auth.and') }} <a href="#" class="text-indigo-600 hover:text-indigo-500">{{ __('messages.footer.privacy') }}</a>
                    </label>
                </div>
                @error('terms') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-indigo-200 shadow-lg transition-transform hover:-translate-y-0.5 active:translate-y-0 transform duration-150">
                        <span wire:loading.remove wire:target="register">{{ __('messages.auth.create_account') }}</span>
                        <span wire:loading wire:target="register" class="flex items-center gap-2">
                             <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ __('messages.auth.creating_account') }}
                        </span>
                    </button>
                </div>
            </form>

            <div class="text-center mt-4">
                <p class="text-sm text-gray-600">
                    {{ __('messages.auth.already_have_account') }} 
                    <a href="{{ route('login') }}" wire:navigate class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">
                        {{ __('messages.auth.sign_in') }}
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
