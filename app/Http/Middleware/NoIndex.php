<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class NoIndex
{
    /**
     * Admin pages are never indexed; with SITE_NOINDEX (staging) nothing is.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if (config('d51.noindex') || $request->is('admin', 'admin/*')) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }
}
