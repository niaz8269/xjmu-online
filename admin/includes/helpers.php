<?php
/**
 * Admin helpers — file uploads for content modules. Validates extension, real
 * MIME and size, stores under uploads/<subdir>/ with a random name.
 * Returns the web-relative path (e.g. 'uploads/gallery/xxx.jpg'),
 * or null when no file was submitted (so callers keep an existing value on edit).
 */
if (!function_exists('save_upload')) {
    function save_upload($field, $subdir, array $allowedExt, array $allowedMime, $maxBytes) {
        if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
            return null; // nothing uploaded
        }
        if ($_FILES[$field]['error'] !== UPLOAD_ERR_OK) {
            throw new RuntimeException('Upload failed (error code ' . $_FILES[$field]['error'] . ').');
        }
        if ($_FILES[$field]['size'] > $maxBytes) {
            throw new RuntimeException('File too large (max ' . round($maxBytes / 1048576) . ' MB).');
        }
        $ext = strtolower(pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt, true)) {
            throw new RuntimeException('File type not allowed.');
        }
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES[$field]['tmp_name']);
        if (!in_array($mime, $allowedMime, true)) {
            throw new RuntimeException('File content does not match an allowed type.');
        }
        $dir = __DIR__ . '/../../uploads/' . $subdir . '/';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        $name = $subdir . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
        if (!move_uploaded_file($_FILES[$field]['tmp_name'], $dir . $name)) {
            throw new RuntimeException('Could not save the uploaded file.');
        }
        $rel = 'uploads/' . $subdir . '/' . $name;
        // Auto-optimize photos: resize huge images + re-encode to WebP. Never fatal.
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true) && extension_loaded('gd')) {
            try {
                $opt = xjmu_optimize_image($dir . $name, $rel);
                if ($opt) return $opt;
            } catch (\Throwable $e) {
                error_log('image optimize skipped: ' . $e->getMessage());
            }
        }
        return $rel;
    }
}

if (!function_exists('xjmu_optimize_image')) {
    /**
     * Resize (to max width) and re-encode an uploaded raster image to WebP for
     * fast delivery, preserving transparency and aspect ratio. Returns the new
     * web-relative path (may change extension to .webp), or null to keep the
     * original untouched. Designed to fail safe — any problem keeps the upload.
     */
    function xjmu_optimize_image($abs, $rel, $maxW = 1600, $quality = 85) {
        if (!is_file($abs)) return null;
        $info = @getimagesize($abs);
        if (!$info) return null;
        [$w, $h] = $info;
        if ($w < 1 || $h < 1 || ($w * $h) > 30000000) return null; // guard memory on huge files
        $ext = strtolower(pathinfo($abs, PATHINFO_EXTENSION));
        @ini_set('memory_limit', '512M');

        switch ($ext) {
            case 'jpg':
            case 'jpeg': $src = @imagecreatefromjpeg($abs); break;
            case 'png':  $src = @imagecreatefrompng($abs);  break;
            case 'webp': $src = @imagecreatefromwebp($abs); break;
            default: return null;
        }
        if (!$src) return null;

        $scale = min($maxW / $w, 1);
        $nw = max(1, (int) round($w * $scale));
        $nh = max(1, (int) round($h * $scale));

        $dst = imagecreatetruecolor($nw, $nh);
        imagealphablending($dst, false);
        imagesavealpha($dst, true);
        imagefill($dst, 0, 0, imagecolorallocatealpha($dst, 0, 0, 0, 127));
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $nw, $nh, $w, $h);

        $gi = function_exists('gd_info') ? gd_info() : [];
        $canWebp = function_exists('imagewebp') && !empty($gi['WebP Support']);

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

        // If we produced a new .webp file, drop the original upload.
        if ($newAbs !== $abs && is_file($newAbs) && is_file($abs)) @unlink($abs);
        return $newRel;
    }
}

if (!function_exists('IMG_EXT')) {
    function IMG_EXT()  { return ['jpg', 'jpeg', 'png', 'webp']; }
    function IMG_MIME() { return ['image/jpeg', 'image/png', 'image/webp']; }
    function DOC_EXT()  { return ['pdf', 'doc', 'docx']; }
    function DOC_MIME() { return ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document']; }
}
