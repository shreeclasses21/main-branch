function showError(message) {
  const modal = document.getElementById('errorModal');
  const errorText = document.getElementById('errorMessage');
  if (modal && errorText) {
    errorText.textContent = message;
    modal.classList.remove('hidden');
  } else {
    alert(message); // Fallback in case modal isn't found
  }
}

function closeModal() {
  const modal = document.getElementById('errorModal');
  if (modal) {
    modal.classList.add('hidden');
  }
}

// Show error if URL has ?error=...
document.addEventListener('DOMContentLoaded', () => {
  const params = new URLSearchParams(window.location.search);
  const error = params.get('error');
  if (error) {
    showError(decodeURIComponent(error));
    // Remove error from URL after showing
    window.history.replaceState({}, document.title, window.location.pathname);
  }
});

document.addEventListener('DOMContentLoaded', () => {
    // Get references to the tab buttons and the single login input/label
    const emailLoginTab = document.getElementById('emailLoginTab');
    const studentIdLoginTab = document.getElementById('studentIdLoginTab');
    const loginIdInput = document.getElementById('login_id');
    const loginIdLabel = document.getElementById('loginIdLabel');
    const loginButton = document.querySelector('#loginForm button[type="submit"]'); // Get the submit button of the form

    // Function to set the active tab and update the input field
    const setActiveTab = (tabToActivate) => {
        // Deactivate all tabs
        emailLoginTab.classList.remove('active');
        studentIdLoginTab.classList.remove('active');

        // Activate the selected tab
        tabToActivate.classList.add('active');

        // Update the input field based on the active tab
        if (tabToActivate.id === 'emailLoginTab') {
            loginIdLabel.textContent = 'Email:';
            loginIdInput.placeholder = 'your.email@example.com';
            loginIdInput.type = 'email'; // Set input type to email for better validation
            loginButton.textContent = 'Login with Email'; // Update button text
        } else {
            loginIdLabel.textContent = 'Student ID:';
            loginIdInput.placeholder = 'e.g., SDC12345';
            loginIdInput.type = 'text'; // Use text type for student ID
            loginButton.textContent = 'Login with Student ID'; // Update button text
        }
    };

    // Event listener for the "Login with Email" tab
    emailLoginTab.addEventListener('click', () => {
        setActiveTab(emailLoginTab);
    });

    // Event listener for the "Login with Student ID" tab
    studentIdLoginTab.addEventListener('click', () => {
        setActiveTab(studentIdLoginTab);
    });

    // Initially set the email login tab as active when the page loads
    setActiveTab(emailLoginTab);
});