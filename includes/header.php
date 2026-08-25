<?php
// Load configuration for path resolution
if (file_exists(__DIR__ . '/../config.php')) {
    require_once __DIR__ . '/../config.php';
} else {
    // Fallback if config.php doesn't exist. Guard against redeclare fatals
    // (header.php + footer.php both define these). Audit #15.
    if (!function_exists('get_absolute_path')) {
        function get_absolute_path($path) { return ltrim($path, '/'); }
    }
    if (!function_exists('get_absolute_url')) {
        function get_absolute_url($path) {
            $forwardedProto = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '');
            $forwardedSsl = strtolower($_SERVER['HTTP_X_FORWARDED_SSL'] ?? '');
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $forwardedProto === 'https' || $forwardedSsl === 'on' ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'xjmu.online';
            return $protocol . '://' . $host . '/' . ltrim($path, '/');
        }
    }
}

// Load site settings so the top-header contacts/socials are admin-editable.
if (file_exists(__DIR__ . '/settings.php')) {
    require_once __DIR__ . '/settings.php';
}
if (!function_exists('get_setting')) {
    function get_setting($k, $d = '') { return $d; }
}
if (!function_exists('setting_e')) {
    function setting_e($k, $d = '') { return htmlspecialchars((string) get_setting($k, $d), ENT_QUOTES, 'UTF-8'); }
}

// Function to get CSS URL with cache busting
if (!function_exists('get_css_url')):
function get_css_url($css_file) {
    if (!function_exists('get_absolute_url')) {
        return $css_file;
    }
    $url = get_absolute_url($css_file);
    // Add version parameter based on file modification time to prevent caching
    $file_path = __DIR__ . '/../' . $css_file;
    if (file_exists($file_path)) {
        $version = filemtime($file_path);
        return $url . '?v=' . $version;
    }
    return $url;
}
endif;

// Function to get JS URL with cache busting
if (!function_exists('get_js_url')):
function get_js_url($js_file) {
    if (!function_exists('get_absolute_url')) {
        return $js_file;
    }
    $url = get_absolute_url($js_file);
    // Add version parameter based on file modification time to prevent caching
    $file_path = __DIR__ . '/../' . $js_file;
    if (file_exists($file_path)) {
        $version = filemtime($file_path);
        return $url . '?v=' . $version;
    }
    return $url;
}
endif;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- i18n: base URL for fetching /lang/*.json -->
    <meta name="xjmu-base" content="<?php echo rtrim(get_absolute_url(''), '/'); ?>">
    
    <!-- SEO Meta Tags -->
    <?php
    // Default SEO values if not set
    if (!isset($page_title)) $page_title = "Xinjiang Medical University | MBBS in China | Study Medicine in China";
    if (!isset($page_description)) $page_description = "Xinjiang Medical University offers world-class MBBS programs in China. Study medicine in China with English medium MBBS degree. Recognized by WHO, MCI, and medical councils worldwide. Apply now for 2025 intake!";
    if (!isset($page_keywords)) $page_keywords = "Xinjiang Medical University, MBBS in China, study medicine China, medical university China, MBBS China, study abroad China, medical education China, international medical programs, Chinese medical university, MBBS degree China";
    if (!isset($page_url)) {
        // Canonical = clean path only. Drop the query string so tracking params
        // (?utm_*, ?fbclid, ?page) can never spawn duplicate canonicals, and strip
        // any .php/.html extension. Pages that need a query (blog-post?slug=) set
        // $page_url explicitly and are unaffected.
        $path = strtok(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '/', '?');
        $path = preg_replace('/\.(php|html)$/', '', $path);
        $page_url = "https://xjmu.online" . $path;
    }
    if (!isset($page_image)) $page_image = "https://xjmu.online/images/logo.png";
    // Canonical / OG / Twitter URLs must ALWAYS be HTTPS — never emit an http://
    // URL even if a page built $page_url via a protocol-relative helper on an
    // http request (this is what triggered GSC's "not served over HTTPS").
    $page_url = preg_replace('#^http://#i', 'https://', $page_url);
    ?>
    
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($page_keywords); ?>">
    <meta name="author" content="Xinjiang Medical University">
    <?php if (!empty($page_noindex)): ?>
    <meta name="robots" content="noindex, nofollow">
    <?php else: ?>
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <?php endif; ?>
    <meta name="language" content="English">
    <meta name="revisit-after" content="7 days">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo htmlspecialchars($page_url); ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?php echo isset($page_og_type) ? htmlspecialchars($page_og_type) : 'website'; ?>">
    <meta property="og:url" content="<?php echo htmlspecialchars($page_url); ?>">
    <meta property="og:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta property="og:image" content="<?php echo htmlspecialchars($page_image); ?>">
    <meta property="og:site_name" content="Xinjiang Medical University">
    <meta property="og:locale" content="en_US">
    
    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="<?php echo htmlspecialchars($page_url); ?>">
    <meta name="twitter:title" content="<?php echo htmlspecialchars($page_title); ?>">
    <meta name="twitter:description" content="<?php echo htmlspecialchars($page_description); ?>">
    <meta name="twitter:image" content="<?php echo htmlspecialchars($page_image); ?>">

    <!-- Favicon -->
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/images/logo.png" type="image/png">
    <link rel="apple-touch-icon" href="/images/logo.png">

    <!-- Structured Data (JSON-LD) -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "EducationalOrganization",
        "name": "Xinjiang Medical University",
        "alternateName": "XJMU",
        "url": "https://xjmu.online",
        "logo": "https://xjmu.online/images/logo.png",
        "description": "Xinjiang Medical University is a premier medical institution in China offering MBBS and other medical programs to international students. Recognized by WHO, MCI, and medical councils worldwide.",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Urumqi",
            "addressRegion": "Xinjiang",
            "addressCountry": "CN"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+86-156-1750-0775",
            "contactType": "Admissions",
            "email": "studyatxjmu@gmail.com",
            "availableLanguage": ["English", "Chinese"]
        },
        "sameAs": [
            "https://www.facebook.com/studyatxjmu"
        ],
        "educationalCredentialAwarded": "Bachelor of Medicine, Bachelor of Surgery",
        "hasOfferCatalog": {
            "@type": "OfferCatalog",
            "name": "Medical Programs",
            "itemListElement": [
                {
                    "@type": "Offer",
                    "itemOffered": {
                        "@type": "Course",
                        "name": "MBBS",
                        "description": "Bachelor of Medicine, Bachelor of Surgery - English Medium",
                        "provider": {
                            "@type": "Organization",
                            "name": "Xinjiang Medical University"
                        }
                    }
                }
            ]
        }
    }
    </script>
    
    <!-- Additional Structured Data for University -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "CollegeOrUniversity",
        "name": "Xinjiang Medical University",
        "url": "https://xjmu.online",
        "logo": "https://xjmu.online/images/logo.png",
        "foundingDate": "1956",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Urumqi",
            "addressRegion": "Xinjiang",
            "addressCountry": "China"
        },
        "contactPoint": {
            "@type": "ContactPoint",
            "telephone": "+86-156-1750-0775",
            "contactType": "Admissions Office",
            "email": "studyatxjmu@gmail.com"
        }
    }
    </script>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome loaded non-render-blocking (icons paint just after first paint) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all';this.onload=null;">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>
    <link rel="stylesheet" href="<?php echo get_css_url('css/global.css'); ?>">
    
    <!-- Page-specific CSS -->
    <?php if (isset($page_css) && is_array($page_css)): ?>
        <?php foreach ($page_css as $css_file): ?>
            <link rel="stylesheet" href="<?php echo get_css_url($css_file); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Preconnect for Performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
    <!-- Fonts (moved out of the CSS @import chain for faster rendering) -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@600;700;800&display=swap">

    <!-- i18n engine (deferred — runs after DOM is parsed) -->
    <script src="<?php echo get_js_url('js/i18n.js'); ?>" defer></script>

    <!-- Language Switcher Styles -->
    <style>
    .xjmu-lang-switcher{display:flex;align-items:center;gap:4px;margin-left:12px}
    .xjmu-lang-btn{background:transparent;border:1px solid #ccc;color:#555;padding:3px 8px;font-size:11px;font-weight:700;cursor:pointer;border-radius:3px;transition:background .2s,color .2s,border-color .2s;line-height:1.5;letter-spacing:.4px}
    .xjmu-lang-btn:hover,.xjmu-lang-btn.active{background:#e72128;color:#fff;border-color:#e72128}
    .xjmu-lang-switcher-mobile{display:flex;gap:8px;padding:14px 20px;border-top:1px solid #eee;margin-top:8px}
    .xjmu-lang-btn-mobile{flex:1;background:#f4f4f4;border:1px solid #ddd;color:#333;padding:9px 4px;font-size:13px;font-weight:600;cursor:pointer;border-radius:4px;transition:background .2s,color .2s,border-color .2s;text-align:center}
    .xjmu-lang-btn-mobile:hover,.xjmu-lang-btn-mobile.active{background:#e72128;color:#fff;border-color:#e72128}
    </style>
</head>
<body<?php if (!empty($body_class)) { echo ' class="' . htmlspecialchars($body_class) . '"'; } ?>>
<!-- Shared Header for Xinjiang Medical University -->
<div class="top-header">
    <div class="container">
        <div class="top-header-content">
            <div class="top-header-contact" style="display: flex; justify-content: left; align-items:center;">
                <a class="welcome-text welcome" style="padding-top: 0px !important;" data-i18n="header.welcome">Welcome!</a>
                <a href="#" data-i18n="header.admissions_label"> International Students Admissions: </a>
            </div>
            <div class="top-header-contact contact-a" style="display: flex; justify-content: left; align-items:center;">
                <?php
                    $waCn      = get_setting('contact_phone2', '+86 175 9082 0634');
                    $waPk      = get_setting('contact_phone_pk', '+92 300 400 9526');
                    $topEmail  = get_setting('contact_email', 'studyatxjmu@gmail.com');
                ?>
                <?php if ($waCn): ?><a href="https://wa.me/<?= preg_replace('/\D/', '', $waCn) ?>" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-whatsapp whatsapp"></i> <?= setting_e('contact_phone2', '+86 175 9082 0634') ?> (CN) </a><?php endif; ?>
                <?php if ($waPk): ?><a href="https://wa.me/<?= preg_replace('/\D/', '', $waPk) ?>" target="_blank" rel="noopener noreferrer"> <?= setting_e('contact_phone_pk', '+92 300 400 9526') ?> (PK) </a><?php endif; ?>
                <?php if ($topEmail): ?><a href="mailto:<?= htmlspecialchars($topEmail, ENT_QUOTES, 'UTF-8') ?>"><i class="fas fa-envelope"></i> <?= htmlspecialchars($topEmail, ENT_QUOTES, 'UTF-8') ?></a><?php endif; ?>
            </div>
            <div class="social-icons">
                <?php if ($yt = get_setting('social_youtube')): ?><a href="<?= htmlspecialchars($yt, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a><?php endif; ?>
                <?php if ($fb = get_setting('social_facebook')): ?><a href="<?= htmlspecialchars($fb, ENT_QUOTES, 'UTF-8') ?>" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook"></i></a><?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Brand Bar -->
<div class="brand-bar">
    <div class="container">
        <div class="brand-content">
            <div class="logo">
                <a href="<?php echo get_absolute_url('index'); ?>"><img src="<?php echo get_absolute_url('images/logo2.png'); ?>" alt="Xinjiang Medical University Logo"></a>
            </div>
            <!-- Compact nav shown only in the scrolled (sticky) navbar; short labels -->
            <div class="nav-in-brand">
                <ul class="nav-links">
                    <li class="nav-item"><a class="nav-link" href="<?php echo get_absolute_url('index'); ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo get_absolute_url('about'); ?>">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo get_absolute_url('mbbs'); ?>">MBBS</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo get_absolute_url('programs'); ?>">Programs</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo get_absolute_url('admissions'); ?>">Admissions</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo get_absolute_url('fees'); ?>">Fees</a></li>
                    <li class="nav-item nav-dd">
                        <span class="nav-link nav-dd-toggle" tabindex="0" role="button" aria-haspopup="true" data-i18n="nav.media">Media</span>
                        <ul class="nav-dd-menu">
                            <li><a href="<?php echo get_absolute_url('gallery'); ?>" data-i18n="nav.gallery">Gallery</a></li>
                            <li><a href="<?php echo get_absolute_url('blogs'); ?>" data-i18n="nav.blogs">Blogs</a></li>
                            <li><a href="<?php echo get_absolute_url('events'); ?>" data-i18n="nav.events">Events</a></li>
                            <li><a href="<?php echo get_absolute_url('downloads'); ?>" data-i18n="nav.downloads">Downloads</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link" href="<?php echo get_absolute_url('contact'); ?>">Contact</a></li>
                </ul>
            </div>
            <div class="brand-actions">
                <a href="<?php echo get_absolute_url('submit-testimonial'); ?>" class="apply-now-btn d-none d-lg-block nav-hovertip" data-tip="Current students &amp; alumni are invited to share their XJMU experience."><span class="apply-full">Share Your Story</span><span class="apply-short">Share Story</span></a>
                <a href="<?php echo get_absolute_url('login'); ?>" class="apply-now-btn d-none d-lg-block nav-hovertip" data-tip="Apply online to study at XJMU" target="_blank" rel="noopener noreferrer"><span class="apply-full">Apply Now</span><span class="apply-short">Apply</span></a>

                <!-- Language Switcher (Desktop) — single button + dropdown -->
                <div class="dropdown xjmu-lang-dd d-none d-lg-block">
                    <button class="xjmu-lang-toggle" type="button" aria-haspopup="true" aria-label="Change language">
                        <i class="fas fa-globe"></i> <span id="langCurrent">EN</span> <i class="fas fa-chevron-down xjmu-lang-caret"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end xjmu-lang-menu">
                        <li><button class="dropdown-item" type="button" data-lang-btn="en">English</button></li>
                        <li><button class="dropdown-item" type="button" data-lang-btn="zh">中文</button></li>
                        <li><button class="dropdown-item" type="button" data-lang-btn="fr">Français</button></li>
                    </ul>
                </div>
                <script>
                (function(){
                    var codes={en:'EN',zh:'中',fr:'FR'};
                    var lbl=document.getElementById('langCurrent');
                    try{ var cur=localStorage.getItem('xjmu_lang')||'en'; if(lbl&&codes[cur]) lbl.textContent=codes[cur]; }catch(e){}
                    document.querySelectorAll('.xjmu-lang-dd [data-lang-btn]').forEach(function(b){
                        b.addEventListener('click',function(){ var c=this.getAttribute('data-lang-btn'); if(lbl&&codes[c]) lbl.textContent=codes[c]; });
                    });
                })();
                </script>

                <button class="mobile-menu-btn d-lg-none">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Desktop Navigation -->
<nav class="main-navbar navbar navbar-expand-lg d-none d-lg-block">
    <div class="container">
        <div class="navbar-collapse">
            <ul class="nav-links navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo get_absolute_url('index'); ?>" data-i18n="nav.home">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo get_absolute_url('about'); ?>" data-i18n="nav.about">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo get_absolute_url('mbbs'); ?>" data-i18n="nav.mbbs_program">MBBS Program</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo get_absolute_url('programs'); ?>" data-i18n="nav.all_programs">All Programs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo get_absolute_url('admissions'); ?>" data-i18n="nav.admissions">Admissions</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo get_absolute_url('fees'); ?>" data-i18n="nav.fees">Fees</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo get_absolute_url('gallery'); ?>" data-i18n="nav.gallery">Gallery</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo get_absolute_url('blogs'); ?>" data-i18n="nav.blogs">Blogs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo get_absolute_url('events'); ?>" data-i18n="nav.events">Events</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo get_absolute_url('downloads'); ?>" data-i18n="nav.downloads">Downloads</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo get_absolute_url('contact'); ?>" style="border: 0px !important;" data-i18n="nav.contact_us">Contact Us</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay"></div>

<!-- Mobile Menu Panel -->
<div class="mobile-menu-panel">
    <button class="mobile-menu-close">
        <i class="fas fa-times"></i>
    </button>
    
    <ul class="mobile-nav-links">
        <li>
            <a class="nav-link" href="<?php echo get_absolute_url('index'); ?>" target="_blank" rel="noopener noreferrer" data-i18n="nav.home">Home</a>
        </li>
        <li>
            <a class="nav-link" href="<?php echo get_absolute_url('about'); ?>" target="_blank" rel="noopener noreferrer" data-i18n="nav.about">About</a>
        </li>
        <li>
            <a class="nav-link" href="<?php echo get_absolute_url('index'); ?>#mbbs-xjmu" target="_blank" rel="noopener noreferrer" data-i18n="nav.mbbs_program">MBBS Program</a>
        </li>
        <li>
            <a class="nav-link" href="<?php echo get_absolute_url('programs'); ?>" target="_blank" rel="noopener noreferrer" data-i18n="nav.all_programs">All Programs</a>
        </li>
        <li>
            <a class="nav-link" href="<?php echo get_absolute_url('admissions'); ?>" target="_blank" rel="noopener noreferrer" data-i18n="nav.admissions">Admissions</a>
        </li>
        <li>
            <a class="nav-link" href="<?php echo get_absolute_url('fees'); ?>" target="_blank" rel="noopener noreferrer" data-i18n="nav.fees">Fees</a>
        </li>
        <li>
            <a class="nav-link" href="<?php echo get_absolute_url('gallery'); ?>" target="_blank" rel="noopener noreferrer" data-i18n="nav.gallery">Gallery</a>
        </li>
        <li>
            <a class="nav-link" href="<?php echo get_absolute_url('blogs'); ?>" target="_blank" rel="noopener noreferrer" data-i18n="nav.blogs">Blogs</a>
        </li>
        <li>
            <a class="nav-link" href="<?php echo get_absolute_url('events'); ?>" target="_blank" rel="noopener noreferrer" data-i18n="nav.events">Events</a>
        </li>
        <li>
            <a class="nav-link" href="<?php echo get_absolute_url('downloads'); ?>" target="_blank" rel="noopener noreferrer" data-i18n="nav.downloads">Downloads</a>
        </li>
        <li>
            <a class="nav-link" href="<?php echo get_absolute_url('contact'); ?>" target="_blank" rel="noopener noreferrer" data-i18n="nav.contact_us">Contact Us</a>
        </li>
    </ul>

    <a href="<?php echo get_absolute_url('login'); ?>" class="apply-now-btn mobile-apply-btn text-decoration-none" target="_blank" rel="noopener noreferrer" data-i18n="common.apply_now">Apply Now</a>

    <!-- Language Switcher (Mobile) -->
    <div class="xjmu-lang-switcher-mobile" role="group" aria-label="Language switcher">
        <button class="xjmu-lang-btn-mobile active" data-lang-btn="en">English</button>
        <button class="xjmu-lang-btn-mobile" data-lang-btn="zh">中文</button>
        <button class="xjmu-lang-btn-mobile" data-lang-btn="fr">Français</button>
    </div>
</div>

<!-- Vertical "Be Our Partner" tab, sticky to the left edge -->
<a id="partnerWidget" class="floating-partner-widget" href="<?php echo get_absolute_url('be-our-partner'); ?>" aria-label="Be Our Partner" data-i18n="header.be_our_partner">
    Be Our Partner
</a>
