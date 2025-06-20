<?php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$id = $data['id'] ?? null;
$date = $data['holiday_date'] ?? null;
$name = $data['name'] ?? null;
$type = $data['type'] ?? null;
$year = $data['year'] ?? null;

if (!$date || !$name || !$year) {
    echo json_encode(['status' => 'error', 'error' => 'Missing required fields']);
    exit;
}

try {
    if ($id) {
        // Update existing
        $stmt = $pdo->prepare("UPDATE public_holidays SET holiday_date=?, name=?, type=?, year=? WHERE id=?");
        $stmt->execute([$date, $name, $type, $year, $id]);
    } else {
        // Insert new
        $stmt = $pdo->prepare("INSERT INTO public_holidays (holiday_date, name, type, year) VALUES (?, ?, ?, ?)");
        $stmt->execute([$date, $name, $type, $year]);
    }

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'error' => 'DB Error: ' . $e->getMessage()]);
}
