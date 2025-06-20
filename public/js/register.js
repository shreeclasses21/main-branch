document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("registerForm");

  if (!form) return;

  const btn = document.getElementById("sendOtpBtn");
  const spinner = document.getElementById("sendOtpSpinner");
  const btnText = document.getElementById("sendOtpText");

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const firstNameEl = document.getElementById("first_name");
    const lastNameEl = document.getElementById("last_name");
    const emailEl = document.getElementById("email");

    const payload = {
      first_name: firstNameEl.value.trim(),
      last_name: lastNameEl.value.trim(),
      email: emailEl.value.trim()
    };

    if (!payload.first_name || !payload.last_name || !payload.email) {
      alert("⚠️ Please fill in all fields.");
      return;
    }

    // 🔄 Show spinner
    btn.disabled = true;
    spinner.classList.remove("hidden");
    btnText.textContent = "Sending...";

    try {
      const res = await fetch("../api/register_with_otp.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(payload)
      });

      const data = await res.json();

      if (data.status === "success") {
        // 🔁 Delay before redirection to see spinner effect
        setTimeout(() => {
          window.location.href = data.redirect;
        }, 800); // Show spinner at least for 800ms
      } else {
        alert("❌ " + (data.message || "Something went wrong."));
        // Reset UI
        btn.disabled = false;
        spinner.classList.add("hidden");
        btnText.textContent = "Send OTP";
      }

    } catch (err) {
      alert("❌ Server error.");
      btn.disabled = false;
      spinner.classList.add("hidden");
      btnText.textContent = "Send OTP";
    }
  });
});
