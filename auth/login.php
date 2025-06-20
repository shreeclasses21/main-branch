<?php
session_start();
require_once __DIR__ . '/../config/db.php';  // ← DB connection file

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $loginId  = trim($_POST['login_id'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!$loginId || !$password) {
        redirectWithError('Please enter both Email/Student ID and Password.');
    }

    try {
        // Fetch user by email or student ID
        $stmt = $pdo->prepare("SELECT * FROM students WHERE email = :login OR student_id = :login LIMIT 1");
        $stmt->execute(['login' => $loginId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$student) {
            redirectWithError('❌ Student not found. Please check your credentials.');
        }

        $storedPass = trim($student['password']);

        if (!$storedPass) {
            redirectWithError('⚠️ Password not set. Please reset your password.');
        }

        if ($password !== $storedPass) {
            redirectWithError('❌ Incorrect password.');
        }

        if (!empty($student['first_time_login']) && $student['first_time_login']) {
            redirectWithError('⚠️ You must reset your password before logging in.');
        }

        // ✅ Success - Set session
        $_SESSION['contact_id']     = $student['id'];
        $_SESSION['student_email']  = $student['email'];
        $_SESSION['student_id']     = $student['student_id'];

        header("Location: ../public/dashboard.php");
        exit;

    } catch (PDOException $e) {
        redirectWithError("⚠️ Database error: " . $e->getMessage());
    }
}

function redirectWithError($message) {
    $encoded = urlencode($message);
    header("Location: ../public/login.php?error=$encoded");
    exit;
}
