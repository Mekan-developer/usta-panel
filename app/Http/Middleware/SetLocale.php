<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    private const SUPPORTED = ['ru', 'tk'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->cookie('locale', 'ru');

        if (in_array($locale, self::SUPPORTED)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
