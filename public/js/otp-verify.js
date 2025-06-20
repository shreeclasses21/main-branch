document.addEventListener('DOMContentLoaded', () => {
    const otpInput = document.getElementById('otp');
    const resendOtpBtn = document.getElementById('resendOtpBtn');

    // Optional: Add client-side validation for OTP input
    otpInput.addEventListener('input', () => {
        // Remove non-digit characters
        otpInput.value = otpInput.value.replace(/\D/g, '');
        // Limit to 6 characters
        if (otpInput.value.length > 6) {
            otpInput.value = otpInput.value.slice(0, 6);
        }
    });

    // Optional: Add functionality for the Resend OTP button
    resendOtpBtn.addEventListener('click', (event) => {
        event.preventDefault(); // Prevent default button behavior
        console.log("Resend OTP button clicked.");
        // In a real application, you would make an AJAX call to resend the OTP
        // And potentially disable the button for a cooldown period
        alert("OTP Resend functionality is not yet implemented. In a live app, an OTP would be resent.");
    });

    console.log("OTP verification page loaded.");
});
