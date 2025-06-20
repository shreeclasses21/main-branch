<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['contact_id'])) {
    echo json_encode(['status' => 'error', 'error' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../config/db.php';

$data = json_decode(file_get_contents("php://input"), true);

$student_id = $_SESSION['contact_id'];
$requested_dates = $data['dates'] ?? [];
$reason = $data['reason'] ?? '';

if (empty($requested_dates) || !$reason) {
    echo json_encode(['status' => 'error', 'error' => 'Missing dates or reason.']);
    exit;
}

try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("INSERT INTO regularization_requests (student_id, requested_date, reason) VALUES (?, ?, ?)");

    foreach ($requested_dates as $date) {
        $stmt->execute([$student_id, $date, $reason]);
    }

    $pdo->commit();
    echo json_encode(['status' => 'success', 'message' => 'Regularization requests submitted.']);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'error' => $e->getMessage()]);
}

