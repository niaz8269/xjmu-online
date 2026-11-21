<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\OpsHelpers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/** admin/users, manage_users, promote_user, demote_user, delete_user (the last four are superadmin only). */
class UsersController extends AdminController
{
    use OpsHelpers;

    /** GET /admin/users */
    public function index(Request $request)
    {
        if ($request->query('export') === 'csv') {
            return $this->exportCsv($request);
        }
        [$users, $search, $page, $totalRecords, $totalPages] = $this->listing($request, ['id', 'full_name', 'email', 'role', 'is_verified', 'created_at'], 'created_at', 'desc');
        $verified = in_array($request->query('verified'), ['0', '1'], true) ? (string) $request->query('verified') : '';

        return $this->page('admin.users.index', 'Registered Users', 'users', null,
            compact('users', 'search', 'page', 'totalRecords', 'totalPages', 'verified'));
    }

    /** CSV of the users list: ?export=csv, optional &search= and &verified=0|1. */
    private function exportCsv(Request $request)
    {
        $search = $request->query('search');
        $search = is_string($search) ? trim($search) : '';
        $verified = $request->query('verified');

        $query = DB::table('users')->orderByDesc('created_at');
        if ($search !== '') {
            $term = '%' . $search . '%';
            $query->where(fn ($q) => $q->where('full_name', 'like', $term)->orWhere('email', 'like', $term));
        }
        if ($verified === '0' || $verified === '1') {
            $query->where('is_verified', (int) $verified);
        }
        $rows = $query->get(['id', 'full_name', 'email', 'role', 'is_verified', 'created_at']);
        \App\Support\Security\Audit::record('users.export', ['rows' => count($rows)]);

        $out = chr(0xEF) . chr(0xBB) . chr(0xBF) . 'ID,Name,Email,Role,Verified,Registered' . chr(13) . chr(10);
        foreach ($rows as $r) {
            $out .= implode(',', array_map([\App\Support\Csv::class, 'cell'],
                [$r->id, $r->full_name, $r->email, $r->role, ! empty($r->is_verified) ? 'Yes' : 'No', $r->created_at])) . chr(13) . chr(10);
        }

        return response($out, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="users-' . date('Y-m-d') . '.csv"',
            'Cache-Control' => 'no-store, private',
        ]);
    }

    /** GET /admin/manage_users (superadmin only) */
    public function manage(Request $request)
    {
        if (! $request->attributes->get('is_superadmin')) {
            return $this->plain('Access denied. Superadmin only.');
        }

        [$users, $search, $page, $totalRecords, $totalPages] = $this->listing($request, ['id', 'full_name', 'email', 'role', 'is_superadmin'], 'id', 'asc');

        return $this->page('admin.users.manage', 'User Management Panel', 'users', null,
            compact('users', 'search', 'page', 'totalRecords', 'totalPages'));
    }

    /** POST /admin/promote_user */
    public function promote(Request $request)
    {
        return $this->changeRole($request, 'admin');
    }

    /** POST /admin/demote_user */
    public function demote(Request $request)
    {
        return $this->changeRole($request, 'user');
    }

    /** POST /admin/delete_user : never deletes a superadmin (so never yourself if you are one) */
    public function destroy(Request $request)
    {
        if (! $request->attributes->get('is_superadmin')) {
            return $this->plain('Access Denied');
        }

        if ($request->isMethod('post') && $request->has('user_id')) {
            $targetIsSuper = DB::table('users')->where('id', $request->input('user_id'))->value('is_superadmin');
            if (! $targetIsSuper) {
                DB::table('users')->where('id', $request->input('user_id'))->delete();
            }
        }

        return redirect('/admin/manage_users');
    }

    private function changeRole(Request $request, string $role)
    {
        if (! $request->attributes->get('is_superadmin')) {
            return $this->plain('Access Denied');
        }

        if ($request->isMethod('post') && $request->has('user_id')) {
            DB::table('users')->where('id', $request->input('user_id'))->update(['role' => $role]);
        }

        return redirect('/admin/manage_users');
    }

    /** Shared search + pagination of the users tables. @return array{0: array, 1: string, 2: int, 3: int, 4: int} */
    private function listing(Request $request, array $columns, string $orderBy, string $dir): array
    {
        $page = max(1, $this->intQuery($request, 'page', 1));
        $perPage = 20;
        $offset = ($page - 1) * $perPage;

        $search = trim($this->strQuery($request, 'search'));
        $base = DB::table('users');
        if (! empty($search)) {
            $term = "%{$search}%";
            $base->whereRaw('(full_name LIKE ? OR email LIKE ?)', [$term, $term]);
        }
        // Verified / unverified filter (?verified=1 or 0).
        if (in_array($request->query('verified'), ['0', '1'], true)) {
            $base->where('is_verified', (int) $request->query('verified'));
        }

        $totalRecords = (int) (clone $base)->count();
        $totalPages = (int) ceil($totalRecords / $perPage);
        $users = $this->rows($base->orderBy($orderBy, $dir)->offset($offset)->limit($perPage)->get($columns));

        return [$users, $search, $page, $totalRecords, $totalPages];
    }
}
