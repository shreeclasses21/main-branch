<?php
session_start();
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

$leaveTypeId = $_GET['type_id'] ?? '';
$studentId   = $_SESSION['contact_id'] ?? '';

if (!$leaveTypeId || !$studentId) {
    echo json_encode(['error' => 'Missing parameters']);
    exit;
}

try {
    $month = date('F');  // e.g. "June"
    $year  = date('Y');  // e.g. 2025

    $stmt = $pdo->prepare("
        SELECT allowed, used
        FROM leave_assignments
        WHERE student_id = :studentId
          AND leave_type_id = :leaveTypeId
          AND month = :month
          AND year = :year
        LIMIT 1
    ");
    $stmt->execute([
        'studentId'   => $studentId,
        'leaveTypeId' => $leaveTypeId,
        'month'       => $month,
        'year'        => $year
    ]);

    if ($assignment = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo json_encode([
            'used'    => (int) $assignment['used'],
            'allowed' => (int) $assignment['allowed']
        ]);
    } else {
        echo json_encode(['used' => 0, 'allowed' => 0]);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
