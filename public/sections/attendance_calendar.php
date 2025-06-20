<?php
session_start();
if (!isset($_SESSION['contact_id'])) {
  echo "<p class='text-red-500'>Please log in to view attendance calendar.</p>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Attendance Calendar</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Link to your custom CSS file -->
    <link rel="stylesheet" href="./css/attendance_calendar.css">
</head>
<body>

    <!-- Main wrapper for the calendar application to isolate its styles -->
    <div id="calendar-app-wrapper">
        <div class="calendar-container">
            <h1 class="calendar-title"><i class="fas fa-calendar-alt"></i> Attendance Calendar</h1>

            <div class="calendar-navigation">
                <button id="prevMonth" class="nav-button"><i class="fas fa-chevron-left"></i> Previous</button>
                <h2 id="calendarMonth" class="month-label"></h2>
                <button id="nextMonth" class="nav-button">Next <i class="fas fa-chevron-right"></i></button>
            </div>

            <div class="calendar-grid-header">
                <div>Sun</div>
                <div>Mon</div>
                <div>Tue</div>
                <div>Wed</div>
                <div>Thu</div>
                <div>Fri</div>
                <div>Sat</div>
            </div>
            <div id="calendarGrid" class="calendar-grid">
                <!-- Calendar cells will be injected by JavaScript -->
            </div>

            <div class="legend">
                <div class="legend-item">
                    <span class="legend-color present"></span> Present
                </div>
                <div class="legend-item">
                    <span class="legend-color absent"></span> Absent
                </div>
                <div class="legend-item">
                    <span class="legend-color on-leave"></span> On Leave
                </div>
                <div class="legend-item">
                    <span class="legend-color off-day"></span> Off (Sunday)
                </div>
            </div>
        </div>
    </div>

    <!-- Link to your custom JavaScript file -->
    <script src="./js/attendance_calendar.js"></script>
</body>
</html>
