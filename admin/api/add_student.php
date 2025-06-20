<?php
// File: api/add_student.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../../config/db.php';

header('Content-Type: application/json');

// Get raw JSON data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['status' => 'error', 'error' => 'Invalid JSON input.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO students (id, student_id, first_name, last_name, email, status, created_at) VALUES (:id, :student_id, :first_name, :last_name, :email, :status, NOW())");

    $stmt->execute([
        'id'          => uniqid(),
        'student_id'  => $data['student_id'] ?? '',
        'first_name'  => $data['first_name'] ?? '',
        'last_name'   => $data['last_name'] ?? '',
        'email'       => $data['email'] ?? '',
        'status'      => $data['status'] ?? 'Inactive'
    ]);

    echo json_encode(['status' => 'success']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'error' => $e->getMessage()]);
}
