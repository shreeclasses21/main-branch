<?php
require_once __DIR__ . '/../config/db.php';  // ← DB connection file
require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting (for debug)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Get today's month and day (ignore year)
$today = date('m-d');

try {
    // Fetch students with birthday today
   $stmt = $pdo->prepare("
    SELECT id, first_name, last_name, email, DATE_FORMAT(birthdate, '%m-%d') as dob 
    FROM students 
    WHERE DATE_FORMAT(birthdate, '%m-%d') = :today
");

    $stmt->execute(['today' => $today]);
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($students)) {
        echo json_encode(['status' => 'success', 'message' => 'No birthdays today.']);
        exit;
    }

    $templatePath = __DIR__ . '/../templates/birthday_email_template.html';

    foreach ($students as $student) {
        $email = $student['email'];
        $fullName = $student['first_name'] . ' ' . $student['last_name'];

        $mail = new PHPMailer(true);
        try {
            // SMTP Setup
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'utest6773@gmail.com';      // ✅ Your email
            $mail->Password = 'bowxnxqgobiepopd';         // ✅ App Password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            // Email Setup
            $mail->setFrom('utest6773@gmail.com', 'Shree Classes');
            $mail->addAddress($email, $fullName);
            $mail->Subject = "🎉 Happy Birthday, $fullName!";
            $mail->isHTML(true);

            // Load & personalize template
            $body = file_get_contents($templatePath);
            $body = str_replace('{{name}}', htmlspecialchars($fullName), $body);

            $mail->Body = $body;
            $mail->send();
        } catch (Exception $e) {
            echo json_encode(['status' => 'error', 'message' => "Mail Error for $email: " . $mail->ErrorInfo]);
        }
    }

    echo json_encode(['status' => 'success', 'message' => '🎂 Birthday emails sent.']);

} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'DB Error: ' . $e->getMessage()]);
}
