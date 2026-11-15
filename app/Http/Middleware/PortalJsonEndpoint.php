<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Throwable;

/**
 * What the original portal JSON scripts did at the top: json_api_guard() (any escaped exception becomes a 500 JSON
 * envelope) and, on POST only, a CSRF check (bad/missing token -> 403 JSON, not Laravel's 419 page).
 *
 * Use it on the portal JSON routes together with ->withoutMiddleware([PreventRequestForgery, ValidateCsrfToken])
 * (see routes/web/portal.php). The token is Laravel's own: `_token` field or the X-CSRF-TOKEN header
 * (public/assets/javascript/csrf.js adds the header to every same-origin fetch from the <meta name="csrf-token"> tag).
 */
class PortalJsonEndpoint
{
    public function handle(Request $request, Closure $next, string $csrf = 'csrf')
    {
        if ($csrf === 'csrf' && $request->isMethod('POST') && ! $this->tokenIsValid($request)) {
            return response()->json(
                ['status' => 'error', 'message' => 'Invalid or missing security token. Please refresh and try again.'],
                403,
                ['Expires' => 'Thu, 19 Nov 1981 08:52:00 GMT', 'Cache-Control' => 'no-store, no-cache, must-revalidate', 'Pragma' => 'no-cache']
            );
        }

        try {
            return $next($request);
        } catch (Throwable $e) {
            report($e);
            return response()->json([
                'status' => 'error',
                'success' => false,
                'message' => 'A server error occurred. Please try again later.',
            ], 500);
        }
    }

    private function tokenIsValid(Request $request): bool
    {
        $token = $request->input('_token') ?: $request->header('X-CSRF-TOKEN');
        $expected = $request->session()->token();

        return is_string($token) && $token !== '' && is_string($expected) && $expected !== '' && hash_equals($expected, $token);
    }
}
