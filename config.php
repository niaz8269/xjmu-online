<?php
/**
 * Configuration file for path resolution
 * Ensures paths work correctly on both localhost and live server
 */

// Prevent multiple includes
if (defined('CONFIG_LOADED')) {
    return;
}
define('CONFIG_LOADED', true);

// Get the document root and script directory
$document_root = $_SERVER['DOCUMENT_ROOT'] ?? dirname(__DIR__);
$script_name = $_SERVER['SCRIPT_NAME'] ?? '/index.php';
$script_dir = dirname($script_name);

// Determine base path - if script is in root, base_path is empty
if ($script_dir === '/' || $script_dir === '\\' || $script_dir === '.') {
    $base_path = '';
} else {
    $base_path = $script_dir;
}

// Clean up base path
$base_path = str_replace('\\', '/', $base_path);
$base_path = trim($base_path, '/');

// Base URL - works for the live server and for CLI fallbacks
$forwardedProto = strtolower($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '');
$forwardedSsl = strtolower($_SERVER['HTTP_X_FORWARDED_SSL'] ?? '');
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $forwardedProto === 'https' || $forwardedSsl === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'xjmu.online';
$base_url = $protocol . '://' . $host . ($base_path ? '/' . $base_path : '');

// Load shared environment variables so DB settings stay consistent across the app
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '' || strpos($trimmed, '#') === 0) {
            continue;
        }
        $parts = explode('=', $line, 2);
        if (count($parts) === 2) {
            $_ENV[trim($parts[0])] = trim($parts[1]);
        }
    }
}

if (!defined('DB_HOST')) {
    define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
}
if (!defined('DB_NAME')) {
    define('DB_NAME', $_ENV['DB_NAME'] ?? 'xjmu');
}
if (!defined('DB_USER')) {
    define('DB_USER', $_ENV['DB_USER'] ?? 'root');
}
if (!defined('DB_PASS')) {
    define('DB_PASS', $_ENV['DB_PASS'] ?? '');
}
if (!defined('DB_CHARSET')) {
    define('DB_CHARSET', $_ENV['DB_CHARSET'] ?? 'utf8mb4');
}

// Apply a single timezone everywhere so PHP date() and MySQL NOW() agree (audit #23).
if (!defined('APP_TIMEZONE_NAME')) {
    $appTz = $_ENV['APP_TIMEZONE'] ?? 'UTC';
    try {
        $tzObj = new DateTimeZone($appTz);
    } catch (\Exception $e) {
        $appTz = 'UTC';
        $tzObj = new DateTimeZone('UTC');
    }
    date_default_timezone_set($appTz);
    define('APP_TIMEZONE_NAME', $appTz);
    // Numeric offset (e.g. +08:00) for MySQL SET time_zone — works without the
    // MySQL named-timezone tables, which XAMPP does not load by default.
    $offsetSecs = $tzObj->getOffset(new DateTime('now', $tzObj));
    $sign = $offsetSecs < 0 ? '-' : '+';
    $offsetSecs = abs($offsetSecs);
    define('DB_TIME_ZONE', sprintf('%s%02d:%02d', $sign, intdiv($offsetSecs, 3600), intdiv($offsetSecs % 3600, 60)));
}

// Helper function to get absolute path (for file system)
function get_absolute_path($relative_path) {
    global $base_path;
    // Remove leading slash if present
    $relative_path = ltrim($relative_path, '/');
    // Return path with base
    if ($base_path) {
        return $base_path . '/' . $relative_path;
    }
    return $relative_path;
}

// Helper function to get absolute URL (for web)
// Always returns clean extensionless URLs (/about not /about.php)
function get_absolute_url($relative_path) {
    global $base_url;
    $relative_path = ltrim($relative_path, '/');
    // Strip .php extension for clean canonical URLs
    $relative_path = preg_replace('/\.php(\?.*)?$/', '$1', $relative_path);
    // Strip .html extension (login, register etc.)
    $relative_path = preg_replace('/\.html(\?.*)?$/', '$1', $relative_path);
    // Homepage: index or empty → root
    if ($relative_path === 'index' || $relative_path === '') {
        return rtrim($base_url, '/') . '/';
    }
    return rtrim($base_url, '/') . '/' . $relative_path;
}

// For backward compatibility, set common paths
if (!defined('BASE_PATH')) {
    define('BASE_PATH', $base_path);
}
if (!defined('BASE_URL')) {
    define('BASE_URL', $base_url);
}
