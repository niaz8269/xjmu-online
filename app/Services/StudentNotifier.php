<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Emails to applicants: "application received" and "status changed".
 * OFF by default: nothing is sent until PORTAL_NOTIFY_STUDENTS=true is set in .env (config/portal.php),
 * so the wording can be approved first. Sending never throws and never blocks the action that triggered it.
 */
class StudentNotifier
{
    public function __construct(private SiteMailer $mailer)
    {
    }

    public static function enabled(): bool
    {
        return (bool) config('portal.notify_students', false);
    }

    public function submitted(int $userId): bool
    {
        return $this->send($userId, 'Your application has been received - Xinjiang Medical University', 'emails.application-submitted', []);
    }

    public function statusChanged(int $userId, string $status, ?string $remark): bool
    {
        if (! in_array($status, ['Approved', 'Rejected'], true) && trim((string) $remark) === '') {
            return false;   // nothing worth an email (for example back to "Pending" with no remark)
        }

        return $this->send($userId, 'Update on your application - Xinjiang Medical University', 'emails.application-status',
            ['status' => $status, 'remark' => trim((string) $remark)]);
    }

    private function send(int $userId, string $subject, string $view, array $data): bool
    {
        if (! self::enabled()) {
            return false;
        }
        try {
            $user = DB::table('users')->where('id', $userId)->first(['full_name', 'email']);
            if (! $user || ! filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
                return false;
            }
            $data += ['name' => (string) $user->full_name, 'portalUrl' => 'https://xjmu.online/login'];
            $html = view($view, $data)->render();
            $text = trim(preg_replace('/\n{3,}/', "\n\n", strip_tags(str_replace(['<br>', '</p>', '</li>'], "\n", $html))));
            $sent = $this->mailer->sendViaSmtp((string) $user->email, (string) $user->full_name, $subject, $html, $text, 'Applicant email');
            if ($sent) {
                ApplicationLog::email($userId, $subject);
            }

            return $sent;
        } catch (\Throwable $e) {
            Log::warning('Applicant email failed: ' . $e->getMessage());

            return false;
        }
    }
}
