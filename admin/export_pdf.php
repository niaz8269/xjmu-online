<?php
use PDO;
use PDOException;

require_once '../session_config.php';
require '../libs/fpdf.php';
require '../db.php';

// Check if user is logged in and is admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login");
    exit;
}

if (!isset($_GET['user_id'])) {
    die("No user selected.");
}

$user_id = intval($_GET['user_id']);

// Fetch user
$userStmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$userStmt->execute([$user_id]);
$user = $userStmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    die("User not found.");
}

// Personal Info
$personalStmt = $pdo->prepare("SELECT * FROM personal_info WHERE user_id = ?");
$personalStmt->execute([$user_id]);
$personal = $personalStmt->fetch(PDO::FETCH_ASSOC);

// Education Records
$eduStmt = $pdo->prepare("SELECT * FROM education_records WHERE user_id = ?");
$eduStmt->execute([$user_id]);
$education = $eduStmt->fetchAll(PDO::FETCH_ASSOC);

// Documents
$docsStmt = $pdo->prepare("SELECT * FROM documents WHERE user_id = ?");
$docsStmt->execute([$user_id]);
$documents = $docsStmt->fetchAll(PDO::FETCH_ASSOC);

// Degree/Major (optional columns)
$appProgram = null;
try {
    $appStmt = $pdo->prepare("SELECT degree_applying_for, major_applying_for FROM applications WHERE user_id = ?");
    $appStmt->execute([$user_id]);
    $appProgram = $appStmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) { }

// Start PDF (FPDF is in global namespace)
$pdf = new \FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Application Details', 0, 1, 'C');

$pdf->SetFont('Arial', '', 12);
$pdf->Ln(10);

// Program Applying For (Degree / Major)
if ($appProgram && (!empty($appProgram['degree_applying_for']) || !empty($appProgram['major_applying_for']))) {
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(0, 10, 'Program Applying For:', 0, 1);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(60, 8, 'Degree:', 0, 0);
    $pdf->Cell(0, 8, $appProgram['degree_applying_for'] ?? 'â€”', 0, 1);
    $pdf->Cell(60, 8, 'Major:', 0, 0);
    $pdf->Cell(0, 8, $appProgram['major_applying_for'] ?? 'â€”', 0, 1);
    $pdf->Ln(5);
}

// Personal Info Section
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Personal Information:', 0, 1);

$pdf->SetFont('Arial', '', 12);
if ($personal) {
    foreach ($personal as $key => $value) {
        if (!in_array($key, ['id', 'user_id', 'created_at'])) {
            $pdf->Cell(60, 8, ucfirst(str_replace('_', ' ', $key)) . ':', 0, 0);
            $pdf->Cell(0, 8, $value, 0, 1);
        }
    }
} else {
    $pdf->Cell(0, 8, 'No personal information found.', 0, 1);
}

$pdf->Ln(5);

// Education Section
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Education Records:', 0, 1);
$pdf->SetFont('Arial', '', 12);

if ($education) {
    foreach ($education as $record) {
        $line = "{$record['entry_type']} - {$record['education_level']} in {$record['field_of_study']} from {$record['institute_name']} ({$record['from_date']} to {$record['to_date']})";
        $pdf->MultiCell(0, 8, $line);
    }
} else {
    $pdf->Cell(0, 8, 'No education records found.', 0, 1);
}

$pdf->Ln(5);

// Documents Section
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, 'Uploaded Documents:', 0, 1);
$pdf->SetFont('Arial', '', 12);

if ($documents) {
    foreach ($documents as $doc) {
        $pdf->Cell(0, 8, "- " . $doc['file_name'], 0, 1);
    }
} else {
    $pdf->Cell(0, 8, 'No documents uploaded.', 0, 1);
}

$pdf->Output("I", "Application_User_{$user_id}.pdf");
