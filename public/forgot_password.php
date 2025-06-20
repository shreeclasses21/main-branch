<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Shree Classes</title>
    <!-- Include Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Define custom Tailwind configuration for professional colors and Inter font -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        inter: ['Inter', 'sans-serif'], // Use Inter font
                    },
                    colors: {
                        // Inherit color palette from the login page for consistency
                        'primary-dark': '#3B0764',
                        'primary-light': '#6D28D9',
                        'background-light': '#F8FAFC',
                        'background-dark': '#E2E8F0',
                        'card-bg': '#FFFFFF',
                        'text-main': '#1F2937',
                        'text-secondary': '#6B7280',
                        'border-light': '#E5E7EB',
                        'shadow-light': 'rgba(0, 0, 0, 0.05)',
                        'accent-gradient-start': '#8B5CF6', /* Purple-pink for a new accent */
                        'accent-gradient-end': '#EC4899',   /* Strong pink for gradient end */
                    },
                    boxShadow: {
                        'custom-light': '0 4px 6px -1px var(--tw-shadow-light), 0 2px 4px -1px var(--tw-shadow-light)',
                        'card-glow': '0 0 30px rgba(109, 40, 217, 0.15)', /* Subtle glow for the card */
                    }
                }
            }
        }
    </script>
    <!-- Link to your external CSS file -->
    <link rel="stylesheet" href="css/reset-password.css">
</head>
<body class="font-inter">
    <!-- Main container for the forgot password module -->
    <div class="bg-card-bg p-8 rounded-2xl shadow-xl w-full max-w-md border border-border-light transform transition-all duration-300 hover:shadow-card-glow animate-fade-in">
        <div class="text-center mb-6">
            <!-- Optional: Add a simple SVG icon for visual appeal -->
            <svg class="mx-auto h-12 w-12 text-primary-light" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-2 4h4m6-14V4a2 2 0 00-2-2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2v-4M10 9V7a2 2 0 012-2h0a2 2 0 012 2v2m-6 0h6"></path>
            </svg>
        </div>
        <h2 class="text-3xl font-extrabold text-center text-primary-dark mb-8">Forgot Password</h2>
        <p class="text-center text-text-secondary mb-6">Enter your email address to receive a One-Time Password (OTP) for password reset.</p>

        <!-- Forgot Password Form -->
        <form action="../auth/send_otp.php" method="POST" class="space-y-6">
            <div>
                <label for="email" class="block text-sm font-medium text-text-secondary mb-1">Email:</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    required
                    class="mt-1 block w-full px-4 py-2 border border-border-light rounded-lg shadow-sm focus:ring-primary-light focus:border-primary-light outline-none transition duration-200 ease-in-out sm:text-sm placeholder-text-secondary"
                    placeholder="your.email@example.com"
                >
            </div>

            <button
                type="submit"
                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-md text-lg font-semibold text-white bg-gradient-to-r from-accent-gradient-start to-accent-gradient-end hover:from-accent-gradient-end hover:to-accent-gradient-start focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-dark transition duration-300 ease-in-out transform hover:scale-105"
            >
                Send OTP
            </button>
        </form>

        <!-- Back to Login Link -->
        <p class="mt-6 text-center text-sm">
           <a href="login.php" class="font-medium text-primary-light hover:text-primary-dark transition duration-150 ease-in-out">Back to Login</a>
        </p>
    </div>

    <!-- Link to your external JavaScript file -->
    <script src="js/reset-password.js"></script>
</body>
</html>
