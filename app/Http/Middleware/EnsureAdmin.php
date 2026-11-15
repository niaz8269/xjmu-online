<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

/**
 * Admin gate (was admin/includes/auth.php). Alias: `admin`.
 * Not logged in OR role !== 'admin' -> 302 to the login page (same as the original `Location: ../login`).
 * On success exposes $admin_name / $is_superadmin to every view and as request attributes.
 */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $session = $request->session();

        if (! $session->has('user_id') || $session->get('role', '') !== 'admin') {
            Log::warning('Unauthorized admin access attempt - user_id: ' . ($session->get('user_id') ?? 'none'));
            return redirect('/login');
        }

        $adminName = $session->get('full_name') ?? 'Admin';
        $isSuper = false;
        try {
            $isSuper = (bool) DB::table('users')->where('id', $session->get('user_id'))->value('is_superadmin');
        } catch (QueryException $e) {
            Log::error('auth.php superadmin lookup: ' . $e->getMessage());
        }

        $request->attributes->set('admin_name', $adminName);
        $request->attributes->set('is_superadmin', $isSuper);
        View::share('admin_name', $adminName);
        View::share('is_superadmin', $isSuper);

        return $next($request);
    }
}
