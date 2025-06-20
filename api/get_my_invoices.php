<?php
session_start();
require_once __DIR__ . '/../config/db.php'; // ✅ DB Connection
header('Content-Type: application/json');

// ✅ Check if student is logged in
if (!isset($_SESSION['contact_id'])) {
    echo json_encode(['status' => 'error', 'error' => 'Unauthorized']);
    exit;
}

$studentId = $_SESSION['contact_id'];

try {
    $stmt = $pdo->prepare("SELECT * FROM payment_invoices WHERE student_id = ? ORDER BY created_at DESC");
    $stmt->execute([$studentId]);

    $invoices = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['status' => 'success', 'data' => $invoices]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'error' => $e->getMessage()]);
}
