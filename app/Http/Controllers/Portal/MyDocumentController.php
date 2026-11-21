<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Admin\SecurityController;
use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

/**
 * my-document?id=N: lets an applicant open a file they uploaded themselves.
 * Applicant files are not reachable by URL (public/.htaccess), so their own links go through here.
 */
class MyDocumentController extends Controller
{
    public function __invoke(Request $request)
    {
        $doc = Document::query()
            ->where('id', (int) $request->query('id'))
            ->where('user_id', $request->session()->get('user_id'))
            ->first(['file_name', 'file_path']);
        $abs = $doc ? SecurityController::resolve((string) $doc->file_path) : null;
        if ($abs === null) {
            abort(404);
        }

        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($abs) ?: 'application/octet-stream';
        $inline = in_array($mime, ['application/pdf', 'image/jpeg', 'image/png', 'image/webp', 'image/gif', 'video/mp4', 'video/webm'], true);

        return response()->file($abs, [
            'Content-Type' => $mime,
            'Content-Disposition' => ($inline ? 'inline' : 'attachment') . '; filename="' . preg_replace('/[^A-Za-z0-9._-]/', '_', basename($abs)) . '"',
            'X-Content-Type-Options' => 'nosniff',
            'Cache-Control' => 'no-store, private',
        ]);
    }
}
