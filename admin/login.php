<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <!-- Load Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts for Inter and Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&family=Poppins:wght@600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" xintegrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0V4LLanw2qksYuRlEzO+tcaEPQogQ0KaoIF2QVp/wP62mtzcrxLaRjLg+zg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* Custom styles for a more dynamic look */
        body {
            font-family: 'Inter', sans-serif;
            /* More vibrant and dynamic gradient */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 1rem; /* Responsive padding */
            overflow: hidden; /* Prevent scrollbar if subtle animations extend slightly */
        }

        /* Card specific styles */
        .login-card {
            background-color: rgba(255, 255, 255, 0.95); /* Slightly transparent white */
            backdrop-filter: blur(10px); /* Frosted glass effect */
            border-radius: 2rem; /* More rounded corners */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.3); /* Stronger, deeper shadow */
            transition: all 0.4s ease-in-out; /* Smooth transition for hover */
            border: 1px solid rgba(255, 255, 255, 0.3); /* Subtle border for depth */
            animation: fadeIn 1s ease-out forwards; /* Fade in animation */
        }

        .login-card:hover {
            transform: translateY(-5px) scale(1.01); /* Slight lift and scale on hover */
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.4);
        }

        /* Input field focus animation */
        input:focus {
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2); /* Ring effect */
        }

        /* Button hover effect */
        .login-button {
            background: linear-gradient(45deg, #4c51bf, #6b46c1); /* Gradient button */
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            z-index: 1;
        }

        .login-button:hover {
            background: linear-gradient(45deg, #6b46c1, #4c51bf); /* Reverse gradient on hover */
            transform: translateY(-2px); /* Slight lift */
            box-shadow: 0 10px 20px -5px rgba(76, 81, 191, 0.4);
        }

        .login-button::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            opacity: 0;
            transition: all 0.3s ease;
            z-index: -1;
        }

        .login-button:hover::after {
            opacity: 1;
            transform: scale(1.05);
        }

        /* Keyframe for fade in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">
    <!-- Login Form Container -->
    <div class="login-card p-8 md:p-12 w-full max-w-md">
        <div class="text-center mb-6">
            <i class="fas fa-lock text-purple-600 text-5xl mb-4"></i> <!-- Lock icon -->
            <h2 class="text-4xl font-extrabold text-gray-800 tracking-tight" style="font-family: 'Poppins', sans-serif;">Admin Portal</h2>
        </div>

        <!-- Error Message Placeholder (replace with your PHP logic) -->
        <?php if (isset($_SESSION['admin_error'])): ?>
            <p class="text-red-600 text-sm text-center mb-6 font-medium bg-red-100 p-3 rounded-xl border border-red-300 animate-pulse">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <?= $_SESSION['admin_error'] ?>
            </p>
            <?php unset($_SESSION['admin_error']); ?>
        <?php endif; ?>

        <form action="api/admin_auth.php" method="POST">
            <div class="mb-6">
                <label for="username" class="block text-gray-700 text-sm font-semibold mb-2">Username</label>
                <div class="relative">
                    <input
                        type="text"
                        id="username"
                        name="username"
                        required
                        class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition duration-200"
                        placeholder="Enter your username"
                    >
                    <i class="fas fa-user absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <div class="mb-8">
                <label for="password" class="block text-gray-700 text-sm font-semibold mb-2">Password</label>
                <div class="relative">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        required
                        class="w-full px-4 py-3 pl-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition duration-200"
                        placeholder="Enter your password"
                    >
                    <i class="fas fa-key absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <button
                type="submit"
                class="login-button text-white w-full py-3 rounded-lg font-bold text-lg shadow-lg hover:shadow-xl transition duration-300"
            >
                Login
            </button>
        </form>
    </div>
</body>
</html>

