<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Profile</title>
  <link rel="icon" href="/favicon.ico" type="image/x-icon">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5" style="max-width: 700px;">
    <h3 class="mb-4">👤 My Profile</h3>

    @if ($feedback)
      <div class="alert alert-info">{{ $feedback }}</div>
    @endif

    <!-- Update Profile Form -->
    <form method="POST" class="mb-4">
      @csrf
      <div class="mb-3">
        <label for="full_name" class="form-label">Full Name:</label>
        <input type="text" name="full_name" class="form-control" value="{{ $user->full_name ?? '' }}" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email Address:</label>
        <input type="email" name="email" class="form-control" value="{{ $user->email ?? '' }}" required>
      </div>
      <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
    </form>

    <hr>

    <!-- Change Password Form -->
    <form method="POST">
      @csrf
      <h5>🔐 Change Password</h5>
      <div class="mb-3">
        <label class="form-label">Current Password:</label>
        <input type="password" name="current_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">New Password:</label>
        <input type="password" name="new_password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm New Password:</label>
        <input type="password" name="confirm_password" class="form-control" required>
      </div>
      <button type="submit" name="change_password" class="btn btn-warning">Change Password</button>
    </form>

    <a href="front" class="btn btn-secondary mt-4">← Back</a>
  </div>
</body>
</html>
