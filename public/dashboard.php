<?php
session_start();
error_log("Dashboard: Session started. Checking login status.");

// Check if student is logged in
if (!isset($_SESSION['contact_id'])) {
    error_log("Dashboard: User not logged in. Redirecting to index.html.");
    header("Location: index.html"); // Redirect to login page if not logged in (index.html is your login page)
    exit;
}

$contactId = $_SESSION['contact_id'];
$email = $_SESSION['student_email'] ?? $_SESSION['student_id'] ?? "Student"; // Use student_email/student_id from session
error_log("Dashboard: User is logged in. Contact ID: $contactId");
error_log("Dashboard: Initial email/ID for display: $email");

// Fallback display name
$displayName = $email;

// Try to fetch FirstName + LastName from Salesforce if access token/session data exists
if (isset($_SESSION['access_token']) && isset($_SESSION['instance_url'])) { // Corrected session variable names
    $instance_url = $_SESSION['instance_url'];
    $access_token = $_SESSION['access_token'];
    error_log("Dashboard: Salesforce session data found. Attempting to fetch Contact details for $contactId.");

    $url = "$instance_url/services/data/v60.0/sobjects/Contact/$contactId";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $access_token",
        "Content-Type: application/json" // Added Content-Type header for consistency
    ]);
    $response = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    error_log("Dashboard: Salesforce API call status: $status. Response: $response");

    if ($status == 200) {
        $data = json_decode($response, true);
        $firstName = $data['FirstName'] ?? '';
        $lastName = $data['LastName'] ?? '';

        if ($firstName || $lastName) {
            $fullName = trim($firstName . ' ' . $lastName);
            $displayName = "$fullName ($email)";
            error_log("Dashboard: Full name fetched from Salesforce: $displayName");
        } else {
            error_log("Dashboard: First/Last name not found in Salesforce response. Using email: $email");
        }
    } else {
        error_log("Dashboard: Failed to fetch contact from Salesforce. Status: $status. Response: $response");
    }
} else {
    error_log("Dashboard: Salesforce access_token or instance_url missing from session. Using email: $email");
}

error_log("Dashboard: Final display name set to: " . $displayName);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Shree Classes</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
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
                    },
                    boxShadow: {
                        'custom-light': '0 4px 6px -1px var(--tw-shadow-light), 0 2px 4px -1px var(--tw-shadow-light)',
                        'card-glow': '0 0 30px rgba(109, 40, 217, 0.15)',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="css/dashboard.css">
    <!-- Inter font from Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="font-inter flex flex-col h-screen overflow-hidden">
    <header class="bg-header-bg shadow-md p-4 flex items-center justify-between z-10 sm:justify-between">
        <div class="flex items-center">
            <!-- Hamburger menu for mobile -->
            <button id="sidebarToggle" class="lg:hidden text-primary-dark mr-4 focus:outline-none">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
            <svg class="h-8 w-8 text-primary-dark mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.201 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.523 5.799 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.799 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.523 18.201 18 16.5 18s-3.332.477-4.5 1.253"></path>
            </svg>
            <h1 class="text-xl sm:text-2xl font-bold text-primary-dark">Shree Classes</h1>
        </div>
        <div class="flex items-center space-x-2 sm:space-x-4">
            <a href="#" data-section="student_profile" id="profileIconLink" class="sidebar-link p-2 rounded-full bg-background-dark hover:bg-primary-light transition duration-300 group">
                <svg class="h-5 w-5 sm:h-6 sm:w-6 text-text-main group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </a>
            <span class="text-sm sm:text-lg text-text-main hidden sm:inline">Welcome, <span id="displayNamePlaceholder" class="font-semibold"><?php echo htmlspecialchars($displayName); ?></span>!</span>
            <a href="../auth/logout.php" class="px-3 py-1 sm:px-4 sm:py-2 bg-logout-red text-white rounded-lg shadow-md hover:bg-red-700 transition duration-300 text-sm sm:text-base">Logout</a>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar -->
        <aside id="sidebar" class="bg-sidebar-bg w-64 p-6 flex-col shadow-lg h-full overflow-y-auto transform -translate-x-full fixed inset-y-0 left-0 z-30 transition-transform duration-300 ease-in-out lg:relative lg:translate-x-0 lg:flex">
            <nav class="space-y-4 mt-8 lg:mt-0"> <!-- Added margin-top for mobile to account for fixed header -->
                <!-- Dashboard Link -->
                <a href="dashboard.php" class="flex items-center space-x-3 p-3 rounded-lg text-sidebar-text-light hover:bg-sidebar-hover transition duration-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l7-7 7 7M19 10v10a1 1 0 001 1h3"></path>
                    </svg>
                    <span>Dashboard</span>
                </a>
                <!-- Apply for Leave Link -->
                <a href="#" data-section="apply_leave" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-sidebar-text-light hover:bg-sidebar-hover transition duration-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span>Apply for Leave</span>
                </a>
                <!-- Leave Request Link -->
                <a href="#" data-section="view_leaves" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-sidebar-text-light hover:bg-sidebar-hover transition duration-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    <span>Leave Request</span>
                </a>
                <!-- Public Holidays Link -->
                <a href="#" data-section="public_holidays" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-sidebar-text-light hover:bg-sidebar-hover transition duration-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h1M4 12H3m15.325 6.675l-.707.707M6.757 6.757l-.707-.707m12.728 0l-.707-.707M6.757 17.243l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span>Public Holidays</span>
                </a>
                <!-- Attendance Calendar Link -->
                <a href="#" data-section="attendance_calendar" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-sidebar-text-light hover:bg-sidebar-hover transition duration-200">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span>Attendance Calendar</span>
                </a>
                <a href="#" data-section="attendance_regularization" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-sidebar-text-light hover:bg-sidebar-hover transition duration-200">
    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4m-6 9h6a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-.707-.707A1 1 0 0012.586 4H11.414a1 1 0 00-.707.293l-.707.707A1 1 0 019.586 5H8a2 2 0 00-2 2v10a2 2 0 002 2z"/>
    </svg>
    <span>Regularization Request</span>
</a>
            </a>
                <a href="#" data-section="student_payment_invoices" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-sidebar-text-light hover:bg-sidebar-hover transition duration-200">
    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4m-6 9h6a2 2 0 002-2V7a2 2 0 00-2-2h-1.586a1 1 0 01-.707-.293l-.707-.707A1 1 0 0012.586 4H11.414a1 1 0 00-.707.293l-.707.707A1 1 0 019.586 5H8a2 2 0 00-2 2v10a2 2 0 002 2z"/>
    </svg>
    <span>My Invoices</span>
</a>
<a href="#" data-section="my_files" class="sidebar-link flex items-center space-x-3 p-3 rounded-lg text-sidebar-text-light hover:bg-sidebar-hover transition duration-200">
  <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"></path>
  </svg>
  <span>My Files</span>
</a>

            </nav>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 p-4 sm:p-8 overflow-y-auto">
            <div id="content-area" class="bg-card-bg p-6 sm:p-8 rounded-2xl shadow-xl w-full border border-border-light transform transition-all duration-300 hover:shadow-card-glow animate-fade-in">
                <div class="text-center mb-6 sm:mb-8">
                    <!-- Dashboard Home Icon for Main Content -->
                    <svg class="mx-auto h-12 w-12 sm:h-16 sm:w-16 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m0 0l7-7 7 7M19 10v10a1 1 0 001 1h3"></path>
                    </svg>
                    <h2 class="text-2xl sm:text-4xl font-extrabold text-center text-primary-dark mt-4">Welcome to Your Dashboard!</h2>
                    <p class="text-base sm:text-xl text-text-main mt-2">Hello, <span id="displayNamePlaceholder" class="font-semibold"><?php echo htmlspecialchars($displayName); ?></span>!</p>
                </div>

                <p class="text-center text-text-secondary mb-6 sm:mb-8 leading-relaxed text-sm sm:text-base">This is your student dashboard. From here you can manage various aspects of your academic journey. Select an option below to get started:</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- 🔘 Mark Attendance Card -->
                    <div id="attendanceCard"
                         class="bg-purple-50 border border-purple-300 rounded-xl p-6 mb-8 shadow-lg flex flex-col items-center justify-center text-center">
                        <h3 class="text-lg sm:text-xl font-bold text-purple-800 mb-2">📌 Daily Attendance</h3>
                        <p class="text-xs sm:text-sm text-purple-700 mb-4">Click below to mark your presence for today.</p>
                        <button id="markAttendanceBtn"
                                class="bg-purple-600 text-white px-4 py-2 sm:px-5 sm:py-2 rounded-lg font-semibold shadow hover:bg-purple-700 transition text-sm sm:text-base">
                            Mark Attendance
                        </button>
                        <p id="attendanceMessage" class="text-xs sm:text-sm mt-3 text-gray-800 font-medium"></p>
                    </div>


                    <div class="p-6 bg-gray-50 rounded-lg shadow-sm flex items-center justify-start space-x-3 text-text-secondary border border-border-light">
                        <svg class="h-6 w-6 sm:h-8 sm:w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        <span class="text-base sm:text-lg font-medium">More Features Soon!</span>
                    </div>

                                        <!-- 📊 Attendance Summary Chart -->
<div class="mt-8 p-6 bg-white rounded-lg shadow-md border border-gray-200">
  <h3 class="text-lg font-bold text-gray-800 mb-4">📊 Attendance Summary</h3>
 <canvas id="attendanceChart" width="200" height="200"></canvas>
</div>
                </div>
                
                
            </div>
        </main>
    </div>

    <!-- Overlay for when sidebar is open on mobile -->
    <div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 hidden lg:hidden"></div>

    <script src="js/dashboard.js"></script>
    <script>
        // JavaScript for sidebar toggle
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('-translate-x-full');
            sidebarOverlay.classList.toggle('hidden');
        });

        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        });

        // Optional: Close sidebar if a link is clicked on mobile
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) { // 1024px is Tailwind's 'lg' breakpoint
                    sidebar.classList.add('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                }
            });
        });

        // Example for Attendance Marking (if it was originally in dashboard.js)
        const markAttendanceBtn = document.getElementById('markAttendanceBtn');
        const attendanceMessage = document.getElementById('attendanceMessage');

        if (markAttendanceBtn) {
            markAttendanceBtn.addEventListener('click', async () => {
                attendanceMessage.textContent = "Marking attendance...";
                attendanceMessage.classList.remove('text-green-600', 'text-red-600');
                attendanceMessage.classList.add('text-gray-800');

                try {
                    const response = await fetch('api/mark_attendance.php', { // Assuming an API endpoint
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({ contact_id: <?php echo json_encode($contactId); ?> })
                    });

                    const data = await response.json();

                    if (response.ok) {
                        attendanceMessage.textContent = data.message;
                        attendanceMessage.classList.remove('text-gray-800');
                        attendanceMessage.classList.add('text-green-600');
                    } else {
                        attendanceMessage.textContent = data.error || 'Failed to mark attendance.';
                        attendanceMessage.classList.remove('text-gray-800');
                        attendanceMessage.classList.add('text-red-600');
                    }
                } catch (error) {
                    console.error('Error marking attendance:', error);
                    attendanceMessage.textContent = 'An error occurred. Please try again.';
                    attendanceMessage.classList.remove('text-gray-800');
                    attendanceMessage.classList.add('text-red-600');
                }
            });
        }


        // 📊 Load Attendance Chart
async function loadAttendanceChart() {
    try {
        const res = await fetch('../api/attendance_summary.php');
        const json = await res.json();

        if (json.status === 'success') {
            const data = json.data;
            const labels = Object.keys(data);
            const values = Object.values(data);

            const ctx = document.getElementById('attendanceChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Monthly Attendance',
                        data: values,
                        borderWidth: 2,
                        borderColor: '#fff',
backgroundColor: [
    'rgba(109,40,217,0.85)',  // Present - Violet
    'rgba(249,115,22,0.85)',  // Absent - Orange
    'rgba(190,18,60,0.85)'    // On Leave - Maroon
],
hoverBackgroundColor: [
    'rgba(109,40,217,1)',
    'rgba(249,115,22,1)',
    'rgba(190,18,60,1)'
]


                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                color: '#374151', // Tailwind Gray-700
                                font: {
                                    size: 14,
                                    weight: 'bold'
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function (tooltipItem) {
                                    const count = tooltipItem.raw;
                                    const label = tooltipItem.label;
                                    return `${label}: ${count} day${count !== 1 ? 's' : ''}`;
                                }
                            }
                        }
                    }
                }
            });
        } else {
            console.warn('Chart Load Error:', json.message);
        }
    } catch (err) {
        console.error('Chart Error:', err);
    }
}


document.addEventListener('DOMContentLoaded', loadAttendanceChart);

    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</body>
</html>
