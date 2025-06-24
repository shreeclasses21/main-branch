<?php
require_once __DIR__ . '/../../config/db.php';
header('Content-Type: application/json');

if (!isset($_GET['exam_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing exam ID']);
    exit;
}

$examId = $_GET['exam_id'];

try {
    // Step 1: Get grade for the exam
    $stmt = $pdo->prepare("SELECT grade FROM exams WHERE id = ?");
    $stmt->execute([$examId]);
    $exam = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$exam) {
        echo json_encode(['status' => 'error', 'message' => 'Exam not found']);
        exit;
    }

    $grade = $exam['grade'];

    // Step 2: Get subjects for that grade
    $subjectStmt = $pdo->prepare("SELECT id, subject_name FROM subjects WHERE grade = ?");
    $subjectStmt->execute([$grade]);
    $subjects = $subjectStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'grade' => $grade, 'subjects' => $subjects]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
}
