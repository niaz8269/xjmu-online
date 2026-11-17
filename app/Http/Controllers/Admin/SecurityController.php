<?php

namespace App\Http\Controllers\Admin;

use App\Support\Security\Audit;
use Illuminate\Http\Request;

/** Admin-only: applicant document viewer (the files are no longer reachable by URL) and the activity log. */
class SecurityController extends AdminController
{
    /** Folders that hold applicants' personal documents. */
    private const PRIVATE_DIRS = ['uploads/admissions', 'uploads/videos'];

    /** GET /admin/document?p=uploads/admissions/<file> */
    public function document(Request $request)
    {
        $abs = self::resolve((string) $request->query('p', ''));
        if ($abs === null) {
            abort(404);
        }
        Audit::record('document.view', ['file' => basename($abs)]);

        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($abs) ?: 'application/octet-stream';
        $inline = in_array($mime, ['application/pdf', 'image/jpeg', 'image/png', 'image/webp', 'image/gif', 'video/mp4', 'video/webm'], true);

        return response()->file($abs, [
            'Content-Type' => $mime,
            'Content-Disposition' => ($inline ? 'inline' : 'attachment') . '; filename="' . preg_replace('/[^A-Za-z0-9._-]/', '_', basename($abs)) . '"',
            'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => "default-src 'none'; img-src 'self' data:; media-src 'self'; style-src 'unsafe-inline'; sandbox",
        ]);
    }

    /** GET /admin/activity */
    public function activity()
    {
        $events = Audit::recent(400);
        $since = gmdate('Y-m-d H:i:s', time() - 86400);
        $failed24h = count(array_filter($events, fn ($e) => $e['event'] === 'login.failed' && $e['t'] >= $since));
        $locked24h = count(array_filter($events, fn ($e) => $e['event'] === 'login.locked' && $e['t'] >= $since));

        return $this->page('admin.activity', 'Activity & Security', 'activity',
            'Sign-ins, document views and every change made in the admin panel', compact('events', 'failed24h', 'locked24h'));
    }

    /** Absolute path of a private upload, or null when the request is not a plain file inside the private folders. */
    public static function resolve(string $relative): ?string
    {
        $relative = ltrim(str_replace(chr(92), '/', $relative), '/');
        if ($relative === '' || str_contains($relative, "\0") || str_contains($relative, '..')) {
            return null;
        }
        foreach (self::PRIVATE_DIRS as $dir) {
            if (! str_starts_with($relative, $dir . '/')) {
                continue;
            }
            $base = realpath(public_path($dir));
            $real = realpath(public_path($relative));
            if ($base !== false && $real !== false && is_file($real) && str_starts_with($real, $base . DIRECTORY_SEPARATOR)) {
                return $real;
            }
        }
        return null;
    }
}
