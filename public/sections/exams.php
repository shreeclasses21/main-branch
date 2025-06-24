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
        /*
           Optional: A general page wrapper. This div's styles (if any)
           would come from your main site's CSS, not specific to this component.
           For demonstration, it still helps align the content.
        */
        .main-content-wrapper {
            width: 100%; /* Make it take full width of its parent */
            padding: 20px; /* Add some padding around the content */
            box-sizing: border-box; /* Include padding in width calculation */
            display: flex; /* Use flexbox to center content if needed */
            justify-content: center; /* Center horizontally if content is less than 100% */
            margin: 0 auto; /* Center the wrapper itself */
            /* Add any global page background/font here if desired, but NOT on body directly */
            background-color: #f3f4f6; /* Example: light gray background for the wrapper */
            min-height: 100vh; /* Ensure it takes full viewport height if you want it to push footer down */
            font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #333;
        }

        /* Styles specifically for the 'Upcoming Exams' section */
        #upcomingExamsSection.exams-container {
            width: 100%; /* Make this inner section take the full width of its parent (.main-content-wrapper) */
            max-width: 900px; /* Optional: Constrain max width for better readability on very wide screens */
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            padding: 30px;
            box-sizing: border-box; /* Crucial for padding not to expand total width */
        }

        /* Styles for the main heading within the section */
        #upcomingExamsSection h2 {
            font-size: 2.2rem;
            color: #6a0572; /* Deeper purple */
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 3px solid #8e24aa; /* Accent border */
            display: flex;
            align-items: center;
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