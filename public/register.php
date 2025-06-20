<?php
session_start();

// Prevent direct access to register if auth code not validated
if (!isset($_SESSION['auth_code_verified'])) {
    header("Location: authorize.php");
    exit;
}

// Prevent direct access to reset_password.php unless contact_id is set
if (!isset($_SESSION['contact_id']) && basename(__FILE__) === 'reset_password.php') {
    header("Location: forgot_password.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - Shree Classes</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet" />
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
      top: 70%;
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
      0%, 100% {
        transform: translateY(0) scale(1);
      }
      50% {
        transform: translateY(-25px) scale(1.05);
      }
    }
  </style>
</head>
<body class="bg-gradient-to-br from-pink-500 to-purple-600 min-h-screen flex items-center justify-center font-sans px-4">

  <div class="bg-white shadow-2xl rounded-3xl flex flex-col md:flex-row max-w-6xl w-full overflow-hidden">
    
    <!-- Welcome Section (Now always visible) -->
    <div class="bg-gradient-to-br from-purple-600 to-indigo-500 text-white p-10 w-full md:w-1/2 flex flex-col justify-center relative">
      <h1 class="text-4xl font-extrabold mb-2 z-10 relative">REGISTER</h1>
      <h2 class="text-xl font-semibold mb-4 z-10 relative">Create your Shree Classes Student Account</h2>
      <p class="text-sm leading-relaxed z-10 relative">
        Get started with your student portal. Register to access attendance, leave requests,
        exams, results, and personalized dashboard features.
      </p>
      <!-- Bubbles -->
      <div class="bubble bubble1"></div>
      <div class="bubble bubble2"></div>
    </div>

    <!-- Form Section -->
    <div class="w-full md:w-1/2 p-10 bg-white">
      <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Create Account</h2>

      <form id="registerForm" class="space-y-4">
        <div>
          <label class="text-sm text-gray-600 block mb-1">First Name</label>
          <input type="text" id="first_name" placeholder="Your first name" required
            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-400" />
        </div>

        <div>
          <label class="text-sm text-gray-600 block mb-1">Last Name</label>
          <input type="text" id="last_name" placeholder="Your last name" required
            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-400" />
        </div>

        <div>
          <label class="text-sm text-gray-600 block mb-1">Email Address</label>
          <input type="email" id="email" placeholder="e.g., you@example.com" required
            class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-purple-400" />
        </div>

        <p class="text-xs text-gray-500">🔐 A verification OTP will be sent to your email after you submit.</p>

     <button id="sendOtpBtn" type="submit"
  class="w-full bg-gradient-to-r from-pink-500 to-purple-600 text-white py-2 rounded font-semibold hover:opacity-90 transition flex items-center justify-center gap-2">
  
  <span id="sendOtpText">Send OTP</span>

  <!-- Spinner (initially hidden) -->
  <svg id="sendOtpSpinner" class="w-5 h-5 text-white animate-spin hidden"
       xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor"
          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
  </svg>
</button>

      </form>

      <p id="statusMsg" class="text-sm mt-4 text-red-600 text-center"></p>

      <p class="text-sm mt-6 text-gray-600 text-center">
        Already have an account? <a href="login.php" class="text-purple-600 font-semibold hover:underline">Login here</a>
      </p>
    </div>

  </div>

  <script src="js/register.js"></script>
</body>
</html>
