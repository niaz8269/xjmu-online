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

      <!-- Search box -->
      <form method="GET" class="d-flex gap-2 align-items-center flex-wrap">
        @if (!empty($filter))
          <input type="hidden" name="filter" value="{{ $filter }}">
        @endif
        <input type="text" name="search" class="form-control form-control-sm"
               placeholder="Search by name or email…"
               value="{{ $search }}" style="max-width: 300px;">
        <button type="submit" class="btn btn-primary btn-sm">Search</button>
        @if (!empty($search) || !empty($filter))
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
  <div class="accordion" id="applicationsAccordion">
    @foreach ($users as $index => $user)
      @php
        $statusText = trim((string)($user['status'] ?? ''));
        $isSubmitted = !empty($user['is_submitted']) || in_array($statusText, ['Pending', 'Approved', 'Rejected', 'Submitted'], true);
      @endphp
      <div class="accordion-item mb-2">
        <h2 class="accordion-header" id="heading{{ $index }}">
          <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $index }}" aria-expanded="false" aria-controls="collapse{{ $index }}">
            {{ $user['full_name'] }} ({{ $user['email'] }})
            - <span class="badge bg-{{ $isSubmitted ? 'success' : 'warning' }} ms-2">
                {{ $isSubmitted ? 'Submitted' : 'Unsubmitted' }}
              </span>
          </button>
        </h2>
        <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#applicationsAccordion">
          <div class="accordion-body">

            @php
              $dm = $degreeMajorData[$user['id']] ?? null;
            @endphp
            @if ($dm && (!empty($dm['degree_applying_for']) || !empty($dm['major_applying_for'])))
            <p><strong>Degree Applying for:</strong> {{ $dm['degree_applying_for'] ?? '—' }}<br>
            <strong>Major Applying for:</strong> {{ $dm['major_applying_for'] ?? '—' }}</p>
            <hr>
            @endif

            <!-- Application Status & Review -->
            <p><strong>Status:</strong>
            {{ !empty($user['status']) ? $user['status'] : 'Not set' }}
               @if (!empty($user['status']) && $user['status'] === 'Rejected' && !empty($user['admin_remark']))
                  <br><small class="text-danger">{{ $user['admin_remark'] }}</small>
              @endif
              </p>
            <a href="review_application?id={{ $user['id'] }}" class="btn btn-sm btn-outline-primary mb-3">🔍 Review</a>

            <!-- Buttons -->
            <div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
              <form action="delete_application" method="POST" onsubmit="return confirm('Are you sure you want to delete this application?');" class="d-inline">
                @csrf
                <input type="hidden" name="user_id" value="{{ (int)$user['id'] }}">
                <button type="submit" class="btn btn-danger btn-sm" aria-label="Delete application for {{ $user['full_name'] }}">🗑️ Delete</button>
              </form>
              <a href="export_pdf?user_id={{ $user['id'] }}" class="btn btn-success btn-sm">📄 Export as PDF</a>
              <a href="download_application?user_id={{ $user['id'] }}" class="btn btn-success btn-sm">📦 Download Application Package</a>
            </div>

            <!-- Sections - Using pre-loaded data (no N+1 queries!) -->
            <strong>Personal Information:</strong><br>
            @php $personal = $personalData[$user['id']] ?? null; @endphp
            @if ($personal)
              @foreach ($personal as $key => $value)
                @if (!in_array($key, ['id', 'user_id', 'created_at']))
                  <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}<br>
                @endif
              @endforeach
            @else
              <em>No personal info provided.</em><br>
            @endif

            <hr>

            <strong>Education Records:</strong><br>
            @php $edu_records = $educationData[$user['id']] ?? []; @endphp
            @if ($edu_records)
              <ul>
                @foreach ($edu_records as $record)
                  <li><strong>{{ (string)$record['entry_type'] }}</strong>: {{ (string)$record['education_level'] }} in {{ (string)$record['field_of_study'] }} from {{ (string)$record['institute_name'] }} ({{ (string)$record['from_date'] }} to {{ (string)$record['to_date'] }})</li>
                @endforeach
              </ul>
            @else
              <em>No education records found.</em><br>
            @endif

            <hr>

            <strong>Uploaded Documents:</strong><br>
            @php $files = $documentsData[$user['id']] ?? []; @endphp
            @if ($files)
              <ul>
                @foreach ($files as $doc)
                  <li><a href='{{ url('/admin/document') }}?p={{ urlencode($doc['file_path']) }}' target='_blank' rel='noopener noreferrer'>{{ $doc['file_name'] }}</a></li>
                @endforeach
              </ul>
            @else
              <em>No documents uploaded.</em><br>
            @endif

          </div>
        </div>
      </div>
    @endforeach
  </div>
  @endif

  <!-- Pagination -->
  @php
  // Build a reusable query-string fragment that preserves search + filter
  $paginationParams = '';
  if (!empty($search))  $paginationParams .= '&search=' . urlencode($search);
  if (!empty($filter))  $paginationParams .= '&filter=' . urlencode($filter);
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
