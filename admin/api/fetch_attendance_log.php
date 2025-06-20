<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'error' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../../config/db.php';

try {
    $stmt = $pdo->query("
        SELECT 
            al.id,
            al.student_id,
            al.attendance_date,
            al.type,
            al.created_at,
            CONCAT(s.first_name, ' ', s.last_name) AS full_name
        FROM attendance_log al
        LEFT JOIN students s ON al.student_id = s.id
        ORDER BY al.attendance_date DESC, s.first_name
    ");

    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($logs);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['status' => 'error', 'error' => $e->getMessage()]);
}
