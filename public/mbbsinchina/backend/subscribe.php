<?php
declare(strict_types=1);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Only POST requests are allowed.'
    ]);
    exit;
}

function respondNewsletterError(string $message, int $statusCode = 400): void
{
    http_response_code($statusCode);
    echo json_encode([
        'success' => false,
        'message' => $message
    ]);
    exit;
}

$fullName = trim(filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
$country = trim(filter_input(INPUT_POST, 'country', FILTER_SANITIZE_SPECIAL_CHARS) ?? '');
$consent = filter_input(INPUT_POST, 'consent', FILTER_SANITIZE_SPECIAL_CHARS);
$sourcePage = trim(filter_input(INPUT_POST, 'source_page', FILTER_SANITIZE_SPECIAL_CHARS) ?? 'footer');
$pageUrl = trim(filter_input(INPUT_POST, 'page_url', FILTER_SANITIZE_URL) ?? '');

if ($fullName === '' || !$email || $country === '' || $consent === null) {
    respondNewsletterError('Please complete all required fields and accept the consent.', 422);
}

$dbHost = getenv('MBBS_DB_HOST') ?: 'localhost';
$dbName = getenv('MBBS_DB_NAME') ?: 'mbbs_applications';
$dbUser = getenv('MBBS_DB_USER') ?: 'mbbs_user';
$dbPass = getenv('MBBS_DB_PASS') ?: 'secure_password';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
    $mysqli->set_charset('utf8mb4');
} catch (mysqli_sql_exception $exception) {
    error_log('Newsletter DB connection failed: ' . $exception->getMessage());
    respondNewsletterError('Unable to save your subscription right now. Please try again shortly.', 500);
}

$insertQuery = <<<SQL
    INSERT INTO newsletter_subscribers (full_name, email, country, consent, source_page, page_url)
    VALUES (?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        full_name = VALUES(full_name),
        country = VALUES(country),
        consent = VALUES(consent),
        source_page = VALUES(source_page),
        page_url = VALUES(page_url),
        updated_at = CURRENT_TIMESTAMP
SQL;

try {
    $consentValue = $consent ? 1 : 0;
    $statement = $mysqli->prepare($insertQuery);
    $statement->bind_param(
        'ssssss',
        $fullName,
        $email,
        $country,
        $consentValue,
        $sourcePage,
        $pageUrl
    );
    $statement->execute();
} catch (mysqli_sql_exception $exception) {
    error_log('Newsletter insert failed: ' . $exception->getMessage());
    respondNewsletterError('Subscription could not be saved. Please reach us on WhatsApp meanwhile.', 500);
}

echo json_encode([
    'success' => true,
    'message' => 'Thanks for subscribing! Watch your inbox for weekly CAS briefings.'
]);
exit;

