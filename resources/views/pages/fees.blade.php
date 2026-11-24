@extends('layouts.site')

@section('content')

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {!! $faqSchema !!}

  ]
}
</script>

<!-- ===================== HERO ===================== -->
<section class="fees-hero">
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2">2027 Fee Structure</span>
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
        <a href="{{ get_absolute_url('index') }}">Home</a> &rsaquo; <span>MBBS Fees</span>
    </nav>
</div>

<!-- Admin-managed intro sections (optional) -->
@if ($feeSections->isNotEmpty())
<section class="ds-section" style="padding-bottom:0">
    <div class="container">
        @foreach ($feeSections as $sec)
        <div class="mb-4" id="{{ $sec->section_key }}">
            @if (!empty($sec->heading))<h2 class="ds-title">{{ $sec->heading }}</h2>@endif
            <div class="page-section-body">{!! $sec->body !!}</div>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- ===================== FEE TABLES ===================== -->
<section class="ds-section">
    <div class="container">
        <div class="ds-section-head">
            <span class="ds-eyebrow">Tuition &amp; Charges</span>
            <h2 class="ds-title">XJMU MBBS fee breakdown (in RMB ¥)</h2>
            <p class="ds-lead">Figures are indicative for the 2027 intake. US$ conversions are approximate.</p>
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
                    <a href="{{ get_absolute_url('scholarships') }}">See scholarships →</a></p>
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
            @foreach ($feeFaqs as $f)
            <details class="ds-faq">
                <summary>{{ $f['q'] }}</summary>
                <div class="ds-faq__body">{{ $f['a'] }}</div>
            </details>
            @endforeach
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
                <a href="{{ get_absolute_url('login') }}" class="ds-btn ds-btn--lg" style="background:#fff;color:var(--ds-red)!important">
                    Apply for 2027
                </a>
                <a href="https://wa.me/8618041566030" target="_blank" rel="noopener noreferrer" class="ds-btn ds-btn--ghost-light ds-btn--lg">
                    <i class="fab fa-whatsapp"></i> Ask on WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
