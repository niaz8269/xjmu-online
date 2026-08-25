<?php
use PDO;

require 'session_config.php';
require 'db.php';
require_once __DIR__ . '/includes/security.php';
json_api_guard();
header('Content-Type: application/json');
csrf_require(true);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
	exit;
}

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
	echo json_encode(['status' => 'error', 'message' => 'User not logged in']);
	exit;
}

// Check if application already submitted
$checkStmt = $pdo->prepare("SELECT is_submitted FROM applications WHERE user_id = ?");
$checkStmt->execute([$user_id]);
$checkRow = $checkStmt->fetch(PDO::FETCH_ASSOC);
if (!empty($checkRow['is_submitted'])) {
	echo json_encode(['status' => 'error', 'message' => 'Application already submitted']);
	exit;
}

// Validate application program choice
$programStmt = $pdo->prepare("SELECT degree_applying_for, major_applying_for FROM applications WHERE user_id = ?");
$programStmt->execute([$user_id]);
$programRow = $programStmt->fetch(PDO::FETCH_ASSOC);
if (!$programRow || trim((string)($programRow['degree_applying_for'] ?? '')) === '' || trim((string)($programRow['major_applying_for'] ?? '')) === '') {
	echo json_encode(['status' => 'error', 'message' => 'Please complete Degree and Major in the application program section.']);
	exit;
}

// Validate mandatory personal fields
$personalStmt = $pdo->prepare("SELECT
	given_name, surname, dob, gender, marital_status, country_of_birth, city_of_birth,
	religion, nationality, native_language, passport_no, present_address, permanent_address,
	tel, email, emergency_contact_name, emergency_gender, emergency_relationship,
	emergency_tel, emergency_email, emergency_address
FROM personal_info WHERE user_id = ? LIMIT 1");
$personalStmt->execute([$user_id]);
$personalRow = $personalStmt->fetch(PDO::FETCH_ASSOC);

if (!$personalRow) {
	echo json_encode(['status' => 'error', 'message' => 'Please complete Personal Details section before submission.']);
	exit;
}

$personalRequiredMap = [
	'given_name' => 'Given Name',
	'surname' => 'Surname',
	'dob' => 'Date of Birth',
	'gender' => 'Gender',
	'marital_status' => 'Marital Status',
	'country_of_birth' => 'Country of Birth',
	'city_of_birth' => 'City of Birth',
	'religion' => 'Religion',
	'nationality' => 'Nationality',
	'native_language' => 'Native Language',
	'passport_no' => 'Passport No.',
	'present_address' => 'Present Address',
	'permanent_address' => 'Permanent Address',
	'tel' => 'Tel',
	'email' => 'Email',
	'emergency_contact_name' => 'Emergency Contact Name',
	'emergency_gender' => 'Emergency Gender',
	'emergency_relationship' => 'Emergency Relationship',
	'emergency_tel' => 'Emergency Tel',
	'emergency_email' => 'Emergency Email',
	'emergency_address' => 'Emergency Address'
];

$missingPersonal = [];
foreach ($personalRequiredMap as $field => $label) {
	if (trim((string)($personalRow[$field] ?? '')) === '') {
		$missingPersonal[] = $label;
	}
}

if (!empty($missingPersonal)) {
	echo json_encode([
		'status' => 'error',
		'message' => 'Please complete required personal fields: ' . implode(', ', $missingPersonal)
	]);
	exit;
}

// Validate education mandatory blocks (Highest/Current + first Other)
$highestStmt = $pdo->prepare("SELECT id FROM education_records
	WHERE user_id = ? AND entry_type = 'Highest'
	AND TRIM(COALESCE(education_level, '')) <> ''
	AND TRIM(COALESCE(field_of_study, '')) <> ''
	AND TRIM(COALESCE(country, '')) <> ''
	AND TRIM(COALESCE(institute_name, '')) <> ''
	AND TRIM(COALESCE(qualification, '')) <> ''
	AND from_date IS NOT NULL
	AND to_date IS NOT NULL
	LIMIT 1");
$highestStmt->execute([$user_id]);

$otherStmt = $pdo->prepare("SELECT id FROM education_records
	WHERE user_id = ? AND entry_type = 'Other'
	AND TRIM(COALESCE(education_level, '')) <> ''
	AND TRIM(COALESCE(field_of_study, '')) <> ''
	AND TRIM(COALESCE(country, '')) <> ''
	AND TRIM(COALESCE(institute_name, '')) <> ''
	AND TRIM(COALESCE(qualification, '')) <> ''
	AND from_date IS NOT NULL
	AND to_date IS NOT NULL
	LIMIT 1");
$otherStmt->execute([$user_id]);

if (!$highestStmt->fetch()) {
	echo json_encode(['status' => 'error', 'message' => 'Please complete all mandatory fields in Highest/Current Education section.']);
	exit;
}

if (!$otherStmt->fetch()) {
	echo json_encode(['status' => 'error', 'message' => 'Please complete all mandatory fields in Other Education section.']);
	exit;
}

// Define mandatory document IDs
$mandatoryDocuments = [1, 2, 3, 4, 7, 8, 15];

// Check if all mandatory documents are uploaded
$missingDocs = [];
foreach ($mandatoryDocuments as $docId) {
	$docStmt = $pdo->prepare("SELECT COUNT(*) as count FROM documents WHERE user_id = ? AND document_id = ?");
	$docStmt->execute([$user_id, $docId]);
	$docResult = $docStmt->fetch(PDO::FETCH_ASSOC);
	
	if ($docResult['count'] == 0) {
		// Get document name for error message
		$docNames = [
			1 => 'ID Photo',
			2 => 'Certificates of Highest Education',
			3 => 'Transcripts of Highest Education',
			4 => 'Study Plan',
			7 => 'Passport Home Page',
			8 => 'Physical Examination Record for Foreigner',
			15 => 'Non-Criminal Record Report'
		];
		$missingDocs[] = isset($docNames[$docId]) ? $docNames[$docId] : "Document {$docId}";
	}
}

// If mandatory documents are missing, prevent submission
if (!empty($missingDocs)) {
	echo json_encode([
		'status' => 'error', 
		'message' => 'Please upload all mandatory documents: ' . implode(', ', $missingDocs)
	]);
	exit;
}

// All validations passed, submit the application
$stmt = $pdo->prepare("
	INSERT INTO applications (user_id, is_submitted, submitted_at, status)
	VALUES (?, 1, NOW(), 'Pending')
	ON DUPLICATE KEY UPDATE is_submitted = 1, submitted_at = NOW(), status = 'Pending'
");
$stmt->execute([$user_id]);

echo json_encode(['status' => 'success', 'message' => 'Application submitted successfully']);
exit;
