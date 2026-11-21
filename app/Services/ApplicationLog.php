<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/** Staff notes and status history of an application (table crm_application_log). Writing never throws. */
class ApplicationLog
{
    public static function note(int $userId, string $note, ?Request $request = null): void
    {
        self::write($userId, 'note', ['body' => mb_substr(trim($note), 0, 5000)], $request);
    }

    public static function status(int $userId, ?string $old, ?string $new, ?string $remark, ?Request $request = null): void
    {
        self::write($userId, 'status', ['old_status' => $old, 'new_status' => $new, 'body' => $remark !== '' ? $remark : null], $request);
    }

    public static function email(int $userId, string $subject): void
    {
        self::write($userId, 'email', ['body' => 'Email sent to the applicant: ' . $subject], null);
    }

    /** @return array<int, array<string, mixed>> newest first */
    public static function for(int $userId): array
    {
        try {
            return DB::table('crm_application_log')->where('user_id', $userId)->orderByDesc('id')->get()->map(fn ($r) => (array) $r)->all();
        } catch (\Throwable $e) {
            return [];
        }
    }

    private static function write(int $userId, string $type, array $data, ?Request $request): void
    {
        try {
            $session = $request?->session();
            DB::table('crm_application_log')->insert($data + [
                'user_id' => $userId, 'type' => $type,
                'admin_id' => $session ? (int) $session->get('user_id') ?: null : null,
                'admin_name' => $session ? (string) ($session->get('full_name') ?: 'Admin') : ($type === 'email' ? 'System' : 'Applicant'),
                'created_at' => now(),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Application log write failed: ' . $e->getMessage());
        }
    }
}
