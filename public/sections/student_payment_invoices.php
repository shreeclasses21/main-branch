<?php
session_start();
if (!isset($_SESSION['contact_id'])) {
    header("Location: ../index.html");
    exit;
}
?>

<div>
  <h2 class="text-2xl font-bold text-purple-700 mb-4">🧾 My Payment Invoices</h2>

  <!-- Invoice List -->
  <div id="studentInvoicesList" class="bg-white shadow-md rounded-xl p-4 border border-gray-200">
    <p>Loading your invoices...</p>
  </div>
</div>

<!-- Load JS -->
<script src="./js/student_invoices.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", () => {
    initStudentInvoices();
  });
</script>
