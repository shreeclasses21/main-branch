<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'error' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../../config/db.php';

try {
    $pdo->beginTransaction();

    // Fetch all pending requests
    $stmt = $pdo->query("SELECT id, student_id, requested_date FROM regularization_requests WHERE status = 'Pending'");
    $requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $updateReq = $pdo->prepare("UPDATE regularization_requests SET status = 'Approved' WHERE id = ?");
    $updateAtt = $pdo->prepare("UPDATE attendance_log SET type = 'Present' WHERE student_id = ? AND attendance_date = ?");

    foreach ($requests as $req) {
        $updateReq->execute([$req['id']]);
        $updateAtt->execute([$req['student_id'], $req['requested_date']]);
    }

    $pdo->commit();

    echo json_encode(['status' => 'success', 'approved_count' => count($requests)]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['status' => 'error', 'error' => $e->getMessage()]);
}
