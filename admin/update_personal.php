<?php
require_once '../session_config.php';
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login");
    exit;
}

// Only accept POST with a valid CSRF token
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: applications");
    exit;
}

if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token'])
    || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $_SESSION['flash'] = "Security validation failed.";
    header("Location: applications");
    exit;
}

$user_id = intval($_POST['user_id'] ?? 0);
if (!$user_id) {
    header("Location: applications");
    exit;
}

$stmt = $pdo->prepare("
    UPDATE personal_info SET
      given_name = ?, surname = ?, dob = ?
    WHERE user_id = ?
");

$stmt->execute([
    $_POST['given_name'],
    $_POST['surname'],
    $_POST['dob'],
    $user_id
]);

$_SESSION['flash'] = "Personal information updated.";
header("Location: applications");
exit;
