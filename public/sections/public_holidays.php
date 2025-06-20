<?php
session_start();
if (!isset($_SESSION['contact_id'])) {
  echo "<p class='text-red-500'>Please log in to view public holidays.</p>";
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Public Holidays</title>

  <!-- Tailwind + Inter -->
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

  <!-- Page-specific styles -->
  <link rel="stylesheet" href="./css/public_holidays.css">
</head>
<body
  class="bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500
         min-h-screen flex items-center justify-center p-4">

  <!-- Page-load spinner -->
  <div id="loadingOverlay"
       class="fixed inset-0 bg-gray-200 bg-opacity-75 flex items-center justify-center z-50">
    <div class="spinner-large"></div>
  </div>

  <div
    class="bg-white p-6 md:p-10 lg:p-12 rounded-3xl shadow-2xl w-full max-w-7xl mx-auto">
    <h1
      class="text-4xl font-extrabold text-center tracking-tight mb-2">
      Public <span class="text-purple-600">Holidays</span>
    </h1>
    <p class="text-center text-gray-600 mb-8 text-lg">
      View upcoming public holidays for the current year.
    </p>

    <!-- === TWO-COLUMN LAYOUT (calendar | list) === -->
    <div
      class="grid grid-cols-1 md:grid-cols-[2fr_1fr] gap-10">

      <!-- ===== Calendar column ===== -->
      <div>
        <h2
          class="text-3xl font-bold text-gray-800 text-center md:text-left mb-6">
          Calendar View – <span id="holYear"></span>
        </h2>

        <div id="holidaysCalendar"
             class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2
                    gap-8">
          <!-- injected by JS -->
        </div>
      </div>

      <!-- ===== List column ===== -->
      <div>
        <!-- Collapsible on mobile -->
        <details open class="md:block">
          <summary
            class="md:hidden cursor-pointer font-semibold text-purple-600 mb-3">
            Holiday List
          </summary>

          <h2
            class="hidden md:block text-2xl font-bold text-gray-800 mb-4">
            Holiday List
          </h2>

          <!-- Scrollable list panel -->
          <ul id="holidaysList"
              class="list-none space-y-3 overflow-y-auto
                     max-h-[75vh] pr-2 md:pr-4">
            <!-- injected by JS -->
          </ul>
        </details>
      </div>
    </div>

    <div id="holidaysError"
         class="mt-6 text-red-600 font-medium text-center"></div>
  </div>

  <!-- Re-usable modal -->
  <div id="messageModal"
       class="modal hidden fixed inset-0 bg-gray-900 bg-opacity-70
              flex items-center justify-center p-4 z-50 backdrop-blur">
    <div
      class="modal-content bg-white rounded-xl shadow-2xl overflow-hidden
             w-full max-w-sm border-t-4 border-purple-500">
      <div id="modalHeader"
           class="modal-header px-6 py-4 flex items-center justify-between
                  text-white bg-blue-500">
        <h3 id="modalTitle"
            class="text-lg font-bold flex items-center">Information</h3>
        <button onclick="closeModal()"
                class="text-white text-2xl leading-none hover:text-gray-200">
          &times;
        </button>
      </div>
      <div class="p-6">
        <p id="modalBody"
           class="text-gray-700 text-base leading-relaxed"></p>
      </div>
      <div
        class="px-6 py-4 bg-gray-50 text-right border-t border-gray-200">
        <button onclick="closeModal()"
                class="bg-purple-600 text-white px-5 py-2 rounded-lg
                       hover:bg-purple-700 focus:outline-none focus:ring-2
                       focus:ring-purple-500 focus:ring-offset-2">
          Got it!
        </button>
      </div>
    </div>
  </div>

  <!-- Page logic -->
  <script src="./js/public_holidays.js"></script>
</body>
</html>
