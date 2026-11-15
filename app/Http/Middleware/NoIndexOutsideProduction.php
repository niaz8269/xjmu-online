<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * SEO safety: any copy of the site that is not production (staging, local) tells search engines not to index it,
 * so a staging site can never compete with xjmu.online in Google.
 */
class NoIndexOutsideProduction
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! app()->environment('production')) {
            $response->headers->set('X-Robots-Tag', 'noindex, nofollow');
        }

        return $response;
    }
}
