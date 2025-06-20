<?php
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);
require_once __DIR__ . '/../../config/db.php';


try {
    $stmt = $pdo->query("SELECT * FROM public_holidays ORDER BY holiday_date ASC");
    $holidays = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($holidays);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'error' => 'Failed to fetch holidays']);
}
