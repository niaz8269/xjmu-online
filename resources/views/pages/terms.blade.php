@extends('layouts.site')

@section('content')

<!-- ===================== HERO ===================== -->
<section class="legal-hero">
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2" data-i18n="terms.eyebrow">Legal</span>
        <h1 data-i18n="terms.page_title">Terms of Use</h1>
        <p data-i18n="terms.lead">By using this website, you agree to these terms and applicable laws related to admissions content and services.</p>
    </div>
</section>

<div class="container">
    <nav class="legal-crumb" aria-label="breadcrumb">
        <a href="{{ get_absolute_url('index') }}">Home</a> &rsaquo; <span>Terms of Use</span>
    </nav>
</div>

<main class="ds-section" style="padding-top:2rem">
    <div class="container">
        <div class="legal-body">
            <h2 data-i18n="terms.content_heading">Use of Content</h2>
            <p data-i18n="terms.content_desc">Information on this website is provided for educational and admissions purposes. Content may be updated without prior notice.</p>

            <h2 data-i18n="terms.responsibility_heading">User Responsibilities</h2>
            <p data-i18n="terms.responsibility_desc">You are responsible for the accuracy of information you submit through forms and application processes.</p>

            <h2 data-i18n="terms.links_heading">External Links</h2>
            <p data-i18n="terms.links_desc">This website may include links to third-party services. We are not responsible for third-party content or policies.</p>

            <h2 data-i18n="terms.contact_heading">Contact</h2>
            <p>For questions regarding these terms, contact <a href="mailto:studyatxjmu@gmail.com">studyatxjmu@gmail.com</a>.</p>
        </div>
    </div>
</main>
@endsection
