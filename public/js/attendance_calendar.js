document.addEventListener("DOMContentLoaded", () => {
    initAttendanceCalendar();
});

async function initAttendanceCalendar() {
    const today = new Date();
    let currentMonth = today.toISOString().slice(0, 7); // "YYYY-MM" format

    const grid = document.getElementById("calendarGrid");
    const monthLabel = document.getElementById("calendarMonth");

    // Event listeners for month navigation buttons
    document.getElementById("prevMonth").onclick = () => changeMonth(-1);
    document.getElementById("nextMonth").onclick = () => changeMonth(1);

    /**
     * Changes the current month displayed in the calendar.
     * @param {number} delta - The number of months to change (e.g., -1 for previous, 1 for next).
     */
    function changeMonth(delta) {
        const [year, month] = currentMonth.split("-").map(Number);
        // Create a new Date object for the new month
        const newDate = new Date(year, month - 1 + delta, 1);
        // Update currentMonth to the new "YYYY-MM" format
        currentMonth = newDate.toISOString().slice(0, 7);
        loadCalendar(); // Reload the calendar for the newly selected month
    }

    /**
     * Loads and renders the calendar for the currentMonth.
     * This function fetches attendance data from a backend API.
     */
    async function loadCalendar() {
        const [year, month] = currentMonth.split("-").map(Number);
        const start = new Date(year, month - 1, 1); // First day of the current month
        const end = new Date(year, month, 0); // Last day of the current month
        const daysInMonth = end.getDate(); // Total number of days in the current month
        const firstDayOfMonthWeekday = start.getDay(); // Weekday of the 1st of the month (0=Sunday, 1=Monday, ..., 6=Saturday)

        // Get today's date information for highlighting the current day
        const todayDate = new Date();
        const isCurrentMonthToday = (todayDate.getFullYear() === year && todayDate.getMonth() === month - 1);
        const todayDay = todayDate.getDate();

        // Update the month and year label in the UI
        monthLabel.textContent = `${start.toLocaleString('default', { month: 'long' })} ${year}`;

        // Fetch attendance data from the API endpoint
        let attendance = {};
        try {
            // This fetch call expects a backend API running at `../api/calendar_status.php`
            // that returns JSON data with attendance statuses for dates.
            const res = await fetch(`../api/calendar_status.php?month=${currentMonth}`);
            if (res.ok) {
                attendance = await res.json();
            } else {
                console.error("Failed to fetch attendance data:", res.statusText);
                // In a production app, you might show a user-friendly error message here.
            }
        } catch (error) {
            console.error("Error fetching attendance data:", error);
            // In a production app, handle network errors gracefully (e.g., display a message).
        }

        grid.innerHTML = ""; // Clear existing calendar grid cells

        // Add empty cells at the beginning to align the first day of the month
        // with the correct weekday column (e.g., if Jan 1st is a Wednesday, add 3 empty cells for Sun, Mon, Tue).
        for (let i = 0; i < firstDayOfMonthWeekday; i++) {
            grid.innerHTML += `<div class="calendar-cell bg-default"></div>`;
        }

        // Populate calendar cells for each day of the month
        for (let day = 1; day <= daysInMonth; day++) {
            const date = `${currentMonth}-${String(day).padStart(2, '0')}`;
            const dayOfWeek = new Date(year, month - 1, day).getDay(); // Get the day of the week (0 for Sunday)
            let status = attendance[date]; // Get status from fetched data

            // If no status is provided by the API and it's a Sunday, default to 'Off'
            if (dayOfWeek === 0 && !status) {
                status = 'Off';
            } else if (!status) {
                status = ''; // If no status from API, leave it empty (can be changed to 'N/A' or 'Pending')
            }

            let statusClass = ""; // CSS class for status text (e.g., 'present', 'absent')
            let backgroundColorClass = "bg-default"; // CSS class for the cell's background color
            let statusText = ""; // Text to display for the status
            // Add 'today' class if the current cell corresponds to today's date
            let todayClass = (isCurrentMonthToday && day === todayDay) ? 'today' : '';

            // Assign appropriate classes and text based on the determined status
            if (status === 'Present') {
                statusClass = "present";
                backgroundColorClass = "bg-present";
                statusText = "Present";
            } else if (status === 'Absent') {
                statusClass = "absent";
                backgroundColorClass = "bg-absent";
                statusText = "Absent";
            } else if (status === 'On Leave') {
                statusClass = "on-leave";
                backgroundColorClass = "bg-on-leave";
                statusText = "On Leave";
            } else if (status === 'Off') {
                statusClass = "off-day";
                backgroundColorClass = "bg-off-day";
                statusText = "Off";
            }

            // Construct and append the HTML for each calendar cell
            grid.innerHTML += `
                <div class="calendar-cell ${backgroundColorClass} ${todayClass}">
                    <div class="day-number">${day}</div>
                    ${statusText ? `<div class="status-text ${statusClass}">${statusText}</div>` : ''}
                </div>
            `;
        }
    }

    loadCalendar(); // Initial load of the calendar when the page loads
}
