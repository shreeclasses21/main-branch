<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['contact_id'])) {
    echo json_encode(['status' => 'error', 'error' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../config/db.php';

$studentId = $_SESSION['contact_id'];

try {
    $stmt = $pdo->prepare("SELECT attendance_date, type FROM attendance_log WHERE student_id = ? AND MONTH(attendance_date) = MONTH(CURDATE()) AND YEAR(attendance_date) = YEAR(CURDATE())");
    $stmt->execute([$studentId]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $rows]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'error' => $e->getMessage()]);
}
