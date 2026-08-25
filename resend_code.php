<?php
require 'db.php';
require 'send_verification_email.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    if (empty($email)) {
        echo "<script>alert('Please enter an email address.'); window.history.back();</script>";
        exit;
    }

    // Look up the account. To prevent account enumeration (audit #26) we return
    // the SAME generic message for every outcome and never reveal whether the
    // email exists or its verification state.
    $stmt = $pdo->prepare("SELECT id, full_name, is_verified FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    // Only do real work for an existing, unverified account; otherwise stay silent.
    if ($user && (int)$user['is_verified'] !== 1) {
        $new_token = bin2hex(random_bytes(32));
        $updateStmt = $pdo->prepare("UPDATE users SET verification_token = ? WHERE email = ?");
        if ($updateStmt->execute([$new_token, $email])) {
            // Ignore the send result for messaging purposes (don't leak state).
            @sendVerificationEmail($email, $user['full_name'], $new_token);
        }
    }

    echo "<script>
            alert('If an unverified account exists for that email, a new verification link has been sent.');
            window.location.href = 'login';
          </script>";
    exit;
} else {
    header("Location: register");
    exit;
}
?>