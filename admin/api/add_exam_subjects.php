<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (empty($data['exam_id']) || empty($data['subject_name']) || empty($data['subject_date'])) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO exam_subjects (exam_id, subject_name, subject_date) VALUES (?, ?, ?)");
    $stmt->execute([
        $data['exam_id'],
        $data['subject_name'],
        $data['subject_date']
    ]);

    echo json_encode(['status' => 'success', 'message' => '✅ Subject added to exam']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
}
