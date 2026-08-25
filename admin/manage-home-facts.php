<?php
require_once __DIR__ . '/includes/auth.php';
$action = $_GET['action'] ?? 'list';
$flash = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require(false);
    try {
        if (($_POST['_do'] ?? '') === 'delete') {
            $pdo->prepare("DELETE FROM home_facts WHERE id = ?")->execute([(int) $_POST['id']]);
            $_SESSION['flash'] = 'Fact deleted.';
        } else {
            $id = (int) ($_POST['id'] ?? 0);
            $label = trim($_POST['label'] ?? '');
            if ($label === '') { $_SESSION['flash'] = 'Label is required.'; }
            else {
                $vals = [$label, trim($_POST['value'] ?? ''), (int) ($_POST['sort_order'] ?? 0), isset($_POST['is_published']) ? 1 : 0];
                if ($id > 0) { $pdo->prepare("UPDATE home_facts SET label=?, value=?, sort_order=?, is_published=? WHERE id=?")->execute(array_merge($vals, [$id])); $_SESSION['flash'] = 'Fact updated.'; }
                else { $pdo->prepare("INSERT INTO home_facts (label,value,sort_order,is_published) VALUES (?,?,?,?)")->execute($vals); $_SESSION['flash'] = 'Fact added.'; }
            }
        }
    } catch (\Throwable $e) { error_log('manage-home-facts: ' . $e->getMessage()); $_SESSION['flash'] = 'Error: ' . $e->getMessage(); }
    header('Location: manage-home-facts'); exit;
}

$edit = null;
if ($action === 'edit' && isset($_GET['id'])) { $stmt = $pdo->prepare("SELECT * FROM home_facts WHERE id = ?"); $stmt->execute([(int) $_GET['id']]); $edit = $stmt->fetch(PDO::FETCH_ASSOC); }
$isForm = ($action === 'edit' || $action === 'new');
$rows = $isForm ? [] : $pdo->query("SELECT * FROM home_facts ORDER BY sort_order, id")->fetchAll(PDO::FETCH_ASSOC);

$admin_page_title = 'MBBS Facts';
$admin_subtitle   = 'Quick facts in the homepage MBBS section';
$admin_active     = 'manage-home-facts';
require __DIR__ . '/includes/layout_top.php';
?>
<?php if ($flash): ?><div class="alert alert-info alert-dismissible fade show"><?= e($flash) ?><button class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if ($isForm): ?>
    <div class="card"><div class="card-header bg-body"><h2 class="h6 mb-0"><?= $edit ? 'Edit' : 'Add' ?> Fact</h2></div>
    <div class="card-body"><form method="POST"><?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
        <div class="row g-3">
            <div class="col-md-5"><label class="form-label">Label</label><input type="text" name="label" class="form-control" required placeholder="Duration" value="<?= e($edit['label'] ?? '') ?>"></div>
            <div class="col-md-7"><label class="form-label">Value</label><input type="text" name="value" class="form-control" placeholder="6 years (incl. 1-yr internship)" value="<?= e($edit['value'] ?? '') ?>"></div>
            <div class="col-md-3"><label class="form-label">Sort order</label><input type="number" name="sort_order" class="form-control" value="<?= (int) ($edit['sort_order'] ?? 0) ?>"></div>
            <div class="col-md-3 d-flex align-items-end"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_published" id="pub" <?= (!$edit || $edit['is_published']) ? 'checked' : '' ?>><label class="form-check-label" for="pub">Published</label></div></div>
        </div>
        <div class="mt-4"><button class="btn btn-xjmu px-4"><i class="bi bi-save me-2"></i>Save</button> <a href="manage-home-facts" class="btn btn-outline-secondary">Cancel</a></div>
    </form></div></div>
<?php else: ?>
    <div class="d-flex justify-content-end mb-3"><a href="manage-home-facts?action=new" class="btn btn-xjmu"><i class="bi bi-plus-lg me-1"></i>Add Fact</a></div>
    <div class="card"><div class="card-body p-0"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>Label</th><th>Value</th><th>Status</th><th></th></tr></thead><tbody>
        <?php if (!$rows): ?><tr><td colspan="4" class="text-center text-muted py-4">No facts yet.</td></tr>
        <?php else: foreach ($rows as $r): ?>
            <tr><td class="fw-semibold"><?= e($r['label']) ?></td><td class="small"><?= e($r['value'] ?? '') ?></td>
            <td><span class="badge text-bg-<?= $r['is_published'] ? 'success' : 'secondary' ?>"><?= $r['is_published'] ? 'Published' : 'Hidden' ?></span></td>
            <td class="text-end"><a href="manage-home-facts?action=edit&id=<?= (int) $r['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                <form action="manage-home-facts" method="POST" class="d-inline" onsubmit="return confirm('Delete this fact?')"><?= csrf_field() ?><input type="hidden" name="_do" value="delete"><input type="hidden" name="id" value="<?= (int) $r['id'] ?>"><button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>
        <?php endforeach; endif; ?>
        </tbody></table></div></div></div>
<?php endif; ?>
<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
