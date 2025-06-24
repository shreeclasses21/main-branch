<?php
session_start();
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

if (!isset($_SESSION['student_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);
$studentId = $_SESSION['student_id'];
$examId = $data['exam_id'] ?? null;
$subjects = $data['subjects'] ?? [];

if (!$examId || empty($subjects)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing exam or subjects']);
    exit;
}

try {
    // 🛑 Check if student already registered
    $check = $pdo->prepare("SELECT COUNT(*) FROM exam_registrations WHERE exam_id = ? AND student_id = ?");
    $check->execute([$examId, $studentId]);
    $alreadyRegistered = $check->fetchColumn();

    if ($alreadyRegistered > 0) {
        echo json_encode(['status' => 'error', 'message' => '❌ You have already registered for this exam.']);
        exit;
    }

    // ✅ Register subjects
    $insert = $pdo->prepare("INSERT INTO exam_registrations (exam_id, student_id, subject_name) VALUES (?, ?, ?)");
    foreach ($subjects as $subjectName) {
        $insert->execute([$examId, $studentId, $subjectName]);
    }

    echo json_encode(['status' => 'success', 'message' => '✅ Exam registration submitted successfully']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
}
