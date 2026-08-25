<?php
require_once '../session_config.php';
require '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login");
    exit;
}

// Optional: check if superadmin
$is_superadmin = false;
$stmt = $pdo->prepare("SELECT is_superadmin FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
if ($row = $stmt->fetch()) {
    $is_superadmin = $row['is_superadmin'];
}


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: applications");
    exit;
}

if (empty($_POST['csrf_token']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
    $_SESSION['flash'] = "Security validation failed. Please try again.";
    header("Location: applications");
    exit;
}

if (!isset($_POST['user_id'])) {
    header("Location: applications");
    exit;
}

$user_id = (int)$_POST['user_id'];

try {
    // Capture file paths before deleting the rows so we can clean up disk
    // AFTER the DB transaction commits successfully.
    $files = $pdo->prepare("SELECT file_path FROM documents WHERE user_id = ?");
    $files->execute([$user_id]);
    $filePaths = array_column($files->fetchAll(PDO::FETCH_ASSOC), 'file_path');

    // All-or-nothing: never leave a half-deleted application (audit #24).
    $pdo->beginTransaction();
    $pdo->prepare("DELETE FROM personal_info WHERE user_id = ?")->execute([$user_id]);
    $pdo->prepare("DELETE FROM education_records WHERE user_id = ?")->execute([$user_id]);
    $pdo->prepare("DELETE FROM documents WHERE user_id = ?")->execute([$user_id]);
    $pdo->prepare("DELETE FROM applications WHERE user_id = ?")->execute([$user_id]);
    $pdo->commit();

    // DB is consistent now — remove the files from disk.
    foreach ($filePaths as $relPath) {
        $path = "../" . $relPath;
        if ($relPath && file_exists($path)) {
            @unlink($path);
        }
    }

    $_SESSION['flash'] = "Application deleted successfully.";
    header("Location: applications");
    exit;
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    error_log("Delete application failed: " . $e->getMessage());
    $_SESSION['flash'] = "Unable to delete application at the moment. Please try again.";
    header("Location: applications");
    exit;
}
