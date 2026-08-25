<?php
// Load configuration
if (file_exists(__DIR__ . '/config.php')) {
    require_once __DIR__ . '/config.php';
}
require_once __DIR__ . '/includes/content.php';
require_once __DIR__ . '/includes/settings.php';

if (!function_exists('get_absolute_url')) {
    function get_absolute_url($path = '') {
        $normalizedPath = ltrim((string) $path, '/');
        if (defined('BASE_URL')) {
            $baseUrl = (string) constant('BASE_URL');
            if ($baseUrl !== '') {
                return rtrim($baseUrl, '/') . '/' . $normalizedPath;
            }
        }
        return $normalizedPath;
    }
}

// Resolve an uploaded image path to a usable URL (absolute, root-relative, or stored filename).
if (!function_exists('home_img_url')) {
    function home_img_url($path, $fallback = 'images/campus2.webp') {
        $path = trim((string) $path);
        if ($path === '') return get_absolute_url($fallback);
        if (preg_match('#^https?://#i', $path) || $path[0] === '/') return $path;
        return get_absolute_url($path);
    }
}

// Process webinar/registration form submission (unchanged behaviour)
$formSubmitted = false;
$successMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['webinar_register'])) {
    require_once __DIR__ . '/includes/security.php';
    $applying_for = htmlspecialchars($_POST['applying_for']);
    $name = htmlspecialchars($_POST['name']);
    $nationality = htmlspecialchars($_POST['nationality']);
    $email = htmlspecialchars($_POST['email']);
    $whatsapp = htmlspecialchars($_POST['whatsapp']);

    $safeName = mail_safe($_POST['name'] ?? '');
    $replyEmail = valid_email($_POST['email'] ?? '');

    $to = "studyatxjmu@gmail.com";
    $subject = mail_safe($safeName . " -MBBS Registration September 2026 Intake");

    $message = "New Student Registration\n";
    $message .= "===========================\n\n";
    $message .= "Applying For: $applying_for\n";
    $message .= "Name: $name\n";
    $message .= "Nationality: $nationality\n";
    $message .= "Email: $email\n";
    $message .= "WhatsApp: $whatsapp\n\n";
    $message .= "Registration Date: " . date('Y-m-d H:i:s');
    $headers = "From: no-reply@xjmu.online\r\n";
    if ($replyEmail !== '') {
        $headers .= "Reply-To: $replyEmail\r\n";
    }
    $headers .= "X-Mailer: PHP/" . phpversion();

    if (mail($to, $subject, $message, $headers)) {
        $formSubmitted = true;
        $successMessage = "Thank you for registering! Your registration has been submitted successfully.";
    } else {
        $successMessage = "There was an error submitting your registration. Please try again.";
    }
}

// ---- SEO Meta (preserved — healthy titles/description kept intact) ----
$page_title       = "MBBS in China 2026 | Xinjiang Medical University";
$page_description = "Study MBBS in China at Xinjiang Medical University — WHO & MOE-listed, English-medium medical degree with affordable fees. Recognized by PMDC, NMC & WHO. Apply for the September 2026 intake.";
$page_keywords    = "MBBS in China, Xinjiang Medical University, XJMU, study medicine China, MBBS 2026, MBBS fees China, CSC scholarship, English medium MBBS, WHO recognized medical university China, MBBS for international students";
$page_url         = "https://xjmu.online/";
$page_image       = "https://xjmu.online/images/logo.png";

// Redesign assets (shared design system + homepage layer)
$body_class = 'ds-page home-hero-overlay';
$page_css   = ['css/design-system.css', 'css/home.css'];

// Live news (from Blogs). Falls back to core announcements if none published.
$latestBlogs = get_latest_blogs(4);

// Testimonials (admin-managed): split into recently-admitted students + alumni.
$students = get_testimonials('student');
$alumni   = get_testimonials('alumni');

// Homepage card collections (admin-managed).
$homePrograms = get_home_programs();
$homeWhy      = get_home_why();
$homeSteps    = get_home_steps();
$homeAccred   = get_home_accreditations();
$homeFacts    = get_home_facts();
$latestEvents = get_latest_events(3);
$galleryItems = get_gallery();

// Dynamic FAQs (admin-managed). Fall back to core MBBS FAQs if none set.
$homeFaqs = get_faqs();
if (!$homeFaqs) {
    $homeFaqs = [
        ['question' => 'Is Xinjiang Medical University recognized for MBBS?',
         'answer'   => 'Yes. XJMU is listed by China\'s Ministry of Education (MOE) and recognized by WHO (World Directory of Medical Schools), PMDC, NMC and other major medical councils, so graduates are eligible to sit licensing exams in their home countries.'],
        ['question' => 'What is the medium of instruction for the MBBS program?',
         'answer'   => 'The MBBS program is taught in English. HSK-4 Chinese proficiency is only required at the time of graduation to support clinical rotations.'],
        ['question' => 'How much does MBBS at XJMU cost?',
         'answer'   => 'The complete first-year package is approximately ¥49,950 (about US$7,000), covering tuition, hostel, visa, insurance and admission charges. From the second year, the total is about ¥35,950 per year.'],
        ['question' => 'How long is the MBBS program at XJMU?',
         'answer'   => 'The MBBS degree is 6 years, including a one-year clinical internship in affiliated teaching hospitals.'],
        ['question' => 'When is the intake and how do I apply?',
         'answer'   => 'The next intake is September 2026. You can apply online through our admissions portal or contact our counsellors on WhatsApp for free, step-by-step guidance.'],
    ];
}

include __DIR__ . '/includes/header.php';
?>

<!-- FAQ structured data (built from the same FAQs shown below) -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    <?php $faqJson = [];
    foreach ($homeFaqs as $f) {
        $q = json_encode((string)($f['question'] ?? ''), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $a = json_encode((string)($f['answer'] ?? ''), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $faqJson[] = '{"@type":"Question","name":' . $q . ',"acceptedAnswer":{"@type":"Answer","text":' . $a . '}}';
    }
    echo implode(",\n    ", $faqJson);
    ?>

  ]
}
</script>

<!-- Webinar Registration Modal (functionality preserved) -->
<div class="webinar-modal-overlay" id="webinarModal" style="<?php echo $formSubmitted ? 'display: none;' : ''; ?>">
    <div class="webinar-modal-content">
        <button class="webinar-close-btn" id="closeWebinarModal">&times;</button>
        <div class="webinar-header">
            <h3 data-i18n="home.modal_title">Apply for MOE Listed English Taught MBBS – September 2026 Intake</h3>
        </div>
        <div class="webinar-details"></div>
        <form class="webinar-form" method="POST" action="">
            <div class="form-group">
                <select class="form-control" name="applying_for" required>
                    <option value="" data-i18n="home.select_program">Select Program</option>
                    <option value="MBBS">MBBS</option>
                    <option value="Chinese Language">Chinese Language</option>
                    <option value="Master">Master</option>
                    <option value="PHD">PHD</option>
                    <option value="Others">Others</option>
                </select>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="name" placeholder="Name" data-i18n-placeholder="home.name_placeholder" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="nationality" placeholder="Nationality" data-i18n-placeholder="home.nationality_placeholder" required>
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email" data-i18n-placeholder="home.email_placeholder" required>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="whatsapp" placeholder="WhatsApp Number" data-i18n-placeholder="home.whatsapp_placeholder" required>
            </div>
            <button type="submit" name="webinar_register" class="btn btn-primary-custom w-100" data-i18n="home.submit">Submit</button>
        </form>
    </div>
</div>

<?php if ($formSubmitted): ?>
<div class="alert-overlay" id="successAlert">
    <div class="alert-content">
        <p><?php echo $successMessage; ?></p>
        <button class="alert-ok-btn" onclick="document.getElementById('successAlert').style.display='none';">OK</button>
    </div>
</div>
<?php endif; ?>

<style>
.webinar-modal-overlay{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.8);display:flex;justify-content:center;align-items:center;z-index:9999}
.webinar-modal-content{background:#fff;padding:20px;border-radius:8px;width:90%;max-width:400px;position:relative;box-shadow:0 4px 20px rgba(0,0,0,.2)}
.webinar-close-btn{position:absolute;top:10px;right:15px;background:none;border:none;font-size:24px;cursor:pointer;color:#666;width:30px;height:30px;line-height:30px}
.webinar-close-btn:hover{color:#333}
.webinar-header h3{color:#003366;font-size:18px;margin-bottom:15px;text-align:center;padding-right:20px}
.webinar-details{padding:0;margin-bottom:10px}
.webinar-form .form-group{margin-bottom:15px}
.webinar-form .form-control{width:100%;padding:8px 12px;border:1px solid #ddd;border-radius:4px;font-size:14px;box-sizing:border-box}
.webinar-form select.form-control{appearance:none;background-image:url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");background-repeat:no-repeat;background-position:right 8px center;background-size:1.5em 1.5em;padding-right:2.5em}
.webinar-form .btn{padding:10px}
.alert-overlay{position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.8);display:flex;justify-content:center;align-items:center;z-index:10000}
.alert-content{background:#fff;padding:20px;border-radius:8px;width:90%;max-width:400px;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,.2)}
.alert-content p{margin-bottom:20px;color:#333;font-size:16px}
.alert-ok-btn{background:#003366;color:#fff;border:none;padding:10px 30px;border-radius:4px;cursor:pointer;font-size:16px}
.alert-ok-btn:hover{background:#002244}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const webinarModal = document.getElementById('webinarModal');
    const closeWebinarModal = document.getElementById('closeWebinarModal');
    <?php if (!$formSubmitted): ?>
    setTimeout(() => { if (webinarModal) webinarModal.style.display = 'flex'; }, 1200);
    <?php endif; ?>
    if (closeWebinarModal) closeWebinarModal.addEventListener('click', () => webinarModal.style.display = 'none');
    if (webinarModal) webinarModal.addEventListener('click', (e) => { if (e.target === webinarModal) webinarModal.style.display = 'none'; });
    document.querySelectorAll('[data-open-apply]').forEach(function (el) {
        el.addEventListener('click', function (e) { e.preventDefault(); if (webinarModal) webinarModal.style.display = 'flex'; });
    });
});
</script>

<!-- Cross-promo popup mount point -->
<div id="popup-container"></div>

<!-- ============================ HERO (full-screen slider, nav overlaid) ============================ -->
<section class="home-hero home-hero--full" id="apply">
    <div id="heroCarousel" class="carousel slide carousel-fade home-hero__carousel" data-bs-ride="carousel" data-bs-interval="6000">
        <div class="carousel-inner">
            <div class="carousel-item active"><div class="home-hero__bg" style="background-image:url('<?= get_absolute_url('images/campus3.webp') ?>')"></div></div>
            <div class="carousel-item"><div class="home-hero__bg" data-bg="<?= get_absolute_url('images/campus13.webp') ?>"></div></div>
            <div class="carousel-item"><div class="home-hero__bg" data-bg="<?= get_absolute_url('images/campus12.webp') ?>"></div></div>
            <div class="carousel-item"><div class="home-hero__bg" data-bg="<?= get_absolute_url('images/campus5.webp') ?>"></div></div>
        </div>
        <div class="carousel-indicators home-hero__pager">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1">1</button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1" aria-label="Slide 2">2</button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2" aria-label="Slide 3">3</button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3" aria-label="Slide 4">4</button>
            <button type="button" class="home-hero__arrow" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="Next slide">&rarr;</button>
        </div>
    </div>
    <div class="home-hero__scrim"></div>
    <div class="container home-hero__content">
        <div class="home-hero__inner">
            <span class="ds-eyebrow home-hero__eyebrow"><?= setting_e('home_hero_eyebrow', 'September 2026 Intake · Applications Open') ?></span>
            <h1><?= setting_e('hero_headline', 'Study MBBS in China at Xinjiang Medical University') ?></h1>
            <p class="home-hero__lead">
                <?= setting_e('hero_subtext', 'A WHO & MOE-listed, English-medium medical degree with world-class clinical training and affordable fees — trusted by international students from 45+ countries.') ?>
            </p>
            <div class="home-hero__trust">
                <span class="ds-pill"><?= setting_e('home_trust1', 'WHO / WDOMS Listed') ?></span>
                <span class="ds-pill"><?= setting_e('home_trust2', 'MOE Approved') ?></span>
                <span class="ds-pill"><?= setting_e('home_trust3', 'PMDC & NMC Recognized') ?></span>
                <span class="ds-pill"><?= setting_e('home_trust4', 'English Medium') ?></span>
            </div>
        </div>
    </div>
</section>

<!-- ===================== NEWS (featured + list) ===================== -->
<section class="ds-section" id="updates">
    <div class="container">
        <div class="home-secthead">
            <div>
                <span class="ds-eyebrow"><?= setting_e('home_news_eyebrow', 'News & Updates') ?></span>
                <h2 class="ds-title"><?= setting_e('home_news_title', 'Latest news & announcements') ?></h2>
            </div>
            <a href="<?= get_absolute_url('blogs') ?>" class="ds-btn ds-btn--primary" data-i18n="home.view_all_news">Read More</a>
        </div>
        <?php $newsFeat = $latestBlogs[0] ?? null; $newsRest = array_slice($latestBlogs ?: [], 1, 3); ?>
        <?php if ($newsFeat): $ts = strtotime($newsFeat['published_at'] ?: $newsFeat['created_at']); ?>
        <div class="home-news2">
            <a class="home-news2__feature" href="<?= get_absolute_url('blog-post?slug=' . urlencode($newsFeat['slug'])) ?>">
                <span class="home-news2__img" style="background-image:url('<?= htmlspecialchars(home_img_url($newsFeat['featured_image'])) ?>')"></span>
                <span class="home-news2__fbody">
                    <span class="home-news2__date"><?= $ts ? date('F j, Y', $ts) : '' ?></span>
                    <span class="home-news2__ftitle"><?= htmlspecialchars($newsFeat['title']) ?></span>
                    <span class="home-news2__fexc"><?= htmlspecialchars(mb_strimwidth((string)$newsFeat['excerpt'], 0, 170, '…')) ?></span>
                </span>
            </a>
            <div class="home-news2__list">
                <?php foreach ($newsRest as $b): $t2 = strtotime($b['published_at'] ?: $b['created_at']); ?>
                <a class="home-news2__item" href="<?= get_absolute_url('blog-post?slug=' . urlencode($b['slug'])) ?>">
                    <span class="home-news2__idate"><?= $t2 ? date('F j, Y', $t2) : '' ?></span>
                    <span class="home-news2__ititle"><?= htmlspecialchars($b['title']) ?></span>
                </a>
                <?php endforeach; ?>
                <?php if (!$newsRest): ?><a class="home-news2__item" href="<?= get_absolute_url('blogs') ?>"><span class="home-news2__ititle">Read all news &amp; announcements →</span></a><?php endif; ?>
            </div>
        </div>
        <?php else: ?>
            <p class="text-muted">No news yet. <a href="<?= get_absolute_url('blogs') ?>">Visit the blog →</a></p>
        <?php endif; ?>
    </div>
</section>

<!-- ===================== TAGLINE BAND ===================== -->
<section class="home-band" style="background-image:url('<?= get_absolute_url('images/campus10.webp') ?>')">
    <span class="home-band__scrim"></span>
    <div class="container"><h2 class="home-band__title"><?= setting_e('home_about_title', 'A leading medical university in China since 1956') ?></h2></div>
</section>

<!-- ===================== ACCREDITATION STRIP ===================== -->
<section class="home-accred">
    <div class="container">
        <div class="row g-3">
            <?php foreach ($homeAccred as $a): ?>
            <div class="col-md-3 col-6"><div class="home-accred__item"><div><h4><?= htmlspecialchars($a['title']) ?></h4><p><?= htmlspecialchars($a['description']) ?></p></div></div></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===================== EVENTS ===================== -->
<section class="ds-section ds-section--soft" id="events">
    <div class="container">
        <div class="home-secthead">
            <div><span class="ds-eyebrow">Events</span><h2 class="ds-title">Upcoming events</h2></div>
            <a href="<?= get_absolute_url('events') ?>" class="ds-btn ds-btn--outline">Learn More</a>
        </div>
        <div class="home-events">
            <div class="home-events__list">
                <?php if ($latestEvents): foreach ($latestEvents as $ev): $ed = strtotime($ev['event_date']); ?>
                <a class="home-events__row" href="<?= get_absolute_url('event?slug=' . urlencode($ev['slug'])) ?>">
                    <span class="home-events__date"><?= $ed ? date('M j, Y', $ed) : '' ?></span>
                    <span class="home-events__body">
                        <span class="home-events__title"><?= htmlspecialchars($ev['title']) ?></span>
                        <?php if (!empty($ev['venue'])): ?><span class="home-events__venue"><i class="fas fa-location-dot"></i> <?= htmlspecialchars($ev['venue']) ?></span><?php endif; ?>
                    </span>
                </a>
                <?php endforeach; else: ?>
                    <p class="text-muted">No upcoming events right now. <a href="<?= get_absolute_url('events') ?>">See all events →</a></p>
                <?php endif; ?>
            </div>
            <div class="home-events__media">
                <?php $evImg = ($latestEvents[0]['featured_image'] ?? '') ?: 'images/campus9.webp'; ?>
                <img src="<?= htmlspecialchars(home_img_url($evImg, 'images/campus9.webp')) ?>" alt="XJMU events" loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- ===================== MULTIMEDIA (gallery, dark) ===================== -->
<?php $media = array_slice($galleryItems ?: [], 0, 3); if ($media): ?>
<section class="home-media">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow" style="color:#ffd0d2">Gallery</span><h2 class="ds-title" style="color:#fff">Campus highlights</h2></div>
        <div class="row g-4">
            <?php foreach ($media as $g): ?>
            <div class="col-md-4"><a class="home-media__card" href="<?= get_absolute_url('gallery') ?>">
                <img src="<?= htmlspecialchars(home_img_url($g['image_path'])) ?>" alt="<?= htmlspecialchars($g['title'] ?: 'Campus') ?>" loading="lazy">
                <span class="home-media__play"><i class="fas fa-play"></i></span>
                <?php if (!empty($g['title'])): ?><span class="home-media__cap"><?= htmlspecialchars($g['title']) ?></span><?php endif; ?>
            </a></div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4"><a href="<?= get_absolute_url('gallery') ?>" class="ds-btn ds-btn--primary">View Gallery</a></div>
    </div>
</section>
<?php endif; ?>

<!-- ===================== STUDY — Admissions ===================== -->
<section class="ds-section" id="study">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">Study</span><h2 class="ds-title">Educating future doctors with world-class medical training</h2></div>
        <div class="row g-5 align-items-center">
            <div class="col-lg-6"><img src="<?= get_absolute_url('images/class1.webp') ?>" class="home-about__img" alt="XJMU admissions" loading="lazy"></div>
            <div class="col-lg-6">
                <span class="ds-eyebrow">Admissions</span>
                <h3 class="ds-title" style="font-size:1.7rem"><?= setting_e('home_help_title', 'Need assistance with your application?') ?></h3>
                <p class="ds-lead"><?= setting_e('home_help_lead', 'Our admissions team is available to guide you at every step — from choosing a program to arriving on campus.') ?></p>
                <ul class="home-about__list">
                    <li><?= setting_e('home_help_point1', 'Personalized counseling') ?></li>
                    <li><?= setting_e('home_help_point2', 'Scholarship guidance') ?></li>
                    <li><?= setting_e('home_help_point3', 'Visa and travel support') ?></li>
                </ul>
                <a href="<?= get_absolute_url('login') ?>" class="ds-btn ds-btn--primary">Apply Now</a>
                <a href="<?= get_absolute_url('admissions') ?>" class="ds-btn ds-btn--outline">Admission Details</a>
            </div>
        </div>
    </div>
</section>

<!-- ===================== STAT STRIP ===================== -->
<section class="home-statstrip">
    <div class="container">
        <div class="row text-center g-0">
            <div class="col-6 col-md-3"><div class="ds-stat"><div class="ds-stat__num"><span data-count="<?= (int) get_setting('home_stat1_num','65') ?>">0</span>+</div><div class="ds-stat__label"><?= setting_e('home_stat1_label','Years of Excellence') ?></div></div></div>
            <div class="col-6 col-md-3"><div class="ds-stat"><div class="ds-stat__num"><span data-count="<?= (int) get_setting('home_stat2_num','2000') ?>">0</span>+</div><div class="ds-stat__label"><?= setting_e('home_stat2_label','International Students') ?></div></div></div>
            <div class="col-6 col-md-3"><div class="ds-stat"><div class="ds-stat__num"><span data-count="<?= (int) get_setting('home_stat3_num','45') ?>">0</span>+</div><div class="ds-stat__label"><?= setting_e('home_stat3_label','Countries Represented') ?></div></div></div>
            <div class="col-6 col-md-3"><div class="ds-stat"><div class="ds-stat__num"><span data-count="<?= (int) get_setting('home_stat4_num','500') ?>">0</span>+</div><div class="ds-stat__label"><?= setting_e('home_stat4_label','Expert Faculty') ?></div></div></div>
        </div>
    </div>
</section>

<!-- ===================== ABOUT (text first, then full-width banner) ===================== -->
<section class="ds-section about-section" id="about-xjmu">
    <div class="container">
        <div class="about-intro">
            <span class="ds-eyebrow"><?= setting_e('home_about_eyebrow', 'About XJMU') ?></span>
            <h2 class="ds-title"><?= setting_e('home_about_title', 'A leading medical university in China since 1956') ?></h2>
            <p class="ds-lead"><?= setting_e('home_about_lead', 'Xinjiang Medical University (XJMU) in Urumqi is the largest provincial-ministerial joint medical university in Xinjiang, spanning 26 colleges with a strong clinical and research network.') ?></p>
            <ul class="home-about__list">
                <li><?= setting_e('home_about_point1', '8 directly-affiliated hospitals, 18 teaching & 95 internship hospitals for hands-on training.') ?></li>
                <li><?= setting_e('home_about_point2', 'ESI-ranked: Clinical Medicine in the top 2.07‰ worldwide.') ?></li>
                <li><?= setting_e('home_about_point3', 'International students from 41+ countries since 1990.') ?></li>
            </ul>
            <a href="<?= get_absolute_url('about') ?>" class="ds-btn ds-btn--navy"><?= setting_e('home_about_btn', 'Learn More About XJMU') ?></a>
        </div>
    </div>
    <?php
        $bannerLink = get_setting('home_about_banner_link', '#mbbs-xjmu');
        $bannerHref = (strpos($bannerLink, '#') === 0 || preg_match('#^https?://#', $bannerLink)) ? $bannerLink : get_absolute_url($bannerLink);
        $bannerImg  = get_setting('home_about_banner_image', 'images/about.jpeg');
    ?>
    <a href="<?= htmlspecialchars($bannerHref) ?>" class="about-banner" aria-label="<?= setting_e('home_about_banner_label', 'Explore the MBBS Program') ?>">
        <img src="<?= htmlspecialchars(home_img_url($bannerImg, 'images/about.jpeg')) ?>" alt="Xinjiang Medical University campus" loading="lazy">
        <span class="about-banner__overlay"></span>
        <span class="about-banner__inner">
            <span class="container">
                <span class="about-banner__label"><?= setting_e('home_about_banner_label', 'Explore the MBBS Program') ?> <i class="fas fa-arrow-right"></i></span>
            </span>
        </span>
    </a>
</section>

<!-- ===================== PROGRAMS ===================== -->
<section class="ds-section ds-section--soft" id="programs">
    <div class="container">
        <div class="ds-section-head">
            <span class="ds-eyebrow"><?= setting_e('home_programs_eyebrow', 'Programs') ?></span>
            <h2 class="ds-title"><?= setting_e('home_programs_title', 'Medical programs for international students') ?></h2>
            <p class="ds-lead"><?= setting_e('home_programs_lead', 'English-medium degrees across medicine, dentistry and allied health at Xinjiang Medical University.') ?></p>
        </div>
        <div class="row g-4">
            <?php foreach ($homePrograms as $p): $purl = !empty($p['url']) ? $p['url'] : get_absolute_url('programs'); ?>
            <div class="col-lg-4 col-sm-6"><a href="<?= htmlspecialchars($purl) ?>" class="home-prog-card"><h3><?= htmlspecialchars($p['title']) ?></h3><p><?= htmlspecialchars($p['description']) ?></p><span class="more">Explore →</span></a></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===================== MBBS PROGRAM (section 1 of 2) ===================== -->
<section class="ds-section" id="mbbs-xjmu">
    <div class="container">
        <div class="ds-section-head ds-section-head--left" style="max-width:760px">
            <span class="ds-eyebrow"><?= setting_e('home_mbbs_eyebrow', 'MBBS Program') ?></span>
            <h2 class="ds-title"><?= setting_e('home_mbbs_title', 'MBBS in China at Xinjiang Medical University') ?></h2>
            <p class="ds-lead"><?= setting_e('home_mbbs_lead', "The MBBS (Clinical Medicine) program is taught in English and designed for international students, combining a strong pre-clinical foundation with extensive clinical rotations in XJMU's affiliated hospitals.") ?></p>
        </div>
        <ul class="home-facts">
            <?php foreach ($homeFacts as $f): ?>
            <li><strong><?= htmlspecialchars($f['label']) ?>:</strong> <?= htmlspecialchars($f['value']) ?></li>
            <?php endforeach; ?>
        </ul>
        <div class="row g-4 mt-1">
            <div class="col-md-6"><div class="ds-card"><h3>Clinical Excellence</h3><p>Rotations across 8 affiliated hospitals give real patient exposure from early years.</p></div></div>
            <div class="col-md-6"><div class="ds-card"><h3>Globally Recognized</h3><p>Listed by WHO (WDOMS) and recognized by PMDC, NMC and other councils for licensing.</p></div></div>
        </div>
        <a href="<?= get_absolute_url('programs') ?>" class="ds-btn ds-btn--outline mt-4">Explore All Programs</a>
    </div>
</section>

<!-- ===================== MBBS FEES (section 2 of 2) ===================== -->
<section class="ds-section ds-section--soft" id="mbbs-fees">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="ds-eyebrow"><?= setting_e('home_fees_eyebrow', 'Tuition') ?></span>
                <h2 class="ds-title"><?= setting_e('home_fees_title', 'MBBS Fee Structure') ?></h2>
                <p class="ds-lead"><?= setting_e('home_fees_note', 'First-year package includes tuition, hostel, visa, insurance, medical, registration & admission charges and JW202 visa letter.') ?></p>
                <a href="<?= get_absolute_url('fees') ?>" class="ds-btn ds-btn--primary mt-2">See full fee breakdown</a>
            </div>
            <div class="col-lg-6">
                <div class="home-fee-card">
                    <div class="home-fee-card__head">
                        <h3><?= setting_e('home_fees_title', 'MBBS Fee Structure') ?></h3>
                        <span class="tag"><?= setting_e('home_fees_tag', 'MOE-listed · English medium') ?></span>
                    </div>
                    <table class="home-fee-table">
                        <tbody>
                            <tr><th>1st Year (complete package)</th><td>¥49,950</td></tr>
                            <tr><th>Tuition (2nd year onward)</th><td>¥30,000/yr</td></tr>
                            <tr><th>Accommodation</th><td>¥4,500/yr</td></tr>
                            <tr><th>Insurance</th><td>¥300/yr</td></tr>
                            <tr><th>Residence permit</th><td>¥800/yr</td></tr>
                            <tr class="total"><th>Total (2nd year onward)</th><td>¥35,950/yr</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== WHY CHOOSE ===================== -->
<section class="ds-section ds-section--soft" id="why-xjmu">
    <div class="container">
        <div class="ds-section-head">
            <span class="ds-eyebrow"><?= setting_e('home_why_eyebrow', 'Why XJMU') ?></span>
            <h2 class="ds-title"><?= setting_e('home_why_title', 'Why international students choose XJMU') ?></h2>
            <p class="ds-lead"><?= setting_e('home_why_lead', 'The advantages that make Xinjiang Medical University a trusted destination for medical education.') ?></p>
        </div>
        <div class="row g-4">
            <?php foreach ($homeWhy as $w): ?>
            <div class="col-md-4 col-sm-6"><div class="ds-card"><h3><?= htmlspecialchars($w['title']) ?></h3><p><?= htmlspecialchars($w['description']) ?></p></div></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===================== HOW TO APPLY (section 1 of 2) ===================== -->
<section class="ds-section" id="how-to-apply">
    <div class="container">
        <div class="ds-section-head">
            <span class="ds-eyebrow"><?= setting_e('home_steps_eyebrow', 'Admissions') ?></span>
            <h2 class="ds-title"><?= setting_e('home_steps_title', 'How to apply in 5 simple steps') ?></h2>
            <p class="ds-lead"><?= setting_e('home_steps_lead', 'Our counsellors guide you through every stage — from choosing a program to arriving on campus.') ?></p>
        </div>
        <div class="row g-4">
            <?php foreach ($homeSteps as $i => $s): ?>
            <div class="col-lg-4 col-md-6">
                <div class="home-step"><div class="home-step__num"><?= $i + 1 ?></div><div><h4><?= htmlspecialchars($s['title']) ?></h4><p><?= htmlspecialchars($s['description']) ?></p></div></div>
            </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-4">
            <a href="<?= get_absolute_url('login') ?>" class="ds-btn ds-btn--primary ds-btn--lg">
                <span data-i18n="common.start_application">Start Your Application</span>
            </a>
        </div>
    </div>
</section>

<!-- ===================== RESEARCH BAND ===================== -->
<section class="home-research" style="background-image:url('<?= get_absolute_url('images/campus13.webp') ?>')">
    <span class="home-research__scrim"></span>
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2">Research</span>
        <h2 class="ds-title" style="color:#fff">Transforming medical research into real-world care</h2>
        <p class="home-research__lead">ESI-ranked Clinical Medicine (top 2.07‰), 8 directly-affiliated hospitals and modern research centres driving healthcare innovation across the region.</p>
        <a href="<?= get_absolute_url('about') ?>" class="ds-btn ds-btn--primary">Explore More</a>
    </div>
</section>

<!-- ===================== TESTIMONIALS (Students & Alumni, admin-managed) ===================== -->
<?php if ($students || $alumni): ?>
<section class="ds-section" id="testimonials">
    <div class="container">
        <div class="ds-section-head">
            <span class="ds-eyebrow"><?= setting_e('home_testi_eyebrow', 'Testimonials') ?></span>
            <h2 class="ds-title"><?= setting_e('home_testi_title', 'Our students & distinguished alumni') ?></h2>
            <p class="ds-lead"><?= setting_e('home_testi_lead', 'Hear from recently admitted students and graduates who built their careers at XJMU.') ?></p>
        </div>

        <?php if ($students): ?>
        <h3 class="ds-eyebrow mb-3" data-i18n="home.recently_admitted">Recently Admitted Students</h3>
        <div class="row g-4 mb-5">
            <?php foreach ($students as $t): $meta = trim(($t['role'] ?? '') . (!empty($t['location']) ? ' · ' . $t['location'] : ''), ' ·'); ?>
            <div class="col-lg-4 col-md-6"><div class="home-tcard">
                <p class="home-tcard__quote"><?= htmlspecialchars($t['quote']) ?></p>
                <div class="home-tcard__person"><img src="<?= htmlspecialchars(home_img_url($t['photo'], 'images/avatar.svg')) ?>" alt="<?= htmlspecialchars($t['name']) ?>" class="home-tcard__avatar" loading="lazy"><div><div class="home-tcard__name"><?= htmlspecialchars($t['name']) ?></div><div class="home-tcard__meta"><?= htmlspecialchars($meta) ?></div></div></div>
            </div></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($alumni): ?>
        <h3 class="ds-eyebrow mb-3" data-i18n="home.distinguished_alumni">Distinguished Alumni</h3>
        <div class="row g-4">
            <?php foreach ($alumni as $t): $meta = trim(($t['role'] ?? '') . (!empty($t['location']) ? ' · ' . $t['location'] : ''), ' ·'); ?>
            <div class="col-lg-4 col-md-6"><div class="home-tcard">
                <p class="home-tcard__quote"><?= htmlspecialchars($t['quote']) ?></p>
                <div class="home-tcard__person"><img src="<?= htmlspecialchars(home_img_url($t['photo'], 'images/avatar.svg')) ?>" alt="<?= htmlspecialchars($t['name']) ?>" class="home-tcard__avatar" loading="lazy"><div><div class="home-tcard__name"><?= htmlspecialchars($t['name']) ?></div><div class="home-tcard__meta"><?= htmlspecialchars($meta) ?></div></div></div>
            </div></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
<?php endif; ?>

<!-- ===================== CAMPUS (gallery grid) ===================== -->
<?php $campus = array_slice($galleryItems ?: [], 0, 8); if ($campus): ?>
<section class="ds-section ds-section--soft" id="campus">
    <div class="container">
        <div class="home-secthead">
            <div><span class="ds-eyebrow">Campus</span><h2 class="ds-title">A glimpse of campus life</h2></div>
            <a href="<?= get_absolute_url('gallery') ?>" class="ds-btn ds-btn--outline">More Photos</a>
        </div>
        <div class="home-campus-grid">
            <?php foreach ($campus as $g): ?>
            <a href="<?= get_absolute_url('gallery') ?>" class="home-campus-cell"><img src="<?= htmlspecialchars(home_img_url($g['image_path'])) ?>" alt="<?= htmlspecialchars($g['title'] ?: 'Campus') ?>" loading="lazy"></a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===================== FAQ (dynamic + schema) ===================== -->
<section class="ds-section ds-section--soft" id="faq">
    <div class="container">
        <div class="ds-section-head">
            <span class="ds-eyebrow"><?= setting_e('home_faq_eyebrow', 'FAQ') ?></span>
            <h2 class="ds-title"><?= setting_e('home_faq_title', 'Frequently asked questions') ?></h2>
            <p class="ds-lead"><?= setting_e('home_faq_lead', 'Answers to the most common questions about studying MBBS at XJMU.') ?></p>
        </div>
        <div class="home-faq-wrap">
            <?php foreach ($homeFaqs as $f): ?>
            <details class="ds-faq">
                <summary><?= htmlspecialchars($f['question'] ?? '') ?></summary>
                <div class="ds-faq__body"><?= nl2br(htmlspecialchars($f['answer'] ?? '')) ?></div>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===================== CTA BAND ===================== -->
<section class="ds-section">
    <div class="container">
        <div class="ds-cta-band">
            <h2><?= setting_e('home_cta_title', 'Ready to start your MBBS journey in China?') ?></h2>
            <p><?= setting_e('home_cta_lead', "Join thousands of international students at Xinjiang Medical University. Talk to our admissions team today — it's free.") ?></p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="<?= get_absolute_url('login') ?>" class="ds-btn ds-btn--lg" style="background:#fff;color:var(--ds-red)!important">
                    <span><?= setting_e('home_cta_btn', 'Apply for 2026') ?></span>
                </a>
                <a href="https://wa.me/+8617590820634" target="_blank" rel="noopener noreferrer" class="ds-btn ds-btn--ghost-light ds-btn--lg">
                    <i class="fab fa-whatsapp"></i> <span data-i18n="home.cta_whatsapp">Chat on WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Page Specific JS -->
<?php echo '<script src="' . get_absolute_url('js/home.js') . '"></script>'; ?>

<?php
$footer_path = __DIR__ . '/includes/footer.php';
if (file_exists($footer_path)) {
    include $footer_path;
} else {
    die('Error: Footer file not found. Please check file paths.');
}
?>
