// public/js/public_holidays.js

/**
 * Global function to show a custom message modal.
 * This is duplicated here for self-containment in this immersive.
 * In a real app, this would be in a shared utility file.
 */
function showMessageModal(msg, type = "info") {
    const modal = document.getElementById("messageModal");
    const modalTitle = document.getElementById("modalTitle");
    const modalBody = document.getElementById("modalBody");
    const modalHeader = document.getElementById("modalHeader");
    const modalTitleIcon = modalTitle.querySelector('svg'); // Get existing icon if any

    modalBody.textContent = msg;

    // Reset classes and remove old icon
    modalHeader.classList.remove("bg-green-500", "bg-red-500", "bg-blue-500");
    modalTitle.classList.remove("text-green-800", "text-red-800", "text-blue-800");
    if (modalTitleIcon) modalTitleIcon.remove(); // Remove old icon before adding new one

    let iconPath = '';
    let headerBgColor = '';
    let titleTextColor = '';
    let titleText = '';

    switch (type) {
        case "success":
            titleText = "Success!";
            headerBgColor = "bg-green-500";
            titleTextColor = "text-green-800";
            iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
            break;
        case "error":
            titleText = "Error!";
            headerBgColor = "bg-red-500";
            titleTextColor = "text-red-800";
            iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
            break;
        case "info":
        default:
            titleText = "Information";
            headerBgColor = "bg-blue-500";
            titleTextColor = "text-blue-800";
            iconPath = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>';
            break;
    }

    modalHeader.classList.add(headerBgColor);
    modalTitle.classList.add(titleTextColor);
    modalTitle.innerHTML = `<svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">${iconPath}</svg> ${titleText}`;

    modal.classList.remove("hidden");
}

/**
 * Global function to close the general message modal.
 * This is duplicated here for self-containment in this immersive.
 */
function closeModal() {
    document.getElementById("messageModal").classList.add("hidden");
}

/**
 * Initialize the “Public Holidays” page:
 * - Fetches public holidays for the current year.
 * - Renders them in a calendar grid and a list view.
 */
window.initPublicHolidays = async function() {
    console.log("✅ initPublicHolidays()");
    const year = new Date().getFullYear();
    document.getElementById("holYear").textContent = year; // Set year in main title

    const calDiv = document.getElementById("holidaysCalendar");
    const listEl = document.getElementById("holidaysList");
    const errBox = document.getElementById("holidaysError");
    const loadingOverlay = document.getElementById("loadingOverlay"); // Get the loading overlay element

    // Show loading overlay
    if (loadingOverlay) {
        loadingOverlay.classList.remove('hidden');
    }

    // Clear previous content
    calDiv.innerHTML = "";
    listEl.innerHTML = "";
    errBox.textContent = "";

    let data;
    try {
        const res = await fetch(`../api/get_public_holidays.php?year=${year}`);
        if (!res.ok) {
            const errorText = await res.text(); // Get potential error message from server
            throw new Error(`Status ${res.status}: ${errorText || res.statusText}`);
        }
        data = await res.json();
        console.log("📥 public holidays:", data);

    } catch (e) {
        console.error("❌ Failed to load public holidays:", e);
        errBox.textContent = `Error loading public holidays: ${e.message}. Please try again later.`;
        if (loadingOverlay) loadingOverlay.classList.add('hidden'); // Hide on error
        return;
    } finally {
        // Hide loading overlay regardless of success or failure
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
    }

    if (!Array.isArray(data) || data.length === 0) {
        errBox.textContent = "No public holidays found for this year.";
        return;
    }

    // Group by month
    const months = {};
    data.forEach(h => {
        // Use consistent field names, fallback to older ones if needed
        const holidayDateString = h.holidayDate || h.date;
        const holidayName = h.name || h.Holiday_Name__c;
        const holidayType = h.type || h.type; // Removed fallback to h.Holiday_Type__c as it's already type

        if (!holidayDateString) {
            console.warn("Skipping holiday due to missing date:", h);
            return;
        }

        const dt = new Date(holidayDateString);
        if (isNaN(dt.getTime())) { // Check for invalid date
            console.warn("Skipping holiday due to invalid date format:", holidayDateString);
            return;
        }

        const m = dt.getMonth(); // 0–11
        months[m] = months[m] || [];
        months[m].push({ date: dt, name: holidayName, type: holidayType });
    });

    // Render calendar per month
    for (let m = 0; m < 12; m++) {
        const monthBlock = document.createElement("div");
        // Apply aesthetic Tailwind classes to month block
        monthBlock.className = "border-2 border-purple-200 rounded-xl p-4 bg-purple-50 shadow-md transform hover:scale-105 transition-transform duration-200";

        const header = document.createElement("h3");
        // Apply aesthetic Tailwind classes to month header
        header.className = "font-bold text-center mb-3 text-lg bg-purple-100 text-purple-800 py-2 rounded-lg";
        header.textContent = new Date(year, m).toLocaleString("default", { month: "long", year: "numeric" });
        monthBlock.appendChild(header);

        const grid = document.createElement("div");
        grid.className = "grid grid-cols-7 gap-1 text-sm text-center";

        // Weekday headers - Using single characters for robustness
        ["S", "M", "T", "W", "T", "F", "S"].forEach(d => {
            const w = document.createElement("div");
            // Apply the custom `weekday-header` class
            w.className = "weekday-header";
            w.textContent = d;
            grid.appendChild(w);
        });

        // Fill days
        const firstDayOfMonth = new Date(year, m, 1).getDay(); // 0 for Sunday, 6 for Saturday
        const daysInMonth = new Date(year, m + 1, 0).getDate(); // Last day of current month

        // Blank leading days
        for (let i = 0; i < firstDayOfMonth; i++) {
            grid.appendChild(document.createElement("div"));
        }

        // Days of the month
        for (let d = 1; d <= daysInMonth; d++) {
            const cell = document.createElement("div");
            cell.textContent = d;
            const dateStr = new Date(year, m, d).toISOString().slice(0, 10);
            const hol = (months[m] || []).find(h => h.date.toISOString().slice(0, 10) === dateStr);

            if (hol) {
                // Apply aesthetic Tailwind classes for holiday cells
                cell.className = "bg-red-200 text-red-800 font-semibold rounded-md p-1 cursor-help hover:bg-red-300 transition-colors duration-150";
                cell.title = `${hol.name || 'Holiday'} (${hol.type || 'General'})`; // Improved tooltip
            } else {
                // Apply aesthetic Tailwind classes for normal date cells
                cell.className = "text-gray-600 p-1";
            }
            grid.appendChild(cell);
        }

        monthBlock.appendChild(grid);
        calDiv.appendChild(monthBlock);
    }

    // Render list of holidays
    // Sort holidays by date for the list view
    data.sort((a, b) => new Date(a.holidayDate || a.date) - new Date(b.holidayDate || b.date));

    data.forEach(h => {
        const li = document.createElement("li");
        const date = h.holidayDate || h.date;
        const name = h.name || h.Holiday_Name__c || 'Unnamed Holiday';
        const type = h.type || h.type || 'General'; // Use 'type' if available, else fallback

        // Apply aesthetic Tailwind classes to list items
        li.className = "bg-gray-50 p-4 rounded-xl shadow-sm hover:bg-gray-100 transition-all duration-200 flex items-center border-l-8 border-purple-400";

        li.innerHTML = `
            <strong class="text-purple-800 mr-2 min-w-[100px]">${date}</strong>
            <span class="text-gray-800">${name}</span>
            <span class="text-sm text-gray-500 ml-auto">(${type})</span>
        `;
        listEl.appendChild(li);
    });
};


// Initialize the public holidays page after the DOM is loaded
document.addEventListener("DOMContentLoaded", () => {
    initPublicHolidays();
});
