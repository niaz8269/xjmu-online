@extends('layouts.site')

@section('content')

<!-- ===================== HERO ===================== -->
<section class="prog-hero">
    <div class="container">
        <span class="ds-eyebrow" style="color:#ffd0d2">Academics</span>
        <h1 data-i18n="programs.page_title">Medical Programs at Xinjiang Medical University</h1>
        <p>English-medium MBBS, Master's, Doctoral and other medical programs for international students — with duration, language and tuition at a glance.</p>
    </div>
</section>

<div class="container">
    <nav class="prog-crumb" aria-label="breadcrumb">
        <a href="{{ get_absolute_url('index') }}">Home</a> &rsaquo; <span>Programs</span>
    </nav>
</div>

<main class="ds-section" style="padding-top:2rem">
    <div class="container">
        <!-- Filter Buttons -->
        <div class="prog-filters filter-section">
            <button class="filter-btn active" data-filter="bachelor" data-i18n="programs.filter_bachelor">Bachelor</button>
            <button class="filter-btn" data-filter="master" data-i18n="programs.filter_master">Master</button>
            <button class="filter-btn" data-filter="doctoral" data-i18n="programs.filter_doctoral">Doctoral</button>
            <button class="filter-btn" data-filter="non-degree" data-i18n="programs.filter_nondegree">Non-degree</button>
            <button class="filter-btn" data-filter="associate" data-i18n="programs.filter_associate">Associate</button>
        </div>

        <!-- Programs Table -->
        <div class="prog-table-wrap programs-table-wrapper">
            <table class="prog-table programs-table">
                <thead>
                    <tr>
                        <th data-i18n="programs.col_program">PROGRAM</th>
                        <th data-i18n="programs.col_duration">DURATION(YEAR)</th>
                        <th data-i18n="programs.col_language">INSTRUCTION LANGUAGE</th>
                        <th data-i18n="programs.col_fee">TUITION FEE(RMB/ACADEMIC YEAR)</th>
                        <th data-i18n="programs.col_apply">APPLY</th>
                    </tr>
                </thead>
                <tbody id="programsTableBody">
                    @foreach ($programs as $p)
                        <tr class="program-row" data-category="{{ strtolower($p->category ?: 'bachelor') }}">
                            <td class="program-name-cell" data-label="Program"><span class="program-name">{{ $p->name }}</span>@if (!empty($p->description))<div class="small text-muted">{{ $p->description }}</div>@endif</td>
                            <td class="duration-cell" data-label="Duration">{{ $p->duration ?: '—' }}</td>
                            <td class="language-cell" data-label="Language">{{ $p->language ?: '—' }}</td>
                            <td class="tuition-cell" data-label="Tuition">{{ $p->tuition ?: '—' }}</td>
                            <td class="apply-cell" data-label="Apply"><a href="{{ get_absolute_url('login') }}" class="btn-apply-table">Apply</a></td>
                        </tr>
                    @endforeach
                    @if ($programs->isEmpty())
                        <tr><td colspan="5" class="text-center text-muted py-4">No programs available at the moment.</td></tr>
                    @endif
                </tbody>
            </table>
        </div>

        <p class="ds-lead text-center mt-4" style="font-size:.95rem">
            Not sure which program fits you? <a href="{{ get_absolute_url('fees') }}" style="color:var(--ds-navy);font-weight:600">See the fee structure</a>
            or <a href="{{ get_absolute_url('contact') }}" style="color:var(--ds-navy);font-weight:600">talk to an admissions counsellor</a>.
        </p>
    </div>
</main>

<!-- ===================== CTA BAND ===================== -->
<section class="ds-section ds-section--soft">
    <div class="container">
        <div class="ds-cta-band">
            <h2>Ready to apply for the 2027 intake?</h2>
            <p>Start your application to Xinjiang Medical University or ask our counsellors which program suits you best.</p>
            <div class="d-flex flex-wrap gap-3 justify-content-center">
                <a href="{{ get_absolute_url('login') }}" class="ds-btn ds-btn--lg" style="background:#fff;color:var(--ds-red)!important">Apply Now</a>
                <a href="https://wa.me/+8617590820634" target="_blank" rel="noopener noreferrer" class="ds-btn ds-btn--ghost-light ds-btn--lg">
                    <i class="fab fa-whatsapp"></i> Chat on WhatsApp
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Client-side filter for the admin-managed program rows (unchanged behaviour) -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rows = Array.from(document.querySelectorAll('.program-row'));
    const buttons = document.querySelectorAll('.filter-btn');
    function applyFilter(filter) {
        rows.forEach(function (r) {
            r.style.display = (r.dataset.category === filter) ? '' : 'none';
        });
        buttons.forEach(function (b) {
            b.classList.toggle('active', b.dataset.filter === filter);
        });
    }
    buttons.forEach(function (b) {
        b.addEventListener('click', function () { applyFilter(b.dataset.filter); });
    });
    applyFilter('bachelor'); // default view
});
</script>
@endsection
