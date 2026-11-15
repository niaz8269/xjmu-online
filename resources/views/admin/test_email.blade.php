@extends('admin.layout')
@section('content')
<div style="max-width:860px">

  <!-- Environment info -->
  <div class="card mb-4">
    <div class="card-header fw-semibold bg-white">⚙️ Environment</div>
    <div class="card-body p-0">
      <table class="table table-sm table-striped mb-0">
        @foreach ($diag as $k => $v)
          <tr>
            <td class="ps-3 text-muted" style="width:220px">{{ $k }}</td>
            <td class="fw-semibold">{{ $v }}</td>
          </tr>
        @endforeach
      </table>
    </div>
  </div>

  <!-- Send form -->
  <div class="card mb-4">
    <div class="card-header fw-semibold bg-white">📤 Send test email</div>
    <div class="card-body">
      <form method="POST">
        @csrf
        <div class="mb-3">
          <label class="form-label fw-semibold">Recipient email</label>
          <input type="email" name="to" class="form-control"
                 value="{{ $post['to'] ?? '' }}"
                 placeholder="e.g. webai.auto@gmail.com" required>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Email type</label>
          <select name="type" class="form-select">
            <option value="verification" {!! ($post['type'] ?? '') === 'verification' ? 'selected' : '' !!}>Verification email</option>
            <option value="reset"        {!! ($post['type'] ?? '') === 'reset'        ? 'selected' : '' !!}>Password reset email</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Send &amp; show SMTP log</button>
      </form>
    </div>
  </div>

  @if ($result !== null)
    <div class="alert alert-{!! $result['ok'] ? 'success' : 'danger' !!} mb-3">
      {{ $result['msg'] }}
      @if ($result['ok'])
        <br><small>If you don't see it in your inbox, <strong>check the Spam / Junk folder</strong>.
        Look for the SMTP log below — if the last line says <code>250 OK</code> it was accepted by the mail server.</small>
      @endif
    </div>
  @endif

  @if ($smtpLog !== '')
    <div class="d-flex justify-content-between align-items-center mb-1">
      <h6 class="mb-0">Full SMTP conversation</h6>
      <small class="text-muted">Scroll right if lines are long</small>
    </div>
    <pre class="bg-dark text-light p-3 rounded" style="font-size:11px;max-height:600px;overflow:auto;white-space:pre">{{ $smtpLog }}</pre>
  @endif

  <div class="alert alert-info mt-3 small">
    <strong>How to read the SMTP log:</strong><br>
    Lines starting with <code>CLIENT -&gt;</code> are commands your server sent.<br>
    Lines starting with <code>SERVER -&gt;</code> are responses from the SMTP server.<br>
    A response of <code>250</code> means success. <code>535</code> = wrong credentials. <code>5xx</code> = permanent failure. <code>4xx</code> = temporary failure.<br>
    If the last server line is <code>250 OK</code> but email never arrives → deliverability issue (iPage IP reputation / Gmail spam filter).
  </div>

</div>

@endsection
