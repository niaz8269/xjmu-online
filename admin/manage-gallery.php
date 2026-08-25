<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/helpers.php';

$action = $_GET['action'] ?? 'list';
$flash = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require(false);
    try {
        if (($_POST['_do'] ?? '') === 'delete') {
            $row = $pdo->prepare("SELECT image_path FROM gallery WHERE id = ?");
            $row->execute([(int) $_POST['id']]);
            $img = $row->fetchColumn();
            $pdo->prepare("DELETE FROM gallery WHERE id = ?")->execute([(int) $_POST['id']]);
            if ($img && file_exists(__DIR__ . '/../' . $img)) @unlink(__DIR__ . '/../' . $img);
            $_SESSION['flash'] = 'Image deleted.';
        } else {
            $id = (int) ($_POST['id'] ?? 0);
            $imgPath = save_upload('image', 'gallery', IMG_EXT(), IMG_MIME(), 6 * 1024 * 1024);
            if ($id === 0 && !$imgPath) {
                $_SESSION['flash'] = 'Please choose an image to upload.';
            } else {
                $f = [trim($_POST['title'] ?? ''), trim($_POST['category'] ?? ''),
                      (int) ($_POST['sort_order'] ?? 0), isset($_POST['is_published']) ? 1 : 0];
                if ($id > 0) {
                    if ($imgPath) {
                        $pdo->prepare("UPDATE gallery SET title=?, category=?, sort_order=?, is_published=?, image_path=? WHERE id=?")
                            ->execute(array_merge($f, [$imgPath, $id]));
                    } else {
                        $pdo->prepare("UPDATE gallery SET title=?, category=?, sort_order=?, is_published=? WHERE id=?")
                            ->execute(array_merge($f, [$id]));
                    }
                    $_SESSION['flash'] = 'Image updated.';
                } else {
                    $pdo->prepare("INSERT INTO gallery (title, category, sort_order, is_published, image_path) VALUES (?,?,?,?,?)")
                        ->execute(array_merge($f, [$imgPath]));
                    $_SESSION['flash'] = 'Image added.';
                }
            }
        }
    } catch (\Throwable $e) {
        error_log('manage-gallery: ' . $e->getMessage());
        $_SESSION['flash'] = 'Error: ' . $e->getMessage();
    }
    header('Location: manage-gallery');
    exit;
}

$edit = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM gallery WHERE id = ?");
    $stmt->execute([(int) $_GET['id']]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}
$isForm = ($action === 'edit' || $action === 'new');
$rows = $isForm ? [] : $pdo->query("SELECT * FROM gallery ORDER BY sort_order, id DESC")->fetchAll(PDO::FETCH_ASSOC);

$admin_page_title = 'Gallery';
$admin_subtitle   = 'Photos shown in the public gallery';
$admin_active     = 'manage-gallery';
require __DIR__ . '/includes/layout_top.php';
?>
<?php if ($flash): ?><div class="alert alert-info alert-dismissible fade show"><?= e($flash) ?><button class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<?php if ($isForm): ?>
    <div class="card"><div class="card-header bg-body"><h2 class="h6 mb-0"><?= $edit ? 'Edit' : 'Add' ?> Image</h2></div>
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Title / Caption</label>
                    <input type="text" name="title" class="form-control" value="<?= e($edit['title'] ?? '') ?>"></div>
                <div class="col-md-6"><label class="form-label">Category</label>
                    <input type="text" name="category" class="form-control" placeholder="Campus, Events…" value="<?= e($edit['category'] ?? '') ?>"></div>
                <div class="col-md-8"><label class="form-label">Image <?= $edit && $edit['image_path'] ? '(leave empty to keep current)' : '' ?></label>
                    <input type="file" name="image" class="form-control" accept="image/*">
                    <?php if ($edit && $edit['image_path']): ?><img src="../<?= e($edit['image_path']) ?>" class="mt-2 rounded" style="height:70px"><?php endif; ?></div>
                <div class="col-md-2"><label class="form-label">Sort</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= (int) ($edit['sort_order'] ?? 0) ?>"></div>
                <div class="col-md-2 d-flex align-items-end"><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" id="pub" <?= (!$edit || $edit['is_published']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="pub">Published</label></div></div>
            </div>
            <div class="mt-4"><button class="btn btn-xjmu px-4"><i class="bi bi-save me-2"></i>Save</button>
                <a href="manage-gallery" class="btn btn-outline-secondary">Cancel</a></div>
        </form>
    </div></div>
<?php else: ?>
    <div class="d-flex justify-content-end mb-3"><a href="manage-gallery?action=new" class="btn btn-xjmu"><i class="bi bi-plus-lg me-1"></i>Add Image</a></div>
    <div class="row g-3">
        <?php if (!$rows): ?><div class="col-12"><div class="card"><div class="card-body text-center text-muted py-5">No images yet.</div></div></div>
        <?php else: foreach ($rows as $r): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100">
                    <img src="../<?= e($r['image_path']) ?>" class="card-img-top" style="height:150px;object-fit:cover" alt="<?= e($r['title']) ?>">
                    <div class="card-body p-2">
                        <div class="small fw-semibold text-truncate"><?= e($r['title'] ?: '—') ?></div>
                        <div class="small text-muted"><?= e($r['category'] ?: '') ?>
                            <span class="badge text-bg-<?= $r['is_published'] ? 'success' : 'secondary' ?> ms-1"><?= $r['is_published'] ? 'On' : 'Off' ?></span></div>
                    </div>
                    <div class="card-footer p-2 d-flex justify-content-between bg-body">
                        <a href="manage-gallery?action=edit&id=<?= (int) $r['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="manage-gallery" method="POST" onsubmit="return confirm('Delete this image?')">
                            <?= csrf_field() ?><input type="hidden" name="_do" value="delete"><input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </div>
                </div>
            </div>
        <?php endforeach; endif; ?>
    </div>
<?php endif; ?>
<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
