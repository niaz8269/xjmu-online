<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\OpsHelpers;
use App\Services\SiteMailer;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * admin/test_email : sends a test message and shows the SMTP conversation.
 * With SMTP_HOST empty SiteMailer's log driver is active: the message is only recorded, nothing is sent.
 */
class TestEmailController extends AdminController
{
    use OpsHelpers;

    public function __invoke(Request $request, SiteMailer $mailer)
    {
        $envFile = base_path('.env');
        $phpmailerLoaded = class_exists(PHPMailer::class);

        // Same settings (and defaults) the original page read from .env
        $smtpHost = (string) (config('smtp.host') ?: 'smtp.ipage.com');
        $smtpPort = (int) config('smtp.port');
        $smtpUser = (string) (config('smtp.user') ?: 'no-reply@xjmu.online');
        $smtpPass = (string) config('smtp.pass');
        $smtpFrom = (string) (config('smtp.from') ?: $smtpUser);

        $logDir = realpath(storage_path('legacy/logs')) ?: storage_path('legacy/logs');
        $logDirExists = is_dir($logDir);
        $logDirWrite = $logDirExists && is_writable($logDir);
        $phpErrorLog = ini_get('error_log');

        $diag = [
            'PHP version'         => PHP_VERSION,
            'PHPMailer loaded'    => $phpmailerLoaded ? '✅ yes' : '❌ no (check vendor/ or libs/)',
            '.env found'          => file_exists($envFile) ? '✅ yes' : '❌ missing',
            'SMTP host'           => $smtpHost . ':' . $smtpPort,
            'SMTP user'           => $smtpUser,
            'SMTP pass set'       => $smtpPass !== '' ? '✅ yes (' . strlen($smtpPass) . ' chars)' : '❌ empty — will fail auth',
            'logs/ dir exists'    => $logDirExists ? '✅ ' . $logDir : '❌ missing',
            'logs/ writable'      => $logDirWrite ? '✅ yes' : '⚠️ no — debug logs fall back to PHP error_log',
            'PHP error_log path'  => $phpErrorLog ?: '(not configured)',
        ];

        $smtpLog = '';
        $result = null;

        if ($request->isMethod('post')) {
            $to = trim($this->post($request, 'to'));
            $type = $this->post($request, 'type', 'verification');

            if (! filter_var($to, FILTER_VALIDATE_EMAIL)) {
                $result = ['ok' => false, 'msg' => 'Invalid email address.'];
            } elseif (! $phpmailerLoaded) {
                $result = ['ok' => false, 'msg' => 'PHPMailer not loaded — cannot send.'];
            } else {
                $token = bin2hex(random_bytes(32));
                $subject = $type === 'reset'
                    ? 'Reset Your Password - XJMU (TEST)'
                    : 'Verify Your Email - XJMU (TEST)';
                $body = $type === 'reset'
                    ? "<p>Password reset test. Token: <code>{$token}</code></p>"
                    : "<p>Email verification test. Token: <code>{$token}</code></p>";

                if ($mailer->isLogDriver()) {
                    // Fake/log driver: record the message through SiteMailer, never touch the network.
                    $ok = $mailer->sendViaSmtp($to, 'Test Recipient', $subject, $body, strip_tags($body), 'Test email');
                    $result = $ok
                        ? ['ok' => true, 'msg' => '✅ Log driver (SMTP_HOST is empty): the message was recorded in the log, nothing was sent.']
                        : ['ok' => false, 'msg' => '❌ SMTP error: ' . $mailer->getLastError()];
                } else {
                    [$result, $smtpLog] = $this->sendWithDebug($to, $subject, $body, $smtpHost, $smtpPort, $smtpUser, $smtpPass, $smtpFrom);
                }

                // Also log so it shows up in the server's error logs (the log driver already logged through SiteMailer)
                if (! $mailer->isLogDriver()) {
                error_log('[XJMU TEST-EMAIL] To=' . $to . ' | Result=' . ($result['ok'] ? 'sent' : 'failed') . ' | ' . $result['msg']);
                if ($smtpLog !== '') {
                    error_log('[XJMU SMTP-LOG] ' . substr(str_replace("\n", ' | ', $smtpLog), 0, 2000));
                }
                }
            }
        }

        return $this->page('admin.test_email', 'Test Email', 'test_email',
            'Sends a real email and shows the full SMTP conversation in the browser.', [
                'diag' => $diag, 'result' => $result, 'smtpLog' => $smtpLog,
                'post' => $request->isMethod('post') ? $request->post() : [],
            ]);
    }

    /** The original page's direct PHPMailer send with the full SMTP conversation captured. @return array{0: array, 1: string} */
    private function sendWithDebug(string $to, string $subject, string $body, string $host, int $port, string $user, string $pass, string $from): array
    {
        ob_start();
        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host        = $host;
            $mail->SMTPAuth    = true;
            $mail->Username    = $user;
            $mail->Password    = $pass;
            $mail->SMTPSecure  = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port        = $port;
            $mail->SMTPAutoTLS = true;
            $mail->SMTPOptions = ['ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ]];
            $mail->Timeout     = 30;
            $mail->CharSet     = 'UTF-8';
            $mail->SMTPDebug   = 4;          // Full conversation
            $mail->Debugoutput = 'echo';     // Print to the output buffer

            $mail->setFrom($from, 'XJMU Admin');
            $mail->addAddress($to, 'Test Recipient');
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $body;
            $mail->AltBody = strip_tags($body);

            $mail->send();
            $result = ['ok' => true, 'msg' => '✅ SMTP accepted the message — check inbox AND spam/junk folder.'];
        } catch (\Throwable $e) {
            $result = ['ok' => false, 'msg' => '❌ SMTP error: ' . $e->getMessage()];
        }

        return [$result, (string) ob_get_clean()];
    }
}
