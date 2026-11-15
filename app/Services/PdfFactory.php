<?php

namespace App\Services;

/**
 * Loads the bundled FPDF library (legacy/libs/fpdf.php, outside public/) and builds documents.
 * Shared by the admin "Export as PDF" and "Download application package" endpoints.
 */
class PdfFactory
{
    public function make(): \FPDF
    {
        if (! class_exists(\FPDF::class, false)) {
            require_once base_path('legacy/libs/fpdf.php');
        }
        return new \FPDF();
    }
}
