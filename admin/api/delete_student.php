<?php
require_once __DIR__ . '/../../config/db.php';
$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'] ?? '';
if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing student ID.']);
    exit;
}

$stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
$stmt->execute([$id]);

echo json_encode(['status' => 'success']);
