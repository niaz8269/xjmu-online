<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/**
 * Same session check the original applicant-portal scripts did: `isset($_SESSION['user_id'])`.
 *
 * Alias `loggedin`. The original scripts reacted to a missing login in three different ways, selected by parameter:
 *   loggedin         HTML pages (front, profile, edit-details...)  -> redirect to /login
 *   loggedin:401     get_*.php JSON endpoints                       -> 401 {"status":"error","message":"Unauthorized"}
 *   loggedin:json    save_*.php / submit_*.php JSON endpoints       -> 200 {"status":"error","message":"User not logged in"}
 */
class EnsureLoggedIn
{
    public function handle(Request $request, Closure $next, string $mode = 'redirect')
    {
        if ($request->session()->has('user_id')) {
            return $next($request);
        }

        return match ($mode) {
            '401' => response()->json(['status' => 'error', 'message' => 'Unauthorized'], 401),
            'json' => response()->json(['status' => 'error', 'message' => 'User not logged in']),
            default => redirect(get_absolute_url('login')),
        };
    }
}
