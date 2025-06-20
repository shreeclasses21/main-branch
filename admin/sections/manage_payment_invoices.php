<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Manage Payment Invoices</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-6 font-sans">
  <div class="max-w-5xl mx-auto bg-white p-6 rounded-xl shadow-md">
    <h2 class="text-2xl font-bold mb-6 text-purple-800 flex items-center">
      🧾 Create Payment Invoice
    </h2>

    <!-- Invoice Form -->
    <form id="invoiceForm" class="grid grid-cols-2 gap-4 bg-purple-50 p-6 rounded-xl border border-purple-200 mb-8">
      <!-- Student Dropdown -->
      <div class="col-span-2">
        <label for="studentDropdown" class="block text-sm font-medium text-gray-700 mb-1">Student Name</label>
        <select id="studentDropdown" required class="input w-full border p-2 rounded">
          <option value="">Select Student</option>
        </select>
      </div>

      <!-- Auto-filled fields -->
      <div>
        <label class="text-sm">Student ID</label>
        <input type="text" id="id" class="input w-full border p-2 rounded" readonly />
      </div>
      <div>
        <label class="text-sm">Student Name</label>
        <input type="text" id="student_name" class="input w-full border p-2 rounded" readonly />
      </div>
      <div>
        <label class="text-sm">Email</label>
        <input type="email" id="email" class="input w-full border p-2 rounded" readonly />
      </div>
      <div>
        <label class="text-sm">Phone</label>
        <input type="text" id="phone" class="input w-full border p-2 rounded" readonly />
      </div>

      <!-- Invoice Details -->
      <div>
        <label class="text-sm">Month</label>
        <input type="month" id="month" name="month" class="input w-full border p-2 rounded" required />
      </div>
      <div>
        <label class="text-sm">Amount</label>
        <input type="number" id="amount" name="amount" class="input w-full border p-2 rounded" required />
      </div>
      <div>
        <label class="text-sm">Status</label>
        <select id="status" name="status" class="input w-full border p-2 rounded" required>
          <option value="Paid">Paid</option>
          <option value="Unpaid">Unpaid</option>
          <option value="Pending">Pending</option>
          <option value="Partial Paid">Partial Paid</option>
        </select>
      </div>
      <div>
        <label class="text-sm">Payment Type</label>
        <select id="payment_type" name="payment_type" class="input w-full border p-2 rounded" required>
          <option value="Online">Online</option>
          <option value="Cash">Cash</option>
        </select>
      </div>

      <!-- Submit -->
      <div class="col-span-2 mt-4">
        <button type="submit" class="bg-purple-600 text-white px-6 py-2 rounded hover:bg-purple-700 transition">
          Save Invoice
        </button>
        <p id="invoiceMessage" class="mt-3 text-sm font-medium"></p>
      </div>
    </form>

    <!-- Invoice List -->
    <div id="invoiceList" class="bg-gray-50 p-4 border border-gray-200 rounded-xl">
      <h3 class="text-lg font-semibold mb-3 text-purple-700">All Payment Invoices</h3>
      <p class="text-gray-500 text-sm">Loading...</p>
    </div>
  </div>

  <!-- JS logic will run from dashboard.js -->
</body>
</html>
