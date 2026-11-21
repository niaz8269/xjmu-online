<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="robots" content="noindex, nofollow">
  <meta name="xjmu-base" content="https://xjmu.online">
  <title>My Profile</title>
  <link rel="icon" href="/favicon.ico" type="image/x-icon">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Manrope:wght@700;800&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
  <link rel="stylesheet" href="css/portal.css?v=1">
</head>
<body class="portal">

@include('portal._bar', ['active' => 'profile'])

  <main class="p-wrap p-narrow">
    <div class="p-hello">
      <h1>My Profile</h1>
      <p>Update your name, email address and password.</p>
    </div>

    @if ($feedback)
      <div class="alert alert-info">{{ $feedback }}</div>
    @endif

    <!-- Update Profile Form -->
    <section class="p-card">
      <h2>Account details</h2>
      <form method="POST" class="p-form">
        @csrf
        <div class="mb-3">
          <label for="full_name" class="form-label">Full Name</label>
          <input type="text" id="full_name" name="full_name" class="form-control" value="{{ $user->full_name ?? '' }}" autocomplete="name" required>
        </div>
        <div class="mb-3">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" id="email" name="email" class="form-control" value="{{ $user->email ?? '' }}" autocomplete="email" required>
        </div>
        <button type="submit" name="update_profile" class="p-btn p-btn--primary">Update Profile</button>
      </form>
    </section>

    <!-- Change Password Form -->
    <section class="p-card">
      <h2>Change Password</h2>
      <form method="POST" class="p-form">
        @csrf
        <div class="mb-3">
          <label for="current_password" class="form-label">Current Password</label>
          <input type="password" id="current_password" name="current_password" class="form-control" autocomplete="current-password" required>
        </div>
        <div class="mb-3">
          <label for="new_password" class="form-label">New Password</label>
          <input type="password" id="new_password" name="new_password" class="form-control" autocomplete="new-password" required>
        </div>
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Confirm New Password</label>
          <input type="password" id="confirm_password" name="confirm_password" class="form-control" autocomplete="new-password" required>
        </div>
        <button type="submit" name="change_password" class="p-btn p-btn--ghost">Change Password</button>
      </form>
    </section>

    <p class="mt-4"><a href="front">← Back to dashboard</a></p>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="js/i18n.js"></script>
</body>
</html>
