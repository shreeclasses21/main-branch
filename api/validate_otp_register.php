<?php
require_once '../config/db.php';
require_once '../PHPMailer-master/src/Exception.php';
require_once '../PHPMailer-master/src/PHPMailer.php';
require_once '../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');
date_default_timezone_set('Asia/Kolkata');

// ✅ Read and validate OTP
$data = json_decode(file_get_contents('php://input'), true);
$otp = trim($data['otp'] ?? '');

if (!$otp || strlen($otp) !== 6) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid OTP format.']);
    exit;
}

// ✅ Get OTP record from student_otp_verification
$stmt = $pdo->prepare("SELECT * FROM student_otp_verification WHERE otp_code = :otp LIMIT 1");
$stmt->execute(['otp' => $otp]);
$record = $stmt->fetch();

if (!$record) {
    echo json_encode(['status' => 'error', 'message' => "OTP is incorrect."]);
    exit;
}

// ✅ Insert student with ID = student_id
$manualId = $record['student_id']; // Unique 6-digit alphanumeric string
$insert = $pdo->prepare("INSERT INTO students (id, student_id, first_name, last_name, email, first_profile) VALUES (?, ?, ?, ?, ?, ?)");
$insert->execute([
    $manualId,
    $manualId,
    $record['first_name'],
    $record['last_name'],
    $record['email'],
    0
]);

// ✅ Mark auth code as used
if (!empty($record['auth_code'])) {
    $auth = $pdo->prepare("UPDATE auth_codes SET is_used = 1 WHERE code = :code");
    $auth->execute(['code' => $record['auth_code']]);
}

// ✅ Remove temporary OTP record
$pdo->prepare("DELETE FROM student_otp_verification WHERE id = ?")->execute([$record['id']]);

// ✅ Send registration success email
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'utest6773@gmail.com';
    $mail->Password = 'bowxnxqgobiepopd'; // Your Gmail App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('utest6773@gmail.com', 'Shree Classes');
    $mail->addAddress($record['email']);
    $mail->Subject = '✅ Registration Successful - Shree Classes';
    $mail->isHTML(true);

    $templatePath = '../templates/registration_success_template.html';
    if (!file_exists($templatePath)) {
        throw new Exception('Template not found.');
    }

    $emailBody = file_get_contents($templatePath);
    $emailBody = str_replace('{{first_name}}', htmlspecialchars($record['first_name']), $emailBody);
    $emailBody = str_replace('{{student_id}}', $manualId, $emailBody);
    $emailBody = str_replace('{{login_link}}', 'http://localhost/shreeclasses-api/auth/login.php', $emailBody);

    $mail->Body = $emailBody;
    $mail->send();
} catch (Exception $e) {
    error_log("📧 Mail Error: " . $mail->ErrorInfo);
}

// ✅ Destroy session to prevent back navigation
session_start();
session_unset();
session_destroy();

// ✅ Final response
echo json_encode(['status' => 'success']);
