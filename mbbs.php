<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/content.php';
require_once __DIR__ . '/includes/settings.php';

// Parse a multi-line admin setting into rows of columns split by "|".
if (!function_exists('mbbs_lines')) {
    function mbbs_lines($key, $default = '') {
        $val = get_setting($key, $default);
        $out = [];
        foreach (preg_split('/\r\n|\r|\n/', (string) $val) as $line) {
            $line = trim($line);
            if ($line === '') continue;
            $out[] = array_map('trim', explode('|', $line));
        }
        return $out;
    }
}

$mbbsFacts       = mbbs_lines('mbbs_facts_list');
$mbbsWhy         = mbbs_lines('mbbs_why_list');
$mbbsCurriculum  = get_mbbs_curriculum(); // admin-managed collection
$mbbsEligibility = mbbs_lines('mbbs_eligibility_list');
$mbbsFeeRows     = mbbs_lines('mbbs_fee_rows');
$mbbsRecognition = mbbs_lines('mbbs_recognition_list');
$mbbsApply       = mbbs_lines('mbbs_apply_list');
$mbbsFaqRows     = mbbs_lines('mbbs_faq_list');
$mbbsSections    = get_page_sections('mbbs'); // freeform admin-authored blocks

// FAQ (for both the visible list and FAQPage schema)
$mbbsFaqs = [];
foreach ($mbbsFaqRows as $r) {
    if (!empty($r[0]) && !empty($r[1])) $mbbsFaqs[] = ['q' => $r[0], 'a' => $r[1]];
}

$page_title       = "MBBS in China at Xinjiang Medical University | Fees, Duration & Admission 2026";
$page_description = "Complete guide to the MBBS program at Xinjiang Medical University — 6-year English-medium degree, WHO & MOE-listed, PMDC/NMC recognized. Curriculum, eligibility, fees (~¥49,950 first year) and how to apply for the September 2026 intake.";
$page_keywords    = "MBBS in China, MBBS at Xinjiang Medical University, XJMU MBBS, study MBBS China, English medium MBBS, MBBS fees China, MBBS duration, WHO recognized MBBS China, MBBS admission 2026";
$page_url         = "https://xjmu.online/mbbs";
$page_image       = "https://xjmu.online/images/logo.png";

$body_class = 'ds-page';
$page_css   = ['css/design-system.css', 'css/mbbs.css'];

include __DIR__ . '/includes/header.php';
?>

<?php if ($mbbsFaqs): ?>
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    <?php $j = [];
    foreach ($mbbsFaqs as $f) {
        $q = json_encode($f['q'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $a = json_encode($f['a'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $j[] = '{"@type":"Question","name":' . $q . ',"acceptedAnswer":{"@type":"Answer","text":' . $a . '}}';
    }
    echo implode(",\n    ", $j); ?>

  ]
}
</script>
<?php endif; ?>

<!-- ===================== HERO ===================== -->
<section class="mbbs-hero">
    <div class="mbbs-hero__bg" role="img" aria-label="Xinjiang Medical University campus"></div>
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2"><?= setting_e('mbbs_hero_eyebrow', 'MBBS Program') ?></span>
        <h1><?= setting_e('mbbs_hero_title', 'MBBS in China at Xinjiang Medical University') ?></h1>
        <p class="mbbs-hero__lead"><?= setting_e('mbbs_hero_subtitle', '') ?></p>
        <div class="mbbs-hero__cta">
            <a href="<?= get_absolute_url('login') ?>" class="ds-btn ds-btn--primary ds-btn--lg">Apply for 2026</a>
            <a href="<?= get_absolute_url('fees') ?>" class="ds-btn ds-btn--ghost-light ds-btn--lg">View Fees</a>
        </div>
        <div class="mbbs-hero__trust">
            <span class="ds-pill">WHO / WDOMS Listed</span>
            <span class="ds-pill">MOE Approved</span>
            <span class="ds-pill">PMDC &amp; NMC Recognized</span>
            <span class="ds-pill">English Medium</span>
        </div>
    </div>
</section>

<div class="container">
    <nav class="mbbs-crumb" aria-label="breadcrumb">
        <a href="<?= get_absolute_url('index') ?>">Home</a> &rsaquo; <span>MBBS Program</span>
    </nav>
</div>

<!-- ===================== OVERVIEW ===================== -->
<section class="ds-section" style="padding-top:2rem" id="overview">
    <div class="container">
        <div class="ds-section-head ds-section-head--left" style="max-width:820px">
            <span class="ds-eyebrow"><?= setting_e('mbbs_overview_eyebrow', 'Overview') ?></span>
            <h2 class="ds-title"><?= setting_e('mbbs_overview_title', 'About the MBBS program at XJMU') ?></h2>
        </div>
        <div style="max-width:820px">
            <?php if ($p = get_setting('mbbs_overview_p1', '')): ?><p class="ds-lead" style="margin-bottom:1rem"><?= htmlspecialchars($p) ?></p><?php endif; ?>
            <?php if ($p = get_setting('mbbs_overview_p2', '')): ?><p style="color:var(--ds-body); line-height:1.8; margin-bottom:1rem"><?= htmlspecialchars($p) ?></p><?php endif; ?>
            <?php if ($p = get_setting('mbbs_overview_p3', '')): ?><p style="color:var(--ds-body); line-height:1.8; margin:0"><?= htmlspecialchars($p) ?></p><?php endif; ?>
        </div>
    </div>
</section>

<!-- ===================== PROGRAM AT A GLANCE ===================== -->
<?php if ($mbbsFacts): ?>
<section class="ds-section ds-section--soft" id="at-a-glance">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">At a glance</span><h2 class="ds-title"><?= setting_e('mbbs_glance_title', 'MBBS program facts') ?></h2></div>
        <div class="mbbs-facts">
            <?php foreach ($mbbsFacts as $f): ?>
            <div class="mbbs-fact"><div class="mbbs-fact__label"><?= htmlspecialchars($f[0]) ?></div><div class="mbbs-fact__value"><?= htmlspecialchars($f[1] ?? '') ?></div></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===================== WHY ===================== -->
<?php if ($mbbsWhy): ?>
<section class="ds-section" id="why">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">Why XJMU</span><h2 class="ds-title"><?= setting_e('mbbs_why_title', 'Why study MBBS at Xinjiang Medical University') ?></h2></div>
        <div class="row g-4">
            <?php foreach ($mbbsWhy as $w): ?>
            <div class="col-md-4 col-sm-6"><div class="ds-card"><h3><?= htmlspecialchars($w[0]) ?></h3><p><?= htmlspecialchars($w[1] ?? '') ?></p></div></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===================== CURRICULUM ===================== -->
<?php if ($mbbsCurriculum): ?>
<section class="ds-section ds-section--soft" id="curriculum">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">Curriculum</span><h2 class="ds-title"><?= setting_e('mbbs_curriculum_title', '6-year MBBS structure') ?></h2></div>
        <div class="mbbs-years">
            <?php foreach ($mbbsCurriculum as $c): ?>
            <div class="mbbs-year"><span class="mbbs-year__tag"><?= htmlspecialchars($c['year_tag']) ?></span><div><h4><?= htmlspecialchars($c['title']) ?></h4><p><?= htmlspecialchars($c['description'] ?? '') ?></p></div></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===================== ELIGIBILITY ===================== -->
<?php if ($mbbsEligibility): ?>
<section class="ds-section" id="eligibility">
    <div class="container">
        <div class="ds-section-head ds-section-head--left" style="max-width:820px"><span class="ds-eyebrow">Eligibility</span><h2 class="ds-title"><?= setting_e('mbbs_eligibility_title', 'Admission requirements') ?></h2></div>
        <ul class="mbbs-reqs">
            <?php foreach ($mbbsEligibility as $e): ?>
            <li><i class="fas fa-check-circle"></i> <?= htmlspecialchars($e[0]) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>
<?php endif; ?>

<!-- ===================== FEES ===================== -->
<section class="ds-section ds-section--soft" id="fees">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="ds-eyebrow">Tuition &amp; Fees</span>
                <h2 class="ds-title"><?= setting_e('mbbs_fees_title', 'MBBS fee structure') ?></h2>
                <p class="ds-lead"><?= setting_e('mbbs_fees_note', '') ?></p>
                <a href="<?= get_absolute_url('fees') ?>" class="ds-btn ds-btn--primary mt-2">See full fee breakdown</a>
            </div>
            <div class="col-lg-6">
                <?php if ($mbbsFeeRows): ?>
                <div class="mbbs-fee-card">
                    <div class="mbbs-fee-card__head"><h3>Indicative fees (RMB ¥)</h3></div>
                    <table class="mbbs-fee-table">
                        <tbody>
                            <?php foreach ($mbbsFeeRows as $fr): $isTotal = (isset($fr[2]) && strtolower($fr[2]) === 'total'); ?>
                            <tr<?= $isTotal ? ' class="total"' : '' ?>><th><?= htmlspecialchars($fr[0]) ?></th><td><?= htmlspecialchars($fr[1] ?? '') ?></td></tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ===================== RECOGNITION ===================== -->
<?php if ($mbbsRecognition): ?>
<section class="ds-section" id="recognition">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">Accreditation</span><h2 class="ds-title"><?= setting_e('mbbs_recognition_title', 'Recognition & accreditation') ?></h2></div>
        <div class="mbbs-recog">
            <?php foreach ($mbbsRecognition as $r): ?>
            <div class="mbbs-recog__item"><h4><?= htmlspecialchars($r[0]) ?></h4><p><?= htmlspecialchars($r[1] ?? '') ?></p></div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===================== ADMISSION PROCESS ===================== -->
<?php if ($mbbsApply): ?>
<section class="ds-section ds-section--soft" id="how-to-apply">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">Admissions</span><h2 class="ds-title"><?= setting_e('mbbs_apply_title', 'How to apply for MBBS') ?></h2></div>
        <div class="mbbs-steps">
            <?php foreach ($mbbsApply as $s): ?>
            <div class="mbbs-step"><h4><?= htmlspecialchars($s[0]) ?></h4><p><?= htmlspecialchars($s[1] ?? '') ?></p></div>
            <?php endforeach; ?>
        </div>
        <div class="mt-4"><a href="<?= get_absolute_url('login') ?>" class="ds-btn ds-btn--primary ds-btn--lg">Start Your Application</a></div>
    </div>
</section>
<?php endif; ?>

<!-- ===================== EXTRA ADMIN SECTIONS (freeform) ===================== -->
<?php if ($mbbsSections): ?>
<section class="ds-section">
    <div class="container" style="max-width:900px">
        <?php foreach ($mbbsSections as $sec): ?>
        <div class="mb-4" id="<?= htmlspecialchars($sec['section_key'], ENT_QUOTES) ?>">
            <?php if (!empty($sec['heading'])): ?><h2 class="ds-title"><?= htmlspecialchars($sec['heading']) ?></h2><?php endif; ?>
            <div class="page-section-body" style="color:var(--ds-body);line-height:1.8"><?= $sec['body'] /* admin-authored HTML */ ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- ===================== FAQ ===================== -->
<?php if ($mbbsFaqs): ?>
<section class="ds-section <?= $mbbsSections ? 'ds-section--soft' : '' ?>" id="faq">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">FAQ</span><h2 class="ds-title"><?= setting_e('mbbs_faq_title', 'MBBS — frequently asked questions') ?></h2></div>
        <div class="mbbs-faq-wrap">
            <?php foreach ($mbbsFaqs as $f): ?>
            <details class="ds-faq">
                <summary><?= htmlspecialchars($f['q']) ?></summary>
                <div class="ds-faq__body"><?= nl2br(htmlspecialchars($f['a'])) ?></div>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- ===================== CTA ===================== -->
<section class="ds-section ds-section--soft">
    <div class="container">
        <div class="ds-cta-band">
            <h2><?= setting_e('mbbs_cta_title', 'Ready to study MBBS at Xinjiang Medical University?') ?></h2>
            <p><?= setting_e('mbbs_cta_lead', 'Apply for the September 2026 intake or talk to our admissions counsellors for free guidance.') ?></p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="<?= get_absolute_url('login') ?>" class="ds-btn ds-btn--lg" style="background:#fff;color:var(--ds-red)!important">Apply Now</a>
                <a href="https://wa.me/+8617590820634" target="_blank" rel="noopener noreferrer" class="ds-btn ds-btn--ghost-light ds-btn--lg"><i class="fab fa-whatsapp"></i> Chat on WhatsApp</a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
