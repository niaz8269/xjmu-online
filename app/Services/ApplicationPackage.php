<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Document;
use App\Models\EducationRecord;
use App\Models\PersonalInfo;

/**
 * Builds the applicant's download: an "Application Summary" PDF (FPDF) plus every uploaded document, zipped as
 * Application_Package_<Given>_<Surname>.zip. Work happens in storage/legacy/temp_zip/<name>, as in the original.
 */
class ApplicationPackage
{
    /** @return array{zip: string, name: string, folder: string, pdf: string, pdfName: string} */
    public function build(int $userId): array
    {
        require_once base_path('legacy/libs/fpdf.php');

        $personal = PersonalInfo::query()->where('user_id', $userId)->first();
        $personal = $personal ? $personal->getAttributes() : null;

        $program = null;
        try {
            $program = Application::query()->where('user_id', $userId)->first(['degree_applying_for', 'major_applying_for']);
        } catch (\Exception $e) {
            // columns may be missing
        }

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Application Summary', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);

        $pdf->Ln(5);
        if ($program && (! empty($program->degree_applying_for) || ! empty($program->major_applying_for))) {
            $pdf->SetFont('Arial', 'B', 14);
            $pdf->Cell(0, 10, 'Program Applying For:', 0, 1);
            $pdf->SetFont('Arial', '', 12);
            $pdf->Cell(60, 8, 'Degree:', 0, 0);
            $pdf->Cell(0, 8, $this->text($program->degree_applying_for ?? '-'), 0, 1);
            $pdf->Cell(60, 8, 'Major:', 0, 0);
            $pdf->Cell(0, 8, $this->text($program->major_applying_for ?? '-'), 0, 1);
            $pdf->Ln(5);
        }

        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Personal Info:', 0, 1);
        $pdf->SetFont('Arial', '', 12);
        if ($personal) {
            foreach ($personal as $key => $val) {
                if (! in_array($key, ['id', 'user_id', 'created_at'])) {
                    $pdf->Cell(60, 8, ucfirst(str_replace('_', ' ', $key)) . ':', 0, 0);
                    $pdf->Cell(0, 8, $this->text($val), 0, 1);
                }
            }
        } else {
            $pdf->Cell(0, 8, 'No data.', 0, 1);
        }

        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->Cell(0, 10, 'Education Records:', 0, 1);
        $pdf->SetFont('Arial', '', 12);

        $records = EducationRecord::query()->where('user_id', $userId)->get();
        if ($records->isNotEmpty()) {
            foreach ($records as $record) {
                $pdf->MultiCell(0, 8, $this->text("{$record->entry_type} - {$record->education_level} at {$record->institute_name} ({$record->from_date} to {$record->to_date})"));
            }
        } else {
            $pdf->Cell(0, 8, 'No records found.', 0, 1);
        }

        // Applicant name for the folder / ZIP filename
        $applicantName = 'user_' . $userId;
        if ($personal && ! empty($personal['given_name'])) {
            $applicantName = preg_replace('/[^A-Za-z0-9]/', '_', $personal['given_name']) . '_' . preg_replace('/[^A-Za-z0-9]/', '_', (string) $personal['surname']);
        }

        // A unique folder per request, so two downloads at once (or two applicants with the same name) never collide.
        $folder = storage_path('legacy/temp_zip') . "/{$applicantName}_" . bin2hex(random_bytes(4));
        if (! is_dir($folder)) {
            mkdir($folder, 0755, true);
        }
        $pdf->Output('F', "{$folder}/Application_Summary.pdf");

        // Uploaded documents
        foreach (Document::query()->where('user_id', $userId)->get(['file_name', 'file_path']) as $doc) {
            $src = $this->resolvePath((string) $doc->file_path);
            if ($src !== null) {
                copy($src, "{$folder}/" . basename($src));
            }
        }

        $given = $personal['given_name'] ?? 'User';
        $surname = $personal['surname'] ?? $userId;
        $zipName = 'Application_Package_' . preg_replace('/[^A-Za-z0-9]/', '_', (string) $given) . '_' . preg_replace('/[^A-Za-z0-9]/', '_', (string) $surname) . '.zip';
        $zipFile = "{$folder}/{$zipName}";

        $zip = new \ZipArchive();
        if ($zip->open($zipFile, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            $this->cleanup($folder);
            throw new \RuntimeException('Failed to create ZIP file.');
        }
        foreach (scandir($folder) as $file) {
            if ($file !== '.' && $file !== '..' && $file !== $zipName) {
                $zip->addFile("{$folder}/{$file}", $file);
            }
        }
        $zip->close();

        return ['zip' => $zipFile, 'name' => $zipName, 'folder' => $folder,
            'pdf' => "{$folder}/Application_Summary.pdf", 'pdfName' => str_replace(['Application_Package_', '.zip'], ['Application_Summary_', '.pdf'], $zipName)];
    }

    /** FPDF's built-in fonts are Windows-1252: convert UTF-8 so accented names print correctly instead of as garbage. */
    private function text($value): string
    {
        $value = (string) $value;
        $converted = @iconv('UTF-8', 'windows-1252//TRANSLIT//IGNORE', $value);

        return $converted === false ? preg_replace('/[^ -~]/', '?', $value) : $converted;
    }

    /** Remove the working folder (it holds applicant documents, so it never outlives the request). */
    public function cleanup(string $folder): void
    {
        array_map('unlink', glob("{$folder}/*.*") ?: []);
        @rmdir($folder);
    }

    /** Stored paths are relative to the web root (public/), e.g. "uploads/admissions/user_1_doc_1_....pdf". */
    private function resolvePath(string $stored): ?string
    {
        $path = public_path($stored);

        return $stored !== '' && is_file($path) ? $path : null;
    }
}
