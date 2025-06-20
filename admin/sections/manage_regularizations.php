<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
?>
<div class="max-w-6xl mx-auto">
  <h2 class="text-2xl font-bold mb-4 text-purple-800">🗂️ Manage Attendance Regularizations</h2>

  <div class="flex items-center justify-between mb-4">
    <input type="text" id="studentNameFilter" placeholder="🔍 Filter by student name..." class="p-2 border rounded w-1/2" />
    <button onclick="approveAllRequests()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
      ✅ Approve All Pending
    </button>
  </div>

  <div id="regularizationAdminTable">
    <p class="text-gray-500">Loading...</p>
  </div>
</div>
