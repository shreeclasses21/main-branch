<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

// Ensure admin is logged in
if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Admin not logged in']);
    exit;
}

// Get the grade from the query parameters (8th or 9th)
$grade = isset($_GET['grade']) ? $_GET['grade'] : '8th'; // Default to 8th grade if not provided

// Fetch students from the database based on current_grade
$query = "SELECT id, first_name, last_name FROM students WHERE current_grade = :grade ORDER BY first_name";
$stmt = $pdo->prepare($query);
$stmt->execute(['grade' => $grade]);

$students = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Return the student data as JSON
echo json_encode($students);
?>
