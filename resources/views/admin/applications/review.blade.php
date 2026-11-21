@extends('admin.layout')
@section('content')

@php
  $status = (string) ($app['status'] ?? '');
  $submitted = !empty($app['is_submitted']);
  $badge = ['Approved' => 'success', 'Rejected' => 'danger', 'Pending' => 'warning'][$status] ?? 'secondary';
  $uid = (int) $app['user_id'];
  $label = fn (string $key) => ucfirst(str_replace('_', ' ', $key));
  $groups = [
      'Identity' => ['given_name', 'surname', 'chinese_name', 'dob', 'gender', 'marital_status', 'country_of_birth', 'city_of_birth', 'religion', 'nationality', 'native_language'],
      'Passport' => ['passport_no', 'passport_expiry'],
      'Contact' => ['tel', 'email', 'alternate_email', 'wechat_id', 'skype_no', 'present_address', 'permanent_address'],
      'Emergency contact' => ['emergency_contact_name', 'emergency_gender', 'emergency_relationship', 'emergency_tel', 'emergency_email', 'emergency_address'],
  ];
  $shown = array_merge(['id', 'user_id', 'created_at'], ...array_values($groups));
  $wa = preg_replace('/\D+/', '', (string) ($personal['tel'] ?? ''));
@endphp

@if (!empty($flash))
  <div class="alert alert-info alert-dismissible fade show" role="alert">{{ $flash }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
@endif

<p><a href="applications" class="text-decoration-none">← All applications</a></p>

<!-- Header -->
<div class="card mb-3"><div class="card-body d-flex flex-wrap align-items-center gap-3">
  <div class="me-auto">
    <div class="h5 mb-1">Applicant: {{ (string)$app['full_name'] }} <span class="badge text-bg-{{ $badge }} align-middle ms-1">{{ $status !== '' ? $status : 'Not set' }}</span></div>
    <div class="small text-muted">
      {{ (string)$app['email'] }}
      @if (!empty($app['degree_applying_for']) || !empty($app['major_applying_for'])) · {{ trim(($app['degree_applying_for'] ?? '') . ' ' . ($app['major_applying_for'] ?? '')) }} @endif
      · {{ $submitted ? 'Submitted ' . substr((string) ($app['submitted_at'] ?? ''), 0, 10) : 'Not submitted yet' }}
    </div>
  </div>
  <a href="mailto:{{ (string)$app['email'] }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-envelope me-1"></i>Email</a>
  @if (strlen($wa) >= 8)<a href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline-success"><i class="bi bi-whatsapp me-1"></i>WhatsApp</a>@endif
  <a href="export_pdf?user_id={{ $uid }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-earmark-pdf me-1"></i>PDF</a>
  <a href="download_application?user_id={{ $uid }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-file-earmark-zip me-1"></i>Full package</a>
</div></div>

<div class="row g-3">
  <div class="col-lg-7">

    <div class="card mb-3"><div class="card-body">
      <h2 class="h6 text-uppercase text-muted mb-3">Personal information</h2>
      @if ($personal)
        @foreach ($groups as $title => $keys)
          <h3 class="h6 mt-3 mb-2">{{ $title }}</h3>
          <dl class="row small mb-0">
            @foreach ($keys as $key)
              @if (array_key_exists($key, $personal))
                <dt class="col-sm-4 text-muted fw-normal">{{ $label($key) }}</dt><dd class="col-sm-8">{{ ($personal[$key] ?? '') !== '' ? $personal[$key] : '—' }}</dd>
              @endif
            @endforeach
          </dl>
        @endforeach
        @php $rest = array_diff_key($personal, array_flip($shown)); @endphp
        @if ($rest)
          <h3 class="h6 mt-3 mb-2">Other</h3>
          <dl class="row small mb-0">
            @foreach ($rest as $key => $value)
              <dt class="col-sm-4 text-muted fw-normal">{{ $label($key) }}</dt><dd class="col-sm-8">{{ ($value ?? '') !== '' ? $value : '—' }}</dd>
            @endforeach
          </dl>
        @endif
      @else
        <em>No personal info provided.</em>
      @endif
    </div></div>

    <div class="card mb-3"><div class="card-body">
      <h2 class="h6 text-uppercase text-muted mb-3">Education</h2>
      @if ($edu_records)
        <div class="table-responsive"><table class="table table-sm align-middle mb-0">
          <thead><tr><th>Type</th><th>Level</th><th>Field</th><th>Institute</th><th>Years</th></tr></thead>
          <tbody>
            @foreach ($edu_records as $record)
              <tr><td>{{ (string)$record['entry_type'] }}</td><td>{{ (string)$record['education_level'] }}</td><td>{{ (string)$record['field_of_study'] }}</td>
                <td>{{ (string)$record['institute_name'] }}</td><td class="text-nowrap">{{ (string)($record['from_date'] ?? '') }} – {{ (string)($record['to_date'] ?? '') }}</td></tr>
            @endforeach
          </tbody>
        </table></div>
      @else
        <em>No education records found.</em>
      @endif
    </div></div>

    <div class="card mb-3"><div class="card-body">
      <h2 class="h6 text-uppercase text-muted mb-3">Uploaded documents ({{ count($documents) }})</h2>
      @if ($documents)
        <ul class="list-group list-group-flush">
          @foreach ($documents as $doc)
            <li class="list-group-item px-0 d-flex align-items-center gap-2">
              <i class="bi bi-file-earmark-text text-muted"></i>
              <span class="small text-muted" style="min-width:11rem">{{ $documentNames[(int) ($doc['document_id'] ?? 0)] ?? 'Document ' . ($doc['document_id'] ?? '') }}</span>
              <a href='{{ url('/admin/document') }}?p={{ urlencode($doc['file_path']) }}' target='_blank' rel='noopener noreferrer'>{{ $doc['file_name'] }}</a>
            </li>
          @endforeach
        </ul>
        @if ($missingDocuments)
          <p class="small text-danger mt-3 mb-0"><strong>Missing required documents:</strong> {{ implode(', ', $missingDocuments) }}</p>
        @endif
      @else
        <em>No documents uploaded.</em>
      @endif
    </div></div>
  </div>

  <div class="col-lg-5">

    <!-- Approve / Reject Form -->
    <form method="POST" class="card mb-3"><div class="card-body">
      @csrf
      <h2 class="h6 text-uppercase text-muted mb-3">Decision</h2>
      <div class="mb-3">
        <label for="status" class="form-label small mb-1">Status</label>
        <select class="form-select" name="status" id="status" required>
          <option value="">-- Select --</option>
          <option value="Pending" {{ $app['status'] === 'Pending' ? 'selected' : '' }}>Pending</option>
          <option value="Approved" {{ $app['status'] === 'Approved' ? 'selected' : '' }}>Approved</option>
          <option value="Rejected" {{ $app['status'] === 'Rejected' ? 'selected' : '' }}>Rejected</option>
        </select>
      </div>
      <div class="mb-3">
        <label for="admin_remark" class="form-label small mb-1">Remark shown to the applicant (optional)</label>
        <textarea name="admin_remark" id="admin_remark" class="form-control" rows="3">{{ $app['admin_remark'] }}</textarea>
        <div class="form-text">{{ $notifyEnabled ? 'The applicant is emailed when the status becomes Approved or Rejected, or when you add a remark.' : 'The applicant sees this in the portal. Emails to applicants are switched off.' }}</div>
      </div>
      <button type="submit" class="btn btn-primary">💾 Save Status</button>
    </div></form>

    <div class="card mb-3"><div class="card-body">
      <h2 class="h6 text-uppercase text-muted mb-3">Internal notes and history</h2>
      <form method="POST" class="mb-3">
        @csrf
        <input type="hidden" name="action" value="note">
        <label class="visually-hidden" for="note">Add an internal note</label>
        <textarea id="note" name="note" class="form-control" rows="2" maxlength="5000" placeholder="Internal note. The applicant never sees this." required></textarea>
        <button class="btn btn-sm btn-outline-primary mt-2" type="submit">Add note</button>
      </form>
      @forelse ($history as $h)
        <div class="border-start border-3 ps-3 mb-3">
          <div class="small text-muted">{{ $h['admin_name'] ?: 'System' }} · {{ substr((string) $h['created_at'], 0, 16) }}</div>
          @if ($h['type'] === 'status')
            <div><strong>Status:</strong> {{ $h['old_status'] ?: 'Not set' }} → {{ $h['new_status'] ?: 'Not set' }}</div>
            @if (!empty($h['body']))<div class="small">Remark: {{ $h['body'] }}</div>@endif
          @else
            <div style="white-space:pre-wrap">{{ $h['body'] }}</div>
          @endif
        </div>
      @empty
        <p class="text-muted small mb-0">No notes or changes recorded yet.</p>
      @endforelse
    </div></div>

    <form action="delete_application" method="POST" class="text-end"
          onsubmit="return prompt('This permanently deletes the application and its uploaded documents. It cannot be undone. Type DELETE to confirm.') === 'DELETE';">
      @csrf
      <input type="hidden" name="user_id" value="{{ $uid }}">
      <button type="submit" class="btn btn-sm btn-outline-danger">Delete this application</button>
    </form>
  </div>
</div>

@endsection
