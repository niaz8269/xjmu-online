<?php
require 'db.php';

$token = trim((string)($_GET['token'] ?? ''));
$error = '';
$success = '';

if ($token === '' || !preg_match('/^[a-f0-9]{64}$/i', $token)) {
  die("Invalid or expired token.");
}

// Step 1: Look up by the token's hash (only hashes are stored — audit #10).
$tokenHash = hash('sha256', $token);
$stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ?");
$stmt->execute([$tokenHash]);
$reset = $stmt->fetch();

if (!$reset) {
    die("Invalid or expired token.");
}

// Fail closed: a missing/unparseable timestamp counts as expired.
$createdAt = !empty($reset['created_at']) ? strtotime((string)$reset['created_at']) : false;
if ($createdAt === false || (time() - $createdAt) > 3600) {
    $pdo->prepare("DELETE FROM password_resets WHERE token = ?")->execute([$tokenHash]);
    die("Invalid or expired token.");
}

$email = $reset['email'];

// Step 2: Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $error = "❌ Passwords do not match.";
    } elseif (strlen($new_password) < 8) {
      $error = "❌ Password must be at least 8 characters.";
    } else {
        // Step 3: Update password
        $hashed = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashed, $email]);

        // Step 4: Delete reset token
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->execute([$email]);

        $success = "✅ Password reset successfully! <a href='login'>Login Now</a>";
    }
}
?>

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

    <?php if ($error): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif ($success): ?>
      <div class="alert alert-success"><?= $success ?></div>
    <?php else: ?>
      <form method="POST">
        <div class="mb-3">
          <input type="password" name="password" class="form-control" placeholder="New Password" required>
        </div>
        <div class="mb-3">
          <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
        </div>
        <button type="submit" class="btn btn-primary">Reset Password</button>
      </form>
    <?php endif; ?>
  </div>
</body>
</html>
