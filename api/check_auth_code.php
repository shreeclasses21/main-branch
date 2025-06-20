<?php
session_start(); // ✅ Needed to track flow across pages
require_once '../config/db.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"), true);
$code = trim($data['code'] ?? '');

if (!$code) {
    echo json_encode(['status' => 'error', 'message' => 'Authorization code is required']);
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM auth_codes WHERE code = ? AND is_used = 0 LIMIT 1");
$stmt->execute([$code]);
$auth = $stmt->fetch();

if ($auth) {
    // ✅ Store in session
    $_SESSION['auth_code_verified'] = true;
    $_SESSION['auth_code'] = $code;

    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid or used authorization code']);
}
