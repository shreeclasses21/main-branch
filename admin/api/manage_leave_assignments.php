<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
?>
<div class="p-6">
  <h2 class="text-2xl font-semibold mb-4 text-purple-700">Manage Leave Assignments</h2>

  <!-- Add Leave Assignment Form -->
  <form id="addLeaveAssignmentForm" class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white p-4 rounded shadow mb-6">
    <select name="student_id" class="border p-2 rounded" required>
      <option value="">-- Select Student --</option>
    </select>
    <select name="leave_type_id" class="border p-2 rounded" required>
      <option value="">-- Select Leave Type --</option>
    </select>
    <input type="number" name="year" placeholder="Year (e.g. 2025)" class="border p-2 rounded" required>
    <input type="text" name="month" placeholder="Month (e.g. June)" class="border p-2 rounded" required>
    <input type="number" name="allowed" placeholder="Allowed Leaves" class="border p-2 rounded" required>
    <input type="number" name="used" placeholder="Used Leaves" class="border p-2 rounded" value="0" required>
    <button type="submit" class="col-span-full bg-purple-600 hover:bg-purple-700 text-white py-2 rounded">
      Add Assignment
    </button>
  </form>

  <!-- Assignments Table -->
  <div class="overflow-x-auto">
    <table class="min-w-full bg-white shadow rounded text-left text-sm">
      <thead>
        <tr class="bg-gray-200 text-xs uppercase text-gray-600">
          <th class="p-2">ID</th>
          <th class="p-2">Student</th>
          <th class="p-2">Leave Type</th>
          <th class="p-2">Year</th>
          <th class="p-2">Month</th>
          <th class="p-2">Allowed</th>
          <th class="p-2">Used</th>
          <th class="p-2">Remaining</th>
          <th class="p-2">Actions</th>
        </tr>
      </thead>
      <tbody id="assignmentTableBody" class="text-gray-700">
        <!-- JS will populate here -->
      </tbody>
    </table>
  </div>
</div>
