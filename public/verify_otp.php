<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Verify OTP - Shree Classes</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    .otp-input::placeholder {
      letter-spacing: 2px;
    }
  </style>
</head>
<body class="bg-gradient-to-br from-indigo-600 to-purple-800 flex items-center justify-center min-h-screen px-4">

  <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
    
    <!-- Header Gradient -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-5 text-white text-center">
      <h2 class="text-2xl font-bold">🔐 Verify Your Email</h2>
      <p class="text-sm opacity-90 mt-1">Enter the 6-digit OTP sent to your email</p>
    </div>

    <!-- OTP Form -->
    <div class="p-6">
      <form id="otpForm" class="space-y-4">
        <input
          type="text"
          id="otpCode"
          maxlength="6"
          required
          placeholder="Enter OTP"
          class="otp-input w-full px-5 py-3 border rounded-md text-center tracking-widest text-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
        />
        <button
          type="submit"
          class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-2 rounded-md font-semibold hover:opacity-90 transition"
        >
          Verify & Register
        </button>
      </form>
      <p id="otpMsg" class="text-sm mt-4 text-red-600 text-center"></p>
    </div>

    <!-- Footer Note -->
    <div class="text-center text-xs text-gray-400 pb-4">
      Didn’t receive the code? <a href="#" class="text-indigo-600 font-medium hover:underline">Resend</a>
    </div>
  </div>

  <script src="js/verify_otp.js"></script>
</body>
</html>
