<?php
require_once '../config/db.php';
require_once '../PHPMailer-master/src/Exception.php';
require_once '../PHPMailer-master/src/PHPMailer.php';
require_once '../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$first = trim($data['first_name'] ?? '');
$last = trim($data['last_name'] ?? '');
$email = trim($data['email'] ?? '');

if (!$first || !$last || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid input.']);
    exit;
}

// ✅ Check if already registered
$checkStmt = $pdo->prepare("SELECT id FROM students WHERE email = ?");
$checkStmt->execute([$email]);
if ($checkStmt->fetch()) {
    echo json_encode(['status' => 'error', 'message' => 'Email already registered.']);
    exit;
}

// ✅ Generate unique student ID
function generateStudentId($length = 6) {
    return strtoupper(substr(bin2hex(random_bytes(6)), 0, $length));
}

do {
    $studentId = generateStudentId();
    $checkId = $pdo->prepare("SELECT id FROM students WHERE student_id = ?");
    $checkId->execute([$studentId]);
} while ($checkId->fetch());

// ✅ Generate OTP
$otpCode = rand(100000, 999999);
$otpExpiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

// ✅ Save to student_otp_verification
$insert = $pdo->prepare("INSERT INTO student_otp_verification 
    (student_id, first_name, last_name, email, otp_code, otp_expiry) 
    VALUES (?, ?, ?, ?, ?, ?)");
$insert->execute([$studentId, $first, $last, $email, $otpCode, $otpExpiry]);

// ✅ Send OTP email using HTML template
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'utest6773@gmail.com';
    $mail->Password = 'bowxnxqgobiepopd';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('utest6773@gmail.com', 'Shree Classes');
    $mail->addAddress($email);
    $mail->Subject = '🔐 Verify your Email - Shree Classes';

    // ✅ Load and personalize the HTML OTP template
    $templatePath = '../templates/otp_email_template2.html';
    if (!file_exists($templatePath)) {
        throw new Exception('OTP email template not found.');
    }

    $emailBody = file_get_contents($templatePath);
    $emailBody = str_replace('{{first_name}}', htmlspecialchars($first), $emailBody);
    $emailBody = str_replace('{{otp_code}}', $otpCode, $emailBody);

    $mail->isHTML(true);
    $mail->Body = $emailBody;

    $mail->send();

    echo json_encode(['status' => 'success', 'redirect' => '../public/verify_otp.php']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Failed to send OTP.']);
}
