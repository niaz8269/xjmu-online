<?php
/**
 * Shared admin gate. Include at the very top of every admin page:
 *     require_once __DIR__ . '/includes/auth.php';   // (from admin/*.php)
 * Provides: $pdo, $is_superadmin, $admin_name, and all helpers from security.php.
 * Redirects non-admins to the login page.
 */

require_once __DIR__ . '/../../session_config.php';
require_once __DIR__ . '/../../db.php';
require_once __DIR__ . '/../../includes/security.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    error_log('Unauthorized admin access attempt - user_id: ' . ($_SESSION['user_id'] ?? 'none'));
    if (ob_get_level()) {
        ob_clean();
    }
    header('Location: ../login');
    exit;
}

$admin_name    = $_SESSION['full_name'] ?? 'Admin';
$is_superadmin = false;
try {
    $stmt = $pdo->prepare("SELECT is_superadmin FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $is_superadmin = (bool) $stmt->fetchColumn();
} catch (\PDOException $e) {
    error_log('auth.php superadmin lookup: ' . $e->getMessage());
}

// Ensure a CSRF token exists for all admin forms.
csrf_token();
