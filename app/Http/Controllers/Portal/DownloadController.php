<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Services\ApplicationPackage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** download_application: the submitted applicant's PDF summary + uploaded documents as one ZIP (?format=pdf: the summary only). */
class DownloadController extends Controller
{
    public function __invoke(Request $request, ApplicationPackage $package)
    {
        $userId = (int) $request->session()->get('user_id');

        if (! Application::query()->where('user_id', $userId)->value('is_submitted')) {
            return response('Application must be submitted before downloading.');
        }

        try {
            $built = $package->build($userId);
        } catch (\RuntimeException $e) {
            return response($e->getMessage());
        } catch (\Throwable $e) {
            // Same friendly 500 + private log as the original fatal_guard()
            $log = storage_path('legacy/logs') . '/download_errors.log';
            if (! is_dir(dirname($log))) {
                @mkdir(dirname($log), 0755, true);
            }
            @error_log('[' . date('Y-m-d H:i:s') . '] Unhandled exception: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine() . "\n", 3, $log);

            return response('Unable to generate your application package right now. Please try again shortly or contact support.', 500);
        }

        // ?format=pdf: only the application summary (easier to open on a phone); default: the full ZIP package.
        $pdfOnly = $request->query('format') === 'pdf';
        $file = $pdfOnly ? $built['pdf'] : $built['zip'];
        $name = $pdfOnly ? $built['pdfName'] : $built['name'];

        $response = new StreamedResponse(function () use ($built, $package, $file) {
            readfile($file);
            $package->cleanup($built['folder']);
        }, 200, [
            'Content-Type' => $pdfOnly ? 'application/pdf' : 'application/zip',
            'Content-Disposition' => 'attachment; filename=' . $name,
            'Content-Length' => (string) filesize($file),
            'Cache-Control' => 'no-store, private',
        ]);

        return $response;
    }
}
