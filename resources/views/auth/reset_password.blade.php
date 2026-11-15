<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="robots" content="noindex, nofollow">
  <title>Reset Password | Xinjiang Medical University</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="icon" href="images/favicon.ico" type="image/x-icon">
</head>
<body class="bg-light">
  <div class="container mt-5" style="max-width: 500px;">
    <h3>Reset Your Password</h3>

    @if ($error)
      <div class="alert alert-danger">{{ $error }}</div>
    @elseif ($success)
      <div class="alert alert-success">{!! $success !!}</div>
    @else
      <form method="POST">
        @csrf
        <div class="mb-3">
          <input type="password" name="password" class="form-control" placeholder="New Password" required>
        </div>
        <div class="mb-3">
          <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
        </div>
        <button type="submit" class="btn btn-primary">Reset Password</button>
      </form>
    @endif
  </div>
</body>
</html>
