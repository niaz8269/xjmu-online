<?php
require_once __DIR__ . '/includes/auth.php';

// ---- Gather dashboard stats (each guarded so one failure can't blank the page) ----
function dash_count($pdo, $sql, $params = []) {
    try { $s = $pdo->prepare($sql); $s->execute($params); return (int) $s->fetchColumn(); }
    catch (\PDOException $e) { error_log('dashboard stat: ' . $e->getMessage()); return 0; }
}

$stats = [
    'users'      => dash_count($pdo, "SELECT COUNT(*) FROM users"),
    'submitted'  => dash_count($pdo, "SELECT COUNT(*) FROM applications WHERE is_submitted = 1"),
    'pending'    => dash_count($pdo, "SELECT COUNT(*) FROM applications WHERE is_submitted = 1 AND status = 'Pending'"),
    'approved'   => dash_count($pdo, "SELECT COUNT(*) FROM applications WHERE status = 'Approved'"),
    'drafts'     => dash_count($pdo, "SELECT COUNT(*) FROM applications WHERE status = 'draft'"),
    'blogs'      => dash_count($pdo, "SELECT COUNT(*) FROM blogs"),
    'events'     => dash_count($pdo, "SELECT COUNT(*) FROM events"),
    'unverified' => dash_count($pdo, "SELECT COUNT(*) FROM users WHERE is_verified = 0"),
];

$recent = [];
try {
    $stmt = $pdo->query("
        SELECT a.user_id, a.status, a.submitted_at, a.updated_at,
               a.degree_applying_for, a.major_applying_for,
               u.full_name, u.email
        FROM applications a
        JOIN users u ON u.id = a.user_id
        WHERE a.is_submitted = 1
        ORDER BY a.submitted_at DESC, a.updated_at DESC
        LIMIT 8
    ");
    $recent = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (\PDOException $e) {
    error_log('dashboard recent: ' . $e->getMessage());
}

function status_badge($status) {
    $map = ['Approved' => 'success', 'Rejected' => 'danger', 'Pending' => 'warning', 'draft' => 'secondary'];
    $cls = $map[$status] ?? 'secondary';
    return '<span class="badge text-bg-' . $cls . '">' . e(ucfirst($status ?: 'draft')) . '</span>';
}

$admin_page_title = 'Dashboard';
$admin_subtitle   = 'Overview of applications, users and content';
$admin_active     = 'dashboard';
require __DIR__ . '/includes/layout_top.php';
?>

<!-- Stat cards -->
<div class="row g-3 mb-2">
    <?php
    $cards = [
        ['Total Users', $stats['users'], 'bi-people-fill', 'primary', 'users'],
        ['Submitted Applications', $stats['submitted'], 'bi-file-earmark-check-fill', 'info', 'applications'],
        ['Pending Review', $stats['pending'], 'bi-hourglass-split', 'warning', 'applications'],
        ['Approved', $stats['approved'], 'bi-patch-check-fill', 'success', 'applications'],
    ];
    foreach ($cards as [$label, $value, $icon, $color, $link]):
    ?>
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card text-bg-<?= $color ?> h-100">
                <div class="card-body d-flex align-items-center justify-content-between position-relative">
                    <div>
                        <div class="stat-value"><?= number_format($value) ?></div>
                        <div class="small text-uppercase opacity-75"><?= e($label) ?></div>
                    </div>
                    <i class="bi <?= $icon ?> stat-icon"></i>
                    <a href="<?= e($link) ?>" class="stretched-link"></a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Secondary stats -->
<div class="row g-3 mb-4">
    <?php
    $cards2 = [
        ['Drafts (in progress)', $stats['drafts'], 'manage-applications'],
        ['Unverified Users', $stats['unverified'], 'users'],
        ['Blog Posts', $stats['blogs'], 'manage-blogs'],
        ['Events', $stats['events'], 'manage-events'],
    ];
    foreach ($cards2 as [$label, $value, $link]):
    ?>
        <div class="col-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-between position-relative">
                    <div>
                        <div class="fs-4 fw-bold"><?= number_format($value) ?></div>
                        <div class="small text-muted"><?= e($label) ?></div>
                    </div>
                    <a href="<?= e($link) ?>" class="stretched-link"></a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Recent applications -->
<div class="card">
    <div class="card-header bg-body d-flex justify-content-between align-items-center">
        <h2 class="card-title h6 mb-0"><i class="bi bi-clock-history me-2"></i>Recent Submitted Applications</h2>
        <a href="applications" class="btn btn-sm btn-outline-secondary">View all</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Applicant</th><th>Email</th><th>Programme</th><th>Status</th><th>Submitted</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!$recent): ?>
                        <tr><td colspan="6" class="text-center text-muted py-4">No submitted applications yet.</td></tr>
                    <?php else: foreach ($recent as $r): ?>
                        <tr>
                            <td class="fw-semibold"><?= e($r['full_name']) ?></td>
                            <td class="text-muted small"><?= e($r['email']) ?></td>
                            <td class="small"><?= e(trim(($r['degree_applying_for'] ?? '') . ' ' . ($r['major_applying_for'] ?? ''))) ?: '—' ?></td>
                            <td><?= status_badge($r['status']) ?></td>
                            <td class="small text-muted"><?= $r['submitted_at'] ? e(date('M j, Y', strtotime($r['submitted_at']))) : '—' ?></td>
                            <td class="text-end"><a href="review_application?id=<?= (int)$r['user_id'] ?>" class="btn btn-sm btn-outline-primary">Review</a></td>
                        </tr>
                    <?php endforeach; endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
