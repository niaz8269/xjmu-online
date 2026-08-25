<?php
require 'session_config.php';
require 'db.php';
require 'libs/fpdf.php';
require_once __DIR__ . '/includes/security.php';

fatal_guard(
    __DIR__ . '/logs/download_errors.log',
    'Unable to generate your application package right now. Please try again shortly or contact support.'
);

if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit;
}

$user_id = $_SESSION['user_id'];

// Check if application is submitted before allowing download
$checkStmt = $pdo->prepare("SELECT is_submitted FROM applications WHERE user_id = ?");
$checkStmt->execute([$user_id]);
$appRow = $checkStmt->fetch(PDO::FETCH_ASSOC);

if (!$appRow || empty($appRow['is_submitted'])) {
    die("Application must be submitted before downloading.");
}

// 1. Generate PDF Summary
$pdf = new \FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Application Summary', 0, 1, 'C');
$pdf->SetFont('Arial', '', 12);

// Personal Info
$stmt = $pdo->prepare("SELECT * FROM personal_info WHERE user_id = ?");
$stmt->execute([$user_id]);
$personal = $stmt->fetch(PDO::FETCH_ASSOC);

// Degree/Major
$appProgram = null;
try {
    $appStmt = $pdo->prepare("SELECT degree_applying_for, major_applying_for FROM applications WHERE user_id = ?");
    $appStmt->execute([$user_id]);
    $appProgram = $appStmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) { }

$pdf->Ln(5);
if ($appProgram && (!empty($appProgram['degree_applying_for']) || !empty($appProgram['major_applying_for']))) {
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Program Applying For:', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(60, 8, 'Degree:', 0, 0);
    $pdf->Cell(0, 8, $appProgram['degree_applying_for'] ?? '—', 0, 1);
    $pdf->Cell(60, 8, 'Major:', 0, 0);
    $pdf->Cell(0, 8, $appProgram['major_applying_for'] ?? '—', 0, 1);
    $pdf->Ln(5);
}

$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Personal Info:', 0, 1);
$pdf->SetFont('Arial', '', 12);
if ($personal) {
    foreach ($personal as $key => $val) {
        if (!in_array($key, ['id', 'user_id', 'created_at'])) {
            $pdf->Cell(60, 8, ucfirst(str_replace('_', ' ', $key)) . ':', 0, 0);
            $pdf->Cell(0, 8, $val, 0, 1);
        }
    }
} else {
    $pdf->Cell(0, 8, 'No data.', 0, 1);
}

// Education Records
$pdf->Ln(5);
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Education Records:', 0, 1);
$pdf->SetFont('Arial', '', 12);

$edu = $pdo->prepare("SELECT * FROM education_records WHERE user_id = ?");
$edu->execute([$user_id]);
$records = $edu->fetchAll(PDO::FETCH_ASSOC);
if ($records) {
    foreach ($records as $record) {
        $line = "{$record['entry_type']} - {$record['education_level']} at {$record['institute_name']} ({$record['from_date']} to {$record['to_date']})";
        $pdf->MultiCell(0, 8, $line);
    }
} else {
    $pdf->Cell(0, 8, 'No records found.', 0, 1);
}

// Prepare applicant name for ZIP filename
$applicantName = 'user_' . $user_id;
if ($personal && $personal['given_name']) {
    $cleanGiven = preg_replace('/[^A-Za-z0-9]/', '_', $personal['given_name']);
    $cleanSurname = preg_replace('/[^A-Za-z0-9]/', '_', $personal['surname']);
    $applicantName = "{$cleanGiven}_{$cleanSurname}";
}

// Save PDF — use absolute path to avoid working-directory ambiguity on iPage
$zipFolder = __DIR__ . "/temp_zip/$applicantName";
$pdfPath = "$zipFolder/Application_Summary.pdf";
if (!is_dir($zipFolder)) mkdir($zipFolder, 0755, true);

// Register cleanup BEFORE anything below can die()/throw, so a mid-way
// failure never leaves applicant PII sitting in this folder indefinitely.
register_shutdown_function(function () use ($zipFolder) {
    array_map('unlink', glob("$zipFolder/*.*"));
    @rmdir($zipFolder);
});

$pdf->Output('F', $pdfPath);

// 2. Copy Uploaded Documents
$docStmt = $pdo->prepare("SELECT file_name, file_path FROM documents WHERE user_id = ?");
$docStmt->execute([$user_id]);
$documents = $docStmt->fetchAll(PDO::FETCH_ASSOC);
foreach ($documents as $doc) {
    $src = $doc['file_path'];
    $dest = "$zipFolder/" . basename($src);
    if (file_exists($src)) copy($src, $dest);
}

// 3. Create ZIP
$applicantGiven = isset($personal['given_name']) ? $personal['given_name'] : 'User';
$applicantSurname = isset($personal['surname']) ? $personal['surname'] : $user_id;

$cleanGiven = preg_replace('/[^A-Za-z0-9]/', '_', $applicantGiven);
$cleanSurname = preg_replace('/[^A-Za-z0-9]/', '_', $applicantSurname);

$applicantName = "{$cleanGiven}_{$cleanSurname}";
$zipFileName = "Application_Package_{$applicantName}.zip";
$zipFile = "$zipFolder/$zipFileName";

$zip = new ZipArchive();
if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
    $files = scandir($zipFolder);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && $file !== basename($zipFile)) {
            $zip->addFile("$zipFolder/$file", $file);
        }
    }
    $zip->close();
} else {
    die("Failed to create ZIP file.");
}

// 4. Download ZIP
header('Content-Type: application/zip');
header('Content-disposition: attachment; filename=' . $zipFileName);
header('Content-Length: ' . filesize($zipFile));
readfile($zipFile);
exit;
