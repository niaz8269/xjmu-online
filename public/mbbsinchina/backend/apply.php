<?php
declare(strict_types=1);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Only POST requests are allowed.'
    ]);
    exit;
}

// Check content type
$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($contentType, 'application/json') !== false) {
    $input = json_decode(file_get_contents('php://input'), true) ?? [];
    $_POST = array_merge($_POST, $input);
} elseif (stripos($contentType, 'multipart/form-data') === false && stripos($contentType, 'application/x-www-form-urlencoded') === false) {
    http_response_code(415);
    echo json_encode([
        'success' => false,
        'message' => 'Unsupported content type.'
    ]);
    exit;
}

function respondWithError(string $message, int $statusCode = 400): void
{
    http_response_code($statusCode);
    echo json_encode([
        'success' => false,
        'message' => $message
    ]);
    exit;
}

function respondWithSuccess(string $message, array $additionalData = []): void
{
    echo json_encode(array_merge([
        'success' => true,
        'message' => $message
    ], $additionalData));
    exit;
}

function sanitizeInput($input): string
{
    return trim(htmlspecialchars($input ?? '', ENT_QUOTES, 'UTF-8'));
}

function validateEmail(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function validatePhone(string $phone): bool
{
    // Basic international phone validation
    $cleaned = preg_replace('/[^+\d]/', '', $phone);
    return strlen($cleaned) >= 10 && strlen($cleaned) <= 15;
}

function logApplication(array $data, string $status = 'success'): void
{
    $logEntry = [
        'timestamp' => date('Y-m-d H:i:s'),
        'status' => $status,
        'data' => $data,
        'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    ];
    
    file_put_contents(
        'application_logs.txt', 
        json_encode($logEntry) . PHP_EOL, 
        FILE_APPEND | LOCK_EX
    );
}

function sendEmailNotification(array $data): bool
{
    $to = "cas0086@qq.com";
    $subject = "📋 New MBBS Application - " . $data['first_name'] . " " . $data['last_name'];
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .header { background: linear-gradient(135deg, #1a73e8, #db4437); color: white; padding: 20px; border-radius: 8px; }
            .content { padding: 20px; border: 1px solid #ddd; border-radius: 8px; margin: 10px 0; }
            .field { margin-bottom: 10px; }
            .label { font-weight: bold; color: #1a73e8; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h2>🎓 New MBBS Application Received</h2>
        </div>
        <div class='content'>
            <div class='field'><span class='label'>Name:</span> {$data['first_name']} {$data['last_name']}</div>
            <div class='field'><span class='label'>Email:</span> {$data['email']}</div>
            <div class='field'><span class='label'>Phone:</span> {$data['phone']}</div>
            <div class='field'><span class='label'>Nationality:</span> {$data['nationality']}</div>
            <div class='field'><span class='label'>Preferred University:</span> {$data['preferred_university']}</div>
            <div class='field'><span class='label'>Intake:</span> {$data['intake']}</div>
            <div class='field'><span class='label'>English Level:</span> {$data['english_proficiency']}</div>
            <div class='field'><span class='label'>Academic Background:</span> {$data['academic_background']}</div>
            <div class='field'><span class='label'>Message:</span> {$data['message']}</div>
            <div class='field'><span class='label'>Page Source:</span> {$data['page_source']}</div>
            <div class='field'><span class='label'>Submitted:</span> {$data['timestamp']}</div>
            <div class='field'><span class='label'>IP Address:</span> {$_SERVER['REMOTE_ADDR'] ?? 'Unknown'}</div>
        </div>
    </body>
    </html>
    ";
    
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=utf-8',
        'From: MBBS Admissions <noreply@mbbsinchina.com>',
        'Reply-To: ' . $data['email'],
        'X-Mailer: PHP/' . phpversion()
    ];
    
    return mail($to, $subject, $message, implode("\r\n", $headers));
}

function sendWhatsAppNotification(array $data): void
{
    $message = "📋 New MBBS Application%0A%0A" .
               "👤 Name: {$data['first_name']} {$data['last_name']}%0A" .
               "📧 Email: {$data['email']}%0A" .
               "📞 Phone: {$data['phone']}%0A" .
               "🌍 Nationality: {$data['nationality']}%0A" .
               "🎓 University: {$data['preferred_university']}%0A" .
               "📅 Intake: {$data['intake']}%0A" .
               "⏰ Submitted: {$data['timestamp']}%0A" .
               "🔗 From: {$data['page_url']}";
    
    $whatsappNumbers = [
        '+8618604090801',
        '+923004009526'
    ];
    
    foreach ($whatsappNumbers as $number) {
        $url = "https://wa.me/{$number}?text=" . urlencode($message);
        // Note: This would typically be queued for background processing
        file_put_contents('whatsapp_queue.txt', $url . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}

// Main application logic
try {
    // Validate required fields
    $requiredFields = [
        'first_name' => 'First name',
        'last_name' => 'Last name',
        'email' => 'Email address',
        'phone' => 'Phone number',
        'nationality' => 'Nationality',
        'preferred_university' => 'Preferred university',
        'intake' => 'Intake period'
    ];

    $missingFields = [];
    foreach ($requiredFields as $field => $label) {
        if (empty(trim($_POST[$field] ?? ''))) {
            $missingFields[] = $label;
        }
    }

    if (!empty($missingFields)) {
        respondWithError('Please complete all required fields: ' . implode(', ', $missingFields), 422);
    }

    // Sanitize and validate inputs
    $firstName = sanitizeInput($_POST['first_name']);
    $lastName = sanitizeInput($_POST['last_name']);
    $email = sanitizeInput($_POST['email']);
    $phone = sanitizeInput($_POST['phone']);
    $nationality = sanitizeInput($_POST['nationality']);
    $preferredUniversity = sanitizeInput($_POST['preferred_university']);
    $intake = sanitizeInput($_POST['intake']);
    $englishLevel = sanitizeInput($_POST['english_proficiency'] ?? 'Not specified');
    $academicBackground = sanitizeInput($_POST['academic_background'] ?? '');
    $message = sanitizeInput($_POST['message'] ?? '');
    $pageSource = sanitizeInput($_POST['page_source'] ?? 'direct');
    $pageUrl = sanitizeInput($_POST['page_url'] ?? '');
    $timestamp = date('Y-m-d H:i:s');

    // Validate email
    if (!validateEmail($email)) {
        respondWithError('Please enter a valid email address.', 422);
    }

    // Validate phone
    if (!validatePhone($phone)) {
        respondWithError('Please enter a valid phone number.', 422);
    }

    // Prepare application data
    $applicationData = [
        'first_name' => $firstName,
        'last_name' => $lastName,
        'email' => $email,
        'phone' => $phone,
        'nationality' => $nationality,
        'preferred_university' => $preferredUniversity,
        'intake' => $intake,
        'english_proficiency' => $englishLevel,
        'academic_background' => $academicBackground,
        'message' => $message,
        'page_source' => $pageSource,
        'page_url' => $pageUrl,
        'timestamp' => $timestamp
    ];

    // Database configuration with fallbacks
    $dbConfig = [
        'host' => getenv('MBBS_DB_HOST') ?: 'localhost',
        'name' => getenv('MBBS_DB_NAME') ?: 'mbbs_applications',
        'user' => getenv('MBBS_DB_USER') ?: 'mbbs_user',
        'pass' => getenv('MBBS_DB_PASS') ?: 'secure_password',
        'port' => getenv('MBBS_DB_PORT') ?: 3306
    ];

    $dbSuccess = false;
    $mysqli = null;

    // Attempt database connection and insertion
    try {
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
        $mysqli = new mysqli($dbConfig['host'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['name'], $dbConfig['port']);
        $mysqli->set_charset('utf8mb4');

        // Check if table exists, create if not
        $tableCheck = $mysqli->query("SHOW TABLES LIKE 'applications'");
        if ($tableCheck->num_rows === 0) {
            $createTable = "
                CREATE TABLE applications (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    first_name VARCHAR(100) NOT NULL,
                    last_name VARCHAR(100) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    phone VARCHAR(50) NOT NULL,
                    nationality VARCHAR(100) NOT NULL,
                    preferred_university VARCHAR(255) NOT NULL,
                    intake VARCHAR(50) NOT NULL,
                    english_proficiency VARCHAR(50),
                    academic_background TEXT,
                    message TEXT,
                    page_source VARCHAR(100),
                    page_url VARCHAR(500),
                    ip_address VARCHAR(45),
                    user_agent TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    UNIQUE KEY unique_application (email, preferred_university, intake)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ";
            $mysqli->query($createTable);
        }

        $insertQuery = "
            INSERT INTO applications 
            (first_name, last_name, email, phone, nationality, preferred_university, intake, 
             english_proficiency, academic_background, message, page_source, page_url, ip_address, user_agent)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ";

        $statement = $mysqli->prepare($insertQuery);
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $statement->bind_param(
            'ssssssssssssss',
            $firstName,
            $lastName,
            $email,
            $phone,
            $nationality,
            $preferredUniversity,
            $intake,
            $englishLevel,
            $academicBackground,
            $message,
            $pageSource,
            $pageUrl,
            $ipAddress,
            $userAgent
        );
        
        $statement->execute();
        $dbSuccess = true;
        
    } catch (mysqli_sql_exception $dbException) {
        // Log database error but continue with fallback methods
        error_log('Database operation failed: ' . $dbException->getMessage());
        $dbSuccess = false;
    } finally {
        if ($mysqli) {
            $mysqli->close();
        }
    }

    // Always save to file as backup
    $backupData = $applicationData;
    $backupData['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? '';
    $backupData['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $backupData['database_saved'] = $dbSuccess;
    
    file_put_contents(
        'applications_backup.jsonl', 
        json_encode($backupData) . PHP_EOL, 
        FILE_APPEND | LOCK_EX
    );

    // Send notifications
    $emailSent = sendEmailNotification($applicationData);
    sendWhatsAppNotification($applicationData);

    // Log the application
    logApplication($applicationData, $dbSuccess ? 'success' : 'database_failed');

    // Prepare response
    $responseData = [
        'application_id' => $dbSuccess ? 'DB-' . time() : 'FILE-' . time(),
        'database_saved' => $dbSuccess,
        'email_sent' => $emailSent,
        'timestamp' => $timestamp
    ];

    if (!$dbSuccess) {
        $responseData['note'] = 'Application saved to backup system. Database will be updated shortly.';
    }

    respondWithSuccess(
        'Application received successfully! Our admissions team will contact you within 24 hours.',
        $responseData
    );

} catch (Exception $exception) {
    // Log unexpected errors
    error_log('Unexpected error in application handler: ' . $exception->getMessage());
    
    // Save to emergency backup
    $emergencyData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'error' => $exception->getMessage(),
        'post_data' => $_POST,
        'server_data' => [
            'ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
        ]
    ];
    
    file_put_contents('emergency_backup.jsonl', json_encode($emergencyData) . PHP_EOL, FILE_APPEND | LOCK_EX);
    
    respondWithError('A system error occurred. Your application has been saved and our team has been notified.', 500);
}
?>