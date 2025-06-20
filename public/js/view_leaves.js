// public/js/view_leaves.js

// Global variable to hold the promise resolvers for the confirm modal
let _confirmResolver;

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
 * Shows a custom confirmation modal.
 * @param {string} message The message to display in the confirmation modal.
 * @returns {Promise<boolean>} A promise that resolves to true if confirmed, false otherwise.
 */
function showConfirmModal(message) {
    const confirmModal = document.getElementById("confirmModal");
    const confirmModalBody = document.getElementById("confirmModalBody");
    const confirmBtn = confirmModal.querySelector('.bg-red-600');

    confirmModalBody.textContent = message;

    return new Promise((resolve) => {
        _confirmResolver = resolve; // Store the resolve function globally
        confirmModal.classList.remove("hidden");

        // Set up click handlers for confirm and cancel buttons within the modal
        confirmBtn.onclick = () => {
            _confirmResolver(true);
            closeConfirmModal(true); // Close the modal
        };
        confirmModal.querySelector('.bg-gray-300').onclick = () => {
            _confirmResolver(false);
            closeConfirmModal(false); // Close the modal
        };
    });
}

/**
 * Closes the confirmation modal.
 * @param {boolean} result The result to pass to the stored resolver.
 */
function closeConfirmModal(result) {
    const confirmModal = document.getElementById("confirmModal");
    confirmModal.classList.add("hidden");
    if (_confirmResolver) {
        _confirmResolver(result);
        _confirmResolver = null; // Clear the resolver
    }
}


/**
 * Initialize the “View Leave Requests” table:
 * - Fetches all leave requests for the current student
 * - Renders each row with a conditional Withdraw button
 */
window.initViewLeaves = async function() {
    console.log("✅ initViewLeaves()");
    const tbody = document.querySelector("#leavesTable tbody");
    const errBox = document.getElementById("viewLeavesError");
    const loadingOverlay = document.getElementById("loadingOverlay");

    // Show loading overlay
    if (loadingOverlay) {
        loadingOverlay.classList.remove('hidden');
    }

    // Clear previous content
    tbody.innerHTML = "";
    errBox.textContent = "";

    let data;
    try {
        const res = await fetch("../api/get_leave_requests.php");
        if (!res.ok) {
            const errorText = await res.text();
            throw new Error(`Status ${res.status}: ${errorText || res.statusText}`);
        }
        data = await res.json();
        console.log("📥 leave requests:", data);
    } catch (e) {
        console.error("❌ Failed to load leave requests", e);
        errBox.textContent = `Error loading leave requests: ${e.message}`;
        if (loadingOverlay) loadingOverlay.classList.add('hidden');
        return;
    } finally {
        // Hide loading overlay regardless of success or failure
        if (loadingOverlay) {
            loadingOverlay.classList.add('hidden');
        }
    }


    // No data
    if (data.length === 0) {
        tbody.innerHTML = `
            <tr class="bg-white">
                <td colspan="6" class="text-center py-6 text-gray-600 italic">
                    You haven’t applied for any leave yet.
                </td>
            </tr>`;
        return;
    }

    // Render each request
    data.forEach(r => {
        // Calculate number of days (inclusive) - client side, for display only
        const days = Math.floor((new Date(r.toDate) - new Date(r.fromDate)) / 86400000) + 1;

        // Determine status class for styling
        let statusClass = '';
        switch (r.status) {
            case 'Pending':
                statusClass = 'status-pending';
                break;
            case 'Approved':
                statusClass = 'status-approved';
                break;
            case 'Rejected':
                statusClass = 'status-rejected';
                break;
            case 'Withdrawn':
                statusClass = 'status-withdrawn';
                break;
            default:
                statusClass = 'text-gray-500';
        }


        const tr = document.createElement("tr");
        tr.classList.add("bg-white", "hover:bg-gray-50", "transition-colors", "duration-150"); // Add row styling

        tr.innerHTML = `
            <td class="py-3 px-6 text-left">${r.fromDate}</td>
            <td class="py-3 px-6 text-left">${r.toDate}</td>
            <td class="py-3 px-6 text-left">${r.reason || 'N/A'}</td>
            <td class="py-3 px-6 text-left">
                <span class="${statusClass}">${r.status}</span>
            </td>
            <td class="py-3 px-6 text-left">${r.createdDate}</td>
            <td class="py-3 px-6 text-center">
                ${
                    // Only allow withdraw when status is NOT Approved and NOT already Withdrawn
                    (r.status !== 'Approved' && r.status !== 'Withdrawn' && r.status !== 'Rejected')
                        ? `<button class="px-4 py-2 bg-red-600 text-white font-semibold rounded-lg shadow-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-200 text-sm"
                                  onclick="withdrawLeave(
                                      '${r.id}',
                                      '${r.typeId}',
                                      '${r.fromDate}',
                                      '${r.toDate}',
                                      this // Pass button element
                                  )">
                               Withdraw
                           </button>`
                        : `<span class="text-gray-500 italic text-sm">Action Not Available</span>`
                }
            </td>`;
        tbody.appendChild(tr);
    });
};


/**
 * Called when the user clicks Withdraw.
 * Sends a POST to withdraw_leave.php, then reloads both views.
 */
async function withdrawLeave(recordId, typeId, fromDate, toDate, btn) {
    const confirmMessage = "Are you sure you want to withdraw this leave request? This action cannot be undone.";
    const confirmed = await showConfirmModal(confirmMessage); // Use custom confirm modal

    if (!confirmed) {
        return; // User cancelled
    }

    // Prepare button for loading state
    const originalButtonText = btn.textContent;
    btn.innerHTML = `<div class="spinner border-2 border-white border-t-2"></div>`; // Small spinner inside button
    btn.disabled = true;
    btn.classList.add('opacity-75', 'cursor-not-allowed'); // Add disabled styling

    try {
        const res = await fetch("../api/withdraw_leave.php", {
            method: "POST",
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ recordId, typeId, fromDate, toDate })
        });
        const result = await res.json();

        if (result.success) {
            showMessageModal(result.message, 'success');
            // Refresh both sections (view leaves and apply leave)
            initViewLeaves(); // Refresh current page
            // Check if initApplyLeaveForm is defined and refresh it too
            if (typeof initApplyLeaveForm === 'function') {
                initApplyLeaveForm();
            }
        } else {
            showMessageModal(result.message, 'error');
        }
    } catch (e) {
        console.error("❌ withdraw failed", e);
        showMessageModal("Network error or server issue withdrawing leave.", 'error');
    } finally {
        // Reset button state regardless of success/failure
        btn.innerHTML = originalButtonText;
        btn.disabled = false;
        btn.classList.remove('opacity-75', 'cursor-not-allowed');
    }
}
