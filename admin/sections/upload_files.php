<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Upload Files for Students</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    .upload-wrapper {
      min-height: 100vh;
      background: linear-gradient(to bottom right, #eef2ff, #f0f7ff);
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 2rem;
    }
    .upload-card {
      background: rgba(255, 255, 255, 0.9);
      border-radius: 1.5rem;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
      padding: 2rem;
      width: 100%;
      max-width: 700px;
      backdrop-filter: blur(10px);
      position: relative;
    }
    .upload-header {
      font-size: 1.75rem;
      font-weight: bold;
      color: #4f46e5;
    }
    .floating-bubble {
      position: absolute;
      top: -60px;
      left: -60px;
      width: 300px;
      height: 300px;
      background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
      border-radius: 50%;
      filter: blur(80px);
      z-index: 0;
    }
    select[multiple] {
      min-height: 130px;
    }
  </style>
</head>

<body>
<div class="upload-wrapper">
  <!-- Floating bubble background -->
  <div class="floating-bubble"></div>

  <!-- Main Upload Card -->
  <div class="upload-card z-10">
    <h2 class="upload-header text-center mb-6 flex items-center justify-center gap-2">
      <img src="https://img.icons8.com/color/48/folder-invoices.png" class="w-8" />
      Upload Files for Students
    </h2>

    <form id="fileUploadForm" method="POST" enctype="multipart/form-data" class="space-y-6">
      <!-- Grade -->
      <div>
        <label for="grade" class="block font-semibold text-gray-700 mb-1 flex items-center gap-2">
          🎓 Select Grade
        </label>
        <select id="grade" name="grade" class="w-full p-3 rounded-lg border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
          <option value="8">8th Grade</option>
          <option value="9">9th Grade</option>
        </select>
      </div>

      <!-- Students -->
      <div>
        <label for="students" class="block font-semibold text-gray-700 mb-1 flex items-center gap-2">
          🧑‍🏫 Select Students
        </label>
        <select id="students" name="students[]" multiple class="w-full p-3 rounded-lg border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
          <!-- Dynamically filled -->
        </select>
      </div>

      <!-- Section -->
      <div>
        <label for="section" class="block font-semibold text-gray-700 mb-1 flex items-center gap-2">
          📘 Select Section
        </label>
        <select id="section" name="section" class="w-full p-3 rounded-lg border border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 bg-white">
          <!-- Dynamically filled -->
        </select>
      </div>

      <!-- Files -->
      <div>
        <label for="attachments" class="block font-semibold text-gray-700 mb-1 flex items-center gap-2">
          📎 Select Files
        </label>
        <input type="file" id="attachments" name="attachments[]" multiple class="block w-full text-sm text-gray-800 border border-gray-300 rounded-lg shadow-sm p-3 focus:ring-indigo-500 focus:border-indigo-500 bg-white"/>
      </div>

      <!-- Submit Button -->
      <div class="text-center">
        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-bold px-6 py-2 rounded-full shadow-lg transition duration-300 ease-in-out transform hover:scale-105">
          ⬆️ Upload Files
        </button>
      </div>
    </form>

    <div id="fileUploadStatus" class="mt-4 text-center text-sm font-medium"></div>
  </div>
</div>
</body>
</html>
