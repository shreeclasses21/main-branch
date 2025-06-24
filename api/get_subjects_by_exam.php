<?php
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

// ✅ Step 1: Validate exam_id
if (!isset($_GET['exam_id']) || !is_numeric($_GET['exam_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Missing or invalid exam ID']);
    exit;
}

$examId = (int) $_GET['exam_id'];

try {
    // ✅ Step 2: Fetch subjects from exam_subjects
    $stmt = $pdo->prepare("SELECT id, subject_name, subject_date FROM exam_subjects WHERE exam_id = ? ORDER BY subject_date ASC");
    $stmt->execute([$examId]);
    $subjects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'subjects' => $subjects
    ]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
}
