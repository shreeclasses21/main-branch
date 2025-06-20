<?php
session_start();
require_once __DIR__ . '/../config/db.php';
header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ALL);

// 🔐 Require login
if (!isset($_SESSION['contact_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Not logged in']);
    exit;
}

$studentId = $_SESSION['contact_id'];

try {
    $stmt = $pdo->prepare("
        SELECT 
            id AS contactId,
            CONCAT(first_name, ' ', last_name) AS name,
            email,
            student_id AS studentId,
            status,
            phone,
            mobile,
            date_of_birth AS dob,
            guardian_name AS guardianName,
            profile_photo_url AS photoUrl,
            board,
            section,
            current_grade AS grade,
            mailing_street AS mailingStreet,
            mailing_city AS mailingCity,
            mailing_state AS mailingState,
            mailing_postal AS mailingPostal,
            mailing_country AS mailingCountry,
            NOT first_profile AS isFirstProfile
        FROM students
        WHERE id = :id
        LIMIT 1
    ");
    $stmt->execute(['id' => $studentId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        echo json_encode($row);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Student not found']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'DB Error: ' . $e->getMessage()]);
}
