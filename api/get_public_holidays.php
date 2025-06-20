<?php
// api/get_public_holidays.php
session_start();
require_once __DIR__ . '/../config/db.php'; // ✅ DB connection file

header('Content-Type: application/json');

// Require login
if (!isset($_SESSION['contact_id'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Not logged in']);
  exit;
}

try {
  // Allow override of year via ?year=2025
  $year = preg_match('/^\d{4}$/', $_GET['year'] ?? '') ? $_GET['year'] : date('Y');

  // Fetch from MySQL
  $stmt = $pdo->prepare("
    SELECT holiday_date, name, type
    FROM public_holidays
    WHERE year = :year
    ORDER BY holiday_date
  ");
  $stmt->execute(['year' => $year]);
  $holidays = $stmt->fetchAll(PDO::FETCH_ASSOC);

  // Return JSON format similar to Salesforce DTO
  $out = array_map(function ($h) {
    return [
      'holidayDate' => $h['holiday_date'],
      'name'        => $h['name'],
      'type'        => $h['type']
    ];
  }, $holidays);

  echo json_encode($out);
} catch (PDOException $e) {
  http_response_code(500);
  echo json_encode(['error' => 'DB Error: ' . $e->getMessage()]);
}
