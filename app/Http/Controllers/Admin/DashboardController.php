<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends AdminController
{
    public function index()
    {
        // Each stat is guarded so one failure can't blank the page.
        $stats = [
            'users'      => $this->count(fn () => DB::table('users')),
            'submitted'  => $this->count(fn () => DB::table('applications')->where('is_submitted', 1)),
            'pending'    => $this->count(fn () => DB::table('applications')->where('is_submitted', 1)->where('status', 'Pending')),
            'approved'   => $this->count(fn () => DB::table('applications')->where('status', 'Approved')),
            'drafts'     => $this->count(fn () => DB::table('applications')->where('status', 'draft')),
            'blogs'      => $this->count(fn () => DB::table('blogs')),
            'events'     => $this->count(fn () => DB::table('events')),
            'unverified' => $this->count(fn () => DB::table('users')->where('is_verified', 0)),
        ];

        $recent = [];
        try {
            $recent = DB::table('applications as a')
                ->join('users as u', 'u.id', '=', 'a.user_id')
                ->where('a.is_submitted', 1)
                ->orderByDesc('a.submitted_at')->orderByDesc('a.updated_at')
                ->limit(8)
                ->get(['a.user_id', 'a.status', 'a.submitted_at', 'a.updated_at',
                       'a.degree_applying_for', 'a.major_applying_for', 'u.full_name', 'u.email'])
                ->map(fn ($r) => (array) $r)->all();
        } catch (QueryException $e) {
            Log::error('dashboard recent: ' . $e->getMessage());
        }

        return $this->page('admin.dashboard', 'Dashboard', 'dashboard',
            'Overview of applications, users and content', compact('stats', 'recent'));
    }

    private function count(\Closure $query): int
    {
        try {
            return (int) $query()->count();
        } catch (QueryException $e) {
            Log::error('dashboard stat: ' . $e->getMessage());
            return 0;
        }
    }
}
