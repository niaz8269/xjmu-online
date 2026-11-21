@extends('admin.layout')
@section('content')

<style>
  .qe-table { --qe-line: #e4e8ee; }
  .qe-table th { white-space: nowrap; }
  .qe-table td { vertical-align: middle; padding-block: .85rem; }
  .qe-person { min-width: 220px; max-width: 300px; }
  .qe-person .qe-name { font-weight: 700; color: #16202e; line-height: 1.25; }
  .qe-person .qe-sub { color: #667085; font-size: .82rem; overflow-wrap: anywhere; line-height: 1.35; }
  .qe-prog { min-width: 170px; max-width: 240px; font-size: .9rem; line-height: 1.35; }
  .qe-prog .qe-sub { color: #667085; font-size: .82rem; }
  .qe-decide { min-width: 420px; }
  .qe-form { display: flex; gap: .5rem; align-items: center; }
  .qe-form select { flex: 0 0 120px; }
  .qe-form input[type=text] { flex: 1 1 220px; min-width: 160px; }
  .qe-form .btn { flex: 0 0 auto; white-space: nowrap; }
  .qe-open { white-space: nowrap; }
  @media (max-width: 1199.98px) {
    .qe-form { flex-wrap: wrap; }
    .qe-decide { min-width: 280px; }
  }
</style>

<p><a href="applications" class="text-decoration-none">← All applications</a></p>
<div class="card"><div class="card-body">
    <p class="text-muted small mb-3">Quick editing: set the status and a remark for several applications without opening each one. The remark is shown to the applicant in the portal.</p>

    @if (!empty($message))
        <div class="alert alert-info alert-dismissible fade show" role="alert">
            {{ $message }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (empty($applications))
        <div class="alert alert-info">
            <h5>No applications found</h5>
            <p>There are currently no applications in the system.</p>
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 qe-table">
                <thead>
                    <tr>
                        <th>Applicant</th>
                        <th>Programme</th>
                        <th>Submitted</th>
                        <th>Status and remark</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applications as $index => $row)
                        @php
                            $submitted = !empty($row['is_submitted']) || in_array((string)($row['status'] ?? ''), ['Pending', 'Approved', 'Rejected', 'Submitted'], true);
                            $formId = 'status-form-' . (int) $row['id'];
                            $programme = trim((string) ($row['degree_applying_for'] ?? ''));
                            $major = trim((string) ($row['major_applying_for'] ?? ''));
                        @endphp
                        <tr>
                            <td class="qe-person">
                                <div class="qe-name">{{ $row['full_name'] ?? '' }}</div>
                                <div class="qe-sub">{{ $row['email'] ?? '' }}</div>
                                @if (!empty($row['phone']))<div class="qe-sub">{{ $row['phone'] }}</div>@endif
                            </td>
                            <td class="qe-prog">
                                @if ($major !== '')<div>{{ $major }}</div>@endif
                                <div class="{{ $major !== '' ? 'qe-sub' : '' }}">{{ $programme !== '' ? $programme : ($major === '' ? '—' : '') }}</div>
                            </td>
                            <td>
                                <span class="badge text-bg-{{ $submitted ? 'success' : 'warning' }}">{{ $submitted ? 'Submitted' : 'Draft' }}</span>
                            </td>
                            <td class="qe-decide">
                                <form method="post" id="{{ $formId }}" class="qe-form">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ (int) $row['id'] }}">
                                    <select name="app_status" class="form-select form-select-sm" aria-label="Review status for {{ $row['full_name'] ?? '' }}">
                                        <option value="">{{ !empty($row['status']) && !in_array($row['status'], $reviewStatuses, true) ? $row['status'] : 'No change' }}</option>
                                        @foreach ($reviewStatuses as $st)
                                            <option value="{{ $st }}" @selected(($row['status'] ?? '') === $st)>{{ $st }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" name="status" class="form-control form-control-sm" maxlength="500"
                                           value="{{ $row['admin_remark'] ?? '' }}" placeholder="Remark for the applicant (optional)"
                                           aria-label="Remark for {{ $row['full_name'] ?? '' }}">
                                    <button type="submit" class="btn btn-sm btn-primary">Save</button>
                                </form>
                            </td>
                            <td class="text-end qe-open">
                                <a href="review_application?id={{ (int) $row['id'] }}" class="btn btn-sm btn-outline-secondary">Open</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if ($totalPages > 1)
        <nav aria-label="Applications pagination">
            <ul class="pagination pagination-sm justify-content-center mt-4 mb-0">
                <li class="page-item{{ ($page <= 1 ? ' disabled' : '') }}">
                    <a class="page-link" href="?page={{ max(1, $page - 1) }}">Previous</a>
                </li>
                @for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++)
                    <li class="page-item{{ ($i == $page ? ' active' : '') }}">
                        <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                    </li>
                @endfor
                <li class="page-item{{ ($page >= $totalPages ? ' disabled' : '') }}">
                    <a class="page-link" href="?page={{ min($totalPages, $page + 1) }}">Next</a>
                </li>
            </ul>
        </nav>
        @endif
    @endif
</div></div>

@endsection
