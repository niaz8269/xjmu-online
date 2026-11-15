<?php

namespace App\Services;

use DateTimeImmutable;
use DateTimeZone;
use PHPMailer\PHPMailer\PHPMailer;

/**
 * Port of the original send_verification_email.php (PHPMailer over the SMTP_* .env settings, multi-config retry,
 * fail-closed when unconfigured) plus the plain mail() path used by the contact / partner / event forms.
 *
 * When SMTP_HOST is empty the "log" driver is used: nothing is sent, messages are kept in $outbox and logged.
 */
class SiteMailer
{
    /** @var array<int, array<string, mixed>> messages "sent" by the log driver (inspected by tests) */
    public array $outbox = [];

    private string $lastError = '';

    /** Strip CR/LF/NUL so a value is safe in a mail header / Subject (header-injection guard, as mail_safe()). */
    public static function headerSafe(string $value): string
    {
        return trim(str_replace(["\r", "\n", "\0", '%0a', '%0d', '%0A', '%0D'], ' ', $value));
    }

    public function isLogDriver(): bool
    {
        return trim((string) config('smtp.host')) === '';
    }

    public function getLastError(): string
    {
        return $this->lastError;
    }

    public function appBaseUrl(): string
    {
        return rtrim(trim((string) config('smtp.base_url')) ?: 'https://xjmu.online', '/');
    }

    public function logEmailError(string $message): void
    {
        $logFile = storage_path('legacy/logs') . '/email_errors.log';
        $logDir = dirname($logFile);

        $tzName = (string) config('smtp.log_timezone');
        try {
            $localTz = new DateTimeZone($tzName);
        } catch (\Exception $e) {
            $localTz = new DateTimeZone('UTC');
            $tzName = 'UTC';
        }
        $local = (new DateTimeImmutable('now', $localTz))->format('Y-m-d H:i:s');
        $utc = (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');
        $formatted = "[$local $tzName | $utc UTC] $message\n";

        $written = false;
        if (! is_dir($logDir)) {
            @mkdir($logDir, 0775, true);
        }
        if (is_dir($logDir) && is_writable($logDir)) {
            // size-based rotation: keep one previous file, ~2 MB each (as the original)
            if (is_file($logFile) && @filesize($logFile) >= 2 * 1024 * 1024) {
                @rename($logFile, $logFile . '.1');
            }
            $written = (@error_log($formatted, 3, $logFile) !== false);
        }
        if (! $written) {
            error_log('[XJMU-EMAIL] ' . $formatted);
        }
    }

    public function sendVerificationEmail(string $email, string $name, string $token): bool
    {
        $verifyUrl = $this->appBaseUrl() . '/verify_email?token=' . urlencode($token);
        $subject = 'Verify Your Email Address - Xinjiang Medical University';
        $htmlBody = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #2c3e50;'>Email Verification</h2>
            <h3>Dear $name,</h3>
            <p>Thank you for registering with Xinjiang Medical University. Please verify your email address to complete your registration.</p>
            <p style='margin: 30px 0;'>
                <a href='$verifyUrl'
                   style='background-color: #3498db; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;'>
                    Verify Email Now
                </a>
            </p>
            <p style='color: #7f8c8d; font-size: 14px;'>
                If the button doesn't work, copy and paste this link into your browser:<br>
                <a href='$verifyUrl'>$verifyUrl</a>
            </p>
            <hr style='border: 1px solid #ecf0f1; margin: 30px 0;'>
            <p style='color: #95a5a6; font-size: 12px;'>
                If you didn't create an account with us, please ignore this email.<br>
                This is an automated message, please do not reply.
            </p>
        </div>
    ";
        $altBody = "Dear $name,\n\nPlease verify your email by visiting: $verifyUrl\n\nIf you didn't create an account, ignore this email.";

        return $this->sendViaSmtp($email, $name, $subject, $htmlBody, $altBody, 'Verification email');
    }

    public function sendPasswordResetEmail(string $email, string $name, string $token): bool
    {
        $resetUrl = $this->appBaseUrl() . '/reset_password?token=' . urlencode($token);
        $subject = 'Reset Your Password - Xinjiang Medical University';
        $htmlBody = "
        <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
            <h2 style='color: #2c3e50;'>Password Reset Request</h2>
            <h3>Dear $name,</h3>
            <p>We received a request to reset your password.</p>
            <p style='margin: 30px 0;'>
                <a href='$resetUrl'
                   style='background-color: #e62129; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; display: inline-block;'>
                    Reset Password
                </a>
            </p>
            <p style='color: #7f8c8d; font-size: 14px;'>
                If the button doesn't work, copy and paste this link into your browser:<br>
                <a href='$resetUrl'>$resetUrl</a>
            </p>
            <p style='color: #7f8c8d; font-size: 14px;'>
                This link expires in 1 hour.
            </p>
            <hr style='border: 1px solid #ecf0f1; margin: 30px 0;'>
            <p style='color: #95a5a6; font-size: 12px;'>
                If you did not request this password reset, please ignore this email.
            </p>
        </div>
    ";
        $altBody = "Dear $name,\n\nReset your password here: $resetUrl\n\nThis link expires in 1 hour. If you did not request this, ignore this email.";

        return $this->sendViaSmtp($email, $name, $subject, $htmlBody, $altBody, 'Password reset email');
    }

    /**
     * Plain-text mail through PHP's mail() (what the contact / partner / event forms used). Log driver: records only.
     */
    public function sendPlain(string $to, string $subject, string $body, string $headers): bool
    {
        if ($this->isLogDriver()) {
            $this->outbox[] = ['driver' => 'mail', 'to' => $to, 'subject' => $subject, 'body' => $body, 'headers' => $headers];
            logger()->info('SiteMailer(log): mail() to ' . $to . ' - ' . $subject);
            return true;
        }
        return (bool) @mail($to, $subject, $body, $headers);
    }

    public function sendViaSmtp(string $email, string $name, string $subject, string $htmlBody, string $altBody, string $logLabel = 'Email'): bool
    {
        $this->lastError = '';

        if ($this->isLogDriver()) {
            $this->outbox[] = ['driver' => 'smtp', 'to' => $email, 'name' => $name, 'subject' => $subject, 'html' => $htmlBody, 'text' => $altBody];
            logger()->info("SiteMailer(log): {$logLabel} to {$email} - {$subject}");
            return true;
        }

        if (! class_exists(PHPMailer::class)) {
            $this->lastError = 'PHPMailer class could not be loaded';
            $this->logEmailError("{$logLabel} failed for {$email} - PHPMailer class could not be loaded");
            return false;
        }

        $smtpHost = (string) config('smtp.host');
        $smtpPort = (int) config('smtp.port');
        $smtpUser = (string) config('smtp.user');
        $smtpPass = (string) config('smtp.pass');

        // Fail closed if SMTP is not configured — never ship hardcoded credentials.
        if ($smtpHost === '' || $smtpUser === '' || $smtpPass === '') {
            $this->lastError = 'SMTP is not configured (SMTP_HOST/SMTP_USER/SMTP_PASS missing in .env)';
            $this->logEmailError("{$logLabel} skipped for {$email} - SMTP not configured in .env");
            return false;
        }
        $smtpFrom = config('smtp.from') ?? $smtpUser;
        $smtpFromName = (string) config('smtp.from_name');
        $replyTo = config('smtp.reply_to') ?? $smtpFrom;
        $smtpTimeout = (int) config('smtp.timeout');
        $smtpDebug = (int) config('smtp.debug');
        $mailDomain = (string) config('smtp.mail_domain');
        $dkimDomain = (string) config('smtp.dkim_domain');
        $dkimSelector = (string) config('smtp.dkim_selector');
        $dkimKey = (string) config('smtp.dkim_key_path');
        $dkimIdentity = config('smtp.dkim_identity') ?? $smtpFrom;

        $secure = strtolower(trim((string) config('smtp.secure')));
        $primarySecure = ($secure === 'ssl' || $secure === 'smtps') ? 'ssl' : 'tls';

        $configs = [
            ['host' => $smtpHost, 'port' => $smtpPort, 'secure' => $primarySecure],
            ['host' => $smtpHost, 'port' => ($smtpPort === 465 ? 587 : 465), 'secure' => ($primarySecure === 'ssl' ? 'tls' : 'ssl')],
        ];
        foreach (array_filter(array_map('trim', explode(',', (string) config('smtp.fallback_hosts')))) as $host) {
            $configs[] = ['host' => $host, 'port' => 587, 'secure' => 'tls'];
            $configs[] = ['host' => $host, 'port' => 465, 'secure' => 'ssl'];
        }
        $configs = array_map(fn ($c) => json_decode($c, true), array_values(array_unique(array_map('json_encode', $configs))));

        foreach ($configs as $index => $cfg) {
            try {
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = $cfg['host'];
                $mail->SMTPAuth = true;
                $mail->Username = $smtpUser;
                $mail->Password = $smtpPass;
                $mail->SMTPSecure = $cfg['secure'];
                $mail->Port = $cfg['port'];
                $mail->SMTPAutoTLS = true;
                $allowInsecure = (string) config('smtp.allow_insecure') === '1';
                $mail->SMTPOptions = ['ssl' => [
                    'verify_peer' => ! $allowInsecure,
                    'verify_peer_name' => ! $allowInsecure,
                    'allow_self_signed' => $allowInsecure,
                ]];
                $mail->Timeout = $smtpTimeout;
                $mail->SMTPKeepAlive = false;
                $mail->CharSet = 'UTF-8';
                $mail->SMTPDebug = $smtpDebug;
                $mail->Debugoutput = function ($str, $level) use ($email, $logLabel) {
                    $this->logEmailError("[SMTP-DEBUG L{$level}] {$logLabel} → {$email}: " . trim($str));
                };

                $mail->setFrom($smtpFrom, $smtpFromName);
                $mail->Sender = $smtpFrom;
                $mail->addAddress($email, $name);
                $mail->addReplyTo($replyTo, 'XJMU Admissions');
                $mail->Hostname = $mailDomain;
                $mail->MessageID = sprintf('<%s@%s>', bin2hex(random_bytes(16)), $mailDomain);
                $mail->isHTML(true);
                $mail->Encoding = 'base64';
                $mail->Subject = $subject;
                $mail->Body = $htmlBody;
                $mail->AltBody = $altBody;

                if ($dkimDomain !== '' && $dkimSelector !== '' && $dkimKey !== '' && file_exists($dkimKey)) {
                    $mail->DKIM_domain = $dkimDomain;
                    $mail->DKIM_selector = $dkimSelector;
                    $mail->DKIM_private = $dkimKey;
                    $mail->DKIM_identity = $dkimIdentity;
                }

                $mail->send();
                $this->logEmailError("{$logLabel} sent via {$cfg['host']}:{$cfg['port']} to {$email} | Message-ID: " . $mail->getLastMessageID());
                return true;
            } catch (\Throwable $e) {
                $this->lastError = $e->getMessage();
                $this->logEmailError('SMTP attempt ' . ($index + 1) . " failed on {$cfg['host']}:{$cfg['port']} for {$email} ({$logLabel}) - " . $e->getMessage());
            }
        }

        $this->logEmailError("All SMTP configurations failed for {$logLabel} to {$email}");
        return false;
    }
}
