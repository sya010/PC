<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleController extends Controller
{
    /**
     * Supported locales for the application.
     */
    protected array $supportedLocales = ['en', 'ar', 'ku'];

    /**
     * Switch the application locale.
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        // Validate the locale is supported
        if (!in_array($locale, $this->supportedLocales)) {
            $locale = config('app.locale', 'en');
        }

        // Create a cookie that lasts for 1 year (525600 minutes)
        $cookie = cookie('app_locale', $locale, 525600);

        // Redirect back with the locale cookie
        return redirect()
            ->back()
            ->withCookie($cookie);
    }
}
