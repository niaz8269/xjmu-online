<?php

/*
| SMTP settings for App\Services\SiteMailer. Same .env keys and defaults the original
| send_verification_email.php read from $_ENV. Leave SMTP_HOST empty to use the log/fake driver (nothing is sent).
*/
return [
    'host'            => (string) env('SMTP_HOST', ''),
    'port'            => (int) env('SMTP_PORT', 587),
    'secure'          => (string) env('SMTP_SECURE', 'starttls'),
    'user'            => (string) env('SMTP_USER', ''),
    'pass'            => (string) env('SMTP_PASS', ''),
    'from'            => env('SMTP_FROM'),
    'from_name'       => (string) env('SMTP_FROM_NAME', 'Xinjiang Medical University'),
    'reply_to'        => env('SMTP_REPLY_TO'),
    'timeout'         => (int) env('SMTP_TIMEOUT', 30),
    'debug'           => (int) env('SMTP_DEBUG', 0),
    'fallback_hosts'  => (string) env('SMTP_FALLBACK_HOSTS', 'smtp.ipage.com'),
    'allow_insecure'  => (string) env('SMTP_ALLOW_INSECURE', '0'),
    'mail_domain'     => (string) env('MAIL_DOMAIN', 'xjmu.online'),
    'dkim_domain'     => (string) env('SMTP_DKIM_DOMAIN', ''),
    'dkim_selector'   => (string) env('SMTP_DKIM_SELECTOR', ''),
    'dkim_key_path'   => (string) env('SMTP_DKIM_PRIVATE_KEY_PATH', ''),
    'dkim_identity'   => env('SMTP_DKIM_IDENTITY'),
    'base_url'        => (string) (env('APP_URL') ?: 'https://xjmu.online'),
    'log_timezone'    => (string) env('APP_TIMEZONE', 'Asia/Karachi'),
];
