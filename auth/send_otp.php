<?php
session_start();
require_once __DIR__ . '/../config/db.php';


// ✅ Manual PHPMailer includes
require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email']);
    $_SESSION['student_email'] = $email;
    $_SESSION['otp_requested'] = true;

    // Step 1: Validate email in DB
    $stmt = $pdo->prepare("SELECT id FROM students WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $student = $stmt->fetch();

    if (!$student) {
        echo "❌ Email not found.";
        exit;
    }

    // Step 2: Generate OTP & expiry (10 minutes)
    $otpCode = rand(100000, 999999);
    $otpExpiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    // Step 3: Save OTP in DB
    $updateStmt = $pdo->prepare("UPDATE students SET otp_code = :otp, otp_expiry = :expiry WHERE id = :id");
    $updateStmt->execute([
        'otp' => $otpCode,
        'expiry' => $otpExpiry,
        'id' => $student['id']
    ]);

    // Step 4: Send email using PHPMailer
    $mail = new PHPMailer(true);

    try {
        // SMTP Settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'utest6773@gmail.com';        // ✅ Your Gmail
        $mail->Password = 'bowxnxqgobiepopd';           // ✅ Gmail App Password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        // Email Setup
        $mail->setFrom('utest6773@gmail.com', 'Shree Classes');
        $mail->addAddress($email);
        $mail->Subject = '🔐 Your OTP Code';

        // ✅ Load HTML template and inject OTP
        $templatePath = __DIR__ . '/../templates/otp_email_template.html';
        $body = file_get_contents($templatePath);
        $body = str_replace('{{OTP}}', $otpCode, $body);

        $mail->isHTML(true);
        $mail->Body = $body;

        $mail->send();

        // ✅ Redirect on success
        header("Location: ../public/enter_otp.php");
        exit;
    } catch (Exception $e) {
        echo "❌ Mail Error: " . $mail->ErrorInfo;
    }
} else {
    echo "Invalid request.";
}
