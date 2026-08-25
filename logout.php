<?php
require 'session_config.php';
require 'config.php';

// Destroy the session completely
$_SESSION = [];
if (ini_get('session.use_cookies')) {
	$p = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000,
		$p['path'], $p['domain'], $p['secure'], $p['httponly']
	);
}
session_destroy();

// Redirect to login page
if (ob_get_level()) ob_end_clean();
$safe_url = htmlspecialchars(get_absolute_url('login'), ENT_QUOTES, 'UTF-8');
echo '<!DOCTYPE html><html><head><meta charset="utf-8">';
echo '<meta http-equiv="refresh" content="0;url=' . $safe_url . '">';
echo '<title>Logging out…</title></head><body>';
echo '<script>window.location.replace("' . $safe_url . '");</script>';
echo '</body></html>';
