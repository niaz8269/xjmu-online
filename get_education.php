<?php
use PDO;
use Exception;

require 'session_config.php';
require 'db.php';

header('Content-Type: application/json');

try {
    if (!isset($_SESSION['user_id'])) {
        http_response_code(401);
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }
    
    $user_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("SELECT * FROM education_records WHERE user_id = ? ORDER BY entry_type ASC, id ASC");
    $stmt->execute([$user_id]);

    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($records) {
        echo json_encode(['status' => 'success', 'data' => $records]);
    } else {
        echo json_encode(['status' => 'empty', 'data' => []]);
    }
} catch (Exception $e) {
    error_log("get_education.php error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to load education records']);
}
