<?php
require_once __DIR__ . '/includes/auth.php';

$action = $_GET['action'] ?? 'list';
$flash = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require(false);
    try {
        if (($_POST['_do'] ?? '') === 'delete') {
            $pdo->prepare("DELETE FROM programs WHERE id = ?")->execute([(int) $_POST['id']]);
            $_SESSION['flash'] = 'Program deleted.';
        } else {
            $id = (int) ($_POST['id'] ?? 0);
            $cat = in_array($_POST['category'] ?? '', ['bachelor', 'master', 'doctoral', 'non-degree', 'associate'], true) ? $_POST['category'] : 'bachelor';
            $f = [
                trim($_POST['name'] ?? ''), trim($_POST['degree'] ?? ''), $cat, trim($_POST['duration'] ?? ''),
                trim($_POST['language'] ?? ''), trim($_POST['tuition'] ?? ''), trim($_POST['description'] ?? ''),
                (int) ($_POST['sort_order'] ?? 0), isset($_POST['is_published']) ? 1 : 0,
            ];
            if ($f[0] === '') {
                $_SESSION['flash'] = 'Program name is required.';
            } elseif ($id > 0) {
                $pdo->prepare("UPDATE programs SET name=?, degree=?, category=?, duration=?, language=?, tuition=?, description=?, sort_order=?, is_published=? WHERE id=?")
                    ->execute(array_merge($f, [$id]));
                $_SESSION['flash'] = 'Program updated.';
            } else {
                $pdo->prepare("INSERT INTO programs (name, degree, category, duration, language, tuition, description, sort_order, is_published) VALUES (?,?,?,?,?,?,?,?,?)")
                    ->execute($f);
                $_SESSION['flash'] = 'Program added.';
            }
        }
    } catch (\PDOException $e) {
        error_log('manage-programs: ' . $e->getMessage());
        $_SESSION['flash'] = 'Database error. Please try again.';
    }
    header('Location: manage-programs');
    exit;
}

$edit = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM programs WHERE id = ?");
    $stmt->execute([(int) $_GET['id']]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}
$isForm = ($action === 'edit' || $action === 'new');
$rows = $isForm ? [] : $pdo->query("SELECT * FROM programs ORDER BY sort_order, id DESC")->fetchAll(PDO::FETCH_ASSOC);

$admin_page_title = 'Programs';
$admin_subtitle   = 'Courses/programs listed on the public site';
$admin_active     = 'manage-programs';
require __DIR__ . '/includes/layout_top.php';
?>
<?php if ($flash): ?><div class="alert alert-info alert-dismissible fade show"><?= e($flash) ?><button class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<?php if ($isForm): ?>
    <div class="card"><div class="card-header bg-body"><h2 class="h6 mb-0"><?= $edit ? 'Edit' : 'Add' ?> Program</h2></div>
    <div class="card-body">
        <form method="POST">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
            <div class="row g-3">
                <div class="col-md-8"><label class="form-label">Program name</label>
                    <input type="text" name="name" class="form-control" required value="<?= e($edit['name'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label">Degree</label>
                    <input type="text" name="degree" class="form-control" placeholder="MBBS, BSc…" value="<?= e($edit['degree'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label">Category (filter tab)</label>
                    <select name="category" class="form-select">
                        <?php foreach (['bachelor', 'master', 'doctoral', 'non-degree', 'associate'] as $c): ?>
                            <option value="<?= $c ?>" <?= (($edit['category'] ?? 'bachelor') === $c) ? 'selected' : '' ?>><?= ucfirst($c) ?></option>
                        <?php endforeach; ?>
                    </select></div>
                <div class="col-md-4"><label class="form-label">Duration</label>
                    <input type="text" name="duration" class="form-control" placeholder="6 years" value="<?= e($edit['duration'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label">Language</label>
                    <input type="text" name="language" class="form-control" placeholder="English" value="<?= e($edit['language'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label">Tuition</label>
                    <input type="text" name="tuition" class="form-control" placeholder="$3,800 / year" value="<?= e($edit['tuition'] ?? '') ?>"></div>
                <div class="col-12"><label class="form-label">Description</label>
                    <textarea name="description" class="form-control" rows="4"><?= e($edit['description'] ?? '') ?></textarea></div>
                <div class="col-md-3"><label class="form-label">Sort order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= (int) ($edit['sort_order'] ?? 0) ?>"></div>
                <div class="col-md-3 d-flex align-items-end"><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" id="pub" <?= (!$edit || $edit['is_published']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="pub">Published</label></div></div>
            </div>
            <div class="mt-4"><button class="btn btn-xjmu px-4"><i class="bi bi-save me-2"></i>Save</button>
                <a href="manage-programs" class="btn btn-outline-secondary">Cancel</a></div>
        </form>
    </div></div>
<?php else: ?>
    <div class="d-flex justify-content-end mb-3"><a href="manage-programs?action=new" class="btn btn-xjmu"><i class="bi bi-plus-lg me-1"></i>Add Program</a></div>
    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Name</th><th>Degree</th><th>Duration</th><th>Tuition</th><th>Status</th><th></th></tr></thead>
            <tbody>
            <?php if (!$rows): ?><tr><td colspan="6" class="text-center text-muted py-4">No programs yet.</td></tr>
            <?php else: foreach ($rows as $r): ?>
                <tr>
                    <td class="fw-semibold"><?= e($r['name']) ?></td>
                    <td><?= e($r['degree'] ?: '—') ?></td>
                    <td class="small"><?= e($r['duration'] ?: '—') ?></td>
                    <td class="small"><?= e($r['tuition'] ?: '—') ?></td>
                    <td><span class="badge text-bg-<?= $r['is_published'] ? 'success' : 'secondary' ?>"><?= $r['is_published'] ? 'Published' : 'Hidden' ?></span></td>
                    <td class="text-end">
                        <a href="manage-programs?action=edit&id=<?= (int) $r['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="manage-programs" method="POST" class="d-inline" onsubmit="return confirm('Delete this program?')">
                            <?= csrf_field() ?><input type="hidden" name="_do" value="delete"><input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div></div></div>
<?php endif; ?>
<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
