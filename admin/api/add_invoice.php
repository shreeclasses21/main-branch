<?php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

try {
    $stmt = $pdo->prepare("INSERT INTO payment_invoices
      (student_id, student_name, email, phone, month, year, amount, status, type)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");

    $stmt->execute([
        $data['student_id'], $data['student_name'], $data['email'], $data['phone'],
        $data['month'], $data['year'], $data['amount'], $data['status'], $data['type']
    ]);

    echo json_encode(['status' => 'success', 'message' => 'Invoice created.']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'error' => $e->getMessage()]);
}
