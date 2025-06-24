<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
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

        /* Active link styling for sidebar */
        .active-link {
            background-color: rgba(139, 92, 246, 0.2); /* Light purple background */
            color: #fff; /* White text for active */
            font-weight: 600;
        }
        .active-link svg {
            color: #c4b5fd; /* Lighter purple icon for active */
        }

        /* Specific styles for sidebar animation */
        .sidebar-hidden {
            transform: translateX(-100%);
            transition: transform 0.3s ease-out;
        }
        .sidebar-visible {
            transform: translateX(0);
            transition: transform 0.3s ease-in;
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col md:flex-row text-gray-800">

    <header class="md:hidden bg-gradient-to-br from-purple-800 to-indigo-900 p-4 flex justify-between items-center shadow-lg">
        <h2 class="text-2xl font-extrabold text-white">Admin Panel</h2>
        <button id="mobile-menu-button" class="text-white focus:outline-none">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
        </button>
    </header>

    <aside id="sidebar" class="w-64 bg-gradient-to-br from-purple-800 to-indigo-900 shadow-2xl flex flex-col p-6 sticky top-0 h-screen overflow-y-auto z-50 rounded-r-3xl
                  md:flex md:static md:translate-x-0
                  absolute inset-y-0 left-0 transform -translate-x-full
                  transition-transform duration-300 ease-in-out">
        <div class="mb-10 mt-2">
            <h2 class="text-4xl font-extrabold text-white tracking-tight leading-tight">Admin <br> Panel</h2>
            <p class="text-sm text-purple-200 mt-2">Dashboard Overview</p>
        </div>
        <nav class="flex-grow">
            <ul>
                <li class="mb-3">
                    <a href="#" onclick="loadSection('manage_students', this)" class="flex items-center px-4 py-3 text-lg font-medium text-purple-200 hover:bg-purple-700 hover:text-white rounded-xl transition-all duration-300 ease-in-out group active-link">
                        <svg class="w-6 h-6 mr-3 text-purple-300 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H2m20 0h-2.197m0 0a1.5 1.5 0 01-1.405-1.012L9.91 3.235A2.75 2.75 0 007.243 2H5.5c-.83 0-1.5.67-1.5 1.5v3.25l7.926 7.926A6.745 6.745 0 0017 20z"></path></svg>
                        Manage Students
                    </a>
                </li>
                <li class="mb-3">
                    <a href="#" onclick="loadSection('manage_leaves', this)" class="flex items-center px-4 py-3 text-lg font-medium text-purple-200 hover:bg-purple-700 hover:text-white rounded-xl transition-all duration-300 ease-in-out group">
                        <svg class="w-6 h-6 mr-3 text-purple-300 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Manage Leave Requests
                    </a>
                </li>
                <li class="mb-3">
                    <a href="#" onclick="loadSection('manage_leave_types', this)" class="flex items-center px-4 py-3 text-lg font-medium text-purple-200 hover:bg-purple-700 hover:text-white rounded-xl transition-all duration-300 ease-in-out group">
                        <svg class="w-6 h-6 mr-3 text-purple-300 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                        Manage Leave Types
                    </a>
                </li>
                <li class="mb-3">
                    <a href="#" onclick="loadSection('manage_leave_assignments', this)" class="flex items-center px-4 py-3 text-lg font-medium text-purple-200 hover:bg-purple-700 hover:text-white rounded-xl transition-all duration-300 ease-in-out group">
                        <svg class="w-6 h-6 mr-3 text-purple-300 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0H9m7 7l-3 3m0 0l-3-3m3 3V12"></path></svg>
                        Leave Assignments
                    </a>
                </li>
                <li class="mb-3">
                    <a href="#" onclick="loadSection('public_holidays', this)" class="flex items-center px-4 py-3 text-lg font-medium text-purple-200 hover:bg-purple-700 hover:text-white rounded-xl transition-all duration-300 ease-in-out group">
                        <svg class="w-6 h-6 mr-3 text-purple-300 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Public Holidays
                    </a>
                </li>
                <li class="mb-3">
                    <a href="#" onclick="loadSection('attendance_log', this)" class="flex items-center px-4 py-3 text-lg font-medium text-purple-200 hover:bg-purple-700 hover:text-white rounded-xl transition-all duration-300 ease-in-out group">
                        <svg class="w-6 h-6 mr-3 text-purple-300 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Attendance Log
                    </a>
                </li>
                <li class="mb-3">
                    <a href="#" onclick="loadSection('manage_regularizations', this)" class="flex items-center px-4 py-3 text-lg font-medium text-purple-200 hover:bg-purple-700 hover:text-white rounded-xl transition-all duration-300 ease-in-out group">
                        <svg class="w-6 h-6 mr-3 text-purple-300 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Manage Regularizations
                    </a>
                </li>
                <li class="mb-3">
                    <a href="#" onclick="loadSection('manage_payment_invoices', this)" class="flex items-center px-4 py-3 text-lg font-medium text-purple-200 hover:bg-purple-700 hover:text-white rounded-xl transition-all duration-300 ease-in-out group">
                        <svg class="w-6 h-6 mr-3 text-purple-300 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Manage Invoices
                    </a>
                    <li class="mb-3">
    <a href="#" onclick="loadSection('manage_subjects', this)" class="flex items-center px-4 py-3 text-lg font-medium text-purple-200 hover:bg-purple-700 hover:text-white rounded-xl transition-all duration-300 ease-in-out group">
        <svg class="w-6 h-6 mr-3 text-purple-300 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Manage Subjects
    </a>
</li>

                </li>
                <li class="mb-3">
    <a href="#" onclick="loadSection('upload_files', this)" class="flex items-center px-4 py-3 text-lg font-medium text-purple-200 hover:bg-purple-700 hover:text-white rounded-xl transition-all duration-300 ease-in-out group">
        <svg class="w-6 h-6 mr-3 text-purple-300 group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        Upload Files for Students
    </a>
</li>

            </ul>
        </nav>
        <div class="mt-auto pt-6 border-t border-purple-700">
            <a href="logout.php" class="flex items-center justify-center px-4 py-3 text-lg font-medium text-red-300 bg-red-800 hover:bg-red-700 rounded-xl transition-colors duration-300 ease-in-out shadow-lg">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
            </a>
        </div>
    </aside>

    <main class="flex-1 p-4 md:p-8 overflow-y-auto">
        <div id="adminContent" class="bg-white rounded-3xl shadow-xl p-6 md:p-10 min-h-[calc(100vh-4rem)] flex items-center justify-center text-gray-500 text-xl font-medium">
            <p>Select a section from the vibrant sidebar to begin managing your data effectively.</p>
        </div>
    </main>

<script src="js/dashboard.js"></script>
<script>
function loadSection(section) {
  fetch(`sections/${section}.php`)
    .then(res => res.text())
    .then(html => {
      document.getElementById("adminContent").innerHTML = html;

      // ✅ Run section-specific JS initializer
      switch (section) {
        case 'manage_students':
          initManageStudents();
          break;
        case 'manage_leaves':
          initManageLeaves();
          break;
        case 'manage_leave_types':
           initManageLeaveTypes();
           break;
        case 'manage_leave_assignments':
           initManageLeaveAssignments();
           break;
         case 'public_holidays':
           initPublicHolidays();
           break;
        case 'attendance_log':
  initAttendanceLog();
  break;
  case 'manage_regularizations':
  initManageRegularizations();
  break;
  case 'manage_payment_invoices':
  initManageInvoices();
  break;
   case 'upload_files':
  initUploadFiles();
  break;
  case 'manage_subjects':
  initManageSubjects();
  break;
        // Add more cases for future modules like manage_leave_types, leave_assignments etc.
      }
    })
    .catch(err => {
      console.error("Section load failed:", err);
      document.getElementById("adminContent").innerHTML = "<p class='text-red-600'>Failed to load section.</p>";
    });
}

</script>
</body>
</html>
