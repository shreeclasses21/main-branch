<?php
require_once __DIR__ . '/../../config/db.php';

$stmt = $pdo->query("SELECT * FROM students ORDER BY created_at DESC");
$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($students);
