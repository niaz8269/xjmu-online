<?php
// session_config.php - Add this to the top of critical files to ensure consistent session handling

// Start output buffering to prevent "headers already sent" errors
if (!ob_get_level()) {
    ob_start();
}

// Ensure session is not already started
if (session_status() === PHP_SESSION_NONE) {
    // Detect HTTPS (handles direct TLS, X-Forwarded-Proto, X-Forwarded-SSL).
    $forwardedProto = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '');
    $forwardedSsl   = strtolower($_SERVER['HTTP_X_FORWARDED_SSL']   ?? '');
    $isHttps = (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
        || $forwardedProto === 'https'
        || $forwardedSsl   === 'on'
        || (int)($_SERVER['SERVER_PORT'] ?? 0) === 443;

    // Use a single shared session directory for the whole app.
    // iPage/LiteSpeed isolates session storage per subdirectory by default,
    // so login.php (in XJMU/) and dashboard.php (in XJMU/admin/) end up
    // reading/writing different session files for the same session ID.
    // Pinning save_path to an absolute directory (relative to this file,
    // which every script includes) guarantees one shared store.
    $sessionSavePath = __DIR__ . '/sessions';
    if (!is_dir($sessionSavePath)) {
        @mkdir($sessionSavePath, 0700, true);
    }
    ini_set('session.save_path', $sessionSavePath);

    ini_set('session.cookie_httponly', '1');
    ini_set('session.use_only_cookies', '1');
    ini_set('session.cookie_secure', $isHttps ? '1' : '0');
    ini_set('session.cookie_samesite', 'Lax');
    ini_set('session.cookie_lifetime', '0');
    ini_set('session.gc_maxlifetime', '3600');
    // Enable garbage collection (~1% of requests) so expired session files are
    // pruned automatically instead of accumulating forever (audit #32).
    ini_set('session.gc_probability', '1');
    ini_set('session.gc_divisor', '100');

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => $isHttps,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}
