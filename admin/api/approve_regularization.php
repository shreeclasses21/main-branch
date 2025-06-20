<?php
session_start();
header('Content-Type: application/json');

// 🔐 Admin access control
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'error' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../../config/db.php';

// 📥 Parse incoming JSON
$data = json_decode(file_get_contents("php://input"), true);
$requestId = $data['request_id'] ?? null;
$studentId = $data['student_id'] ?? null;
$requestedDate = $data['requested_date'] ?? null;

if (!$requestId || !$studentId || !$requestedDate) {
    echo json_encode(['status' => 'error', 'error' => 'Missing required data.']);
    exit;
}

try {
    $pdo->beginTransaction();

    // ✅ Step 1: Approve the request
    $updateRequest = $pdo->prepare("UPDATE regularization_requests SET status = 'Approved' WHERE id = ?");
    $updateRequest->execute([$requestId]);

    // ✅ Step 2: Update attendance_log to Present
    $updateAttendance = $pdo->prepare("
        UPDATE attendance_log
        SET type = 'Present'
        WHERE student_id = ? AND attendance_date = ?
    ");
    $updateAttendance->execute([$studentId, $requestedDate]);

    $pdo->commit();

    echo json_encode(['status' => 'success', 'message' => 'Request approved and attendance updated.']);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'error' => $e->getMessage()]);
}
