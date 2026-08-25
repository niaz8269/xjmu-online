<?php
header('Content-Type: application/json; charset=UTF-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

$requiredFields = [
    'first_name', 'last_name', 'email', 'mobile', 'company_name', 'current_business',
    'address', 'street_address', 'city', 'state', 'pincode', 'region', 'whatsapp'
];

$data = [];
foreach ($requiredFields as $field) {
    $data[$field] = trim((string)($_POST[$field] ?? ''));
    if ($data[$field] === '') {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Please fill all required fields.']);
        exit;
    }
}

if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Please enter a valid email address.']);
    exit;
}

require_once __DIR__ . '/includes/security.php';
$to = 'partners@xjmu.online';
$subject = mail_safe('New Partner Registration - ' . $data['first_name'] . ' ' . $data['last_name']);

$body = "Xinjiang Medical University - Partner Registration\n";
$body .= "================================================\n\n";
$body .= "First Name: {$data['first_name']}\n";
$body .= "Last Name: {$data['last_name']}\n";
$body .= "Email: {$data['email']}\n";
$body .= "Mobile Number: {$data['mobile']}\n";
$body .= "Company Name: {$data['company_name']}\n";
$body .= "Current Business: {$data['current_business']}\n";
$body .= "Address: {$data['address']}\n";
$body .= "Street Address: {$data['street_address']}\n";
$body .= "City: {$data['city']}\n";
$body .= "State: {$data['state']}\n";
$body .= "Pin Code/Zip Code: {$data['pincode']}\n";
$body .= "State/Province/Region: {$data['region']}\n";
$body .= "WhatsApp Number: {$data['whatsapp']}\n";
$body .= "\n";
$body .= "Submitted At: " . date('Y-m-d H:i:s') . "\n";
$body .= "IP Address: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n";

$headers = "From: no-reply@xjmu.online\r\n";
$headers .= "Reply-To: {$data['email']}\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

$logDir = __DIR__ . '/logs';
if (!is_dir($logDir)) {
    @mkdir($logDir, 0755, true);
}
$logFile = $logDir . '/partner_registration.log';

$sent = @mail($to, $subject, $body, $headers);

if ($sent) {
    @file_put_contents($logFile, date('Y-m-d H:i:s') . " OK - {$data['first_name']} {$data['last_name']} <{$data['email']}>\n", FILE_APPEND | LOCK_EX);
    echo json_encode(['status' => 'success', 'message' => 'Warm welcome! Your partner registration has been submitted successfully. Our partnership team will contact you soon.']);
} else {
    @file_put_contents($logFile, date('Y-m-d H:i:s') . " FAILED - {$data['first_name']} {$data['last_name']} <{$data['email']}>\n", FILE_APPEND | LOCK_EX);
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Unable to submit registration right now. Please try again.']);
}
