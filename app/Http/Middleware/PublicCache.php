<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Public content pages are identical for every visitor, so (like the original site) they are served without a
 * session cookie and with "Cache-Control: max-age=86400". That lets browsers and the host's CDN cache them,
 * which keeps page speed equal to the original site. Never apply this to pages that carry a CSRF token.
 */
class PublicCache
{
    public const MAX_AGE = 86400;

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($request->isMethodCacheable() && $response->getStatusCode() === 200) {
            foreach ($response->headers->getCookies() as $cookie) {
                $response->headers->removeCookie($cookie->getName(), $cookie->getPath(), $cookie->getDomain());
            }
            $response->headers->set('Cache-Control', 'public, max-age=' . self::MAX_AGE);
            $response->headers->set('Expires', gmdate('D, d M Y H:i:s', time() + self::MAX_AGE) . ' GMT');
        }

        return $response;
    }
}
