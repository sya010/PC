<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('home', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-surface-primary dark:bg-brand-dark relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-gradient-to-br from-brand-light/20 to-brand-base/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[500px] h-[500px] bg-gradient-to-tr from-brand-accent/10 to-brand-base/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="max-w-md w-full relative">
        <div class="bg-surface-primary dark:bg-dark-800 rounded-3xl shadow-xl shadow-surface-secondary/50 dark:shadow-dark-700/50 p-8 sm:p-10 border border-border-subtle dark:border-dark-700 backdrop-blur-xl relative z-10 transform transition-all hover:scale-[1.005] duration-500">
            <!-- Header -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-brand-base text-white shadow-lg shadow-brand-base/20 mb-6">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                    </svg>
                </div>
                <h2 class="text-3xl font-bold text-content-primary dark:text-dark-100 tracking-tight">{{ __('messages.auth.welcome_back') }}</h2>
                <p class="mt-2 text-content-secondary dark:text-dark-400 font-medium">{{ __('messages.auth.enter_details') }}</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <div class="space-y-6">
                <form class="space-y-5" wire:submit="login" novalidate>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-content-secondary dark:text-dark-300 mb-1.5">{{ __('messages.auth.email') }}</label>
                        <div class="relative group">
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                autocomplete="email" 
                                class="w-full h-12 px-4 bg-surface-secondary dark:bg-dark-900 border border-border-subtle dark:border-dark-700 rounded-xl text-content-primary dark:text-dark-100 placeholder-content-muted dark:placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-brand-accent/20 focus:border-brand-accent transition-all duration-200 @error('form.email') border-brand-accent bg-brand-accent/5 text-content-primary dark:text-dark-100 focus:ring-brand-accent/20 focus:border-brand-accent @enderror" 
                                placeholder="name@company.com"
                                wire:model="form.email"
                            >
                            @error('form.email')
                            <div class="absolute inset-y-0 end-0 pe-3 flex items-center pointer-events-none animate-in fade-in zoom-in duration-200">
                                <svg class="h-5 w-5 text-brand-accent" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            @enderror
                        </div>
                        @error('form.email') <p class="mt-1.5 text-xs font-medium text-brand-accent animate-in slide-in-from-top-1">{{ $message }}</p> @enderror
                    </div>

                    <div x-data="{ showPassword: false }">
                        <label for="password" class="block text-sm font-semibold text-content-secondary dark:text-dark-300 mb-1.5">{{ __('messages.auth.password') }}</label>
                        <div class="relative">
                            <input 
                                id="password" 
                                name="password" 
                                :type="showPassword ? 'text' : 'password'" 
                                autocomplete="current-password" 
                                class="w-full h-12 px-4 pe-12 bg-surface-secondary dark:bg-dark-900 border border-border-subtle dark:border-dark-700 rounded-xl text-content-primary dark:text-dark-100 placeholder-content-muted dark:placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-brand-accent/20 focus:border-brand-accent transition-all duration-200 @error('form.password') border-brand-accent bg-brand-accent/5 text-content-primary dark:text-dark-100 focus:ring-brand-accent/20 focus:border-brand-accent @enderror" 
                                placeholder="••••••••"
                                wire:model="form.password"
                            >
                            <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 end-0 pe-4 flex items-center text-content-muted hover:text-content-primary dark:text-dark-400 dark:hover:text-dark-200 transition-colors focus:outline-none">
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="!showPassword" x-cloak class="w-5 h-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                            </button>
                        </div>
                        @error('form.password') <p class="mt-1.5 text-xs font-medium text-brand-accent animate-in slide-in-from-top-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" wire:model="form.remember" class="w-4 h-4 text-brand-base border-border-subtle dark:border-dark-700 rounded focus:ring-brand-accent cursor-pointer">
                        <label for="remember-me" class="ms-2 block text-sm text-content-secondary dark:text-dark-400 cursor-pointer font-medium">{{ __('messages.auth.remember_me') }}</label>
                    </div>

                    <button type="submit" class="relative w-full h-12 flex justify-center items-center bg-brand-base hover:bg-brand-accent text-white font-bold rounded-xl shadow-lg shadow-brand-accent/30 transition-all duration-200 transform active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed">
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

                <p class="text-center text-sm text-content-secondary dark:text-dark-400">
                    {{ __('messages.auth.no_account') }} 
                    <a href="{{ route('register') }}" wire:navigate class="font-bold text-brand-base hover:text-brand-accent transition-colors">
                        {{ __('messages.auth.create_account') }}
                    </a>
                </p>
            </div>
        </div>
        
        <!-- Trust Badge / Footer text -->
        <div class="mt-6 text-center">
            <p class="text-xs text-content-muted dark:text-dark-500 font-medium flex items-center justify-center gap-2">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                {{ __('messages.auth.secure_auth') }}
            </p>
        </div>
    </div>
</div>
