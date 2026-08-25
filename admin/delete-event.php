<?php
require_once '../session_config.php';
require_once '../db.php';
require_once __DIR__ . '/../includes/security.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login');
    exit;
}

$user_stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$user_stmt->execute([$_SESSION['user_id']]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);
if (!$user || $user['role'] !== 'admin') {
    http_response_code(403);
    die("Access denied.");
}

// Deletion must be a POST with a valid CSRF token (audit #20).
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check()) {
    http_response_code(403);
    $_SESSION['event_message'] = 'Invalid or expired request. Please use the Delete button.';
    header('Location: manage-events');
    exit;
}

$id = (int)($_POST['id'] ?? 0);
if ($id <= 0) {
    header('Location: manage-events');
    exit;
}

try {
    $pdo->prepare("DELETE FROM events WHERE id = ?")->execute([$id]);
    $_SESSION['event_message'] = 'Event deleted.';
} catch (PDOException $e) {
    error_log('delete-event: ' . $e->getMessage());
    $_SESSION['event_message'] = 'Error deleting event. Please try again.';
}
header('Location: manage-events');
exit;
