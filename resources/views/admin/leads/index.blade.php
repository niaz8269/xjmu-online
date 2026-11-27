@extends('admin.layout')
@section('content')

@if (!empty($flash))
  <div class="alert alert-info alert-dismissible fade show" role="alert">{{ $flash }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
@endif

@php
  $qs = function (array $over = []) use ($f) {
      $p = array_filter(array_merge($f, $over), fn ($v) => $v !== '' && $v !== null);
      return $p ? '?' . http_build_query($p) : '?';
  };
  $badge = ['New' => 'primary', 'Contacted' => 'info', 'Interested' => 'warning', 'Applied' => 'success', 'Enrolled' => 'success', 'Not interested' => 'secondary'];
@endphp

<form method="post" class="card mb-3"><div class="card-body d-flex flex-wrap align-items-center gap-2">
  @csrf
  <input type="hidden" name="action" value="notify_to">
  <label class="small fw-semibold mb-0" for="notifyTo"><i class="bi bi-envelope-at me-1"></i>Website enquiries are emailed to</label>
  <input id="notifyTo" type="email" name="notify_to" value="{{ $notifyTo }}" class="form-control form-control-sm" style="max-width:280px" required maxlength="190">
  <button class="btn btn-sm btn-primary" type="submit">Save</button>
  <span class="small text-muted">Home pop-up, contact, partner and event forms.</span>
</div></form>

<!-- Summary -->
<div class="row g-3 mb-3">
  <div class="col-6 col-lg-3"><a class="card h-100 text-decoration-none" href="{{ $qs(['status' => 'New', 'due' => '']) }}"><div class="card-body"><div class="stat-value">{{ number_format($counts['New'] ?? 0) }}</div><div class="small text-muted">New, not contacted yet</div></div></a></div>
  <div class="col-6 col-lg-3"><a class="card h-100 text-decoration-none" href="{{ $qs(['due' => 'today', 'status' => '']) }}"><div class="card-body"><div class="stat-value">{{ number_format($dueToday) }}</div><div class="small text-muted">Follow-ups due today</div></div></a></div>
  <div class="col-6 col-lg-3"><a class="card h-100 text-decoration-none" href="{{ $qs(['due' => 'overdue', 'status' => '']) }}"><div class="card-body"><div class="stat-value {{ $overdue ? 'text-danger' : '' }}">{{ number_format($overdue) }}</div><div class="small text-muted">Overdue follow-ups</div></div></a></div>
  <div class="col-6 col-lg-3"><a class="card h-100 text-decoration-none" href="leads"><div class="card-body"><div class="stat-value">{{ number_format($allCount) }}</div><div class="small text-muted">All leads</div></div></a></div>
</div>

<div class="card"><div class="card-body">

  <div class="d-flex flex-wrap gap-2 mb-3">
    <a href="{{ $qs(['status' => '']) }}" class="btn btn-sm {{ $f['status'] === '' ? 'btn-primary' : 'btn-outline-secondary' }}">All</a>
    @foreach ($statuses as $st)
      <a href="{{ $qs(['status' => $st]) }}" class="btn btn-sm {{ $f['status'] === $st ? 'btn-primary' : 'btn-outline-secondary' }}">{{ $st }} <span class="badge text-bg-light ms-1">{{ $counts[$st] ?? 0 }}</span></a>
    @endforeach
    <div class="ms-auto d-flex gap-2">
      <button class="btn btn-sm btn-xjmu" type="button" data-bs-toggle="collapse" data-bs-target="#addLead"><i class="bi bi-plus-lg me-1"></i>Add lead</button>
      <form method="post" onsubmit="return confirm('Add every registered student who has not submitted an application as a lead? Students who are already leads are skipped.');">
        @csrf
        <input type="hidden" name="action" value="import_registered">
        <button class="btn btn-sm btn-outline-primary" type="submit"><i class="bi bi-person-plus me-1"></i>Import registered students</button>
      </form>
    </div>
  </div>

  <!-- Add lead -->
  <div class="collapse mb-3" id="addLead">
    <form method="post" class="border rounded-3 p-3 bg-body-tertiary">
      @csrf
      <input type="hidden" name="action" value="create">
      <div class="row g-2">
        <div class="col-md-4"><label class="form-label small mb-1" for="ln">Name *</label><input id="ln" name="name" class="form-control form-control-sm" required maxlength="160"></div>
        <div class="col-md-4"><label class="form-label small mb-1" for="le">Email</label><input id="le" type="email" name="email" class="form-control form-control-sm" maxlength="190"></div>
        <div class="col-md-4"><label class="form-label small mb-1" for="lp">Phone / WhatsApp</label><input id="lp" name="phone" class="form-control form-control-sm" maxlength="60"></div>
        <div class="col-md-3"><label class="form-label small mb-1" for="lna">Nationality</label><input id="lna" name="nationality" class="form-control form-control-sm" maxlength="100"></div>
        <div class="col-md-3"><label class="form-label small mb-1" for="lpr">Program of interest</label><input id="lpr" name="program" class="form-control form-control-sm" maxlength="160" placeholder="e.g. MBBS"></div>
        <div class="col-md-3"><label class="form-label small mb-1" for="ls">Where from</label>
          <select id="ls" name="source" class="form-select form-select-sm">
            @foreach (['manual', 'whatsapp', 'phone', 'agent'] as $src)<option value="{{ $src }}">{{ $sources[$src] }}</option>@endforeach
          </select></div>
        <div class="col-md-3"><label class="form-label small mb-1" for="lf">Next follow-up</label><input id="lf" type="date" name="next_follow_up" class="form-control form-control-sm" min="{{ $today }}"></div>
        <div class="col-12"><label class="form-label small mb-1" for="lm">Notes</label><textarea id="lm" name="message" class="form-control form-control-sm" rows="2" maxlength="5000"></textarea></div>
      </div>
      <button class="btn btn-sm btn-primary mt-3" type="submit">Save lead</button>
    </form>
  </div>

  <!-- Filters -->
  <form method="get" class="d-flex flex-wrap gap-2 align-items-center mb-3">
    @if ($f['status'] !== '')<input type="hidden" name="status" value="{{ $f['status'] }}">@endif
    <input type="text" name="q" value="{{ $f['q'] }}" class="form-control form-control-sm" placeholder="Search name, email, phone, nationality…" style="max-width:280px" aria-label="Search leads">
    <select name="source" class="form-select form-select-sm" style="max-width:190px" aria-label="Source">
      <option value="">Any source</option>
      @foreach ($sources as $key => $label)<option value="{{ $key }}" @selected($f['source'] === $key)>{{ $label }}</option>@endforeach
    </select>
    <select name="assigned" class="form-select form-select-sm" style="max-width:180px" aria-label="Assigned to">
      <option value="">Anyone</option>
      <option value="none" @selected($f['assigned'] === 'none')>Not assigned</option>
      @foreach ($admins as $aid => $aname)<option value="{{ $aid }}" @selected($f['assigned'] === (string) $aid)>{{ $aname }}</option>@endforeach
    </select>
    <select name="due" class="form-select form-select-sm" style="max-width:170px" aria-label="Follow-up">
      <option value="">Any follow-up</option>
      <option value="today" @selected($f['due'] === 'today')>Due today</option>
      <option value="overdue" @selected($f['due'] === 'overdue')>Overdue</option>
    </select>
    <button class="btn btn-sm btn-primary" type="submit">Filter</button>
    @if (array_filter($f))<a href="leads" class="btn btn-sm btn-outline-secondary">Reset</a>@endif
    <a href="{{ $qs(['export' => 'csv']) }}" class="btn btn-sm btn-outline-success ms-auto"><i class="bi bi-download me-1"></i>Export this list (Excel/CSV)</a>
  </form>

  <p class="small text-muted mb-2">Showing {{ count($leads) }} of {{ number_format($total) }} lead{{ $total === 1 ? '' : 's' }}</p>

  @if (empty($leads))
    <div class="alert alert-info mb-0">
      <strong>No leads here yet.</strong>
      Website enquiries are saved here automatically. You can also add a lead by hand, or import the students who registered but have not applied.
    </div>
  @else
    <form method="post" action="lead-email" id="leadPick">
      @csrf
      <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
        <button type="submit" class="btn btn-sm btn-primary" id="emailPicked" disabled><i class="bi bi-envelope me-1"></i>Email selected (<span id="pickedCount">0</span>)</button>
        <span class="small text-muted">Tick the leads you want to write to. You write the message on the next screen; nothing is sent until you confirm there.</span>
        <span class="ms-auto small">Show
          @foreach ([25, 50, 100] as $n)
            <a href="{{ $qs(['per' => $n === 25 ? '' : $n, 'page' => '']) }}" class="{{ (string) ($f['per'] ?: 25) === (string) $n ? 'fw-bold' : '' }}">{{ $n }}</a>{{ $loop->last ? '' : ' ·' }}
          @endforeach
          per page</span>
      </div>
    <div class="table-responsive">
      <table class="table table-hover align-middle mb-0">
        <thead><tr><th style="width:2rem"><input class="form-check-input" type="checkbox" id="pickAll" aria-label="Select all on this page"></th><th>Name</th><th>Contact</th><th>Interest</th><th>Source</th><th>Status</th><th>Assigned</th><th>Follow-up</th><th></th></tr></thead>
        <tbody>
          @foreach ($leads as $l)
            @php $late = $l['next_follow_up'] && $l['next_follow_up'] < $today && !in_array($l['status'], ['Enrolled', 'Not interested'], true); @endphp
            <tr>
              <td><input class="form-check-input lead-pick" type="checkbox" name="ids[]" value="{{ (int) $l['id'] }}" aria-label="Select {{ $l['name'] }}" @disabled(empty($l['email']))></td>
              <td><a href="lead?id={{ (int) $l['id'] }}" class="fw-semibold text-decoration-none">{{ $l['name'] }}</a><div class="small text-muted">{{ $l['nationality'] }}</div></td>
              <td class="small">{{ $l['email'] }}<div>{{ $l['phone'] }}</div></td>
              <td class="small">{{ $l['program'] ?: ($l['subject'] ?: '—') }}</td>
              <td class="small">{{ $sources[$l['source']] ?? $l['source'] }}</td>
              <td><span class="badge text-bg-{{ $badge[$l['status']] ?? 'secondary' }}">{{ $l['status'] }}</span></td>
              <td class="small">{{ $admins[$l['assigned_to']] ?? '—' }}</td>
              <td class="small {{ $late ? 'text-danger fw-semibold' : '' }}">{{ $l['next_follow_up'] ?: '—' }}{{ $late ? ' (overdue)' : ($l['next_follow_up'] === $today ? ' (today)' : '') }}</td>
              <td class="text-end"><a href="lead?id={{ (int) $l['id'] }}" class="btn btn-sm btn-outline-primary">Open</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    </form>

    @if ($pages > 1)
      <nav aria-label="Leads pages" class="mt-3">
        <ul class="pagination pagination-sm justify-content-center mb-0">
          <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}"><a class="page-link" href="{{ $qs(['page' => $page - 1]) }}">Previous</a></li>
          @for ($i = max(1, $page - 2); $i <= min($pages, $page + 2); $i++)
            <li class="page-item {{ $i === $page ? 'active' : '' }}"><a class="page-link" href="{{ $qs(['page' => $i]) }}">{{ $i }}</a></li>
          @endfor
          <li class="page-item {{ $page >= $pages ? 'disabled' : '' }}"><a class="page-link" href="{{ $qs(['page' => $page + 1]) }}">Next</a></li>
        </ul>
      </nav>
    @endif
  @endif
</div></div>

@push('scripts')
<script>
  (function () {
    var form = document.getElementById('leadPick');
    if (!form) { return; }
    var boxes = function () { return Array.prototype.slice.call(form.querySelectorAll('.lead-pick:not(:disabled)')); };
    var update = function () {
      var n = boxes().filter(function (b) { return b.checked; }).length;
      document.getElementById('pickedCount').textContent = n;
      document.getElementById('emailPicked').disabled = n === 0;
    };
    document.getElementById('pickAll').addEventListener('change', function () {
      var on = this.checked;
      boxes().forEach(function (b) { b.checked = on; });
      update();
    });
    form.addEventListener('change', update);
    update();
  })();
</script>
@endpush

@endsection
