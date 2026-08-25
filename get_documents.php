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

    $stmt = $pdo->prepare("SELECT * FROM documents WHERE user_id = ?");
    $stmt->execute([$user_id]);

    $docs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($docs) {
        echo json_encode(['status' => 'success', 'data' => $docs]);
    } else {
        echo json_encode(['status' => 'empty', 'data' => []]);
    }
} catch (Exception $e) {
    error_log("get_documents.php error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to load documents']);
}
