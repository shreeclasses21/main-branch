<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

require_once __DIR__ . '/../../config/db.php';

try {
    $sql = "SELECT la.*, 
                   s.first_name, 
                   s.last_name, 
                   lt.name AS leave_type_name 
            FROM leave_assignments la
            JOIN students s ON la.student_id = s.id
            JOIN leave_types lt ON la.leave_type_id = lt.id
            ORDER BY la.created_at DESC";

    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode($rows);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'error' => 'Server error']);
}
