<?php

namespace App\Http\Middleware;

use App\Support\Security\Audit;
use Closure;
use Illuminate\Http\Request;

/** Records every state-changing admin request (who, where, which record) in the audit trail. Alias: `admin.audit`. */
class AuditAdminActions
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (! in_array($request->method(), ['POST', 'PUT', 'PATCH', 'DELETE'], true)) {
            return $response;
        }
        $target = array_filter([
            'id' => $request->input('id'),
            'user_id' => $request->input('user_id'),
            'do' => $request->input('_do'),
            'status' => $request->input('status'),
        ], fn ($v) => is_scalar($v) && $v !== '');
        Audit::record('admin.' . $request->method(), ['path' => '/' . ltrim($request->path(), '/'), 'target' => $target, 'http' => $response->getStatusCode()]);

        return $response;
    }
}
