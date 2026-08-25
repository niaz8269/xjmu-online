<?php
require 'db.php';
require 'send_verification_email.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $response['message'] = "❌ Please enter an email address.";
        echo json_encode($response);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, full_name FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Raw token goes in the email link; only its hash is stored, so a DB
        // read alone cannot reset a password (audit #10).
        $token = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $token);

        $pdo->prepare("DELETE FROM password_resets WHERE email = ?")->execute([$email]);
        $pdo->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)")->execute([$email, $tokenHash]);

        if (sendPasswordResetEmail($email, $user['full_name'], $token)) {
            $response['success'] = true;
            $response['message'] = "✅ Password reset link has been sent to your email. Please check your inbox.";
        } else {
            $response['message'] = "❌ Failed to send reset email. Please try again later or contact support.";
        }
    } else {
        // Prevent email enumeration — always return success
        error_log("Password reset requested for non-existent email: " . $email);
        $response['success'] = true;
        $response['message'] = "✅ If the email exists, a reset link has been sent.";
    }
} else {
    $response['message'] = "Invalid request method.";
}

echo json_encode($response);
?>
