/* global fetch, document */

window.initStudentProfile = async function () {

  /* ───────────────────────── DOM refs ───────────────────────── */
  const $ = id => document.getElementById(id);

  const profileDisplay = $("profileDisplay");
  const studentProfileForm = $("studentProfileForm");
  const editProfileBtn = $("editProfileBtn");
  const saveProfileBtn = $("saveProfileBtn");
  const cancelEditBtn = $("cancelEditBtn");
  const msgBox = $("profileMessage");
  const profilePhoto = $("profilePhoto"); // The <img> tag
  const profilePhotoPlaceholder = $("profilePhotoPlaceholder"); // The <div> for the placeholder icon


  let studentData = {};   // DTO from SF (Data Transfer Object from Salesforce)
  let allStates = [];   // Cached list of all states/provinces [{value,label,country}, …]

  /* ───────── display-id map ───────── */
  // Maps DTO keys to HTML element IDs for displaying data
  const displayMap = {
    name: "displayName",
    email: "displayEmail",
    studentId: "displayStudentId",
    status: "displayStatus",
    phone: "displayPhone",
    mobile: "displayMobile",
    dob: "displayDob",
    guardianName: "displayGuardianName",
    photoUrl: "displayPhotoUrl",
    board: "displayBoard",
    section: "displaySection",
    grade: "displayGrade"
  };

  /* ───────── form ↔ DTO map ───────── */
  // Maps HTML form element names to DTO keys for data transfer
  const fieldMap = {
    contactId: "contactId",
    Name: "name",
    Email: "email",
    Student_ID__c: "studentId",
    Status__c: "status",

    Phone: "phone",
    MobilePhone: "mobile",
    Date_of_Birth__c: "dob",
    Guardian_Name__c: "guardianName",
    Profile_Photo_URL__c: "photoUrl",

    Board__c: "board",
    Section__c: "section",
    Current_Grade__c: "grade",

    MailingStreet: "mailingStreet",
    MailingCity: "mailingCity",
    MailingStateCode: "mailingState",
    MailingPostalCode: "mailingPostal",
    MailingCountryCode: "mailingCountry",


  };

  /* ─────────────────── SF pick-lists ─────────────────── */

  /**
   * Loads 'Board__c' and 'Section__c' picklist values from the API
   * and populates the respective <select> elements.
   */
  async function loadBoardSection() {
    try {
      const res = await fetch("../api/get_contact_picklists.php");
      if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
      const pl = await res.json();
      if (pl.error) throw new Error(pl.error);

      ["Board__c", "Section__c"].forEach(api => {
        const sel = $(api);
        if (!sel) { console.warn(`⚠️ <select id="${api}"> not found`); return; }
        sel.innerHTML =
          `<option value="">-- Select ${api.replace('__c', '').replace('_', ' ')} --</option>` +
          pl[api].map(v => `<option value="${v}">${v}</option>`).join('');
      });
    } catch (error) {
      console.error("Error loading board/section picklists:", error);
      showToast("❌ Failed to load academic options.");
    }
  }

  /**
   * Loads country picklist values from the API and populates the
   * 'MailingCountryCode' <select> element.
   */
  async function loadCountries() {
    try {
      const res = await fetch("../api/get_countries.php");
      if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
      const countries = await res.json();
      if (countries.error) throw new Error(countries.error);

      const sel = $("MailingCountryCode");
      if (!sel) { console.warn('⚠️ <select id="MailingCountryCode"> not found'); return; }
      sel.innerHTML =
        '<option value="">-- Select Country --</option>' +
        countries.map(c => `<option value="${c.value}">${c.label}</option>`).join('');
    } catch (error) {
      console.error("Error loading countries:", error);
      showToast("❌ Failed to load country options.");
    }
  }

  /**
   * Attempts to preload all states/provinces from a local API.
   * If it fails, `renderStatesFor` will fall back to an on-demand public API.
   */
  async function ensureStatesLoaded() {
    if (allStates.length) return; /* already cached */
    try {
      const res = await fetch("../api/get_all_states.php");
      if (!res.ok) throw new Error("file not found or API error");
      allStates = await res.json(); /* [{value,label,country}] */
    } catch (e) {
      /* Not fatal – we’ll fetch per-country later */
      console.warn("⚠️ Couldn’t preload master state list – will use on-demand API.", e);
      allStates = []; /* make sure it’s an array if preload failed */
    }
  }

  /**
   * Populates the 'MailingStateCode' <select> with states/provinces
   * for the selected country code. Prioritizes cached data, falls back
   * to CountriesNow public API if needed.
   * @param {string} countryCode - The ISO-2 country code.
   */
  async function renderStatesFor(countryCode) {
    const sSel = document.getElementById("MailingStateCode");
    if (!sSel) { console.warn("⚠️ #MailingStateCode missing"); return; }

    // Clear existing options if no country is selected or if a new country is selected
    sSel.innerHTML = '<option value="">-- Select State / Province --</option>';
    if (!countryCode) return;

    /* 1. check the local cache --------------------------------- */
    let list = allStates.filter(s => s.country === countryCode);

    /* 2. if we have nothing, call the public API once ---------- */
    if (list.length === 0) {
      try {
        const r = await fetch("https://countriesnow.space/api/v0.1/countries/states",
          {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ iso2: countryCode })
          });
        const j = await r.json();
        if (!j.data || !j.data.states) throw new Error("Invalid response from states API");
        list = (j.data.states).map(st => ({
          value: st.state_code || st.name,    /* try to keep code; fallback to name */
          label: st.name,
          country: countryCode
        }));
        allStates.push(...list);              /* cache for future selections        */
      } catch (err) {
        console.error("State API error:", err);
        // Don't clear options, leave "Select State..." or previous valid ones
        return; // Exit if API fails, don't populate with empty options
      }
    }

    /* 3. populate the <select> -------------------------------- */
    sSel.innerHTML = `
          <option value="">-- Select State / Province --</option>
          ${list.map(s => `<option value="${s.value}">${s.label}</option>`).join("")}`;
  }

  /* Event listener to update state list when country changes */
  document.addEventListener("change", evt => {
    if (evt.target && evt.target.id === "MailingCountryCode") {
      renderStatesFor(evt.target.value);
    }
  });


  /* ─────────────────── loadProfile ─────────────────── */
  /**
   * Loads the student's profile data, populates display elements,
   * and fills the form fields.
   */
  async function loadProfile() {
  showToast("Loading profile…");
  showOverlaySpinner();

  try {
    // Load picklists and states in parallel
    await Promise.all([
      loadBoardSection(),
      loadCountries(),
      ensureStatesLoaded()
    ]);

    const res = await fetch("../api/get_student_profile.php");
    if (!res.ok) throw new Error(`HTTP error! status: ${res.status}`);
    const data = await res.json();
    if (data.error) throw new Error(data.error);

    studentData = data;

    // Country dropdown first
    const cSel = $("MailingCountryCode");
    if (cSel) cSel.value = data.mailingCountry || '';

    // Then populate and set state
    await renderStatesFor(data.mailingCountry).then(() => {
      const sSel = $("MailingStateCode");
      if (sSel && data.mailingState) {
        sSel.value = data.mailingState;
      }
    });

    // 🎯 Fill visible display card
    for (const [k, id] of Object.entries(displayMap)) {
      const el = $(id);
      if (!el) continue;

      const val = data[k];

      if (k === 'dob' && val) {
        el.textContent = new Date(val).toLocaleDateString('en-US', {
          year: 'numeric', month: 'long', day: 'numeric'
        });
      } else if (k === 'grade') {
        if (val !== undefined && val !== null && !isNaN(val)) {
          el.textContent = parseFloat(val) % 1 === 0
            ? parseInt(val)
            : parseFloat(val).toFixed(2);
        } else {
          el.textContent = 'N/A';
        }
      } else {
        el.textContent = (val && val.toString().trim() !== "") ? val : 'N/A';
      }
    }

    // 🏠 Mailing address block
    const addr = $("displayMailingAddress");
    if (addr) {
      addr.textContent = [
        data.mailingStreet,
        data.mailingCity,
        data.mailingState,
        data.mailingPostal,
        data.mailingCountry
      ].filter(Boolean).join(', ') || 'N/A';
    }

    // 👤 Photo handling
    if (data.photoUrl) {
      profilePhoto.src = data.photoUrl;
      profilePhoto.classList.remove('hidden');
      profilePhotoPlaceholder.classList.add('hidden');
    } else {
      profilePhoto.src = './uploads/default-avatar.png';
      profilePhoto.classList.remove('hidden');
      profilePhotoPlaceholder.classList.add('hidden');
    }

    // 📝 Fill form fields
    for (const [htmlId, dtoKey] of Object.entries(fieldMap)) {
      const el = studentProfileForm.elements[htmlId];
      if (el && data[dtoKey] !== undefined && data[dtoKey] !== null) {
        el.value = data[dtoKey];
      } else if (el) {
        el.value = '';
      }
    }

    studentProfileForm.elements.contactId.value = data.contactId;

    showToast("✅ Profile loaded successfully!");
  } catch (e) {
    console.error("Error loading profile:", e);
    showToast("❌ " + e.message, "error");
  } finally {
    hideOverlaySpinner();
  }
}



  /* ───────── UI Toggle Helpers ───────── */
  /** Shows the profile display and hides the form. */
  function showDisplay() {
    profileDisplay.classList.remove('hidden');
    studentProfileForm.classList.add('hidden');

    saveProfileBtn.disabled = !data.isFirstProfile;
    saveProfileBtn.classList.toggle('opacity-50', !data.isFirstProfile);
    saveProfileBtn.classList.toggle('cursor-not-allowed', !data.isFirstProfile);
  }

  /** Shows the edit form and hides the display. */
  function showForm() {
  profileDisplay.classList.add('hidden');
  studentProfileForm.classList.remove('hidden');

  const locked = !studentData.isFirstProfile;

Array.from(studentProfileForm.elements).forEach(el => {
  const coreReadOnlyFields = ['formName', 'formEmail', 'formStudent_ID__c', 'formStatus__c'];
  const isSelect = el.tagName === "SELECT";

  if (coreReadOnlyFields.includes(el.id)) {
    el.readOnly = true;
    el.disabled = true;
    el.classList.add('input[readonly]');
    el.classList.remove('input');
  } else {
    if (isSelect) {
      el.disabled = locked; // ✅ Disable select dropdown
    } else {
      el.readOnly = locked;
    }

    if (locked) {
      el.classList.add('input[readonly]');
      el.classList.remove('input');
    } else {
      el.classList.add('input');
      el.classList.remove('input[readonly]');
    }
  }
});


  // ✅ Control Save button availability + visual feedback
  if (locked) {
    saveProfileBtn.disabled = true;
    saveProfileBtn.classList.add('opacity-50', 'cursor-not-allowed');
  } else {
    saveProfileBtn.disabled = false;
    saveProfileBtn.classList.remove('opacity-50', 'cursor-not-allowed');
  }

  // ✅ Control Upload Photo section
  const uploadPhotoInput = document.getElementById("profileImage");
  const uploadPhotoBtn = document.getElementById("uploadPhotoBtn");

  if (uploadPhotoInput && uploadPhotoBtn) {
    if (locked) {
      uploadPhotoInput.disabled = true;
      uploadPhotoBtn.disabled = true;
      uploadPhotoBtn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
      uploadPhotoInput.disabled = false;
      uploadPhotoBtn.disabled = false;
      uploadPhotoBtn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
  }
}


  // Event listeners for switching between display and edit modes
  editProfileBtn?.addEventListener('click', showForm);
  cancelEditBtn?.addEventListener('click', showDisplay);

  /* ───────── Form Submit Handler ───────── */
  studentProfileForm.onsubmit = async e => {
    e.preventDefault(); // Prevent default form submission

   // Check if editing is allowed based on 'isFirstProfile'
    if (!studentData.isFirstProfile) {
    showToast("❌ Profile already completed – editing not allowed.");
      return;
    }

   showToast("⏳ Saving profile…");
   showOverlaySpinner();


    const dto = {}; // Data Transfer Object to send to the server
    // Populate DTO from form fields
    for (const [html, dtoKey] of Object.entries(fieldMap)) {
      const el = studentProfileForm.elements[html];
      if (!el) continue; // Skip if element doesn't exist
      const v = el.value || null; // Get value, convert empty string to null

      // Special handling for grade (ensure it's a float)
      dto[dtoKey] = (dtoKey === 'grade' && v) ? parseFloat(v) : v;
    }
    dto.contactId = studentData.contactId; // Ensure contact ID is included

    try {
      const r = await fetch("../api/update_student_profile.php", {
        method: "PATCH", // Use PATCH for partial updates
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(dto) // Send DTO as JSON
      });

      // Check for HTTP errors before parsing JSON
      if (!r.ok) {
        const errorText = await r.text();
        throw new Error(`Server responded with status ${r.status}: ${errorText}`);
      }

      const out = await r.json(); // Parse server response
      if (out.error || out.success === false) {
        throw new Error(out.msg || out.error || "An unknown error occurred during update.");
      }

      showToast("✅ Profile updated successfully!");
      await loadProfile(); // Reload profile to reflect changes
      hideOverlaySpinner();
      showDisplay(); // Switch back to display mode
      
    }
    catch (e) {
      console.error("Error saving profile:", e);
    
    }
  };

  /* Initial load of the profile when the page is ready */
  await loadProfile();
  hideOverlaySpinner();

};

// Ensure initStudentProfile is called once the DOM is fully loaded
document.addEventListener("DOMContentLoaded", () => {
  if (typeof initStudentProfile === "function") {
    initStudentProfile();
  } else {
    console.error("initStudentProfile() function not found.");
  }
});

document.getElementById('uploadPhotoBtn')?.addEventListener('click', async function () {
  const fileInput = document.getElementById('profileImage');
  if (!fileInput.files[0]) {
    alert("Please choose an image.");
    return;
  }

  const formData = new FormData();
  formData.append('profileImage', fileInput.files[0]);

  try {
    const response = await fetch('../api/upload_photo.php', {
      method: 'POST',
      body: formData
    });

    const result = await response.json();

    if (result.success) {
      // Update avatar
      const avatarImg = document.getElementById('profilePhoto');
      const avatarPlaceholder = document.getElementById('profilePhotoPlaceholder');
      const displayPhotoUrl = document.getElementById('displayPhotoUrl');

      avatarImg.src = result.url;
      avatarImg.classList.remove('hidden');
      avatarPlaceholder.classList.add('hidden');

      if (displayPhotoUrl) displayPhotoUrl.textContent = result.url;

      // Update hidden form field so photo URL gets saved on form submit
      const urlField = document.getElementById('Profile_Photo_URL__c');
      if (urlField) urlField.value = result.url;

      alert("✅ Profile photo uploaded!");
    } else {
      alert("❌ Upload failed: " + (result.message || result.error));
    }
  } catch (err) {
    console.error("Upload error:", err);
    alert("❌ Upload failed.");
  }
});
function showOverlaySpinner() {
  document.getElementById("loadingOverlay").style.display = "flex";
}
function hideOverlaySpinner() {
  document.getElementById("loadingOverlay").style.display = "none";
}

function showToast(message, type = 'success') {
  const toast = document.createElement('div');
  toast.textContent = message;
  toast.className = `fixed top-6 right-6 z-50 px-4 py-2 rounded shadow-lg text-white transition-opacity duration-300
                    ${type === 'error' ? 'bg-red-600' : 'bg-green-600'}`;

  document.body.appendChild(toast);

  setTimeout(() => {
    toast.style.opacity = '0';
    setTimeout(() => toast.remove(), 300);
  }, 3000);
}
