<?php
require_once __DIR__ . '/includes/auth.php';

// ── Load .env ────────────────────────────────────────────────────────────────
$envFile = __DIR__ . '/../.env';
if (file_exists($envFile)) {
    foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) continue;
        [$k, $v] = explode('=', $line, 2);
        $_ENV[trim($k)] = trim($v);
    }
}

// ── Load PHPMailer ────────────────────────────────────────────────────────────
$autoload = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoload)) require_once $autoload;

if (!class_exists('PHPMailer\\PHPMailer\\PHPMailer')) {
    foreach ([
        __DIR__ . '/../libs/PHPMailer/Exception.php',
        __DIR__ . '/../libs/PHPMailer/SMTP.php',
        __DIR__ . '/../libs/PHPMailer/PHPMailer.php',
    ] as $f) { if (file_exists($f)) require_once $f; }
}

$phpmailerLoaded = class_exists('PHPMailer\\PHPMailer\\PHPMailer');

// ── Environment diagnostics (always shown) ───────────────────────────────────
$smtpHost = $_ENV['SMTP_HOST'] ?? 'smtp.ipage.com';
$smtpPort = (int)($_ENV['SMTP_PORT'] ?? 587);
$smtpUser = $_ENV['SMTP_USER'] ?? 'no-reply@xjmu.online';
$smtpPass = $_ENV['SMTP_PASS'] ?? '';
$smtpFrom = $_ENV['SMTP_FROM'] ?? $smtpUser;

$logDir       = realpath(__DIR__ . '/../logs') ?: (__DIR__ . '/../logs');
$logDirExists = is_dir($logDir);
$logDirWrite  = $logDirExists && is_writable($logDir);
$phpErrorLog  = ini_get('error_log');

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

// ── Send test email on POST ───────────────────────────────────────────────────
$smtpLog = '';
$result  = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_require(false);
    $to   = trim($_POST['to'] ?? '');
    $type = $_POST['type'] ?? 'verification';

    if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
        $result = ['ok' => false, 'msg' => 'Invalid email address.'];
    } elseif (!$phpmailerLoaded) {
        $result = ['ok' => false, 'msg' => 'PHPMailer not loaded — cannot send.'];
    } else {
        $token   = bin2hex(random_bytes(32));
        $subject = $type === 'reset'
            ? 'Reset Your Password - XJMU (TEST)'
            : 'Verify Your Email - XJMU (TEST)';
        $body = $type === 'reset'
            ? "<p>Password reset test. Token: <code>{$token}</code></p>"
            : "<p>Email verification test. Token: <code>{$token}</code></p>";

        ob_start();
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host        = $smtpHost;
            $mail->SMTPAuth    = true;
            $mail->Username    = $smtpUser;
            $mail->Password    = $smtpPass;
            $mail->SMTPSecure  = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port        = $smtpPort;
            $mail->SMTPAutoTLS = true;
            $mail->SMTPOptions = ['ssl' => [
                'verify_peer'       => false,
                'verify_peer_name'  => false,
                'allow_self_signed' => true,
            ]];
            $mail->Timeout     = 30;
            $mail->CharSet     = 'UTF-8';
            $mail->SMTPDebug   = 4;          // Full conversation
            $mail->Debugoutput = 'echo';     // Print to output buffer

            $mail->setFrom($smtpFrom, 'XJMU Admin');
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
        $smtpLog = ob_get_clean();

        // Also log to PHP error_log so it shows up in cPanel Error Logs
        error_log('[XJMU TEST-EMAIL] To=' . $to . ' | Result=' . ($result['ok'] ? 'sent' : 'failed') . ' | ' . $result['msg']);
        if ($smtpLog !== '') {
            error_log('[XJMU SMTP-LOG] ' . substr(str_replace("\n", ' | ', $smtpLog), 0, 2000));
        }
    }
}
$admin_page_title = 'Test Email';
$admin_active     = 'test_email';
$admin_subtitle   = 'Sends a real email and shows the full SMTP conversation in the browser.';
require __DIR__ . '/includes/layout_top.php';
?>
<div style="max-width:860px">

  <!-- Environment info -->
  <div class="card mb-4">
    <div class="card-header fw-semibold bg-white">⚙️ Environment</div>
    <div class="card-body p-0">
      <table class="table table-sm table-striped mb-0">
        <?php foreach ($diag as $k => $v): ?>
          <tr>
            <td class="ps-3 text-muted" style="width:220px"><?= htmlspecialchars($k) ?></td>
            <td class="fw-semibold"><?= htmlspecialchars($v) ?></td>
          </tr>
        <?php endforeach; ?>
      </table>
    </div>
  </div>

  <!-- Send form -->
  <div class="card mb-4">
    <div class="card-header fw-semibold bg-white">📤 Send test email</div>
    <div class="card-body">
      <form method="POST">
        <?= csrf_field() ?>
        <div class="mb-3">
          <label class="form-label fw-semibold">Recipient email</label>
          <input type="email" name="to" class="form-control"
                 value="<?= htmlspecialchars($_POST['to'] ?? '') ?>"
                 placeholder="e.g. webai.auto@gmail.com" required>
        </div>
        <div class="mb-3">
          <label class="form-label fw-semibold">Email type</label>
          <select name="type" class="form-select">
            <option value="verification" <?= ($_POST['type'] ?? '') === 'verification' ? 'selected' : '' ?>>Verification email</option>
            <option value="reset"        <?= ($_POST['type'] ?? '') === 'reset'        ? 'selected' : '' ?>>Password reset email</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary">Send &amp; show SMTP log</button>
      </form>
    </div>
  </div>

  <?php if ($result !== null): ?>
    <div class="alert alert-<?= $result['ok'] ? 'success' : 'danger' ?> mb-3">
      <?= htmlspecialchars($result['msg']) ?>
      <?php if ($result['ok']): ?>
        <br><small>If you don't see it in your inbox, <strong>check the Spam / Junk folder</strong>.
        Look for the SMTP log below — if the last line says <code>250 OK</code> it was accepted by the mail server.</small>
      <?php endif; ?>
    </div>
  <?php endif; ?>

  <?php if ($smtpLog !== ''): ?>
    <div class="d-flex justify-content-between align-items-center mb-1">
      <h6 class="mb-0">Full SMTP conversation</h6>
      <small class="text-muted">Scroll right if lines are long</small>
    </div>
    <pre class="bg-dark text-light p-3 rounded" style="font-size:11px;max-height:600px;overflow:auto;white-space:pre"><?= htmlspecialchars($smtpLog) ?></pre>
  <?php endif; ?>

  <div class="alert alert-info mt-3 small">
    <strong>How to read the SMTP log:</strong><br>
    Lines starting with <code>CLIENT -&gt;</code> are commands your server sent.<br>
    Lines starting with <code>SERVER -&gt;</code> are responses from the SMTP server.<br>
    A response of <code>250</code> means success. <code>535</code> = wrong credentials. <code>5xx</code> = permanent failure. <code>4xx</code> = temporary failure.<br>
    If the last server line is <code>250 OK</code> but email never arrives → deliverability issue (iPage IP reputation / Gmail spam filter).
  </div>

</div>
<?php require __DIR__ . '/includes/layout_bottom.php'; ?>
