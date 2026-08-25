<?php
// Load configuration
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}
require_once __DIR__ . '/includes/content.php';
require_once __DIR__ . '/includes/settings.php'; // for video_embed()
$aboutSections = get_page_sections('about'); // admin-managed extra sections

$page_title = "About XJMU | History, Leadership & Campus Life";
$page_description = "Discover Xinjiang Medical University's history, leadership, and vision. A leading Chinese medical institution serving thousands of international students pursuing excellence.";
$page_keywords = "Xinjiang Medical University, about XJMU, medical university China, university history, medical education China, university leadership, campus life China, international students China";
$page_url = "https://xjmu.online/about";
$page_image = "https://xjmu.online/images/logo.png";

$body_class = 'ds-page';
$page_css = ['css/design-system.css', 'css/about.css'];
?>
<?php
$header_path = __DIR__ . '/includes/header.php';
if (file_exists($header_path)) {
    include $header_path;
} else {
    die('Error: Header file not found. Please check file paths.');
}
?>

<!-- ===================== HERO ===================== -->
<section class="about-hero">
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2">About Us</span>
        <h1 data-i18n="about.page_title">About Xinjiang Medical University</h1>
        <p>A leading medical university in China since 1956 — combining teaching, research and clinical practice to train globally-minded healthcare professionals.</p>
    </div>
</section>

<div class="container">
    <nav class="about-crumb" aria-label="breadcrumb">
        <a href="<?= get_absolute_url('index') ?>">Home</a> &rsaquo; <span>About</span>
    </nav>
</div>

<main class="ds-section" style="padding-top:2rem">
    <div class="container">
        <?php if (!empty($aboutSections)): ?>
            <?php foreach ($aboutSections as $sec): ?>
                <section class="mb-4" id="<?= htmlspecialchars($sec['section_key'], ENT_QUOTES) ?>">
                    <?php if (!empty($sec['heading'])): ?><h2 class="ds-title"><?= htmlspecialchars($sec['heading']) ?></h2><?php endif; ?>
                    <div class="page-section-body"><?= $sec['body'] /* admin-authored HTML */ ?></div>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="accordion about-accordion" id="aboutAccordion">
            <!-- History & Overview -->
            <div class="accordion-item" id="history">
                <h2 class="accordion-header" id="headingHistory">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseHistory" aria-expanded="true" aria-controls="collapseHistory"><span data-i18n="about.history_btn">History &amp; Overview</span></button>
                </h2>
                <div id="collapseHistory" class="accordion-collapse collapse show" aria-labelledby="headingHistory" data-bs-parent="#aboutAccordion">
                    <div class="accordion-body">
                        <div class="row g-4 align-items-start">
                            <div class="col-lg-7 about-section-text">
                                <p>
                                    Founded with the mission to advance medical science and healthcare, Xinjiang Medical University
                                    has grown into a comprehensive institution combining teaching, research and clinical practice.
                                </p>
                                <ul>
                                    <li>Long-standing history of training medical professionals for the region and beyond.</li>
                                    <li>Progressive development of faculties, departments and research institutes.</li>
                                    <li>Integration of traditional and modern medical education approaches.</li>
                                </ul>
                            </div>
                            <div class="col-lg-5 about-media">
                                <div class="ratio ratio-16x9 mb-3">
                                    <!-- Replace src with real campus history video URL -->
                                    <iframe src="<?= htmlspecialchars(video_embed('video_about_history')) ?>" title="XJMU History Video" allowfullscreen></iframe>
                                </div>
                                <div class="row g-2 about-media-images">
                                    <div class="col-6">
                                        <img src="images/campus3.webp" alt="Historic campus building" class="img-fluid" loading="lazy">
                                    </div>
                                    <div class="col-6">
                                        <img src="images/campus1.webp" alt="Students at XJMU" class="img-fluid" loading="lazy">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leadership -->
            <div class="accordion-item" id="leadership">
                <h2 class="accordion-header" id="headingLeadership">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLeadership" aria-expanded="false" aria-controls="collapseLeadership"><span data-i18n="about.leadership_btn">Leadership</span></button>
                </h2>
                <div id="collapseLeadership" class="accordion-collapse collapse" aria-labelledby="headingLeadership" data-bs-parent="#aboutAccordion">
                    <div class="accordion-body">
                        <div class="row g-4 align-items-start">
                            <div class="col-lg-7 about-section-text">
                                <p>
                                    XJMU is guided by an experienced leadership team committed to academic quality, innovation
                                    and student success.
                                </p>
                                <ul>
                                    <li>Strategic vision focused on internationalization and research excellence.</li>
                                    <li>Collaborative governance involving faculty, staff and students.</li>
                                    <li>Strong emphasis on ethical practice and community service.</li>
                                </ul>
                            </div>
                            <div class="col-lg-5 about-media">
                                <div class="ratio ratio-16x9 mb-3">
                                    <!-- Replace src with real leadership message video URL -->
                                    <iframe src="<?= htmlspecialchars(video_embed('video_about_leadership')) ?>" title="XJMU Leadership Video" allowfullscreen></iframe>
                                </div>
                                <div class="row g-2 about-media-images">
                                    <div class="col-6">
                                        <img src="images/activity2.webp" alt="University leadership" class="img-fluid" loading="lazy">
                                    </div>
                                    <div class="col-6">
                                        <img src="images/activity1.webp" alt="Leadership meeting" class="img-fluid" loading="lazy">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Vision & Mission -->
            <div class="accordion-item" id="vision">
                <h2 class="accordion-header" id="headingVision">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVision" aria-expanded="false" aria-controls="collapseVision"><span data-i18n="about.vision_btn">Vision &amp; Mission</span></button>
                </h2>
                <div id="collapseVision" class="accordion-collapse collapse" aria-labelledby="headingVision" data-bs-parent="#aboutAccordion">
                    <div class="accordion-body">
                        <div class="row g-4 align-items-start">
                            <div class="col-lg-7 about-section-text">
                                <p>
                                    The university's vision is to be a leading center for medical education and research, contributing
                                    to regional and global health.
                                </p>
                                <ul>
                                    <li>Train competent, compassionate and globally-minded healthcare professionals.</li>
                                    <li>Advance medical knowledge through research and innovation.</li>
                                    <li>Serve the community through high-quality clinical services.</li>
                                </ul>
                            </div>
                            <div class="col-lg-5 about-media">
                                <div class="ratio ratio-16x9 mb-3">
                                    <!-- Replace src with real vision & mission video URL -->
                                    <iframe src="<?= htmlspecialchars(video_embed('video_about_vision')) ?>" title="XJMU Vision &amp; Mission Video" allowfullscreen></iframe>
                                </div>
                                <div class="row g-2 about-media-images">
                                    <div class="col-6">
                                        <img src="images/class2.webp" alt="Students in lecture" class="img-fluid" loading="lazy">
                                    </div>
                                    <div class="col-6">
                                        <img src="images/class3.webp" alt="Research facilities" class="img-fluid" loading="lazy">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Campus Life -->
            <div class="accordion-item" id="campus">
                <h2 class="accordion-header" id="headingCampus">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCampus" aria-expanded="false" aria-controls="collapseCampus"><span data-i18n="about.campus_btn">Campus Life</span></button>
                </h2>
                <div id="collapseCampus" class="accordion-collapse collapse" aria-labelledby="headingCampus" data-bs-parent="#aboutAccordion">
                    <div class="accordion-body">
                        <div class="row g-4 align-items-start">
                            <div class="col-lg-7 about-section-text">
                                <p>
                                    XJMU offers a vibrant campus life with modern facilities, student organizations and cultural
                                    activities that support personal and professional growth.
                                </p>
                                <ul>
                                    <li>Comfortable student housing and on-campus amenities.</li>
                                    <li>Clubs, societies and sports to enrich student experience.</li>
                                    <li>Support services for academic, health and wellbeing needs.</li>
                                </ul>
                            </div>
                            <div class="col-lg-5 about-media">
                                <div class="ratio ratio-16x9 mb-3">
                                    <!-- Replace src with real campus life video URL -->
                                    <iframe src="<?= htmlspecialchars(video_embed('video_about_campus')) ?>" title="XJMU Campus Life Video" allowfullscreen></iframe>
                                </div>
                                <div class="row g-2 about-media-images">
                                    <div class="col-6">
                                        <img src="images/campus9.webp" alt="Campus grounds" class="img-fluid" loading="lazy">
                                    </div>
                                    <div class="col-6">
                                        <img src="images/sports2.webp" alt="Students on campus" class="img-fluid" loading="lazy">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- International Relations -->
            <div class="accordion-item" id="international">
                <h2 class="accordion-header" id="headingInternational">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseInternational" aria-expanded="false" aria-controls="collapseInternational">
                        International Relations
                    </button>
                </h2>
                <div id="collapseInternational" class="accordion-collapse collapse" aria-labelledby="headingInternational" data-bs-parent="#aboutAccordion">
                    <div class="accordion-body">
                        <div class="row g-4 align-items-start">
                            <div class="col-lg-7 about-section-text">
                                <p>
                                    Xinjiang Medical University maintains active partnerships with institutions worldwide, welcoming
                                    international students and fostering global academic exchange.
                                </p>
                                <ul>
                                    <li>Collaborative programs and joint research initiatives.</li>
                                    <li>Student and faculty exchange opportunities.</li>
                                    <li>Dedicated support for international student integration.</li>
                                </ul>
                            </div>
                            <div class="col-lg-5 about-media">
                                <div class="ratio ratio-16x9 mb-3">
                                    <!-- Replace src with real international relations video URL -->
                                    <iframe src="<?= htmlspecialchars(video_embed('video_about_international')) ?>" title="XJMU International Relations Video" allowfullscreen></iframe>
                                </div>
                                <div class="row g-2 about-media-images">
                                    <div class="col-6">
                                        <img src="images/class1.webp" alt="International students" class="img-fluid" loading="lazy">
                                    </div>
                                    <div class="col-6">
                                        <img src="images/practice1.webp" alt="Global collaboration" class="img-fluid" loading="lazy">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- ===================== CTA BAND ===================== -->
<section class="ds-section ds-section--soft">
    <div class="container">
        <div class="ds-cta-band">
            <h2>Join Xinjiang Medical University</h2>
            <p>Explore our programs and start your application for the September 2026 intake.</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="<?= get_absolute_url('programs') ?>" class="ds-btn ds-btn--lg" style="background:#fff;color:var(--ds-red)!important">View Programs</a>
                <a href="<?= get_absolute_url('login') ?>" class="ds-btn ds-btn--ghost-light ds-btn--lg">Apply Now</a>
            </div>
        </div>
    </div>
</section>

<!-- Page Specific JS -->
<?php
if (function_exists('get_absolute_url')) {
    echo '<script src="' . get_absolute_url('js/about.js') . '"></script>';
} else {
    echo '<script src="js/about.js"></script>';
}
?>
<?php
$footer_path = __DIR__ . '/includes/footer.php';
if (file_exists($footer_path)) {
    include $footer_path;
} else {
    die('Error: Footer file not found. Please check file paths.');
}
?>
