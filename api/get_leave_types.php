<?php
require_once __DIR__ . '/../config/db.php'; // ✅ Include DB connection

try {
    $stmt = $pdo->query("SELECT id, name FROM leave_types ORDER BY name");
    $leaveTypes = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $leaveTypes[] = [
            'id' => $row['id'],
            'name' => $row['name']
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($leaveTypes);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Database error: ' . $e->getMessage()
    ]);
}
