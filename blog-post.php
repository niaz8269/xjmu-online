<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/db.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

$blog = null;
if ($slug !== '') {
    try {
        $stmt = $pdo->prepare("SELECT * FROM blogs WHERE slug = ? AND status = 'published' LIMIT 1");
        $stmt->execute([$slug]);
        $blog = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        error_log('blog-post.php query error: ' . $e->getMessage());
    }
}

// 404 if not found
if (!$blog) {
    http_response_code(404);
    $page_title       = "Blog Post Not Found | XJMU";
    $page_description = "The blog post you requested could not be found.";
    $page_keywords    = "XJMU blog";
    $page_url         = "https://xjmu.online/blogs";
    $page_image       = "https://xjmu.online/images/logo.png";
    $body_class       = 'ds-page';
    $page_css         = ['css/design-system.css', 'css/blog-post.css'];
    include __DIR__ . '/includes/header.php';
    echo '<div class="container bp-404">';
    echo '<h1>404</h1>';
    echo '<p>Blog post not found or has been removed.</p>';
    echo '<a href="' . get_absolute_url('blogs') . '" class="ds-btn ds-btn--primary">&larr; Back to Blogs</a>';
    echo '</div>';
    include __DIR__ . '/includes/footer.php';
    exit;
}

// Increment view count (ignore failures)
try {
    $pdo->prepare("UPDATE blogs SET views = views + 1 WHERE id = ?")->execute([$blog['id']]);
} catch (\PDOException $e) {}

// Page meta
$page_title       = htmlspecialchars($blog['title']) . " | XJMU Blog";
$page_description = !empty($blog['meta_description']) ? htmlspecialchars($blog['meta_description']) : (htmlspecialchars(substr(strip_tags($blog['excerpt'] ?? $blog['content']), 0, 155)) . '...');
$page_keywords    = !empty($blog['meta_keywords']) ? htmlspecialchars($blog['meta_keywords']) : "XJMU blog, Xinjiang Medical University";
$page_url         = get_absolute_url('blog-post?slug=' . urlencode($blog['slug']));
$page_image       = (!empty($blog['featured_image']) && file_exists(__DIR__ . '/' . $blog['featured_image']))
                    ? get_absolute_url($blog['featured_image'])
                    : "https://xjmu.online/images/logo.png";
$body_class       = 'ds-page';
$page_css         = ['css/design-system.css', 'css/blog-post.css'];

include __DIR__ . '/includes/header.php';

$published_date = !empty($blog['published_at']) ? date('F j, Y', strtotime($blog['published_at'])) : '';
?>

<div class="blog-post-page">

    <!-- Hero -->
    <section class="bp-hero">
        <div class="container">
            <?php if ($blog['is_featured']): ?>
                <span class="ds-eyebrow" style="color:#ffd0d2">Featured</span>
            <?php else: ?>
                <span class="ds-eyebrow" style="color:#ffd0d2">Blog</span>
            <?php endif; ?>
            <h1><?php echo htmlspecialchars($blog['title']); ?></h1>
            <?php if ($published_date): ?>
                <p class="bp-meta"><i class="fas fa-calendar-alt"></i> Published <?php echo $published_date; ?></p>
            <?php endif; ?>
        </div>
    </section>

    <!-- Breadcrumb -->
    <div class="container">
        <nav class="bp-crumb" aria-label="breadcrumb">
            <a href="<?php echo get_absolute_url('index'); ?>">Home</a>
            <span aria-hidden="true">&rsaquo;</span>
            <a href="<?php echo get_absolute_url('blogs'); ?>">Blogs</a>
            <span aria-hidden="true">&rsaquo;</span>
            <span><?php echo htmlspecialchars($blog['title']); ?></span>
        </nav>
    </div>

    <div class="bp-wrap">
        <div class="container">
            <article class="bp-article">

                <!-- Featured Image -->
                <?php if (!empty($blog['featured_image']) && file_exists(__DIR__ . '/' . $blog['featured_image'])): ?>
                <div class="bp-featured">
                    <img src="<?php echo get_absolute_url($blog['featured_image']); ?>"
                         alt="<?php echo htmlspecialchars($blog['title']); ?>" loading="lazy">
                </div>
                <?php endif; ?>

                <?php if (!empty($blog['excerpt'])): ?>
                    <p class="bp-excerpt"><?php echo htmlspecialchars($blog['excerpt']); ?></p>
                <?php endif; ?>

                <hr class="bp-divider">

                <!-- Post Content (admin-authored HTML) -->
                <div class="post-body blog-content">
                    <?php echo $blog['content']; ?>
                </div>

                <!-- Back link -->
                <div class="bp-back">
                    <a href="<?php echo get_absolute_url('blogs'); ?>" class="ds-btn ds-btn--outline">
                        <i class="fas fa-arrow-left me-2"></i>Back to All Blogs
                    </a>
                </div>

            </article>
        </div>
    </div>

</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
