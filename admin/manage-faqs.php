<?php
require_once __DIR__ . '/includes/auth.php';

$table = 'faqs';
$action = $_GET['action'] ?? 'list';
$flash = $_SESSION['flash'] ?? ''; unset($_SESSION['flash']);

// ---- Handle POST (save / delete) ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require(false);
    try {
        if (($_POST['_do'] ?? '') === 'delete') {
            $pdo->prepare("DELETE FROM faqs WHERE id = ?")->execute([(int) $_POST['id']]);
            $_SESSION['flash'] = 'FAQ deleted.';
        } else {
            $id        = (int) ($_POST['id'] ?? 0);
            $question  = trim($_POST['question'] ?? '');
            $answer    = trim($_POST['answer'] ?? '');
            $category  = trim($_POST['category'] ?? '');
            $sort      = (int) ($_POST['sort_order'] ?? 0);
            $published = isset($_POST['is_published']) ? 1 : 0;
            if ($question === '' || $answer === '') {
                $_SESSION['flash'] = 'Question and answer are required.';
            } elseif ($id > 0) {
                $pdo->prepare("UPDATE faqs SET question=?, answer=?, category=?, sort_order=?, is_published=? WHERE id=?")
                    ->execute([$question, $answer, $category, $sort, $published, $id]);
                $_SESSION['flash'] = 'FAQ updated.';
            } else {
                $pdo->prepare("INSERT INTO faqs (question, answer, category, sort_order, is_published) VALUES (?,?,?,?,?)")
                    ->execute([$question, $answer, $category, $sort, $published]);
                $_SESSION['flash'] = 'FAQ added.';
            }
        }
    } catch (\PDOException $e) {
        error_log('manage-faqs: ' . $e->getMessage());
        $_SESSION['flash'] = 'Database error. Please try again.';
    }
    header('Location: manage-faqs');
    exit;
}

// ---- Load a row for editing ----
$edit = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM faqs WHERE id = ?");
    $stmt->execute([(int) $_GET['id']]);
    $edit = $stmt->fetch(PDO::FETCH_ASSOC);
}
$isForm = ($action === 'edit' || $action === 'new');

$rows = $isForm ? [] : $pdo->query("SELECT * FROM faqs ORDER BY sort_order, id DESC")->fetchAll(PDO::FETCH_ASSOC);

$admin_page_title = 'FAQs';
$admin_subtitle   = 'Frequently asked questions shown on the public site';
$admin_active     = 'manage-faqs';
require __DIR__ . '/includes/layout_top.php';
?>

<?php if ($flash): ?>
    <div class="alert alert-info alert-dismissible fade show"><?= e($flash) ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endif; ?>

<?php if ($isForm): ?>
    <div class="card">
        <div class="card-header bg-body"><h2 class="h6 mb-0"><?= $edit ? 'Edit' : 'Add' ?> FAQ</h2></div>
        <div class="card-body">
            <form method="POST">
                <?= csrf_field() ?>
                <input type="hidden" name="id" value="<?= (int) ($edit['id'] ?? 0) ?>">
                <div class="mb-3">
                    <label class="form-label">Question</label>
                    <input type="text" name="question" class="form-control" required value="<?= e($edit['question'] ?? '') ?>">
                </div>
                <div class="mb-3">
                    <label class="form-label">Answer</label>
                    <textarea name="answer" class="form-control" rows="4" required><?= e($edit['answer'] ?? '') ?></textarea>
                </div>
                <div class="row g-3">
                    <div class="col-md-5"><label class="form-label">Category (optional)</label>
                        <input type="text" name="category" class="form-control" value="<?= e($edit['category'] ?? '') ?>"></div>
                    <div class="col-md-3"><label class="form-label">Sort order</label>
                        <input type="number" name="sort_order" class="form-control" value="<?= (int) ($edit['sort_order'] ?? 0) ?>"></div>
                    <div class="col-md-4 d-flex align-items-end">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_published" id="pub" <?= (!$edit || $edit['is_published']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="pub">Published</label>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-xjmu px-4"><i class="bi bi-save me-2"></i>Save</button>
                    <a href="manage-faqs" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
<?php else: ?>
    <div class="d-flex justify-content-end mb-3">
        <a href="manage-faqs?action=new" class="btn btn-xjmu"><i class="bi bi-plus-lg me-1"></i>Add FAQ</a>
    </div>
    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light"><tr><th>Question</th><th>Category</th><th>Order</th><th>Status</th><th></th></tr></thead>
                    <tbody>
                    <?php if (!$rows): ?>
                        <tr><td colspan="5" class="text-center text-muted py-4">No FAQs yet. Click “Add FAQ”.</td></tr>
                    <?php else: foreach ($rows as $r): ?>
                        <tr>
                            <td class="fw-semibold"><?= e($r['question']) ?></td>
                            <td class="small text-muted"><?= e($r['category'] ?: '—') ?></td>
                            <td><?= (int) $r['sort_order'] ?></td>
                            <td><span class="badge text-bg-<?= $r['is_published'] ? 'success' : 'secondary' ?>"><?= $r['is_published'] ? 'Published' : 'Hidden' ?></span></td>
                            <td class="text-end">
                                <a href="manage-faqs?action=edit&id=<?= (int) $r['id'] ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="manage-faqs" method="POST" class="d-inline" onsubmit="return confirm('Delete this FAQ?')">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="_do" value="delete">
                                    <input type="hidden" name="id" value="<?= (int) $r['id'] ?>">
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
