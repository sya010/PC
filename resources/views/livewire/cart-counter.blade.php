<a href="{{ route('cart') }}" class="neu-button p-2.5 rounded-xl relative group hover:bg-brand-base/5 block transition-colors" aria-label="{{ __('messages.cart') }}">
    <svg class="w-5 h-5 text-content-secondary group-hover:text-brand-base transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
    </svg>
    <!-- Cart Count Badge -->
    @if($count > 0)
        <span class="absolute -top-1 {{ in_array(app()->getLocale(), ['ar', 'ku']) ? '-left-1' : '-right-1' }} w-5 h-5 bg-brand-base text-white text-xs font-bold rounded-full flex items-center justify-center animate-bounce-short shadow-md">
            {{ $count }}
        </span>
    @endif
</a>
