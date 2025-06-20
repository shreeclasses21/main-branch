function initAttendanceRegularization() {
  const container = document.getElementById('calendarContainer');
  const selectedDates = new Set();
  const month = new Date().toISOString().slice(0, 7); // YYYY-MM

  fetch('../api/get_student_attendance.php')
    .then(res => res.json())
    .then(res => {
      if (res.status !== 'success') throw new Error(res.error);
      const data = res.data;
      renderCalendar(data);
    })
    .catch(err => {
      container.innerHTML = `<p class="text-red-600">Failed to load calendar: ${err.message}</p>`;
    });

  function renderCalendar(data) {
    const daysInMonth = new Date().getFullYear() === 2025 && new Date().getMonth() === 5 ? 30 : 31;
    const calendar = document.createElement('div');
    calendar.className = "grid grid-cols-7 gap-2";

    for (let day = 1; day <= daysInMonth; day++) {
      const dateStr = `${month}-${String(day).padStart(2, '0')}`;
      const status = data.find(row => row.attendance_date === dateStr)?.type || '';

      const box = document.createElement('div');
      box.className = "p-2 text-center border rounded cursor-pointer";

      // Visual tags
      if (status === 'Present') {
        box.classList.add("bg-green-100");
        box.innerHTML = `<div class="text-sm font-bold">${day}</div><div class="text-xs text-green-700">Present</div>`;
      } else if (status === 'On Leave') {
        box.classList.add("bg-yellow-100");
        box.innerHTML = `<div class="text-sm font-bold">${day}</div><div class="text-xs text-yellow-700">On Leave</div>`;
      } else if (status === 'Absent') {
        box.classList.add("bg-red-100", "hover:bg-red-200", "relative");
        box.innerHTML = `<div class="text-sm font-bold">${day}</div><div class="text-xs text-red-700">Absent</div>
          <div class="absolute top-0 right-0 w-0 h-0 border-l-[10px] border-l-transparent border-b-[10px] border-b-red-500"></div>`;

        // Selectable logic
        box.addEventListener('click', () => {
          if (selectedDates.has(dateStr)) {
            selectedDates.delete(dateStr);
            box.classList.remove('ring-2', 'ring-purple-600');
          } else {
            selectedDates.add(dateStr);
            box.classList.add('ring-2', 'ring-purple-600');
          }
        });
      } else {
        box.innerHTML = `<div class="text-sm font-bold text-gray-400">${day}</div>`;
      }

      calendar.appendChild(box);
    }

    container.innerHTML = '';
    container.appendChild(calendar);
  }

  // Form Submission
  const form = document.getElementById('regularizationForm');
  const message = document.getElementById('regularizationMessage');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const reason = document.getElementById('reason').value;
    const dates = Array.from(selectedDates);

    if (!dates.length) {
      message.textContent = "Please select at least one date.";
      message.className = 'text-red-600';
      return;
    }

    const res = await fetch('../api/submit_regularization.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ dates, reason })
    });

    const data = await res.json();
    if (data.status === 'success') {
      message.textContent = data.message;
      message.className = 'text-green-600';
      selectedDates.clear();
      form.reset();
      initAttendanceRegularization(); // Refresh
    } else {
      message.textContent = data.error;
      message.className = 'text-red-600';
    }
  });
}

function loadRequestHistory() {
  fetch('../api/get_regularization_requests.php')
    .then(res => res.json())
    .then(data => {
      const container = document.getElementById('requestHistory');
      if (data.length === 0) {
        container.innerHTML = "<p class='text-gray-600'>No requests yet.</p>";
        return;
      }

      let expanded = false;
      const maxVisible = 5;

      function render(limit) {
        const visibleData = limit ? data.slice(0, limit) : data;

        const rows = visibleData.map(r => `
          <tr class="border-t">
            <td class="p-2">${r.requested_date}</td>
            <td class="p-2">${r.reason}</td>
            <td class="p-2">
              <span class="inline-block px-2 py-1 rounded text-xs font-medium ${
                r.status === 'Pending' ? 'bg-yellow-100 text-yellow-800' :
                r.status === 'Approved' ? 'bg-green-100 text-green-800' :
                'bg-red-100 text-red-800'
              }">${r.status}</span>
            </td>
            <td class="p-2 text-xs text-gray-500">${r.created_at}</td>
          </tr>
        `).join('');

        container.innerHTML = `
          <table class="w-full text-left border border-gray-200 text-sm mb-2">
            <thead class="bg-gray-100 text-gray-600">
              <tr>
                <th class="p-2">Date</th>
                <th class="p-2">Reason</th>
                <th class="p-2">Status</th>
                <th class="p-2">Submitted</th>
              </tr>
            </thead>
            <tbody>${rows}</tbody>
          </table>
          <div class="text-center">
            <button id="toggleHistoryBtn" class="mt-2 text-sm text-purple-600 hover:underline">
              ${expanded ? 'Show Less' : 'Show More'}
            </button>
          </div>
        `;

        // Re-attach the event listener after DOM change
        document.getElementById('toggleHistoryBtn').addEventListener('click', () => {
          expanded = !expanded;
          render(expanded ? null : maxVisible);
        });
      }

      // Initial render with limit
      render(maxVisible);
    });
}


// Call it on load
loadRequestHistory();
