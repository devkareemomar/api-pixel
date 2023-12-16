<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = $request->header('x-locale');

        if ($locale) {
            app()->setLocale($locale);
        } else {
            // If 'x-locale' header is not present, set a default language
            app()->setLocale(config('app.fallback_locale'));
        }

        return $next($request);
    }
}
