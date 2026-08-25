<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/helpers.php';

$editing = false;
$event = null;
try {
    $pdo->query("SELECT 1 FROM events LIMIT 1");
} catch (PDOException $e) {
    die("Events table not found. Run install_events_table.sql in phpMyAdmin first.");
}

if (isset($_GET['id'])) {
    $editing = true;
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([(int)$_GET['id']]);
    $event = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$event) die("Event not found.");
}

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require(false); // audit #19
    $title = trim($_POST['title'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $excerpt = trim($_POST['excerpt'] ?? '');
    $content = $_POST['content'] ?? '';
    $event_date = trim($_POST['event_date'] ?? '');
    $event_end_date = !empty($_POST['event_end_date']) ? trim($_POST['event_end_date']) : null;
    $event_time_start = !empty($_POST['event_time_start']) ? trim($_POST['event_time_start']) : null;
    $event_time_end = !empty($_POST['event_time_end']) ? trim($_POST['event_time_end']) : null;
    $venue = trim($_POST['venue'] ?? '');
    // Whitelist status (audit #21).
    $status = in_array($_POST['status'] ?? '', ['draft', 'published'], true) ? $_POST['status'] : 'draft';

    if (empty($slug)) $slug = strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $title));
    if (empty($event_date)) $event_date = date('Y-m-d');

    // Validated (extension + real MIME) like every other upload path in this
    // app; a raw pathinfo() extension would let .html/.svg through for stored XSS.
    $featured_image = $editing ? ($event['featured_image'] ?? '') : '';
    try {
        $uploaded = save_upload('featured_image', 'events', IMG_EXT(), IMG_MIME(), 5 * 1024 * 1024);
        if ($uploaded !== null) {
            $featured_image = $uploaded;
        }
    } catch (RuntimeException $e) {
        $error_message = $e->getMessage();
    }

    try {
        if ($editing) {
            $pdo->prepare("UPDATE events SET title=?, slug=?, excerpt=?, content=?, event_date=?, event_end_date=?, event_time_start=?, event_time_end=?, venue=?, featured_image=?, status=? WHERE id=?")
                ->execute([$title, $slug, $excerpt, $content, $event_date, $event_end_date, $event_time_start, $event_time_end, $venue, $featured_image, $status, $event['id']]);
            $_SESSION['event_message'] = 'Event updated.';
        } else {
            $pdo->prepare("INSERT INTO events (title, slug, excerpt, content, event_date, event_end_date, event_time_start, event_time_end, venue, featured_image, status) VALUES (?,?,?,?,?,?,?,?,?,?,?)")
                ->execute([$title, $slug, $excerpt, $content, $event_date, $event_end_date, $event_time_start, $event_time_end, $venue, $featured_image, $status]);
            $_SESSION['event_message'] = 'Event created.';
        }
        header('Location: manage-events');
        exit;
    } catch (PDOException $e) {
        $error_message = $e->getCode() == 23000 ? 'Slug already exists.' : $e->getMessage();
    }
}
$admin_page_title = $editing ? 'Edit Event' : 'Add Event';
$admin_active     = 'manage-events';
require __DIR__ . '/includes/layout_top.php';
?>
<div class="card"><div class="card-body">

        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="post" enctype="multipart/form-data" class="card p-4">
            <?php echo csrf_field(); ?>
            <div class="mb-3">
                <label class="form-label">Title *</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($event['title'] ?? $_POST['title'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Slug (URL)</label>
                <input type="text" name="slug" class="form-control" value="<?php echo htmlspecialchars($event['slug'] ?? $_POST['slug'] ?? ''); ?>" placeholder="auto from title if empty">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Event date *</label>
                    <input type="date" name="event_date" class="form-control" value="<?php echo htmlspecialchars($event['event_date'] ?? $_POST['event_date'] ?? date('Y-m-d')); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">End date (optional)</label>
                    <input type="date" name="event_end_date" class="form-control" value="<?php echo htmlspecialchars($event['event_end_date'] ?? $_POST['event_end_date'] ?? ''); ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">From time (optional)</label>
                    <input type="time" name="event_time_start" class="form-control" value="<?php echo htmlspecialchars(substr($event['event_time_start'] ?? $_POST['event_time_start'] ?? '', 0, 5)); ?>" placeholder="e.g. 14:00 for 2 PM">
                    <small class="text-muted">For webinars/seminars: start time</small>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">To time (optional)</label>
                    <input type="time" name="event_time_end" class="form-control" value="<?php echo htmlspecialchars(substr($event['event_time_end'] ?? $_POST['event_time_end'] ?? '', 0, 5)); ?>" placeholder="e.g. 17:00 for 5 PM">
                    <small class="text-muted">End time</small>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Venue</label>
                <input type="text" name="venue" class="form-control" value="<?php echo htmlspecialchars($event['venue'] ?? $_POST['venue'] ?? ''); ?>" placeholder="e.g. Main Hall, Online">
            </div>
            <div class="mb-3">
                <label class="form-label">Excerpt (short description)</label>
                <textarea name="excerpt" class="form-control" rows="2"><?php echo htmlspecialchars($event['excerpt'] ?? $_POST['excerpt'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Content (HTML allowed)</label>
                <textarea name="content" class="form-control" rows="8"><?php echo htmlspecialchars($event['content'] ?? $_POST['content'] ?? ''); ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Featured image</label>
                <input type="file" name="featured_image" class="form-control" accept="image/*">
                <?php if ($editing && !empty($event['featured_image'])): ?>
                    <p class="small text-muted mt-1">Current: <a href="../<?php echo htmlspecialchars($event['featured_image']); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($event['featured_image']); ?></a></p>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="draft" <?php echo ($event['status'] ?? '') === 'draft' ? 'selected' : ''; ?>>Draft</option>
                    <option value="published" <?php echo ($event['status'] ?? '') === 'published' ? 'selected' : ''; ?>>Published</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $editing ? 'Update' : 'Create'; ?> Event</button>
        </form>
</div></div>
<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
