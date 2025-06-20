<?php
session_start();
if (!isset($_SESSION['contact_id'])) {
  echo "<p class='text-red-500'>Session expired. Please log in again.</p>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Leave</title>
    <!-- Link Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Chart.js for the Doughnut charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Link to your custom stylesheet -->
    <link rel="stylesheet" href="./css/apply_leave.css">
</head>
<body class="bg-transparent">
    <!-- The main content area of your dashboard (where this file will be loaded) already handles the background and flex.
         This body tag here is essentially a placeholder for when this file is viewed standalone,
         or if loaded directly without the dashboard wrapper.
         It's set to transparent so it doesn't interfere with the dashboard's background. -->

    <!-- Loading Overlay - Visible by default, hidden by JS -->
    <div id="loadingOverlay" class="fixed inset-0 bg-gray-200 bg-opacity-75 flex items-center justify-center z-50">
        <div class="spinner-large"></div>
    </div>

    <!-- Reintroduced max-w-5xl and mx-auto to make it a centered, contained card -->
    <div class="bg-white p-6 md:p-10 lg:p-12 rounded-3xl shadow-2xl w-full max-w-5xl mx-auto transform transition-all duration-300 scale-95 md:scale-100 hover:scale-100">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-6 text-center tracking-tight">
            Apply for <span class="text-purple-600">Leave</span>
        </h1>
        <p class="text-center text-gray-600 mb-10 text-lg">Your well-being matters. Request time off effortlessly.</p>

        <!-- TWO CHARTS SIDE BY SIDE -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 justify-items-center mb-12">
            <!-- Sick Leave Chart -->
            <div class="flex flex-col items-center bg-purple-50 p-6 rounded-2xl shadow-md w-full border border-purple-200 transform hover:scale-105 transition-transform duration-200">
                <h2 class="text-xl font-semibold text-purple-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-purple-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-7-9a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V9z" clip-rule="evenodd"></path>
                    </svg>
                    Sick Leave
                </h2>
                <div class="w-48 h-48 mb-4">
                    <canvas id="chart-sick"></canvas>
                </div>
                <p class="text-lg font-medium text-gray-700">
                    <span id="sick-used" class="text-purple-700 font-bold text-2xl">0</span> /
                    <span id="sick-allowed" class="text-gray-500 text-lg">0</span> days Used
                </p>
            </div>

            <!-- Vacations Chart -->
            <div class="flex flex-col items-center bg-blue-50 p-6 rounded-2xl shadow-md w-full border border-blue-200 transform hover:scale-105 transition-transform duration-200">
                <h2 class="text-xl font-semibold text-blue-800 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 12h6m-6 4h6m2-12v6a2 2 0 01-2 2H5a2 2 0 01-2-2V4a2 2 0 012-2h7a2 2 0 012 2z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    Vacations
                </h2>
                <div class="w-48 h-48 mb-4">
                    <canvas id="chart-vacation"></canvas>
                </div>
                <p class="text-lg font-medium text-gray-700">
                    <span id="vac-used" class="text-blue-700 font-bold text-2xl">0</span> /
                    <span id="vac-allowed" class="text-gray-500 text-lg">0</span> days Used
                </p>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-8 mt-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Your Leave Request</h2>
            <form id="leaveForm" class="space-y-6">
                <!-- Leave Type -->
                <div>
                    <label for="leave_type" class="block text-sm font-medium text-gray-700 mb-1">Leave Type</label>
                    <select id="leave_type" name="Leave_Type__c" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 p-3 bg-white"></select>
                </div>

                <!-- From Date -->
                <div>
                    <label for="from_date" class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                    <input id="from_date" type="date" name="From_Date__c" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 p-3"/>
                    <div id="dateError" class="text-red-600 text-sm mt-2 font-medium"></div>
                </div>

                <!-- To Date -->
                <div>
                    <label for="to_date" class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                    <input id="to_date" type="date" name="To_Date__c" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 p-3"/>
                </div>

                <!-- Total Days -->
                <div>
                    <label for="leave_days" class="block text-sm font-medium text-gray-700 mb-1">Total Days</label>
                    <input type="text" id="leave_days" readonly class="w-full border border-gray-300 bg-gray-50 rounded-lg shadow-sm p-3 cursor-not-allowed text-gray-700 font-semibold"/>
                </div>

                <!-- Reason -->
                <div>
                    <label for="reason" class="block text-sm font-medium text-gray-700 mb-1">Reason</label>
                    <textarea id="reason" name="Reason__c" rows="4" class="w-full border border-gray-300 rounded-lg shadow-sm focus:ring-purple-500 focus:border-purple-500 p-3 resize-y bg-white"></textarea>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full bg-purple-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition duration-300 transform hover:scale-105 btn-glow flex items-center justify-center">
                    <span id="submitButtonText">Submit Leave Request</span>
                    <div id="submitSpinner" class="spinner ml-3 hidden"></div>
                </button>
            </form>
        </div>
    </div>

    <!-- Message Modal Structure -->
    <div id="messageModal" class="modal hidden fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center p-4 z-50 backdrop-blur-sm">
        <div class="modal-content bg-white rounded-xl shadow-2xl overflow-hidden w-full max-w-sm mx-auto transform transition-transform duration-300 ease-in-out border-t-4 border-purple-500">
            <div class="modal-header px-6 py-4 flex items-center justify-between text-white" id="modalHeader">
                <h3 id="modalTitle" class="text-lg font-bold flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Information
                </h3>
                <button onclick="closeModal()" class="text-white text-2xl leading-none hover:text-gray-200 focus:outline-none">&times;</button>
            </div>
            <div class="p-6">
                <p id="modalBody" class="text-gray-700 text-base leading-relaxed"></p>
            </div>
            <div class="px-6 py-4 bg-gray-50 text-right border-t border-gray-200">
                <button onclick="closeModal()" class="bg-purple-600 text-white px-5 py-2 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition duration-200">
                    Got it!
                </button>
            </div>
        </div>
    </div>

    <script src="public/js/apply_leave.js"></script>
    <script>
        // Initialize the form after the DOM is loaded
        document.addEventListener("DOMContentLoaded", () => {
            initApplyLeaveForm();
        });
    </script>
</body>
</html>
