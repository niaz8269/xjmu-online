@extends('admin.layout')
@section('content')

@if (!is_null($flash))
  <div class="alert alert-success">{{ $flash }}</div>
@endif

<div class="card"><div class="card-body">

  <!-- Filter Tabs + Search -->
  <div class="card mb-4 border-0 shadow-sm">
    <div class="card-body pb-2">

      <!-- Submission status filter tabs -->
      <div class="btn-group mb-3" role="group" aria-label="Filter by submission status">
        <a href="?{!! !empty($search) ? 'search='.urlencode($search).'&' : '' !!}filter="
          class="btn btn-sm {{ $filter === '' ? 'btn-dark' : 'btn-outline-secondary' }}">
          All <span class="badge {{ $filter === '' ? 'bg-light text-dark' : 'bg-secondary' }} ms-1">{{ $countAll }}</span>
        </a>
        <a href="?{!! !empty($search) ? 'search='.urlencode($search).'&' : '' !!}filter=submitted"
          class="btn btn-sm {{ $filter === 'submitted' ? 'btn-success' : 'btn-outline-success' }}">
          Submitted <span class="badge {{ $filter === 'submitted' ? 'bg-light text-success' : 'bg-success' }} ms-1">{{ $countSubmitted }}</span>
        </a>
        <a href="?{!! !empty($search) ? 'search='.urlencode($search).'&' : '' !!}filter=unsubmitted"
          class="btn btn-sm {{ $filter === 'unsubmitted' ? 'btn-warning text-dark' : 'btn-outline-warning' }}">
          Unsubmitted <span class="badge {{ $filter === 'unsubmitted' ? 'bg-light text-warning' : 'bg-warning text-dark' }} ms-1">{{ $countUnsub }}</span>
        </a>
      </div>

      <!-- Search box (name and email in the list are written as "Name (email)" for screen readers) -->
      <form method="GET" class="d-flex gap-2 align-items-center flex-wrap">
        @if (!empty($filter))
          <input type="hidden" name="filter" value="{{ $filter }}">
        @endif
        <input type="text" name="search" class="form-control form-control-sm"
               placeholder="Search by name or email…"
               value="{{ $search }}" style="max-width: 300px;">
        <select name="status" class="form-select form-select-sm" style="max-width: 170px;" aria-label="Review status">
          <option value="">Any status</option>
          @foreach ($reviewStatuses as $st)
            <option value="{{ $st }}" @selected($status === $st)>{{ $st }}</option>
          @endforeach
        </select>
        <select name="degree" class="form-select form-select-sm" style="max-width: 200px;" aria-label="Degree">
          <option value="">Any degree</option>
          @foreach ($degrees as $dg)
            <option value="{{ $dg }}" @selected($degree === $dg)>{{ $dg }}</option>
          @endforeach
        </select>
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
        <a href="manage-applications" class="btn btn-outline-primary btn-sm ms-auto"><i class="bi bi-pencil-square me-1"></i>Quick status editing</a>
        <a href="?export=csv{!! (!empty($search) ? '&search='.urlencode($search) : '') . (!empty($filter) ? '&filter='.urlencode($filter) : '') . ($status !== '' ? '&status='.urlencode($status) : '') . ($degree !== '' ? '&degree='.urlencode($degree) : '') !!}" class="btn btn-outline-success btn-sm"><i class="bi bi-download me-1"></i>Export this list (Excel/CSV)</a>
        @if (!empty($search) || !empty($filter) || $status !== '' || $degree !== '')
          <a href="applications" class="btn btn-outline-secondary btn-sm">Reset all</a>
        @endif
      </form>

    </div>
  </div>

  <!-- Results Info -->
  <div class="mb-3">
    <small class="text-muted">
      Showing {{ count($users) }} of {{ $totalRecords }}
      @if ($filter === 'submitted')
        submitted
      @elseif ($filter === 'unsubmitted')
        unsubmitted
      @endif
      application{{ $totalRecords !== 1 ? 's' : '' }}
      @if (!empty($search))
        matching <strong>{{ $search }}</strong>
      @endif
    </small>
  </div>

  @if (empty($users))
    <div class="alert alert-info">
      <h5>No applications found</h5>
      <p>
        @if (!empty($search) && !empty($filter))
          No {{ $filter }} applications matching <strong>{{ $search }}</strong>.
        @elseif (!empty($search))
          No applications matching <strong>{{ $search }}</strong>.
        @elseif (!empty($filter))
          There are currently no {{ $filter }} applications.
        @else
          There are currently no user applications in the system.
        @endif
      </p>
      @if (!empty($search) || !empty($filter))
        <a href="applications" class="btn btn-primary btn-sm">Reset all filters</a>
      @endif
    </div>
  @else
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead><tr><th>Applicant</th><th>Programme</th><th>Nationality</th><th>Documents</th><th>Submitted</th><th>Status</th><th></th></tr></thead>
      <tbody>
      @foreach ($users as $index => $user)
        @php
          $dm = $degreeMajorData[$user['id']] ?? null;
          $isSubmitted = !empty($user['is_submitted']) || in_array((string) ($user['status'] ?? ''), ['Pending', 'Approved', 'Rejected', 'Submitted'], true);
          $st = (string) ($user['status'] ?? '');
          $color = ['Approved' => 'success', 'Rejected' => 'danger', 'Pending' => 'warning'][$st] ?? 'secondary';
          $hasApp = $dm !== null || $isSubmitted || $st !== '';
        @endphp
        <tr>
          <td>
            @if ($hasApp)<a href="review_application?id={{ (int) $user['id'] }}" class="fw-semibold text-decoration-none">{{ $user['full_name'] }}</a>@else<span class="fw-semibold">{{ $user['full_name'] }}</span>@endif
            <div class="small text-muted">{{ $user['email'] }}</div>
          </td>
          <td class="small">{{ $dm ? trim(($dm['degree_applying_for'] ?? '') . ' ' . ($dm['major_applying_for'] ?? '')) : '' }}{{ !$dm || trim(($dm['degree_applying_for'] ?? '') . ($dm['major_applying_for'] ?? '')) === '' ? '—' : '' }}</td>
          <td class="small">{{ $personalData[$user['id']]['nationality'] ?? '—' }}</td>
          <td class="small">{{ count($documentsData[$user['id']] ?? []) }}</td>
          <td class="small text-nowrap">{{ $isSubmitted ? (substr((string) ($user['submitted_at'] ?? ''), 0, 10) ?: 'Yes') : '—' }}</td>
          <td>
            @if ($isSubmitted)
              <span class="badge text-bg-{{ $color }}">{{ $st !== '' ? $st : 'Submitted' }}</span>
            @elseif ($hasApp)
              <span class="badge text-bg-light">Draft</span>
            @else
              <span class="badge text-bg-light">Registered only</span>
            @endif
            @if ($st === 'Rejected' && !empty($user['admin_remark']))<div class="small text-danger">{{ $user['admin_remark'] }}</div>@endif
          </td>
          <td class="text-end text-nowrap">
            @if ($hasApp)
              <a href="review_application?id={{ (int) $user['id'] }}" class="btn btn-sm btn-outline-primary">Open</a>
            @endif
          </td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  @endif

  <!-- Pagination -->
  @php
  // Build a reusable query-string fragment that preserves search + filter
  $paginationParams = '';
  if (!empty($search))  $paginationParams .= '&search=' . urlencode($search);
  if (!empty($filter))  $paginationParams .= '&filter=' . urlencode($filter);
  if ($status !== '')   $paginationParams .= '&status=' . urlencode($status);
  if ($degree !== '')   $paginationParams .= '&degree=' . urlencode($degree);
  @endphp
  @if (!empty($users) && $totalPages > 1)
    <div class="mt-4">
      <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
          @if ($page > 1)
            <li class="page-item">
              <a class="page-link" href="?page={!! $page - 1 . $paginationParams !!}">Previous</a>
            </li>
          @endif

          @php
          $startPage = max(1, $page - 2);
          $endPage   = min($totalPages, $page + 2);
          @endphp
          @for ($i = $startPage; $i <= $endPage; $i++)
            <li class="page-item {{ $i === $page ? 'active' : '' }}">
              <a class="page-link" href="?page={!! $i . $paginationParams !!}">{{ $i }}</a>
            </li>
          @endfor

          @if ($page < $totalPages)
            <li class="page-item">
              <a class="page-link" href="?page={!! $page + 1 . $paginationParams !!}">Next</a>
            </li>
          @endif
        </ul>
      </nav>
    </div>
  @endif
</div></div>

@endsection
