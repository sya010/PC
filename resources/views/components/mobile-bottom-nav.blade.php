<div class="lg:hidden fixed bottom-0 left-0 z-50 w-full h-16 bg-surface-primary/90 dark:bg-dark-900/90 backdrop-blur-lg border-t border-border-subtle dark:border-dark-700 pb-safe-area-inset-bottom">
    <div class="grid h-full max-w-lg grid-cols-5 mx-auto font-medium">
        <!-- Home -->
        <a href="{{ route('home') }}" wire:navigate class="inline-flex flex-col items-center justify-center px-5 group hover:bg-surface-secondary/50 dark:hover:bg-dark-800 transition-colors {{ request()->routeIs('home') ? 'text-brand-base' : 'text-content-secondary dark:text-dark-500' }}">
            <svg class="w-6 h-6 mb-1 group-hover:text-brand-base transition-colors {{ request()->routeIs('home') ? 'text-brand-base' : 'text-content-secondary dark:text-dark-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-[10px] group-hover:text-brand-base transition-colors {{ request()->routeIs('home') ? 'text-brand-base' : 'text-content-secondary dark:text-dark-500' }}">Home</span>
        </a>

        <!-- Shop -->
        <a href="{{ route('shop') }}" wire:navigate class="inline-flex flex-col items-center justify-center px-5 group hover:bg-surface-secondary/50 dark:hover:bg-dark-800 transition-colors {{ request()->routeIs('shop') ? 'text-brand-base' : 'text-content-secondary dark:text-dark-500' }}">
            <svg class="w-6 h-6 mb-1 group-hover:text-brand-base transition-colors {{ request()->routeIs('shop') ? 'text-brand-base' : 'text-content-secondary dark:text-dark-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
            </svg>
            <span class="text-[10px] group-hover:text-brand-base transition-colors {{ request()->routeIs('shop') ? 'text-brand-base' : 'text-content-secondary dark:text-dark-500' }}">Shop</span>
        </a>

        <!-- Build PC (Center/Highlighted) -->
        <div class="flex items-center justify-center relative">
            <a href="{{ route('build-pc') }}" wire:navigate class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-brand-accent shadow-lg hover:bg-brand-base hover:scale-105 transition-all absolute -top-4">
                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
            </a>
            <span class="absolute bottom-1 text-[10px] text-content-secondary dark:text-dark-500 {{ request()->routeIs('build-pc') ? 'text-brand-base' : '' }}">Build</span>
        </div>

        <!-- Compare -->
        <a href="{{ route('compare') }}" wire:navigate class="inline-flex flex-col items-center justify-center px-5 group hover:bg-surface-secondary/50 dark:hover:bg-dark-800 transition-colors {{ request()->routeIs('compare') ? 'text-brand-base' : 'text-content-secondary dark:text-dark-500' }}">
            <svg class="w-6 h-6 mb-1 group-hover:text-brand-base transition-colors {{ request()->routeIs('compare') ? 'text-brand-base' : 'text-content-secondary dark:text-dark-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            <span class="text-[10px] group-hover:text-brand-base transition-colors {{ request()->routeIs('compare') ? 'text-brand-base' : 'text-content-secondary dark:text-dark-500' }}">Compare</span>
        </a>

        <!-- Profile/Menu -->
        <button type="button" x-data @click="$dispatch('toggle-mobile-menu')" class="inline-flex flex-col items-center justify-center px-5 group hover:bg-surface-secondary/50 dark:hover:bg-dark-800 transition-colors text-content-secondary dark:text-dark-500">
            <svg class="w-6 h-6 mb-1 group-hover:text-brand-base transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
            <span class="text-[10px] group-hover:text-brand-base transition-colors">Menu</span>
        </button>
    </div>
</div>
