<?php
require_once '../config/db.php'; // Adjust if needed

header('Content-Type: application/json');

// Parse JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (!$data || empty($data['consent'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid data']);
    exit;
}

// Gather metadata
$ip = $_SERVER['REMOTE_ADDR'];
$acceptedAt = $data['timestamp'] ?? date('Y-m-d H:i:s');
$browser = $data['browser'] ?? '';
$language = $data['language'] ?? '';

// Insert into DB
$stmt = $pdo->prepare("
    INSERT INTO cookie_logs (ip_address, accepted_at, browser_info, language)
    VALUES (?, ?, ?, ?)
");
$stmt->execute([$ip, $acceptedAt, $browser, $language]);

echo json_encode(['status' => 'success']);
