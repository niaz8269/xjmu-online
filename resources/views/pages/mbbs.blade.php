@extends('layouts.site')

@section('content')

@if ($mbbsFaqs)
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {!! $faqSchema !!}

  ]
}
</script>
@endif

<!-- ===================== HERO ===================== -->
<section class="mbbs-hero">
    <div class="mbbs-hero__bg" role="img" aria-label="Xinjiang Medical University campus"></div>
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2">{{ $t['mbbs_hero_eyebrow'] }}</span>
        <h1>{{ $t['mbbs_hero_title'] }}</h1>
        <p class="mbbs-hero__lead">{{ $t['mbbs_hero_subtitle'] }}</p>
        <div class="mbbs-hero__cta">
            <a href="{{ get_absolute_url('login') }}" class="ds-btn ds-btn--primary ds-btn--lg">Apply for 2027</a>
            <a href="{{ get_absolute_url('fees') }}" class="ds-btn ds-btn--ghost-light ds-btn--lg">View Fees</a>
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
        <a href="{{ get_absolute_url('index') }}">Home</a> &rsaquo; <span>MBBS Program</span>
    </nav>
</div>

<!-- ===================== OVERVIEW ===================== -->
<section class="ds-section" style="padding-top:2rem" id="overview">
    <div class="container">
        <div class="ds-section-head ds-section-head--left" style="max-width:820px">
            <span class="ds-eyebrow">{{ $t['mbbs_overview_eyebrow'] }}</span>
            <h2 class="ds-title">{{ $t['mbbs_overview_title'] }}</h2>
        </div>
        <div style="max-width:820px">
            @if ($t['mbbs_overview_p1'])<p class="ds-lead" style="margin-bottom:1rem">{{ $t['mbbs_overview_p1'] }}</p>@endif
            @if ($t['mbbs_overview_p2'])<p style="color:var(--ds-body); line-height:1.8; margin-bottom:1rem">{{ $t['mbbs_overview_p2'] }}</p>@endif
            @if ($t['mbbs_overview_p3'])<p style="color:var(--ds-body); line-height:1.8; margin:0">{{ $t['mbbs_overview_p3'] }}</p>@endif
        </div>
    </div>
</section>

<!-- ===================== PROGRAM AT A GLANCE ===================== -->
@if ($mbbsFacts)
<section class="ds-section ds-section--soft" id="at-a-glance">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">At a glance</span><h2 class="ds-title">{{ $t['mbbs_glance_title'] }}</h2></div>
        <div class="mbbs-facts">
            @foreach ($mbbsFacts as $f)
            <div class="mbbs-fact"><div class="mbbs-fact__label">{{ $f[0] }}</div><div class="mbbs-fact__value">{{ $f[1] }}</div></div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===================== WHY ===================== -->
@if ($mbbsWhy)
<section class="ds-section" id="why">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">Why XJMU</span><h2 class="ds-title">{{ $t['mbbs_why_title'] }}</h2></div>
        <div class="row g-4">
            @foreach ($mbbsWhy as $w)
            <div class="col-md-4 col-sm-6"><div class="ds-card"><h3>{{ $w[0] }}</h3><p>{{ $w[1] }}</p></div></div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===================== CURRICULUM ===================== -->
@if ($mbbsCurriculum)
<section class="ds-section ds-section--soft" id="curriculum">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">Curriculum</span><h2 class="ds-title">{{ $t['mbbs_curriculum_title'] }}</h2></div>
        <div class="mbbs-years">
            @foreach ($mbbsCurriculum as $c)
            <div class="mbbs-year"><span class="mbbs-year__tag">{{ $c->year_tag }}</span><div><h4>{{ $c->title }}</h4><p>{{ $c->description }}</p></div></div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===================== ELIGIBILITY ===================== -->
@if ($mbbsEligibility)
<section class="ds-section" id="eligibility">
    <div class="container">
        <div class="ds-section-head ds-section-head--left" style="max-width:820px"><span class="ds-eyebrow">Eligibility</span><h2 class="ds-title">{{ $t['mbbs_eligibility_title'] }}</h2></div>
        <ul class="mbbs-reqs">
            @foreach ($mbbsEligibility as $e)
            <li><i class="fas fa-check-circle"></i> {{ $e[0] }}</li>
            @endforeach
        </ul>
    </div>
</section>
@endif

<!-- ===================== FEES ===================== -->
<section class="ds-section ds-section--soft" id="fees">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="ds-eyebrow">Tuition &amp; Fees</span>
                <h2 class="ds-title">{{ $t['mbbs_fees_title'] }}</h2>
                <p class="ds-lead">{{ $t['mbbs_fees_note'] }}</p>
                <a href="{{ get_absolute_url('fees') }}" class="ds-btn ds-btn--primary mt-2">See full fee breakdown</a>
            </div>
            <div class="col-lg-6">
                @if ($mbbsFeeRows)
                <div class="mbbs-fee-card">
                    <div class="mbbs-fee-card__head"><h3>Indicative fees (RMB ¥)</h3></div>
                    <table class="mbbs-fee-table">
                        <tbody>
                            @foreach ($mbbsFeeRows as $fr)
                            <tr{!! $fr['is_total'] ? ' class="total"' : '' !!}><th>{{ $fr[0] }}</th><td>{{ $fr[1] }}</td></tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</section>

<!-- ===================== RECOGNITION ===================== -->
@if ($mbbsRecognition)
<section class="ds-section" id="recognition">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">Accreditation</span><h2 class="ds-title">{{ $t['mbbs_recognition_title'] }}</h2></div>
        <div class="mbbs-recog">
            @foreach ($mbbsRecognition as $r)
            <div class="mbbs-recog__item"><h4>{{ $r[0] }}</h4><p>{{ $r[1] }}</p></div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===================== ADMISSION PROCESS ===================== -->
@if ($mbbsApply)
<section class="ds-section ds-section--soft" id="how-to-apply">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">Admissions</span><h2 class="ds-title">{{ $t['mbbs_apply_title'] }}</h2></div>
        <div class="mbbs-steps">
            @foreach ($mbbsApply as $s)
            <div class="mbbs-step"><h4>{{ $s[0] }}</h4><p>{{ $s[1] }}</p></div>
            @endforeach
        </div>
        <div class="mt-4"><a href="{{ get_absolute_url('login') }}" class="ds-btn ds-btn--primary ds-btn--lg">Start Your Application</a></div>
    </div>
</section>
@endif

<!-- ===================== EXTRA ADMIN SECTIONS (freeform) ===================== -->
@if ($mbbsSections)
<section class="ds-section">
    <div class="container" style="max-width:900px">
        @foreach ($mbbsSections as $sec)
        <div class="mb-4" id="{{ $sec->section_key }}">
            @if (!empty($sec->heading))<h2 class="ds-title">{{ $sec->heading }}</h2>@endif
            <div class="page-section-body" style="color:var(--ds-body);line-height:1.8">{!! $sec->body !!}</div>
        </div>
        @endforeach
    </div>
</section>
@endif

<!-- ===================== FAQ ===================== -->
@if ($mbbsFaqs)
<section class="ds-section {{ $mbbsSections ? 'ds-section--soft' : '' }}" id="faq">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">FAQ</span><h2 class="ds-title">{{ $t['mbbs_faq_title'] }}</h2></div>
        <div class="mbbs-faq-wrap">
            @foreach ($mbbsFaqs as $f)
            <details class="ds-faq">
                <summary>{{ $f['q'] }}</summary>
                <div class="ds-faq__body">{!! nl2br(e($f['a'])) !!}</div>
            </details>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===================== GUIDES (SEO landing pages, config/seo_guides.php) ===================== -->
@if (config('seo_guides'))
<section class="ds-section" id="guides">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">Guides</span><h2 class="ds-title">MBBS in China guides</h2></div>
        <div class="row g-4">
            @foreach (config('seo_guides') as $guideSlug => $g)
            <div class="col-md-4 col-sm-6"><div class="ds-card"><h3><a href="{{ get_absolute_url($guideSlug) }}" style="color:inherit;text-decoration:none">{{ $g['h1'] }}</a></h3><p>{{ $g['lead'] }}</p></div></div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===================== CTA ===================== -->
<section class="ds-section ds-section--soft">
    <div class="container">
        <div class="ds-cta-band">
            <h2>{{ $t['mbbs_cta_title'] }}</h2>
            <p>{{ $t['mbbs_cta_lead'] }}</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ get_absolute_url('login') }}" class="ds-btn ds-btn--lg" style="background:#fff;color:var(--ds-red)!important">Apply Now</a>
                <a href="https://wa.me/+8617590820634" target="_blank" rel="noopener noreferrer" class="ds-btn ds-btn--ghost-light ds-btn--lg"><i class="fab fa-whatsapp"></i> Chat on WhatsApp</a>
            </div>
        </div>
    </div>
</section>
@endsection
