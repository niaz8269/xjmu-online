<?php
require_once __DIR__ . '/includes/auth.php';

$flash = '';
$flash_type = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require(false);
    try {
        $keys = $pdo->query("SELECT setting_key, input_type FROM site_settings")->fetchAll(PDO::FETCH_KEY_PAIR);
        $upd = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
        $pdo->beginTransaction();
        foreach ($keys as $key => $type) {
            if (!array_key_exists($key, $_POST)) {
                continue;
            }
            $val = trim((string) $_POST[$key]);
            // Light validation by type; store as-is otherwise.
            if ($type === 'email' && $val !== '' && !filter_var($val, FILTER_VALIDATE_EMAIL)) {
                continue; // skip invalid email rather than store junk
            }
            if ($type === 'url' && $val !== '' && !preg_match('~^https?://~i', $val)) {
                $val = 'https://' . $val;
            }
            $upd->execute([$val, $key]);
        }
        $pdo->commit();
        $flash = 'Settings saved.';
    } catch (\PDOException $e) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        error_log('settings save: ' . $e->getMessage());
        $flash = 'Could not save settings. Please try again.';
        $flash_type = 'danger';
    }
}

// Load grouped settings for display
$groups = [];
try {
    $rows = $pdo->query("SELECT * FROM site_settings ORDER BY group_name, sort_order, label")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        $groups[$r['group_name']][] = $r;
    }
} catch (\PDOException $e) {
    error_log('settings load: ' . $e->getMessage());
}
$groupTitles = ['general' => 'General & Hero', 'homepage' => 'Homepage Sections', 'mbbs' => 'MBBS Page', 'videos' => 'Videos (YouTube)', 'contact' => 'Contact Details', 'social' => 'Social Links'];

$admin_page_title = 'Site Settings';
$admin_subtitle   = 'Global content shown across the public website';
$admin_active     = 'settings';
require __DIR__ . '/includes/layout_top.php';
?>

<?php if ($flash): ?>
    <div class="alert alert-<?= e($flash_type) ?> alert-dismissible fade show">
        <?= e($flash) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<form method="POST">
    <?= csrf_field() ?>
    <?php foreach ($groups as $group => $items): ?>
        <div class="card mb-4">
            <div class="card-header bg-body">
                <h2 class="card-title h6 mb-0"><?= e($groupTitles[$group] ?? ucfirst($group)) ?></h2>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach ($items as $s): ?>
                        <div class="col-md-6">
                            <label class="form-label" for="f_<?= e($s['setting_key']) ?>"><?= e($s['label']) ?></label>
                            <?php if ($s['input_type'] === 'textarea'): ?>
                                <textarea class="form-control" id="f_<?= e($s['setting_key']) ?>" name="<?= e($s['setting_key']) ?>" rows="2"><?= e($s['setting_value']) ?></textarea>
                            <?php else: ?>
                                <input type="<?= e(in_array($s['input_type'], ['email','url','tel']) ? $s['input_type'] : 'text') ?>"
                                       class="form-control" id="f_<?= e($s['setting_key']) ?>"
                                       name="<?= e($s['setting_key']) ?>" value="<?= e($s['setting_value']) ?>">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="mb-5">
        <button type="submit" class="btn btn-xjmu px-4"><i class="bi bi-save me-2"></i>Save Settings</button>
    </div>
</form>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
