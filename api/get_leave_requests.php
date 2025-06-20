<?php
session_start();
require_once __DIR__ . '/../config/db.php'; // ✅ MySQL DB connection
header('Content-Type: application/json');

// Turn off warnings/notices so they don’t break JSON
error_reporting(E_ERROR | E_PARSE);

// ✅ Check session
if (!isset($_SESSION['contact_id'])) {
    echo json_encode(['error' => 'Not logged in']);
    exit;
}
$studentId = $_SESSION['contact_id'];

try {
    // ✅ Fetch leave records from DB
    $stmt = $pdo->prepare("
    SELECT id, leave_type_id, from_date, to_date, reason, status, created_at
    FROM leave_requests
    WHERE student_id = :studentId
    ORDER BY created_at DESC
");
    $stmt->execute(['studentId' => $studentId]);
    $records = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // ✅ Format response
    $out = array_map(function ($r) {
        return [
            'id'          => $r['id'],
            'typeId'      => $r['leave_type_id'],
            'fromDate'    => $r['from_date'],
            'toDate'      => $r['to_date'],
            'reason'      => $r['reason'],
            'status'      => $r['status'] ?? 'N/A',
            'createdDate' => substr($r['created_at'], 0, 10)
        ];
    }, $records);

    echo json_encode($out);
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
