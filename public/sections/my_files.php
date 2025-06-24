<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['contact_id'])) {
    echo "<script>window.location.href = '../index.html';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Files - Shree Classes</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom styles to ensure full screen and Inter font */
        :root {
            --primary-dark: #374151; /* A dark gray */
            --text-secondary: #6B7280; /* A slightly lighter gray */
        }
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6; /* Light gray background */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Align to top for better scrolling */
            min-height: 100vh; /* Ensure full viewport height */
            box-sizing: border-box;
        }
        /* Specific container styling to isolate and center the content */
        .my-files-container {
            max-width: 1200px; /* Max width for content */
            width: 100%;
            padding: 1.5rem; /* Consistent padding */
            margin-top: 2rem; /* Margin from top */
            margin-bottom: 2rem; /* Margin from bottom */
            background-color: #ffffff; /* White background for the main content area */
            border-radius: 1rem; /* Large rounded corners for the main container */
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* Soft shadow */
        }

        /* Override Tailwind's default scrollbar styles if necessary, but generally not needed */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
    <div class="my-files-container">
        <!-- Main header for the files section -->
        <div class="mb-6">
            <h2 class="text-3xl md:text-4xl font-extrabold text-gray-800 mb-2 leading-tight">
                📁 My Learning Materials
            </h2>
            <p class="text-lg text-gray-600">
                Dive into the learning resources shared with you by Shree Classes.
            </p>
        </div>

        <!-- Container for files, will be populated by JavaScript -->
        <div id="filesContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- File sections will be injected here -->
        </div>

        <!-- Message displayed when no files are available -->
        <div id="noFilesMessage" class="text-gray-500 text-center py-12 mt-8 border-t border-gray-200 rounded-lg bg-gray-50 hidden">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.5 14.25v-2.25H15V14.25m3-8.25-3-3m0 0L9 6v3h3V6.75M12 21.75V15.75M12 21.75a2.25 2.25 0 0 0-2.25-2.25M12 21.75a2.25 2.25 0 0 1-2.25-2.25m4.5 0a2.25 2.25 0 01-2.25 2.25m0 0a2.25 2.25 0 002.25 2.25m-2.25-2.25h-3.375c-.621 0-1.125-.504-1.125-1.125V11.25m11.25-1.5v4.5m-1.5-4.5H12a2.25 2.25 0 00-2.25 2.25v.75m6.75-4.5H15m0 0H5.625c-.621 0-1.125.504-1.125 1.125v13.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125Z" />
            </svg>
            <p class="mt-2 text-sm text-gray-500">No files shared with you yet.</p>
            <p class="text-sm text-gray-400">Please check back later for new materials.</p>
        </div>
    </div>

<script src="./js/my_files.js?ts=<?= time(); ?>"></script>
</body>
</html>
