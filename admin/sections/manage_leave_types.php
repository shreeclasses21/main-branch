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
    <title>Manage Leave Types</title>
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
    <!-- Changed max-w-7xl to max-w-full and added w-full for wider appearance -->
    <div class="bg-white rounded-3xl shadow-xl p-8 mb-8 w-full">
        <h2 class="text-3xl font-extrabold mb-6 text-purple-800">Manage Leave Types</h2>

        <!-- Add Leave Type Form -->
        <form id="addLeaveTypeForm" class="grid grid-cols-1 md:grid-cols-2 gap-5 bg-purple-50 p-6 rounded-2xl shadow-inner mb-8 border border-purple-100">
            <label class="block col-span-1">
                <span class="text-gray-700 font-medium text-sm">Leave Type Name:</span>
                <input type="text" name="name" placeholder="e.g., Sick Leave" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base" required>
            </label>
            <label class="block col-span-1">
                <span class="text-gray-700 font-medium text-sm">Allowed per Month:</span>
                <input type="number" name="allowed_per_month" placeholder="e.g., 2" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base" required>
            </label>
            <label class="block col-span-full">
                <span class="text-gray-700 font-medium text-sm">Description:</span>
                <textarea name="description" placeholder="Short description of the leave type" rows="3" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base"></textarea>
            </label>

            <button type="submit" class="col-span-full bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white py-3 rounded-xl shadow-lg transition-all duration-300 ease-in-out flex items-center justify-center text-lg font-semibold">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Add Leave Type
            </button>
        </form>

        <!-- Leave Types Table -->
        <div class="overflow-x-auto rounded-xl shadow-md border border-gray-200">
            <table class="min-w-full bg-white text-sm text-left">
                <thead class="bg-gray-100 text-xs uppercase text-gray-700 border-b border-gray-200">
                    <tr>
                        <th class="p-4 font-semibold rounded-tl-xl">ID</th>
                        <th class="p-4 font-semibold">Name</th>
                        <th class="p-4 font-semibold">Description</th>
                        <th class="p-4 font-semibold">Allowed/Month</th>
                        <th class="p-4 font-semibold rounded-tr-xl">Actions</th>
                    </tr>
                </thead>
                <tbody id="leaveTypeTableBody" class="text-gray-700">
                    <!-- JS will populate rows here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Edit Leave Type Modal -->
    <div id="editLeaveTypeModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50 p-4">
        <div class="bg-white p-8 rounded-2xl w-full max-w-lg shadow-2xl transform transition-all scale-100 opacity-100 duration-300 ease-out max-h-[90vh] overflow-y-auto">
            <h3 class="text-2xl font-bold mb-6 text-purple-700">Edit Leave Type</h3>
            <form id="editLeaveTypeForm" class="grid grid-cols-1 gap-5">
                <input type="hidden" name="id" id="editLeaveId">
                <label class="block">
                    <span class="text-gray-700 font-medium text-sm">Leave Type Name:</span>
                    <input type="text" name="name" id="editLeaveName" placeholder="Leave Type Name" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base" required>
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium text-sm">Allowed per Month:</span>
                    <input type="number" name="allowed_per_month" id="editLeaveAllowed" placeholder="Allowed per Month" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base" required>
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium text-sm">Description:</span>
                    <textarea name="description" id="editLeaveDesc" placeholder="Description" rows="3" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base"></textarea>
                </label>
                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" onclick="closeEditLeaveModal()" class="text-gray-700 bg-gray-200 px-5 py-2 rounded-xl hover:bg-gray-300 transition-colors duration-200 font-medium">Cancel</button>
                    <button type="submit" class="bg-purple-600 text-white px-5 py-2 rounded-xl hover:bg-purple-700 transition-colors duration-200 font-medium shadow-md">Save Changes</button>
                </div>
            </form>
        </div>
    </div>