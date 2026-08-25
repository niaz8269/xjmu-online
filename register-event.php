<?php
/**
 * Event/Webinar registration – sends details to webinar@xjmu.online
 */
header('Content-Type: application/json; charset=UTF-8');

require_once __DIR__ . '/session_config.php';
require_once __DIR__ . '/includes/security.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

if (!csrf_check()) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Your session expired. Please refresh the page and try again.']);
    exit;
}

$name = trim($_POST['name'] ?? '');
$nationality = trim($_POST['nationality'] ?? '');
$email = trim($_POST['email'] ?? '');
$whatsapp = trim($_POST['whatsapp'] ?? '');
$event_title = trim($_POST['event_title'] ?? '');
$event_date = trim($_POST['event_date'] ?? '');
$event_time = trim($_POST['event_time'] ?? '');

if (empty($name) || empty($email)) {
    echo json_encode(['success' => false, 'message' => 'Name and Email are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Please enter a valid email address.']);
    exit;
}

$to = 'webinar@xjmu.online';
$subject = mail_safe('Event Registration: ' . ($event_title ?: 'Xinjiang Medical University'));

$body = "Event/Webinar Registration\n";
$body .= "===========================\n\n";
$body .= "Name: $name\n";
$body .= "Nationality: $nationality\n";
$body .= "Email: $email\n";
$body .= "WhatsApp: $whatsapp\n\n";
$body .= "Event/Webinar:\n";
$body .= "Title: " . ($event_title ?: 'Xinjiang Medical University – MBBS (English Taught) March 2026 intake') . "\n";
if ($event_date) $body .= "Date: $event_date\n";
if ($event_time) $body .= "Time: $event_time (Beijing Time)\n";
$body .= "\nRegistration submitted: " . date('Y-m-d H:i:s');
$body .= "\nIP: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown');

$headers = "From: no-reply@xjmu.online\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$logDir = __DIR__ . '/logs';
if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
}
$logFile = $logDir . '/event_registration.log';

$sent = @mail($to, $subject, $body, $headers);

if ($sent) {
    @file_put_contents($logFile, date('Y-m-d H:i:s') . " OK – sent to $to – $name <$email>\n", FILE_APPEND | LOCK_EX);
    echo json_encode(['success' => true, 'message' => 'Thank you for registering! We will contact you shortly.']);
} else {
    $err = date('Y-m-d H:i:s') . " FAILED – to=$to – $name <$email> – " . (error_get_last()['message'] ?? 'mail() returned false') . "\n";
    @file_put_contents($logFile, $err, FILE_APPEND | LOCK_EX);
    echo json_encode(['success' => false, 'message' => 'Unable to send registration. Please try again or contact us directly at webinar@xjmu.online.']);
}
