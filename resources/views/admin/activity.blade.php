@extends('admin.layout')
@section('content')
<div class="row g-3 mb-3">
  <div class="col-sm-4"><div class="card"><div class="card-body"><div class="text-muted small">Failed sign-ins (24 h)</div><div class="fs-3 fw-bold">{{ $failed24h }}</div></div></div></div>
  <div class="col-sm-4"><div class="card"><div class="card-body"><div class="text-muted small">Locked-out attempts (24 h)</div><div class="fs-3 fw-bold">{{ $locked24h }}</div></div></div></div>
  <div class="col-sm-4"><div class="card"><div class="card-body"><div class="text-muted small">Entries shown</div><div class="fs-3 fw-bold">{{ count($events) }}</div></div></div></div>
</div>
<div class="card">
  <div class="card-body table-responsive">
    <table class="table table-sm table-striped align-middle mb-0">
      <thead><tr><th>Time (UTC)</th><th>Event</th><th>User</th><th>IP</th><th>Details</th></tr></thead>
      <tbody>
      @forelse ($events as $e)
        <tr>
          <td class="text-nowrap">{{ $e['t'] }}</td>
          <td><span class="badge text-bg-{{ str_starts_with($e['event'], 'login.failed') || str_starts_with($e['event'], 'login.locked') || str_starts_with($e['event'], 'admin.denied') ? 'danger' : (str_starts_with($e['event'], 'admin.') ? 'primary' : 'secondary') }}">{{ $e['event'] }}</span></td>
          <td>{{ $e['user'] ?? '-' }}</td>
          <td>{{ $e['ip'] ?? '-' }}</td>
          <td class="small text-break">{{ json_encode($e['ctx'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) }}</td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-muted text-center">Nothing recorded yet.</td></tr>
      @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
