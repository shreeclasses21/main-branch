<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['contact_id'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'error' => 'Unauthorized']);
    exit;
}

require_once __DIR__ . '/../config/db.php';

$studentId = $_SESSION['contact_id'];

// Get current month and year
$currentMonth = date('m');
$currentYear = date('Y');

try {
    $stmt = $pdo->prepare("
        SELECT type, COUNT(*) as count
        FROM attendance_log
        WHERE student_id = :studentId
          AND MONTH(attendance_date) = :month
          AND YEAR(attendance_date) = :year
        GROUP BY type
    ");
    $stmt->execute([
        'studentId' => $studentId,
        'month' => $currentMonth,
        'year' => $currentYear
    ]);

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $summary = [
        'Present' => 0,
        'Absent' => 0,
        'On Leave' => 0
    ];

    foreach ($results as $row) {
        $type = $row['type'];
        $summary[$type] = (int)$row['count'];
    }

    echo json_encode([
        'status' => 'success',
        'data' => $summary
    ]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'error' => $e->getMessage()]);
}
