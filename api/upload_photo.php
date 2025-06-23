<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json');

// Validate session and file
if (!isset($_SESSION['contact_id'])) {
    http_response_code(403);
    echo json_encode(['error' => 'Unauthorized: Session not found.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['profileImage'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid request or file missing.']);
    exit;
}

$contactId     = $_SESSION['contact_id'];
$file          = $_FILES['profileImage'];
$originalName  = basename($file['name']);
$tmpPath       = $file['tmp_name'];
$fileExt       = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
$allowedExts   = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

if (!in_array($fileExt, $allowedExts)) {
    http_response_code(415);
    echo json_encode(['error' => 'Unsupported file type.']);
    exit;
}

// Save image locally
$localDir = __DIR__ . '/../public/uploads/';
if (!is_dir($localDir)) {
    mkdir($localDir, 0777, true);
}

$localFilename = 'photo_' . $contactId . '_' . time() . '.' . $fileExt;
$localPath     = $localDir . $localFilename;
$publicUrl = '/public/uploads/' . $localFilename;

if (!move_uploaded_file($tmpPath, $localPath)) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to save image locally.']);
    exit;
}

// Respond with image URL only (no Salesforce)
echo json_encode(['success' => true, 'url' => $publicUrl]);
