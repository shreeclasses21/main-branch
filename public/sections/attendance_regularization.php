<?php
session_start();
if (!isset($_SESSION['contact_id'])) {
    header("Location: ../index.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Regularization - Shree Classes</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Tailwind CSS Configuration
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'], // Use Inter font
                    },
                    colors: {
                        // Inherit color palette from previous pages for consistency
                        'primary-dark': '#3B0764',
                        'primary-light': '#6D28D9',
                        'background-light': '#F8FAFC',
                        'background-dark': '#E2E8F0',
                        'card-bg': '#FFFFFF',
                        'text-main': '#1F2937',
                        'text-secondary': '#6B7280',
                        'border-light': '#E5E7EB',
                        'shadow-light': 'rgba(0, 0, 0, 0.05)',
                        'accent-gradient-start': '#8B5CF6',
                        'accent-gradient-end': '#EC4899',
                        'header-bg': '#FFFFFF', /* White header background */
                        'sidebar-bg': '#1F2937', /* Dark sidebar background */
                        'sidebar-text-light': '#D1D5DB', /* Light text for sidebar */
                        'sidebar-hover': '#374151', /* Darker grey for sidebar hover */
                        'logout-red': '#EF4444', /* Red for logout button */
                        // Chart Colors (from previous context, if they were desired here too)
                        'chart-present-bg': '#6EE7B7', // Light Emerald Green
                        'chart-absent-bg': '#FCD34D',  // Amber
                        'chart-leave-bg': '#60A5FA',   // Light Blue
                        'chart-present-border': '#059669', // Darker Emerald Green
                        'chart-absent-border': '#D97706',  // Darker Amber
                        'chart-leave-border': '#2563EB',   // Darker Blue
                    },
                    boxShadow: {
                        'custom-light': '0 4px 6px -1px var(--tw-shadow-light), 0 2px 4px -1px var(--tw-shadow-light)',
                        'card-glow': '0 0 30px rgba(109, 40, 217, 0.15)',
                    }
                }
            }
        }
    </script>
    <!-- Inter font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Link to your dashboard.css if it contains global styles. Assuming dashboard.css is not critical for *this* page's layout itself, but for overall theme -->
    <!-- <link rel="stylesheet" href="css/dashboard.css"> -->
    <style>
        /* Basic body styling to ensure full height and background consistency */
        body {
            background-color: #F8FAFC; /* background-light */
            display: flex;
            justify-content: center;
            align-items: flex-start; /* Align to top, not center vertically */
            min-height: 100vh; /* Ensure full viewport height */
            font-family: 'Inter', sans-serif; /* Fallback font */
        }
        /* Fade-in animation for the main content block */
        @keyframes fade-in {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fade-in {
            animation: fade-in 0.8s ease-out forwards;
        }
    </style>
</head>
<body>
    <!-- Changed max-w-4xl to max-w-full for full width, adjusted horizontal padding -->
    <div class="w-full mx-auto my-8 px-4 sm:px-6 md:px-8 p-6 sm:p-8 bg-card-bg rounded-2xl shadow-xl border border-border-light animate-fade-in">
        <!-- Main Heading -->
        <h2 class="text-2xl sm:text-3xl font-extrabold mb-6 text-primary-dark flex items-center justify-center">
            <svg class="w-8 h-8 mr-3 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
            </svg>
            Attendance Regularization
        </h2>

        <!-- Calendar Placeholder -->
        <div class="mb-8 p-4 bg-background-light rounded-lg border border-border-light shadow-inner min-h-[250px] flex items-center justify-center">
            <div id="calendarContainer" class="w-full h-full text-center text-text-secondary text-lg">
                <p class="py-16">Your interactive calendar will load here.</p>
            </div>
        </div>

        <!-- Regularization Form -->
        <form id="regularizationForm" class="space-y-6 p-6 bg-purple-50 rounded-xl shadow-md border border-purple-200">
            <h3 class="text-xl font-bold text-purple-800 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                </svg>
                Submit New Request
            </h3>
            <div>
                <label for="reason" class="block text-sm font-semibold text-gray-700 mb-1">Reason for Regularization</label>
                <textarea id="reason" class="w-full border border-border-light p-3 rounded-lg focus:ring-2 focus:ring-primary-light focus:border-primary-light outline-none transition duration-200 shadow-sm" rows="4" required placeholder="E.g., Was sick, forgot to mark attendance, etc."></textarea>
            </div>
            <button type="submit" class="w-full py-3 px-6 rounded-xl text-white font-semibold shadow-lg
                                         bg-gradient-to-r from-accent-gradient-start to-accent-gradient-end
                                         hover:from-accent-gradient-end hover:to-accent-gradient-start
                                         transform hover:scale-105 transition-all duration-300 ease-in-out">
                Submit Request
            </button>
            <p id="regularizationMessage" class="text-sm mt-3 font-medium text-center"></p>
        </form>

        <!-- Divider -->
        <div class="flex items-center my-10">
            <div class="flex-grow border-t-2 border-dashed border-purple-300"></div>
            <span class="flex-shrink mx-4 text-purple-500 font-bold text-lg">HISTORY</span>
            <div class="flex-grow border-t-2 border-dashed border-purple-300"></div>
        </div>

        <!-- Request History -->
        <h3 class="text-xl font-bold text-primary-dark mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
            </svg>
            Your Regularization Requests
        </h3>
        <div id="requestHistory" class="overflow-x-auto p-4 bg-background-light rounded-lg border border-border-light shadow-inner min-h-[150px]">
            <p class="text-center text-text-secondary py-8">Your past regularization requests will appear here.</p>
            <!-- Your JavaScript from attendance_regularization.js will populate this div with request history data -->
        </div>
    </div>

    <!-- Script for attendance_regularization.js - ensure this path is correct relative to your HTML file -->
    <script src="./js/attendance_regularization.js"></script>
</body>
</html>
