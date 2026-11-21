@extends('admin.layout')

@section('content')

<!-- Stat cards -->
<div class="row g-3 mb-2">
    @php
    $cards = [
        ['Total Users', $stats['users'], 'bi-people-fill', 'primary', 'users'],
        ['Submitted Applications', $stats['submitted'], 'bi-file-earmark-check-fill', 'info', 'applications'],
        ['Pending Review', $stats['pending'], 'bi-hourglass-split', 'warning', 'applications'],
        ['Approved', $stats['approved'], 'bi-patch-check-fill', 'success', 'applications'],
    ];
    @endphp
    @foreach ($cards as [$label, $value, $icon, $color, $link])
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card stat-card text-bg-{{ $color }} h-100">
                <div class="card-body d-flex align-items-center justify-content-between position-relative">
                    <div>
                        <div class="stat-value">{{ number_format($value) }}</div>
                        <div class="small text-uppercase opacity-75">{{ $label }}</div>
                    </div>
                    <i class="bi {{ $icon }} stat-icon"></i>
                    <a href="{{ $link }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Secondary stats -->
<div class="row g-3 mb-4">
    @php
    $cards2 = [
        ['New leads', $stats['leads_new'], 'leads?status=New'],
        ['Follow-ups due', $stats['leads_due'], 'leads?due=overdue'],
        ['Drafts (in progress)', $stats['drafts'], 'manage-applications'],
        ['Unverified Users', $stats['unverified'], 'users'],
        ['Blog Posts', $stats['blogs'], 'manage-blogs'],
        ['Events', $stats['events'], 'manage-events'],
    ];
    @endphp
    @foreach ($cards2 as [$label, $value, $link])
        <div class="col-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body d-flex align-items-center justify-content-between position-relative">
                    <div>
                        <div class="fs-4 fw-bold">{{ number_format($value) }}</div>
                        <div class="small text-muted">{{ $label }}</div>
                    </div>
                    <a href="{{ $link }}" class="stretched-link"></a>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Recent applications -->
<div class="card">
    <div class="card-header bg-body d-flex justify-content-between align-items-center">
        <h2 class="card-title h6 mb-0"><i class="bi bi-clock-history me-2"></i>Recent Submitted Applications</h2>
        <a href="applications" class="btn btn-sm btn-outline-secondary">View all</a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Applicant</th><th>Email</th><th>Programme</th><th>Status</th><th>Submitted</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    @if (!$recent)
                        <tr><td colspan="6" class="text-center text-muted py-4">No submitted applications yet.</td></tr>
                    @else
                        @foreach ($recent as $r)
                        <tr>
                            <td class="fw-semibold">{{ $r['full_name'] }}</td>
                            <td class="text-muted small">{{ $r['email'] }}</td>
                            <td class="small">{!! e(trim(($r['degree_applying_for'] ?? '') . ' ' . ($r['major_applying_for'] ?? ''))) ?: '—' !!}</td>
                            <td>{!! \App\Support\Admin\Html::statusBadge($r['status']) !!}</td>
                            <td class="small text-muted">{!! $r['submitted_at'] ? e(date('M j, Y', strtotime($r['submitted_at']))) : '—' !!}</td>
                            <td class="text-end"><a href="review_application?id={{ (int)$r['user_id'] }}" class="btn btn-sm btn-outline-primary">Review</a></td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
