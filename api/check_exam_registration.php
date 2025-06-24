<?php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['student_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$studentId = $_SESSION['student_id'];

if (!isset($_GET['exam_id']) || !is_numeric($_GET['exam_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid exam ID']);
    exit;
}

$examId = $_GET['exam_id'];

try {
    $stmt = $pdo->prepare("SELECT subject_name FROM exam_registrations WHERE student_id = ? AND exam_id = ?");
    $stmt->execute([$studentId, $examId]);
    $registeredSubjects = $stmt->fetchAll(PDO::FETCH_COLUMN);

    echo json_encode([
        'status' => 'success',
        'already_registered' => count($registeredSubjects) > 0,
        'subjects' => $registeredSubjects
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
}
