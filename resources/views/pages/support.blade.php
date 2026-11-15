@extends('layouts.site')

@section('content')

<!-- ===================== HERO ===================== -->
<section class="support-hero">
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2" data-i18n="support.eyebrow">Student Support</span>
        <h1 data-i18n="support.page_title">Dedicated Support for International Students</h1>
        <p data-i18n="support.hero_sub">Academic guidance, health services, visa assistance, and language support to help you thrive at Xinjiang Medical University.</p>
    </div>
</section>

<div class="container">
    <nav class="support-crumb" aria-label="breadcrumb">
        <a href="{{ get_absolute_url('index') }}">Home</a> &rsaquo; <span>Support</span>
    </nav>
</div>

<main class="support-page">
    <div class="container py-5">
        <div class="row g-4">
            <div class="col-md-6 col-sm-12">
                <div class="card feature-card p-3 h-100">
                    <img src="assets/images/iso.webp" alt="International Student Office" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-globe"></i> <span data-i18n="support.card1_title">International Student Office</span></h5>
                        <p class="card-text" data-i18n="support.card1_desc">Assists with visa procedures, academic guidance, and personal matters to ensure a smooth transition for international students.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="card feature-card p-3 h-100">
                    <img src="assets/images/campus1.webp" alt="Orientation Programs" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-info-circle"></i> <span data-i18n="support.card2_title">Orientation Programs</span></h5>
                        <p class="card-text" data-i18n="support.card2_desc">Comprehensive orientation sessions to familiarize international students with campus life, academic policies, and cultural norms.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <h2 class="section-title" data-i18n="support.health_heading">Health and Wellness</h2>
        <div class="row g-4 mt-4">
            <div class="col-md-6 col-sm-12">
                <div class="card feature-card p-3 h-100">
                    <img src="assets/images/clinic.webp" alt="On-campus Clinic" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-clinic-medical"></i> <span data-i18n="support.card3_title">On-campus Clinic</span></h5>
                        <p class="card-text" data-i18n="support.card3_desc">Provides medical services and health consultations to address students' health concerns promptly.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="card feature-card p-3 h-100">
                    <img src="assets/images/counselling.webp" alt="Counseling Services" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-user-md"></i> <span data-i18n="support.card4_title">Counseling Services</span></h5>
                        <p class="card-text" data-i18n="support.card4_desc">Offers psychological support and counseling to promote mental well-being among students.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <h2 class="section-title" data-i18n="support.career_heading">Career Counseling</h2>
        <div class="row g-4 mt-4">
            <div class="col-md-6 col-sm-12">
                <div class="card feature-card p-3 h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-briefcase"></i> <span data-i18n="support.card5_title">Career Guidance</span></h5>
                        <p class="card-text" data-i18n="support.card5_desc">Provides advice on internships, residencies, and career pathways to help students achieve their professional goals.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="card feature-card p-3 h-100">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-handshake"></i> <span data-i18n="support.card6_title">Job Placement Assistance</span></h5>
                        <p class="card-text" data-i18n="support.card6_desc">Assists students in finding suitable job opportunities through workshops and networking events.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container py-5">
        <h2 class="section-title" data-i18n="support.language_heading">Language Assistance</h2>
        <div class="row g-4 mt-4">
            <div class="col-md-6 col-sm-12">
                <div class="card feature-card p-3 h-100">
                    <img src="assets/images/language.webp" alt="Chinese Language Courses" class="card-img-top">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-language"></i> <span data-i18n="support.card7_title">Chinese Language Courses</span></h5>
                        <p class="card-text" data-i18n="support.card7_desc">Comprehensive courses designed to help international students master the Chinese language for better integration into local life and academics.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @if ($faqs->isNotEmpty())
    <div class="container py-5">
        <h2 class="section-title">Frequently Asked Questions</h2>
        <div class="accordion support-faq mt-4" id="faqAccordion">
            @foreach ($faqs as $f)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq{{ (int) $f->id }}">
                            {{ $f->question }}
                        </button>
                    </h2>
                    <div id="faq{{ (int) $f->id }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">{!! nl2br(e($f->answer)) !!}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</main>
    @endsection
