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
    <title>Attendance Log</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter (Link remains as it's an external resource) -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        /* No global body or scrollbar styles here. These should be defined in your main application's global CSS. */
        /* This style block is intentionally minimal for isolation. */

        /* If you need very specific, non-Tailwind styles for elements *within* this component,
           you would add them here, ideally scoped to specific classes within this component's div. */
    </style>
</head>
<body>

    <!-- Main Content Container for Attendance Log -->
    <!-- This div contains all the UI for the Attendance Log.
         Its styling is applied via Tailwind CSS classes, making it self-contained. -->
    <div class="bg-white rounded-3xl shadow-xl p-8 w-full max-w-4xl mx-auto md:p-10">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-extrabold text-purple-800">Attendance Log</h2>
            <!-- Optional: Add a button here if needed, e.g., "Export Data" -->
            <!-- <button class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 py-3 rounded-xl shadow-lg transition-all duration-300 ease-in-out text-lg font-semibold">
                Export Data
            </button> -->
        </div>

        <div class="overflow-x-auto rounded-xl shadow-md border border-gray-200">
            <table class="min-w-full bg-white text-sm text-left">
                <thead class="bg-purple-50 text-xs uppercase text-purple-700 border-b border-purple-200">
                    <tr>
                        <th scope="col" class="px-6 py-3 font-semibold rounded-tl-xl">Student ID</th>
                        <th scope="col" class="px-6 py-3 font-semibold">Student Name</th>
                        <th scope="col" class="px-6 py-3 font-semibold">Date</th>
                        <th scope="col" class="px-6 py-3 font-semibold rounded-tr-xl">Type</th>
                    </tr>
                </thead>
                <tbody id="attendanceLogTable" class="divide-y divide-gray-100">
                    <!-- Data rows will be loaded here by JS -->
                    <!-- Example Rows (for visual reference) -->
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800">STD001</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800">Alice Johnson</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800">2025-06-15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800">Present</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800">STD002</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800">Bob Williams</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800">2025-06-15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-red-600 font-medium">Absent</td>
                    </tr>
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800">STD003</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800">Charlie Brown</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800">2025-06-15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-blue-600 font-medium">Leave</td>
                    </tr>
                     <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800">STD004</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800">Diana Prince</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800">2025-06-15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-800">Present</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    
</body>
</html>
