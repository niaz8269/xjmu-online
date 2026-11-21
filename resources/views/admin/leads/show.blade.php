@extends('admin.layout')
@section('content')

@if (!empty($flash))
  <div class="alert alert-info alert-dismissible fade show" role="alert">{{ $flash }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
@endif

@php
  $wa = preg_replace('/\D+/', '', (string) $lead['phone']);
@endphp

<p><a href="leads" class="text-decoration-none">← All leads</a></p>

<div class="row g-3">
  <div class="col-lg-5">
    <div class="card mb-3"><div class="card-body">
      <h2 class="h6 text-uppercase text-muted mb-3">Contact</h2>
      <dl class="row mb-0 small">
        <dt class="col-4">Email</dt><dd class="col-8">@if ($lead['email'])<a href="mailto:{{ $lead['email'] }}">{{ $lead['email'] }}</a>@else — @endif</dd>
        <dt class="col-4">Phone</dt><dd class="col-8">{{ $lead['phone'] ?: '—' }}
          @if (strlen($wa) >= 8) <a class="ms-2" href="https://wa.me/{{ $wa }}" target="_blank" rel="noopener noreferrer"><i class="bi bi-whatsapp"></i> WhatsApp</a>@endif</dd>
        <dt class="col-4">Nationality</dt><dd class="col-8">{{ $lead['nationality'] ?: '—' }}</dd>
        <dt class="col-4">Interest</dt><dd class="col-8">{{ $lead['program'] ?: '—' }}</dd>
        <dt class="col-4">Source</dt><dd class="col-8">{{ $sources[$lead['source']] ?? $lead['source'] }}</dd>
        @if ($lead['subject'])<dt class="col-4">Subject</dt><dd class="col-8">{{ $lead['subject'] }}</dd>@endif
      </dl>
      @if ($lead['email'])
        <form method="post" class="mt-3" onsubmit="return confirm('Send a reminder email to {{ $lead['email'] }} now?');">
          @csrf
          <input type="hidden" name="action" value="remind">
          <button class="btn btn-sm btn-outline-primary" type="submit"><i class="bi bi-envelope-paper me-1"></i>Send reminder email</button>
        </form>
      @endif
      @if ($lead['message'])
        <hr>
        <h3 class="h6 text-uppercase text-muted">First message</h3>
        <p class="mb-0 small" style="white-space:pre-wrap">{{ $lead['message'] }}</p>
      @endif
      @if ($lead['user_id'])
        <hr>
        <p class="small mb-2"><strong>Portal account:</strong>
          @if ($application && !empty($application['is_submitted']))
            application submitted{{ $application['status'] ? ' (' . $application['status'] . ')' : '' }}
          @elseif ($application)
            application started, not submitted
          @else
            registered, no application started
          @endif
        </p>
        @if ($application)<a href="review_application?id={{ (int) $lead['user_id'] }}" class="btn btn-sm btn-outline-primary">Open application</a>@endif
      @endif
    </div></div>

    <div class="card"><div class="card-body">
      <h2 class="h6 text-uppercase text-muted mb-3">Status and follow-up</h2>
      <form method="post">
        @csrf
        <input type="hidden" name="action" value="update">
        <div class="row g-2">
          <div class="col-6"><label class="form-label small mb-1" for="st">Status</label>
            <select id="st" name="status" class="form-select form-select-sm">
              @foreach ($statuses as $st)<option value="{{ $st }}" @selected($lead['status'] === $st)>{{ $st }}</option>@endforeach
            </select></div>
          <div class="col-6"><label class="form-label small mb-1" for="as">Assigned to</label>
            <select id="as" name="assigned_to" class="form-select form-select-sm">
              <option value="">Nobody</option>
              @foreach ($admins as $aid => $aname)<option value="{{ $aid }}" @selected((int) $lead['assigned_to'] === (int) $aid)>{{ $aname }}</option>@endforeach
            </select></div>
          <div class="col-6"><label class="form-label small mb-1" for="fu">Next follow-up</label><input id="fu" type="date" name="next_follow_up" value="{{ $lead['next_follow_up'] }}" class="form-control form-control-sm"></div>
          <div class="col-6"><label class="form-label small mb-1" for="ph">Phone / WhatsApp</label><input id="ph" name="phone" value="{{ $lead['phone'] }}" class="form-control form-control-sm" maxlength="60"></div>
          <div class="col-6"><label class="form-label small mb-1" for="na">Nationality</label><input id="na" name="nationality" value="{{ $lead['nationality'] }}" class="form-control form-control-sm" maxlength="100"></div>
          <div class="col-6"><label class="form-label small mb-1" for="pr">Program of interest</label><input id="pr" name="program" value="{{ $lead['program'] }}" class="form-control form-control-sm" maxlength="160"></div>
        </div>
        <button class="btn btn-sm btn-primary mt-3" type="submit">Save</button>
      </form>
    </div></div>
  </div>

  <div class="col-lg-7">
    <div class="card"><div class="card-body">
      <h2 class="h6 text-uppercase text-muted mb-3">Notes and history</h2>
      <form method="post" class="mb-4">
        @csrf
        <input type="hidden" name="action" value="note">
        <label class="visually-hidden" for="nt">Add a note</label>
        <textarea id="nt" name="note" class="form-control" rows="3" maxlength="5000" placeholder="What was said, what was promised, what to do next…" required></textarea>
        <button class="btn btn-sm btn-primary mt-2" type="submit">Add note</button>
      </form>

      @forelse ($notes as $n)
        <div class="border-start border-3 ps-3 mb-3">
          <div class="small text-muted">{{ $n['admin_name'] ?: 'System' }} · {{ substr((string) $n['created_at'], 0, 16) }}</div>
          <div style="white-space:pre-wrap">{{ $n['note'] }}</div>
        </div>
      @empty
        <p class="text-muted small mb-0">No notes yet.</p>
      @endforelse
    </div></div>

    <form method="post" class="mt-3 text-end" onsubmit="return prompt('This permanently deletes the lead and its notes. Type DELETE to confirm.') === 'DELETE';">
      @csrf
      <input type="hidden" name="action" value="delete">
      <button class="btn btn-sm btn-outline-danger" type="submit">Delete this lead</button>
    </form>
  </div>
</div>

@endsection
