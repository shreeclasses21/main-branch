<?php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ALL);

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;
$status = $data['status'] ?? null;

if (!$id || !$status) {
    echo json_encode(['status' => 'error', 'error' => 'Missing fields']);
    exit;
}

try {
    // Update the leave_requests table
    $stmt = $pdo->prepare("UPDATE leave_requests SET status = ? WHERE id = ?");
    $stmt->execute([$status, $id]);

    if (strtolower($status) === 'rejected') {
        // Get student_id and leave_type_id from the leave request
        $reqStmt = $pdo->prepare("SELECT student_id, leave_type_id, 
            YEAR(from_date) as year, 
            MONTHNAME(from_date) as month 
            FROM leave_requests WHERE id = ?");
        $reqStmt->execute([$id]);
        $request = $reqStmt->fetch(PDO::FETCH_ASSOC);

        if ($request) {
            $updateAssign = $pdo->prepare("
                UPDATE leave_assignments
                SET used = GREATEST(used - 1, 0), remaining = remaining + 1
                WHERE student_id = ? AND leave_type_id = ? AND year = ? AND month = ?
            ");
            $updateAssign->execute([
                $request['student_id'],
                $request['leave_type_id'],
                $request['year'],
                $request['month']
            ]);
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Leave status updated']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'error' => $e->getMessage()]);
}
