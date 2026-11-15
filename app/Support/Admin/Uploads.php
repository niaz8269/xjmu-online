<?php

namespace App\Support\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Admin file uploads (was save_upload()/xjmu_optimize_image()/IMG_EXT()... in admin/includes/helpers.php).
 * Stores under public/uploads/<subdir>/ with a random name; returns the web-relative path
 * ('uploads/gallery/xxx.jpg') or null when no file was submitted (callers keep the existing value on edit).
 */
class Uploads
{
    public static function imageExt(): array  { return ['jpg', 'jpeg', 'png', 'webp']; }
    public static function imageMime(): array { return ['image/jpeg', 'image/png', 'image/webp']; }
    public static function docExt(): array    { return ['pdf', 'doc', 'docx']; }
    public static function docMime(): array
    {
        return ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    }

    /** @throws RuntimeException with the original user-facing messages */
    public static function save(Request $request, string $field, string $subdir, array $allowedExt, array $allowedMime, int $maxBytes): ?string
    {
        $file = $request->file($field);
        if (! $file) {
            // Laravel's file() hides uploads that failed; fall back to the raw error code.
            $raw = $_FILES[$field]['error'] ?? null;
            if ($raw === null || $raw === UPLOAD_ERR_NO_FILE) {
                return null;
            }
            throw new RuntimeException('Upload failed (error code ' . $raw . ').');
        }
        if ($file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Upload failed (error code ' . $file->getError() . ').');
        }
        if ($file->getSize() > $maxBytes) {
            throw new RuntimeException('File too large (max ' . round($maxBytes / 1048576) . ' MB).');
        }
        $ext = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
        if (! in_array($ext, $allowedExt, true)) {
            throw new RuntimeException('File type not allowed.');
        }
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file->getRealPath());
        if (! in_array($mime, $allowedMime, true)) {
            throw new RuntimeException('File content does not match an allowed type.');
        }
        $dir = public_path('uploads') . '/' . $subdir . '/';
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $name = $subdir . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        try {
            $file->move($dir, $name);
        } catch (\Throwable $e) {
            throw new RuntimeException('Could not save the uploaded file.');
        }
        $rel = 'uploads/' . $subdir . '/' . $name;
        // Auto-optimize photos: resize huge images + re-encode to WebP. Never fatal.
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true) && extension_loaded('gd')) {
            try {
                $opt = self::optimizeImage($dir . $name, $rel);
                if ($opt) {
                    return $opt;
                }
            } catch (\Throwable $e) {
                Log::warning('image optimize skipped: ' . $e->getMessage());
            }
        }
        return $rel;
    }

    /**
     * Resize (to max width) and re-encode a raster image to WebP, preserving transparency/aspect.
     * Returns the new web-relative path (may change extension to .webp) or null to keep the original.
     */
    public static function optimizeImage(string $abs, string $rel, int $maxW = 1600, int $quality = 85): ?string
    {
        if (! is_file($abs)) return null;
        $info = @getimagesize($abs);
        if (! $info) return null;
        [$w, $h] = $info;
        if ($w < 1 || $h < 1 || ($w * $h) > 30000000) return null;
        $ext = strtolower(pathinfo($abs, PATHINFO_EXTENSION));
        @ini_set('memory_limit', '512M');

        switch ($ext) {
            case 'jpg':
            case 'jpeg': $src = @imagecreatefromjpeg($abs); break;
            case 'png':  $src = @imagecreatefrompng($abs);  break;
            case 'webp': $src = @imagecreatefromwebp($abs); break;
            default: return null;
        }
        if (! $src) return null;

        $scale = min($maxW / $w, 1);
        $nw = max(1, (int) round($w * $scale));
        $nh = max(1, (int) round($h * $scale));

        $dst = imagecreatetruecolor($nw, $nh);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

        $gi = function_exists('gd_info') ? gd_info() : [];
        $canWebp = function_exists('imagewebp') && ! empty($gi['WebP Support']);

        $newAbs = $abs; $newRel = $rel; $ok = false;
        if ($canWebp) {
            $newAbs = preg_replace('/\.(jpe?g|png|webp)$/i', '.webp', $abs);
            $newRel = preg_replace('/\.(jpe?g|png|webp)$/i', '.webp', $rel);
            $ok = imagewebp($dst, $newAbs, $quality);
        } elseif ($ext === 'png') {
            $ok = imagepng($dst, $abs, 8);
        } else {
            $ok = imagejpeg($dst, $abs, $quality);
        }

        imagedestroy($src);
        imagedestroy($dst);
        if (empty($ok)) return null;

        if ($newAbs !== $abs && is_file($newAbs) && is_file($abs)) @unlink($abs);
        return $newRel;
    }
}
