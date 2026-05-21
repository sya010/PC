<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Supported locales for the application.
     */
    protected array $supportedLocales = ['en', 'ar', 'ku'];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('lang') ?? $request->query('locale') ?? $request->query('hl');
        $cookieToSet = null;

        if ($locale && in_array($locale, $this->supportedLocales)) {
            $cookieToSet = cookie('app_locale', $locale, 525600);
        } else {
            // Get locale from cookie
            $locale = $request->cookie('app_locale');
            
            // Auto-detect from browser Accept-Language header if no cookie
            if (!$locale) {
                $locale = $request->getPreferredLanguage($this->supportedLocales);
            }
            
            // Validate the locale is supported
            if (!in_array($locale, $this->supportedLocales)) {
                $locale = config('app.locale', 'en');
            }
        }

        // Set the application locale
        App::setLocale($locale);

        $response = $next($request);

        if ($cookieToSet) {
            $response->headers->setCookie($cookieToSet);
        }

        return $response;
    }
}
