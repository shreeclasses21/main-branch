<?php
session_start();
if (!isset($_SESSION['contact_id'])) {
echo "<p class='text-red-500 text-center mt-10'>Please log in to view your profile.</p>";
exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Isolated Student Profile Component</title>

<link rel="stylesheet" href="./css/student_profile.css"/>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Roboto+Slab:wght@400;700&display=swap" rel="stylesheet">
</head>
<body>

<div class="isolated-component-wrapper">


<div class="profile-container">
    <div class="profile-glow"></div>

    <section class="profile-card">
        <h1 class="profile-title">
         Student Profile
        </h1>

        <p id="profileMessage" class="profile-message"></p>

        <div id="profileDisplay" class="profile-display-section">
            <div class="profile-avatar-wrapper">
                <div class="profile-avatar-ring">
                    <img id="profilePhoto" class="profile-avatar-img" alt="Profile Photo"/>
                    <div id="profilePhotoPlaceholder" class="profile-avatar-placeholder">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 14c-4.33 0-8 2.015-8 4.357V20h16v-1.643C20 16.015 16.33 14 12 14z"/>
                            <circle cx="12" cy="8" r="4"/>
                        </svg>
                    </div>
                    <p id="displayPhotoUrl" class="profile-photo-url-display"></p>
                </div>
            </div>
            <div>
                <h2 class="section-heading">Personal Information</h2>
                <dl class="info-grid">
                    <dt>Full Name</dt><dd id="displayName"></dd>
                    <dt>Email</dt><dd id="displayEmail"></dd>
                    <dt>Student ID</dt><dd id="displayStudentId"></dd>
                    <dt>Status</dt><dd id="displayStatus"></dd>
                    <dt>Phone</dt><dd id="displayPhone"></dd>
                    <dt>Mobile</dt><dd id="displayMobile"></dd>
                    <dt>Date of Birth</dt><dd id="displayDob"></dd>
                    <dt>Guardian Name</dt><dd id="displayGuardianName"></dd>
                </dl>
            </div>

            <div>
                <h2 class="section-heading">Academic Information</h2>
                <dl class="info-grid">
                    <dt>Board</dt><dd id="displayBoard"></dd>
                    <dt>Section</dt><dd id="displaySection"></dd>
                    <dt>Grade</dt><dd id="displayGrade"></dd>
                </dl>
            </div>

            <div>
                <h2 class="section-heading">Home Address</h2>
                <p id="displayMailingAddress" class="mailing-address-text"></p>
            </div>

            <div class="profile-actions">
                <button id="editProfileBtn" class="btn-primary">Update My Profile</button>
            </div>
        </div>

        <form id="studentProfileForm" class="profile-form hidden">
            <input type="hidden" name="contactId" id="contactId"/>

            <div><h2 class="form-heading">Personal Information</h2></div>
            <div class="form-grid">
                <div class="form-field-readonly"><label>Full Name</label><input id="formName" name="Name" readonly></div>
                <div class="form-field-readonly"><label>Email</label><input id="formEmail" name="Email" type="email" readonly></div>
                <div class="form-field-readonly"><label>Student ID</label><input id="formStudent_ID__c" name="Student_ID__c" readonly></div>
                <div class="form-field-readonly"><label>Status</label><input id="formStatus__c" name="Status__c" readonly></div>

                <div><label for="Phone">Phone</label><input id="Phone" name="Phone" type="tel" class="input-field"></div>
                <div><label for="MobilePhone">Mobile</label><input id="MobilePhone" name="MobilePhone" type="tel" class="input-field"></div>
                <div><label for="Date_of_Birth__c">Date of Birth</label><input id="Date_of_Birth__c" name="Date_of_Birth__c" type="date" class="input-field"></div>
                <div><label for="Guardian_Name__c">Guardian Name</label><input id="Guardian_Name__c" name="Guardian_Name__c" class="input-field"></div>

                <div class="form-field-full-width">
                    <label for="Profile_Photo_URL__c">Profile Photo URL</label>
                    <input id="Profile_Photo_URL__c" name="Profile_Photo_URL__c" type="text" class="input-field" placeholder="https://example.com/me.jpg">
                </div>
                <!-- ✅ New: Upload photo control inside form -->
<div class="form-field-full-width">
<label for="profileImage">Upload Profile Photo</label>
<input type="file" name="profileImage" id="profileImage" accept="image/*" class="input-field" />
<button type="button" id="uploadPhotoBtn" class="btn-primary mt-2">Upload Profile Photo</button>
</div>
            </div>

            <div><h2 class="form-heading">Academic Information</h2></div>
            <div class="form-grid">
                <div><label for="Board__c">Board</label><select id="Board__c" name="Board__c" class="input-field"></select></div>
                <div><label for="Section__c">Section</label><select id="Section__c" name="Section__c" class="input-field"></select></div>
                <div><label for="Current_Grade__c">Grade</label><input id="Current_Grade__c" name="Current_Grade__c" type="number" step="0.01" class="input-field"></div>
            </div>

            <div><h2 class="form-heading">Mailing Address</h2></div>
            <div class="form-grid">
                <div class="form-field-full-width"><label for="MailingStreet">Street</label><textarea id="MailingStreet" name="MailingStreet" rows="3" class="input-field"></textarea></div>
                <div><label for="MailingCity">City</label><input id="MailingCity" name="MailingCity" class="input-field"></div>
                <div><label for="MailingCountryCode">Country</label><select id="MailingCountryCode" name="MailingCountryCode" class="input-field"></select></div>
                <div><label for="MailingStateCode">State / Province</label><select id="MailingStateCode" name="MailingStateCode" class="input-field"><option value="">--state--</option></select></div>
                <div><label for="MailingPostalCode">Postal Code</label><input id="MailingPostalCode" name="MailingPostalCode" class="input-field"></div>
            </div>

            <div class="form-actions">
                <button type="button" id="cancelEditBtn" class="btn-secondary">Cancel</button>
                <button id="saveProfileBtn" class="btn-primary opacity-50 cursor-not-allowed" disabled>Save Profile</button>
            </div>
        </form>
    </section>
</div>
</div> 
<div id="loadingOverlay">
  <div class="loader"></div>
</div> <script src="./js/student_profile.js"></script>
</body>
</html>