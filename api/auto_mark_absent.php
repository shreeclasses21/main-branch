<?php
require_once __DIR__ . '/../config/db.php';

header('Content-Type: application/json');

$today = date('Y-m-d');

try {
    // 🛑 Step 0: Prevent duplicate run
    $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM attendance_log WHERE attendance_date = :today");
    $checkStmt->execute(['today' => $today]);
    $existingCount = $checkStmt->fetchColumn();

    if ($existingCount > 0) {
        echo json_encode(['message' => 'Attendance already marked for today']);
        exit;
    }

    // ✅ Step 1: Get all students
    $studentsStmt = $pdo->query("SELECT id FROM students");
    $allStudentIds = $studentsStmt->fetchAll(PDO::FETCH_COLUMN);

    if (empty($allStudentIds)) {
        echo json_encode(['message' => 'No students found']);
        exit;
    }

    // ✅ Step 2: Get students who marked attendance today
    $attendedStmt = $pdo->prepare("SELECT student_id FROM attendance_log WHERE attendance_date = :today");
    $attendedStmt->execute(['today' => $today]);
    $attendedIds = $attendedStmt->fetchAll(PDO::FETCH_COLUMN);

    // ✅ Step 3: Get students on leave today
    $leaveStmt = $pdo->prepare("
        SELECT student_id FROM leave_assignments
        WHERE from_date <= :today AND to_date >= :today AND status = 'Approved'
    ");
    $leaveStmt->execute(['today' => $today]);
    $onLeaveIds = $leaveStmt->fetchAll(PDO::FETCH_COLUMN);

    // ✅ Step 4: Calculate who is absent
    $absentStudents = array_diff($allStudentIds, array_merge($attendedIds, $onLeaveIds));
    $insertStmt = $pdo->prepare("
        INSERT INTO attendance_log (student_id, attendance_date, type, created_at)
        VALUES (:studentId, :date, 'Absent', NOW())
    ");

    foreach ($absentStudents as $sid) {
        $insertStmt->execute([
            'studentId' => $sid,
            'date'      => $today
        ]);
    }

    // ✅ Step 5: Log this run
    $logStmt = $pdo->prepare("
        INSERT INTO attendance_run_log (run_date, run_time, marked_count)
        VALUES (:date, NOW(), :count)
    ");
    $logStmt->execute([
        'date'  => $today,
        'count' => count($absentStudents)
    ]);

    echo json_encode([
        'status'        => 'success',
        'absent_count'  => count($absentStudents),
        'date'          => $today
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB Error: ' . $e->getMessage()]);
}
