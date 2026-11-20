@extends('layouts.site')

@section('content')

<script type="application/ld+json">
{
  "@@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {"@type": "ListItem", "position": 1, "name": "Home", "item": "https://xjmu.online/"},
    {"@type": "ListItem", "position": 2, "name": "Blog", "item": "https://xjmu.online/blogs"},
    {"@type": "ListItem", "position": 3, "name": {!! json_encode($guide['crumb'], JSON_UNESCAPED_UNICODE) !!}, "item": {!! json_encode($page_url, JSON_UNESCAPED_SLASHES) !!}}
  ]
}
</script>
<script type="application/ld+json">
{!! json_encode(['@context' => 'https://schema.org', '@type' => 'BlogPosting', 'headline' => $guide['h1'], 'description' => $guide['description'], 'mainEntityOfPage' => $page_url, 'datePublished' => $guide['published'] ?? $guide['updated'], 'dateModified' => $guide['updated'], 'abstract' => $guide['answer'] ?? $guide['lead'], 'image' => $page_image, 'inLanguage' => 'en', 'about' => ['@type' => 'CollegeOrUniversity', 'name' => 'Xinjiang Medical University', 'url' => 'https://xjmu.online/'], 'speakable' => ['@type' => 'SpeakableSpecification', 'cssSelector' => ['h1', '.guide-answer']], 'author' => ['@type' => 'Organization', 'name' => 'Xinjiang Medical University Admissions'], 'publisher' => ['@type' => 'Organization', 'name' => 'XJMU', 'logo' => ['@type' => 'ImageObject', 'url' => 'https://xjmu.online/images/logo.png']]], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) !!}
</script>
@if (!empty($guide['faqs']))
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
        <span class="ds-eyebrow" style="color:#ffd0d2">{{ $guide['eyebrow'] }}</span>
        <h1>{{ $guide['h1'] }}</h1>
        <p class="mbbs-hero__lead">{{ $guide['lead'] }}</p>
        <div class="mbbs-hero__cta">
            <a href="{{ get_absolute_url('login') }}" class="ds-btn ds-btn--primary ds-btn--lg">{{ $guide['cta'] }}</a>
            <a href="{{ get_absolute_url('fees') }}" class="ds-btn ds-btn--ghost-light ds-btn--lg">View Fees</a>
        </div>
        <div class="mbbs-hero__trust">
            <span class="ds-pill">WHO / WDOMS Listed</span>
            <span class="ds-pill">MOE Approved</span>
            <span class="ds-pill">English Medium</span>
            <span class="ds-pill">Updated {{ $updatedLabel }}</span>
        </div>
    </div>
</section>

<div class="container">
    <nav class="mbbs-crumb" aria-label="breadcrumb">
        <a href="{{ get_absolute_url('index') }}">Home</a> &rsaquo; <a href="{{ get_absolute_url('blogs') }}">Blog</a> &rsaquo; <span>{{ $guide['crumb'] }}</span>
    </nav>
</div>

@if (!empty($guide['answer']))
<!-- ===================== QUICK ANSWER + KEY FACTS ===================== -->
<section class="ds-section" style="padding:1.5rem 0 0" id="quick-answer">
    <div class="container">
        <div style="max-width:820px; border-inline-start:4px solid var(--ds-red); background:var(--ds-navy-050); border-radius:12px; padding:1.25rem 1.5rem">
            <h2 style="font-size:1.15rem; margin:0 0 .5rem; color:var(--ds-navy)">Quick answer</h2>
            <p class="guide-answer" style="margin:0; line-height:1.75; color:var(--ds-body)">{{ $guide['answer'] }}</p>
        </div>
        @if (!empty($guide['facts']))
        <div class="table-responsive" style="max-width:820px; margin-top:1rem">
            <table class="table" style="width:100%">
                <caption style="caption-side:top; color:var(--ds-muted); font-size:.9rem">Key facts at a glance (checked {{ $updatedLabel }})</caption>
                <tbody>
                    @foreach ($guide['facts'] as [$label, $value])
                    <tr><th scope="row" style="width:38%">{{ $label }}</th><td>{{ $value }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</section>
@endif

<!-- ===================== ON THIS PAGE ===================== -->
<section class="ds-section" style="padding:1.5rem 0 0">
    <div class="container">
        <nav aria-label="On this page" style="max-width:820px">
            <span class="ds-eyebrow">On this page</span>
            <ol style="columns:2; column-gap:2rem; padding-left:1.2rem; margin:.5rem 0 0; line-height:1.9">
                @foreach ($guide['sections'] as $i => $sec)
                <li><a href="#{{ $sec['id'] ?? 'section-' . ($i + 1) }}" style="color:var(--ds-navy)">{{ $sec['h'] }}</a></li>
                @endforeach
                <li><a href="#faq" style="color:var(--ds-navy)">Frequently asked questions</a></li>
            </ol>
        </nav>
    </div>
</section>

@foreach ($guide['sections'] as $i => $sec)
<section class="ds-section {{ $i % 2 ? 'ds-section--soft' : '' }}" id="{{ $sec['id'] ?? 'section-' . ($i + 1) }}">
    <div class="container">
        <div class="ds-section-head ds-section-head--left" style="max-width:820px">
            @if (!empty($sec['eyebrow']))<span class="ds-eyebrow">{{ $sec['eyebrow'] }}</span>@endif
            <h2 class="ds-title">{{ $sec['h'] }}</h2>
        </div>
        <div style="max-width:820px">
            @foreach ($sec['p'] ?? [] as $n => $para)
            <p @if ($n === 0) class="ds-lead" style="margin-bottom:1rem" @else style="color:var(--ds-body); line-height:1.8; margin-bottom:1rem" @endif>{!! \App\Support\CasLinks::text($para) !!}</p>
            @endforeach

            @if (!empty($sec['img']))
            <figure style="margin:1.5rem 0">
                <img src="{{ get_absolute_url($sec['img']['src']) }}" alt="{{ $sec['img']['alt'] }}" loading="lazy" decoding="async" style="width:100%; height:auto; border-radius:14px">
                <figcaption style="color:var(--ds-muted); font-size:.9rem; margin-top:.5rem">{{ $sec['img']['caption'] }}</figcaption>
            </figure>
            @endif

            @if (!empty($sec['list']))
            <ul class="mbbs-reqs">
                @foreach ($sec['list'] as $item)
                <li><i class="fas fa-check-circle"></i> {!! \App\Support\CasLinks::text($item) !!}</li>
                @endforeach
            </ul>
            @endif

            @foreach ($sec['sub'] ?? [] as $sub)
            <h3 style="font-size:1.25rem; margin:1.75rem 0 .6rem; color:var(--ds-navy)">{{ $sub['h'] }}</h3>
            @foreach ($sub['p'] ?? [] as $para)
            <p style="color:var(--ds-body); line-height:1.8; margin-bottom:1rem">{!! \App\Support\CasLinks::text($para) !!}</p>
            @endforeach
            @if (!empty($sub['list']))
            <ul class="mbbs-reqs">
                @foreach ($sub['list'] as $item)
                <li><i class="fas fa-check-circle"></i> {!! \App\Support\CasLinks::text($item) !!}</li>
                @endforeach
            </ul>
            @endif
            @endforeach

            @if (!empty($sec['table']))
            <div class="mbbs-fee-card" style="margin-top:1rem">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        @if (!empty($sec['table']['caption']))<caption style="caption-side:top; padding:.75rem 1rem; font-weight:600; color:var(--ds-navy)">{{ $sec['table']['caption'] }}</caption>@endif
                        <thead><tr>@foreach ($sec['table']['head'] as $th)<th scope="col">{{ $th }}</th>@endforeach</tr></thead>
                        <tbody>
                            @foreach ($sec['table']['rows'] as $row)
                            <tr @if (in_array($sec['table']['highlight'] ?? null, $row, true) || $row[0] === '') style="font-weight:700" @endif>@foreach ($row as $cell)<td>{!! \App\Support\CasLinks::text($cell) !!}</td>@endforeach</tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            @foreach (($sec['charts'] ?? []) as $chart)
            @php
                $cMin = $chart['min']; $cMax = $chart['max']; $cSpan = max(1, $cMax - $cMin);
                $cColors = ['#12305a', '#d98e04'];
                $cZero = (0 - $cMin) / $cSpan * 100;
            @endphp
            <figure class="mbbs-fee-card" style="margin-top:1rem; padding:1rem 1.25rem" role="group" aria-label="{{ $chart['caption'] }}">
                <figcaption style="font-weight:600; color:var(--ds-navy); margin-bottom:.75rem">{{ $chart['caption'] }}</figcaption>
                @if (count($chart['series']) > 1)
                <div style="display:flex; gap:1rem; flex-wrap:wrap; font-size:.85rem; margin-bottom:.5rem">
                    @foreach ($chart['series'] as $si => $sname)<span><span aria-hidden="true" style="display:inline-block; width:.8rem; height:.8rem; border-radius:3px; background:{{ $cColors[$si] }}; margin-inline-end:.35rem"></span>{{ $sname }}</span>@endforeach
                </div>
                @endif
                @foreach ($chart['rows'] as [$label, $values])
                <div style="display:flex; align-items:center; gap:.75rem; margin:.3rem 0">
                    <div style="flex:0 0 38%; font-size:.9rem">{{ $label }}</div>
                    <div style="flex:1; min-width:0">
                        @foreach ($values as $vi => $v)
                        @php $left = (min(0, $v) - $cMin) / $cSpan * 100; $width = abs($v) / $cSpan * 100; @endphp
                        <div style="position:relative; height:1.15rem; margin:2px 0; background:rgba(0,0,0,.04); border-radius:4px">
                            <div style="position:absolute; top:0; bottom:0; inset-inline-start:{{ $cZero }}%; width:1px; background:rgba(0,0,0,.25)"></div>
                            <div style="position:absolute; top:0; bottom:0; inset-inline-start:{{ $left }}%; width:{{ max($width, 0.6) }}%; background:{{ $cColors[$vi] }}; border-radius:4px"></div>
                            <span style="position:absolute; inset-inline-end:.35rem; top:50%; transform:translateY(-50%); font-size:.75rem; font-weight:600">{{ str_replace('-', '−', (string) $v) }}{{ $chart['unit'] ?? '' }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
                @if (!empty($chart['note']))<p style="color:var(--ds-muted); font-size:.85rem; margin:.75rem 0 0">{{ $chart['note'] }}</p>@endif
            </figure>
            @endforeach

            @if (!empty($sec['video']))
            <div class="ratio ratio-16x9" style="margin-top:1.5rem; border-radius:14px; overflow:hidden">
                <iframe src="{{ $sec['video']['src'] }}" title="{{ $sec['video']['title'] }}" loading="lazy" allowfullscreen></iframe>
            </div>
            @endif

            @if (!empty($sec['note']))<p style="color:var(--ds-muted); font-size:.9rem; margin-top:1rem">{{ $sec['note'] }}</p>@endif
            @if (!empty($sec['link']))<a href="{{ get_absolute_url($sec['link'][0]) }}" class="ds-btn ds-btn--primary mt-2">{{ $sec['link'][1] }}</a>@endif
        </div>
    </div>
</section>
@endforeach

<!-- ===================== FAQ ===================== -->
@if (!empty($guide['faqs']))
<section class="ds-section" id="faq">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">FAQ</span><h2 class="ds-title">Frequently asked questions</h2></div>
        <div class="mbbs-faq-wrap">
            @foreach ($guide['faqs'] as $f)
            <details class="ds-faq">
                <summary>{{ $f[0] }}</summary>
                <div class="ds-faq__body">{{ $f[1] }}</div>
            </details>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===================== RELATED GUIDES ===================== -->
@if ($related)
<section class="ds-section ds-section--soft">
    <div class="container">
        <div class="ds-section-head"><span class="ds-eyebrow">Keep reading</span><h2 class="ds-title">Related guides</h2></div>
        <div class="row g-4 justify-content-center">
            @foreach ($related as $r)
            <div class="col-md-5 col-sm-6"><div class="ds-card"><h3><a href="{{ $r['url'] }}" style="color:inherit;text-decoration:none">{{ $r['title'] }}</a></h3><p>{{ $r['lead'] }}</p></div></div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- ===================== CTA ===================== -->
<section class="ds-section">
    <div class="container">
        <div class="ds-cta-band">
            <h2>Ready to study MBBS at Xinjiang Medical University?</h2>
            <p>Talk to our admissions counsellors for free guidance, or start your application online.</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ get_absolute_url('login') }}" class="ds-btn ds-btn--lg" style="background:#fff;color:var(--ds-red)!important">Apply Now</a>
                <a href="{{ get_absolute_url('contact') }}" class="ds-btn ds-btn--ghost-light ds-btn--lg">Contact Admissions</a>
            </div>
        </div>
    </div>
</section>
@endsection
