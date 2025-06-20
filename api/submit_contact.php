<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

header('Content-Type: application/json');

// ✅ Input validation
$data = json_decode(file_get_contents('php://input'), true);
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$message = trim($data['message'] ?? '');

if (!$name || !$email || !$message) {
  http_response_code(400);
  echo json_encode(['status' => 'error', 'error' => 'All fields are required.']);
  exit;
}

// ✅ Store in DB
$stmt = $pdo->prepare("INSERT INTO contact_submissions (name, email, message) VALUES (?, ?, ?)");
$stmt->execute([$name, $email, $message]);

// ✅ Send Acknowledgment Email
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'utest6773@gmail.com';         // ✅ Your Gmail
    $mail->Password = 'bowxnxqgobiepopd';            // ✅ App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('utest6773@gmail.com', 'Shree Classes');
    $mail->addAddress($email, $name);
    $mail->Subject = '✅ Thank You for Contacting Shree Classes';

    // ✅ Load Template
    $template = file_get_contents(__DIR__ . '/../templates/contact_ack.html');
    $body = str_replace('{{NAME}}', htmlspecialchars($name), $template);

    $mail->isHTML(true);
    $mail->Body = $body;

    $mail->send();

    echo json_encode(['status' => 'success', 'message' => 'Thank you message sent.']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'error' => 'Mail Error: ' . $mail->ErrorInfo]);
}
