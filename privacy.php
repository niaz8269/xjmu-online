<?php
$page_title = "Privacy Policy - Xinjiang Medical University";
$page_description = "Read the privacy policy for xjmu.online — how we collect, use, and protect your personal data as part of our admissions platform and student portal.";
$page_keywords = "privacy policy, xjmu, xinjiang medical university";
$page_url = "https://xjmu.online/privacy";
$body_class = "ds-page";
$page_css = ['css/design-system.css', 'css/legal.css'];

include __DIR__ . '/includes/header.php';
?>

<!-- ===================== HERO ===================== -->
<section class="legal-hero">
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2" data-i18n="privacy.eyebrow">Legal</span>
        <h1 data-i18n="privacy.page_title">Privacy Policy</h1>
        <p data-i18n="privacy.lead">Xinjiang Medical University values your privacy. This page explains how we collect, use, and protect personal information submitted through this website.</p>
    </div>
</section>

<div class="container">
    <nav class="legal-crumb" aria-label="breadcrumb">
        <a href="<?= get_absolute_url('index') ?>">Home</a> &rsaquo; <span>Privacy Policy</span>
    </nav>
</div>

<main class="ds-section" style="padding-top:2rem">
    <div class="container">
        <div class="legal-body">
            <h2 data-i18n="privacy.collect_heading">Information We Collect</h2>
            <p data-i18n="privacy.collect_desc">We may collect contact details, academic information, and documents that you submit through inquiry and admissions forms.</p>

            <h2 data-i18n="privacy.use_heading">How We Use Information</h2>
            <p data-i18n="privacy.use_desc">Your information is used to process admissions requests, respond to inquiries, and provide support related to programs and applications.</p>

            <h2 data-i18n="privacy.security_heading">Data Security</h2>
            <p data-i18n="privacy.security_desc">We use administrative and technical safeguards to protect submitted information from unauthorized access or disclosure.</p>

            <h2 data-i18n="privacy.contact_heading">Contact</h2>
            <p>For privacy-related questions, contact us at <a href="mailto:studyatxjmu@gmail.com">studyatxjmu@gmail.com</a>.</p>
        </div>
    </div>
</main>

<?php include __DIR__ . '/includes/footer.php'; ?>

