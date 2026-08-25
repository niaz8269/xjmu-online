<?php
use PDO;
use Exception;
/**
 * IMPROVED VERSION - Added File Upload Validation
 * 
 * FIXES:
 * 1. File size validation (max 1.5MB per file)
 * 2. File type validation (MIME type checking)
 * 3. Allowed extensions whitelist
 * 4. Error logging
 * 5. Better error handling
 */

require 'session_config.php';
require 'db.php';
require_once __DIR__ . '/includes/security.php';
json_api_guard();

header('Content-Type: application/json');
csrf_require(true);

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo json_encode(['status' => 'error', 'errors' => ['User not logged in.']]);
    exit;
}

// Configuration
$defaultMaxFileSize = (int)(1 * 1024 * 1024); // 1MB for most documents
$videoMaxFileSize = (int)(10 * 1024 * 1024); // 10MB for self-introduction video (document_id 11)
$allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi'];
$allowedMimeTypes = [
    'application/pdf',
    'application/x-pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/octet-stream',
    'binary/octet-stream',
    'application/zip',
    'application/x-zip-compressed',
    'image/jpeg',
    'image/jpg',
    'image/png',
    'image/gif',
    'video/mp4',
    'video/quicktime',
    'video/x-msvideo'
];

// Base upload directories
$upload_dir_general = "uploads/admissions/";
$upload_dir_videos = "uploads/videos/";

// Ensure directories exist
if (!is_dir($upload_dir_general)) mkdir($upload_dir_general, 0777, true);
if (!is_dir($upload_dir_videos)) mkdir($upload_dir_videos, 0777, true);

// Error logging function
function logError($message, $user_id) {
    $logFile = 'logs/upload_errors.log';
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) mkdir($logDir, 0755, true);
    $timestamp = date('Y-m-d H:i:s');
    error_log("[$timestamp] User ID: $user_id - $message\n", 3, $logFile);
}

$errors = [];
$successCount = 0;

// Block edits if application is submitted
$statusStmt = $pdo->prepare("SELECT is_submitted FROM applications WHERE user_id = ?");
$statusStmt->execute([$user_id]);
$statusRow = $statusStmt->fetch(PDO::FETCH_ASSOC);
if (!empty($statusRow['is_submitted'])) {
    echo json_encode(['status' => 'error', 'errors' => ['Application is submitted and cannot be edited.']]);
    exit;
}

// Check if any files were actually selected for upload
$hasFiles = false;
if (!empty($_FILES)) {
    foreach ($_FILES as $key => $file) {
        if (preg_match('/^document_(\d+)$/', $key)) {
            $fileCount = is_array($file['error']) ? count($file['error']) : 1;
            for ($i = 0; $i < $fileCount; $i++) {
                $currentError = is_array($file['error']) ? $file['error'][$i] : $file['error'];
                if ($currentError !== UPLOAD_ERR_NO_FILE) {
                    $hasFiles = true;
                    break;
                }
            }
            if ($hasFiles) break;
        }
    }
}

if (!$hasFiles) {
    echo json_encode(['status' => 'error', 'errors' => ['Please select at least one file before clicking Verify and Save.']]);
    exit;
}

// Only process if files were selected
try {
    $finfo = new finfo(FILEINFO_MIME_TYPE);

    foreach ($_FILES as $key => $file) {
        if (!preg_match('/^document_(\d+)$/', $key, $matches)) {
            continue;
        }

        $documentId = (int)$matches[1];

        // Handle multiple files per input
        $fileCount = is_array($file['error']) ? count($file['error']) : 1;
        
        for ($i = 0; $i < $fileCount; $i++) {
            $currentError = is_array($file['error']) ? $file['error'][$i] : $file['error'];
            $currentSize = is_array($file['size']) ? $file['size'][$i] : $file['size'];
            $currentTmpName = is_array($file['tmp_name']) ? $file['tmp_name'][$i] : $file['tmp_name'];
            $currentName = is_array($file['name']) ? $file['name'][$i] : $file['name'];

            if ($currentError === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            // Map PHP upload error codes to user-friendly messages
            if ($currentError === UPLOAD_ERR_INI_SIZE || $currentError === UPLOAD_ERR_FORM_SIZE) {
                $maxMB = ini_get('upload_max_filesize');
                $errors[] = "Document {$documentId} exceeds server limit ({$maxMB}).";
                logError("File size exceeds PHP limit ({$currentError}) for document {$documentId}, file size: {$currentSize} bytes", $user_id);
                continue;
            }

            if ($currentError !== UPLOAD_ERR_OK) {
                $errors[] = "Upload error for document {$documentId} (Code: {$currentError}).";
                logError("Upload error code {$currentError} for document {$documentId}", $user_id);
                continue;
            }

            // Determine max file size based on document type (video = 10MB, others = 1MB)
            $maxFileSize = ($documentId === 11) ? $videoMaxFileSize : $defaultMaxFileSize;
            $maxFileSizeMB = ($documentId === 11) ? '10' : '1';
            
            if ($currentSize > $maxFileSize) {
                $errors[] = "Document {$documentId} exceeds {$maxFileSizeMB}MB limit.";
                logError("File size {$currentSize} bytes exceeds {$maxFileSizeMB}MB limit for document {$documentId}", $user_id);
                continue;
            }

            $originalName = basename($currentName);
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            if ($extension === '' || !in_array($extension, $allowedExtensions, true)) {
                $errors[] = "Invalid file type for document {$documentId}.";
                continue;
            }

            $mimeType = $finfo->file($currentTmpName);
            $mimeAllowed = $mimeType !== false && in_array($mimeType, $allowedMimeTypes, true);
            $isPdf = $extension === 'pdf';

            // Be tolerant for office docs that many hosts report as generic binary/zip
            $isOfficeLike = in_array($extension, ['pdf', 'doc', 'docx'], true);
            $isBinaryLikeMime = in_array((string)$mimeType, [
                'application/octet-stream',
                'binary/octet-stream',
                'application/zip',
                'application/x-zip-compressed',
                'application/x-pdf'
            ], true);

            // Requirement: accept every PDF within size limit even if MIME is reported inconsistently by host
            if (!$mimeAllowed && !$isPdf && !($isOfficeLike && $isBinaryLikeMime)) {
                $errors[] = "Invalid file content type for document {$documentId} ({$mimeType}).";
                logError("Invalid MIME type {$mimeType} for document {$documentId}, file {$originalName}", $user_id);
                continue;
            }

            $targetDir = ($documentId === 11) ? $upload_dir_videos : $upload_dir_general;
            $uniqueName = 'user_' . $user_id . '_doc_' . $documentId . '_' . time() . '_' . uniqid() . '.' . $extension;
            $targetPath = $targetDir . $uniqueName;

            // No longer delete previous files - allow multiple uploads per document type

            if (!move_uploaded_file($currentTmpName, $targetPath)) {
                $errors[] = "Failed to save document {$documentId}.";
                logError("Failed to move uploaded file for document {$documentId}", $user_id);
                continue;
            }

            // Insert as new record - allow multiple documents per document_id
            $stmt = $pdo->prepare(
                "INSERT INTO documents (user_id, document_id, file_name, file_path)
                 VALUES (?, ?, ?, ?)"
            );
            $stmt->execute([$user_id, $documentId, $originalName, $targetPath]);

            $successCount++;
        }
    }
} catch (Exception $e) {
    logError('Unhandled upload exception: ' . $e->getMessage(), (int)$user_id);
    echo json_encode(['status' => 'error', 'errors' => ['Upload failed due to a server error. Please try again.']]);
    exit;
}
// If no files were selected, don't add an error - just redirect silently
// The form was submitted but no files were chosen, which is OK

// Redirect with success/error message
if (!empty($errors)) {
    $_SESSION['upload_errors'] = $errors;
    echo json_encode(['status' => 'error', 'errors' => $errors]);
    exit;
}

if ($successCount > 0) {
    echo json_encode(['status' => 'success', 'message' => "$successCount file(s) uploaded successfully."]);
    exit;
}
echo json_encode(['status' => 'error', 'errors' => ['No files were processed. Please check selected files and try again.']]);
exit;
