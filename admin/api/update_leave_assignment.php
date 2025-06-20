<?php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);

$id = $data['id'] ?? null;
$student = $data['student_id'] ?? null;
$type = $data['leave_type_id'] ?? null;
$year = $data['year'] ?? null;
$month = $data['month'] ?? null;
$allowed = $data['allowed'] ?? 0;
$used = $data['used'] ?? 0;
$remaining = $data['remaining'] ?? 0;

if (!$id || !$student || !$type || !$year || !$month) {
    echo json_encode(['status' => 'error', 'error' => 'Missing fields']);
    exit;
}

$stmt = $pdo->prepare("UPDATE leave_assignments 
                       SET student_id=?, leave_type_id=?, year=?, month=?, allowed=?, used=?, remaining=? 
                       WHERE id=?");

$success = $stmt->execute([$student, $type, $year, $month, $allowed, $used, $remaining, $id]);

echo json_encode(['status' => $success ? 'success' : 'error']);
