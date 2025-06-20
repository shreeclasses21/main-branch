// main.js

document.addEventListener("DOMContentLoaded", () => {
  // Smooth scroll for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      const target = document.querySelector(this.getAttribute("href"));
      if (target) {
        target.scrollIntoView({ behavior: "smooth" });
      }
    });
  });


  // Animate hero section
  const heroHeading = document.querySelector("section h1");
  if (heroHeading) {
    heroHeading.classList.add("hero-animate");
  }
});

document.addEventListener("DOMContentLoaded", () => {
  const cookieName = "shree_cookie_consent";
  const cookieModal = document.getElementById("cookieModal");
  const acceptBtn = document.getElementById("acceptCookieBtn");

  function getCookie(name) {
    const match = document.cookie.match(new RegExp("(^| )" + name + "=([^;]+)"));
    return match ? decodeURIComponent(match[2]) : null;
  }

  // Show modal if cookie not found
  if (!getCookie(cookieName)) {
    cookieModal.classList.remove("hidden");
  }

  // Accept button click
  acceptBtn.addEventListener("click", () => {
    // Prepare consent object
    const consentInfo = {
      consent: true,
      timestamp: new Date().toISOString(),
      browser: navigator.userAgent,
      language: navigator.language
    };

    // Save cookie
    document.cookie = `${cookieName}=${encodeURIComponent(JSON.stringify(consentInfo))}; max-age=31536000; path=/`;

    // Hide modal
    cookieModal.classList.add("hidden");

    // Send to server
    fetch("api/log_cookie_consent.php", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify(consentInfo)
    });
  });
});

// Toggle mobile menu
document.addEventListener("DOMContentLoaded", () => {
  const menuToggle = document.getElementById("menuToggle");
  const mobileMenu = document.getElementById("mobileMenu");

  menuToggle.addEventListener("click", () => {
    mobileMenu.classList.toggle("hidden");
  });
});

document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("contactForm");
  const submitBtn = document.getElementById("submitBtn");
  const spinner = document.getElementById("spinner");
  const submitText = document.getElementById("submitText");

  form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const name = form.querySelector('input[name="name"]').value.trim();
    const email = form.querySelector('input[name="email"]').value.trim();
    const message = form.querySelector('textarea[name="message"]').value.trim();

    if (!name || !email || !message) {
      alert("Please fill in all fields.");
      return;
    }

    // 🔄 Show spinner
    submitBtn.disabled = true;
    spinner.classList.remove("hidden");
    submitText.textContent = "Submitting...";

    try {
      const res = await fetch("api/submit_contact.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name, email, message })
      });

      const result = await res.json();
      alert(result.message || result.error || "Unknown response.");
      if (result.status === 'success') form.reset();
    } catch (err) {
      alert("❌ Server not reachable.");
      console.error("Fetch error:", err);
    }

    // ✅ Hide spinner and reset button
    submitBtn.disabled = false;
    spinner.classList.add("hidden");
    submitText.textContent = "Submit";
  });
});
