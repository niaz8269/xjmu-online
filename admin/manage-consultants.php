<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/helpers.php';

$action = $_GET['action'] ?? 'list';
$flash = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require(false);
    try {
        if (($_POST['_do'] ?? '') === 'delete') {
            $row = $pdo->prepare("SELECT photo FROM consultants WHERE id = ?");
            $row->execute([(int) $_POST['id']]);
            $photo = $row->fetchColumn();
            $pdo->prepare("DELETE FROM consultants WHERE id = ?")->execute([(int) $_POST['id']]);
            if ($photo && file_exists(__DIR__ . '/../' . $photo)) @unlink(__DIR__ . '/../' . $photo);
            $_SESSION['flash'] = 'Consultant deleted.';
        } else {
            $id = (int) ($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            if ($name === '') {
                $_SESSION['flash'] = 'Name is required.';
            } else {
                $photoPath = save_upload('photo', 'consultants', IMG_EXT(), IMG_MIME(), 4 * 1024 * 1024);
                $qrPath    = save_upload('qr_image', 'consultants', IMG_EXT(), IMG_MIME(), 4 * 1024 * 1024);
                // Base columns always written; uploads appended only when provided.
                $cols = ['name', 'role', 'whatsapp', 'phone', 'email', 'wechat', 'sort_order', 'is_published'];
                $vals = [
                    $name, trim($_POST['role'] ?? ''), trim($_POST['whatsapp'] ?? ''),
                    trim($_POST['phone'] ?? ''), trim($_POST['email'] ?? ''), trim($_POST['wechat'] ?? ''),
                    (int) ($_POST['sort_order'] ?? 0), isset($_POST['is_published']) ? 1 : 0,
                ];
                if ($photoPath) { $cols[] = 'photo';    $vals[] = $photoPath; }
                if ($qrPath)    { $cols[] = 'qr_image'; $vals[] = $qrPath; }
                if ($id > 0) {
                    $set = implode(', ', array_map(fn($c) => "$c=?", $cols));
                    $pdo->prepare("UPDATE consultants SET $set WHERE id=?")->execute(array_merge($vals, [$id]));
                    $_SESSION['flash'] = 'Consultant updated.';
                } else {
                    $ph = implode(',', array_fill(0, count($cols), '?'));
                    $pdo->prepare("INSERT INTO consultants (" . implode(',', $cols) . ") VALUES ($ph)")->execute($vals);
                    $_SESSION['flash'] = 'Consultant added.';
                }
            }
        }
    } catch (\Throwable $e) {
        error_log('manage-consultants: ' . $e->getMessage());
        $_SESSION['flash'] = 'Error: ' . $e->getMessage();
    }
    header('Location: manage-consultants');
    exit;
}

$edit = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM consultants WHERE id = ?");
    $stmt->execute([(int) $_GET['id']]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}
$isForm = ($action === 'edit' || $action === 'new');
$rows = $isForm ? [] : $pdo->query("SELECT * FROM consultants ORDER BY sort_order, id DESC")->fetchAll(PDO::FETCH_ASSOC);

$admin_page_title = 'Consultants';
$admin_subtitle   = 'Counsellors shown on the public site';
$admin_active     = 'manage-consultants';
require __DIR__ . '/includes/layout_top.php';
?>
<?php if ($flash): ?><div class="alert alert-info alert-dismissible fade show"><?= e($flash) ?><button class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<?php if ($isForm): ?>
    <div class="card"><div class="card-header bg-body"><h2 class="h6 mb-0"><?= $edit ? 'Edit' : 'Add' ?> Consultant</h2></div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" required value="<?= e($edit['name'] ?? '') ?>"></div>
                <div class="col-md-6"><label class="form-label">Role / Title</label>
                    <input type="text" name="role" class="form-control" placeholder="Admissions Counsellor" value="<?= e($edit['role'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label">WhatsApp (digits)</label>
                    <input type="text" name="whatsapp" class="form-control" placeholder="8617590820634" value="<?= e($edit['whatsapp'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= e($edit['phone'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= e($edit['email'] ?? '') ?>"></div>
                <div class="col-md-4"><label class="form-label">WeChat ID</label>
                    <input type="text" name="wechat" class="form-control" value="<?= e($edit['wechat'] ?? '') ?>"></div>
                <div class="col-md-6"><label class="form-label">Photo / Avatar <?= $edit && $edit['photo'] ? '(leave empty to keep current)' : '' ?></label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                    <?php if ($edit && $edit['photo']): ?><img src="../<?= e($edit['photo']) ?>" class="mt-2 rounded" style="height:60px"><?php endif; ?></div>
                <div class="col-md-6"><label class="form-label">QR Code image <?= $edit && !empty($edit['qr_image']) ? '(leave empty to keep current)' : '' ?></label>
                    <input type="file" name="qr_image" class="form-control" accept="image/*">
                    <?php if ($edit && !empty($edit['qr_image'])): ?><img src="../<?= e($edit['qr_image']) ?>" class="mt-2 rounded" style="height:60px"><?php endif; ?></div>
                <div class="col-md-3"><label class="form-label">Sort order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= (int) ($edit['sort_order'] ?? 0) ?>"></div>
                <div class="col-md-3 d-flex align-items-end"><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" id="pub" <?= (!$edit || $edit['is_published']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="pub">Published</label></div></div>
            </div>
            <div class="mt-4"><button class="btn btn-xjmu px-4"><i class="bi bi-save me-2"></i>Save</button>
                <a href="manage-consultants" class="btn btn-outline-secondary">Cancel</a></div>
        </form>
    </div></div>
<?php else: ?>
    <div class="d-flex justify-content-end mb-3"><a href="manage-consultants?action=new" class="btn btn-xjmu"><i class="bi bi-plus-lg me-1"></i>Add Consultant</a></div>
    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th></th><th>Name</th><th>Role</th><th>WhatsApp</th><th>Status</th><th></th></tr></thead>
            <tbody>
            <?php if (!$rows): ?><tr><td colspan="6" class="text-center text-muted py-4">No consultants yet.</td></tr>
            <?php else: foreach ($rows as $r): ?>
                <tr>
                    <td><?php if ($r['photo']): ?><img src="../<?= e($r['photo']) ?>" class="rounded-circle" style="width:38px;height:38px;object-fit:cover"><?php else: ?><i class="bi bi-person-circle fs-3 text-muted"></i><?php endif; ?></td>
                    <td class="fw-semibold"><?= e($r['name']) ?></td>
                    <td class="small"><?= e($r['role'] ?: '—') ?></td>
                    <td class="small"><?= e($r['whatsapp'] ?: '—') ?></td>
                    <td><span class="badge text-bg-<?= $r['is_published'] ? 'success' : 'secondary' ?>"><?= $r['is_published'] ? 'Published' : 'Hidden' ?></span></td>
                    <td class="text-end">
                        <a href="manage-consultants?action=edit&id=<?= (int) $r['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="manage-consultants" method="POST" class="d-inline" onsubmit="return confirm('Delete this consultant?')">
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
