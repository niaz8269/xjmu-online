<?php
use PDO;
use Exception;

// save_personal.php

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


// Sanitize and get POST data
$data = [
    'given_name' => $_POST['given_name'] ?? '',
    'surname' => $_POST['surname'] ?? '',
    'chinese_name' => $_POST['chinese_name'] ?? '',
    'dob' => $_POST['dob'] ?? '',
    'gender' => $_POST['gender'] ?? '',
    'marital_status' => $_POST['marital_status'] ?? '',
    'country_of_birth' => $_POST['country_of_birth'] ?? '',
    'city_of_birth' => $_POST['city_of_birth'] ?? '',
    'religion' => $_POST['religion'] ?? '',
    'nationality' => $_POST['nationality'] ?? '',
    'native_language' => $_POST['native_language'] ?? '',
    'passport_no' => $_POST['passport_no'] ?? '',
    'passport_expiry' => $_POST['passport_expiry'] ?? null,
    'present_address' => $_POST['present_address'] ?? '',
    'permanent_address' => $_POST['permanent_address'] ?? '',
    'tel' => $_POST['tel'] ?? '',
    'email' => $_POST['email'] ?? '',
    'alternate_email' => $_POST['alternate_email'] ?? '',
    'wechat_id' => $_POST['wechat_id'] ?? '',
    'skype_no' => $_POST['skype_no'] ?? '',
    'emergency_contact_name' => $_POST['emergency_contact_name'] ?? '',
    'emergency_gender' => $_POST['emergency_gender'] ?? '',
    'emergency_relationship' => $_POST['emergency_relationship'] ?? '',
    'emergency_tel' => $_POST['emergency_tel'] ?? '',
    'emergency_email' => $_POST['emergency_email'] ?? '',
    'emergency_address' => $_POST['emergency_address'] ?? ''
];

// Check if record exists
$stmt = $pdo->prepare("SELECT id FROM personal_info WHERE user_id = ?");
$stmt->execute([$user_id]);

if ($stmt->rowCount() > 0) {
    // Record exists → update
    $sql = "UPDATE personal_info SET
        given_name = :given_name,
        surname = :surname,
        chinese_name = :chinese_name,
        dob = :dob,
        gender = :gender,
        marital_status = :marital_status,
        country_of_birth = :country_of_birth,
        city_of_birth = :city_of_birth,
        religion = :religion,
        nationality = :nationality,
        native_language = :native_language,
        passport_no = :passport_no,
        passport_expiry = :passport_expiry,
        present_address = :present_address,
        permanent_address = :permanent_address,
        tel = :tel,
        email = :email,
        alternate_email = :alternate_email,
        wechat_id = :wechat_id,
        skype_no = :skype_no,
        emergency_contact_name = :emergency_contact_name,
        emergency_gender = :emergency_gender,
        emergency_relationship = :emergency_relationship,
        emergency_tel = :emergency_tel,
        emergency_email = :emergency_email,
        emergency_address = :emergency_address
        WHERE user_id = :user_id";
} else {
    // New record → insert
    $sql = "INSERT INTO personal_info (
        user_id, given_name, surname, chinese_name, dob, gender, marital_status,
        country_of_birth, city_of_birth, religion, nationality, native_language,
        passport_no, passport_expiry, present_address, permanent_address,
        tel, email, alternate_email, wechat_id, skype_no,
        emergency_contact_name, emergency_gender, emergency_relationship,
        emergency_tel, emergency_email, emergency_address
    ) VALUES (
        :user_id, :given_name, :surname, :chinese_name, :dob, :gender, :marital_status,
        :country_of_birth, :city_of_birth, :religion, :nationality, :native_language,
        :passport_no, :passport_expiry, :present_address, :permanent_address,
        :tel, :email, :alternate_email, :wechat_id, :skype_no,
        :emergency_contact_name, :emergency_gender, :emergency_relationship,
        :emergency_tel, :emergency_email, :emergency_address
    )";
}

try {
    // Bind and execute
    $stmt = $pdo->prepare($sql);
    $data['user_id'] = $user_id;
    $stmt->execute($data);

    echo json_encode(['status' => 'success', 'message' => 'Personal details saved successfully.']);
    exit;
} catch (Exception $e) {
    error_log('save_personal: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Failed to save personal details']);
    exit;
}