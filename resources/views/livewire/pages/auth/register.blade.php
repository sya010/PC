<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.app')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public bool $terms = false;

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'terms' => ['accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('messages.validation.register.name_required'),
            'name.min' => __('messages.validation.register.name_min'),
            'email.required' => __('messages.validation.register.email_required'),
            'email.email' => __('messages.validation.register.email_email'),
            'email.unique' => __('messages.validation.register.email_unique'),
            'password.required' => __('messages.validation.register.password_required'),
            'password.confirmed' => __('messages.validation.register.password_confirmed'),
            'password.min' => __('messages.validation.register.password_min'),
            'terms.accepted' => __('messages.validation.register.terms_accepted'),
        ];
    }

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate();

        $validated['password'] = Hash::make($validated['password']);

        unset($validated['terms']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('home', absolute: false), navigate: true);
    }
}; ?>

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
                            wire:model="name"
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
                            wire:model="email"
                        >
                    </div>
                     @error('email') <p class="mt-2 text-sm text-brand-accent">{{ $message }}</p> @enderror
                </div>

                <div x-data="{ showPassword: false }">
                    <label for="password" class="block text-sm font-medium text-content-secondary dark:text-dark-300">{{ __('messages.auth.password') }}</label>
                    <div class="mt-1 relative">
                        <input 
                            id="password" 
                            name="password" 
                            :type="showPassword ? 'text' : 'password'" 
                            autocomplete="new-password" 
                            maxlength="255"
                            class="input-neu block w-full px-4 pe-12 py-3 border border-border-subtle dark:border-dark-700 rounded-xl shadow-sm placeholder-content-muted dark:placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-brand-accent/50 focus:border-brand-accent sm:text-sm transition-all bg-surface-secondary dark:bg-dark-900 text-content-primary dark:text-dark-100 @error('password') border-brand-accent bg-brand-accent/5 text-content-primary dark:text-dark-100 focus:ring-brand-accent/20 @enderror" 
                            wire:model="password"
                        >
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 end-0 pe-4 flex items-center text-content-muted hover:text-content-primary dark:text-dark-400 dark:hover:text-dark-200 transition-colors focus:outline-none">
                            <svg x-show="showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-show="!showPassword" x-cloak class="w-5 h-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
                    </div>
                     @error('password') <p class="mt-2 text-sm text-brand-accent">{{ $message }}</p> @enderror
                </div>

                <div x-data="{ showPassword: false }">
                    <label for="password_confirmation" class="block text-sm font-medium text-content-secondary dark:text-dark-300">{{ __('messages.auth.confirm_password') }}</label>
                    <div class="mt-1 relative">
                        <input 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            :type="showPassword ? 'text' : 'password'" 
                            autocomplete="new-password" 
                            maxlength="255"
                            class="input-neu block w-full px-4 pe-12 py-3 border border-border-subtle dark:border-dark-700 rounded-xl shadow-sm placeholder-content-muted dark:placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-brand-accent/50 focus:border-brand-accent sm:text-sm transition-all bg-surface-secondary dark:bg-dark-900 text-content-primary dark:text-dark-100" 
                            wire:model="password_confirmation"
                        >
                        <button type="button" @click="showPassword = !showPassword" class="absolute inset-y-0 end-0 pe-4 flex items-center text-content-muted hover:text-content-primary dark:text-dark-400 dark:hover:text-dark-200 transition-colors focus:outline-none">
                            <svg x-show="showPassword" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                            <svg x-show="!showPassword" x-cloak class="w-5 h-5" style="display: none;" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                        </button>
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
