<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Services\ApplicationPackage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/** download_application: the submitted applicant's PDF summary + uploaded documents as one ZIP. */
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

        $response = new StreamedResponse(function () use ($built, $package) {
            readfile($built['zip']);
            $package->cleanup($built['folder']);
        }, 200, [
            'Content-Type' => 'application/zip',
            'Content-Disposition' => 'attachment; filename=' . $built['name'],
            'Content-Length' => (string) filesize($built['zip']),
        ]);

        return $response;
    }
}
