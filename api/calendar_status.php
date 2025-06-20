<?php
session_start();
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

// ✅ Set timezone
date_default_timezone_set('Asia/Kolkata');

// ✅ Check session
if (!isset($_SESSION['contact_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$contactId = $_SESSION['contact_id'];
$month = $_GET['month'] ?? date('Y-m'); // Format: YYYY-MM
$startDate = $month . '-01';
$endDate = date('Y-m-t', strtotime($startDate));

try {
    // ✅ Fetch attendance log for the month
    $attendanceStmt = $pdo->prepare("
        SELECT attendance_date, type 
        FROM attendance_log 
        WHERE student_id = :studentId 
          AND attendance_date BETWEEN :start AND :end
    ");
    $attendanceStmt->execute([
        'studentId' => $contactId,
        'start'     => $startDate,
        'end'       => $endDate
    ]);

    $attendanceMap = [];
    while ($row = $attendanceStmt->fetch(PDO::FETCH_ASSOC)) {
        $attendanceMap[$row['attendance_date']] = $row['type'];
    }

    // ✅ Fetch approved leaves for the month
    $leaveStmt = $pdo->prepare("
        SELECT from_date, to_date
        FROM leave_requests 
        WHERE student_id = :studentId 
          AND status = 'Approved'
          AND (
              (from_date BETWEEN :start AND :end) OR
              (to_date BETWEEN :start AND :end) OR
              (:start BETWEEN from_date AND to_date)
          )
    ");
    $leaveStmt->execute([
        'studentId' => $contactId,
        'start'     => $startDate,
        'end'       => $endDate
    ]);

    while ($row = $leaveStmt->fetch(PDO::FETCH_ASSOC)) {
        $from = new DateTime($row['from_date']);
        $to = new DateTime($row['to_date']);

        // ✅ Loop through each date in leave range
        for ($date = clone $from; $date <= $to; $date->modify('+1 day')) {
            $d = $date->format('Y-m-d');
            // Only add leave status if attendance not already marked
            if (!isset($attendanceMap[$d])) {
                $attendanceMap[$d] = 'On Leave';
            }
        }
    }

    echo json_encode($attendanceMap);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB Error: ' . $e->getMessage()]);
}
