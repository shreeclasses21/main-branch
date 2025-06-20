<?php
session_start();

// 🚫 Prevent revisiting after success
if (isset($_SESSION['otp_verified']) && $_SESSION['otp_verified'] === true) {
    header("Location: ../public/reset_password.php");
    exit;
}

require_once __DIR__ . '/../config/db.php'; // MySQL connection
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $enteredOtp = trim($_POST['otp']);
    $email = $_SESSION['student_email'] ?? '';

    if (!$email || !$enteredOtp) {
        echo json_encode(['success' => false, 'message' => '❌ Email or OTP missing.']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT id, otp_code, otp_expiry FROM students WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $student = $stmt->fetch();

    if (!$student) {
        echo json_encode(['success' => false, 'message' => '❌ Student not found.']);
        exit;
    }

    $storedOtp = $student['otp_code'];
    $otpExpiry = strtotime($student['otp_expiry']);
    $now = time();

    if ($enteredOtp === $storedOtp && $now <= $otpExpiry) {
        $_SESSION['contact_id'] = $student['id'];
        $_SESSION['otp_verified'] = true;
        unset($_SESSION['otp_requested']);

        echo json_encode(['success' => true, 'redirect' => '../public/reset_password.php']);
    } else {
        echo json_encode(['success' => false, 'message' => '❌ Invalid or expired OTP.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
}
