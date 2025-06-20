<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Authorization Code - Shree Classes</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .bubble {
      position: absolute;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.08);
      animation: float 6s ease-in-out infinite;
      z-index: 0;
    }
    .bubble1 {
      width: 120px;
      height: 120px;
      top: 65%;
      left: 18%;
      animation-delay: 0s;
    }
    .bubble2 {
      width: 100px;
      height: 100px;
      top: 78%;
      left: 58%;
      animation-delay: 1.5s;
    }
    @keyframes float {
      0%, 100% { transform: translateY(0) scale(1); }
      50% { transform: translateY(-25px) scale(1.05); }
    }
  </style>
</head>
<body class="bg-gradient-to-br from-indigo-600 to-purple-700 min-h-screen flex items-center justify-center font-sans px-4">

  <div class="bg-white shadow-2xl rounded-3xl flex flex-col md:flex-row max-w-5xl w-full overflow-hidden">

    <!-- Left Welcome Panel -->
    <div class="bg-gradient-to-br from-purple-600 to-indigo-500 text-white p-10 w-full md:w-1/2 flex flex-col justify-center relative">
      <h1 class="text-4xl font-extrabold mb-2 z-10 relative">🔐 Student Authorization</h1>
      <h2 class="text-lg font-medium mb-4 z-10 relative">Enter the code provided by Shree Classes</h2>
      <p class="text-sm leading-relaxed z-10 relative">
        This ensures you're registering as an authorized student for the session. Please enter the secure code provided to you to proceed with registration.
      </p>
      <div class="bubble bubble1"></div>
      <div class="bubble bubble2"></div>
    </div>

    <!-- Right Form Panel -->
    <div class="w-full md:w-1/2 p-10 bg-white flex items-center justify-center">
      <div class="w-full max-w-sm">
        <h2 class="text-xl font-bold text-gray-800 text-center mb-6">Enter Authorization Code</h2>
        <form id="authForm" class="space-y-4">
          <input
            type="text"
            id="authCode"
            placeholder="Enter Authorization Code"
            required
            class="w-full px-4 py-3 border rounded-md focus:outline-none focus:ring-2 focus:ring-purple-400 text-center tracking-wider font-medium"
          />
          <button
  id="proceedBtn"
  type="submit"
  class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-2 rounded-md font-semibold hover:opacity-90 transition flex items-center justify-center gap-2"
>
  <span id="proceedText">Proceed</span>
  <svg
    id="proceedSpinner"
    class="w-5 h-5 text-white animate-spin hidden"
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    viewBox="0 0 24 24"
  >
    <circle
      class="opacity-25"
      cx="12"
      cy="12"
      r="10"
      stroke="currentColor"
      stroke-width="4"
    ></circle>
    <path
      class="opacity-75"
      fill="currentColor"
      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"
    ></path>
  </svg>
</button>

        </form>
        <p id="authMsg" class="mt-4 text-sm text-red-600 text-center"></p>
      </div>
    </div>

  </div>

  <script src="js/authorize.js"></script>
</body>
</html>
