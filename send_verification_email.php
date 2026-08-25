<?php
function ensureMailerClassesLoaded() {
    if (class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        return true;
    }

    $autoload = __DIR__ . '/vendor/autoload.php';
    if (file_exists($autoload)) {
        require_once $autoload;
    }

    if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
        $fallbackFiles = [
            __DIR__ . '/vendor/phpmailer/phpmailer/src/Exception.php',
            __DIR__ . '/vendor/phpmailer/phpmailer/src/SMTP.php',
            __DIR__ . '/vendor/phpmailer/phpmailer/src/PHPMailer.php',
            __DIR__ . '/libs/PHPMailer/Exception.php',
            __DIR__ . '/libs/PHPMailer/SMTP.php',
            __DIR__ . '/libs/PHPMailer/PHPMailer.php',
        ];

        foreach ($fallbackFiles as $file) {
            if (file_exists($file)) {
                require_once $file;
            }
        }
    }

    return class_exists('PHPMailer\\PHPMailer\\PHPMailer');
}

ensureMailerClassesLoaded();

$GLOBALS['LAST_VERIFICATION_EMAIL_ERROR'] = '';
$GLOBALS['LAST_EMAIL_ERROR'] = '';

function loadLocalEnvIfNeeded() {
    if (isset($_ENV['SMTP_HOST']) || !file_exists(__DIR__ . '/.env')) {
        return;
    }

    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

function logEmailError($message) {
    loadLocalEnvIfNeeded();
    $logFile = __DIR__ . '/logs/email_errors.log';
    $logDir = dirname($logFile);

    $tzName = $_ENV['APP_TIMEZONE'] ?? 'Asia/Karachi';
    try {
        $localTz = new DateTimeZone($tzName);
    } catch (\Exception $e) {
        $localTz = new DateTimeZone('UTC');
        $tzName = 'UTC';
    }

    $localTimestamp = (new DateTimeImmutable('now', $localTz))->format('Y-m-d H:i:s');
    $utcTimestamp = (new DateTimeImmutable('now', new DateTimeZone('UTC')))->format('Y-m-d H:i:s');
    $formatted = "[$localTimestamp $tzName | $utcTimestamp UTC] $message\n";

    // Try to write to the custom log file; fall back to PHP's error_log on failure
    $written = false;
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0775, true);
    }
    if (is_dir($logDir) && is_writable($logDir)) {
        // Size-based rotation: keep one previous file, cap each at ~2 MB so the
        // log can never grow unbounded again (audit #5 / #27).
        $maxBytes = 2 * 1024 * 1024;
        if (is_file($logFile) && @filesize($logFile) >= $maxBytes) {
            @rename($logFile, $logFile . '.1'); // overwrites any prior .1
        }
        $written = (@error_log($formatted, 3, $logFile) !== false);
    }
    if (!$written) {
        // Fallback: write to PHP's own error log (visible in cPanel Error Logs)
        error_log('[XJMU-EMAIL] ' . $formatted);
    }
}

function appBaseUrl() {
    loadLocalEnvIfNeeded();
    // Prefer an explicit APP_URL (set this to http://xjmu.localhost for local dev),
    // otherwise fall back to the production canonical host.
    $url = trim($_ENV['APP_URL'] ?? '');
    if ($url === '') {
        $url = 'https://xjmu.online';
    }
    return rtrim($url, '/');
}

function getLastVerificationEmailError() {
    return $GLOBALS['LAST_VERIFICATION_EMAIL_ERROR'] ?? '';
}

function getLastEmailError() {
    return $GLOBALS['LAST_EMAIL_ERROR'] ?? '';
}

function sendEmailViaConfiguredSmtp($email, $name, $subject, $htmlBody, $altBody, $logLabel = 'Email') {
    loadLocalEnvIfNeeded();

    if (!ensureMailerClassesLoaded()) {
        $GLOBALS['LAST_EMAIL_ERROR'] = 'PHPMailer class could not be loaded';
        logEmailError("{$logLabel} failed for {$email} - PHPMailer class could not be loaded");
        return false;
    }

    $smtpHost = $_ENV['SMTP_HOST'] ?? '';
    $smtpPort = (int)($_ENV['SMTP_PORT'] ?? 587);
    $smtpUser = $_ENV['SMTP_USER'] ?? '';
    $smtpPass = $_ENV['SMTP_PASS'] ?? '';

    // Fail closed if SMTP is not configured — never ship hardcoded credentials.
    if ($smtpHost === '' || $smtpUser === '' || $smtpPass === '') {
        $GLOBALS['LAST_EMAIL_ERROR'] = 'SMTP is not configured (SMTP_HOST/SMTP_USER/SMTP_PASS missing in .env)';
        logEmailError("{$logLabel} skipped for {$email} - SMTP not configured in .env");
        return false;
    }
    $smtpFrom = $_ENV['SMTP_FROM'] ?? $smtpUser;
    $smtpFromName = $_ENV['SMTP_FROM_NAME'] ?? 'Xinjiang Medical University';
    $replyTo = $_ENV['SMTP_REPLY_TO'] ?? $smtpFrom;
    $smtpTimeout = (int)($_ENV['SMTP_TIMEOUT'] ?? 30);
    $smtpDebug = (int)($_ENV['SMTP_DEBUG'] ?? 0);
    $fallbackHosts = $_ENV['SMTP_FALLBACK_HOSTS'] ?? 'smtp.ipage.com';
    $mailDomain = $_ENV['MAIL_DOMAIN'] ?? 'xjmu.online';
    $dkimDomain = $_ENV['SMTP_DKIM_DOMAIN'] ?? '';
    $dkimSelector = $_ENV['SMTP_DKIM_SELECTOR'] ?? '';
    $dkimPrivateKeyPath = $_ENV['SMTP_DKIM_PRIVATE_KEY_PATH'] ?? '';
    $dkimIdentity = $_ENV['SMTP_DKIM_IDENTITY'] ?? $smtpFrom;

    $secureFromEnv = strtolower(trim($_ENV['SMTP_SECURE'] ?? 'starttls'));
    if ($secureFromEnv === 'ssl' || $secureFromEnv === 'smtps') {
        $primarySecure = 'ssl';
    } else {
        $primarySecure = 'tls';
    }

    $smtp_configs = [];
    $smtp_configs[] = ['host' => $smtpHost, 'port' => $smtpPort, 'secure' => $primarySecure];
    $smtp_configs[] = [
        'host' => $smtpHost,
        'port' => ($smtpPort === 465 ? 587 : 465),
        'secure' => ($primarySecure === 'ssl' ? 'tls' : 'ssl')
    ];

    $fallbackHostList = array_filter(array_map('trim', explode(',', $fallbackHosts)));
    foreach ($fallbackHostList as $host) {
        $smtp_configs[] = ['host' => $host, 'port' => 587, 'secure' => 'tls'];
        $smtp_configs[] = ['host' => $host, 'port' => 465, 'secure' => 'ssl'];
    }

    $smtp_configs = array_values(array_unique(array_map(function ($cfg) {
        return json_encode($cfg);
    }, $smtp_configs)));
    $smtp_configs = array_map(function ($cfg) {
        return json_decode($cfg, true);
    }, $smtp_configs);

    $GLOBALS['LAST_EMAIL_ERROR'] = '';

    foreach ($smtp_configs as $index => $config) {
        try {
            $mailerClass = 'PHPMailer\\PHPMailer\\PHPMailer';
            $mail = new $mailerClass(true);
            $mail->isSMTP();
            $mail->Host = $config['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $smtpUser;
            $mail->Password = $smtpPass;
            $mail->SMTPSecure = $config['secure'];
            $mail->Port = $config['port'];
            $mail->SMTPAutoTLS = true;
            // Verify the SMTP server's TLS certificate by default. Only relax this
            // for explicitly trusted/self-signed local relays via SMTP_ALLOW_INSECURE=1.
            $allowInsecureTls = ($_ENV['SMTP_ALLOW_INSECURE'] ?? '0') === '1';
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => !$allowInsecureTls,
                    'verify_peer_name' => !$allowInsecureTls,
                    'allow_self_signed' => $allowInsecureTls,
                ],
            ];

            $mail->Timeout = $smtpTimeout;
            $mail->SMTPKeepAlive = false;
            $mail->CharSet = 'UTF-8';
            $mail->SMTPDebug = $smtpDebug;
            // Always capture SMTP conversation to the log file (not to browser)
            $mail->Debugoutput = function($str, $level) use ($email, $logLabel) {
                logEmailError("[SMTP-DEBUG L{$level}] {$logLabel} → {$email}: " . trim($str));
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

            if ($dkimDomain !== '' && $dkimSelector !== '' && $dkimPrivateKeyPath !== '' && file_exists($dkimPrivateKeyPath)) {
                $mail->DKIM_domain = $dkimDomain;
                $mail->DKIM_selector = $dkimSelector;
                $mail->DKIM_private = $dkimPrivateKeyPath;
                $mail->DKIM_identity = $dkimIdentity;
            }

            $mail->send();

            $messageId = $mail->getLastMessageID();
            logEmailError("{$logLabel} sent via {$config['host']}:{$config['port']} to {$email} | Message-ID: {$messageId}");
            return true;
        } catch (\Throwable $e) {
            $GLOBALS['LAST_EMAIL_ERROR'] = $e->getMessage();
            logEmailError("SMTP attempt " . ($index + 1) . " failed on {$config['host']}:{$config['port']} for {$email} ({$logLabel}) - " . $e->getMessage());
            continue;
        }
    }

    logEmailError("All SMTP configurations failed for {$logLabel} to {$email}");
    return false;
}

function sendVerificationEmail($email, $name, $token) {
    $base = appBaseUrl();
    $verifyUrl = $base . '/verify_email?token=' . urlencode($token);
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

    $sent = sendEmailViaConfiguredSmtp($email, $name, $subject, $htmlBody, $altBody, 'Verification email');
    if (!$sent) {
        $GLOBALS['LAST_VERIFICATION_EMAIL_ERROR'] = getLastEmailError();
    }
    return $sent;
}

function sendPasswordResetEmail($email, $name, $token) {
    $base = appBaseUrl();
    $resetUrl = $base . '/reset_password?token=' . urlencode($token);
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

    return sendEmailViaConfiguredSmtp($email, $name, $subject, $htmlBody, $altBody, 'Password reset email');
}
?>