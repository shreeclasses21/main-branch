document.getElementById("authForm").addEventListener("submit", async function (e) {
  e.preventDefault();

  const authCode = document.getElementById("authCode").value.trim();
  const authMsg = document.getElementById("authMsg");

  const btn = document.getElementById("proceedBtn");
  const spinner = document.getElementById("proceedSpinner");
  const btnText = document.getElementById("proceedText");

  // 🔄 Show spinner and disable button
  btn.disabled = true;
  spinner.classList.remove("hidden");
  btnText.textContent = "Checking...";

  try {
    const res = await fetch("../api/check_auth_code.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ code: authCode }),
    });

    const data = await res.json();

    if (data.status === "success") {
      sessionStorage.setItem("authorized_code", authCode);
      window.location.href = "register.php";
    } else {
      authMsg.textContent = "❌ " + data.message;
    }
  } catch (err) {
    authMsg.textContent = "❌ Something went wrong.";
  }

  // ✅ Restore button state
  btn.disabled = false;
  spinner.classList.add("hidden");
  btnText.textContent = "Proceed";
});
