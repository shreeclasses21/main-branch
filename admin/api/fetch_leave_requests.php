<?php
header('Content-Type: application/json');
error_reporting(0); // hide warnings in response
ini_set('display_errors', 0);

require_once __DIR__ . '/../../config/db.php';

try {
    $stmt = $pdo->query("SELECT * FROM leave_requests ORDER BY created_at DESC");
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'error' => 'Server error']);
}
