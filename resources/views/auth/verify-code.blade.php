<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <title>Enter your sign-in code | Xinjiang Medical University</title>
  <link rel="icon" href="/favicon.ico" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="{{ get_absolute_url('css/portal.css') }}?v=1">
</head>
<body class="portal">
  <main class="p-wrap" style="max-width: 460px; padding-top: 4rem;">
    <section class="p-card">
      <span class="p-eyebrow">Admin sign-in</span>
      <h1 style="font-size: 1.4rem; margin: .35rem 0 .5rem;">Enter your sign-in code</h1>
      <p style="color: var(--p-muted);">We sent a 6-digit code to <strong>{{ $maskedEmail }}</strong>. It works for 10 minutes.</p>

      @if (!empty($error))
        <div class="alert alert-danger py-2" role="alert">{{ $error }}</div>
      @endif
      @if (!empty($notice))
        <div class="alert alert-info py-2" role="status">{{ $notice }}</div>
      @endif

      <form method="POST" action="{{ get_absolute_url('admin-verify') }}" class="p-form" autocomplete="off">
        @csrf
        <label for="code" class="form-label">6-digit code</label>
        <input id="code" name="code" class="form-control mb-3" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code" autofocus required
               style="font-size: 1.4rem; letter-spacing: .4em; text-align: center;">
        <button type="submit" class="p-btn p-btn--primary" style="width: 100%;">Sign in</button>
      </form>

      <form method="POST" action="{{ get_absolute_url('admin-verify') }}" class="mt-3 text-center">
        @csrf
        <input type="hidden" name="resend" value="1">
        <button type="submit" class="btn btn-link btn-sm">Send a new code</button>
        <a class="btn btn-link btn-sm" href="{{ get_absolute_url('logout') }}">Cancel</a>
      </form>
    </section>
  </main>
</body>
</html>
