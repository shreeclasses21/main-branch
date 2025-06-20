<?php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT * FROM leave_types ORDER BY id DESC");
    $leaveTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($leaveTypes);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'error' => 'Server error']);
}
