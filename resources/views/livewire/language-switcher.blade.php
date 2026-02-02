<div class="relative" x-data="{ dropdownOpen: false }">
    <!-- Language Toggle Button -->
    <button 
        @click="dropdownOpen = !dropdownOpen"
        @click.away="dropdownOpen = false"
        class="lang-btn px-3 py-2.5 rounded-xl flex items-center gap-2 bg-[var(--color-bg-tertiary)] border border-[var(--glass-border)] hover:bg-[var(--color-bg-elevated)] transition-all duration-200"
        aria-label="{{ __('messages.select_language') }}"
        :aria-expanded="dropdownOpen"
        aria-haspopup="true"
    >
        <span class="text-lg">{{ $languages[$currentLocale]['flag'] }}</span>
        <span class="hidden sm:block text-sm font-medium text-[var(--color-text-secondary)]">
            {{ $languages[$currentLocale]['native'] }}
        </span>
        <svg 
            class="w-4 h-4 text-[var(--color-text-muted)] transition-transform duration-200" 
            :class="{ 'rotate-180': dropdownOpen }"
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Language Dropdown Menu -->
    <div 
        x-show="dropdownOpen" 
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95 translate-y-1"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute {{ in_array($currentLocale, ['ar', 'ku']) ? 'left-0' : 'right-0' }} mt-2 py-2 min-w-[180px] z-50 bg-[var(--color-bg-secondary)] border border-[var(--glass-border)] rounded-xl shadow-xl overflow-hidden"
        style="display: none;"
    >
        <!-- Header -->
        <div class="px-4 py-2 border-b border-[var(--glass-border)]">
            <span class="text-xs font-semibold text-[var(--color-text-muted)] uppercase tracking-wider">
                {{ __('messages.language') }}
            </span>
        </div>
        
        <!-- Language Options -->
        @foreach($languages as $locale => $language)
            <button 
                wire:click="switchLocale('{{ $locale }}')"
                @click="dropdownOpen = false"
                class="w-full flex items-center gap-3 px-4 py-3 text-left transition-all duration-150 cursor-pointer
                    {{ $currentLocale === $locale 
                        ? 'bg-indigo-600 text-white' 
                        : 'text-[var(--color-text-secondary)] hover:bg-[var(--color-bg-tertiary)] hover:text-[var(--color-text-primary)]' 
                    }}"
            >
                <span class="text-lg">{{ $language['flag'] }}</span>
                <div class="flex flex-col items-start flex-1">
                    <span class="text-sm font-medium">{{ $language['native'] }}</span>
                    @if($language['native'] !== $language['name'])
                        <span class="text-xs {{ $currentLocale === $locale ? 'text-indigo-200' : 'text-[var(--color-text-muted)]' }}">
                            {{ $language['name'] }}
                        </span>
                    @endif
                </div>
                @if($currentLocale === $locale)
                    <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                @endif
            </button>
        @endforeach
    </div>
</div>
