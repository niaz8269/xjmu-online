<?php
session_start();

// Set page metadata
$page_title = "Academic Blog | Xinjiang Medical University";
$page_description = "Explore insights, research, and stories from our faculty and students. Discover the latest in medical education, research, student life, and more.";
$page_keywords = "XJMU blog, medical education blog, student experiences, medical research, academic articles, Xinjiang Medical University news";
$page_url   = "https://xjmu.online/blogs";
$page_image = "https://xjmu.online/images/logo.png";

$body_class = 'ds-page';
$page_css   = ['css/design-system.css', 'css/blogs.css'];

// Include necessary files
require_once 'db.php';
require_once 'config.php';

// Pagination settings
$posts_per_page = 9;
$current_page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($current_page - 1) * $posts_per_page;

// Filter parameters
$search_query    = isset($_GET['search']) ? trim($_GET['search']) : '';
$category_filter = 0; // reserved for future category filtering

// Build SQL query
$where_conditions = ["b.status = 'published'"];
$params = [];

if (!empty($search_query)) {
    $where_conditions[] = "(b.title LIKE ? OR b.excerpt LIKE ? OR b.content LIKE ?)";
    $search_param = "%{$search_query}%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
}

$where_clause = implode(' AND ', $where_conditions);

// Get total count for pagination
$count_sql = "SELECT COUNT(*) FROM blogs b WHERE {$where_clause}";
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute($params);
$total_posts = $count_stmt->fetchColumn();
$total_pages = ceil($total_posts / $posts_per_page);

// Get featured blogs
$featured_sql = "
    SELECT b.*
    FROM blogs b
    WHERE b.status = 'published' AND b.is_featured = 1
    ORDER BY b.published_at DESC
    LIMIT 3
";
$featured_stmt = $pdo->query($featured_sql);
$featured_blogs = $featured_stmt->fetchAll(PDO::FETCH_ASSOC);

// Get latest blogs
$blogs_sql = "
    SELECT b.*
    FROM blogs b
    WHERE {$where_clause}
    ORDER BY b.published_at DESC
    LIMIT {$posts_per_page} OFFSET {$offset}
";
$blogs_stmt = $pdo->prepare($blogs_sql);
$blogs_stmt->execute($params);
$blogs = $blogs_stmt->fetchAll(PDO::FETCH_ASSOC);

// Helper function to format date
function format_date($date) {
    return date('F j, Y', strtotime($date));
}

// Helper function to get blog image or placeholder
function get_blog_image($image_path) {
    if (!empty($image_path) && file_exists($image_path)) {
        return get_absolute_url($image_path);
    }
    // Return a placeholder gradient based on hash
    return 'data:image/svg+xml,' . urlencode('<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300"><defs><linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%"><stop offset="0%" style="stop-color:rgb(102,126,234);stop-opacity:1" /><stop offset="100%" style="stop-color:rgb(118,75,162);stop-opacity:1" /></linearGradient></defs><rect width="400" height="300" fill="url(#grad)"/></svg>');
}

// Helper function to create excerpt
function create_excerpt($content, $length = 150) {
    $text = strip_tags($content);
    if (strlen($text) > $length) {
        return substr($text, 0, $length) . '...';
    }
    return $text;
}
?>
<?php include 'includes/header.php'; ?>

    <div class="blog-page">
        <!-- Hero Section -->
        <section class="blog-hero">
            <div class="container">
                <span class="ds-eyebrow" style="color:#ffd0d2">Insights &amp; Stories</span>
                <h1 data-i18n="blogs.hero_title">XJMU Blog</h1>
                <p data-i18n="blogs.hero_desc">Welcome to Xinjiang Medical University's official blog. Stay updated with the latest news, announcements, student life, campus events, and everything you need to know about studying MBBS in China.</p>
            </div>
        </section>

        <div class="container">
            <nav class="blog-crumb" aria-label="breadcrumb">
                <a href="<?= get_absolute_url('index') ?>">Home</a> &rsaquo; <span>Blogs</span>
            </nav>
        </div>

        <!-- Search Bar -->
        <section class="blog-container" style="padding-top: 30px;">
            <form method="GET" action="blogs" class="blog-filter-bar">
                <div class="search-box">
                    <input type="text" name="search" placeholder="Search blogs..." value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" data-i18n="blogs.search_btn">Search</button>
                </div>
            </form>
        </section>

        <?php if (!empty($search_query)): ?>
            <div class="blog-container">
                <p class="blog-search-note">
                    Searching for: <strong>"<?php echo htmlspecialchars($search_query); ?>"</strong>
                    <a href="blogs" style="margin-left: 15px;" target="_blank" rel="noopener noreferrer">Clear search</a>
                </p>
            </div>
        <?php endif; ?>

        <?php if (!empty($featured_blogs) && empty($search_query) && $category_filter == 0 && $current_page == 1): ?>
        <!-- Featured Blogs Section -->
        <section class="blog-section featured-section">
            <div class="blog-container">
                <div class="blog-section-header">
                    <h2 data-i18n="blogs.featured_heading">Featured Blogs</h2>
                </div>
                <div class="featured-grid">
                    <?php foreach ($featured_blogs as $blog): ?>
                        <article class="blog-card">
                            <div class="blog-card-image">
                                <img src="<?php echo get_blog_image($blog['featured_image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" loading="lazy">
                                <span class="blog-card-badge" data-i18n="blogs.featured_badge">Featured</span>
                            </div>
                            <div class="blog-card-content">
                                <h3 class="blog-card-title">
                                    <a href="blog-post?slug=<?php echo urlencode($blog['slug']); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php echo htmlspecialchars($blog['title']); ?>
                                    </a>
                                </h3>
                                <p class="blog-card-excerpt"><?php echo htmlspecialchars($blog['excerpt']); ?></p>
                                <div class="blog-card-footer">
                                    <span class="blog-card-date"><?php echo format_date($blog['published_at']); ?></span>
                                    <a href="blog-post?slug=<?php echo urlencode($blog['slug']); ?>" class="blog-card-link" target="_blank" rel="noopener noreferrer">Read more</a>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Latest Blogs Section -->
        <section class="blog-section">
            <div class="blog-container">
                <div class="blog-section-header">
                    <h2><?php echo (!empty($search_query) || $category_filter > 0) ? 'Search Results' : 'Latest Blogs'; ?></h2>
                </div>
                
                <?php if (empty($blogs)): ?>
                    <div class="no-results">
                        <h3 data-i18n="blogs.no_blogs">No blogs found</h3>
                        <p data-i18n="blogs.no_blogs_desc">Try adjusting your search or filter criteria</p>
                    </div>
                <?php else: ?>
                    <div class="blog-grid">
                        <?php foreach ($blogs as $blog): ?>
                            <article class="blog-card">
                                <div class="blog-card-image">
                                    <img src="<?php echo get_blog_image($blog['featured_image']); ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" loading="lazy">
                                </div>
                                <div class="blog-card-content">
                                    <h3 class="blog-card-title">
                                        <a href="blog-post?slug=<?php echo urlencode($blog['slug']); ?>" target="_blank" rel="noopener noreferrer">
                                            <?php echo htmlspecialchars($blog['title']); ?>
                                        </a>
                                    </h3>
                                    <p class="blog-card-excerpt"><?php echo htmlspecialchars($blog['excerpt']); ?></p>
                                    <div class="blog-card-footer">
                                        <span class="blog-card-date"><?php echo format_date($blog['published_at']); ?></span>
                                        <a href="blog-post?slug=<?php echo urlencode($blog['slug']); ?>" class="blog-card-link" target="_blank" rel="noopener noreferrer">Read more</a>
                                    </div>
                                </div>
                            </article>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                        <div class="blog-pagination">
                            <?php if ($current_page > 1): ?>
                                <a href="?page=<?php echo $current_page - 1; ?><?php echo $search_query ? '&search=' . urlencode($search_query) : ''; ?>" class="pagination-btn" target="_blank" rel="noopener noreferrer">← Previous</a>
                            <?php endif; ?>

                            <?php 
                            $start_page = max(1, $current_page - 2);
                            $end_page = min($total_pages, $current_page + 2);
                            
                            for ($i = $start_page; $i <= $end_page; $i++): 
                            ?>
                                <a href="?page=<?php echo $i; ?><?php echo $search_query ? '&search=' . urlencode($search_query) : ''; ?>" target="_blank" rel="noopener noreferrer"
                                   class="pagination-btn <?php echo $i == $current_page ? 'active' : ''; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($current_page < $total_pages): ?>
                                <a href="?page=<?php echo $current_page + 1; ?><?php echo $search_query ? '&search=' . urlencode($search_query) : ''; ?>" class="pagination-btn" target="_blank" rel="noopener noreferrer">Next →</a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>

    </div>

    <!-- Include footer -->
    <?php include 'includes/footer.php'; ?>

