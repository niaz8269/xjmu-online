@extends('admin.layout')
@section('content')

<style>
        html,
        body {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        .applications-wrap .table {
            width: 100%;
            table-layout: auto;
        }

        .applications-wrap .table th,
        .applications-wrap .table td {
            white-space: normal;
            word-break: break-word;
            vertical-align: middle;
        }

        .applications-wrap .table th.col-index,
        .applications-wrap .table td.col-index,
        .applications-wrap .table th.col-actions,
        .applications-wrap .table td.col-actions,
        .applications-wrap .table th.col-save,
        .applications-wrap .table td.col-save {
            width: 1%;
            white-space: nowrap;
            word-break: normal;
        }

        .status-form {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
            align-items: center;
        }

        .status-form .status-input {
            min-width: 0;
            width: 100%;
            flex: 1 1 260px;
        }

        .col-save .btn {
            white-space: nowrap;
        }

        .table-responsive {
            overflow-x: auto;
        }

        @media (max-width: 991.98px) {
            .applications-wrap .table {
                font-size: 0.9rem;
            }
        }
    </style>
<p><a href="applications" class="text-decoration-none">← All applications</a></p>
<div class="card applications-wrap"><div class="card-body">
    <p class="text-muted small">Quick editing: set the status and the remark for several applications without opening each one.</p>

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
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th class="col-index">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Degree</th>
                        <th>Major</th>
                        <th>Submission</th>
                        <th>Status and remark for the applicant</th>
                        <th class="col-save">Save</th>
                        <th class="col-actions">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($applications as $index => $row)
                        <tr>
                            <td class="col-index">{{ ($offset + $index + 1) }}</td>
                            <td>{{ $row['full_name'] ?? '' }}</td>
                            <td>{{ $row['email'] ?? '' }}</td>
                            <td>{{ $row['phone'] ?? '' }}</td>
                            <td>{{ $row['degree_applying_for'] ?? '' }}</td>
                            <td>{{ $row['major_applying_for'] ?? '' }}</td>
                            <td>
                                @php $submitted = !empty($row['is_submitted']) || in_array((string)($row['status'] ?? ''), ['Pending', 'Approved', 'Rejected', 'Submitted'], true); @endphp
                                <span class="badge bg-{{ $submitted ? 'success' : 'warning' }}">
                                    {{ $submitted ? 'Submitted' : 'Unsubmitted' }}
                                </span>
                            </td>
                            <td>
                                <form method="post" id="status-form-{{ (int)$row['id'] }}" class="status-form">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ (int)$row['id'] }}">
                                    <select name="app_status" class="form-select form-select-sm" style="flex:0 0 130px" aria-label="Review status">
                                        <option value="">{{ !empty($row['status']) && !in_array($row['status'], $reviewStatuses, true) ? $row['status'] : 'No change' }}</option>
                                        @foreach ($reviewStatuses as $st)
                                            <option value="{{ $st }}" @selected(($row['status'] ?? '') === $st)>{{ $st }}</option>
                                        @endforeach
                                    </select>
                                    <input
                                        type="text"
                                        name="status"
                                        class="form-control status-input"
                                        value="{{ $row['admin_remark'] ?? '' }}"
                                        placeholder="Remark shown to the applicant (optional)">
                                </form>
                            </td>
                            <td class="col-save text-center">
                                <button type="submit" form="status-form-{{ (int)$row['id'] }}" class="btn btn-sm btn-primary">Save</button>
                            </td>
                            <td class="col-actions">
                                <a href="review_application?id={{ (int)$row['id'] }}" class="btn btn-sm btn-outline-secondary">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <!-- Pagination Controls -->
        <nav aria-label="Applications pagination">
            <ul class="pagination justify-content-center mt-4">
                <li class="page-item{{ ($page <= 1 ? ' disabled' : '') }}">
                    <a class="page-link" href="?page={{ $page - 1 }}" tabindex="-1">Previous</a>
                </li>
                @for ($i = 1; $i <= $totalPages; $i++)
                    <li class="page-item{{ ($i == $page ? ' active' : '') }}">
                        <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                    </li>
                @endfor
                <li class="page-item{{ ($page >= $totalPages ? ' disabled' : '') }}">
                    <a class="page-link" href="?page={{ $page + 1 }}">Next</a>
                </li>
            </ul>
        </nav>
    @endif
</div></div>

@endsection
