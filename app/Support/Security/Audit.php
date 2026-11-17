<?php

namespace App\Support\Security;

/**
 * Append-only security/audit trail kept in a file (storage/logs/audit.log), one JSON object per line.
 * A file is used on purpose: it needs no database change. Never log passwords or tokens.
 */
class Audit
{
    private const MAX_BYTES = 3 * 1024 * 1024;

    public static function path(): string
    {
        return storage_path('logs/audit.log');
    }

    public static function record(string $event, array $context = []): void
    {
        try {
            $request = request();
            $line = json_encode([
                't' => gmdate('Y-m-d H:i:s'),
                'event' => $event,
                'user' => $request?->session()?->get('user_id'),
                'ip' => $request?->ip(),
                'ua' => mb_substr((string) $request?->userAgent(), 0, 120),
                'ctx' => $context,
            ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . "\n";

            $path = self::path();
            if (is_file($path) && @filesize($path) >= self::MAX_BYTES) {
                @rename($path, $path . '.1');
            }
            @file_put_contents($path, $line, FILE_APPEND | LOCK_EX);
        } catch (\Throwable) {
            // auditing must never break a request
        }
    }

    /** Newest first. @return array<int, array<string, mixed>> */
    public static function recent(int $limit = 300): array
    {
        $path = self::path();
        if (! is_file($path)) {
            return [];
        }
        $lines = [];
        foreach (array_slice(file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [], -$limit) as $l) {
            $row = json_decode($l, true);
            if (is_array($row)) {
                $lines[] = $row;
            }
        }
        return array_reverse($lines);
    }
}
