<?php
/**
 * save_data.php — Legacy monolithic save endpoint.
 * NOTE: The active application form uses individual endpoints:
 *   save_personal.php, save_education.php, save_documents.php
 * This file is kept for backward compatibility and uses the
 * canonical table/column names that match the live database schema.
 */
require_once __DIR__ . '/session_config.php';
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/includes/security.php';
json_api_guard();

header('Content-Type: application/json');
csrf_require(true);

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['status' => 'error', 'message' => 'User not logged in']));
}

$userId   = (int) $_SESSION['user_id'];
$response = ['status' => 'error', 'message' => 'Invalid request'];

// Block if application already submitted
try {
    $chk = $pdo->prepare("SELECT is_submitted FROM applications WHERE user_id = ?");
    $chk->execute([$userId]);
    $row = $chk->fetch(PDO::FETCH_ASSOC);
    if ($row && !empty($row['is_submitted'])) {
        die(json_encode(['status' => 'error', 'message' => 'Application is already submitted and cannot be modified']));
    }
} catch (\PDOException $e) {
    // applications table may not exist yet; continue
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("save_data.php POST request received");

    $section  = $_POST['section']   ?? '';
    $formData = $_POST['form_data'] ?? [];

    try {
        $pdo->beginTransaction();

        switch ($section) {

            // ── Personal details ──────────────────────────────────────────
            case 'personal_details':
                $pdo->prepare("
                    INSERT INTO personal_info
                        (user_id, given_name, surname, dob, gender, marital_status,
                         country_of_birth, city_of_birth, religion, nationality, native_language,
                         passport_no, passport_expiry, tel, email, alternate_email,
                         wechat_id, skype_no, present_address, permanent_address,
                         emergency_contact_name, emergency_gender, emergency_relationship,
                         emergency_tel, emergency_email, emergency_address)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                        given_name = VALUES(given_name),
                        surname = VALUES(surname),
                        dob = VALUES(dob),
                        gender = VALUES(gender),
                        marital_status = VALUES(marital_status),
                        country_of_birth = VALUES(country_of_birth),
                        city_of_birth = VALUES(city_of_birth),
                        religion = VALUES(religion),
                        nationality = VALUES(nationality),
                        native_language = VALUES(native_language),
                        passport_no = VALUES(passport_no),
                        passport_expiry = VALUES(passport_expiry),
                        tel = VALUES(tel),
                        email = VALUES(email),
                        alternate_email = VALUES(alternate_email),
                        wechat_id = VALUES(wechat_id),
                        skype_no = VALUES(skype_no),
                        present_address = VALUES(present_address),
                        permanent_address = VALUES(permanent_address),
                        emergency_contact_name = VALUES(emergency_contact_name),
                        emergency_gender = VALUES(emergency_gender),
                        emergency_relationship = VALUES(emergency_relationship),
                        emergency_tel = VALUES(emergency_tel),
                        emergency_email = VALUES(emergency_email),
                        emergency_address = VALUES(emergency_address)")
                ->execute([
                    $userId,
                    $formData['given_name']             ?? '',
                    $formData['surname']                ?? '',
                    $formData['date_of_birth']          ?? ($formData['dob'] ?? ''),
                    $formData['gender']                 ?? '',
                    $formData['marital_status']         ?? '',
                    $formData['country_of_birth']       ?? '',
                    $formData['city_of_birth']          ?? '',
                    $formData['religion']               ?? '',
                    $formData['nationality']            ?? '',
                    $formData['native_language']        ?? '',
                    $formData['passport_no']            ?? '',
                    $formData['passport_expiry']        ?? null,
                    $formData['personal_tel']           ?? ($formData['tel'] ?? ''),
                    $formData['personal_email']         ?? ($formData['email'] ?? ''),
                    $formData['alternate_email']        ?? '',
                    $formData['wechat_id']              ?? '',
                    $formData['skype_no']               ?? '',
                    $formData['personal_address']       ?? ($formData['present_address'] ?? ''),
                    $formData['permanent_address']      ?? ($formData['personal_address'] ?? ''),
                    $formData['emergency_contact_name'] ?? '',
                    $formData['emergency_contact_gender'] ?? ($formData['emergency_gender'] ?? ''),
                    $formData['relationship']           ?? ($formData['emergency_relationship'] ?? ''),
                    $formData['emergency_tel']          ?? '',
                    $formData['emergency_email']        ?? '',
                    $formData['emergency_address']      ?? '',
                ]);
                break;

            // ── Education background ──────────────────────────────────────
            case 'education_background':
                $pdo->prepare("
                    INSERT INTO education_records
                        (user_id, entry_type, education_level, field_of_study,
                         country, institute_name, qualification, from_date, to_date)
                    VALUES (?, 'Highest', ?, ?, ?, ?, ?, ?, ?)
                    ON DUPLICATE KEY UPDATE
                        education_level = VALUES(education_level),
                        field_of_study  = VALUES(field_of_study),
                        country         = VALUES(country),
                        institute_name  = VALUES(institute_name),
                        qualification   = VALUES(qualification),
                        from_date       = VALUES(from_date),
                        to_date         = VALUES(to_date)")
                ->execute([
                    $userId,
                    $formData['education_level']      ?? '',
                    $formData['field_of_study']       ?? '',
                    $formData['country_of_institute'] ?? ($formData['country'] ?? ''),
                    $formData['institute_name']       ?? '',
                    $formData['qualification']        ?? '',
                    $formData['year_from']            ?? ($formData['from_date'] ?? ''),
                    $formData['year_to']              ?? ($formData['to_date'] ?? ''),
                ]);
                break;

            // ── Documents ─────────────────────────────────────────────────
            case 'documents':
                if (!isset($_FILES['document_file']) || $_FILES['document_file']['error'] !== UPLOAD_ERR_OK) {
                    throw new \Exception("No file uploaded or upload error");
                }
                $documentId = (int) ($formData['document_id'] ?? 0);
                $fileName   = basename($_FILES['document_file']['name']);
                $ext        = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                $allowed    = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png'];
                if (!in_array($ext, $allowed, true)) {
                    throw new \Exception("File type not allowed");
                }

                // Size cap (10 MB) — audit #25.
                if ($_FILES['document_file']['size'] > 10 * 1024 * 1024) {
                    throw new \Exception("File too large (max 10 MB)");
                }

                // Verify the real MIME type, not just the extension — audit #25.
                $allowedMime = [
                    'application/pdf',
                    'application/msword',
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'image/jpeg', 'image/png',
                ];
                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $detectedMime = $finfo->file($_FILES['document_file']['tmp_name']);
                if (!in_array($detectedMime, $allowedMime, true)) {
                    throw new \Exception("File content does not match an allowed type");
                }

                $uploadDir = __DIR__ . '/uploads/admissions/';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0755, true);
                }
                $uniqueName = 'user_' . $userId . '_doc_' . $documentId . '_' . time() . '.' . $ext;
                $targetPath = $uploadDir . $uniqueName;

                if (!move_uploaded_file($_FILES['document_file']['tmp_name'], $targetPath)) {
                    throw new \Exception("Failed to save uploaded file");
                }

                $relativePath = 'uploads/admissions/' . $uniqueName;
                $pdo->prepare("
                    INSERT INTO documents (user_id, document_id, file_name, file_path)
                    VALUES (?, ?, ?, ?)")
                ->execute([$userId, $documentId, $fileName, $relativePath]);
                break;

            default:
                throw new \Exception("Invalid section");
        }

        // Upsert draft application row
        $pdo->prepare("
            INSERT INTO applications (user_id, status) VALUES (?, 'draft')
            ON DUPLICATE KEY UPDATE updated_at = NOW()")
            ->execute([$userId]);

        $pdo->commit();
        $response = ['status' => 'success', 'message' => 'Data saved successfully'];

    } catch (\Exception $e) {
        $pdo->rollBack();
        // Log the detail server-side; return a generic message (audit #31).
        error_log("save_data.php error: " . $e->getMessage());
        $response = ['status' => 'error', 'message' => 'Could not save your data. Please check your input and try again.'];
    }
}

echo json_encode($response);
