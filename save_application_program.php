<?php
use \PDO;
use \PDOException;

require_once __DIR__ . '/session_config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/security.php';
json_api_guard();

header('Content-Type: application/json');
csrf_require(true);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit;
}

$user_id = $_SESSION['user_id'];
$degree = trim($_POST['degree_applying_for'] ?? '');
$major = trim($_POST['major_applying_for'] ?? '');

if ($degree === '' || $major === '') {
    echo json_encode(['status' => 'error', 'message' => 'Degree and Major are required.']);
    exit;
}

try {
    // Check if application is already submitted (do not overwrite then)
    $stmt = $pdo->prepare("SELECT is_submitted, status FROM applications WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && ((int)($row['is_submitted'] ?? 0) === 1 || in_array($row['status'] ?? '', ['Pending', 'Rejected']))) {
        echo json_encode(['status' => 'error', 'message' => 'Application is already submitted and cannot be modified.']);
        exit;
    }

    // Upsert degree and major (create draft row if not exists, else update only degree/major)
    $stmt = $pdo->prepare("
        INSERT INTO applications (user_id, degree_applying_for, major_applying_for, is_submitted, status)
        VALUES (?, ?, ?, 0, 'draft')
        ON DUPLICATE KEY UPDATE
            degree_applying_for = VALUES(degree_applying_for),
            major_applying_for = VALUES(major_applying_for)
    ");
    $stmt->execute([$user_id, $degree, $major]);

    echo json_encode(['status' => 'success', 'message' => 'Program choice saved successfully.']);
} catch (PDOException $e) {
    // If columns don't exist yet, return helpful message
    if (strpos($e->getMessage(), 'degree_applying_for') !== false || strpos($e->getMessage(), 'Unknown column') !== false) {
        echo json_encode(['status' => 'error', 'message' => 'Please run install_application_degree_major.sql on your database first.']);
        exit;
    }
    error_log('save_application_program: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Failed to save. Please try again.']);
}
