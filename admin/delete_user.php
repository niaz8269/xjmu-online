<?php
require_once '../session_config.php';
require '../db.php';

// Security: Check if requester is Superadmin
$check = $pdo->prepare("SELECT is_superadmin FROM users WHERE id = ?");
$check->execute([$_SESSION['user_id'] ?? 0]);
$isSuper = $check->fetchColumn();

if (!$isSuper) {
    die("Access Denied");
}

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && (
        empty($_POST['csrf_token'])
        || empty($_SESSION['csrf_token'])
        || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
    )
) {
    die("Invalid request token");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    // Prevent accidental deletion of self or other superadmins (Safety check)
    $targetCheck = $pdo->prepare("SELECT is_superadmin FROM users WHERE id = ?");
    $targetCheck->execute([$_POST['user_id']]);
    $targetIsSuper = $targetCheck->fetchColumn();

    if (!$targetIsSuper) {
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$_POST['user_id']]);
    }
}

header("Location: manage_users");
exit;
?>