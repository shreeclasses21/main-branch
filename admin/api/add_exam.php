<?php
session_start();
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (
    empty($data['title']) || empty($data['grade']) ||
    empty($data['start_date']) || empty($data['end_date']) ||
    empty($data['instructions'])
) {
    echo json_encode(['status' => 'error', 'message' => 'All required fields must be filled']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO exams (title, grade, start_date, end_date, description, rules, instructions) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $data['title'],
        $data['grade'],
        $data['start_date'],
        $data['end_date'],
        $data['description'] ?? '',
        $data['rules'] ?? '',
        $data['instructions']
    ]);

    echo json_encode(['status' => 'success', 'message' => '✅ Exam created successfully']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
}
