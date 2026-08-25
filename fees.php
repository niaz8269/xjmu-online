<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/content.php';
require_once __DIR__ . '/includes/settings.php';

$feeSections = get_page_sections('fees'); // admin-managed extra sections (optional)

// ---- SEO (targets "china mbbs fees" + variants) ----
$page_title       = "MBBS Fees in China 2026 | Xinjiang Medical University Tuition & Cost";
$page_description = "Complete MBBS fees in China at Xinjiang Medical University — first-year package ~¥49,950 (≈US$7,000), tuition, hostel, insurance and cost of living. Transparent 2026 fee structure with no hidden charges.";
$page_keywords    = "china mbbs fees, MBBS fees in China, Xinjiang Medical University fees, XJMU tuition, MBBS cost in China, cheapest MBBS in China, MBBS China fee structure 2026, study MBBS China cost";
$page_url         = "https://xjmu.online/fees";
$page_image       = "https://xjmu.online/images/logo.png";

$body_class = 'ds-page';
$page_css   = ['css/design-system.css', 'css/fees.css'];

// Fee FAQ (drives visible section + FAQPage schema)
$feeFaqs = [
    ['q' => 'How much does MBBS cost in China at XJMU?',
     'a' => 'The complete first-year package is approximately ¥49,950 (about US$7,000). From the second year onward, the total is about ¥35,950 per year, which includes tuition, accommodation, insurance and residence permit.'],
    ['q' => 'What is included in the first-year MBBS fee package?',
     'a' => 'The first-year package covers the application processing fee, tuition, hostel, visa, health insurance and medical, registration fee, management and admission charges, JW202 visa letter and airport pickup.'],
    ['q' => 'Are there any hidden charges?',
     'a' => 'No. XJMU publishes a transparent, all-inclusive fee structure. Living expenses (food and personal costs) are separate and typically range from US$150–250 per month.'],
    ['q' => 'Can I get a scholarship to reduce MBBS fees?',
     'a' => 'Yes. Eligible international students can apply for CSC government scholarships and merit awards. Visit our scholarships page or speak to a counsellor for guidance.'],
    ['q' => 'How and when do I pay the fees?',
     'a' => 'Fees are paid annually after you receive your admission letter. Our admissions team guides you through the payment process for the September 2026 intake.'],
];

include __DIR__ . '/includes/header.php';
?>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    <?php $j = [];
    foreach ($feeFaqs as $f) {
        $q = json_encode($f['q'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $a = json_encode($f['a'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $j[] = '{"@type":"Question","name":' . $q . ',"acceptedAnswer":{"@type":"Answer","text":' . $a . '}}';
    }
    echo implode(",\n    ", $j); ?>

  ]
}
</script>

<!-- ===================== HERO ===================== -->
<section class="fees-hero">
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2">2026 Fee Structure</span>
        <h1>MBBS Fees in China at Xinjiang Medical University</h1>
        <p>Transparent, all-inclusive MBBS tuition and living costs — one of the most affordable WHO &amp; MOE-listed English-medium medical degrees in China.</p>
        <div class="home-hero__trust">
            <span class="ds-pill"><i class="fas fa-check-circle"></i> No hidden charges</span>
            <span class="ds-pill"><i class="fas fa-check-circle"></i> Pay annually</span>
            <span class="ds-pill"><i class="fas fa-check-circle"></i> Scholarships available</span>
        </div>
    </div>
</section>

<div class="container">
    <nav class="fees-crumb" aria-label="breadcrumb">
        <a href="<?= get_absolute_url('index') ?>">Home</a> &rsaquo; <span>MBBS Fees</span>
    </nav>
</div>

<!-- Admin-managed intro sections (optional) -->
<?php if ($feeSections): ?>
<section class="ds-section" style="padding-bottom:0">
    <div class="container">
        <?php foreach ($feeSections as $sec): ?>
        <div class="mb-4" id="<?= htmlspecialchars($sec['section_key'], ENT_QUOTES) ?>">
            <?php if (!empty($sec['heading'])): ?><h2 class="ds-title"><?= htmlspecialchars($sec['heading']) ?></h2><?php endif; ?>
            <div class="page-section-body"><?= $sec['body'] /* admin-authored HTML */ ?></div>
        </div>
        <?php endforeach; ?>
    </div>
</section>
<?php endif; ?>

<!-- ===================== FEE TABLES ===================== -->
<section class="ds-section">
    <div class="container">
        <div class="ds-section-head">
            <span class="ds-eyebrow">Tuition &amp; Charges</span>
            <h2 class="ds-title">XJMU MBBS fee breakdown (in RMB ¥)</h2>
            <p class="ds-lead">Figures are indicative for the 2026 intake. US$ conversions are approximate.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="fee-table-card">
                    <div class="fee-table-card__head">
                        <h3>First Year — Complete Package</h3>
                        <span class="tag">All-inclusive · one-time first-year total</span>
                    </div>
                    <table class="fee-table">
                        <tbody>
                            <tr class="total"><th>Total (1st year)</th><td>¥49,950 (≈ US$7,000)</td></tr>
                            <tr><th>Tuition, hostel &amp; admission</th><td>Included</td></tr>
                            <tr><th>Visa, insurance &amp; medical</th><td>Included</td></tr>
                            <tr><th>JW202 letter &amp; airport pickup</th><td>Included</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="fee-table-card">
                    <div class="fee-table-card__head">
                        <h3>2nd Year Onward — Per Year</h3>
                        <span class="tag">Recurring annual costs</span>
                    </div>
                    <table class="fee-table">
                        <tbody>
                            <tr><th>Tuition fee</th><td>¥30,000</td></tr>
                            <tr><th>Accommodation</th><td>¥4,500</td></tr>
                            <tr><th>Insurance</th><td>¥300</td></tr>
                            <tr><th>Residence permit</th><td>¥800</td></tr>
                            <tr><th>Physical examination</th><td>¥350</td></tr>
                            <tr class="total"><th>Total per year</th><td>¥35,950 (≈ US$5,050)</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== WHAT'S INCLUDED ===================== -->
<section class="ds-section ds-section--soft">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="ds-eyebrow">What's Included</span>
                <h2 class="ds-title">Your first-year package covers everything</h2>
                <p class="ds-lead mb-4">No surprises after you arrive — the first-year fee bundles all the essentials to get you enrolled and settled.</p>
                <ul class="fee-inc-grid">
                    <li><i class="fas fa-check-circle"></i> Application processing</li>
                    <li><i class="fas fa-check-circle"></i> Tuition fee</li>
                    <li><i class="fas fa-check-circle"></i> Hostel / accommodation</li>
                    <li><i class="fas fa-check-circle"></i> Student visa</li>
                    <li><i class="fas fa-check-circle"></i> Health insurance &amp; medical</li>
                    <li><i class="fas fa-check-circle"></i> Registration fee</li>
                    <li><i class="fas fa-check-circle"></i> Admission &amp; management</li>
                    <li><i class="fas fa-check-circle"></i> JW202 visa letter</li>
                    <li><i class="fas fa-check-circle"></i> Airport pickup</li>
                    <li><i class="fas fa-check-circle"></i> Liaison support</li>
                </ul>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-sm-4"><div class="ds-card fee-col-card"><div class="ds-icon-badge"><i class="fas fa-utensils"></i></div><div class="fee-col-amount">US$150–250</div><p>Food / month</p></div></div>
                    <div class="col-sm-4"><div class="ds-card fee-col-card"><div class="ds-icon-badge ds-icon-badge--navy"><i class="fas fa-bus"></i></div><div class="fee-col-amount">US$10–20</div><p>Transport / month</p></div></div>
                    <div class="col-sm-4"><div class="ds-card fee-col-card"><div class="ds-icon-badge"><i class="fas fa-mobile-alt"></i></div><div class="fee-col-amount">US$10–15</div><p>SIM / month</p></div></div>
                </div>
                <div class="ds-card mt-3">
                    <div class="ds-icon-badge ds-icon-badge--navy"><i class="fas fa-hand-holding-usd"></i></div>
                    <h3>Reduce your fees with a scholarship</h3>
                    <p>Eligible students can apply for CSC government scholarships and merit awards.
                    <a href="<?= get_absolute_url('scholarships') ?>">See scholarships →</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===================== FEE FAQ ===================== -->
<section class="ds-section">
    <div class="container">
        <div class="ds-section-head">
            <span class="ds-eyebrow">FAQ</span>
            <h2 class="ds-title">MBBS fees — frequently asked questions</h2>
        </div>
        <div style="max-width:820px;margin:0 auto">
            <?php foreach ($feeFaqs as $f): ?>
            <details class="ds-faq">
                <summary><?= htmlspecialchars($f['q']) ?></summary>
                <div class="ds-faq__body"><?= htmlspecialchars($f['a']) ?></div>
            </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ===================== CTA ===================== -->
<section class="ds-section ds-section--soft">
    <div class="container">
        <div class="ds-cta-band">
            <h2>Questions about MBBS fees?</h2>
            <p>Talk to our admissions counsellors for a personalised cost estimate and scholarship guidance — free of charge.</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="<?= get_absolute_url('login') ?>" class="ds-btn ds-btn--lg" style="background:#fff;color:var(--ds-red)!important">
                    Apply for 2026
                </a>
                <a href="https://wa.me/+8617590820634" target="_blank" rel="noopener noreferrer" class="ds-btn ds-btn--ghost-light ds-btn--lg">
                    <i class="fab fa-whatsapp"></i> Ask on WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>

<?php include __DIR__ . '/includes/footer.php'; ?>
