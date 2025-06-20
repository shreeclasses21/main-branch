<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Student Login - Shree Classes</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            inter: ['Inter', 'sans-serif'],
          },
          colors: {
            'primary-start': '#EC4899',
            'primary-end': '#8B5CF6',
            'text-main': '#1F2937',
            'text-secondary': '#6B7280',
            'border-light': '#E5E7EB'
          },
          borderRadius: {
            'xl': '1.5rem',
          },
          fontFamily: {
  inter: ['Inter', 'sans-serif'],
  poppins: ['Poppins', 'sans-serif'],
  roboto: ['Roboto', 'sans-serif'],
}

        }
      }
    };
  </script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

  <link rel="stylesheet" href="css/login.css">
</head>

<body class="font-inter min-h-screen bg-gradient-to-br from-primary-start to-primary-end flex items-center justify-center px-4">
  <div class="bg-white shadow-2xl rounded-xl overflow-hidden w-full max-w-6xl flex flex-col md:flex-row">
    
    <!-- Left Welcome Side -->
    <!-- Replace with -->
<div class="w-full md:w-1/2 p-10 left-bubble-bg text-white relative flex flex-col justify-center">
     <div class="bubble bubble-xl top-10 left-10"></div>
      <div class="bubble bubble-md bottom-10 right-10"></div>
      <div class="z-10 text-white">
  <h2 class="text-4xl font-extrabold mb-2 font-poppins">WELCOME</h2>
  <h4 class="text-2xl font-semibold mb-4 font-poppins">To SHREE CLASSES STUDENT PORTAL</h4>
  <p class="text-base font-roboto leading-relaxed">
    Access your classes, apply for leave, check attendance, download exam documents,
    and manage your student profile — all in one smart and seamless platform.
  </p>
</div>

    </div>

    <!-- Right Login Side -->
    <div class="w-full md:w-1/2 p-10">
      <h2 class="text-3xl font-bold text-center text-text-main mb-6">Sign in</h2>

      <!-- Toggle Tabs -->
      <div class="flex bg-gray-100 rounded-full p-1 mb-6 shadow-inner">
        <button id="emailLoginTab" class="tab-button active flex-1 py-2 px-4 text-sm font-semibold rounded-full text-center transition-all">Login with Email</button>
        <button id="studentIdLoginTab" class="tab-button flex-1 py-2 px-4 text-sm font-semibold rounded-full text-center transition-all">Login with Student ID</button>
      </div>

      <!-- Login Form -->
      <form id="loginForm" method="POST" action="../auth/login.php" class="space-y-5">
        <div>
          <label for="login_id" class="block text-sm font-medium text-text-secondary mb-1" id="loginIdLabel">Email:</label>
          <input type="email" id="login_id" name="login_id" required class="w-full px-4 py-2 border border-border-light rounded-lg focus:ring-primary-start focus:border-primary-start placeholder-text-secondary" placeholder="your.email@example.com">
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-text-secondary mb-1">Password:</label>
          <input type="password" id="password" name="password" required class="w-full px-4 py-2 border border-border-light rounded-lg focus:ring-primary-start focus:border-primary-start placeholder-text-secondary" placeholder="Enter your password">
        </div>

        <div class="flex justify-between text-sm text-text-secondary">
          <label class="flex items-center space-x-2">
            <input type="checkbox" class="form-checkbox text-primary-start">
            <span>Remember me</span>
          </label>
          <a href="forgot_password.php" class="text-primary-start hover:text-pink-700 transition">Forgot Password?</a>
        </div>

        <button type="submit" class="w-full py-2 bg-gradient-to-r from-primary-start to-primary-end text-white font-semibold rounded-lg transition-all hover:opacity-90">Sign in</button>
      </form>

      <p class="mt-6 text-sm text-center text-text-secondary">
      </p>
    </div>
  </div>

  <script src="js/login.js"></script>
</body>
</html>
