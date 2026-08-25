<?php
require_once '../session_config.php';
require_once '../db.php';
require_once __DIR__ . '/../includes/security.php';

// Must be logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login');
    exit;
}

// Must be an admin (verified against the DB)
$user_stmt = $pdo->prepare("SELECT role FROM users WHERE id = ?");
$user_stmt->execute([$_SESSION['user_id']]);
$user = $user_stmt->fetch(PDO::FETCH_ASSOC);
if (!$user || $user['role'] !== 'admin') {
    http_response_code(403);
    die("Access denied. Admin privileges required.");
}

// Deletion must be a POST with a valid CSRF token (audit #20).
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !csrf_check()) {
    http_response_code(403);
    $_SESSION['delete_error'] = "Invalid or expired request. Please use the Delete button.";
    header('Location: manage-blogs');
    exit;
}

$blog_id = intval($_POST['id'] ?? 0);
if ($blog_id <= 0) {
    header('Location: manage-blogs');
    exit;
}

try {
    // Look up the image first (for cleanup), then delete the row.
    $stmt = $pdo->prepare("SELECT featured_image FROM blogs WHERE id = ?");
    $stmt->execute([$blog_id]);
    $blog = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($blog) {
        $pdo->prepare("DELETE FROM blogs WHERE id = ?")->execute([$blog_id]);

        if ($blog['featured_image'] && file_exists('../' . $blog['featured_image'])) {
            @unlink('../' . $blog['featured_image']);
        }
        $_SESSION['delete_success'] = "Blog post deleted successfully!";
    }
} catch (PDOException $e) {
    error_log('delete-blog: ' . $e->getMessage());
    $_SESSION['delete_error'] = "Error deleting blog post. Please try again.";
}

header('Location: manage-blogs');
exit;
