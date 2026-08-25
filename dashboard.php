<?php
require 'session_config.php';
require 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: login');
    exit;
}

// Use the clean, extensionless URL to match the rest of the app's routing (audit #32).
header('Location: admin/dashboard');
exit;
