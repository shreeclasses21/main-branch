<?php
session_start();
if (!isset($_SESSION['student_id'])) {
    header("Location: ../index.html");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upcoming Exams</title>
    <style>
        /* Base styles for better responsiveness */
        :root {
            font-size: 16px; /* Base font size for rem units */
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #333;
            background-color: #f3f4f6; /* Example: light gray background for the wrapper */
            min-height: 100vh;
            line-height: 1.6;
        }

        /* General page wrapper */
        .main-content-wrapper {
            width: 100%;
            padding: 1rem; /* Use rem for responsive padding */
            box-sizing: border-box;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Align to the top, not center vertically */
            margin: 0 auto;
        }

        /* Styles specifically for the 'Upcoming Exams' section */
        #upcomingExamsSection.exams-container {
            width: 100%;
            max-width: 900px; /* Constrain max width for readability on wide screens */
            border-radius: 1rem; /* Use rem for border-radius */
            box-shadow: 0 0.625rem 1.5625rem rgba(0, 0, 0, 0.1); /* Use rem for shadow */
            background-color: #ffffff;
            padding: 1.5rem; /* Use rem for padding */
            box-sizing: border-box;
        }

        /* Styles for the main heading within the section */
        #upcomingExamsSection h2 {
            font-size: 1.8rem; /* Adjusted for better mobile scaling */
            color: #6a0572; /* Deeper purple */
            margin-bottom: 1.25rem;
            padding-bottom: 0.75rem;
            border-bottom: 0.1875rem solid #8e24aa; /* Use rem for border thickness */
            display: flex;
            align-items: center;
            flex-wrap: wrap; /* Allow wrapping for long titles on small screens */
            gap: 0.5rem; /* Space between icon and text */
        }

        /* Styles for individual exam cards */
        .exam-card {
            width: 100%;
            box-sizing: border-box;
            border: 0.0625rem solid #e0e0e0;
            border-radius: 0.75rem;
            box-shadow: 0 0.25rem 0.75rem rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            background-color: #ffffff;
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
            margin-bottom: 1.5rem;
            overflow: hidden; /* Ensures shadows and transforms don't cause scroll issues */
        }

        .exam-card:hover {
            transform: translateY(-0.4375rem); /* Use rem */
            box-shadow: 0 0.9375rem 1.5625rem rgba(0, 0, 0, 0.15); /* Use rem */
        }

        .exam-card h3 {
            color: #6a0572;
            margin-bottom: 0.625rem;
            font-size: 1.5rem; /* Adjusted for better mobile scaling */
            padding-bottom: 0.5rem;
            border-bottom: 0.0625rem dashed #e0e0e0;
        }

        .exam-card h3 span {
            font-size: 0.6em; /* Relative to parent h3 */
        }

        .exam-card p {
            color: #555;
            margin-top: 0.5rem;
            line-height: 1.6;
            font-size: 0.95rem; /* Slightly smaller for mobile */
        }

        .exam-card p strong {
            color: #333;
        }

        .exam-card .mt-4 { /* For the div containing "Select Subjects" */
            margin-top: 1.5rem;
            padding-top: 1.25rem;
            border-top: 0.0625rem solid #f0f0f0;
        }

        .exam-card label.block { /* "Select Subjects" label */
            color: #444;
            margin-bottom: 0.75rem;
            font-size: 1.05rem; /* Slightly adjusted */
        }

        /* Subject container grid */
        .exam-card .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); /* Adjusted minmax for smaller screens */
            gap: 0.625rem; /* Use rem for gap */
            color: #333;
            padding: 0.3125rem 0;
        }

        /* Subject checkbox label styling */
        .exam-card .grid label {
            display: flex;
            align-items: center;
            gap: 0.625rem;
            cursor: pointer;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            background-color: #f8f8f8;
            border: 0.0625rem solid #e0e0e0;
            transition: background-color 0.2s, border-color 0.2s;
            white-space: nowrap;
            font-size: 0.9rem; /* Smaller font for subjects on mobile */
        }

        .exam-card .grid label[title="Already registered, cannot change subjects"] {
            cursor: not-allowed;
        }

        .exam-card .grid label input[type="checkbox"] {
            height: 1.25rem; /* Use rem */
            width: 1.25rem; /* Use rem */
            border-radius: 0.25rem;
            cursor: pointer;
            transition: background-color 0.2s, border-color 0.2s;
        }

        .exam-card .grid label input[type="checkbox"]:disabled {
            cursor: not-allowed;
            opacity: 0.6;
        }

        /* Buttons container */
        .exam-card .flex {
            display: flex;
            flex-wrap: wrap; /* Allow buttons to wrap to next line on small screens */
            align-items: center;
            gap: 1.25rem; /* Use rem for gap */
            margin-top: 1.875rem;
            justify-content: flex-start;
        }

        .exam-card button {
            background-color: #673ab7;
            color: #ffffff;
            padding: 0.75rem 1.5rem; /* Use rem */
            border-radius: 0.5rem;
            border: none;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: background-color 0.2s ease-in-out, transform 0.1s ease-in-out;
            box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
            flex-grow: 1; /* Allow buttons to grow and fill space */
            min-width: 150px; /* Ensure buttons don't get too small */
            box-sizing: border-box; /* Crucial for padding/border not to expand total width when flex-grow is used */
        }

        .exam-card button:hover {
            background-color: #5e35b1;
            transform: translateY(-0.125rem); /* Use rem */
        }

        .exam-card button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .exam-card #downloadBtn {
            background-color: #9c27b0;
        }

        .exam-card #downloadBtn:hover {
            background-color: #8e24aa;
        }

        .exam-card .mt-4.text-sm.font-semibold { /* Exam Status Box */
            margin-top: 1.25rem;
            font-size: 0.9rem;
            padding: 0.625rem 0;
        }

        /* Specific styles for messages (loading, error, success) */
        p[style*="text-align: center"],
        .text-red-600,
        .text-green-600 {
            padding: 0.625rem 1rem;
            border-radius: 0.5rem;
            text-align: center;
            font-size: 0.95rem;
            margin: 0.5rem 0; /* Add some vertical margin */
        }

        .text-red-600 {
            color: #dc2626;
            background-color: #fef2f2;
            border: 0.0625rem solid #fee2e2;
        }

        .text-green-600 {
            color: #28a745;
            background-color: #d4edda;
            border: 0.0625rem solid #c3e6cb;
        }

        p[style*="color: #555; text-align: center;"] { /* for "Submitting registration..." */
            background-color: #e0f7fa; /* light blue */
            border: 0.0625rem solid #b2ebf2;
            color: #00838f !important;
        }

        /* Media Queries for fine-tuning on smaller screens */
        @media (max-width: 768px) {
            #upcomingExamsSection.exams-container {
                padding: 1rem; /* Reduce padding on smaller screens */
            }

            .exam-card {
                padding: 1rem; /* Reduce card padding */
            }

            #upcomingExamsSection h2 {
                font-size: 1.5rem; /* Smaller heading on very small screens */
                justify-content: center; /* Center the heading on small screens */
            }
             .exam-card h3 {
                font-size: 1.3rem; /* Smaller card title */
                text-align: center; /* Center card title */
            }

            .exam-card h3 span {
                display: block; /* Grade on new line if too long */
            }

            .exam-card .grid {
                grid-template-columns: 1fr; /* Stack subjects vertically on very small screens */
                gap: 0.5rem;
            }

            .exam-card label.block {
                text-align: center;
                margin-bottom: 0.5rem;
            }

            .exam-card .flex {
                flex-direction: column; /* Stack buttons vertically */
                align-items: stretch; /* Stretch buttons to full width */
                gap: 0.75rem;
            }

            .exam-card button {
                width: 100%; /* Make buttons full width */
            }
        }

        @media (max-width: 480px) {
            :root {
                font-size: 14px; /* Even smaller base font size for tiny screens */
            }

            .main-content-wrapper {
                padding: 0.5rem; /* Minimal padding on very small screens */
            }

            #upcomingExamsSection.exams-container {
                padding: 0.75rem;
            }

            .exam-card {
                padding: 0.75rem;
            }

            #upcomingExamsSection h2 {
                font-size: 1.3rem;
            }

            .exam-card h3 {
                font-size: 1.1rem;
            }

            .exam-card p, .exam-card label.block, .exam-card .grid label, .exam-card button, .exam-card .mt-4.text-sm.font-semibold {
                font-size: 0.85rem; /* Consistent smaller font for content */
            }
            p[style*="text-align: center"],
            .text-red-600,
            .text-green-600 {
                font-size: 0.85rem;
            }
        }
    </style>
</head>
<body>

<div class="main-content-wrapper">
    <div id="upcomingExamsSection" class="p-6 bg-white rounded-xl shadow-md exams-container">
        <h2 class="text-2xl font-bold text-purple-700 mb-4 flex items-center gap-2">
            🎓 Upcoming Exams
        </h2>

        <div id="examList" class="space-y-6">
            <p style="text-align: center; color: #6b7280; font-size: 1.1rem; padding: 20px;">Loading exams...</p>
        </div>
    </div>
</div>

<script>
    window.currentSection = 'exams'; // Keep this if used elsewhere
</script>
<script src="./js/exam_register.js"></script>

</body>
</html>