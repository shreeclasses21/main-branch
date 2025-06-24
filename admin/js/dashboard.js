function initManageStudents() {
  loadStudents();

  // Add Student Submit
  const addForm = document.getElementById("addStudentForm");
  if (addForm) {
    addForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(addForm);
      const data = Object.fromEntries(formData.entries());

      const res = await fetch("api/add_student.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
      });

      const result = await res.json();
      if (result.status === "success") {
        addForm.reset();
        loadStudents();
      } else {
        alert(result.error || "Failed to add student.");
      }
    });
  }

  // Attach Edit Submit
  const editForm = document.getElementById("editStudentForm");
  if (editForm) {
    editForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const data = Object.fromEntries(new FormData(editForm).entries());

      const res = await fetch("api/update_student.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
      });

      const result = await res.json();
      if (result.status === "success") {
        closeEditModal();
        loadStudents();
      } else {
        alert(result.error || "Failed to update.");
      }
    });
  }

  // Register openEditModal
  window.openEditModal = function (student) {
    const modal = document.getElementById("editModal");
    if (!modal) return;

    modal.classList.remove("hidden");

    // Set all fields (same as before)
    document.getElementById("editId").value = student.id || '';
    document.getElementById("editFirst").value = student.first_name || '';
    document.getElementById("editLast").value = student.last_name || '';
    document.getElementById("editEmail").value = student.email || '';
    document.getElementById("editStudentId").value = student.student_id || '';
    document.getElementById("editMobile").value = student.mobile || '';
    document.getElementById("editPhone").value = student.phone || '';
    document.getElementById("editHomePhone").value = student.home_phone || '';
    document.getElementById("editBirthdate").value = student.birthdate || '';
    document.getElementById("editSection").value = student.section || '';
    document.getElementById("editBoard").value = student.board || '';
    document.getElementById("editGuardian").value = student.guardian_name || '';
    document.getElementById("editStreet").value = student.mailing_street || '';
    document.getElementById("editCity").value = student.mailing_city || '';
    document.getElementById("editState").value = student.mailing_state || '';
    document.getElementById("editPostal").value = student.mailing_postal || '';
    document.getElementById("editCountry").value = student.mailing_country || '';
    document.getElementById("editStatus").value = student.status || '';
  };
}



async function loadStudents() {
    const res = await fetch("api/fetch_students.php");
    const students = await res.json();

    const table = document.getElementById("studentTableBody");
    if (!table) return;

    table.innerHTML = students.map(s => `
        <tr class="border-t">
            <td class="p-2">${s.id}</td>
            <td class="p-2">${s.student_id}</td>
            <td class="p-2">${s.first_name} ${s.last_name}</td>
            <td class="p-2">${s.email}</td>
            <td class="p-2">${s.mobile || '-'}</td>
            <td class="p-2">${s.section || '-'}</td>
            <td class="p-2">${s.board || '-'}</td>
            <td class="p-2">${s.guardian_name || '-'}</td>
            <td class="p-2 font-semibold ${s.status === 'Active' ? 'text-green-600' : 'text-red-500'}">${s.status}</td>
            <td class="p-2">${s.created_at}</td>
            <td class="p-2 space-x-2">
                <button class="text-blue-600 underline" onclick="openEditModal(${JSON.stringify(s).replace(/"/g, '&quot;')})">Edit</button>
                <button class="text-red-600 underline" onclick="deleteStudent('${s.id}')">Delete</button>
            </td>
        </tr>
    `).join('');
}


function openEditModal(student) {
  document.getElementById("editModal").classList.remove("hidden");

  document.getElementById("editId").value = student.id;
  document.getElementById("editFirst").value = student.first_name || '';
  document.getElementById("editLast").value = student.last_name || '';
  document.getElementById("editEmail").value = student.email || '';
  document.getElementById("editStudentId").value = student.student_id || '';

  document.getElementById("editMobile").value = student.mobile || '';
  document.getElementById("editPhone").value = student.phone || '';
  document.getElementById("editHomePhone").value = student.home_phone || '';

  document.getElementById("editBirthdate").value = student.birthdate || '';

  document.getElementById("editSection").value = student.section || '';
  document.getElementById("editBoard").value = student.board || '';
  document.getElementById("editGuardian").value = student.guardian_name || '';

  document.getElementById("editStreet").value = student.mailing_street || '';
  document.getElementById("editCity").value = student.mailing_city || '';
  document.getElementById("editState").value = student.mailing_state || '';
  document.getElementById("editPostal").value = student.mailing_postal || '';
  document.getElementById("editCountry").value = student.mailing_country || '';

  document.getElementById("editStatus").value = student.status || '';
}

function closeEditModal() {
  const modal = document.getElementById("editModal");
  if (modal) modal.classList.add("hidden");
}


// Edit form submit
document.addEventListener("DOMContentLoaded", () => {
  document.getElementById("editStudentForm")?.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(e.target);
    const data = Object.fromEntries(formData.entries());

    const res = await fetch("api/update_student.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(data)
    });

    const result = await res.json();
    if (result.status === "success") {
      closeEditModal();
      loadStudents();
    } else {
      alert(result.error || "Failed to update student.");
    }
  });
});


async function deleteStudent(id) {
  if (!confirm("Are you sure you want to delete this student?")) return;

  const res = await fetch("api/delete_student.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id })
  });

  const result = await res.json();
  if (result.status === "success") {
    loadStudents();
  } else {
    alert(result.error || "Failed to delete student.");
  }
}

function initManageLeaves() {
  loadLeaveRequests();
}

async function loadLeaveRequests() {
  try {
    const res = await fetch("api/fetch_leave_requests.php");
    
    if (!res.ok) throw new Error("Server returned an error");

    const data = await res.json(); // This line fails if response is HTML

    renderLeaveRequests(data);
  } catch (err) {
    console.error("❌ Failed to fetch leave requests:", err);
    alert("Something went wrong while loading leave requests.");
  }
}

async function updateLeaveStatus(id, status) {
  const res = await fetch("api/update_leave_status.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id, status })
  });

  const result = await res.json();
  if (result.status === "success") {
    loadLeaveRequests();
  } else {
    alert(result.error || "Update failed.");
  }
}


function renderLeaveRequests(requests) {
  const table = document.getElementById("leaveTableBody");
  if (!table) return;

  table.innerHTML = requests.map(req => {
    const statusColor = req.status === 'Approved' ? 'text-green-600'
                      : req.status === 'Rejected' ? 'text-red-600'
                      : 'text-yellow-600';

    const actionButtons = req.status === 'Pending' ? `
      <button class="text-green-600 underline" onclick="updateLeaveStatus(${req.id}, 'Approved')">Approve</button>
      <button class="text-red-600 underline" onclick="updateLeaveStatus(${req.id}, 'Rejected')">Reject</button>
    ` : '-';

    return `
      <tr class="border-t">
        <td class="p-2">${req.id}</td>
        <td class="p-2">${req.student_id}</td>
        <td class="p-2">${req.leave_type_id}</td>
        <td class="p-2">${req.from_date}</td>
        <td class="p-2">${req.to_date}</td>
        <td class="p-2">${req.reason || '-'}</td>
        <td class="p-2">
          ${req.supporting_document ? `<a href="${req.supporting_document}" target="_blank" class="text-blue-600 underline">View</a>` : '-'}
        </td>
        <td class="p-2 font-semibold ${statusColor}">${req.status || 'Pending'}</td>
        <td class="p-2">${req.created_at}</td>
        <td class="p-2 space-x-2">${actionButtons}</td>
      </tr>
    `;
  }).join('');
}

function initManageLeaveTypes() {
  loadLeaveTypes();

  // Add Leave Type
  const form = document.getElementById("addLeaveTypeForm");
  if (form) {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      const data = Object.fromEntries(new FormData(form).entries());

      const res = await fetch("api/add_leave_type.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
      });

      const result = await res.json();
      if (result.status === "success") {
        form.reset();
        loadLeaveTypes();
      } else {
        alert(result.error || "Failed to add leave type.");
      }
    });
  }

  // Edit Modal Submit
  const editForm = document.getElementById("editLeaveTypeForm");
  if (editForm) {
    editForm.addEventListener("submit", async (e) => {
      e.preventDefault();
      const data = Object.fromEntries(new FormData(editForm).entries());

      const res = await fetch("api/update_leave_type.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
      });

      const result = await res.json();
      if (result.status === "success") {
        closeEditLeaveModal();
        loadLeaveTypes();
      } else {
        alert(result.error || "Update failed.");
      }
    });
  }
}

async function loadLeaveTypes() {
  const res = await fetch("api/fetch_leave_types.php");
  const leaveTypes = await res.json();

  const table = document.getElementById("leaveTypeTableBody");
  if (!table) return;

  table.innerHTML = leaveTypes.map(l => `
    <tr class="border-t">
      <td class="p-2">${l.id}</td>
      <td class="p-2">${l.name}</td>
      <td class="p-2">${l.description || '-'}</td>
      <td class="p-2">${l.allowed_per_month}</td>
      <td class="p-2 space-x-2">
        <button class="text-blue-600 underline" onclick='openEditLeaveModal(${JSON.stringify(l)})'>Edit</button>
        <button class="text-red-600 underline" onclick='deleteLeaveType(${l.id})'>Delete</button>
      </td>
    </tr>
  `).join('');
}

function openEditLeaveModal(leaveType) {
  document.getElementById("editLeaveTypeModal").classList.remove("hidden");

  document.getElementById("editLeaveId").value = leaveType.id;
  document.getElementById("editLeaveName").value = leaveType.name;
  document.getElementById("editLeaveAllowed").value = leaveType.allowed_per_month;
  document.getElementById("editLeaveDesc").value = leaveType.description || '';
}

function closeEditLeaveModal() {
  document.getElementById("editLeaveTypeModal").classList.add("hidden");
}

async function deleteLeaveType(id) {
  if (!confirm("Are you sure you want to delete this leave type?")) return;

  const res = await fetch("api/delete_leave_type.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id })
  });

  const result = await res.json();
  if (result.status === "success") {
    loadLeaveTypes();
  } else {
    alert(result.error || "Failed to delete leave type.");
  }
}

function initManageLeaveAssignments() {
  loadLeaveAssignments();

  // Handle edit submit
  const form = document.getElementById("editAssignmentForm");
  if (form) {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      const data = Object.fromEntries(new FormData(form).entries());

      const res = await fetch("api/update_leave_assignment.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });

      const result = await res.json();
      if (result.status === "success") {
        closeAssignmentModal();
        loadLeaveAssignments();
      } else {
        alert(result.error || "Update failed.");
      }
    });
  }
}

// Open Edit Modal
function openAssignmentModal(item) {
  const modal = document.getElementById("editAssignmentModal");
  modal.classList.remove("hidden");

  document.getElementById("assignId").value = item.id;
  document.getElementById("assignStudent").value = item.student_id;
  document.getElementById("assignLeaveType").value = item.leave_type_id;
  document.getElementById("assignYear").value = item.year;
  document.getElementById("assignMonth").value = item.month;
  document.getElementById("assignAllowed").value = item.allowed;
  document.getElementById("assignUsed").value = item.used;
  document.getElementById("assignRemaining").value = item.remaining;
}

// Close modal
function closeAssignmentModal() {
  document.getElementById("editAssignmentModal").classList.add("hidden");
}

// Delete
async function deleteLeaveAssignment(id) {
  if (!confirm("Are you sure you want to delete this assignment?")) return;

  const res = await fetch("api/delete_leave_assignment.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id })
  });

  const result = await res.json();
  if (result.status === "success") {
    loadLeaveAssignments();
  } else {
    alert(result.error || "Delete failed.");
  }
}

function renderLeaveAssignments(assignments) {
  const table = document.getElementById("assignmentTableBody");
  if (!table) return;

  table.innerHTML = assignments.map(row => `
    <tr class="border-t">
      <td class="p-2">${row.id}</td>
      <td class="p-2">${row.student_id}</td>
      <td class="p-2">${row.student_name || '-'}</td>
      <td class="p-2">${row.leave_type || '-'}</td>
      <td class="p-2">${row.month}</td>
      <td class="p-2">${row.year}</td>
      <td class="p-2">${row.allowed}</td>
      <td class="p-2">${row.used}</td>
      <td class="p-2">${row.remaining}</td>
      <td class="p-2">${row.created_at}</td>
      <td class="p-2 space-x-2">
        <button class="text-blue-600 underline" onclick="openAssignmentModal(${JSON.stringify(row).replace(/"/g, '&quot;')})">Edit</button>
        <button class="text-red-600 underline" onclick="deleteLeaveAssignment(${row.id})">Delete</button>
      </td>
    </tr>
  `).join('');
}


async function loadLeaveAssignments() {
  try {
    const res = await fetch("api/fetch_leave_assignments.php");
    if (!res.ok) throw new Error("Failed to load assignments");

    const rows = await res.json();
    renderLeaveAssignments(rows);
  } catch (err) {
    console.error("❌ Failed to fetch leave assignments:", err);
    alert("Error loading assignments.");
  }
}

async function generateMonthlyAssignments() {
  const btn = event.target;
  const status = document.getElementById("genStatus");
  
  btn.disabled = true;
  btn.textContent = "⏳ Generating...";
  status.textContent = "";

  try {
    const res = await fetch("api/assign_monthly_leaves.php");
    const text = await res.text();

    status.textContent = text;
    loadLeaveAssignments(); // 🔄 Reload updated assignments
  } catch (err) {
    status.textContent = "❌ Failed to generate assignments.";
    console.error("Error:", err);
  }

  btn.disabled = false;
  btn.textContent = "➕ Generate Monthly Assignments";
}

function initPublicHolidays() {
  loadPublicHolidays();

  // Save (Add/Edit) Holiday Form
  const form = document.getElementById("holidayForm");
  if (form) {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      const data = Object.fromEntries(new FormData(form).entries());

      const res = await fetch("api/save_holiday.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
      });

      const result = await res.json();
      if (result.status === "success") {
        form.reset();
        closeHolidayModal();
        loadPublicHolidays();
      } else {
        alert(result.error || "Save failed.");
      }
    });
  }

  // Bulk Upload CSV Handler
  const uploadForm = document.getElementById("uploadCsvForm");
  if (uploadForm) {
    uploadForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const formData = new FormData(uploadForm);
      const fileInput = document.getElementById("holidayCsvFile");
      if (!fileInput.files.length) {
        alert("Please select a CSV file.");
        return;
      }

      try {
        const res = await fetch("api/bulk_upload_holidays.php", {
          method: "POST",
          body: formData
        });

        const result = await res.json();
        if (result.status === "success" || result.status === "partial_success") {
          alert(result.message);
          uploadForm.reset();
          loadPublicHolidays(); // Refresh table
        } else {
          alert("❌ Error: " + (result.error || "Upload failed"));
        }
      } catch (err) {
        console.error("CSV Upload Error:", err);
        alert("❌ Upload failed. Please try again.");
      }
    });
  }
}




async function loadPublicHolidays() {
  try {
    const res = await fetch("api/fetch_holidays.php");
    const holidays = await res.json();

    const table = document.getElementById("holidayTableBody");
    table.innerHTML = holidays.map(h => `
      <tr class="border-t">
        <td class="p-2">${h.id}</td>
        <td class="p-2">${h.holiday_date}</td>
        <td class="p-2">${h.name}</td>
        <td class="p-2">${h.type || '-'}</td>
        <td class="p-2">${h.year}</td>
        <td class="p-2 space-x-2">
         <button onclick='editHoliday(this)' data-holiday='${JSON.stringify(h).replace(/'/g, "&apos;")}' class="text-blue-600 underline">Edit</button>
          <button onclick='deleteHoliday(${h.id})' class="text-red-600 underline">Delete</button>
        </td>
      </tr>
    `).join('');
  } catch (err) {
    alert("Failed to load public holidays.");
    console.error(err);
  }
}

function editHoliday(button) {
  const rawData = button.getAttribute('data-holiday').replace(/&apos;/g, "'");
  const h = JSON.parse(rawData);

  document.getElementById("holidayId").value = h.id;
  document.getElementById("holidayDate").value = h.holiday_date;
  document.getElementById("holidayName").value = h.name;
  document.getElementById("holidayType").value = h.type || '';
  document.getElementById("holidayYear").value = h.year;

  openHolidayModal();
}


async function deleteHoliday(id) {
  if (!confirm("Are you sure you want to delete this holiday?")) return;

  const res = await fetch("api/delete_holiday.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id })
  });

  const result = await res.json();
  if (result.status === "success") {
    loadPublicHolidays();
  } else {
    alert(result.error || "Delete failed.");
  }
}

function openHolidayModal() {
  document.getElementById("holidayModal").classList.remove("hidden");
}

function closeHolidayModal() {
  document.getElementById("holidayModal").classList.add("hidden");
}

function initAttendanceLog() {
  fetch('api/fetch_attendance_log.php')
    .then(res => res.json())
    .then(data => {
      const tableBody = document.getElementById('attendanceLogTable');
      tableBody.innerHTML = '';

      data.forEach(entry => {
        const row = `
          <tr class="border-t">
            <td class="px-4 py-2">${entry.student_id}</td>
            <td class="px-4 py-2">${entry.full_name || 'N/A'}</td>
            <td class="px-4 py-2">${entry.attendance_date}</td>
            <td class="px-4 py-2">${entry.type}</td>
          </tr>
        `;
        tableBody.insertAdjacentHTML('beforeend', row);
      });
    })
    .catch(err => {
      console.error("Failed to fetch attendance logs:", err);
    });
}

function initManageRegularizations() {
  const filterInput = document.getElementById('studentNameFilter');

  function fetchData(filter = '') {
    fetch(`api/admin_get_regularizations.php?name=${encodeURIComponent(filter)}`)
      .then(res => res.json())
      .then(data => renderTable(data));
  }

  function renderTable(data) {
    const container = document.getElementById('regularizationAdminTable');

    if (data.length === 0) {
      container.innerHTML = "<p class='text-gray-600'>No matching requests.</p>";
      return;
    }

    const rows = data.map(req => `
      <tr class="border-t">
        <td class="p-2">${req.full_name || req.student_id}</td>
        <td class="p-2">${req.requested_date}</td>
        <td class="p-2">${req.reason}</td>
        <td class="p-2">${req.status}</td>
        <td class="p-2">${req.created_at}</td>
        <td class="p-2">
          ${req.status === 'Pending' ? `
            <button onclick="approveRequest(${req.id}, '${req.student_id}', '${req.requested_date}')" class="text-green-700 hover:underline">Approve</button> |
            <button onclick="rejectRequest(${req.id})" class="text-red-700 hover:underline">Reject</button>
          ` : `<span class="text-gray-400 italic">Handled</span>`}
        </td>
      </tr>
    `).join('');

    container.innerHTML = `
      <table class="w-full border text-sm">
        <thead class="bg-gray-100 text-gray-700">
          <tr><th class="p-2">Student</th><th class="p-2">Date</th><th class="p-2">Reason</th><th class="p-2">Status</th><th class="p-2">Created</th><th class="p-2">Action</th></tr>
        </thead>
        <tbody>${rows}</tbody>
      </table>
    `;
  }

  // Load initial data
  fetchData();

  // Filter as you type
  filterInput.addEventListener('input', e => {
    fetchData(e.target.value);
  });
}


function approveRequest(id, student_id, requested_date) {
  fetch('api/approve_regularization.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ request_id: id, student_id, requested_date })
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      alert("✅ Approved!");
      initManageRegularizations(); // reload
    } else {
      alert("❌ Error: " + data.error);
    }
  });
}

function rejectRequest(id) {
  fetch('api/reject_regularization.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ request_id: id })
  })
  .then(res => res.json())
  .then(data => {
    if (data.status === 'success') {
      alert("❌ Rejected.");
      initManageRegularizations(); // reload
    } else {
      alert("Error: " + data.error);
    }
  });
}
function approveAllRequests() {
  if (!confirm("Are you sure you want to approve ALL pending requests?")) return;

  fetch('api/admin_approve_all_regularizations.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' }
  })
    .then(res => res.json())
    .then(data => {
      if (data.status === 'success') {
        alert(`✅ ${data.approved_count} requests approved.`);
        initManageRegularizations();
      } else {
        alert(`❌ ${data.error}`);
      }
    });
}

function initManageInvoices() {
  const form = document.getElementById('invoiceForm');
  const msg = document.getElementById('invoiceMessage');
  const list = document.getElementById('invoiceList');
  const dropdown = document.getElementById('studentDropdown');

  // ✅ Load students
  async function loadStudents() {
    try {
      const res = await fetch('api/fetch_students.php');
      const json = await res.json();

      dropdown.innerHTML = '<option value="">Select Student</option>';
      json.forEach(student => {
        const option = document.createElement('option');
        option.value = student.id; // internal ID
        option.textContent = `${student.first_name} ${student.last_name}`;
        option.dataset.id = student.id; // internal DB id
        option.dataset.studentId = student.student_id; // optional, public ID
        option.dataset.name = `${student.first_name} ${student.last_name}`;
        option.dataset.email = student.email;
        option.dataset.phone = student.mobile;
        dropdown.appendChild(option);
      });

      dropdown.addEventListener('change', () => {
        const selected = dropdown.options[dropdown.selectedIndex];
        document.getElementById('id').value = selected.dataset.id || '';
        document.getElementById('student_name').value = selected.dataset.name || '';
        document.getElementById('email').value = selected.dataset.email || '';
        document.getElementById('phone').value = selected.dataset.phone || '';
      });
    } catch (err) {
      dropdown.innerHTML = '<option>Error loading students</option>';
    }
  }

  // ✅ Submit Invoice
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    msg.textContent = '';

    const data = {
      student_id: form.id.value,  // internal DB id
      student_name: form.student_name.value,
      email: form.email.value,
      phone: form.phone.value,
      month: form.month.value.split("-")[1],
      year: form.month.value.split("-")[0],
      amount: form.amount.value,
      status: form.status.value,
      type: form.payment_type.value
    };

    try {
      const res = await fetch('api/add_invoice.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
      });

      const json = await res.json();
      msg.textContent = json.message || json.error;
      msg.className = json.status === 'success' ? 'text-green-600' : 'text-red-600';

      if (json.status === 'success') {
        form.reset();
        loadInvoices();
      }
    } catch (err) {
      msg.textContent = 'Failed to submit invoice.';
      msg.className = 'text-red-600';
    }
  });

  // ✅ Load invoices initially
  async function loadInvoices() {
    try {
      const res = await fetch('api/get_invoices.php');
      const json = await res.json();

      if (json.status === 'success') {
        list.innerHTML = `
          <table class="w-full text-sm border">
            <thead class="bg-gray-100">
              <tr>
                <th class="p-2">Student</th><th>Month</th><th>Amount</th><th>Status</th><th>Type</th><th>Created</th>
              </tr>
            </thead>
            <tbody>
              ${json.data.map(inv => `
                <tr class="border-t">
                  <td class="p-2">${inv.student_name}</td>
                  <td>${inv.month} ${inv.year}</td>
                  <td>₹${inv.amount}</td>
                  <td>${inv.status}</td>
                  <td>${inv.type}</td>
                  <td>${inv.created_at}</td>
                </tr>
              `).join('')}
            </tbody>
          </table>
        `;
      } else {
        list.innerHTML = `<p class="text-red-600">${json.error}</p>`;
      }
    } catch (err) {
      list.innerHTML = `<p class="text-red-600">Failed to load invoices.</p>`;
    }
  }

  loadStudents();
  loadInvoices();
}


document.addEventListener('DOMContentLoaded', function () {
    initUploadFiles(); // Initialize everything once DOM is ready
});

function initUploadFiles() {
    const gradeDropdown = document.getElementById('grade');
    const sectionDropdown = document.getElementById('section');
    const studentsDropdown = document.getElementById('students');
    const fileUploadForm = document.getElementById('fileUploadForm');

    if (!gradeDropdown || !sectionDropdown || !studentsDropdown || !fileUploadForm) {
        console.error('One or more required elements not found!');
        return;
    }

    // Initial load
    loadStudentsForUpload(gradeDropdown.value);
    updateSections(gradeDropdown.value);

    // Handle grade change
    gradeDropdown.addEventListener('change', () => {
        const selectedGrade = gradeDropdown.value;
        loadStudentsForUpload(selectedGrade);
        updateSections(selectedGrade);
    });

    // Handle file upload form submission
    fileUploadForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(fileUploadForm);
        const res = await fetch('api/upload_files.php', {
            method: 'POST',
            body: formData
        });

        const result = await res.json();
        const statusElement = document.getElementById('fileUploadStatus');

        if (result.status === 'success') {
            statusElement.innerHTML = '<p class="text-green-600">✅ Files uploaded successfully!</p>';
            fileUploadForm.reset();
        } else {
            statusElement.innerHTML = '<p class="text-red-600">❌ Error: ' + result.message + '</p>';
        }
    });
}

// Load students by grade
async function loadStudentsForUpload(grade) {
    try {
        const response = await fetch(`api/fetch_students_by_grade.php?grade=${encodeURIComponent(grade)}`);
        const students = await response.json();

        const studentsDropdown = document.getElementById('students');
        studentsDropdown.innerHTML = students.map(student => `
            <option value="${student.id}">${student.first_name} ${student.last_name}</option>
        `).join('');
    } catch (err) {
        console.error('Failed to load students:', err);
    }
}

async function updateSections(grade) {
    const sectionDropdown = document.getElementById('section');
    sectionDropdown.innerHTML = '<option disabled selected>Loading...</option>';

    try {
        const res = await fetch(`api/fetch_subjects_by_grade.php?grade=${encodeURIComponent(grade)}`);
        const subjects = await res.json();

        if (!Array.isArray(subjects) || subjects.length === 0) {
            sectionDropdown.innerHTML = `<option disabled selected>No subjects found</option>`;
            return;
        }

        sectionDropdown.innerHTML = subjects.map(subject => `
            <option value="${subject.subject_name}">${subject.subject_name}</option>
        `).join('');
    } catch (err) {
        console.error('Failed to load subjects:', err);
        sectionDropdown.innerHTML = `<option disabled selected>Error loading subjects</option>`;
    }
}



function initManageSubjects() {
  loadSubjects();

  const form = document.getElementById("addSubjectForm");
  if (form) {
    form.addEventListener("submit", async (e) => {
      e.preventDefault();
      const data = Object.fromEntries(new FormData(form).entries());

      const res = await fetch("api/add_subject.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data)
      });

      const result = await res.json();
      if (result.status === "success") {
        form.reset();
        loadSubjects();
      } else {
        alert(result.error || "Failed to add subject.");
      }
    });
  }
}

async function loadSubjects() {
  const res = await fetch("api/fetch_subjects.php");
  const subjects = await res.json();
  const list = document.getElementById("subjectList");
  if (!list) return;

  // 🧠 Group subjects by grade
  const grouped = {};
  subjects.forEach(sub => {
    if (!grouped[sub.grade]) grouped[sub.grade] = [];
    grouped[sub.grade].push(sub);
  });

  // 🧱 Build columns for each grade
  list.innerHTML = `
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
      ${Object.keys(grouped).map(grade => `
        <div class="bg-white border border-purple-200 shadow rounded-xl overflow-hidden">
          <div class="bg-purple-600 text-white px-4 py-3 font-bold text-lg rounded-t-xl">
            📘 Grade ${grade}
          </div>
          <div class="p-4">
            <ul class="divide-y divide-gray-200">
              ${grouped[grade].map(sub => `
                <li class="flex items-center justify-between py-2">
                  <span>${sub.subject_name}</span>
                  <button onclick="deleteSubject(${sub.id})" class="text-red-600 hover:underline text-sm">🗑️</button>
                </li>
              `).join('')}
            </ul>
          </div>
        </div>
      `).join('')}
    </div>
  `;
}


async function deleteSubject(id) {
  if (!confirm("Delete this subject?")) return;

  const res = await fetch("api/delete_subject.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ id })
  });

  const result = await res.json();
  if (result.status === "success") {
    loadSubjects();
  } else {
    alert("Failed to delete.");
  }
}



// js/admin_exams.js

// Initialize the admin panel functionality when the DOM is fully loaded
document.addEventListener('DOMContentLoaded', initExamAdmin);

function initExamAdmin() {
    // Load initial data
    loadExistingExams();
    loadExamDropdown();

    // 📅 Exam Creation Form Handler
    const examForm = document.getElementById('examForm');
    if (examForm) {
        examForm.addEventListener('submit', handleExamCreation);
    }

    // 📚 Subject Form Handler
    const addSubjectsForm = document.getElementById('addSubjectsForm');
    if (addSubjectsForm) {
        addSubjectsForm.addEventListener('submit', handleSubjectAddition);
    }

    // 🧠 Load subjects dynamically when an exam is selected in the dropdown
    const examDropdown = document.getElementById('examDropdown');
    if (examDropdown) {
        examDropdown.addEventListener('change', handleExamSelectionChange);
    }
}

/**
 * Handles the submission of the exam creation form.
 * @param {Event} e - The submit event object.
 */
async function handleExamCreation(e) {
    e.preventDefault(); // Prevent default form submission

    // Gather form data
    const payload = {
        title: document.getElementById('examTitle').value,
        grade: document.getElementById('examGrade').value,
        start_date: document.getElementById('examStartDate').value,
        end_date: document.getElementById('examEndDate').value,
        description: document.getElementById('examDescription').value,
        rules: document.getElementById('examRules').value,
        instructions: document.getElementById('examInstructions').value
    };

    const statusBox = document.getElementById('examCreationStatus');
    statusBox.innerHTML = `<p class="status-message">Creating exam...</p>`; // Loading message

    try {
        const res = await fetch('api/add_exam.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const result = await res.json();

        if (result.status === 'success') {
            statusBox.innerHTML = `<p class="status-message success">${result.message}</p>`;
            examForm.reset(); // Clear the form
            loadExistingExams(); // Refresh the list of exams
            loadExamDropdown(); // Refresh the exam dropdown for subject assignment
        } else {
            statusBox.innerHTML = `<p class="status-message error">Error: ${result.message}</p>`;
        }
    } catch (err) {
        console.error('Error creating exam:', err);
        statusBox.innerHTML = `<p class="status-message error">Error submitting exam: ${err.message}</p>`;
    }
}

/**
 * Handles the submission of the form to add subjects to an exam.
 * @param {Event} e - The submit event object.
 */
async function handleSubjectAddition(e) {
    e.preventDefault(); // Prevent default form submission

    // Gather form data
    const payload = {
        exam_id: document.getElementById('examDropdown').value,
        subject_name: document.getElementById('subjectDropdown').value,
        subject_date: document.getElementById('subjectDate').value
    };

    const statusBox = document.getElementById('subjectAddStatus');
    statusBox.innerHTML = `<p class="status-message">Adding subject...</p>`; // Loading message

    try {
        const res = await fetch('api/add_exam_subjects.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        const result = await res.json();

        if (result.status === 'success') {
            statusBox.innerHTML = `<p class="status-message success">${result.message}</p>`;
            addSubjectsForm.reset(); // Clear the form
        } else {
            statusBox.innerHTML = `<p class="status-message error">Error: ${result.message}</p>`;
        }
    } catch (err) {
        console.error('Error adding subject:', err);
        statusBox.innerHTML = `<p class="status-message error">Error submitting subject: ${err.message}</p>`;
    }
}

/**
 * Handles the change event on the exam dropdown, loading subjects for the selected exam.
 */
async function handleExamSelectionChange() {
    const examId = this.value;
    const subjectDropdown = document.getElementById('subjectDropdown');
    subjectDropdown.innerHTML = '<option value="">Loading subjects...</option>'; // Loading state for subjects

    if (!examId) {
        subjectDropdown.innerHTML = '<option value="">Select Exam First</option>';
        return;
    }

    try {
        const res = await fetch(`api/get_subjects_by_exam.php?exam_id=${examId}`);
        const data = await res.json();

        if (data.status === 'success') {
            if (data.subjects.length === 0) {
                subjectDropdown.innerHTML = '<option value="">No subjects found for this exam</option>';
                return;
            }

            // Populate the dropdown with fetched subjects
            subjectDropdown.innerHTML = '<option value="">Select Subject</option>';
            data.subjects.forEach(subject => {
                subjectDropdown.innerHTML += `<option value="${subject.subject_name}">${subject.subject_name}</option>`;
            });
        } else {
            subjectDropdown.innerHTML = `<option value="">Error loading subjects</option>`;
            console.error('Failed to load subjects:', data.message);
        }
    } catch (err) {
        console.error('Error fetching subjects for exam:', err);
        subjectDropdown.innerHTML = `<option value="">Error: ${err.message}</option>`;
    }
}

/**
 * Fetches and renders the list of existing exams.
 */
async function loadExistingExams() {
    const container = document.getElementById('existingExamsList');
    container.innerHTML = '<p style="text-align: center; color: #6b7280; padding: 15px;">Loading existing exams...</p>';

    try {
        const res = await fetch('api/fetch_exams.php');
        const data = await res.json();

        if (data.status === 'success') {
            if (data.exams.length === 0) {
                container.innerHTML = '<p class="text-gray-600" style="text-align: center; color: #6b7280; padding: 15px;">No exams found.</p>';
                return;
            }

            // Map exam data to HTML elements for display
            container.innerHTML = data.exams.map(exam => `
                <div class="existing-exam-item">
                    <div class="title">${exam.title} (<span style="font-weight: normal;">Grade: ${exam.grade}</span>)</div>
                    <div class="details"><strong>📅 Period:</strong> ${exam.start_date} to ${exam.end_date}</div>
                    <div class="details"><strong>📝 Description:</strong> ${exam.description || '<span class="na-text">N/A</span>'}</div>
                    <div class="details"><strong>📜 Rules:</strong> ${exam.rules || '<span class="na-text">N/A</span>'}</div>
                    <div class="details"><strong>📋 Instructions:</strong> ${exam.instructions || '<span class="na-text">N/A</span>'}</div>
                </div>
            `).join('');
        } else {
            container.innerHTML = `<p class="status-message error">Failed to load exams: ${data.message}</p>`;
            console.error('Failed to load exams:', data.message);
        }
    } catch (err) {
        console.error('Error loading existing exams:', err);
        container.innerHTML = `<p class="status-message error">Error: ${err.message}</p>`;
    }
}

/**
 * Fetches all exams and populates the 'Select Exam' dropdown for subject assignment.
 */
async function loadExamDropdown() {
    const dropdown = document.getElementById('examDropdown');
    dropdown.innerHTML = `<option value="">Loading Exams...</option>`; // Loading state

    try {
        const res = await fetch('api/fetch_exams.php');
        const data = await res.json();

        if (data.status === 'success') {
            if (data.exams.length === 0) {
                 dropdown.innerHTML = '<option value="">No Exams Available</option>';
                 return;
            }
            dropdown.innerHTML = '<option value="">Select Exam</option>'; // Default option
            data.exams.forEach(exam => {
                dropdown.innerHTML += `<option value="${exam.id}">${exam.title} (Grade: ${exam.grade})</option>`;
            });
        } else {
            dropdown.innerHTML = `<option value="">Failed to load exams</option>`;
            console.error('Failed to load exam dropdown:', data.message);
        }
    } catch (err) {
        console.error('Error loading exam dropdown:', err);
        dropdown.innerHTML = `<option value="">Error loading exams</option>`;
    }
}

// Ensure initExamAdmin runs after the DOM is fully loaded
// (already handled by document.addEventListener('DOMContentLoaded', initExamAdmin) at the top)
