<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-surface-primary dark:bg-brand-dark relative overflow-hidden">
    <!-- Background Decor -->
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-[-10%] right-[-5%] w-[500px] h-[500px] bg-gradient-to-br from-brand-light/20 to-brand-base/10 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-[-10%] left-[-5%] w-[500px] h-[500px] bg-gradient-to-tr from-brand-accent/10 to-brand-base/10 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="max-w-md w-full relative">
        <div class="bg-surface-primary dark:bg-dark-800 rounded-3xl shadow-xl shadow-surface-secondary/50 dark:shadow-dark-700/50 p-8 sm:p-10 border border-border-subtle dark:border-dark-700 backdrop-blur-xl relative z-10 transform transition-all hover:scale-[1.002] duration-500">
            
            @if($success)
                <!-- Success State -->
                <div class="text-center py-6 animate-in fade-in zoom-in duration-300">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-500/10 text-green-500 mb-6 shadow-lg shadow-green-500/10">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <h2 class="text-2xl font-extrabold text-content-primary dark:text-dark-100 tracking-tight">{{ __('messages.auth.success_title') }}</h2>
                    <p class="mt-4 text-content-secondary dark:text-dark-400 font-medium leading-relaxed">
                        {{ __('messages.auth.success_message') }}
                    </p>

                    <div class="mt-8">
                        <a href="{{ route('login') }}" wire:navigate class="inline-flex justify-center items-center w-full h-12 bg-brand-base hover:bg-brand-accent text-white font-bold rounded-xl shadow-lg shadow-brand-base/20 transition-all duration-200 transform active:scale-[0.98]">
                            {{ __('messages.auth.back_to_login') }}
                        </a>
                    </div>
                </div>
            @else
                <!-- Form State -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-brand-base text-white shadow-lg shadow-brand-base/20 mb-6">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <h2 class="text-3xl font-bold text-content-primary dark:text-dark-100 tracking-tight">{{ __('messages.auth.forgot_password_title') }}</h2>
                    <p class="mt-2 text-content-secondary dark:text-dark-400 font-medium text-sm leading-relaxed">{{ __('messages.auth.forgot_password_subtitle') }}</p>
                </div>

                <div class="space-y-5">
                    <!-- Info Badge -->
                    <div class="p-4 rounded-2xl bg-brand-base/5 border border-brand-base/10 flex items-start gap-3">
                        <svg class="w-5 h-5 text-brand-base flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-xs text-content-secondary dark:text-dark-300 font-medium leading-relaxed">
                            {{ __('messages.auth.send_to') }}
                        </div>
                    </div>

                    <form class="space-y-5" wire:submit="submit" novalidate>
                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-semibold text-content-secondary dark:text-dark-300 mb-1.5">{{ __('messages.auth.email') }}</label>
                            <input 
                                id="email" 
                                name="email" 
                                type="email" 
                                class="w-full h-12 px-4 bg-surface-secondary dark:bg-dark-900 border border-border-subtle dark:border-dark-700 rounded-xl text-content-primary dark:text-dark-100 placeholder-content-muted dark:placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-brand-accent/20 focus:border-brand-accent transition-all duration-200 @error('email') border-brand-accent bg-brand-accent/5 text-content-primary dark:text-dark-100 focus:ring-brand-accent/20 focus:border-brand-accent @enderror" 
                                placeholder="name@company.com"
                                wire:model.blur="email"
                            >
                            @error('email') <p class="mt-1.5 text-xs font-medium text-brand-accent animate-in slide-in-from-top-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Reason (Custom Alpine Select) -->
                        <div class="group" x-data="{ 
                            open: false, 
                            selected: @entangle('reason'),
                            options: {
                                'Forgot current password': '{{ __('messages.auth.reason_forgot') }}',
                                'Want to reset/change password': '{{ __('messages.auth.reason_reset') }}',
                                'Security concern / account compromised': '{{ __('messages.auth.reason_compromised') }}',
                                'Other reason': '{{ __('messages.auth.reason_other') }}'
                            }
                        }">
                            <label class="block text-sm font-semibold text-content-secondary dark:text-dark-300 mb-1.5">{{ __('messages.auth.reason') }}</label>
                            <div class="relative">
                                <button type="button" @click="open = !open" @click.away="open = false" 
                                    class="w-full h-12 px-4 rounded-xl border @error('reason') border-brand-accent bg-brand-accent/5 @else border-border-subtle dark:border-dark-700 bg-surface-secondary dark:bg-dark-900 @enderror text-{{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'right' : 'left' }} text-content-primary dark:text-dark-100 cursor-pointer focus:outline-none focus:ring-2 focus:ring-brand-accent/20 focus:border-brand-accent transition-all duration-200 flex items-center justify-between shadow-sm">
                                    <span x-text="selected ? options[selected] : '-- {{ __('messages.auth.reason') }} --'" :class="selected ? 'text-content-primary dark:text-dark-100 font-medium' : 'text-content-muted dark:text-dark-500'"></span>
                                    <svg class="w-5 h-5 text-content-muted dark:text-dark-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                
                                <!-- Dropdown options list -->
                                <div x-show="open" 
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute z-50 w-full mt-2 bg-surface-primary dark:bg-dark-800 rounded-xl shadow-xl border border-border-subtle dark:border-dark-700 overflow-hidden py-1 max-h-60 overflow-y-auto"
                                    style="display: none;">
                                    
                                    <template x-for="(label, value) in options" :key="value">
                                        <div @click="selected = value; open = false" 
                                            class="px-4 py-3 hover:bg-brand-base/5 dark:hover:bg-dark-700/50 cursor-pointer flex items-center justify-between group/item transition-colors">
                                            <span x-text="label" class="font-medium text-content-primary dark:text-dark-200 group-hover/item:text-brand-base"></span>
                                            <span x-show="selected === value" class="text-brand-base">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" /></svg>
                                            </span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            @error('reason') <p class="mt-1.5 text-xs font-medium text-brand-accent animate-in slide-in-from-top-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-semibold text-content-secondary dark:text-dark-300 mb-1.5">{{ __('messages.auth.description') }}</label>
                            <textarea 
                                id="description" 
                                name="description" 
                                rows="4"
                                class="w-full px-4 py-3 bg-surface-secondary dark:bg-dark-900 border border-border-subtle dark:border-dark-700 rounded-xl text-content-primary dark:text-dark-100 placeholder-content-muted dark:placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-brand-accent/20 focus:border-brand-accent transition-all duration-200 resize-none @error('description') border-brand-accent bg-brand-accent/5 text-content-primary dark:text-dark-100 focus:ring-brand-accent/20 focus:border-brand-accent @enderror"
                                placeholder="Please detail your request here (minimum 15 characters)..."
                                wire:model.blur="description"
                            ></textarea>
                            @error('description') <p class="mt-1.5 text-xs font-medium text-brand-accent animate-in slide-in-from-top-1">{{ $message }}</p> @enderror
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="relative w-full h-12 flex justify-center items-center bg-brand-base hover:bg-brand-accent text-white font-bold rounded-xl shadow-lg shadow-brand-accent/30 transition-all duration-200 transform active:scale-[0.98] disabled:opacity-70 disabled:cursor-not-allowed">
                            <span wire:loading.remove wire:target="submit">{{ __('messages.auth.submit_request') }}</span>
                            <div wire:loading wire:target="submit" class="flex items-center gap-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="font-medium">{{ __('messages.auth.submitting_request') }}</span>
                            </div>
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <a href="{{ route('login') }}" wire:navigate class="font-semibold text-sm text-brand-base hover:text-brand-accent transition-colors flex items-center justify-center gap-1.5">
                            <svg class="w-4 h-4 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            {{ __('messages.auth.back_to_login') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
