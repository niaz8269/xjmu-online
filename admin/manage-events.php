<?php
require_once __DIR__ . '/includes/auth.php';

$events = [];
try {
    $pdo->query("SELECT 1 FROM events LIMIT 1");
    $events = $pdo->query("SELECT * FROM events ORDER BY event_date DESC, id DESC")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // Table may not exist
}

$admin_page_title = 'Manage Events';
$admin_active     = 'manage-events';
require __DIR__ . '/includes/layout_top.php';
?>
<div class="card"><div class="card-body">
    <div class="d-flex justify-content-end mb-3">
        <a href="edit-event" class="btn btn-primary">Add Event</a>
    </div>

    <?php if (isset($_SESSION['event_message'])): ?>
        <div class="alert alert-success"><?php echo htmlspecialchars($_SESSION['event_message']); unset($_SESSION['event_message']); ?></div>
    <?php endif; ?>

    <?php if (empty($events)): ?>
        <div class="card">
            <div class="card-body text-center py-5">
                <p class="text-muted">No events yet.</p>
                <p class="small text-muted">Run <code>install_events_table.sql</code> in phpMyAdmin if you haven't.</p>
                <a href="edit-event" class="btn btn-primary mt-2">Add first event</a>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Venue</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $fmt_time = function($start, $end) {
                            if (empty($start)) return '&mdash;';
                            $s = date('g:i A', strtotime('1970-01-01 ' . $start));
                            if (!empty($end) && $end !== $start) $s .= ' &ndash; ' . date('g:i A', strtotime('1970-01-01 ' . $end));
                            return $s;
                        };
                        foreach ($events as $ev): ?>
                            <tr>
                                <td><strong><?php echo htmlspecialchars($ev['title']); ?></strong></td>
                                <td><?php echo date('M j, Y', strtotime($ev['event_date'])); ?></td>
                                <td><?php echo $fmt_time($ev['event_time_start'] ?? '', $ev['event_time_end'] ?? null); ?></td>
                                <td><?php echo htmlspecialchars($ev['venue'] ?? '—'); ?></td>
                                <td><span class="badge bg-<?php echo $ev['status'] === 'published' ? 'success' : 'secondary'; ?>"><?php echo e($ev['status']); ?></span></td>
                                <td>
                                    <a href="edit-event?id=<?php echo (int)$ev['id']; ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <a href="../event?slug=<?php echo urlencode($ev['slug']); ?>" class="btn btn-sm btn-outline-info" target="_blank" rel="noopener noreferrer">View</a>
                                    <form action="delete-event" method="POST" class="d-inline" onsubmit="return confirm('Delete this event?');">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="id" value="<?php echo (int)$ev['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div></div>
<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
