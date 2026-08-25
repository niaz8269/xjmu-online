<?php
// db.php — single PDO connection for the app.
// Credentials come exclusively from config.php (which loads .env and defines the
// DB_* constants). No credentials are hardcoded here — if .env is missing the
// connection uses config.php's safe localhost defaults and fails loudly.

require_once __DIR__ . '/config.php';

$host    = defined('DB_HOST') ? DB_HOST : 'localhost';
$db      = defined('DB_NAME') ? DB_NAME : 'xjmu';
$user    = defined('DB_USER') ? DB_USER : 'root';
$pass    = defined('DB_PASS') ? DB_PASS : '';
$charset = defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4';
$port    = $_ENV['DB_PORT'] ?? '3306';

// connect_timeout keeps a dead host from hanging the page for 60s.
$dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset;connect_timeout=5";

// Apply the configured app timezone to the MySQL session so NOW()/CURRENT_TIMESTAMP
// match PHP's date() (audit #23). Falls back to UTC offset if tz tables are absent.
$initTz = defined('DB_TIME_ZONE') ? DB_TIME_ZONE : '+00:00';

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT         => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset, time_zone = '$initTz'",
    ]);
} catch (\PDOException $e) {
    // Log the real error (never shown to the user); rethrow so callers can show a
    // friendly message. The message intentionally omits credentials.
    $logFile = __DIR__ . '/logs/db_errors.log';
    $logDir  = dirname($logFile);
    if (!is_dir($logDir)) @mkdir($logDir, 0755, true);
    @error_log('[' . date('Y-m-d H:i:s') . '] Database connection error: ' . $e->getMessage() . "\n", 3, $logFile);
    throw $e;
}
