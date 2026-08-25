<?php
/**
 * IMPROVED VERSION - Added Rate Limiting
 * 
 * FIXES:
 * 1. Rate limiting (max 5 submissions per hour per IP)
 * 2. Error logging
 * 3. Better error handling
 */

// send_contact_email.php - Handle contact form submission and send to guide@xjmu.online

header('Content-Type: application/json');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Rate limiting configuration
$rateLimitFile = 'logs/contact_rate_limit.json';
$rateLimitDir = dirname($rateLimitFile);
if (!is_dir($rateLimitDir)) mkdir($rateLimitDir, 0755, true);

$ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
$currentTime = time();
$rateLimitWindow = 3600; // 1 hour in seconds
$maxRequests = 5; // Max 5 requests per hour

// Load rate limit data
$rateLimitData = [];
if (file_exists($rateLimitFile)) {
    $rateLimitData = json_decode(file_get_contents($rateLimitFile), true) ?: [];
}

// Clean old entries and check rate limit
if (isset($rateLimitData[$ip])) {
    // Remove entries older than rate limit window
    $rateLimitData[$ip] = array_filter($rateLimitData[$ip], function($timestamp) use ($currentTime, $rateLimitWindow) {
        return ($currentTime - $timestamp) < $rateLimitWindow;
    });
    
    // Check if limit exceeded
    if (count($rateLimitData[$ip]) >= $maxRequests) {
        http_response_code(429);
        echo json_encode([
            'status' => 'error',
            'message' => 'Too many requests. Please try again later.'
        ]);
        exit;
    }
} else {
    $rateLimitData[$ip] = [];
}

// Add current request timestamp
$rateLimitData[$ip][] = $currentTime;

// Save rate limit data
file_put_contents($rateLimitFile, json_encode($rateLimitData));

// Error logging function
function logContactError($message, $ip) {
    $logFile = 'logs/contact_errors.log';
    $logDir = dirname($logFile);
    if (!is_dir($logDir)) mkdir($logDir, 0755, true);
    $timestamp = date('Y-m-d H:i:s');
    error_log("[$timestamp] IP: $ip - $message\n", 3, $logFile);
}

// Get form data
$name = isset($_POST['name']) ? trim($_POST['name']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
$nationality = isset($_POST['nationality']) ? trim($_POST['nationality']) : '';
$subject = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$program = isset($_POST['program']) ? trim($_POST['program']) : '';
$message = isset($_POST['message']) ? trim($_POST['message']) : '';
$newsletter = isset($_POST['newsletter']) ? 'Yes' : 'No';

// Validate required fields
if (empty($name) || empty($email) || empty($nationality) || empty($subject) || empty($message)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Please fill in all required fields']);
    exit;
}

// Validate message length (400 characters max)
if (strlen($message) > 400) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Message must be 400 characters or less']);
    exit;
}

// Validate email format
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid email address']);
    exit;
}

// Prepare email
require_once __DIR__ . '/includes/security.php';
$to = 'guide@xjmu.online';
$emailSubject = mail_safe('XJMU Contact Form: ' . $subject . ' - ' . $name);

// Build email body
$emailBody = "XINJIANG MEDICAL UNIVERSITY - CONTACT FORM SUBMISSION\n\n";
$emailBody .= "APPLICANT INFORMATION:\n";
$emailBody .= "• Name: {$name}\n";
$emailBody .= "• Email: {$email}\n";
$emailBody .= "• Phone: " . ($phone ? $phone : 'Not provided') . "\n";
$emailBody .= "• Nationality: {$nationality}\n\n";

$emailBody .= "ACADEMIC INTEREST:\n";
$emailBody .= "• Inquiry Type: {$subject}\n";
$emailBody .= "• Program Interest: " . ($program ? $program : 'Not specified') . "\n\n";

$emailBody .= "MESSAGE:\n{$message}\n\n";

$emailBody .= "NEWSLETTER SUBSCRIPTION: {$newsletter}\n\n";

$emailBody .= "SUBMISSION DETAILS:\n";
$emailBody .= "• Submitted: " . date('Y-m-d H:i:s') . "\n";
$emailBody .= "• Source: XJMU Website Contact Form\n";
$emailBody .= "• IP Address: " . ($_SERVER['REMOTE_ADDR'] ?? 'Unknown') . "\n";

// Email headers
// From must be a domain-owned address so SPF/DMARC pass; user email goes in Reply-To
$headers = "From: no-reply@xjmu.online\r\n";
$headers .= "Reply-To: {$email}\r\n";
$headers .= "X-Mailer: PHP/" . phpversion() . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

// Send email
$mailSent = mail($to, $emailSubject, $emailBody, $headers);

if ($mailSent) {
    logContactError("Contact form submitted successfully from: $email", $ip);
    echo json_encode([
        'status' => 'success',
        'message' => 'Your message has been sent successfully. We will get back to you soon.'
    ]);
} else {
    logContactError("Failed to send email for contact form from: $email", $ip);
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Failed to send message. Please try again or contact us directly.'
    ]);
}
?>
