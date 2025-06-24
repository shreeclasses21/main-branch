<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

// ✅ Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Admin not logged in']);
    exit;
}

// ✅ Ensure POST method
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_FILES['attachments']['name'][0])) {
        echo json_encode(['status' => 'error', 'message' => 'No files uploaded.']);
        exit;
    }

    $students = $_POST['students'];
    $section = $_POST['section'];
    $files = $_FILES['attachments'];

    // ✅ Determine base directory dynamically
    $scriptPath = dirname($_SERVER['SCRIPT_NAME']); // e.g., /shreeclasses-api/admin/api
    $basePath = explode('/admin', $scriptPath)[0];  // e.g., /shreeclasses-api

    $uploadDir = $basePath . '/uploads/';  // Public-facing path
    $serverPath = realpath(__DIR__ . '/../../uploads/') . '/';  // Filesystem path

    // ✅ Ensure uploads folder is writable
    if (!is_dir($serverPath) || !is_writable($serverPath)) {
        echo json_encode(['status' => 'error', 'message' => 'Uploads directory is not writable.']);
        exit;
    }

    $uploadedFiles = [];

    // ✅ Upload files
    foreach ($files['tmp_name'] as $key => $tmp_name) {
        $originalName = basename($files['name'][$key]);
        $fileName = uniqid() . '-' . $originalName;
        $targetPath = $serverPath . $fileName;
        $webPath = $uploadDir . $fileName;

        if (!move_uploaded_file($tmp_name, $targetPath)) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to upload file: ' . $originalName]);
            exit;
        }

        $uploadedFiles[] = $webPath;
    }

    // ✅ Store file records for each student
    foreach ($students as $student_id) {
        foreach ($uploadedFiles as $webPath) {
            $stmt = $pdo->prepare("INSERT INTO student_files (student_id, section, file_path) VALUES (?, ?, ?)");
            $stmt->execute([$student_id, $section, $webPath]);
        }
    }

    echo json_encode(['status' => 'success', 'message' => 'Files uploaded successfully']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
