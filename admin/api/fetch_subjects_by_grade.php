<?php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$grade = $_GET['grade'] ?? '';
if (!$grade) {
    echo json_encode([]);
    exit;
}

$stmt = $pdo->prepare("SELECT subject_name FROM subjects WHERE grade = ? ORDER BY subject_name ASC");
$stmt->execute([$grade]);
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
