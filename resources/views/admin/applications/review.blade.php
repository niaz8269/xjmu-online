@extends('admin.layout')
@section('content')

<div class="card"><div class="card-body">

  <div class="card mb-4">
    <div class="card-header fw-bold">Applicant: {{ (string)$app['full_name'] }} ({{ (string)$app['email'] }})</div>
    <div class="card-body">
      <p><strong>Submitted At:</strong> {{ (string)($app['submitted_at'] ?? 'â€”') }}</p>
      <p><strong>Status:</strong> {{ (string)($app['status'] ?? 'Not set') }}</p>
      @if (!empty($app['degree_applying_for']) || !empty($app['major_applying_for']))
        <p><strong>Degree Applying for:</strong> {{ $app['degree_applying_for'] ?? 'â€”' }}</p>
        <p><strong>Major Applying for:</strong> {{ $app['major_applying_for'] ?? 'â€”' }}</p>
      @endif
      @if ($app['status'] === 'Rejected' && !empty($app['admin_remark']))
        <p><strong>Remark:</strong> <span class="text-danger">{{ $app['admin_remark'] }}</span></p>
      @endif
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header fw-bold">Personal Information</div>
    <div class="card-body">
      @if ($personal)
        @foreach ($personal as $key => $value)
          @if (!in_array($key, ['id', 'user_id', 'created_at']))
            <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}<br>
          @endif
        @endforeach
      @else
        <em>No personal info provided.</em>
      @endif
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header fw-bold">Education Records</div>
    <div class="card-body">
      @if ($edu_records)
        <ul>
          @foreach ($edu_records as $record)
            <li><strong>{{ (string)$record['entry_type'] }}</strong>: {{ (string)$record['education_level'] }} in {{ (string)$record['field_of_study'] }} from {{ (string)$record['institute_name'] }} ({{ (string)$record['from_date'] }} to {{ (string)$record['to_date'] }})</li>
          @endforeach
        </ul>
      @else
        <em>No education records found.</em>
      @endif
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header fw-bold">Uploaded Documents</div>
    <div class="card-body">
      @if ($documents)
        <ul>
          @foreach ($documents as $doc)
            <li><a href='{{ url('/admin/document') }}?p={{ urlencode($doc['file_path']) }}' target='_blank' rel='noopener noreferrer'>{{ $doc['file_name'] }}</a></li>
          @endforeach
        </ul>
      @else
        <em>No documents uploaded.</em>
      @endif
    </div>
  </div>

  <!-- Approve / Reject Form -->
  <form method="POST" class="border rounded p-4 bg-white shadow-sm">
    @csrf
    <h5 class="mb-3">Update Application Status</h5>
    <div class="mb-3">
      <label for="status" class="form-label">Status:</label>
      <select class="form-select" name="status" id="status" required>
        <option value="">-- Select --</option>
        <option value="Pending" {{ $app['status'] === 'Pending' ? 'selected' : '' }}>Pending</option>
        <option value="Approved" {{ $app['status'] === 'Approved' ? 'selected' : '' }}>Approved</option>
        <option value="Rejected" {{ $app['status'] === 'Rejected' ? 'selected' : '' }}>Rejected</option>
      </select>
    </div>
    <div class="mb-3">
      <label for="admin_remark" class="form-label">Admin Remark (optional for rejection):</label>
      <textarea name="admin_remark" id="admin_remark" class="form-control" rows="3">{{ $app['admin_remark'] }}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">💾 Save Status</button>
  </form>
</div></div>

@endsection
