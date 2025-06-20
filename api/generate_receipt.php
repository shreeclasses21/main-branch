<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';

use Mpdf\Mpdf;

session_start();
if (!isset($_SESSION['contact_id'])) {
    die("❌ Unauthorized access.");
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("❌ Invalid invoice ID.");
}

$invoiceId = (int) $_GET['id'];
$studentId = $_SESSION['contact_id'];

// Fetch invoice
$stmt = $pdo->prepare("SELECT * FROM payment_invoices WHERE id = ? AND student_id = ?");
$stmt->execute([$invoiceId, $studentId]);
$invoice = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$invoice) die("❌ Invoice not found.");

// Fetch student
$stmt2 = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt2->execute([$studentId]);
$student = $stmt2->fetch(PDO::FETCH_ASSOC);
if (!$student) die("❌ Student record not found.");

// Format data
$fullName = htmlspecialchars($student['first_name'] . ' ' . $student['last_name']);
$address = implode(', ', array_filter([
    $student['mailing_street'],
    $student['mailing_city'],
    $student['mailing_state'],
    $student['mailing_postal'],
    $student['mailing_country']
]));
$createdDate = date('F j, Y', strtotime($invoice['created_at']));
$amountFormatted = '₹' . number_format($invoice['amount'], 2);

$html = "
<html>
<head>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #f4f4f5;
        margin: 0;
        color: #333;
    }
    .invoice-box {
        max-width: 800px;
        margin: 30px auto;
        border: 1px solid #e0e0e0;
        border-radius: 12px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        overflow: hidden;
        background-color: #fff;
    }
    .header {
        background: linear-gradient(to right, #6a11cb, #2575fc);
        color: #fff;
        padding: 30px;
        text-align: center;
        border-radius: 12px 12px 0 0;
    }
    .header h1 {
        font-size: 28px;
        margin: 0;
    }
    .header small {
        font-size: 14px;
        margin-top: 8px;
        display: block;
    }
    .section {
        padding: 25px 30px;
        border-bottom: 1px solid #f1f1f1;
    }
    .section-title {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 18px;
        color: #4c1d95;
        border-bottom: 1px solid #ddd;
        padding-bottom: 6px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }
    table td {
        padding: 8px 10px;
        vertical-align: top;
        font-size: 15px;
    }
    table td.label {
        font-weight: bold;
        color: #4c1d95;
        width: 30%;
    }
    table.invoice-summary {
        border: 1px solid #ddd;
        margin-top: 20px;
    }
    table.invoice-summary td {
        border: 1px solid #ddd;
        text-align: center;
        font-size: 14px;
        padding: 10px;
    }
    .amount-box {
        background: #ede9fe;
        color: #4c1d95;
        text-align: right;
        font-size: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        font-weight: bold;
        margin-top: 20px;
    }
    .remarks {
        font-size: 14px;
        line-height: 1.6;
        color: #555;
    }
    .footer {
        padding: 20px;
        font-size: 12px;
        text-align: center;
        background-color: #f9fafb;
        color: #777;
    }
</style>
</head>
<body>
<div class='invoice-box'>

    <div class='header'>
        <h1>Shree Classes</h1>
        <small>Receipt ID: {$invoice['id']} | Date: {$createdDate}</small>
    </div>

    <div class='section'>
        <div class='section-title'>Student Information</div>
        <table>
            <tr><td class='label'>Name</td><td>{$fullName}</td></tr>
            <tr><td class='label'>Student ID</td><td>{$student['student_id']}</td></tr>
            <tr><td class='label'>Email</td><td>{$student['email']}</td></tr>
            <tr><td class='label'>Address</td><td>{$address}</td></tr>
        </table>
    </div>

    <div class='section'>
        <div class='section-title'>Invoice Summary</div>
        <table class='invoice-summary'>
            <tr style='background-color: #f3f4f6; font-weight: bold;'>
                <td>Month</td>
                <td>Status</td>
                <td>Method</td>
                <td>Paid On</td>
                <td>Amount</td>
            </tr>
            <tr>
                <td>{$invoice['month']} {$invoice['year']}</td>
                <td>" . ucfirst($invoice['status']) . "</td>
                <td>{$invoice['type']}</td>
                <td>{$createdDate}</td>
                <td>{$amountFormatted}</td>
            </tr>
        </table>

        <div class='amount-box'>Total Paid: {$amountFormatted}</div>
    </div>

    <div class='section'>
        <div class='section-title'>Remarks</div>
        <p class='remarks'>
            Thank you for your payment! This is a digitally generated receipt issued by Shree Classes.
            If you have any questions, please contact <strong>support@shreeclasses.com</strong>.
        </p>
    </div>

    <div class='footer'>
        &copy; 2025 Shree Classes • All rights reserved. This receipt is digitally generated and does not require a signature.
    </div>
</div>
</body>
</html>
";



// Output PDF
$mpdf = new Mpdf(['format' => 'A4']);
$mpdf->WriteHTML($html);
$mpdf->Output("receipt_{$invoice['id']}.pdf", 'I');