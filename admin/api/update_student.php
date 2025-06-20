<?php
require_once __DIR__ . '/../../config/db.php';
$data = json_decode(file_get_contents('php://input'), true);

$id = $data['id'] ?? '';

if (!$id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing ID']);
    exit;
}

// Get all fields
$fields = [
    'first_name', 'last_name', 'email', 'student_id', 'status',
    'mobile', 'phone', 'home_phone',
    'birthdate', 'section', 'board', 'guardian_name',
    'mailing_street', 'mailing_city', 'mailing_state',
    'mailing_postal', 'mailing_country'
];

$values = [];
$setClauses = [];

foreach ($fields as $field) {
    $values[$field] = $data[$field] ?? null;
    $setClauses[] = "$field = :$field";
}

$setSQL = implode(', ', $setClauses);

$sql = "UPDATE students SET $setSQL, updated_at = NOW() WHERE id = :id";
$stmt = $pdo->prepare($sql);
$values['id'] = $id;

$stmt->execute($values);

echo json_encode(['status' => 'success']);
