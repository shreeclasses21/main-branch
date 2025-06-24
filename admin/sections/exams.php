<?php
session_start();
// Redirect to login if admin is not authenticated
if (!isset($_SESSION['admin_id'])) {
    echo "<script>window.location.href = '../login.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Manage Exams</title>
    <!-- Tailwind CSS CDN - If you're using it, otherwise remove -->
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Isolated CSS for the Admin Exam Management Section -->
    <style>
        /* This wrapper defines the overall page layout for this component */
        body {
            margin: 0;
            padding: 0;
            background-color: #f0f2f5; /* Light grey background for the entire page */
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            font-family: 'Inter', sans-serif; /* Apply font globally to the body */
            color: #333;
        }

        /* Styles specific to the main admin content container */
        #adminExamManagementContainer.admin-panel-section {
            width: 100%;
            max-width: 960px; /* Max width for better readability on large screens */
            margin: 40px auto; /* Margin top/bottom and auto for centering */
            padding: 30px;
            background-color: #ffffff;
            border-radius: 18px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
            display: grid;
            gap: 30px; /* Space between the two main sections (Create Exam, Add Subjects) */
            box-sizing: border-box;
        }

        /* Styling for the individual card sections within the main container */
        .card-section {
            padding: 25px;
            background-color: #fdfdfd;
            border-radius: 14px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        /* Headings for the sections */
        .card-section h2 {
            font-size: 2.1rem;
            font-weight: 700;
            color: #4a148c; /* Darker purple */
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 3px solid #7b1fa2; /* Matching accent border */
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Sub-headings for existing exams */
        .card-section h3 {
            font-size: 1.6rem;
            font-weight: 600;
            color: #6a1b9a; /* A bit lighter purple */
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e0e0e0;
        }

        /* Form input fields and textareas */
        .form-input, .form-textarea, .form-select {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #d1d5db; /* Light grey border */
            border-radius: 10px; /* More rounded corners */
            font-size: 1rem;
            color: #374151;
            box-sizing: border-box; /* Ensures padding doesn't add to total width */
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-input:focus, .form-textarea:focus, .form-select:focus {
            border-color: #8b5cf6; /* Purple on focus */
            outline: none;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.25); /* Subtle glow on focus */
        }

        /* Buttons */
        .action-button {
            width: 100%;
            padding: 14px 20px;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #ffffff;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.2s ease;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            background: linear-gradient(135deg, #6d28d9, #9333ea); /* Purple gradient */
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.15);
            background: linear-gradient(135deg, #5e22c9, #832ece); /* Slightly darker gradient on hover */
        }

        .action-button:active {
            transform: translateY(0);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Status messages */
        .status-message {
            margin-top: 15px;
            padding: 12px;
            border-radius: 8px;
            font-weight: 500;
            text-align: center;
        }

        .status-message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        /* Divider */
        .section-divider {
            border: none;
            height: 1px;
            background-color: #e0e0e0;
            margin: 30px 0;
        }

        /* Existing exam list items */
        .existing-exam-item {
            padding: 18px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background-color: #f9fafb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            margin-bottom: 12px; /* Space between items */
        }

        .existing-exam-item:last-child {
            margin-bottom: 0; /* No bottom margin for the last item */
        }

        .existing-exam-item .title {
            font-weight: 700;
            color: #4a148c; /* Darker purple */
            font-size: 1.15rem;
            margin-bottom: 5px;
        }

        .existing-exam-item .details {
            font-size: 0.95rem;
            color: #555;
            line-height: 1.5;
        }

        .existing-exam-item .details strong {
            color: #333;
            font-weight: 600;
        }
        .existing-exam-item .details span.na-text {
            color: #999;
            font-style: italic;
        }

        /* Responsive grid for forms */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }
        @media (min-width: 768px) {
            .form-grid.md-cols-2 {
                grid-template-columns: 1fr 1fr;
            }
            .form-grid .col-span-2 {
                grid-column: span 2;
            }
        }
    </style>
</head>
<body>

<div id="adminExamManagementContainer" class="admin-panel-section">

    <!-- Section 1: Create New Exam -->
    <div class="card-section">
        <h2><span style="font-size: 1.2em;">✨</span> Create New Exam</h2>
        <form id="examForm" class="form-grid md-cols-2">
            <input type="text" id="examTitle" placeholder="Exam Title" class="form-input" required />

            <select id="examGrade" class="form-select" required>
                <option value="">Select Grade</option>
                <option value="8">8th Grade</option>
                <option value="9">9th Grade</option>
                <option value="10">10th Grade</option>
                <option value="11">11th Grade</option>
                <option value="12">12th Grade</option>
            </select>

            <input type="date" id="examStartDate" class="form-input" required />
            <input type="date" id="examEndDate" class="form-input" required />

            <textarea id="examDescription" class="form-textarea col-span-2" placeholder="Description (Optional)" rows="3"></textarea>
            <textarea id="examRules" class="form-textarea col-span-2" placeholder="Rules (Optional)" rows="3"></textarea>
            <textarea id="examInstructions" class="form-textarea col-span-2" placeholder="Instructions (Required)" rows="4" required></textarea>

            <button type="submit" class="action-button col-span-2">➕ Create Exam</button>
        </form>
        <div id="examCreationStatus" class="status-message"></div>
    </div>

    <hr class="section-divider" />

    <!-- Section 2: Existing Exams List -->
    <div class="card-section">
        <h3><span style="font-size: 1.1em;">📚</span> Existing Exams</h3>
        <div id="existingExamsList" class="space-y-3">
            <p style="text-align: center; color: #6b7280; padding: 15px;">Loading existing exams...</p>
        </div>
    </div>

    <hr class="section-divider" />

    <!-- Section 3: Add Subjects to Exam -->
    <div class="card-section">
        <h2><span style="font-size: 1.2em;">➕</span> Add Exam Subjects</h2>
        <form id="addSubjectsForm" class="form-grid md-cols-2">
            <select id="examDropdown" class="form-select" required>
                <option value="">Select Exam</option>
                <!-- Options populated by JS -->
            </select>

            <select id="subjectDropdown" class="form-select" required>
                <option value="">Select Subject</option>
                <!-- Options populated by JS -->
            </select>

            <input type="date" id="subjectDate" class="form-input" required />

            <button type="submit" class="action-button col-span-2">➕ Add Subject to Exam</button>
        </form>
        <div id="subjectAddStatus" class="status-message"></div>
    </div>

</div>

<script>
    window.currentSection = 'admin-exams'; // For any global navigation context
</script>
</body>
</html>
