<?php
require 'session_config.php';
require 'config.php';

$redirect_url  = null;
$error_message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	if (empty($_POST['email']) || empty($_POST['password'])) {
		$error_message = 'Email and password are required';
	} else {
		try {
			require 'db.php';
		} catch (\PDOException $e) {
			error_log('Login DB connection error: ' . $e->getMessage());
			$error_message = 'Database connection failed. Please contact administrator.';
		}

		if (!$error_message) {
			$email    = trim($_POST['email']);
			$password = $_POST['password'];

			try {
				$stmt = $pdo->prepare("SELECT id, full_name, email, password, role, is_verified FROM users WHERE email = ?");
				$stmt->execute([$email]);
				$user = $stmt->fetch(PDO::FETCH_ASSOC);

				if ($user && password_verify($password, $user['password'])) {
					if (isset($user['is_verified']) && !$user['is_verified']) {
						$error_message = 'Email not verified. Please check your inbox.';
					} else {
						$_SESSION['user_id']   = $user['id'];
						$_SESSION['full_name'] = $user['full_name'];
						$_SESSION['role']      = $user['role'];

						// Flush session to disk before the browser follows the redirect.
						session_write_close();

						$redirect_url = $user['role'] === 'admin'
							? get_absolute_url('admin/dashboard')
							: get_absolute_url('front');
					}
				} else {
					$error_message = 'Incorrect email or password';
				}
			} catch (\PDOException $e) {
				error_log('Login query error: ' . $e->getMessage());
				$error_message = 'An error occurred. Please try again later.';
			}
		}
	}
}

if ($redirect_url) {
	if (ob_get_level()) ob_end_clean();
	$safe_url = htmlspecialchars($redirect_url, ENT_QUOTES, 'UTF-8');
	echo '<!DOCTYPE html><html><head><meta charset="utf-8">';
	echo '<meta http-equiv="refresh" content="0;url=' . $safe_url . '">';
	echo '<title>Redirecting…</title></head><body>';
	echo '<script>window.location.replace("' . $safe_url . '");</script>';
	echo '</body></html>';
	exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $error_message) {
	if (ob_get_level()) ob_end_clean();
	$loginUrl = htmlspecialchars(get_absolute_url('login'), ENT_QUOTES, 'UTF-8');
	echo '<script>alert("' . addslashes($error_message) . '");window.location.href="' . $loginUrl . '";</script>';
	exit;
}

$form = __DIR__ . '/login.html';
if (file_exists($form)) {
	readfile($form);
} else {
	echo '<!doctype html><html><head><meta charset="utf-8"><title>Login</title></head><body>';
	echo '<form method="POST" action="login.php">';
	echo '<input type="email" name="email" required placeholder="Email"><br>';
	echo '<input type="password" name="password" required placeholder="Password"><br>';
	echo '<button type="submit">Sign In</button>';
	echo '</form></body></html>';
}
