<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

$event = null;
if ($slug !== '') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM events WHERE slug = ? AND status = 'published' LIMIT 1");
        $stmt->execute([$slug]);
        $event = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        error_log('event.php query error: ' . $e->getMessage());
    }
}

// 404 if not found
if (!$event) {
    http_response_code(404);
    $page_title       = "Event Not Found | XJMU";
    $page_description = "The event you requested could not be found.";
    $page_keywords    = "XJMU events";
    $page_url         = "https://xjmu.online/events";
    $page_image       = "https://xjmu.online/images/logo.png";
    $body_class       = 'ds-page';
    $page_css         = ['css/design-system.css', 'css/event.css'];
    include __DIR__ . '/includes/header.php';
    echo '<div class="container ev-404">';
    echo '<h1>404</h1>';
    echo '<p>Event not found or has been removed.</p>';
    echo '<a href="' . get_absolute_url('events') . '" class="ds-btn ds-btn--primary">&larr; Back to Events</a>';
    echo '</div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

// Page meta
$page_title       = htmlspecialchars($event['title']) . " | XJMU Events";
$page_description = !empty($event['excerpt'])
                    ? htmlspecialchars(substr(strip_tags($event['excerpt']), 0, 155))
                    : "Campus event at Xinjiang Medical University.";
$page_keywords    = "XJMU events, Xinjiang Medical University, campus event";
$page_url         = get_absolute_url('event?slug=' . urlencode($event['slug']));
$page_image       = (!empty($event['featured_image']) && file_exists(__DIR__ . '/' . $event['featured_image']))
                    ? get_absolute_url($event['featured_image'])
                    : "https://xjmu.online/images/logo.png";
$body_class       = 'ds-page';
$page_css         = ['css/design-system.css', 'css/event.css'];

include __DIR__ . '/includes/header.php';

function format_event_date_post($start, $end = null) {
    $s = date('F j, Y', strtotime($start));
    if ($end && $end !== $start) {
        $s .= ' – ' . date('F j, Y', strtotime($end));
    }
    return $s;
}

function format_event_time_post($start, $end = null) {
    if (empty($start)) return '';
    $s = date('g:i A', strtotime('1970-01-01 ' . $start));
    if (!empty($end) && $end !== $start) {
        $s .= ' – ' . date('g:i A', strtotime('1970-01-01 ' . $end));
    }
    return $s;
}
?>

<div class="event-post-page">

    <!-- Hero -->
    <section class="ev-hero">
        <div class="container">
            <span class="ds-eyebrow" style="color:#ffd0d2">Event</span>
            <h1><?php echo htmlspecialchars($event['title']); ?></h1>
            <?php if (!empty($event['event_date'])): ?>
            <p class="ev-meta">
                <i class="fas fa-calendar-alt"></i>
                <?php echo format_event_date_post($event['event_date'], $event['event_end_date'] ?? null); ?>
                <?php
                $time_str = format_event_time_post($event['event_time_start'] ?? '', $event['event_time_end'] ?? null);
                if ($time_str): ?>
                    &nbsp;&middot;&nbsp;<i class="fas fa-clock"></i> <?php echo htmlspecialchars($time_str); ?>
                <?php endif; ?>
            </p>
            <?php endif; ?>
            <?php if (!empty($event['venue'])): ?>
            <p class="ev-meta"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($event['venue']); ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Breadcrumb -->
    <div class="container">
        <nav class="ev-crumb" aria-label="breadcrumb">
            <a href="<?php echo get_absolute_url('index'); ?>">Home</a>
            <span aria-hidden="true">&rsaquo;</span>
            <a href="<?php echo get_absolute_url('events'); ?>">Events</a>
            <span aria-hidden="true">&rsaquo;</span>
            <span><?php echo htmlspecialchars($event['title']); ?></span>
        </nav>
    </div>

    <div class="ev-wrap">
        <div class="container">
            <article class="ev-article">

                <!-- Featured Image -->
                <?php if (!empty($event['featured_image']) && file_exists(__DIR__ . '/' . $event['featured_image'])): ?>
                <div class="ev-featured">
                    <img src="<?php echo get_absolute_url($event['featured_image']); ?>"
                         alt="<?php echo htmlspecialchars($event['title']); ?>" loading="lazy">
                </div>
                <?php endif; ?>

                <?php if (!empty($event['excerpt'])): ?>
                    <p class="ev-excerpt"><?php echo htmlspecialchars($event['excerpt']); ?></p>
                <?php endif; ?>

                <hr class="ev-divider">

                <!-- Event Content (admin-authored HTML) -->
                <?php if (!empty($event['content'])): ?>
                <div class="event-body event-content">
                    <?php echo $event['content']; ?>
                </div>
                <?php endif; ?>

                <!-- Back link -->
                <div class="ev-back">
                    <a href="<?php echo get_absolute_url('events'); ?>" class="ds-btn ds-btn--outline">
                        <i class="fas fa-arrow-left me-2"></i>Back to All Events
                    </a>
                </div>

            </article>
        </div>
    </div>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
