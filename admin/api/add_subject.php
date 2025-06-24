<?php
require_once __DIR__ . '/../../config/db.php';

$data = json_decode(file_get_contents('php://input'), true);
$grade = $data['grade'] ?? '';
$subject = $data['subject_name'] ?? '';

if ($grade && $subject) {
    $stmt = $pdo->prepare("INSERT INTO subjects (grade, subject_name) VALUES (?, ?)");
    $stmt->execute([$grade, $subject]);
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'error' => 'Missing fields']);
}
