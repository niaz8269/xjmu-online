<?php

namespace App\Support\Admin;

/** Small HTML snippets shared by admin pages. */
class Html
{
    /** Bootstrap status badge (was status_badge() in dashboard.php). Returns safe HTML: print with {!! !!}. */
    public static function statusBadge(?string $status): string
    {
        $map = ['Approved' => 'success', 'Rejected' => 'danger', 'Pending' => 'warning', 'draft' => 'secondary'];
        $cls = $map[$status] ?? 'secondary';
        return '<span class="badge text-bg-' . $cls . '">' . e(ucfirst($status ?: 'draft')) . '</span>';
    }
}
