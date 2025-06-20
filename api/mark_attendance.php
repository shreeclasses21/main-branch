<?php
session_start();
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

// ✅ Set Indian timezone
date_default_timezone_set('Asia/Kolkata');

if (!isset($_SESSION['contact_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$contactId = $_SESSION['contact_id'];
$today = date('Y-m-d'); // now correct in IST

try {
    // Check if attendance already marked
    $stmt = $pdo->prepare("SELECT id FROM attendance_log WHERE student_id = :studentId AND attendance_date = :today LIMIT 1");
    $stmt->execute(['studentId' => $contactId, 'today' => $today]);

    if ($stmt->rowCount()) {
        echo json_encode(['status' => 'exists', 'message' => 'Attendance already marked for today.']);
        exit;
    }

    // Insert today's attendance
    $insert = $pdo->prepare("
        INSERT INTO attendance_log (student_id, attendance_date, type, created_at)
        VALUES (:studentId, :today, 'Present', NOW())
    ");
    $insert->execute(['studentId' => $contactId, 'today' => $today]);

    echo json_encode(['status' => 'success', 'message' => '✅ Present marked successfully']);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB Error: ' . $e->getMessage()]);
}
