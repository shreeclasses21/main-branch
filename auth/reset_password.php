<?php
session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $newPass = $_POST['new_password'] ?? '';
    $confirmPass = $_POST['confirm_password'] ?? '';

    if ($newPass !== $confirmPass) {
        echo json_encode(["status" => "error", "message" => "Passwords do not match."]);
        exit;
    }

    if (!isset($_SESSION['contact_id'])) {
        echo json_encode(["status" => "error", "message" => "Session expired. Please login again."]);
        exit;
    }

    $contactId = $_SESSION['contact_id'];

    try {
        // ✅ Hash the password securely
        $hashedPass = password_hash($newPass, PASSWORD_DEFAULT);

        // ✅ Update password in DB
        $stmt = $pdo->prepare("
            UPDATE students 
            SET password = :password, first_time_login = 0 
            WHERE id = :id
        ");
        $stmt->execute([
            'password' => $hashedPass,
            'id'       => $contactId
        ]);

        // ✅ Fetch student info
        $stmtEmail = $pdo->prepare("SELECT email, CONCAT(first_name, ' ', last_name) AS name FROM students WHERE id = :id LIMIT 1");
        $stmtEmail->execute(['id' => $contactId]);
        $row = $stmtEmail->fetch();
        $email = $row['email'] ?? '';
        $studentName = $row['name'] ?? 'Student';

        if ($email) {
            // ✅ Send confirmation email
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'utest6773@gmail.com';
            $mail->Password = 'bowxnxqgobiepopd'; // App password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('utest6773@gmail.com', 'Shree Classes');
            $mail->addAddress($email, $studentName);
            $mail->Subject = '🔐 Your Password Has Been Changed';
            $mail->isHTML(true);

            // Load & personalize HTML template
            $template = file_get_contents(__DIR__ . '/../templates/password_changed.html');
            $personalized = str_replace('{{name}}', htmlspecialchars($studentName), $template);
            $mail->Body = $personalized;

            $mail->send();
        }

        session_destroy();
        echo json_encode(["status" => "success", "message" => "✅ Password updated and email sent."]);
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => "Database error: " . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Email error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request method. Only POST allowed."]);
}
