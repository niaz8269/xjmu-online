<?php
use PDO;

require 'session_config.php';
require 'db.php';
require_once __DIR__ . '/includes/security.php';
json_api_guard();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['is_submitted' => false]);
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("SELECT is_submitted FROM applications WHERE user_id = ?");
$stmt->execute([$user_id]);

$result = $stmt->fetch(PDO::FETCH_ASSOC);

// Ensure we return a proper boolean - treat NULL, 0, or no record as false
$isSubmitted = false;
if ($result && isset($result['is_submitted'])) {
    $isSubmitted = (int)$result['is_submitted'] === 1;
}

echo json_encode(['is_submitted' => $isSubmitted]);

