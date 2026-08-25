<?php
require 'session_config.php';
$page_noindex = true; // Private — keep out of search index
require 'db.php';
require_once __DIR__ . '/includes/security.php';

// Reject any POST without a valid CSRF token (audit #19).
csrf_require(false);

// Password validation function
function validate_password($password) {
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters long";
    }
    if (!preg_match('/[A-Z]/', $password)) {
        return "Password must contain at least one uppercase letter";
    }
    if (!preg_match('/[a-z]/', $password)) {
        return "Password must contain at least one lowercase letter";
    }
    if (!preg_match('/[0-9]/', $password)) {
        return "Password must contain at least one number";
    }
    return null; // Valid
}

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit;
}

$user_id = $_SESSION['user_id'];
$feedback = "";

// Fetch current user info
$stmt = $pdo->prepare("SELECT full_name, email FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Update name/email
if (isset($_POST['update_profile'])) {
    $name = trim($_POST['full_name']);
    $email = trim($_POST['email']);

    // Validate email format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $feedback = "❌ Invalid email format.";
    } else {
        // Check if email already exists for another user
        $check = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $check->execute([$email, $user_id]);
        
        if ($check->fetch()) {
            $feedback = "❌ Email already in use by another account.";
        } else {
            $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ? WHERE id = ?");
            $stmt->execute([$name, $email, $user_id]);

            $_SESSION['full_name'] = $name;
            $feedback = "✅ Profile updated successfully.";
        }
    }
}

// Change password
if (isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $userData = $stmt->fetch();

    if (!password_verify($current_password, $userData['password'])) {
        $feedback = "❌ Current password is incorrect.";
    } elseif ($new_password !== $confirm_password) {
        $feedback = "❌ New passwords do not match.";
    } else {
        // Validate password strength
        $password_error = validate_password($new_password);
        if ($password_error) {
            $feedback = "❌ " . $password_error;
        } else {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed, $user_id]);
            $feedback = "✅ Password changed successfully.";
        }
    }
}
?>

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

    <?php if ($feedback): ?>
      <div class="alert alert-info"><?= e($feedback) ?></div>
    <?php endif; ?>

    <!-- Update Profile Form -->
    <form method="POST" class="mb-4">
      <?= csrf_field() ?>
      <div class="mb-3">
        <label for="full_name" class="form-label">Full Name:</label>
        <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Email Address:</label>
        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
      </div>
      <button type="submit" name="update_profile" class="btn btn-primary">Update Profile</button>
    </form>

    <hr>

    <!-- Change Password Form -->
    <form method="POST">
      <?= csrf_field() ?>
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
