<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/db.php';

use Mpdf\Mpdf;

session_start();
if (!isset($_SESSION['contact_id'])) {
    die("❌ Unauthorized access.");
}

if (!isset($_GET['exam_id']) || !is_numeric($_GET['exam_id'])) {
    die("❌ Invalid exam ID.");
}

$examId = (int) $_GET['exam_id'];
$studentId = $_SESSION['contact_id'];

// Fetch student info
$stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
$stmt->execute([$studentId]);
$student = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$student) die("❌ Student record not found.");

// Fetch exam info
$stmt2 = $pdo->prepare("SELECT * FROM exams WHERE id = ?");
$stmt2->execute([$examId]);
$exam = $stmt2->fetch(PDO::FETCH_ASSOC);
if (!$exam) die("❌ Exam not found.");

// ✅ Fetch registered subjects (cross-reference exam_subjects with exam_registrations)
$stmt3 = $pdo->prepare("
    SELECT es.subject_name, es.subject_date
    FROM exam_subjects es
    INNER JOIN exam_registrations er
        ON es.exam_id = er.exam_id AND es.subject_name = er.subject_name
    WHERE es.exam_id = ? AND er.student_id = ?
    ORDER BY es.subject_date ASC
");
$stmt3->execute([$examId, $student['student_id']]); // Important: match on student_id field, not DB ID
$subjects = $stmt3->fetchAll(PDO::FETCH_ASSOC);

// Format values
$fullName = htmlspecialchars($student['first_name'] . ' ' . $student['last_name']);
$createdDate = date('F j, Y');
$address = implode(', ', array_filter([
    $student['mailing_street'] ?? '',
    $student['mailing_city'] ?? '',
    $student['mailing_state'] ?? '',
    $student['mailing_postal'] ?? '',
    $student['mailing_country'] ?? ''
]));

// Subject table
$subjectRows = '';
if (count($subjects) > 0) {
    foreach ($subjects as $sub) {
        $subjectName = htmlspecialchars($sub['subject_name']);
        $subjectDate = htmlspecialchars(date('d M Y', strtotime($sub['subject_date'])));
        $subjectRows .= "<tr><td>{$subjectName}</td><td>{$subjectDate}</td></tr>";
    }
} else {
    $subjectRows = "<tr><td colspan='2'>No subjects registered.</td></tr>";
}

// HTML structure with enhanced CSS
$html = "
<html>
<head>
    <title>Exam Slip - {$fullName}</title>
    <link href='https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Playfair+Display:wght@700&display=swap' rel='stylesheet'>
    <style>
        /* General Body Styling - Isolated to document context */
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f8f8; /* Light background for the overall page */
            color: #333;
            line-height: 1.6;
        }

        /* Main Container for the Exam Slip */
        .exam-slip-container {
            max-width: 850px;
            margin: 30px auto;
            background: #ffffff;
            border: 1px solid #e0e0e0;
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            overflow: hidden; /* Ensures border-radius applies to children */
        }

        /* Header Section */
        .header {
            background: linear-gradient(135deg, #6a1b9a, #8e24aa); /* Deep purple gradient */
            color: white;
            padding: 30px 40px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .header::before { /* Subtle background pattern */
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: radial-gradient(circle at 100% 150%, #7b1fa2, #6a1b9a 50%, #512da8 75%, #42129c 100%);
            opacity: 0.1;
            transform: scale(2);
            z-index: 0;
        }
        .header h2 {
            font-family: 'Playfair Display', serif;
            font-size: 2.8em;
            margin: 0;
            padding: 0;
            letter-spacing: 1px;
            position: relative;
            z-index: 1;
        }
        .header small {
            display: block;
            margin-top: 10px;
            font-size: 0.9em;
            opacity: 0.9;
            position: relative;
            z-index: 1;
        }

        /* Section Styling */
        .section {
            padding: 25px 40px;
            border-bottom: 1px dashed #e9e9e9; /* Subtle dashed separator */
        }
        .section:last-of-type {
            border-bottom: none; /* No border for the last section */
        }

        .section-title {
            font-family: 'Roboto', sans-serif;
            font-size: 1.5em;
            color: #4a148c; /* Darker purple for titles */
            margin-bottom: 15px;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-title::before { /* Decorative bullet */
            content: '•';
            color: #8e24aa;
            font-size: 1.2em;
            line-height: 1;
        }
        .section p {
            margin: 8px 0;
            font-size: 1em;
            color: #555;
        }
        .section p strong {
            color: #333;
            font-weight: 500;
        }

        /* General Table Styling (for .info-table and main subject table) */
        .info-table {
            width: 100%;
            border-collapse: separate; /* Allows border-radius on cells */
            border-spacing: 0; /* Remove space between cells */
            margin-top: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            border-radius: 8px; /* Rounded corners for the table */
            overflow: hidden; /* Ensures rounded corners are visible */
        }
        .info-table th, .info-table td {
            padding: 14px 20px;
            text-align: left;
            font-size: 0.95em;
            border-bottom: 1px solid #f0f0f0; /* Light line between rows */
            border-right: 1px solid #f0f0f0; /* Vertical separator */
        }
        .info-table th:last-child, .info-table td:last-child {
            border-right: none; /* No right border for last column */
        }
        .info-table th {
            background-color: #f5f5f5; /* Light grey header background */
            color: #4a148c; /* Dark purple for headers */
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-table tbody tr:nth-child(even) {
            background-color: #fdfdfd; /* Lighter background for even rows */
        }
        .info-table tbody tr:nth-child(odd) {
            background-color: #ffffff; /* White background for odd rows */
        }
        .info-table tbody tr:hover {
            background-color: #f0e6f7; /* Light purple on row hover */
        }
        .info-table th:first-child { border-top-left-radius: 8px; }
        .info-table th:last-child { border-top-right-radius: 8px; }
        .info-table tbody tr:last-child td:first-child { border-bottom-left-radius: 8px; border-bottom: none; }
        .info-table tbody tr:last-child td:last-child { border-bottom-right-radius: 8px; border-bottom: none; }

        /* Specific style for instruction section to avoid table wrapping for long text */
        .instructions-text {
            margin-top: 15px;
            font-size: 1em;
            color: #555;
            background-color: #f9f9f9;
            padding: 15px 20px;
            border-left: 4px solid #8e24aa;
            border-radius: 5px;
            line-height: 1.8;
        }


        /* Footer Section */
        .footer {
            background-color: #f5f5f5;
            padding: 20px 40px;
            text-align: center;
            font-size: 0.85em;
            color: #777;
            border-top: 1px solid #e0e0e0;
        }

        /* Print Specific Styles */
        @media print {
            body {
                background-color: #fff;
                padding: 0;
            }
            .exam-slip-container {
                box-shadow: none;
                border: none;
                margin: 0;
                border-radius: 0;
                width: 100%;
                max-width: none;
            }
            .header, .section, .footer {
                padding: 20px 30px; /* Adjust padding for print */
            }
            .header::before {
                display: none; /* Hide pattern on print */
            }
            .info-table {
                box-shadow: none;
                border-radius: 0;
            }
            .info-table th, .info-table td {
                border: 1px solid #ddd;
            }
        }
    </style>
</head>
<body>
<div class='exam-slip-container'>
    <div class='header'>
        <h2>Exam Slip</h2>
        <small>Generated on: {$createdDate}</small>
    </div>

    <div class='section'>
        <div class='section-title'>Student Information</div>
        <table class='info-table'>
            <tbody>
                <tr><td><strong>Name:</strong></td><td>{$fullName}</td></tr>
                <tr><td><strong>Student ID:</strong></td><td>{$student['student_id']}</td></tr>
                <tr><td><strong>Email:</strong></td><td>{$student['email']}</td></tr>
                <tr><td><strong>Address:</strong></td><td>{$address}</td></tr>
            </tbody>
        </table>
    </div>

    <div class='section'>
        <div class='section-title'>Exam Details</div>
        <table class='info-table'>
            <tbody>
                <tr><td><strong>Exam Title:</strong></td><td>{$exam['title']}</td></tr>
                <tr><td><strong>Grade:</strong></td><td>{$exam['grade']}</td></tr>
                <tr><td><strong>Exam Period:</strong></td><td>" . date('d M Y', strtotime($exam['start_date'])) . " to " . date('d M Y', strtotime($exam['end_date'])) . "</td></tr>
            </tbody>
        </table>
        <div class='section-title' style='margin-top: 25px;'>Instructions</div>
        <div class='instructions-text'>
            {$exam['instructions']}
        </div>
    </div>

    <div class='section'>
        <div class='section-title'>Subject Schedule</div>
        <table class='info-table'>
            <thead>
                <tr><th>Subject</th><th>Date</th></tr>
            </thead>
            <tbody>
                {$subjectRows}
            </tbody>
        </table>
    </div>

    <div class='footer'>
        &copy; 2025 Shree Classes. This slip is system generated and does not require signature.
    </div>
</div>
</body>
</html>
";


// Generate PDF
$mpdf = new Mpdf(['format' => 'A4']);
$mpdf->WriteHTML($html);
$mpdf->Output("exam_slip_{$examId}.pdf", 'I');
