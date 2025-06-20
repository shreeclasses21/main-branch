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
    <title>Leave Assignments</title>
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
    <!-- This div wraps the entire content for this section -->
    <div class="bg-white rounded-3xl shadow-xl p-8 mb-8">
        <h2 class="text-3xl font-extrabold mb-6 text-purple-800">Leave Assignments</h2>

        <!-- Generate Monthly Assignments Button -->
        <div class="mb-8 flex items-center justify-between flex-wrap gap-4">
            <button
                onclick="generateMonthlyAssignments()"
                class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3 rounded-xl shadow-lg hover:from-green-600 hover:to-green-700 transition-all duration-300 ease-in-out flex items-center justify-center text-lg font-semibold"
            >
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Generate Monthly Assignments
            </button>
            <span id="genStatus" class="ml-4 text-base text-gray-600 font-medium"></span>
        </div>

        <div class="overflow-x-auto rounded-xl shadow-md border border-gray-200">
            <table class="min-w-full bg-white text-sm text-left">
                <thead class="bg-gray-100 text-xs uppercase text-gray-700 border-b border-gray-200">
                    <tr>
                        <th class="p-4 font-semibold rounded-tl-xl">ID</th>
                        <th class="p-4 font-semibold">Student ID</th>
                        <th class="p-4 font-semibold">Student Name</th>
                        <th class="p-4 font-semibold">Leave Type</th>
                        <th class="p-4 font-semibold">Month</th>
                        <th class="p-4 font-semibold">Year</th>
                        <th class="p-4 font-semibold">Allowed</th>
                        <th class="p-4 font-semibold">Used</th>
                        <th class="p-4 font-semibold">Remaining</th>
                        <th class="p-4 font-semibold">Created At</th>
                        <th class="p-4 font-semibold rounded-tr-xl">Actions</th>
                    </tr>
                </thead>
                <tbody id="assignmentTableBody">
                    <!-- JS will load rows here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Assignment Modal -->
    <div id="editAssignmentModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50 p-4">
        <!-- Added max-h-screen and overflow-y-auto to allow scrolling for very tall forms -->
        <div class="bg-white p-8 rounded-2xl w-full max-w-lg shadow-2xl transform transition-all scale-100 opacity-100 duration-300 ease-out max-h-[90vh] overflow-y-auto">
            <h3 class="text-2xl font-bold mb-6 text-purple-700">Edit Leave Assignment</h3>
            <!-- Adjusted gap to be slightly smaller -->
            <form id="editAssignmentForm" class="grid grid-cols-1 gap-4">
                <input type="hidden" name="id" id="assignId">
                <label class="block">
                    <span class="text-gray-700 font-medium text-sm">Student ID:</span>
                    <input type="text" name="student_id" id="assignStudent" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base" placeholder="Student ID" required>
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium text-sm">Leave Type:</span>
                    <input type="text" name="leave_type_id" id="assignLeaveType" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base" placeholder="Leave Type (e.g., Sick Leave)" required>
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium text-sm">Year:</span>
                    <input type="number" name="year" id="assignYear" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base" placeholder="Year" required>
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium text-sm">Month:</span>
                    <input type="text" name="month" id="assignMonth" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base" placeholder="Month (e.g., January)" required>
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium text-sm">Allowed Leaves:</span>
                    <input type="number" name="allowed" id="assignAllowed" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base" placeholder="Allowed Leaves" required>
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium text-sm">Used Leaves:</span>
                    <input type="number" name="used" id="assignUsed" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base" placeholder="Used Leaves">
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium text-sm">Remaining Leaves:</span>
                    <input type="number" name="remaining" id="assignRemaining" class="mt-1 block w-full border border-gray-300 rounded-lg p-2 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base" placeholder="Remaining Leaves">
                </label>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" onclick="closeAssignmentModal()" class="text-gray-700 bg-gray-200 px-4 py-2 rounded-xl hover:bg-gray-300 transition-colors duration-200 font-medium text-base">Cancel</button>
                    <button type="submit" class="bg-purple-600 text-white px-4 py-2 rounded-xl hover:bg-purple-700 transition-colors duration-200 font-medium shadow-md text-base">Update Assignment</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Assignment Modal -->
    <div id="editAssignmentModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50 p-4">
        <div class="bg-white p-8 rounded-2xl w-full max-w-lg shadow-2xl transform transition-all scale-100 opacity-100 duration-300 ease-out">
            <h3 class="text-2xl font-bold mb-6 text-purple-700">Edit Leave Assignment</h3>
            <form id="editAssignmentForm" class="grid grid-cols-1 gap-5">
                <input type="hidden" name="id" id="assignId">
                <label class="block">
                    <span class="text-gray-700 font-medium">Student ID:</span>
                    <input type="text" name="student_id" id="assignStudent" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50" placeholder="Student ID" required>
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium">Leave Type:</span>
                    <input type="text" name="leave_type_id" id="assignLeaveType" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50" placeholder="Leave Type (e.g., Sick Leave)" required>
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium">Year:</span>
                    <input type="number" name="year" id="assignYear" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50" placeholder="Year" required>
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium">Month:</span>
                    <input type="text" name="month" id="assignMonth" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50" placeholder="Month (e.g., January)" required>
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium">Allowed Leaves:</span>
                    <input type="number" name="allowed" id="assignAllowed" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50" placeholder="Allowed Leaves" required>
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium">Used Leaves:</span>
                    <input type="number" name="used" id="assignUsed" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50" placeholder="Used Leaves">
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium">Remaining Leaves:</span>
                    <input type="number" name="remaining" id="assignRemaining" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50" placeholder="Remaining Leaves">
                </label>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" onclick="closeAssignmentModal()" class="text-gray-700 bg-gray-200 px-5 py-2 rounded-xl hover:bg-gray-300 transition-colors duration-200 font-medium">Cancel</button>
                    <button type="submit" class="bg-purple-600 text-white px-5 py-2 rounded-xl hover:bg-purple-700 transition-colors duration-200 font-medium shadow-md">Update Assignment</button>
                </div>
            </form>
        </div>
    </div>
