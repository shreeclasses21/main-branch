<?php
session_start();
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}

$body     = json_decode(file_get_contents('php://input'), true);
$recId    = $body['recordId']  ?? '';
$typeId   = $body['typeId']    ?? '';
$fromDate = $body['fromDate']  ?? '';
$toDate   = $body['toDate']    ?? '';
$stuId    = $_SESSION['contact_id'] ?? '';

if (!$recId || !$typeId || !$fromDate || !$toDate || !$stuId) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

try {
    $days  = (strtotime($toDate) - strtotime($fromDate)) / (60 * 60 * 24) + 1;
    $month = date('F', strtotime($fromDate));
    $year  = date('Y', strtotime($fromDate));

    // ✅ 1. Adjust used value in leave_assignments
    $stmt = $pdo->prepare("
        SELECT id, used 
        FROM leave_assignments
        WHERE student_id = :stuId
          AND leave_type_id = :typeId
          AND month = :month
          AND year = :year
        LIMIT 1
    ");
    $stmt->execute([
        'stuId'  => $stuId,
        'typeId' => $typeId,
        'month'  => $month,
        'year'   => $year
    ]);
    $assignment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($assignment) {
        $newUsed = max(0, intval($assignment['used']) - $days);

        $updateStmt = $pdo->prepare("UPDATE leave_assignments SET used = :used WHERE id = :id");
        $updateStmt->execute([
            'used' => $newUsed,
            'id'   => $assignment['id']
        ]);
    }

    // ✅ 2. Delete the leave request from leave_requests
    $deleteStmt = $pdo->prepare("DELETE FROM leave_requests WHERE id = :id AND student_id = :stuId");
    $deleteStmt->execute([
        'id'    => $recId,
        'stuId' => $stuId
    ]);

    if ($deleteStmt->rowCount() > 0) {
        echo json_encode([
            'success' => true,
            'message' => 'Leave request deleted and balance restored.'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to delete leave record or unauthorized.'
        ]);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'DB Error: ' . $e->getMessage()]);
}
