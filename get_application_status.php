<?php
use PDO;

require 'session_config.php';
require 'db.php';
require_once __DIR__ . '/includes/security.php';
json_api_guard();

header('Content-Type: application/json');

$user_id = $_SESSION['user_id'] ?? null;

$response = [
    'is_submitted' => false,
    'personal_data' => false,
    'has_any_data' => false,
    'status' => 'Unsubmitted',
    'admin_remark' => ''
];

if ($user_id) {
    // Check application row (submission + program data)
    $stmt = $pdo->prepare("SELECT is_submitted, status, admin_remark, degree_applying_for, major_applying_for FROM applications WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row) {
        $response['is_submitted'] = (bool)$row['is_submitted'];
        $response['status'] = $row['status'] ?? 'Unsubmitted';
        $response['admin_remark'] = $row['admin_remark'] ?? '';

        if (!empty(trim((string)($row['degree_applying_for'] ?? ''))) || !empty(trim((string)($row['major_applying_for'] ?? '')))) {
            $response['has_any_data'] = true;
        }
    }

    // Check if personal data exists
    $checkPersonal = $pdo->prepare("SELECT id FROM personal_info WHERE user_id = ?");
    $checkPersonal->execute([$user_id]);
    $response['personal_data'] = $checkPersonal->fetch() ? true : false;
    if ($response['personal_data']) {
        $response['has_any_data'] = true;
    }

    $checkEducation = $pdo->prepare("SELECT id FROM education_records WHERE user_id = ? LIMIT 1");
    $checkEducation->execute([$user_id]);
    if ($checkEducation->fetch()) {
        $response['has_any_data'] = true;
    }

    $checkDocuments = $pdo->prepare("SELECT id FROM documents WHERE user_id = ? LIMIT 1");
    $checkDocuments->execute([$user_id]);
    if ($checkDocuments->fetch()) {
        $response['has_any_data'] = true;
    }
}

echo json_encode($response);
