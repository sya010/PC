<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-surface-primary dark:bg-brand-dark rounded-2xl m-4">
    <div class="max-w-md w-full space-y-8 p-10 bg-surface-primary dark:bg-dark-800 backdrop-blur-md rounded-3xl shadow-xl border border-border-subtle dark:border-dark-700 relative overflow-hidden">
        <!-- Decorative background blob -->
        <div class="absolute top-0 left-0 w-64 h-64 bg-brand-accent/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-brand-accent/10 rounded-full blur-3xl translate-x-1/2 translate-y-1/2"></div>

        <div class="relative text-center">
            <h2 class="text-3xl font-extrabold text-content-primary dark:text-dark-100 tracking-tight">{{ __('messages.auth.create_your_account') }}</h2>
            <p class="mt-2 text-sm text-content-secondary dark:text-dark-400">
                {{ __('messages.auth.enter_details_register') }}
            </p>
        </div>
        
        <div class="relative mt-8 space-y-6">


            <form class="space-y-6" wire:submit="register" novalidate>
                <div>
                    <label for="name" class="block text-sm font-medium text-content-secondary dark:text-dark-300">{{ __('messages.auth.full_name') }}</label>
                    <div class="mt-1 relative">
                        <input 
                            id="name" 
                            name="name" 
                            type="text" 
                            autocomplete="name" 
                            maxlength="255"
                            class="input-neu block w-full px-4 py-3 border border-border-subtle dark:border-dark-700 rounded-xl shadow-sm placeholder-content-muted dark:placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-brand-accent/50 focus:border-brand-accent sm:text-sm transition-all bg-surface-secondary dark:bg-dark-900 text-content-primary dark:text-dark-100 @error('name') border-brand-accent bg-brand-accent/5 text-content-primary dark:text-dark-100 focus:ring-brand-accent/20 @enderror" 
                            wire:model.blur="name"
                        >
                    </div>
                     @error('name') <p class="mt-2 text-sm text-brand-accent">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-content-secondary dark:text-dark-300">{{ __('messages.auth.email') }}</label>
                    <div class="mt-1 relative">
                        <input 
                            id="email" 
                            name="email" 
                            type="email" 
                            autocomplete="email" 
                            maxlength="255"
                            class="input-neu block w-full px-4 py-3 border border-border-subtle dark:border-dark-700 rounded-xl shadow-sm placeholder-content-muted dark:placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-brand-accent/50 focus:border-brand-accent sm:text-sm transition-all bg-surface-secondary dark:bg-dark-900 text-content-primary dark:text-dark-100 @error('email') border-brand-accent bg-brand-accent/5 text-content-primary dark:text-dark-100 focus:ring-brand-accent/20 @enderror" 
                            wire:model.blur="email"
                        >
                    </div>
                     @error('email') <p class="mt-2 text-sm text-brand-accent">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-content-secondary dark:text-dark-300">{{ __('messages.auth.password') }}</label>
                    <div class="mt-1 relative">
                        <input 
                            id="password" 
                            name="password" 
                            type="password" 
                            autocomplete="new-password" 
                            maxlength="255"
                            class="input-neu block w-full px-4 py-3 border border-border-subtle dark:border-dark-700 rounded-xl shadow-sm placeholder-content-muted dark:placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-brand-accent/50 focus:border-brand-accent sm:text-sm transition-all bg-surface-secondary dark:bg-dark-900 text-content-primary dark:text-dark-100 @error('password') border-brand-accent bg-brand-accent/5 text-content-primary dark:text-dark-100 focus:ring-brand-accent/20 @enderror" 
                            wire:model.blur="password"
                        >
                    </div>
                     @error('password') <p class="mt-2 text-sm text-brand-accent">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-content-secondary dark:text-dark-300">{{ __('messages.auth.confirm_password') }}</label>
                    <div class="mt-1">
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            type="password" 
                            autocomplete="new-password" 
                            maxlength="255"
                            class="input-neu block w-full px-4 py-3 border border-border-subtle dark:border-dark-700 rounded-xl shadow-sm placeholder-content-muted dark:placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-brand-accent/50 focus:border-brand-accent sm:text-sm transition-all bg-surface-secondary dark:bg-dark-900 text-content-primary dark:text-dark-100" 
                            wire:model="password_confirmation"
                        >
                    </div>
                </div>

                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" wire:model="terms" class="h-4 w-4 text-brand-base focus:ring-brand-accent border-border-subtle dark:border-dark-700 rounded cursor-pointer transition-colors">
                    <label for="terms" class="ms-2 block text-sm text-content-primary dark:text-dark-100 cursor-pointer">
                        {{ __('messages.auth.agree_to') }} <a href="#" class="text-brand-base hover:text-brand-accent">{{ __('messages.footer.terms') }}</a> {{ __('messages.auth.and') }} <a href="#" class="text-brand-base hover:text-brand-accent">{{ __('messages.footer.privacy') }}</a>
                    </label>
                </div>
                @error('terms') <p class="mt-1 text-sm text-brand-accent">{{ $message }}</p> @enderror

                <div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-brand-base hover:bg-brand-accent focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-accent shadow-brand-base/20 shadow-lg transition-transform hover:-translate-y-0.5 active:translate-y-0 transform duration-150">
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
                <p class="text-sm text-content-secondary dark:text-dark-400">
                    {{ __('messages.auth.already_have_account') }} 
                    <a href="{{ route('login') }}" wire:navigate class="font-bold text-brand-base hover:text-brand-accent transition-colors">
                        {{ __('messages.auth.sign_in') }}
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>
