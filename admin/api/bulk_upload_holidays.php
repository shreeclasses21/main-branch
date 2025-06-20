<?php
session_start();
header('Content-Type: application/json');

// ✅ Authorization check
if (!isset($_SESSION['admin_id'])) {
    http_response_code(403);
    echo json_encode(['status' => 'error', 'error' => 'Unauthorized access.']);
    exit;
}

require_once __DIR__ . '/../../config/db.php';

// ✅ File validation
if (!isset($_FILES['holidayCsvFile']) || $_FILES['holidayCsvFile']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['status' => 'error', 'error' => 'CSV file upload failed.']);
    exit;
}

$csvFile = $_FILES['holidayCsvFile']['tmp_name'];
$handle = fopen($csvFile, 'r');
if (!$handle) {
    echo json_encode(['status' => 'error', 'error' => 'Unable to open the uploaded CSV file.']);
    exit;
}

$header = fgetcsv($handle); // Skip header
$expectedHeader = ['holiday_date', 'name', 'type', 'year'];
if (array_map('strtolower', $header) !== $expectedHeader) {
    echo json_encode(['status' => 'error', 'error' => 'CSV header must be: holiday_date,name,type,year']);
    exit;
}

$inserted = 0;
$skipped = 0;
$errors = [];

$stmt = $pdo->prepare("INSERT INTO public_holidays (holiday_date, name, type, year) VALUES (?, ?, ?, ?)");

// ✅ Parse and insert rows
while (($row = fgetcsv($handle, 1000, ",")) !== false) {
    if (count($row) < 4) {
        $errors[] = "❌ Skipped row with missing columns: " . implode(', ', $row);
        $skipped++;
        continue;
    }

    [$date, $name, $type, $year] = array_map('trim', $row);

    if (!strtotime($date) || empty($name) || !is_numeric($year)) {
        $errors[] = "⚠️ Invalid data format: $date, $name, $type, $year";
        $skipped++;
        continue;
    }

    try {
        $stmt->execute([$date, $name, $type, $year]);
        $inserted++;
    } catch (PDOException $e) {
        $errors[] = "❌ DB Error on '$name': " . $e->getMessage();
        $skipped++;
    }
}

fclose($handle);

// ✅ Final response
if ($inserted > 0 && $skipped === 0) {
    echo json_encode([
        'status' => 'success',
        'message' => "✅ Successfully uploaded $inserted public holidays."
    ]);
} else {
    echo json_encode([
        'status' => $inserted > 0 ? 'partial_success' : 'error',
        'message' => "ℹ️ Uploaded $inserted rows. Skipped $skipped rows.",
        'errors' => $errors
    ]);
}
