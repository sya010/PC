<x-layouts.app>
    <!-- Contact Hero -->
    <section class="py-12 lg:py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="text-3xl lg:text-4xl font-bold mb-4">{{ __('messages.contact.title') }}</h1>
                <p class="text-[var(--color-text-secondary)] max-w-2xl mx-auto">
                    {{ __('messages.contact.subtitle') }}
                </p>
            </div>

            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="neu-card p-6 lg:p-8">
                    <h2 class="text-xl font-semibold mb-6">{{ __('messages.contact.send_message') }}</h2>
                    
                    <form class="space-y-4">
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-2">
                                    {{ __('messages.contact.name') }}
                                </label>
                                <input type="text" class="input-neu w-full" placeholder="{{ __('messages.contact.name_placeholder') }}">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-2">
                                    {{ __('messages.contact.email') }}
                                </label>
                                <input type="email" class="input-neu w-full" placeholder="{{ __('messages.contact.email_placeholder') }}">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-2">
                                {{ __('messages.contact.subject') }}
                            </label>
                            <select class="input-neu w-full">
                                <option>{{ __('messages.contact.subject_general') }}</option>
                                <option>{{ __('messages.contact.subject_order') }}</option>
                                <option>{{ __('messages.contact.subject_support') }}</option>
                                <option>{{ __('messages.contact.subject_partnership') }}</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-[var(--color-text-secondary)] mb-2">
                                {{ __('messages.contact.message') }}
                            </label>
                            <textarea rows="5" class="input-neu w-full resize-none" placeholder="{{ __('messages.contact.message_placeholder') }}"></textarea>
                        </div>
                        
                        <button type="submit" class="w-full bg-indigo-600 text-white font-semibold py-3 rounded-xl hover:bg-indigo-700 transition-colors">
                            {{ __('messages.contact.send') }}
                        </button>
                    </form>
                </div>

                <!-- Contact Info -->
                <div class="space-y-6">
                    <!-- Office Info -->
                    <div class="neu-card p-6">
                        <h3 class="text-lg font-semibold mb-4">{{ __('messages.contact.office') }}</h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-lg bg-indigo-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-[var(--color-text-primary)]">{{ __('messages.contact.address_label') }}</p>
                                    <p class="text-sm text-[var(--color-text-muted)]">{{ __('messages.footer.address') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-lg bg-purple-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-[var(--color-text-primary)]">{{ __('messages.contact.phone_label') }}</p>
                                    <p class="text-sm text-[var(--color-text-muted)]">{{ __('messages.footer.phone') }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start gap-4">
                                <div class="w-10 h-10 rounded-lg bg-emerald-600 flex items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-[var(--color-text-primary)]">{{ __('messages.contact.email_label') }}</p>
                                    <p class="text-sm text-[var(--color-text-muted)]">{{ __('messages.footer.email') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Working Hours -->
                    <div class="neu-card p-6">
                        <h3 class="text-lg font-semibold mb-4">{{ __('messages.contact.hours') }}</h3>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-[var(--color-text-muted)]">{{ __('messages.contact.weekdays') }}</span>
                                <span class="text-[var(--color-text-secondary)]">9:00 AM - 9:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[var(--color-text-muted)]">{{ __('messages.contact.friday') }}</span>
                                <span class="text-[var(--color-text-secondary)]">2:00 PM - 9:00 PM</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-[var(--color-text-muted)]">{{ __('messages.contact.saturday') }}</span>
                                <span class="text-[var(--color-text-secondary)]">10:00 AM - 6:00 PM</span>
                            </div>
                        </div>
                    </div>

                    <!-- Social Links -->
                    <div class="neu-card p-6">
                        <h3 class="text-lg font-semibold mb-4">{{ __('messages.contact.follow_us') }}</h3>
                        <div class="flex gap-3">
                            <a href="#" class="w-10 h-10 rounded-lg bg-[var(--color-bg-tertiary)] flex items-center justify-center hover:bg-indigo-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-lg bg-[var(--color-bg-tertiary)] flex items-center justify-center hover:bg-indigo-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-lg bg-[var(--color-bg-tertiary)] flex items-center justify-center hover:bg-indigo-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 rounded-lg bg-[var(--color-bg-tertiary)] flex items-center justify-center hover:bg-indigo-600 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
