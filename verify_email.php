<?php
require 'db.php';
$page_noindex = true; // Private — keep out of search index

$token = isset($_GET['token']) ? trim((string)$_GET['token']) : '';

// Validate format before touching the DB (audit #11): tokens are 64 hex chars.
if ($token === '' || !preg_match('/^[a-f0-9]{64}$/i', $token)) {
    echo "<script>
            alert('❌ Invalid or missing verification token.');
            window.location.href = 'register';
          </script>";
    exit;
}

// Find user with this token
$stmt = $pdo->prepare("SELECT id, email, full_name FROM users WHERE verification_token = ?");
$stmt->execute([$token]);
$user = $stmt->fetch();

if ($user) {
    // Mark email as verified and consume the single-use token
    $stmt = $pdo->prepare("UPDATE users SET is_verified = 1, verification_token = NULL WHERE id = ?");
    $stmt->execute([$user['id']]);

    echo "<script>
            alert('✅ Email verified successfully! You can now log in.');
            window.location.href = 'login';
          </script>";
    exit;
}

echo "<script>
        alert('❌ Invalid or expired verification token.');
        window.location.href = 'register';
      </script>";
exit;
