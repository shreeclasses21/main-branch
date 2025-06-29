<?php
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../PHPMailer-master/src/Exception.php';
require_once __DIR__ . '/../PHPMailer-master/src/PHPMailer.php';
require_once __DIR__ . '/../PHPMailer-master/src/SMTP.php';

use Mpdf\Mpdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 🔎 Fetch pending leave requests with student info
function fetchPendingLeaveRequests($pdo) {
    $stmt = $pdo->prepare("
        SELECT lr.*, s.first_name, s.last_name
        FROM leave_requests lr
        JOIN students s ON lr.student_id = s.student_id
        WHERE lr.status = 'Pending'
        ORDER BY lr.from_date
    ");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 📄 Generate PDF (per student)
function generateLeavePDF($data, $title, $savePath) {
    $mpdf = new Mpdf(['format' => 'A4']);
    $mpdf->SetTitle($title);

    // Group by student
    $grouped = [];
    foreach ($data as $row) {
        $key = $row['student_id'];
        $grouped[$key][] = $row;
    }

    $isFirst = true;

    foreach ($grouped as $studentId => $records) {
        $studentName = htmlspecialchars($records[0]['first_name'] . ' ' . $records[0]['last_name']);

        if (!$isFirst) $mpdf->AddPage();
        $isFirst = false;

        $html = "
        <html><head><style>
        body { font-family: Arial; font-size: 12px; }
        h2 { text-align: center; color: #1e3a8a; }
        .info { font-size: 14px; margin-top: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
        </style></head><body>
        <h2>$title</h2>
        <div class='info'><strong>Student ID:</strong> $studentId<br>
        <strong>Name:</strong> $studentName</div>
        <table>
        <tr>
            <th>From</th>
            <th>To</th>
            <th>Reason</th>
            <th>Document</th>
        </tr>";

        foreach ($records as $rec) {
            $doc = $rec['supporting_document'] ? "Yes" : "No";
            $html .= "<tr>
                <td>{$rec['from_date']}</td>
                <td>{$rec['to_date']}</td>
                <td>{$rec['reason']}</td>
                <td>$doc</td>
            </tr>";
        }

        $html .= "</table></body></html>";

        $mpdf->WriteHTML($html);
    }

    $fileName = 'pending_leave_requests_' . date('Ymd_His') . '.pdf';
    $fullPath = $savePath . $fileName;
    $mpdf->Output($fullPath, 'F');

    return [
        'filename' => $fileName,
        'path' => $fullPath,
        'content' => $mpdf->Output('', 'S')
    ];
}

// 📁 Create directory if missing
$savePath = __DIR__ . '/../reports/leaves/';
if (!is_dir($savePath)) {
    mkdir($savePath, 0777, true);
}

// 📊 Generate report
$leaveData = fetchPendingLeaveRequests($pdo);
if (count($leaveData) === 0) {
    echo "✅ No pending leave requests.";
    exit;
}

$report = generateLeavePDF($leaveData, "Pending Leave Requests Report", $savePath);

// 📧 Send email
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
    $mail->addAddress('shreeclasses21@gmail.com');
    $mail->Subject = '📌 Pending Leave Requests Report';
    $mail->isHTML(true);
    $mail->Body = "
        Hi Admin,<br><br>
        Please find attached a summary of all <b>Pending</b> leave requests submitted by students.<br><br>
        Kindly review and take necessary action.<br><br>
        <b>Note:</b> This is an automated daily notification.<br><br>
        Regards,<br>
        <b>Shree Classes Bot</b>
    ";

    $mail->addStringAttachment($report['content'], $report['filename']);
    $mail->send();

    echo "✅ Leave request report sent successfully.<br>";
    echo "🗂 Saved: " . $report['filename'];

} catch (Exception $e) {
    echo "❌ Failed to send email: " . $mail->ErrorInfo;
}
