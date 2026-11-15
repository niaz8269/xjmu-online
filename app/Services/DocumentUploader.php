<?php

namespace App\Services;

use App\Models\Document;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Applicant document uploads (save_documents). Same rules as the original script: input names `document_<id>`
 * (single or array), 1 MB limit (10 MB for the self-introduction video, id 11), extension whitelist, MIME sniffing
 * with the tolerant PDF/office rules, files kept under public/uploads/admissions (videos: public/uploads/videos),
 * every upload is a new `documents` row.
 */
class DocumentUploader
{
    private const DEFAULT_MAX_BYTES = 1 * 1024 * 1024;
    private const VIDEO_MAX_BYTES = 10 * 1024 * 1024;
    private const VIDEO_DOCUMENT_ID = 11;

    private const EXTENSIONS = ['pdf', 'doc', 'docx', 'jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi'];

    private const MIME_TYPES = [
        'application/pdf', 'application/x-pdf', 'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/octet-stream', 'binary/octet-stream', 'application/zip', 'application/x-zip-compressed',
        'image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'video/mp4', 'video/quicktime', 'video/x-msvideo',
    ];

    private const BINARY_LIKE_MIME = [
        'application/octet-stream', 'binary/octet-stream', 'application/zip', 'application/x-zip-compressed', 'application/x-pdf',
    ];

    /**
     * Flatten the `document_<id>` inputs into [documentId, UploadedFile] pairs. Empty file inputs (UPLOAD_ERR_NO_FILE)
     * are already dropped by Symfony, which is what the original skipped as well.
     *
     * @return array{hasFiles: bool, entries: list<array{0:int,1:UploadedFile}>}
     */
    public function collect(Request $request): array
    {
        $entries = [];
        foreach ($request->allFiles() as $key => $value) {
            if (! preg_match('/^document_(\d+)$/', (string) $key, $m)) {
                continue;
            }
            $stack = is_array($value) ? $value : [$value];
            array_walk_recursive($stack, function ($file) use (&$entries, $m) {
                if ($file instanceof UploadedFile) {
                    $entries[] = [(int) $m[1], $file];
                }
            });
        }

        return ['hasFiles' => $entries !== [], 'entries' => $entries];
    }

    /** @return array{errors: list<string>, success: int} */
    public function store(int $userId, array $entries): array
    {
        $errors = [];
        $success = 0;

        $generalDir = public_path('uploads/admissions');
        $videoDir = public_path('uploads/videos');
        foreach ([$generalDir, $videoDir] as $dir) {
            if (! is_dir($dir)) {
                mkdir($dir, 0777, true);
            }
        }

        $finfo = new \finfo(FILEINFO_MIME_TYPE);

        foreach ($entries as [$documentId, $file]) {
            $error = $file->getError();

            if ($error === UPLOAD_ERR_INI_SIZE || $error === UPLOAD_ERR_FORM_SIZE) {
                $errors[] = "Document {$documentId} exceeds server limit (" . ini_get('upload_max_filesize') . ').';
                $this->log("File size exceeds PHP limit ({$error}) for document {$documentId}", $userId);
                continue;
            }
            if ($error !== UPLOAD_ERR_OK) {
                $errors[] = "Upload error for document {$documentId} (Code: {$error}).";
                $this->log("Upload error code {$error} for document {$documentId}", $userId);
                continue;
            }

            $size = (int) $file->getSize();
            $isVideo = $documentId === self::VIDEO_DOCUMENT_ID;
            $maxBytes = $isVideo ? self::VIDEO_MAX_BYTES : self::DEFAULT_MAX_BYTES;
            $maxMb = $isVideo ? '10' : '1';
            if ($size > $maxBytes) {
                $errors[] = "Document {$documentId} exceeds {$maxMb}MB limit.";
                $this->log("File size {$size} bytes exceeds {$maxMb}MB limit for document {$documentId}", $userId);
                continue;
            }

            $originalName = basename($file->getClientOriginalName());
            $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            if ($extension === '' || ! in_array($extension, self::EXTENSIONS, true)) {
                $errors[] = "Invalid file type for document {$documentId}.";
                continue;
            }

            $mimeType = $finfo->file($file->getPathname());
            $mimeAllowed = $mimeType !== false && in_array($mimeType, self::MIME_TYPES, true);
            $isPdf = $extension === 'pdf';
            $isOfficeLike = in_array($extension, ['pdf', 'doc', 'docx'], true);
            $isBinaryLike = in_array((string) $mimeType, self::BINARY_LIKE_MIME, true);

            // Accept every PDF within the size limit even if the host sniffs the MIME type inconsistently
            if (! $mimeAllowed && ! $isPdf && ! ($isOfficeLike && $isBinaryLike)) {
                $errors[] = "Invalid file content type for document {$documentId} ({$mimeType}).";
                $this->log("Invalid MIME type {$mimeType} for document {$documentId}, file {$originalName}", $userId);
                continue;
            }

            $relativeDir = $isVideo ? 'uploads/videos/' : 'uploads/admissions/';
            $uniqueName = 'user_' . $userId . '_doc_' . $documentId . '_' . time() . '_' . uniqid() . '.' . $extension;

            try {
                $file->move($isVideo ? $videoDir : $generalDir, $uniqueName);
            } catch (\Throwable $e) {
                $errors[] = "Failed to save document {$documentId}.";
                $this->log("Failed to move uploaded file for document {$documentId}", $userId);
                continue;
            }

            Document::query()->insert([
                'user_id' => $userId,
                'document_id' => $documentId,
                'file_name' => $originalName,
                'file_path' => $relativeDir . $uniqueName,
                'uploaded_on' => now(),
            ]);

            $success++;
        }

        return ['errors' => $errors, 'success' => $success];
    }

    public function log(string $message, int $userId): void
    {
        $file = storage_path('legacy/logs/upload_errors.log');
        $dir = dirname($file);
        if (! is_dir($dir)) {
            @mkdir($dir, 0755, true);
        }
        @error_log('[' . date('Y-m-d H:i:s') . "] User ID: {$userId} - {$message}\n", 3, $file);
    }
}
