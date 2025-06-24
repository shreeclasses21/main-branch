document.addEventListener("DOMContentLoaded", () => {
 const markBtn = document.getElementById("markAttendanceBtn");
  const msgBox = document.getElementById("attendanceMessage");

  // ✅ Use India time (YYYY-MM-DD)
  const today = new Date().toLocaleString("en-CA", { timeZone: "Asia/Kolkata" }).split(",")[0];

  (async () => {
    try {
      const res = await fetch(`../api/calendar_status.php?month=${today.slice(0, 7)}&t=${Date.now()}`);
      const data = await res.json();

      if (data[today]) {
        markBtn.disabled = true;
        markBtn.classList.add("opacity-50", "cursor-not-allowed");
        msgBox.textContent = `✅ Attendance already marked for today (${data[today]})`;
      } else {
        markBtn.disabled = false;
        markBtn.classList.remove("opacity-50", "cursor-not-allowed");
        msgBox.textContent = "";
      }
    } catch (err) {
      console.error("⚠️ Error checking attendance", err);
    }
  })();


// 📌 Mark attendance on button click
if (markBtn) {
  markBtn.addEventListener("click", async () => {
    msgBox.textContent = "⏳ Submitting attendance...";

    try {
      const res = await fetch("../api/mark_attendance.php");
      const raw = await res.text();

      let data;
      try {
        data = JSON.parse(raw);
      } catch (jsonErr) {
        console.error("❌ JSON parsing error:", jsonErr);
        console.error("📄 Raw response:", raw);
        msgBox.textContent = "❌ Unexpected server response.";
        return;
      }

      if (data.status === "success") {
        markBtn.disabled = true;
        markBtn.classList.add("opacity-50", "cursor-not-allowed");
        msgBox.innerHTML = ` <span class="text-green-700 font-semibold">${data.message}</span>`;
      } else {
        msgBox.innerHTML = `⚠️ <span class="text-red-500">${data.message || data.error}</span>`;
      }
    } catch (err) {
      console.error("❌ Error during fetch:", err);
      msgBox.textContent = "❌ Error submitting attendance.";
    }
  });
}


  console.log("🔧 dashboard.js loaded");

  // 🎯 Sidebar navigation handler
  document.querySelectorAll(".sidebar-link").forEach(link => {
    link.addEventListener("click", async e => {
      e.preventDefault();
      const section = link.dataset.section;
      console.log("🔀 Loading section:", section);

      try {
        // Load section HTML
        const html = await fetch(`sections/${section}.php`)
          .then(r => r.ok ? r.text() : Promise.reject(`Status ${r.status}`));
        document.getElementById("content-area").innerHTML = html;

        // Load and initialize JS per section
        switch (section) {
          case "apply_leave":
            await loadScript("https://cdn.jsdelivr.net/npm/chart.js@3/dist/chart.min.js", "Chart.js");
            await loadScript("js/apply_leave.js", "apply_leave.js");
            initApplyLeaveForm?.();
            break;
          case "view_leaves":
            await loadScript("js/view_leaves.js", "view_leaves.js");
            initViewLeaves?.();
            break;
          case "public_holidays":
            await loadScript("js/public_holidays.js", "public_holidays.js");
            initPublicHolidays?.();
            break;
          case "student_profile":
            await loadScript("js/student_profile.js", "student_profile.js");
            initStudentProfile?.();
            break;
          case "attendance_calendar":
            await loadScript("js/attendance_calendar.js", "attendance_calendar.js");
            initAttendanceCalendar?.();
            break;
          case "attendance_regularization":
            await loadScript("js/attendance_regularization.js", "attendance_regularization.js");
            initAttendanceRegularization?.();
            break;
           case "student_payment_invoices":
            await loadScript("js/student_invoices.js", "student_invoices.js");
            initStudentInvoices?.();
            break;
            case "my_files":
  await loadScript("js/my_files.js", "my_files.js");
  initMyFiles?.();
  break;
   case "exams":
  await loadScript("js/exam_register.js", "exam_register.js");
  fetchAndRenderExams?.();
  break;


          default:
            console.log(`ℹ️ No extra JS for section "${section}"`);
        }
      } catch (err) {
        console.error("❌ Error loading section:", err);
        document.getElementById("content-area").innerHTML =
          `<div class="text-red-500 p-4">Error loading "${section}": ${err}</div>`;
      }
    });
  });

  
});

// Helper to inject a <script> once
function loadScript(src, name = src) {
  return new Promise((resolve, reject) => {
    if (document.querySelector(`script[src="${src}"]`)) {
      console.log(`🔄 "${name}" already loaded, skipping`);
      return resolve();
    }

    const script = document.createElement("script");
    script.src = src;
    script.onload = () => {
      console.log(`✅ ${name} loaded`);
      resolve();
    };
    script.onerror = () => {
      console.error(`❌ Failed to load ${name}`);
      reject(name);
    };
    document.body.appendChild(script);
  });
}
