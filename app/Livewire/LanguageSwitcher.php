<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cookie;

class LanguageSwitcher extends Component
{
    /**
     * Currently selected locale.
     */
    public string $currentLocale;

    /**
     * Available languages with their display names and flags.
     */
    public array $languages = [
        'en' => [
            'name' => 'EN',
            'native' => 'EN',
            'flag' => '',
            'dir' => 'ltr',
        ],
        'ar' => [
            'name' => 'AR',
            'native' => 'AR',
            'flag' => '',
            'dir' => 'rtl',
        ],
        'ku' => [
            'name' => 'KU',
            'native' => 'KU',
            'flag' => '',
            'dir' => 'rtl',
        ],
    ];

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->currentLocale = app()->getLocale();
    }

    /**
     * Switch to a different locale without page refresh.
     */
    public function switchLocale(string $locale)
    {
        // Validate the locale
        if (!array_key_exists($locale, $this->languages)) {
            $locale = 'en';
        }

        // Update the current locale
        $this->currentLocale = $locale;
        
        // Set application locale
        app()->setLocale($locale);
        
        // Get the direction for this locale
        $direction = $this->languages[$locale]['dir'];

        // Dispatch browser event to update HTML attributes and reload translations
        $this->dispatch('locale-changed', [
            'locale' => $locale,
            'direction' => $direction,
        ]);

        // Queue the cookie to be set
        Cookie::queue('app_locale', $locale, 525600);
        
        // Redirect to refresh the page with new locale (full page reload for complete translation update)
        return redirect(request()->header('Referer', '/'));
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.language-switcher');
    }
}
