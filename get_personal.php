<?php
// get_personal.php
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

    $stmt = $pdo->prepare("SELECT * FROM personal_info WHERE user_id = ?");
    $stmt->execute([$user_id]);

    if ($stmt->rowCount() > 0) {
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode(['status' => 'success', 'data' => $data]);
    } else {
        echo json_encode(['status' => 'empty', 'data' => []]);
    }
} catch (Exception $e) {
    error_log("get_personal.php error: " . $e->getMessage());
    http_response_code(500);
    echo json_encode(['status' => 'error', 'message' => 'Failed to load personal information']);
}
