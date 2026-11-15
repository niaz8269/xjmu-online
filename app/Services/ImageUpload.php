<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

/**
 * Port of the original save_upload() (+ xjmu_optimize_image) for public uploads: validates extension, real MIME
 * and size, stores under public/uploads/<subdir>/<subdir>_<12 hex>.<ext>, optionally optimises to WebP.
 * Returns the web-relative path, or null when nothing was uploaded.
 */
class ImageUpload
{
    public const EXT = ['jpg', 'jpeg', 'png', 'webp'];
    public const MIME = ['image/jpeg', 'image/png', 'image/webp'];

    public function save(?UploadedFile $file, string $subdir, int $maxBytes, array $allowedExt = self::EXT, array $allowedMime = self::MIME): ?string
    {
        if ($file === null) {
            return null;
        }
        if ($file->getError() === UPLOAD_ERR_NO_FILE) {
            return null;
        }
        if ($file->getError() !== UPLOAD_ERR_OK) {
            throw new \RuntimeException('Upload failed (error code ' . $file->getError() . ').');
        }
        if ($file->getSize() > $maxBytes) {
            throw new \RuntimeException('File too large (max ' . round($maxBytes / 1048576) . ' MB).');
        }
        $ext = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
        if (! in_array($ext, $allowedExt, true)) {
            throw new \RuntimeException('File type not allowed.');
        }
        $mime = (new \finfo(FILEINFO_MIME_TYPE))->file($file->getPathname());
        if (! in_array($mime, $allowedMime, true)) {
            throw new \RuntimeException('File content does not match an allowed type.');
        }
        $dir = public_path('uploads') . '/' . $subdir . '/';
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $name = $subdir . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        $file->move($dir, $name);
        $rel = 'uploads/' . $subdir . '/' . $name;

        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true) && extension_loaded('gd')) {
            try {
                $opt = $this->optimize($dir . $name, $rel);
                if ($opt) {
                    return $opt;
                }
            } catch (\Throwable $e) {
                error_log('image optimize skipped: ' . $e->getMessage());
            }
        }
        return $rel;
    }

    /** Resize (max width) and re-encode to WebP; returns the new relative path or null to keep the original. */
    private function optimize(string $abs, string $rel, int $maxW = 1600, int $quality = 85): ?string
    {
        if (! is_file($abs)) return null;
        $info = @getimagesize($abs);
        if (! $info) return null;
        [$w, $h] = $info;
        if ($w < 1 || $h < 1 || ($w * $h) > 30000000) return null;
        $ext = strtolower(pathinfo($abs, PATHINFO_EXTENSION));
        @ini_set('memory_limit', '512M');

        $src = match ($ext) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($abs),
            'png' => @imagecreatefrompng($abs),
            'webp' => @imagecreatefromwebp($abs),
            default => false,
        };
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

        $newAbs = $abs;
        $newRel = $rel;
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
