<?php
session_start();
require_once __DIR__ . '/../config/db.php'; // DB connection

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo "Method Not Allowed";
    exit;
}

if (!isset($_SESSION['contact_id'])) {
    http_response_code(401);
    echo "❌ Session expired. Please log in again.";
    exit;
}

// Get form inputs
$studentId   = $_SESSION['contact_id'];
$leaveTypeId = $_POST['Leave_Type__c'] ?? '';
$fromDate    = $_POST['From_Date__c'] ?? '';
$toDate      = $_POST['To_Date__c'] ?? '';
$reason      = $_POST['Reason__c'] ?? '';

if (!$leaveTypeId || !$fromDate || !$toDate) {
    http_response_code(400);
    echo "❌ All leave fields are required.";
    exit;
}

$daysRequested = (strtotime($toDate) - strtotime($fromDate)) / (60 * 60 * 24) + 1;
if ($daysRequested <= 0) {
    http_response_code(400);
    echo "❌ Invalid date range.";
    exit;
}

try {
    // 1. Check overlapping leaves
    $stmt = $pdo->prepare("
        SELECT id FROM leave_requests
        WHERE student_id = :student_id
        AND status IN ('Pending', 'Approved')
        AND (
            (from_date <= :to_date AND to_date >= :from_date)
        )
    ");
    $stmt->execute([
        'student_id' => $studentId,
        'from_date'  => $fromDate,
        'to_date'    => $toDate
    ]);
    if ($stmt->rowCount() > 0) {
        http_response_code(400);
        echo "❌ Overlapping leave request exists.";
        exit;
    }

    // 2. Insert new leave request
    $insertLeave = $pdo->prepare("
        INSERT INTO leave_requests (student_id, leave_type_id, from_date, to_date, reason, status)
        VALUES (:student_id, :leave_type_id, :from_date, :to_date, :reason, 'Pending')
    ");
    $insertLeave->execute([
        'student_id'    => $studentId,
        'leave_type_id' => $leaveTypeId,
        'from_date'     => $fromDate,
        'to_date'       => $toDate,
        'reason'        => $reason
    ]);

    // 3. Month and Year
    $month = date('F', strtotime($fromDate));
    $year  = date('Y', strtotime($fromDate));

    // 4. Check existing assignment
    $checkAssignment = $pdo->prepare("
        SELECT id, used, allowed FROM leave_assignments
        WHERE student_id = :student_id AND leave_type_id = :leave_type_id
        AND month = :month AND year = :year
        LIMIT 1
    ");
    $checkAssignment->execute([
        'student_id'    => $studentId,
        'leave_type_id' => $leaveTypeId,
        'month'         => $month,
        'year'          => $year
    ]);

    if ($assignment = $checkAssignment->fetch()) {
        $newUsed = $assignment['used'] + $daysRequested;
        if ($newUsed > $assignment['allowed']) {
            error_log("⚠️ Warning: Leave exceeds allowed limit.");
        }

        // Update used
        $update = $pdo->prepare("UPDATE leave_assignments SET used = :used WHERE id = :id");
        $update->execute(['used' => $newUsed, 'id' => $assignment['id']]);

    } else {
        // Insert new assignment
        $insertAssignment = $pdo->prepare("
            INSERT INTO leave_assignments (student_id, leave_type_id, month, year, used, allowed)
            VALUES (:student_id, :leave_type_id, :month, :year, :used, 0)
        ");
        $insertAssignment->execute([
            'student_id'    => $studentId,
            'leave_type_id' => $leaveTypeId,
            'month'         => $month,
            'year'          => $year,
            'used'          => $daysRequested
        ]);
    }

    echo "✅ Leave submitted successfully.";

} catch (PDOException $e) {
    http_response_code(500);
    echo "❌ DB Error: " . $e->getMessage();
}
