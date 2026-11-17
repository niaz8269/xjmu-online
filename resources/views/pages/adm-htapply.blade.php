@extends('layouts.site')

@section('content')
<!-- ===================== HERO ===================== -->
<section class="hta-hero">
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2">Admissions</span>
        <h1>Xinjiang Medical University — How to Apply</h1>
        <p>Eligibility criteria, required documents and the full step-by-step application process for international MBBS students.</p>
    </div>
</section>

<div class="container">
    <nav class="hta-crumb" aria-label="breadcrumb">
        <a href="{{ get_absolute_url('index') }}">Home</a> &rsaquo; <span>How to Apply</span>
    </nav>
</div>

<main class="ds-section" style="padding-top:2rem">
    <div class="container">

        <!-- Admission Requirements -->
        <div class="ds-section-head">
            <span class="ds-eyebrow">Eligibility</span>
            <h2 id="admissions-requirements" class="ds-title">Admission Requirements</h2>
        </div>

        <div class="hta-req-grid">
            <div class="ds-card hta-req-card">
                <h3>General Eligibility</h3>
                <ul>
                    <li>Between 18 and 35 years of age.</li>
                    <li>High school diploma or equivalent with science background (Physics, Chemistry, Biology).</li>
                </ul>
            </div>
            <div class="ds-card hta-req-card">
                <h3>Academic Standards</h3>
                <ul>
                    <li>Minimum GPA or percentage as defined by Xinjiang Medical University (e.g., 60% or higher).</li>
                </ul>
            </div>
            <div class="ds-card hta-req-card">
                <h3>English Proficiency</h3>
                <ul>
                    <li>IELTS, TOEFL, or proof of prior education in English.</li>
                    <li>Some exemptions may apply for students from English-speaking countries.</li>
                </ul>
            </div>
            <div class="ds-card hta-req-card">
                <h3>Health Certificate</h3>
                <ul>
                    <li>Medical fitness certificate, including immunization records.</li>
                </ul>
            </div>
            <div class="ds-card hta-req-card">
                <h3>Passport and Visa</h3>
                <ul>
                    <li>Valid passport with at least 12 months validity at the time of application.</li>
                </ul>
            </div>
        </div>

        <!-- How to Apply -->
        <div class="ds-section-head" style="margin-top:3.5rem">
            <span class="ds-eyebrow">Process</span>
            <h2 id="how-to-apply" class="ds-title">How to Apply — Step by Step</h2>
        </div>

        <ol class="hta-steps">
            <li>
                <span class="hta-step-num">1</span>
                <div class="hta-step-body">Visit the official XJMU international admissions portal.</div>
            </li>
            <li>
                <span class="hta-step-num">2</span>
                <div class="hta-step-body">Fill out the online application form.</div>
            </li>
            <li>
                <span class="hta-step-num">3</span>
                <div class="hta-step-body">Upload required documents (passport, academic transcripts, language certificates, etc.).</div>
            </li>
            <li>
                <span class="hta-step-num">4</span>
                <div class="hta-step-body">Pay the application fee online.</div>
            </li>
            <li>
                <span class="hta-step-num">5</span>
                <div class="hta-step-body">Wait for an offer letter from the admissions office.</div>
            </li>
        </ol>

        <div class="text-center" style="margin:2rem 0 3rem">
            <a href="{{ get_absolute_url('login') }}" class="ds-btn ds-btn--primary ds-btn--lg">Start Your Application</a>
        </div>

        <!-- Application Deadlines -->
        <div class="ds-section-head">
            <span class="ds-eyebrow">Dates</span>
            <h2 class="ds-title">Application Deadlines</h2>
        </div>

        <div class="hta-table-wrap">
            <table class="hta-table">
                <thead>
                    <tr>
                        <th>Intake</th>
                        <th>Deadline</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Intake">September/Fall Intake</td>
                        <td data-label="Deadline">Typically around June/July</td>
                    </tr>
                    <tr>
                        <td data-label="Intake">March/Spring Intake</td>
                        <td data-label="Deadline">Typically around December/January</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Required Documents -->
        <div class="ds-section-head" style="margin-top:3.5rem">
            <span class="ds-eyebrow">Checklist</span>
            <h2 class="ds-title">Required Documents</h2>
        </div>

        <div class="ds-card hta-docs">
            <ul>
                <li>High school diploma and transcripts (translated into English or Chinese).</li>
                <li>Copy of valid passport.</li>
                <li>Proof of English language proficiency.</li>
                <li>Financial guarantee letter (if applicable).</li>
                <li>Recent passport-sized photographs.</li>
            </ul>
        </div>

    </div>
</main>

<!-- ===================== CTA BAND ===================== -->
<section class="ds-section ds-section--soft">
    <div class="container">
        <div class="ds-cta-band">
            <h2>Ready to begin your MBBS journey?</h2>
            <p>Start your application to Xinjiang Medical University or reach our counsellors for guidance on requirements and documents.</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ get_absolute_url('login') }}" class="ds-btn ds-btn--lg" style="background:#fff;color:var(--ds-red)!important">Apply Now</a>
                <a href="https://wa.me/+8617590820634" target="_blank" rel="noopener noreferrer" class="ds-btn ds-btn--ghost-light ds-btn--lg">
                    <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>

@include('partials.chatbot')
@include('partials.socials')
@endsection
