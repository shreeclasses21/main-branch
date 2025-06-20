<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['contact_id'])) {
    echo json_encode([]);
    exit;
}

require_once __DIR__ . '/../config/db.php';
$student_id = $_SESSION['contact_id'];

// 🔄 Sort by created_at DESC
$stmt = $pdo->prepare("
    SELECT requested_date, reason, status, created_at
    FROM regularization_requests
    WHERE student_id = ?
    ORDER BY created_at DESC
");
$stmt->execute([$student_id]);

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
