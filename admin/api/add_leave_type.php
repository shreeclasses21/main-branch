<?php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$name = $data['name'] ?? null;
$description = $data['description'] ?? '';
$allowed = $data['allowed_per_month'] ?? 0;

if (!$name || !is_numeric($allowed)) {
    echo json_encode(['status' => 'error', 'error' => 'Missing or invalid fields']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO leave_types (name, description, allowed_per_month) VALUES (?, ?, ?)");
    $stmt->execute([$name, $description, $allowed]);

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'error' => 'Server error']);
}
