<?php
require_once __DIR__ . '/includes/auth.php';

$pagesAvailable = ['about' => 'About', 'admissions' => 'Admissions', 'mbbs' => 'MBBS Program', 'fees' => 'Fees', 'support' => 'Support', 'scholarships' => 'Scholarships'];
$page_key = $_GET['page'] ?? 'about';
if (!isset($pagesAvailable[$page_key])) $page_key = 'about';

$action = $_GET['action'] ?? 'list';
$flash = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require(false);
    $pk = $_POST['page_key'] ?? 'about';
    try {
        if (($_POST['_do'] ?? '') === 'delete') {
            $pdo->prepare("DELETE FROM page_sections WHERE id = ?")->execute([(int) $_POST['id']]);
            $_SESSION['flash'] = 'Section deleted.';
        } else {
            $id        = (int) ($_POST['id'] ?? 0);
            $section   = trim($_POST['section_key'] ?? '');
            $heading   = trim($_POST['heading'] ?? '');
            $body      = $_POST['body'] ?? '';                 // rich HTML from Quill
            $sort      = (int) ($_POST['sort_order'] ?? 0);
            $published = isset($_POST['is_published']) ? 1 : 0;
            if ($section === '') $section = 'section-' . substr(md5($heading . microtime()), 0, 6);
            if ($id > 0) {
                $pdo->prepare("UPDATE page_sections SET section_key=?, heading=?, body=?, sort_order=?, is_published=? WHERE id=?")
                    ->execute([$section, $heading, $body, $sort, $published, $id]);
                $_SESSION['flash'] = 'Section saved.';
            } else {
                $pdo->prepare("INSERT INTO page_sections (page_key, section_key, heading, body, sort_order, is_published) VALUES (?,?,?,?,?,?)")
                    ->execute([$pk, $section, $heading, $body, $sort, $published]);
                $_SESSION['flash'] = 'Section added.';
            }
        }
    } catch (\PDOException $e) {
        error_log('manage-pages: ' . $e->getMessage());
        $_SESSION['flash'] = 'Database error. Please try again.';
    }
    header('Location: manage-pages?page=' . urlencode($pk));
    exit;
}

$edit = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM page_sections WHERE id = ?");
    $stmt->execute([(int) $_GET['id']]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($edit) $page_key = $edit['page_key'];
}
$isForm = ($action === 'edit' || $action === 'new');

$rows = [];
if (!$isForm) {
    $stmt = $pdo->prepare("SELECT * FROM page_sections WHERE page_key = ? ORDER BY sort_order, id");
    $stmt->execute([$page_key]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$admin_page_title = 'Page Content';
$admin_subtitle   = 'Edit the text sections of your main pages';
$admin_active     = 'manage-pages';
require __DIR__ . '/includes/layout_top.php';
?>
<?php if ($flash): ?><div class="alert alert-info alert-dismissible fade show"><?= e($flash) ?><button class="btn-close" data-bs-dismiss="alert"></button></div><?php endif; ?>

<?php if ($isForm): ?>
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet">
    <div class="card"><div class="card-header bg-body"><h2 class="h6 mb-0"><?= $edit ? 'Edit' : 'Add' ?> Section — <?= e($pagesAvailable[$page_key]) ?></h2></div>
    <div class="card-body">
        <form method="POST" id="sectionForm">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
            <input type="hidden" name="page_key" value="<?= e($page_key) ?>">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Heading (optional)</label>
                    <input type="text" name="heading" class="form-control" value="<?= e($edit['heading'] ?? '') ?>"></div>
                <div class="col-md-3"><label class="form-label">Section key</label>
                    <input type="text" name="section_key" class="form-control" placeholder="history" value="<?= e($edit['section_key'] ?? '') ?>"></div>
                <div class="col-md-3"><label class="form-label">Sort order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= (int) ($edit['sort_order'] ?? 0) ?>"></div>
                <div class="col-12">
                    <label class="form-label">Content</label>
                    <div id="editor" style="min-height:240px;background:#fff;"><?= $edit['body'] ?? '' ?></div>
                    <input type="hidden" name="body" id="bodyInput">
                </div>
                <div class="col-12"><div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="is_published" id="pub" <?= (!$edit || $edit['is_published']) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="pub">Published</label></div></div>
            </div>
            <div class="mt-4"><button type="submit" class="btn btn-xjmu px-4"><i class="bi bi-save me-2"></i>Save</button>
                <a href="manage-pages?page=<?= e($page_key) ?>" class="btn btn-outline-secondary">Cancel</a></div>
        </form>
    </div></div>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script>
        const quill = new Quill('#editor', {
            theme: 'snow',
            modules: { toolbar: [
                [{ header: [2, 3, false] }],
                ['bold', 'italic', 'underline', 'link'],
                [{ list: 'ordered' }, { list: 'bullet' }],
                ['blockquote'], ['clean']
            ]}
        });
        document.getElementById('sectionForm').addEventListener('submit', function () {
            document.getElementById('bodyInput').value = quill.root.innerHTML;
        });
    </script>
<?php else: ?>
    <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center mb-3">
        <ul class="nav nav-pills">
            <?php foreach ($pagesAvailable as $pk => $pl): ?>
                <li class="nav-item"><a class="nav-link<?= $pk === $page_key ? ' active' : '' ?>" href="manage-pages?page=<?= e($pk) ?>"><?= e($pl) ?></a></li>
            <?php endforeach; ?>
        </ul>
        <a href="manage-pages?action=new&page=<?= e($page_key) ?>" class="btn btn-xjmu"><i class="bi bi-plus-lg me-1"></i>Add Section</a>
    </div>
    <div class="card"><div class="card-body p-0"><div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Heading</th><th>Section key</th><th>Order</th><th>Status</th><th></th></tr></thead>
            <tbody>
            <?php if (!$rows): ?><tr><td colspan="5" class="text-center text-muted py-4">No sections for this page yet. Click “Add Section”.</td></tr>
            <?php else: foreach ($rows as $r): ?>
                <tr>
                    <td class="fw-semibold"><?= e($r['heading'] ?: '(no heading)') ?></td>
                    <td class="small text-muted"><?= e($r['section_key']) ?></td>
                    <td><?= (int) $r['sort_order'] ?></td>
                    <td><span class="badge text-bg-<?= $r['is_published'] ? 'success' : 'secondary' ?>"><?= $r['is_published'] ? 'Published' : 'Hidden' ?></span></td>
                    <td class="text-end">
                        <a href="manage-pages?action=edit&id=<?= (int) $r['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                        <form action="manage-pages" method="POST" class="d-inline" onsubmit="return confirm('Delete this section?')">
                            <?= csrf_field() ?><input type="hidden" name="_do" value="delete"><input type="hidden" name="page_key" value="<?= e($page_key) ?>"><input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                            <button class="btn btn-sm btn-outline-danger">Delete</button></form>
                    </td>
                </tr>
            <?php endforeach; endif; ?>
            </tbody>
        </table>
    </div></div></div>
<?php endif; ?>
<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
