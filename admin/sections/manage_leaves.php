<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Leave Requests</title>
    <!-- Tailwind CSS CDN - Included for standalone preview -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter for a modern, professional look - Included for standalone preview -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* Ensures the Inter font is applied globally */
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Custom scrollbar for better aesthetics */
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #e0e0e0; /* Light gray track */
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: #8b5cf6; /* Purple-500 thumb */
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #7c3aed; /* Darker purple on hover */
        }
    </style>
</head>
<body class="bg-gray-100 p-8">
    <!-- This div wraps the entire content for this section, made full width -->
    <!-- Changed p-8 to pt-6 px-8 pb-8 to reduce top padding and keep horizontal/bottom padding consistent -->
    <div class="bg-white rounded-3xl shadow-xl pt-6 px-8 pb-8 w-full">
        <h2 class="text-3xl font-extrabold mb-6 text-purple-800">Manage Leave Requests</h2>

        <div class="overflow-x-auto rounded-xl shadow-md border border-gray-200">
            <table class="min-w-full bg-white text-sm text-left">
                <thead class="bg-gray-100 text-xs uppercase text-gray-700 border-b border-gray-200">
                    <tr>
                        <th class="p-4 font-semibold rounded-tl-xl">ID</th>
                        <th class="p-4 font-semibold">Student ID</th>
                        <th class="p-4 font-semibold">Leave Type</th>
                        <th class="p-4 font-semibold">From</th>
                        <th class="p-4 font-semibold">To</th>
                        <th class="p-4 font-semibold">Reason</th>
                        <th class="p-4 font-semibold">Document</th>
                        <th class="p-4 font-semibold">Status</th>
                        <th class="p-4 font-semibold">Created At</th>
                        <th class="p-4 font-semibold rounded-tr-xl">Actions</th>
                    </tr>
                </thead>
                <tbody id="leaveTableBody" class="text-gray-700">
                    <!-- JS will populate rows here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Leave Request Modal (Example - add if needed) -->
    <!-- You can add a modal similar to the Leave Assignments or Leave Types if you need edit/view functionality -->
    <div id="viewLeaveRequestModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50 p-4">
        <div class="bg-white p-8 rounded-2xl w-full max-w-lg shadow-2xl transform transition-all scale-100 opacity-100 duration-300 ease-out max-h-[90vh] overflow-y-auto">
            <h3 class="text-2xl font-bold mb-6 text-purple-700">View Leave Request Details</h3>
            <div id="leaveRequestDetails" class="grid grid-cols-1 gap-4 text-base text-gray-700">
                <!-- Details will be populated by JS -->
                <p><strong>ID:</strong> <span id="viewId"></span></p>
                <p><strong>Student ID:</strong> <span id="viewStudentId"></span></p>
                <p><strong>Leave Type:</strong> <span id="viewLeaveType"></span></p>
                <p><strong>From:</strong> <span id="viewFrom"></span></p>
                <p><strong>To:</strong> <span id="viewTo"></span></p>
                <p><strong>Reason:</strong> <span id="viewReason"></span></p>
                <p><strong>Document:</strong> <a id="viewDocument" href="#" target="_blank" class="text-blue-600 hover:underline">View Document</a></p>
                <p><strong>Status:</strong> <span id="viewStatus" class="font-semibold"></span></p>
                <p><strong>Created:</strong> <span id="viewCreated"></span></p>
            </div>
            <div class="flex justify-end gap-4 mt-6">
                <button type="button" onclick="closeViewLeaveModal()" class="text-gray-700 bg-gray-200 px-5 py-2 rounded-xl hover:bg-gray-300 transition-colors duration-200 font-medium">Close</button>
            </div>
        </div>
    </div>