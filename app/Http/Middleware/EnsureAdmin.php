<?php

namespace App\Http\Middleware;

use App\Support\Security\Audit;
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
            Audit::record('admin.denied', ['path' => '/' . $request->path()]);
            return redirect('/login');
        }

        // The role in the session can be stale: demoting or deleting an admin must take effect on their very next click.
        try {
            $current = DB::table('users')->where('id', $session->get('user_id'))->value('role');
        } catch (QueryException $e) {
            Log::error('admin role recheck: ' . $e->getMessage());
            $current = $session->get('role');
        }
        if ($current !== 'admin') {
            Audit::record('admin.revoked', ['path' => '/' . $request->path()]);
            $session->flush();
            $session->regenerate();
            return redirect('/login');
        }

        // Idle timeout: an unattended admin session closes itself.
        $idle = (int) config('security.admin_idle_minutes', 30);
        $last = (int) $session->get('admin_last_activity', time());
        if ($idle > 0 && time() - $last > $idle * 60) {
            Audit::record('admin.idle_logout');
            $session->flush();
            $session->regenerate();
            return redirect('/login');
        }
        $session->put('admin_last_activity', time());

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

        $response = $next($request);
        // Admin pages hold personal data: never store them in a browser or CDN cache.
        $response->headers->set('Cache-Control', 'no-store, private');
        $response->headers->set('X-Robots-Tag', 'noindex, nofollow, noarchive');

        return $response;
    }
}
