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
        // Get locale from cookie, fall back to config default
        $locale = $request->cookie('app_locale', config('app.locale', 'en'));
        
        // Validate the locale is supported
        if (!in_array($locale, $this->supportedLocales)) {
            $locale = config('app.locale', 'en');
        }
        
        // Set the application locale
        App::setLocale($locale);

        return $next($request);
    }
}
