<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

// Only allow admin
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

try {
    $stmt = $pdo->query("SELECT id, title, grade, start_date, end_date, instructions FROM exams ORDER BY created_at DESC");
    $exams = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'exams' => $exams
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
}
