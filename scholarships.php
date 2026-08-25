<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/content.php';
require_once __DIR__ . '/includes/settings.php';
$scholarships = get_scholarships(); // admin-managed; empty -> empty-state row below

$page_title       = "XJMU Scholarships | CSC & Merit Awards 2026";
$page_description = "Discover XJMU scholarships — CSC government awards, merit scholarships, and provincial grants for international MBBS students in China. Apply for 2026.";
$page_keywords    = "XJMU scholarships, CSC scholarship China, MBBS scholarship China, funded medical programs China, Xinjiang Medical University scholarship, government scholarship China, international student scholarship, merit scholarship MBBS";
$page_url         = "https://xjmu.online/scholarships";
$page_image       = "https://xjmu.online/images/logo.png";

$body_class = 'ds-page';
$page_css   = ['css/design-system.css', 'css/scholarships.css'];

include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="schol-hero">
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2">Funding</span>
        <h1 data-i18n="scholarships.page_title">Scholarships at Xinjiang Medical University</h1>
        <p data-i18n="scholarships.lead_desc">Explore various scholarship opportunities to support your education.</p>
    </div>
</section>

<div class="container">
    <nav class="schol-crumb" aria-label="breadcrumb">
        <a href="<?= get_absolute_url('index') ?>">Home</a> &rsaquo; <span>Scholarships</span>
    </nav>
</div>

<!-- ===================== OVERVIEW ===================== -->
<section class="ds-section" style="padding-top:2rem;padding-bottom:1.5rem">
    <div class="container">
        <span class="ds-eyebrow" data-i18n="scholarships.available_heading">Available Scholarships</span>
        <h2 class="ds-title">Funding for international students</h2>
        <p class="ds-lead" data-i18n="scholarships.available_desc">Xinjiang Medical University offers a variety of scholarships to assist students in achieving their educational goals.</p>
    </div>
</section>

<!-- ===================== SCHOLARSHIP TABLE ===================== -->
<section class="ds-section" style="padding-top:0">
    <div class="container">
        <h2 class="ds-title" style="font-size:1.5rem" data-i18n="scholarships.details_heading">Scholarship Details</h2>
        <div class="schol-table-wrap">
            <table class="schol-table">
                <thead>
                    <tr>
                        <th data-i18n="scholarships.col_name">Scholarship Name</th>
                        <th data-i18n="scholarships.col_eligibility">Eligibility Criteria</th>
                        <th data-i18n="scholarships.col_benefits">Benefits</th>
                        <th data-i18n="scholarships.col_deadline">Application Deadline</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($scholarships): ?>
                        <?php foreach ($scholarships as $s): ?>
                            <tr>
                                <td data-label="Scholarship Name"><?= htmlspecialchars($s['name']) ?></td>
                                <td data-label="Eligibility"><?= htmlspecialchars($s['eligibility'] ?: '—') ?></td>
                                <td data-label="Benefits"><?= htmlspecialchars($s['coverage'] ?: '—') ?></td>
                                <td data-label="Details"><?= htmlspecialchars($s['description'] ?: '—') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center text-muted py-3">No scholarships available at the moment.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- ===================== HOW TO APPLY ===================== -->
<section class="ds-section ds-section--soft">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-5">
                <span class="ds-eyebrow">Application</span>
                <h2 class="ds-title" data-i18n="scholarships.apply_heading">How to Apply</h2>
                <p class="ds-lead mb-4">Follow these steps to apply for a scholarship at XJMU.</p>
                <a href="<?= get_absolute_url('login') ?>" class="ds-btn ds-btn--primary ds-btn--lg">
                    <strong data-i18n="scholarships.apply_btn">APPLY NOW</strong>
                </a>
            </div>
            <div class="col-lg-7">
                <ol class="schol-steps">
                    <li data-i18n="scholarships.step1">Check the eligibility criteria for the scholarship you wish to apply for.</li>
                    <li data-i18n="scholarships.step2">Prepare the necessary documents, including transcripts, recommendation letters, and personal statements.</li>
                    <li data-i18n="scholarships.step3">Submit your application through the official university portal or relevant authority.</li>
                    <li data-i18n="scholarships.step4">Await the scholarship committee's decision, which will be communicated via email.</li>
                </ol>
            </div>
        </div>
    </div>
</section>

<!-- ===================== CONTACT ===================== -->
<section class="ds-section">
    <div class="container">
        <div class="schol-contact">
            <span class="ds-eyebrow" data-i18n="scholarships.contact_heading">Contact Information</span>
            <p data-i18n="scholarships.contact_desc">If you have any questions regarding scholarships, please feel free to contact the International Student Office at Xinjiang Medical University:</p>
            <ul>
                <li><strong>Email:</strong>   <?= setting_e('admissions_email', 'admissions@xjmu.online') ?></li>
                <li><strong>Phone:</strong>   <?= setting_e('contact_phone') ?></li>
                <li><strong>Address:</strong> <?= setting_e('office_address', 'Urumqi, Xinjiang, China') ?></li>
            </ul>
        </div>
    </div>
</section>

<?php include 'includes/chatbot.php'; ?>
<?php include 'includes/socials.php'; ?>
<?php include 'includes/footer.php'; ?>
