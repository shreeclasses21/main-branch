<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

use Mpdf\Mpdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 📌 Fetch attendance data for last N days
function fetchAttendanceData($pdo, $daysBack) {
    $fromDate = date('Y-m-d', strtotime("-$daysBack days"));
    $stmt = $pdo->prepare("
        SELECT al.*, s.first_name, s.last_name
        FROM attendance_log al
        JOIN students s ON al.student_id = s.student_id
        WHERE al.attendance_date >= :fromDate
        ORDER BY al.attendance_date DESC
    ");
    $stmt->execute(['fromDate' => $fromDate]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 🧾 Generate per-student PDF (1 page per student)
function generatePDF($data, $title, $savePath) {
    $mpdf = new Mpdf(['format' => 'A4']);
    $mpdf->SetTitle($title);

    // 🧠 Group by student
    $grouped = [];
    foreach ($data as $row) {
        $key = $row['student_id'];
        $grouped[$key][] = $row;
    }

    $isFirst = true;

    foreach ($grouped as $studentId => $records) {
        $studentName = htmlspecialchars($records[0]['first_name'] . ' ' . $records[0]['last_name']);

        if (!$isFirst) {
            $mpdf->AddPage(); // New page
        }
        $isFirst = false;

        $html = "
        <html><head><style>
            body { font-family: Arial; font-size: 12px; }
            h2 { text-align: center; color: #4c1d95; }
            .info { margin-top: 10px; font-size: 14px; }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
            th { background-color: #eee; }
        </style></head><body>
        <h2>$title</h2>
        <div class='info'><strong>Student ID:</strong> $studentId<br>
        <strong>Name:</strong> $studentName</div>
        <table>
            <tr>
                <th>Date</th>
                <th>Status</th>
            </tr>";

        foreach ($records as $rec) {
            $html .= "<tr>
                <td>{$rec['attendance_date']}</td>
                <td>{$rec['type']}</td>
            </tr>";
        }

        $html .= "</table></body></html>";

        $mpdf->WriteHTML($html);
    }

    $fileName = strtolower(str_replace(' ', '_', $title)) . '_' . date('Ymd_His') . '.pdf';
    $fullPath = $savePath . $fileName;
    $mpdf->Output($fullPath, 'F'); // Save to disk

    return [
        'filename' => $fileName,
        'path' => $fullPath,
        'content' => $mpdf->Output('', 'S') // for email
    ];
}

// ✅ Setup save location
$reportPath = __DIR__ . '/../reports/attendance/';
if (!is_dir($reportPath)) {
    mkdir($reportPath, 0777, true);
}

// 📊 Generate both reports
$weeklyData = fetchAttendanceData($pdo, 7);
$monthlyData = fetchAttendanceData($pdo, 30);
$weeklyReport = generatePDF($weeklyData, "Weekly Attendance Report", $reportPath);
$monthlyReport = generatePDF($monthlyData, "Monthly Attendance Report", $reportPath);

// 📧 Email with PHPMailer
$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'utest6773@gmail.com'; // ✅ Replace with yours
    $mail->Password = 'bowxnxqgobiepopd';    // ✅ App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('utest6773@gmail.com', 'Shree Classes');
    $mail->addAddress('shreeclasses21@gmail.com');
    $mail->Subject = '📊 Weekly & Monthly Attendance Logs';
    $mail->isHTML(true);
    $mail->Body = "
        Hi Admin,<br><br>
        Please find the attached attendance reports:<br>
        <ul>
            <li><b>Weekly:</b> Last 7 days</li>
            <li><b>Monthly:</b> Last 30 days</li>
        </ul>
        <br>Reports also saved in: <code>shreeclasses-api/reports/attendance/</code><br><br>
        Regards,<br><b>Shree Classes Bot</b>
    ";

    $mail->addStringAttachment($weeklyReport['content'], $weeklyReport['filename']);
    $mail->addStringAttachment($monthlyReport['content'], $monthlyReport['filename']);
    $mail->send();

    echo "✅ Email sent successfully with reports.<br>";
    echo "🗂 Saved to: reports/attendance/<br>";
    echo "- " . $weeklyReport['filename'] . "<br>";
    echo "- " . $monthlyReport['filename'] . "<br>";

} catch (Exception $e) {
    echo "❌ Failed to send email: " . $mail->ErrorInfo;
}
