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
    <title>Public Holidays</title>
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
    <div class="bg-white rounded-3xl shadow-xl pt-6 px-8 pb-8 w-full">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-extrabold text-purple-800">Public Holidays</h2>
            <button onclick="openHolidayModal('add')" class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 py-3 rounded-xl shadow-lg transition-all duration-300 ease-in-out flex items-center justify-center text-lg font-semibold">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Add Holiday
            </button>

            <!-- Upload CSV Form -->
        <form id="uploadCsvForm" enctype="multipart/form-data" class="flex gap-2 items-center">
            <input type="file" id="holidayCsvFile" name="holidayCsvFile" accept=".csv"
                   class="text-sm border border-gray-300 rounded-lg file:bg-purple-100 file:border-0 file:px-3 file:py-1 file:text-purple-700 file:font-semibold file:cursor-pointer hover:file:bg-purple-200 cursor-pointer bg-white" required>
            <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-xl shadow transition">
                Upload CSV
            </button>
        </form>
        </div>

        <div class="overflow-x-auto rounded-xl shadow-md border border-gray-200">
            <table class="min-w-full bg-white text-sm text-left">
                <thead class="bg-gray-100 text-xs uppercase text-gray-700 border-b border-gray-200">
                    <tr>
                        <th class="p-4 font-semibold rounded-tl-xl">ID</th>
                        <th class="p-4 font-semibold">Date</th>
                        <th class="p-4 font-semibold">Name</th>
                        <th class="p-4 font-semibold">Type</th>
                        <th class="p-4 font-semibold">Year</th>
                        <th class="p-4 font-semibold rounded-tr-xl">Actions</th>
                    </tr>
                </thead>
                <tbody id="holidayTableBody">
                    <!-- JS loads rows here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- 🎯 Modal Form -->
    <div id="holidayModal" class="hidden fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50 p-4">
        <div class="bg-white p-8 rounded-2xl w-full max-w-lg shadow-2xl transform transition-all scale-100 opacity-100 duration-300 ease-out max-h-[90vh] overflow-y-auto">
            <h3 class="text-2xl font-bold mb-6 text-purple-700" id="holidayModalTitle">Add/Edit Holiday</h3>
            <form id="holidayForm" class="grid grid-cols-1 gap-5">
                <input type="hidden" name="id" id="holidayId">
                <label class="block">
                    <span class="text-gray-700 font-medium text-sm">Holiday Date:</span>
                    <input type="date" name="holiday_date" id="holidayDate" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base" required>
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium text-sm">Holiday Name:</span>
                    <input type="text" name="name" id="holidayName" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base" placeholder="e.g., New Year's Day" required>
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium text-sm">Type:</span>
                    <select name="type" id="holidayType" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base" required>
                        <option value="">-- Select Type --</option>
                        <option value="National">National</option>
                        <option value="State">State</option>
                        <option value="Religious">Religious</option>
                        <option value="Regional">Regional</option>
                        <option value="Other">Other</option>
                    </select>
                </label>
                <label class="block">
                    <span class="text-gray-700 font-medium text-sm">Year:</span>
                    <input type="number" name="year" id="holidayYear" class="mt-1 block w-full border border-gray-300 rounded-lg p-3 shadow-sm focus:border-purple-500 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-base" placeholder="e.g., 2025" required>
                </label>

                <div class="flex justify-end gap-4 mt-6">
                    <button type="button" onclick="closeHolidayModal()" class="text-gray-700 bg-gray-200 px-5 py-2 rounded-xl hover:bg-gray-300 transition-colors duration-200 font-medium">Cancel</button>
                    <button type="submit" class="bg-purple-600 text-white px-5 py-2 rounded-xl hover:bg-purple-700 transition-colors duration-200 font-medium shadow-md">Save</button>
                </div>
            </form>
        </div>
    </div>