document.addEventListener('DOMContentLoaded', () => {
    const newPasswordInput = document.getElementById('new_password');
    const confirmPasswordInput = document.getElementById('confirm_password');
    const updatePasswordBtn = document.getElementById('updatePasswordBtn');
    const resetPasswordForm = document.getElementById('resetPasswordForm');
    const messageContainer = document.getElementById('messageContainer');
    const messageText = document.getElementById('messageText');
    const successMessageArea = document.getElementById('successMessageArea'); // New success message area
    const countdownTimer = document.getElementById('countdownTimer');         // New countdown timer span
    const redirectNowBtn = document.getElementById('redirectNowBtn');         // New "Login Now" button
    const backToLoginLinkContainer = document.getElementById('backToLoginLinkContainer'); // Container for the original link

    let countdownInterval; // Variable to store the interval ID for the countdown

    // Function to display messages
    const showMessage = (message, type) => {
        messageText.textContent = message;
        messageContainer.classList.remove('hidden', 'bg-red-100', 'text-error-red', 'bg-green-100', 'text-success-green');

        if (type === 'success') {
            messageContainer.classList.add('bg-green-100', 'text-success-green');
        } else if (type === 'error') {
            messageContainer.classList.add('bg-red-100', 'text-error-red');
        }
        messageContainer.classList.remove('hidden');
    };

    // Function to start the countdown and redirect
    const startRedirectCountdown = (seconds) => {
        let timeLeft = seconds;
        countdownTimer.textContent = timeLeft;

        countdownInterval = setInterval(() => {
            timeLeft--;
            countdownTimer.textContent = timeLeft;
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                window.location.href = 'login.php'; // Redirect to login page
            }
        }, 1000); // Update every 1 second
    };

    // Handle form submission with fetch API
    resetPasswordForm.addEventListener('submit', async (event) => {
        event.preventDefault(); // Prevent default form submission

        // Client-side validation
        if (newPasswordInput.value !== confirmPasswordInput.value) {
            showMessage("New password and confirm password do not match!", 'error');
            return;
        }
        if (newPasswordInput.value.length < 8) {
            showMessage("Password must be at least 8 characters long.", 'error');
            return;
        }
        // You can add more password complexity rules here

        // Disable button and show loading indicator (optional)
        updatePasswordBtn.disabled = true;
        updatePasswordBtn.textContent = 'Updating...';
        messageContainer.classList.add('hidden'); // Hide previous error messages

        try {
            const formData = new FormData(resetPasswordForm);
            const response = await fetch(resetPasswordForm.action, {
                method: 'POST',
                body: formData
            });

            // Read the response body as text first
            const rawResponseText = await response.text();
            let result;

            try {
                // IMPORTANT FIX: Remove markdown code block fences before parsing JSON
                const cleanResponseText = rawResponseText
                    .replace(/^```php\s*/, '') // Remove starting ```php and any whitespace
                    .replace(/\s*```$/, '')   // Remove ending ``` and any whitespace
                    .trim();                  // Trim any remaining whitespace

                // Attempt to parse the cleaned text as JSON
                result = JSON.parse(cleanResponseText);
            } catch (jsonParseError) {
                // If parsing as JSON fails, it means the server didn't send valid JSON.
                console.error("JSON parsing error:", jsonParseError);
                console.error("Raw server response (unparsable):", rawResponseText);
                showMessage("Server returned an unexpected response. Please check the browser console for details.", 'error');
                return; // Stop further processing as we don't have valid JSON
            }

            if (result.status === 'success') {
                // Hide the form and show the success message area
                resetPasswordForm.classList.add('hidden');
                backToLoginLinkContainer.classList.add('hidden'); // Hide the standard back link
                successMessageArea.classList.remove('hidden');

                // Start the countdown timer for redirection
                startRedirectCountdown(10); // Redirect in 10 seconds
            } else {
                showMessage(result.message || "Failed to update password. Please try again.", 'error');
            }
        } catch (error) {
            // This catch block handles network errors or errors before response.text() is called
            console.error("Error updating password:", error);
            showMessage("An unexpected error occurred. Please try again later.", 'error');
        } finally {
            updatePasswordBtn.disabled = false;
            updatePasswordBtn.textContent = 'Update Password'; // Revert button text
        }
    });

    // Handle "Login Now" button click (to immediately redirect)
    redirectNowBtn.addEventListener('click', () => {
        clearInterval(countdownInterval); // Stop the countdown
        window.location.href = 'login.php'; // Redirect immediately
    });

    console.log("Set New Password page loaded.");
});
