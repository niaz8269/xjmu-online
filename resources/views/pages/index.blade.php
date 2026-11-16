@extends('layouts.site')

@section('content')

<!-- FAQ structured data (built from the same FAQs shown below) -->
<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {!! $faqSchema !!}

  ]
}
</script>

<!-- Webinar Registration Modal (functionality preserved) -->
<div class="webinar-modal-overlay" id="webinarModal" style="{{ $formSubmitted ? 'display: none;' : '' }}">
    <div class="webinar-modal-content">
        <button class="webinar-close-btn" id="closeWebinarModal">&times;</button>
        <div class="webinar-header">
            <h3 data-i18n="home.modal_title">Apply for MOE Listed English Taught MBBS – 2027 Intake</h3>
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

@if ($formSubmitted)
<div class="alert-overlay" id="successAlert">
    <div class="alert-content">
        <p>{{ $successMessage }}</p>
        <button class="alert-ok-btn" onclick="document.getElementById('successAlert').style.display='none';">OK</button>
    </div>
</div>
@endif

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
    @if (!$formSubmitted)
    setTimeout(() => { if (webinarModal) webinarModal.style.display = 'flex'; }, 1200);
    @endif
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
            <div class="carousel-item active"><div class="home-hero__bg" style="background-image:url('{{ get_absolute_url('images/campus3.webp') }}')"></div></div>
            <div class="carousel-item"><div class="home-hero__bg" data-bg="{{ get_absolute_url('images/campus13.webp') }}"></div></div>
            <div class="carousel-item"><div class="home-hero__bg" data-bg="{{ get_absolute_url('images/campus12.webp') }}"></div></div>
            <div class="carousel-item"><div class="home-hero__bg" data-bg="{{ get_absolute_url('images/campus5.webp') }}"></div></div>
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
            <span class="ds-eyebrow home-hero__eyebrow">{{ $t['home_hero_eyebrow'] }}</span>
            <h1>{{ $t['hero_headline'] }}</h1>
            <p class="home-hero__lead">
                {{ $t['hero_subtext'] }}
            </p>
            <div class="home-hero__trust">
                <span class="ds-pill">{{ $t['home_trust1'] }}</span>
                <span class="ds-pill">{{ $t['home_trust2'] }}</span>
                <span class="ds-pill">{{ $t['home_trust3'] }}</span>
                <span class="ds-pill">{{ $t['home_trust4'] }}</span>
            </div>
        </div>
    </div>
</section>

<!-- ===================== NEWS (featured + list) ===================== -->
<section class="ds-section" id="updates">
    <div class="container">
        <div class="home-secthead">
            <div>
                <span class="ds-eyebrow">{{ $t['home_news_eyebrow'] }}</span>
                <h2 class="ds-title">{{ $t['home_news_title'] }}</h2>
            </div>
            <a href="{{ get_absolute_url('blogs') }}" class="ds-btn ds-btn--primary" data-i18n="home.view_all_news">Read More</a>
        </div>
                @if ($newsFeat)
        <div class="home-news2">
            <a class="home-news2__feature" href="{{ $newsFeat['href'] }}">
                <span class="home-news2__img" style="background-image:url('{{ $newsFeat['img'] }}')"></span>
                <span class="home-news2__fbody">
                    <span class="home-news2__date">{{ $newsFeat['date'] }}</span>
                    <span class="home-news2__ftitle">{{ $newsFeat['title'] }}</span>
                    <span class="home-news2__fexc">{{ $newsFeat['excerpt'] }}</span>
                </span>
            </a>
            <div class="home-news2__list">
                @foreach ($newsRest as $b)
                <a class="home-news2__item" href="{{ $b['href'] }}">
                    <span class="home-news2__idate">{{ $b['date'] }}</span>
                    <span class="home-news2__ititle">{{ $b['title'] }}</span>
                </a>
                @endforeach
                @if (!$newsRest)<a class="home-news2__item" href="{{ get_absolute_url('blogs') }}"><span class="home-news2__ititle">Read all news &amp; announcements →</span></a>@endif
            </div>
        </div>
        @else
            <p class="text-muted">No news yet. <a href="{{ get_absolute_url('blogs') }}">Visit the blog →</a></p>
        @endif
    </div>
</section>

<!-- ===================== TAGLINE BAND ===================== -->
<section class="home-band" style="background-image:url('{{ get_absolute_url('images/campus10.webp') }}')">
    <span class="home-band__scrim"></span>
    <div class="container"><h2 class="home-band__title">{{ $t['home_about_title'] }}</h2></div>
</section>

<!-- ===================== ACCREDITATION STRIP ===================== -->
<section class="home-accred">
    <div class="container">
        <div class="row g-3">
            @foreach ($homeAccred as $a)
            <div class="col-md-3 col-6"><div class="home-accred__item"><div><h4>{{ $a->title }}</h4><p>{{ $a->description }}</p></div></div></div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===================== EVENTS ===================== -->
<section class="ds-section ds-section--soft" id="events">
    <div class="container">
        <div class="home-secthead">
            <div><span class="ds-eyebrow">Events</span><h2 class="ds-title">Upcoming events</h2></div>
            <a href="{{ get_absolute_url('events') }}" class="ds-btn ds-btn--outline">Learn More</a>
        </div>
        <div class="home-events">
            <div class="home-events__list">
                @forelse ($latestEvents as $ev)
                <a class="home-events__row" href="{{ $ev['href'] }}">
                    <span class="home-events__date">{{ $ev['date'] }}</span>
                    <span class="home-events__body">
                        <span class="home-events__title">{{ $ev['title'] }}</span>
                        @if (!empty($ev['venue']))<span class="home-events__venue"><i class="fas fa-location-dot"></i> {{ $ev['venue'] }}</span>@endif
                    </span>
                </a>
                @empty
                    <p class="text-muted">No upcoming events right now. <a href="{{ get_absolute_url('events') }}">See all events →</a></p>
                @endforelse
            </div>
            <div class="home-events__media">
                                <img src="{{ $evImg }}" alt="XJMU events" loading="lazy">
            </div>
        </div>
    </div>
</section>

<!-- ===================== MULTIMEDIA (gallery, dark) ===================== -->
@if ($media)
<section class="home-media">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow" style="color:#ffd0d2">Gallery</span><h2 class="ds-title" style="color:#fff">Campus highlights</h2></div>
        <div class="row g-4">
            @foreach ($media as $g)
            <div class="col-md-4"><a class="home-media__card" href="{{ get_absolute_url('gallery') }}">
                <img src="{{ $g['img'] }}" alt="{{ $g['alt'] }}" loading="lazy">
                <span class="home-media__play"><i class="fas fa-play"></i></span>
                @if (!empty($g['title']))<span class="home-media__cap">{{ $g['title'] }}</span>@endif
            </a></div>
            @endforeach
        </div>
        <div class="text-center mt-4"><a href="{{ get_absolute_url('gallery') }}" class="ds-btn ds-btn--primary">View Gallery</a></div>
    </div>
</section>
@endif

<!-- ===================== STUDY — Admissions ===================== -->
<section class="ds-section" id="study">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">Study</span><h2 class="ds-title">Educating future doctors with world-class medical training</h2></div>
        <div class="row g-5 align-items-center">
            <div class="col-lg-6"><img src="{{ get_absolute_url('images/class1.webp') }}" class="home-about__img" alt="XJMU admissions" loading="lazy"></div>
            <div class="col-lg-6">
                <span class="ds-eyebrow">Admissions</span>
                <h3 class="ds-title" style="font-size:1.7rem">{{ $t['home_help_title'] }}</h3>
                <p class="ds-lead">{{ $t['home_help_lead'] }}</p>
                <ul class="home-about__list">
                    <li>{{ $t['home_help_point1'] }}</li>
                    <li>{{ $t['home_help_point2'] }}</li>
                    <li>{{ $t['home_help_point3'] }}</li>
                </ul>
                <a href="{{ get_absolute_url('login') }}" class="ds-btn ds-btn--primary">Apply Now</a>
                <a href="{{ get_absolute_url('admissions') }}" class="ds-btn ds-btn--outline">Admission Details</a>
            </div>
        </div>
    </div>
</section>

<!-- ===================== STAT STRIP ===================== -->
<section class="home-statstrip">
    <div class="container">
        <div class="row text-center g-0">
            <div class="col-6 col-md-3"><div class="ds-stat"><div class="ds-stat__num"><span data-count="{{ $stats[1] }}">0</span>+</div><div class="ds-stat__label">{{ $t['home_stat1_label'] }}</div></div></div>
            <div class="col-6 col-md-3"><div class="ds-stat"><div class="ds-stat__num"><span data-count="{{ $stats[2] }}">0</span>+</div><div class="ds-stat__label">{{ $t['home_stat2_label'] }}</div></div></div>
            <div class="col-6 col-md-3"><div class="ds-stat"><div class="ds-stat__num"><span data-count="{{ $stats[3] }}">0</span>+</div><div class="ds-stat__label">{{ $t['home_stat3_label'] }}</div></div></div>
            <div class="col-6 col-md-3"><div class="ds-stat"><div class="ds-stat__num"><span data-count="{{ $stats[4] }}">0</span>+</div><div class="ds-stat__label">{{ $t['home_stat4_label'] }}</div></div></div>
        </div>
    </div>
</section>

<!-- ===================== ABOUT (text first, then full-width banner) ===================== -->
<section class="ds-section about-section" id="about-xjmu">
    <div class="container">
        <div class="about-intro">
            <span class="ds-eyebrow">{{ $t['home_about_eyebrow'] }}</span>
            <h2 class="ds-title">{{ $t['home_about_title'] }}</h2>
            <p class="ds-lead">{{ $t['home_about_lead'] }}</p>
            <ul class="home-about__list">
                <li>{{ $t['home_about_point1'] }}</li>
                <li>{{ $t['home_about_point2'] }}</li>
                <li>{{ $t['home_about_point3'] }}</li>
            </ul>
            <a href="{{ get_absolute_url('about') }}" class="ds-btn ds-btn--navy">{{ $t['home_about_btn'] }}</a>
        </div>
    </div>
    <a href="{{ $banner['href'] }}" class="about-banner" aria-label="{{ $t['home_about_banner_label'] }}">
        <img src="{{ $banner['img'] }}" alt="Xinjiang Medical University campus" loading="lazy">
        <span class="about-banner__overlay"></span>
        <span class="about-banner__inner">
            <span class="container">
                <span class="about-banner__label">{{ $t['home_about_banner_label'] }} <i class="fas fa-arrow-right"></i></span>
            </span>
        </span>
    </a>
</section>

<!-- ===================== PROGRAMS ===================== -->
<section class="ds-section ds-section--soft" id="programs">
    <div class="container">
        <div class="ds-section-head">
            <span class="ds-eyebrow">{{ $t['home_programs_eyebrow'] }}</span>
            <h2 class="ds-title">{{ $t['home_programs_title'] }}</h2>
            <p class="ds-lead">{{ $t['home_programs_lead'] }}</p>
        </div>
        <div class="row g-4">
            @foreach ($homePrograms as $p)
            <div class="col-lg-4 col-sm-6"><a href="{{ $p['url'] }}" class="home-prog-card"><h3>{{ $p['title'] }}</h3><p>{{ $p['description'] }}</p><span class="more">Explore →</span></a></div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===================== MBBS PROGRAM (section 1 of 2) ===================== -->
<section class="ds-section" id="mbbs-xjmu">
    <div class="container">
        <div class="ds-section-head ds-section-head--left" style="max-width:760px">
            <span class="ds-eyebrow">{{ $t['home_mbbs_eyebrow'] }}</span>
            <h2 class="ds-title">{{ $t['home_mbbs_title'] }}</h2>
            <p class="ds-lead">{{ $t['home_mbbs_lead'] }}</p>
        </div>
        <ul class="home-facts">
            @foreach ($homeFacts as $f)
            <li><strong>{{ $f->label }}:</strong> {{ $f->value }}</li>
            @endforeach
        </ul>
        <div class="row g-4 mt-1">
            <div class="col-md-6"><div class="ds-card"><h3>Clinical Excellence</h3><p>Rotations across 8 affiliated hospitals give real patient exposure from early years.</p></div></div>
            <div class="col-md-6"><div class="ds-card"><h3>Globally Recognized</h3><p>Listed by WHO (WDOMS) and recognized by PMDC, NMC and other councils for licensing.</p></div></div>
        </div>
        <a href="{{ get_absolute_url('programs') }}" class="ds-btn ds-btn--outline mt-4">Explore All Programs</a>
    </div>
</section>

<!-- ===================== MBBS FEES (section 2 of 2) ===================== -->
<section class="ds-section ds-section--soft" id="mbbs-fees">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <span class="ds-eyebrow">{{ $t['home_fees_eyebrow'] }}</span>
                <h2 class="ds-title">{{ $t['home_fees_title'] }}</h2>
                <p class="ds-lead">{{ $t['home_fees_note'] }}</p>
                <a href="{{ get_absolute_url('fees') }}" class="ds-btn ds-btn--primary mt-2">See full fee breakdown</a>
            </div>
            <div class="col-lg-6">
                <div class="home-fee-card">
                    <div class="home-fee-card__head">
                        <h3>{{ $t['home_fees_title'] }}</h3>
                        <span class="tag">{{ $t['home_fees_tag'] }}</span>
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
            <span class="ds-eyebrow">{{ $t['home_why_eyebrow'] }}</span>
            <h2 class="ds-title">{{ $t['home_why_title'] }}</h2>
            <p class="ds-lead">{{ $t['home_why_lead'] }}</p>
        </div>
        <div class="row g-4">
            @foreach ($homeWhy as $w)
            <div class="col-md-4 col-sm-6"><div class="ds-card"><h3>{{ $w->title }}</h3><p>{{ $w->description }}</p></div></div>
            @endforeach
        </div>
    </div>
</section>

<!-- ===================== HOW TO APPLY (section 1 of 2) ===================== -->
<section class="ds-section" id="how-to-apply">
    <div class="container">
        <div class="ds-section-head">
            <span class="ds-eyebrow">{{ $t['home_steps_eyebrow'] }}</span>
            <h2 class="ds-title">{{ $t['home_steps_title'] }}</h2>
            <p class="ds-lead">{{ $t['home_steps_lead'] }}</p>
        </div>
        <div class="row g-4">
            @foreach ($homeSteps as $i => $s)
            <div class="col-lg-4 col-md-6">
                <div class="home-step"><div class="home-step__num">{{ $i + 1 }}</div><div><h4>{{ $s->title }}</h4><p>{{ $s->description }}</p></div></div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-4">
            <a href="{{ get_absolute_url('login') }}" class="ds-btn ds-btn--primary ds-btn--lg">
                <span data-i18n="common.start_application">Start Your Application</span>
            </a>
        </div>
    </div>
</section>

<!-- ===================== RESEARCH BAND ===================== -->
<section class="home-research" style="background-image:url('{{ get_absolute_url('images/campus13.webp') }}')">
    <span class="home-research__scrim"></span>
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2">Research</span>
        <h2 class="ds-title" style="color:#fff">Transforming medical research into real-world care</h2>
        <p class="home-research__lead">ESI-ranked Clinical Medicine (top 2.07‰), 8 directly-affiliated hospitals and modern research centres driving healthcare innovation across the region.</p>
        <a href="{{ get_absolute_url('about') }}" class="ds-btn ds-btn--primary">Explore More</a>
    </div>
</section>

<!-- ===================== TESTIMONIALS (Students & Alumni, admin-managed) ===================== -->
@if ($students || $alumni)
<section class="ds-section" id="testimonials">
    <div class="container">
        <div class="ds-section-head">
            <span class="ds-eyebrow">{{ $t['home_testi_eyebrow'] }}</span>
            <h2 class="ds-title">{{ $t['home_testi_title'] }}</h2>
            <p class="ds-lead">{{ $t['home_testi_lead'] }}</p>
        </div>

        @if ($students)
        <h3 class="ds-eyebrow mb-3" data-i18n="home.recently_admitted">Recently Admitted Students</h3>
        <div class="row g-4 mb-5">
            @foreach ($students as $tm)
            <div class="col-lg-4 col-md-6"><div class="home-tcard">
                <p class="home-tcard__quote">{{ $tm['quote'] }}</p>
                <div class="home-tcard__person"><img src="{{ $tm['img'] }}" alt="{{ $tm['name'] }}" class="home-tcard__avatar" loading="lazy"><div><div class="home-tcard__name">{{ $tm['name'] }}</div><div class="home-tcard__meta">{{ $tm['meta'] }}</div></div></div>
            </div></div>
            @endforeach
        </div>
        @endif

        @if ($alumni)
        <h3 class="ds-eyebrow mb-3" data-i18n="home.distinguished_alumni">Distinguished Alumni</h3>
        <div class="row g-4">
            @foreach ($alumni as $tm)
            <div class="col-lg-4 col-md-6"><div class="home-tcard">
                <p class="home-tcard__quote">{{ $tm['quote'] }}</p>
                <div class="home-tcard__person"><img src="{{ $tm['img'] }}" alt="{{ $tm['name'] }}" class="home-tcard__avatar" loading="lazy"><div><div class="home-tcard__name">{{ $tm['name'] }}</div><div class="home-tcard__meta">{{ $tm['meta'] }}</div></div></div>
            </div></div>
            @endforeach
        </div>
        @endif
    </div>
</section>
@endif

<!-- ===================== CAMPUS (gallery grid) ===================== -->
@if ($campus)
<section class="ds-section ds-section--soft" id="campus">
    <div class="container">
        <div class="home-secthead">
            <div><span class="ds-eyebrow">Campus</span><h2 class="ds-title">A glimpse of campus life</h2></div>
            <a href="{{ get_absolute_url('gallery') }}" class="ds-btn ds-btn--outline">More Photos</a>
        </div>
        <div class="home-campus-grid">
            @foreach ($campus as $g)
            <a href="{{ get_absolute_url('gallery') }}" class="home-campus-cell"><img src="{{ $g['img'] }}" alt="{{ $g['alt'] }}" loading="lazy"></a>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===================== FAQ (dynamic + schema) ===================== -->
<section class="ds-section ds-section--soft" id="faq">
    <div class="container">
        <div class="ds-section-head">
            <span class="ds-eyebrow">{{ $t['home_faq_eyebrow'] }}</span>
            <h2 class="ds-title">{{ $t['home_faq_title'] }}</h2>
            <p class="ds-lead">{{ $t['home_faq_lead'] }}</p>
        </div>
        <div class="home-faq-wrap">
            @foreach ($homeFaqs as $f)
            <details class="ds-faq">
                <summary>{{ $f['question'] }}</summary>
                <div class="ds-faq__body">{!! nl2br(e($f['answer'])) !!}</div>
            </details>
            @endforeach
        </div>
    </div>
</section>

<!-- ===================== CTA BAND ===================== -->
<section class="ds-section">
    <div class="container">
        <div class="ds-cta-band">
            <h2>{{ $t['home_cta_title'] }}</h2>
            <p>{{ $t['home_cta_lead'] }}</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ get_absolute_url('login') }}" class="ds-btn ds-btn--lg" style="background:#fff;color:var(--ds-red)!important">
                    <span>{{ $t['home_cta_btn'] }}</span>
                </a>
                <a href="https://wa.me/+8617590820634" target="_blank" rel="noopener noreferrer" class="ds-btn ds-btn--ghost-light ds-btn--lg">
                    <i class="fab fa-whatsapp"></i> <span data-i18n="home.cta_whatsapp">Chat on WhatsApp</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Page Specific JS -->
<script src="{{ get_js_url('js/home.js') }}"></script>
@endsection
