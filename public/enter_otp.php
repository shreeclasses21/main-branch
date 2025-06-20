
<?php
session_start();
if (!isset($_SESSION['otp_requested']) || !isset($_SESSION['student_email'])) {
    header("Location: forgot_password.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Enter OTP - Shree Classes</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            inter: ['Inter', 'sans-serif'],
          },
          colors: {
            'primary-dark': '#3B0764',
            'primary-light': '#6D28D9',
            'active-pink-red': '#EC4899',
            'active-pink-red-darker': '#DB2777',
            'background-light': '#F8FAFC',
            'background-dark': '#E2E8F0',
            'card-bg': '#FFFFFF',
            'text-main': '#1F2937',
            'text-secondary': '#6B7280',
            'border-light': '#E5E7EB',
            'shadow-light': 'rgba(0, 0, 0, 0.05)',
            'accent-gradient-start': '#8B5CF6',
            'accent-gradient-end': '#EC4899',
          },
          boxShadow: {
            'custom-light': '0 4px 6px -1px var(--tw-shadow-light), 0 2px 4px -1px var(--tw-shadow-light)',
            'card-glow': '0 0 30px rgba(109, 40, 217, 0.15)',
          }
        }
      }
    };
  </script>
  <link rel="stylesheet" href="css/otp-verify.css" />
</head>

<body class="font-inter flex items-center justify-center min-h-screen bg-background-light">
  <div class="bg-card-bg p-8 rounded-2xl shadow-xl w-full max-w-md border border-border-light transform transition-all duration-300 hover:shadow-card-glow animate-fade-in">
    <div class="text-center mb-6">
      <svg class="mx-auto h-12 w-12 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M15 7a2 2 0 012 2v5a2 2 0 01-2 2h-1a2 2 0 01-2-2V9a2 2 0 012-2h1zM5 7a2 2 0 012 2v5a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2h1zM19 7a2 2 0 012 2v5a2 2 0 01-2 2h-1a2 2 0 01-2-2V9a2 2 0 012-2h1z">
        </path>
      </svg>
    </div>
    <h2 class="text-3xl font-extrabold text-center text-primary-dark mb-8">Enter OTP</h2>
    <p class="text-center text-text-secondary mb-6">Please enter the 6-digit One-Time Password (OTP) sent to your registered email address.</p>

    <form id="otpForm" class="space-y-6">
      <div>
        <label for="otp" class="block text-sm font-medium text-text-secondary mb-1">Enter 6-digit OTP:</label>
        <input type="text" id="otp" name="otp" maxlength="6" required
          class="mt-1 block w-full px-4 py-2 text-center text-xl font-bold tracking-widest border border-border-light rounded-lg shadow-sm focus:ring-primary-light focus:border-primary-light outline-none transition duration-200 ease-in-out sm:text-xl placeholder-text-secondary"
          placeholder="______" />
      </div>

      <button type="submit"
        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-lg font-semibold text-white bg-gradient-to-r from-accent-gradient-start to-accent-gradient-end hover:from-accent-gradient-end hover:to-accent-gradient-start focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-dark transition duration-300 ease-in-out transform hover:scale-105">
        Verify OTP
      </button>
    </form>

    <p class="mt-6 text-center text-sm">
      <button id="resendOtpBtn" class="font-medium text-primary-light hover:text-primary-dark transition duration-150 ease-in-out underline">Resend OTP</button>
    </p>
    <p class="mt-2 text-center text-sm">
      <a href="forgot_password.php"
        class="font-medium text-text-secondary hover:text-text-main transition duration-150 ease-in-out">Back to Forgot
        Password</a>
    </p>
  </div>

  <!-- ✅ Toast container -->
  <div id="toastContainer" class="fixed bottom-6 right-6 z-50 space-y-2"></div>

  <script>
    // Submit OTP using fetch
    document.getElementById("otpForm").addEventListener("submit", async function (e) {
      e.preventDefault();
      const otp = document.getElementById("otp").value;

      const res = await fetch("../auth/verify_otp.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams({ otp })
      });

      const out = await res.json();

      if (out.success) {
        showToast("✅ OTP Verified! Redirecting...");
        setTimeout(() => {
          window.location.href = out.redirect;
        }, 1200);
      } else {
        showToast(out.message, "error");
      }
    });

    function showToast(msg, type = "success") {
      const toast = document.createElement("div");
      toast.textContent = msg;
      toast.className = `px-4 py-2 rounded shadow-md text-white text-sm transition-opacity duration-300
        ${type === "error" ? "bg-red-600" : "bg-green-600"}`;
      document.getElementById("toastContainer").appendChild(toast);

      setTimeout(() => {
        toast.style.opacity = "0";
        setTimeout(() => toast.remove(), 300);
      }, 3000);
    }
  </script>
</body>
</html>
