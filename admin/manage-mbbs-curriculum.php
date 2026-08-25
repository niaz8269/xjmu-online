<?php
require_once __DIR__ . '/includes/auth.php';
$action = $_GET['action'] ?? 'list';
$flash = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require(false);
    try {
        if (($_POST['_do'] ?? '') === 'delete') {
            $pdo->prepare("DELETE FROM mbbs_curriculum WHERE id = ?")->execute([(int) $_POST['id']]);
            $_SESSION['flash'] = 'Curriculum stage deleted.';
        } else {
            $id = (int) ($_POST['id'] ?? 0);
            $year = trim($_POST['year_tag'] ?? '');
            $title = trim($_POST['title'] ?? '');
            if ($year === '' || $title === '') { $_SESSION['flash'] = 'Year and title are required.'; }
            else {
                $vals = [$year, $title, trim($_POST['description'] ?? ''), (int) ($_POST['sort_order'] ?? 0), isset($_POST['is_published']) ? 1 : 0];
                if ($id > 0) { $pdo->prepare("UPDATE mbbs_curriculum SET year_tag=?, title=?, description=?, sort_order=?, is_published=? WHERE id=?")->execute(array_merge($vals, [$id])); $_SESSION['flash'] = 'Curriculum stage updated.'; }
                else { $pdo->prepare("INSERT INTO mbbs_curriculum (year_tag,title,description,sort_order,is_published) VALUES (?,?,?,?,?)")->execute($vals); $_SESSION['flash'] = 'Curriculum stage added.'; }
            }
        }
    } catch (\Throwable $e) { error_log('manage-mbbs-curriculum: ' . $e->getMessage()); $_SESSION['flash'] = 'Error: ' . $e->getMessage(); }
    header('Location: manage-mbbs-curriculum'); exit;
}

$edit = null;
if ($action === 'edit' && isset($_GET['id'])) { $stmt = $pdo->prepare("SELECT * FROM mbbs_curriculum WHERE id = ?"); $stmt->execute([(int) $_GET['id']]); $edit = $stmt->fetch(PDO::FETCH_ASSOC); }
$isForm = ($action === 'edit' || $action === 'new');
$rows = $isForm ? [] : $pdo->query("SELECT * FROM mbbs_curriculum ORDER BY sort_order, id")->fetchAll(PDO::FETCH_ASSOC);

$admin_page_title = 'MBBS Curriculum';
$admin_subtitle   = 'Year-by-year structure shown on the MBBS page';
$admin_active     = 'manage-mbbs-curriculum';
require __DIR__ . '/includes/layout_top.php';
?>
<?php if ($flash): ?><div class="alert alert-info alert-dismissible fade show"><?= e($flash) ?><button class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>
<?php if ($isForm): ?>
    <div class="card"><div class="card-header bg-body"><h2 class="h6 mb-0"><?= $edit ? 'Edit' : 'Add' ?> Curriculum Stage</h2></div>
    <div class="card-body"><form method="POST"><?= csrf_field() ?>
        <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
        <div class="row g-3">
            <div class="col-md-4"><label class="form-label">Year / Stage</label><input type="text" name="year_tag" class="form-control" required placeholder="Year 1–2" value="<?= e($edit['year_tag'] ?? '') ?>"></div>
            <div class="col-md-8"><label class="form-label">Title</label><input type="text" name="title" class="form-control" required placeholder="Basic Medical Sciences" value="<?= e($edit['title'] ?? '') ?>"></div>
            <div class="col-12"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="3"><?= e($edit['description'] ?? '') ?></textarea></div>
            <div class="col-md-3"><label class="form-label">Sort order</label><input type="number" name="sort_order" class="form-control" value="<?= (int) ($edit['sort_order'] ?? 0) ?>"></div>
            <div class="col-md-3 d-flex align-items-end"><div class="form-check form-switch"><input class="form-check-input" type="checkbox" name="is_published" id="pub" <?= (!$edit || $edit['is_published']) ? 'checked' : '' ?>><label class="form-check-label" for="pub">Published</label></div></div>
        </div>
        <div class="mt-4"><button class="btn btn-xjmu px-4"><i class="bi bi-save me-2"></i>Save</button> <a href="manage-mbbs-curriculum" class="btn btn-outline-secondary">Cancel</a></div>
    </form></div></div>
<?php else: ?>
    <div class="d-flex justify-content-end mb-3"><a href="manage-mbbs-curriculum?action=new" class="btn btn-xjmu"><i class="bi bi-plus-lg me-1"></i>Add Stage</a></div>
    <div class="card"><div class="card-body p-0"><div class="table-responsive"><table class="table table-hover align-middle mb-0">
        <thead class="table-light"><tr><th>Year</th><th>Title</th><th>Status</th><th></th></tr></thead><tbody>
        <?php if (!$rows): ?><tr><td colspan="4" class="text-center text-muted py-4">No curriculum stages yet.</td></tr>
        <?php else: foreach ($rows as $r): ?>
            <tr><td class="fw-semibold"><?= e($r['year_tag']) ?></td><td><?= e($r['title']) ?><div class="small text-muted"><?= e(mb_strimwidth($r['description'] ?? '', 0, 70, '…')) ?></div></td>
            <td><span class="badge text-bg-<?= $r['is_published'] ? 'success' : 'secondary' ?>"><?= $r['is_published'] ? 'Published' : 'Hidden' ?></span></td>
            <td class="text-end"><a href="manage-mbbs-curriculum?action=edit&id=<?= (int) $r['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                <form action="manage-mbbs-curriculum" method="POST" class="d-inline" onsubmit="return confirm('Delete this stage?')"><?= csrf_field() ?><input type="hidden" name="_do" value="delete"><input type="hidden" name="id" value="<?= (int) $r['id'] ?>"><button class="btn btn-sm btn-outline-danger">Delete</button></form></td></tr>
        <?php endforeach; endif; ?>
        </tbody></table></div></div></div>
<?php endif; ?>
<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
