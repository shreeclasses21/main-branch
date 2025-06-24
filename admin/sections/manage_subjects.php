<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit;
}
?>

<div class="w-full max-w-5xl mx-auto space-y-8">
  <div class="bg-white shadow-lg rounded-3xl p-8 border border-purple-100">
    <h2 class="text-3xl font-bold text-purple-800 mb-6">📚 Manage Subjects</h2>

    <!-- Subject Form -->
    <form id="addSubjectForm" class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Grade</label>
        <input type="text" name="grade" placeholder="e.g. 8th" required
               class="w-full border border-gray-300 rounded-xl shadow-sm px-4 py-2 focus:ring-purple-500 focus:border-purple-500" />
      </div>
      <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Subject Name</label>
        <input type="text" name="subject_name" placeholder="e.g. Mathematics" required
               class="w-full border border-gray-300 rounded-xl shadow-sm px-4 py-2 focus:ring-purple-500 focus:border-purple-500" />
      </div>
      <div>
        <button type="submit"
                class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-xl shadow">
          ➕ Add Subject
        </button>
      </div>
    </form>
  </div>

  <!-- Subject List -->
  <div id="subjectList" class="bg-white shadow-md rounded-2xl overflow-hidden border border-purple-100">
    <!-- Dynamic table loads via JS -->
  </div>
</div>
