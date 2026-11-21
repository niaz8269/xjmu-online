@extends('admin.layout')
@section('content')

@if (!empty($flash))
  <div class="alert alert-info alert-dismissible fade show" role="alert">{{ $flash }}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>
@endif

<p><a href="leads" class="text-decoration-none">← All leads</a></p>

@if (empty($recipients))
  <div class="alert alert-info">
    <strong>No leads selected.</strong> Go back to the leads list, tick the people you want to write to, and press "Email selected".
  </div>
@else
<form method="post" id="leadEmailForm">
  @csrf
  <input type="hidden" name="action" value="send">

  <div class="row g-3">
    <div class="col-lg-5">
      <div class="card"><div class="card-body">
        <h2 class="h6 text-uppercase text-muted mb-2">Recipients</h2>
        <p class="small text-muted mb-2"><strong>{{ $sendable }}</strong> will receive the email{{ $skipped ? ', ' . $skipped . ' cannot (no valid email address)' : '' }}. Untick anyone you do not want to include. At most {{ $max }} emails are sent at a time.</p>
        <div style="max-height: 420px; overflow: auto;" class="border rounded-3">
          <table class="table table-sm align-middle mb-0">
            <tbody>
              @foreach ($recipients as $r)
                <tr class="{{ $r['ok'] ? '' : 'text-muted' }}">
                  <td style="width: 2rem">
                    <input class="form-check-input" type="checkbox" name="ids[]" value="{{ (int) $r['id'] }}" id="r{{ (int) $r['id'] }}" @checked($r['ok']) @disabled(! $r['ok'])>
                  </td>
                  <td><label for="r{{ (int) $r['id'] }}" class="mb-0">{{ $r['name'] }}<div class="small text-muted">{{ $r['ok'] ? $r['email'] : 'No valid email address' }}</div></label></td>
                  <td class="small text-muted text-end">{{ $r['status'] }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div></div>
    </div>

    <div class="col-lg-7">
      <div class="card"><div class="card-body">
        <h2 class="h6 text-uppercase text-muted mb-3">Your email</h2>
        <div class="mb-3">
          <label class="form-label small mb-1" for="subject">Subject</label>
          <input id="subject" name="subject" class="form-control" maxlength="150" required value="{{ $subject }}">
        </div>
        <div class="mb-2">
          <label class="form-label small mb-1" for="message">Message</label>
          <textarea id="message" name="message" class="form-control" rows="11" maxlength="5000" required placeholder="Write your message here.">{{ $message }}</textarea>
          <div class="form-text">
            Each email starts with "Dear [their name]," and ends with your name and "International Students Admissions, Xinjiang Medical University", so write only the message itself.
            A short line telling people how to stop further emails is added at the bottom.
          </div>
        </div>
        <div class="form-check my-3">
          <input class="form-check-input" type="checkbox" id="confirm" name="confirm" value="1" required>
          <label class="form-check-label" for="confirm">I have checked the recipients and the message.</label>
        </div>
        <button type="submit" class="btn btn-xjmu"><i class="bi bi-send me-1"></i>Send to the ticked leads</button>
        <a href="leads" class="btn btn-outline-secondary ms-2">Cancel</a>
        <p class="small text-muted mt-3 mb-0">Sending is recorded in each lead's history and on the Activity &amp; Security page. Emails already sent cannot be recalled.</p>
      </div></div>
    </div>
  </div>
</form>

@push('scripts')
<script>
  document.getElementById('leadEmailForm').addEventListener('submit', function (e) {
    var n = this.querySelectorAll('input[name="ids[]"]:checked').length;
    if (!n) { e.preventDefault(); alert('Tick at least one recipient.'); return; }
    if (!confirm('Send this email to ' + n + ' lead' + (n === 1 ? '' : 's') + ' now? This cannot be undone.')) { e.preventDefault(); return; }
    var btn = this.querySelector('button[type="submit"]');
    btn.disabled = true; btn.textContent = 'Sending, please wait...';
  });
</script>
@endpush
@endif

@endsection
