<?php
use PDO;
use Exception;

require 'session_config.php';
require 'db.php';
require_once __DIR__ . '/includes/security.php';
json_api_guard();
header('Content-Type: application/json');
csrf_require(true);

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
    exit;
}

// Block edits if application is submitted
$statusStmt = $pdo->prepare("SELECT is_submitted FROM applications WHERE user_id = ?");
$statusStmt->execute([$user_id]);
$statusRow = $statusStmt->fetch(PDO::FETCH_ASSOC);
if (!empty($statusRow['is_submitted'])) {
    echo json_encode(['status' => 'error', 'message' => 'Application is submitted and cannot be edited']);
    exit;
}


try {
    // Step 1: Read submitted arrays
    $levels = $_POST['education_level'] ?? [];
    $fields = $_POST['field_of_study'] ?? [];
    $countries = $_POST['country'] ?? [];
    $institutes = $_POST['institute_name'] ?? [];
    $qualifications = $_POST['qualification'] ?? [];
    $from_dates = $_POST['from_date'] ?? [];
    $to_dates = $_POST['to_date'] ?? [];

    // Step 2: Validate first two education blocks (Highest/Current + Other)
    $requiredIndices = [0, 1];
    foreach ($requiredIndices as $idx) {
        $educationLevel = trim((string)($levels[$idx] ?? ''));
        $fieldOfStudy = trim((string)($fields[$idx] ?? ''));
        $country = trim((string)($countries[$idx] ?? ''));
        $instituteName = trim((string)($institutes[$idx] ?? ''));
        $qualification = trim((string)($qualifications[$idx] ?? ''));
        $fromDate = trim((string)($from_dates[$idx] ?? ''));
        $toDate = trim((string)($to_dates[$idx] ?? ''));

        if (
            $educationLevel === '' ||
            $fieldOfStudy === '' ||
            $country === '' ||
            $instituteName === '' ||
            $qualification === '' ||
            $fromDate === '' ||
            $toDate === ''
        ) {
            $blockName = $idx === 0 ? 'Highest/Current Education' : 'Other Education';
            echo json_encode([
                'status' => 'error',
                'message' => $blockName . ' requires Education Level, Field of Study, Country, Institute Name, Qualification, and Year Attended (From/To).'
            ]);
            exit;
        }
    }

    // Step 3: Delete old records
    $delete = $pdo->prepare("DELETE FROM education_records WHERE user_id = ?");
    $delete->execute([$user_id]);

    $entry_types = ['Highest', 'Other', 'Other']; // For 3 blocks only

    for ($i = 0; $i < count($levels); $i++) {
        // Skip empty blocks
        if (empty($levels[$i]) && empty($fields[$i]) && empty($institutes[$i])) continue;

        $stmt = $pdo->prepare("
            INSERT INTO education_records (
                user_id, entry_type, education_level, field_of_study, country, institute_name,
                qualification, from_date, to_date
            ) VALUES (
                :user_id, :entry_type, :education_level, :field_of_study, :country, :institute_name,
                :qualification, :from_date, :to_date
            )
        ");

        $stmt->execute([
            'user_id' => $user_id,
            'entry_type' => $entry_types[$i] ?? 'Other',
            'education_level' => $levels[$i],
            'field_of_study' => $fields[$i],
            'country' => $countries[$i],
            'institute_name' => $institutes[$i],
            'qualification' => $qualifications[$i],
            'from_date' => $from_dates[$i],
            'to_date' => $to_dates[$i]
        ]);
    }

    echo json_encode(['status' => 'success', 'message' => 'Education details saved successfully.']);
    exit;
} catch (Exception $e) {
    error_log('save_education: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Failed to save education details']);
    exit;
}