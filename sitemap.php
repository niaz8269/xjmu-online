<?php
require_once __DIR__ . '/db.php';

header('Content-Type: application/xml; charset=UTF-8');
// Allow crawlers to index the sitemap (remove previous noindex header)

$base  = 'https://xjmu.online';
$today = date('Y-m-d');

$static = [
    // Core pages
    ['loc' => '/',               'lastmod' => $today, 'changefreq' => 'daily',   'priority' => '1.0'],
    ['loc' => '/mbbs',           'lastmod' => $today, 'changefreq' => 'weekly',  'priority' => '1.0'],
    ['loc' => '/programs',       'lastmod' => $today, 'changefreq' => 'weekly',  'priority' => '0.9'],
    ['loc' => '/admissions',     'lastmod' => $today, 'changefreq' => 'weekly',  'priority' => '0.9'],
    ['loc' => '/fees',           'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.9'],
    ['loc' => '/adm-HtApply',   'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.8'],
    ['loc' => '/about',          'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.8'],
    ['loc' => '/contact',        'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.8'],
    ['loc' => '/scholarships',   'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.8'],
    // Content pages
    ['loc' => '/blogs',          'lastmod' => $today, 'changefreq' => 'daily',   'priority' => '0.8'],
    ['loc' => '/events',         'lastmod' => $today, 'changefreq' => 'weekly',  'priority' => '0.8'],
    ['loc' => '/gallery',        'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.7'],
    ['loc' => '/downloads',      'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.7'],
    ['loc' => '/support',        'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.7'],
    ['loc' => '/be-our-partner', 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.6'],
    // Legal
    ['loc' => '/privacy',        'lastmod' => $today, 'changefreq' => 'yearly',  'priority' => '0.4'],
    ['loc' => '/terms',          'lastmod' => $today, 'changefreq' => 'yearly',  'priority' => '0.4'],
];

// Published blog posts
$blogs = [];
try {
    $stmt = $pdo->query("SELECT slug, published_at FROM blogs WHERE status = 'published' ORDER BY published_at DESC");
    $blogs = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

// Published events
$events = [];
try {
    $stmt = $pdo->query("SELECT slug, event_date FROM events WHERE status = 'published' ORDER BY event_date DESC");
    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
        https://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

<?php foreach ($static as $p): ?>
  <url>
    <loc><?php echo $base . $p['loc']; ?></loc>
    <lastmod><?php echo $p['lastmod']; ?></lastmod>
    <changefreq><?php echo $p['changefreq']; ?></changefreq>
    <priority><?php echo $p['priority']; ?></priority>
  </url>
<?php endforeach; ?>

<?php foreach ($blogs as $b): $lastmod = !empty($b['published_at']) ? date('Y-m-d', strtotime($b['published_at'])) : $today; ?>
  <url>
    <loc><?php echo $base . '/blog-post?slug=' . urlencode($b['slug']); ?></loc>
    <lastmod><?php echo $lastmod; ?></lastmod>
    <changefreq>monthly</changefreq>
    <priority>0.7</priority>
  </url>
<?php endforeach; ?>

<?php foreach ($events as $e): $lastmod = !empty($e['event_date']) ? date('Y-m-d', strtotime($e['event_date'])) : $today; ?>
  <url>
    <loc><?php echo $base . '/event?slug=' . urlencode($e['slug']); ?></loc>
    <lastmod><?php echo $lastmod; ?></lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.6</priority>
  </url>
<?php endforeach; ?>

</urlset>
