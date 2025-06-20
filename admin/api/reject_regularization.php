<?php
session_start();
header('Content-Type: application/json');
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'error' => 'Unauthorized']);
    exit;
}
require_once __DIR__ . '/../../config/db.php';

$data = json_decode(file_get_contents("php://input"), true);
$request_id = $data['request_id'] ?? null;

if (!$request_id) {
    echo json_encode(['status' => 'error', 'error' => 'Missing ID']);
    exit;
}

$stmt = $pdo->prepare("UPDATE regularization_requests SET status = 'Rejected' WHERE id = ?");
$stmt->execute([$request_id]);

echo json_encode(['status' => 'success']);
