<?php
require_once __DIR__ . '/../config/db.php';
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['contact_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$studentId = $_SESSION['contact_id'];

try {
    // 🔄 Get student's current grade
    $stmt = $pdo->prepare("SELECT current_grade FROM students WHERE id = ?");
    $stmt->execute([$studentId]);
    $student = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$student) {
        echo json_encode(['status' => 'error', 'message' => 'Student not found']);
        exit;
    }

    $grade = $student['current_grade'];

    // 📚 Get exams for that grade
    $examStmt = $pdo->prepare("SELECT * FROM exams WHERE grade = ? ORDER BY start_date DESC");
    $examStmt->execute([$grade]);
    $exams = $examStmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'exams' => $exams]);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
}
