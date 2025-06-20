document.getElementById("otpForm").addEventListener("submit", async function (e) {
  e.preventDefault();

  const otpCode = document.getElementById("otpCode").value.trim();
  const otpMsg = document.getElementById("otpMsg");

  try {
    const res = await fetch("../api/validate_otp_register.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ otp: otpCode })
    });

    const data = await res.json();

    if (data.status === "success") {
      window.location.href = "../public/login.php";
    } else {
      otpMsg.textContent = "❌ " + data.message;
    }
  } catch (err) {
    otpMsg.textContent = "❌ Something went wrong.";
  }
});
