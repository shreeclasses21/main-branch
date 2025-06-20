// public/js/apply_leave.js

let assignments = {}; // { [typeId]: { used, allowed } }

window.initApplyLeaveForm = async function() {
 console.log("✅ initApplyLeaveForm()");

 // Elements
 const leaveTypeSel = document.getElementById("leave_type");
 const form     = document.getElementById("leaveForm");
 const submitBtn  = form.querySelector("button[type=submit]");
 const submitButtonText = document.getElementById("submitButtonText"); // Added for spinner
 const submitSpinner = document.getElementById("submitSpinner"); // Added for spinner
 const fromInput  = document.getElementById("from_date");
 const toInput   = document.getElementById("to_date");
 const dateErrorBox = document.getElementById("dateError");
 const leaveDaysInput = document.getElementById("leave_days"); // Added for total days calculation
  const loadingOverlay = document.getElementById("loadingOverlay"); // Added for page load spinner

 // Date validation and calculation on change
 fromInput.addEventListener("change", () => {
    validateDates();
    calculateDays(); // Calculate days when dates change
});
 toInput .addEventListener("change", () => {
    validateDates();
    calculateDays(); // Calculate days when dates change
});

 // 1) Load leave types
 let types;
 try {
  const r = await fetch("../api/get_leave_types.php");
  types = await r.json();
 } catch (e) {
  console.error("❌ Failed to load types", e);
  leaveTypeSel.innerHTML = `<option disabled>Error loading types</option>`;
  return; // Exit if types can't be loaded (critical for form)
 }
 leaveTypeSel.innerHTML = types.map(t=>`<option value="${t.id}">${t.name}</option>`).join("");

 // 2) Fetch assignments
 await Promise.all(types.map(t=>fetchAssignment(t.id)));

 // 3) Draw both charts
 const sickTypeId = types.find(t=>/sick/i.test(t.name))?.id;
 const vacTypeId = types.find(t=>/vacation/i.test(t.name))?.id;

 // Pass primary colors for aesthetic charts
 if (sickTypeId) {
    await drawDoughnut("chart-sick", sickTypeId, "sick-used", "sick-allowed", "#8B5CF6"); // Purple for sick
} else {
    document.getElementById("chart-sick").closest('.flex-col').innerHTML = "<p class='text-red-500 text-center'>Sick leave data unavailable.</p>";
}
if (vacTypeId) {
    await drawDoughnut("chart-vacation", vacTypeId, "vac-used", "vac-allowed", "#3B82F6"); // Blue for vacation
} else {
    document.getElementById("chart-vacation").closest('.flex-col').innerHTML = "<p class='text-red-500 text-center'>Vacation leave data unavailable.</p>";
}

 // 4) Form‐state on type change
 leaveTypeSel.addEventListener("change", updateFormState);
 updateFormState();

 // Hide the loading overlay once everything is loaded
 if (loadingOverlay) {
    loadingOverlay.classList.add('hidden');
 }

 // 5) Submit handler
 form.addEventListener("submit", async e => {
  e.preventDefault();

  // date re-validation
  if (validateDates()) return;

  // remaining check
  const typeId = leaveTypeSel.value;
  const { used=0, allowed=0 } = assignments[typeId];
  const remaining = Math.max(allowed - used, 0);
  const daysRequested = parseInt(leaveDaysInput.value, 10) || 0;

    if (daysRequested === 0) { // New check: must request at least one day
        return showMessageModal("Please select valid 'From' and 'To' dates to calculate leave days.", "error");
    }

  if (daysRequested > remaining) {
   return showMessageModal(`You have only ${remaining} day(s) left, but requested ${daysRequested} day(s).`, "error");
  }
  if (remaining === 0) {
   return showMessageModal(`All your ${leaveTypeSel.selectedOptions[0].text} days are used.`, "info"); // Changed type to info
  }

    // Show loading spinner and disable button
    submitButtonText.classList.add('hidden');
    submitSpinner.classList.remove('hidden');
    submitBtn.disabled = true;

  try {
   const r = await fetch("../api/push_leave.php", {
    method: "POST",
    body: new FormData(form)
   });
   const txt = await r.text();
   if (r.ok && txt.includes("✅")) { // Check for successful HTTP status and content
    showMessageModal("Leave request submitted successfully!", "success"); // Use new modal
    form.reset();
        calculateDays(); // Reset total days input after form reset
    await fetchAssignment(typeId);
    await drawDoughnut(
     typeId===sickTypeId?"chart-sick":"chart-vacation",
     typeId,
     typeId===sickTypeId?"sick-used":"vac-used",
     typeId===sickTypeId?"sick-allowed":"vac-allowed",
            typeId===sickTypeId?"#8B5CF6":"#3B82F6" // Pass primary color
    );
    updateFormState();
   } else {
        const errorMessage = txt.trim() === '' ? `Server error: ${r.status} ${r.statusText}` : txt;
    showMessageModal(errorMessage, "error"); // Use new modal
   }
  } catch (err) {
   console.error("❌ submit error", err);
   showMessageModal(`An error occurred: ${err.message}`, "error"); // Use new modal
  } finally {
        // Hide loading spinner and re-enable button
        submitButtonText.classList.remove('hidden');
        submitSpinner.classList.add('hidden');
        submitBtn.disabled = false;
    }
 });
};


// —————————————————————————————————————————————
// DATE VALIDATION
// Returns true if there *is* an error (and shows it).
function validateDates() {
 const fromVal = document.getElementById("from_date").value;
 const toVal  = document.getElementById("to_date").value;
 const dateErrorBox = document.getElementById("dateError");
 const submitBtn  = document.querySelector("#leaveForm button[type=submit]");
 dateErrorBox.textContent = "";

 if (!fromVal || !toVal) {
  submitBtn.disabled = false;
  return false;
 }

 const from = new Date(fromVal);
 const to  = new Date(toVal);
 const today = new Date();
 today.setHours(0,0,0,0);

 if (from < today) {
  dateErrorBox.textContent = "❌ You can’t apply for leave in the past.";
  submitBtn.disabled = true;
  return true;
 }
 if (to < from) {
  dateErrorBox.textContent = "❌ Your “To” date must be on or after the “From” date.";
  submitBtn.disabled = true;
  return true;
 }

 // valid
 submitBtn.disabled = false;
 return false;
}

// —————————————————————————————————————————————
// Calculate Leave Days (NEW FUNCTION)
function calculateDays() {
    const fromVal = document.getElementById("from_date").value;
    const toVal = document.getElementById("to_date").value;
    const leaveDaysInput = document.getElementById("leave_days");

    if (fromVal && toVal) {
        const from = new Date(fromVal);
        const to = new Date(toVal);

        if (to >= from) {
            const diffTime = Math.abs(to.getTime() - from.getTime());
            // Add 1 to include both start and end day
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            leaveDaysInput.value = diffDays;
        } else {
            leaveDaysInput.value = 0;
        }
    } else {
        leaveDaysInput.value = 0;
    }
}


// —————————————————————————————————————————————
// remaining functions (modified for aesthetic updates)

async function fetchAssignment(typeId) {
 try {
  const r = await fetch(`../api/get_leave_assignment.php?type_id=${typeId}`);
  const d = await r.json();
  assignments[typeId] = { used:d.used||0, allowed:d.allowed||0 };
 } catch (e) {
  assignments[typeId] = { used:0, allowed:0 };
    console.error("Error fetching assignment for typeId:", typeId, e);
 }
}

// drawDoughnut now accepts a primaryColor argument for dynamic chart colors
async function drawDoughnut(canvasId, typeId, usedId, allowedId, primaryColor) {
 const { used, allowed } = assignments[typeId];
 document.getElementById(usedId).textContent  = used;
 document.getElementById(allowedId).textContent = allowed;
 const rem = Math.max(allowed - used, 0);
 const ctx = document.getElementById(canvasId).getContext("2d");

    // Destroy existing chart instance to prevent errors on re-draw
    if (Chart.getChart(canvasId)) {
        Chart.getChart(canvasId).destroy();
    }

 new Chart(ctx, {
  type: "doughnut",
  data: {
        labels:["Used","Remaining"],
        datasets:[{
            data:[used,rem],
            backgroundColor:[primaryColor,"#E5E7EB"], // Use primaryColor and a lighter gray for remaining
            borderColor: ["white", "white"], // Added white border for separation
            borderWidth: 2
        }]
    },
  options:{
        cutout:"70%",
        plugins:{
            legend:{display:false},
            tooltip:{enabled:false} // Disabled tooltips for cleaner look
        },
        responsive: true,
        maintainAspectRatio: false, // Allow canvas to resize freely
        animation: { // Added animations for a smoother feel
            animateRotate: true,
            animateScale: true
        }
    }
 });
}

function updateFormState() {
 const sel = document.getElementById("leave_type");
 const { used=0, allowed=0 } = assignments[sel.value] || { used: 0, allowed: 0 }; // Handle case where assignment might be undefined
 const rem = Math.max(allowed - used, 0);

 // allow select always
 sel.disabled = false;
 // disable only inputs, textarea, button
 const ctrls = Array.from(document.querySelectorAll("#leaveForm input:not(#leave_type), #leaveForm textarea, #leaveForm button[type=submit]"));
 ctrls.forEach(c=>c.disabled=false);

 if (rem === 0) {
  ctrls.forEach(c=>c.disabled=true);
  sel.disabled = false; // Keep the select enabled so user can change type
  showMessageModal(`All your ${sel.selectedOptions[0].text} days are used. You cannot apply for this leave type.`, "info"); // Changed to modal
 } else {
  // document.getElementById("responseMsg").textContent = ""; // Removed, replaced by modal
 }
    // Ensure submit button is re-enabled if valid dates and remaining days > 0
    if (!validateDates() && rem > 0) {
        document.querySelector("#leaveForm button[type=submit]").disabled = false;
    }
}

// Function to show custom message modal (NEW FUNCTION - replaces showToast and responseBox logic)
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
    // Prepend the new icon to the title
    modalTitle.innerHTML = `<svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">${iconPath}</svg> ${titleText}`;

    modal.classList.remove("hidden");
}

// Function to close custom message modal (NEW FUNCTION - replaces closeToast)
function closeModal() {
 document.getElementById("messageModal").classList.add("hidden");
}
