<?php
session_start();
if (!isset($_SESSION['contact_id'])) {
  echo "<p class='text-red-500'>Please log in to view leave requests.</p>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Leave Requests</title>
    <!-- Link Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Link to your custom stylesheet -->
    <link rel="stylesheet" href="./css/view_leaves.css">
</head>
<body class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 min-h-screen flex items-center justify-center p-4">

    <!-- Loading Overlay - Visible by default, hidden by JS -->
    <div id="loadingOverlay" class="fixed inset-0 bg-gray-200 bg-opacity-75 flex items-center justify-center z-50">
        <div class="spinner-large"></div>
    </div>

    <div class="bg-white p-6 md:p-10 lg:p-12 rounded-3xl shadow-2xl w-full max-w-5xl mx-auto transform transition-all duration-300 scale-95 md:scale-100 hover:scale-100">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-6 text-center tracking-tight">
            Your <span class="text-purple-600">Leave Requests</span>
        </h1>
        <p class="text-center text-gray-600 mb-10 text-lg">Manage and view the status of your submitted leave applications.</p>

        <div class="overflow-x-auto rounded-xl border border-gray-200 shadow-sm">
            <table id="leavesTable" class="min-w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-purple-100 text-purple-800 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left rounded-tl-xl">From</th>
                        <th class="py-3 px-6 text-left">To</th>
                        <th class="py-3 px-6 text-left">Reason</th>
                        <th class="py-3 px-6 text-left">Status</th>
                        <th class="py-3 px-6 text-left">Requested On</th>
                        <th class="py-3 px-6 text-center rounded-tr-xl">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700 text-sm font-light">
                    <!-- Data will be injected by JavaScript -->
                </tbody>
            </table>
        </div>
        <div id="viewLeavesError" class="mt-6 text-red-600 font-medium text-center"></div>
    </div>

    <!-- Message Modal Structure (consistent with apply_leave.html) -->
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

    <!-- Confirmation Modal Structure -->
    <div id="confirmModal" class="modal hidden fixed inset-0 bg-gray-900 bg-opacity-70 flex items-center justify-center p-4 z-50 backdrop-blur-sm">
        <div class="modal-content bg-white rounded-xl shadow-2xl overflow-hidden w-full max-w-sm mx-auto transform transition-transform duration-300 ease-in-out border-t-4 border-red-500">
            <div class="modal-header px-6 py-4 flex items-center justify-between bg-red-500 text-white">
                <h3 class="text-lg font-bold flex items-center">
                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    Confirm Action
                </h3>
                <button onclick="closeConfirmModal(false)" class="text-white text-2xl leading-none hover:text-gray-200 focus:outline-none">&times;</button>
            </div>
            <div class="p-6">
                <p id="confirmModalBody" class="text-gray-700 text-base leading-relaxed">Are you sure you want to proceed?</p>
            </div>
            <div class="px-6 py-4 bg-gray-50 text-right border-t border-gray-200 space-x-3">
                <button onclick="closeConfirmModal(false)" class="bg-gray-300 text-gray-800 px-5 py-2 rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2 transition duration-200">
                    Cancel
                </button>
                <button onclick="confirmAction()" class="bg-red-600 text-white px-5 py-2 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200">
                    Confirm
                </button>
            </div>
        </div>
    </div>


    <script src="public/js/view_leaves.js"></script>
    <script>
        // Initialize the view leaves page after the DOM is loaded
        document.addEventListener("DOMContentLoaded", () => {
            initViewLeaves();
        });
    </script>
</body>
</html>
