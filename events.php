<?php
require_once 'session_config.php';

require_once 'db.php';
require_once 'config.php';
require_once 'includes/security.php';

$page_title = "News & Events | Xinjiang Medical University";
$page_description = "Stay updated with the latest campus events, webinars, open days, and news at Xinjiang Medical University. Browse upcoming activities for 2026 students.";
$page_keywords = "XJMU events, campus events, medical university events, Xinjiang Medical University news, webinars";
$page_url   = "https://xjmu.online/events";
$page_image = "https://xjmu.online/images/logo.png";
$body_class = 'ds-page';
$page_css   = ['css/design-system.css', 'css/events.css'];

// Check if events table exists and fetch events
$events = [];
$table_exists = false;
try {
    $pdo->query("SELECT 1 FROM events LIMIT 1");
    $table_exists = true;
} catch (PDOException $e) {
    $table_exists = false;
}

if ($table_exists) {
    $stmt = $pdo->prepare("
        SELECT id, title, slug, excerpt, event_date, event_end_date, event_time_start, event_time_end, venue, featured_image, status
        FROM events
        WHERE status = 'published'
        ORDER BY event_date DESC, id DESC
    ");
    $stmt->execute();
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function format_event_date($start, $end = null) {
    $s = date('M j, Y', strtotime($start));
    if ($end && $end !== $start) {
        $s .= ' – ' . date('M j, Y', strtotime($end));
    }
    return $s;
}

function format_event_time($start, $end = null) {
    if (empty($start)) return '';
    $s = date('g:i A', strtotime('1970-01-01 ' . $start));
    if (!empty($end) && $end !== $start) {
        $s .= ' – ' . date('g:i A', strtotime('1970-01-01 ' . $end));
    }
    return $s;
}

function get_event_image($path) {
    if (!empty($path) && file_exists($path)) {
        return get_absolute_url($path);
    }
    return 'data:image/svg+xml,' . urlencode('<svg xmlns="http://www.w3.org/2000/svg" width="400" height="250"><defs><linearGradient id="g" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:#e72128"/><stop offset="100%" style="stop-color:#cc0000"/></linearGradient></defs><rect width="400" height="250" fill="url(#g)"/><text x="200" y="130" fill="white" text-anchor="middle" font-size="18" font-family="sans-serif">Event</text></svg>');
}
include __DIR__ . '/includes/header.php';
?>
    <div class="events-page">
        <section class="events-hero">
            <div class="container">
                <span class="ds-eyebrow" style="color:#ffd0d2">Campus Life</span>
                <h1 data-i18n="events.hero_title">News &amp; Events</h1>
                <p data-i18n="events.hero_desc">Latest campus events, webinars, and news from Xinjiang Medical University.</p>
            </div>
        </section>

        <div class="container">
            <nav class="events-crumb" aria-label="breadcrumb">
                <a href="<?= get_absolute_url('index') ?>">Home</a> &rsaquo; <span>Events</span>
            </nav>
        </div>

        <section class="events-section">
            <div class="events-container">
                <?php if (!$table_exists): ?>
                    <div class="events-empty">
                        <p data-i18n="events.setup_message">Events will appear here once the database table is set up.</p>
                        <p class="text-muted small">Run <code>install_events_table.sql</code> in phpMyAdmin, then add events from the admin panel.</p>
                    </div>
                <?php elseif (empty($events)): ?>
                    <div class="events-empty">
                        <h3 data-i18n="events.no_events">No upcoming events</h3>
                        <p data-i18n="events.no_events_desc">Check back later for campus events, webinars, and news.</p>
                    </div>
                <?php else: ?>
                    <div class="events-list">
                        <?php foreach ($events as $event): ?>
                            <article class="event-card">
                                <div class="event-card-image">
                                    <a href="event?slug=<?php echo urlencode($event['slug']); ?>" target="_blank" rel="noopener noreferrer">
                                        <img src="<?php echo get_event_image($event['featured_image'] ?? ''); ?>" alt="<?php echo htmlspecialchars($event['title']); ?>" loading="lazy">
                                    </a>
                                    <span class="event-card-date"><?php echo format_event_date($event['event_date'], $event['event_end_date'] ?? null); ?></span>
                                </div>
                                <div class="event-card-content">
                                    <h2 class="event-card-title">
                                        <a href="event?slug=<?php echo urlencode($event['slug']); ?>" target="_blank" rel="noopener noreferrer"><?php echo htmlspecialchars($event['title']); ?></a>
                                    </h2>
                                    <?php
                                    $time_str = format_event_time($event['event_time_start'] ?? '', $event['event_time_end'] ?? null);
                                    if ($time_str): ?>
                                        <p class="event-time"><i class="fas fa-clock"></i> <?php echo htmlspecialchars($time_str); ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($event['venue'])): ?>
                                        <p class="event-venue"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['venue']); ?></p>
                                    <?php endif; ?>
                                    <?php if (!empty($event['excerpt'])): ?>
                                        <p class="event-excerpt"><?php echo htmlspecialchars($event['excerpt']); ?></p>
                                    <?php endif; ?>
                                    <div class="event-card-actions">
                                        <a href="event?slug=<?php echo urlencode($event['slug']); ?>" class="event-card-link" target="_blank" rel="noopener noreferrer" data-i18n="events.view_details">View details</a>
                                        <button type="button" class="btn btn-sm btn-danger event-register-btn" data-event-title="<?php echo htmlspecialchars($event['title']); ?>" data-event-date="<?php echo htmlspecialchars(date('j F Y', strtotime($event['event_date']))); ?>" data-event-time="<?php echo htmlspecialchars(format_event_time($event['event_time_start'] ?? '', $event['event_time_end'] ?? null) ?: '2PM to 5PM'); ?>" data-i18n="events.register_btn">Register</button>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>
<?php include __DIR__ . '/includes/event-register-modal.php'; ?>
<?php include __DIR__ . '/includes/footer.php'; ?>

