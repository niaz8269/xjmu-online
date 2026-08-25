<?php
require 'db.php';
require 'send_verification_email.php';

// Password validation function
function validate_password($password) {
    if (strlen($password) < 8) {
        return "Password must be at least 8 characters long";
    }
    if (!preg_match('/[a-zA-Z]/', $password)) {
        return "Password must contain at least one letter";
    }
    if (!preg_match('/[0-9]/', $password)) {
        return "Password must contain at least one number";
    }
    if (!preg_match('/[!@#$%^&*()\[\]{}\-_=+|;:\'",.<?\/\\\\`~]/', $password)) {
        return "Password must contain at least one special character";
    }
    return null; // Valid
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = strtolower(trim($_POST['email']));
    $raw_password = $_POST['password'];
    
    // Validate password strength
    $password_error = validate_password($raw_password);
    if ($password_error) {
        echo "<script>
                alert('Registration failed: " . addslashes($password_error) . "');
                window.location.href = 'register';
              </script>";
        exit;
    }
    
    $password = password_hash($raw_password, PASSWORD_DEFAULT);
    $token = bin2hex(random_bytes(32));
    
    $stmt = $pdo->prepare("SELECT id, full_name, is_verified FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($existingUser) {
        // If already verified, direct user to login.
        if ((int)$existingUser['is_verified'] === 1) {
            echo "<script>
                    alert('This email is already registered. Please login.');
                    window.location.href = 'login';
                  </script>";
            exit;
        }

        // If not verified yet, regenerate token and resend verification email.
        $newToken = bin2hex(random_bytes(32));
        $update = $pdo->prepare("UPDATE users SET verification_token = ? WHERE id = ?");
        $update->execute([$newToken, $existingUser['id']]);

        $targetName = !empty($existingUser['full_name']) ? $existingUser['full_name'] : $full_name;
        if (sendVerificationEmail($email, $targetName, $newToken)) {
            echo "<script>
                    alert('Your account exists but is not verified. We sent a new verification email.');
                    window.location.href = 'login';
                  </script>";
        } else {
            $errorDetails = function_exists('getLastVerificationEmailError') ? getLastVerificationEmailError() : 'No SMTP details available';
            error_log("Resend-on-register failed for " . $email . ". SMTP error: " . $errorDetails);
            echo "<script>
                    alert('Unable to send verification email right now. Please try again shortly or use Resend Verification.');
                    window.location.href = 'register';
                  </script>";
        }
        exit;
    }

    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, verification_token, is_superadmin) VALUES (?, ?, ?, ?, 0)");
    
    if ($stmt->execute([$full_name, $email, $password, $token])) {
        // User is created, now try to send email
        $emailSent = sendVerificationEmail($email, $full_name, $token);

        if ($emailSent) {
            echo "<script>
                    alert('Registration successful. Please check your email to verify.');
                    window.location.href = 'login';
                  </script>";
        } else {
            // Do NOT delete the account when email delivery fails — that made
            // registration impossible whenever SMTP was down. Keep the unverified
            // account and let the user request the verification email again.
            $errorDetails = function_exists('getLastVerificationEmailError') ? getLastVerificationEmailError() : 'No SMTP details available';
            error_log("Verification email failed for user: " . $email . " - account kept as unverified. SMTP error: " . $errorDetails);
            echo "<script>
                    alert('Your account was created, but we could not send the verification email right now. Please use \\'Resend Verification\\' shortly.');
                    window.location.href = 'login';
                  </script>";
        }
    } else {
        echo "Registration failed.";
    }
} else {
    // GET request — serve the registration form
    $register_html = __DIR__ . '/register.html';
    if (file_exists($register_html)) {
        readfile($register_html);
    } else {
        header("Location: /register.html");
    }
}
?>