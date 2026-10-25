<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        abort_unless(in_array($locale, config('d51.locales'), true), 404);

        app()->setLocale($locale);
        URL::defaults(['locale' => $locale]);

        // Controllers never need the locale as an argument.
        $request->route()->forgetParameter('locale');

        return $next($request);
    }
}
