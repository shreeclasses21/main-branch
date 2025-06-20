<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode([]);
    exit;
}

require_once __DIR__ . '/../../config/db.php';

$nameFilter = $_GET['name'] ?? '';
$sql = "
    SELECT r.*, CONCAT(s.first_name, ' ', s.last_name) AS full_name
    FROM regularization_requests r
    LEFT JOIN students s ON r.student_id = s.id
";

// Always use prepare if conditionally building
if (!empty($nameFilter)) {
    $sql .= " WHERE CONCAT(s.first_name, ' ', s.last_name) LIKE ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%" . $nameFilter . "%"]);
} else {
    $sql .= " ORDER BY r.created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
}

echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
