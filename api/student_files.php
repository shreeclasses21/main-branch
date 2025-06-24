<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['contact_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized access']);
    exit;
}

$studentId = $_SESSION['contact_id'];

try {
    $stmt = $pdo->prepare("SELECT section, file_path, uploaded_at FROM student_files WHERE student_id = :student_id ORDER BY section, uploaded_at DESC");
    $stmt->execute(['student_id' => $studentId]);

    $filesBySection = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $section = $row['section'];
        $fileName = basename($row['file_path']);

        if (!isset($filesBySection[$section])) {
            $filesBySection[$section] = [];
        }

        $filesBySection[$section][] = [
            'file_path' => $row['file_path'],
            'file_name' => $fileName,
            'uploaded_at' => $row['uploaded_at']
        ];
    }

    echo json_encode(['status' => 'success', 'files' => $filesBySection]);

} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
}
