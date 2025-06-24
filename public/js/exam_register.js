// ./js/exam_register.js (Same as previous response)

document.addEventListener("DOMContentLoaded", () => {
    fetchAndRenderExams();
});

async function fetchAndRenderExams() {
    const examListDiv = document.getElementById('examList');
    examListDiv.innerHTML = '<p style="text-align: center; color: #6b7280; font-size: 1.1rem; padding: 20px;">Loading exams...</p>'; // Centered loading text

    try {
        const res = await fetch('../api/fetch_available_exams.php');
        const data = await res.json();

        if (data.status !== 'success' || data.exams.length === 0) {
            examListDiv.innerHTML = `<p class="text-red-600" style="text-align: center; color: #dc2626; font-size: 1.1rem; padding: 20px; background-color: #fef2f2; border: 1px solid #fee2e2; border-radius: 8px;">No exams found for your grade.</p>`;
            return;
        }

        examListDiv.innerHTML = ''; // Clear loading text

        for (const exam of data.exams) {
            const examCard = document.createElement('div');
            examCard.className = "exam-card"; // Custom class for potential external styling later
            examCard.style = `
                width: 100%; /* Ensure card takes full width of its container */
                box-sizing: border-box; /* Include padding and border in the element's total width and height */
                border: 1px solid #e0e0e0;
                border-radius: 12px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
                padding: 25px;
                background-color: #ffffff;
                transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
                margin-bottom: 25px;
                overflow: hidden;
            `;
            // Add hover effect with JS for inline style
            examCard.onmouseover = function() {
                this.style.transform = 'translateY(-7px)';
                this.style.boxShadow = '0 15px 25px rgba(0, 0, 0, 0.15)';
            };
            examCard.onmouseout = function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = '0 4px 12px rgba(0, 0, 0, 0.08)';
            };


            examCard.innerHTML = `
                <h3 class="text-xl font-bold text-purple-700" style="
                    color: #6a0572; /* Deeper purple */
                    margin-bottom: 10px;
                    font-size: 1.8rem;
                    padding-bottom: 8px;
                    border-bottom: 1px dashed #e0e0e0;
                ">${exam.title} <span style="font-size: 0.7em; color: #9c27b0; font-weight: normal;">(Grade: ${exam.grade})</span></h3>

                <p class="text-sm text-gray-600 mt-1" style="color: #555; margin-top: 8px; line-height: 1.6;">
                    <strong style="color: #333;">📅 Exam Period:</strong> ${exam.start_date} to ${exam.end_date}
                </p>
                <p class="text-sm mt-1" style="color: #555; margin-top: 8px; line-height: 1.6;">
                    <strong style="color: #333;">📝 Description:</strong> ${exam.description || '<span style="color: #999; font-style: italic;">No description provided.</span>'}
                </p>
                <p class="text-sm mt-1" style="color: #555; margin-top: 8px; line-height: 1.6;">
                    <strong style="color: #333;">📜 Rules:</strong> ${exam.rules || '<span style="color: #999; font-style: italic;">No specific rules.</span>'}
                </p>
                <p class="text-sm mt-1" style="color: #555; margin-top: 8px; line-height: 1.6;">
                    <strong style="color: #333;">📋 Instructions:</strong> ${exam.instructions || '<span style="color: #999; font-style: italic;">No special instructions.</span>'}
                </p>

                <div class="mt-4" style="margin-top: 25px; padding-top: 20px; border-top: 1px solid #f0f0f0;">
                    <label class="block mb-2 font-semibold text-gray-700" style="color: #444; margin-bottom: 12px; font-size: 1.1rem;">
                        Select Subjects:
                    </label>
                    <div id="subjectContainer-${exam.id}" class="grid grid-cols-2 gap-2 text-sm text-gray-800" style="
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                        gap: 10px;
                        color: #333;
                        padding: 5px 0;
                    ">
                        <span class="col-span-2 text-gray-500" style="grid-column: span 2; color: #999; text-align: center;">Loading subjects...</span>
                    </div>
                </div>

                <div class="flex items-center gap-4 mt-6" style="
                    display: flex;
                    align-items: center;
                    gap: 20px;
                    margin-top: 30px;
                    justify-content: flex-start;
                ">
                    <button id="submitBtn-${exam.id}" onclick="submitExamRegistration(${exam.id})"
                        class="bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition"
                        style="
                            background-color: #673ab7; /* Deeper indigo */
                            color: #ffffff;
                            padding: 12px 25px;
                            border-radius: 8px;
                            border: none;
                            cursor: pointer;
                            font-weight: 600;
                            font-size: 1rem;
                            transition: background-color 0.2s ease-in-out, transform 0.1s ease-in-out;
                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                        "
                        onmouseover="this.style.backgroundColor='#5e35b1'; this.style.transform='translateY(-2px)';"
                        onmouseout="this.style.backgroundColor='#673ab7'; this.style.transform='translateY(0)';">
                        ✅ Submit Registration
                    </button>
                    <button id="downloadBtn-${exam.id}" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 hidden"
                        onclick="downloadExamSlip(${exam.id})"
                        style="
                            background-color: #9c27b0; /* Deeper purple */
                            color: #ffffff;
                            padding: 12px 25px;
                            border-radius: 8px;
                            border: none;
                            cursor: pointer;
                            font-weight: 600;
                            font-size: 1rem;
                            transition: background-color 0.2s ease-in-out, transform 0.1s ease-in-out;
                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                            display: none; /* Controlled by JS */
                        "
                        onmouseover="this.style.backgroundColor='#8e24aa'; this.style.transform='translateY(-2px)';"
                        onmouseout="this.style.backgroundColor='#9c27b0'; this.style.transform='translateY(0)';">
                        📥 Download Exam Slip
                    </button>
                </div>

                <div id="examStatus-${exam.id}" class="mt-4 text-sm font-semibold" style="
                    margin-top: 20px;
                    font-size: 0.95rem;
                    font-weight: 600;
                    padding: 10px 0;
                "></div>
            `;

            examListDiv.appendChild(examCard);

            await fetchSubjectsForExam(exam.id);
        }

    } catch (err) {
        examListDiv.innerHTML = `<p class="text-red-600" style="text-align: center; color: #dc2626; font-size: 1.1rem; padding: 20px; background-color: #fef2f2; border: 1px solid #fee2e2; border-radius: 8px;">Error loading exams: ${err.message}</p>`;
    }
}

async function fetchSubjectsForExam(examId) {
    const container = document.getElementById(`subjectContainer-${examId}`);
    const statusBox = document.getElementById(`examStatus-${examId}`);
    const submitBtn = document.getElementById(`submitBtn-${examId}`);
    const downloadBtn = document.getElementById(`downloadBtn-${examId}`);

    try {
        const [subjectRes, regRes] = await Promise.all([
            fetch(`../api/get_subjects_by_exam.php?exam_id=${examId}`),
            fetch(`../api/check_exam_registration.php?exam_id=${examId}`)
        ]);

        const subjectData = await subjectRes.json();
        const regData = await regRes.json();

        if (subjectData.status === 'success') {
            const registeredSubjects = regData.subjects || [];
            const isAlreadyRegistered = regData.already_registered;

            container.innerHTML = subjectData.subjects.map(subj => {
                const isChecked = registeredSubjects.includes(subj.subject_name);
                const isDisabled = isAlreadyRegistered;
                return `
                    <label class="flex items-center gap-2" style="
                        display: flex;
                        align-items: center;
                        gap: 10px;
                        cursor: ${isDisabled ? 'not-allowed' : 'pointer'};
                        padding: 8px 12px;
                        border-radius: 6px;
                        background-color: ${isChecked ? '#ede7f6' : '#f8f8f8'}; /* Light purple for checked, light gray for unchecked */
                        border: 1px solid ${isChecked ? '#d1c4e9' : '#e0e0e0'};
                        transition: background-color 0.2s, border-color 0.2s;
                        white-space: nowrap;
                    "
                    ${isDisabled ? 'title="Already registered, cannot change subjects"' : ''}>
                     <input
    type="checkbox"
    name="subject"
    value="${subj.subject_name}"
    ${isChecked ? 'checked' : ''}
    ${isDisabled ? 'disabled' : ''}
    class="form-checkbox h-5 w-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 focus:ring-2"
    style="${isDisabled ? 'cursor: not-allowed; opacity: 0.6;' : ''}"
/>

                        <span style="color: ${isDisabled ? '#888' : '#333'}; font-weight: ${isChecked ? '600' : 'normal'};">
                            ${subj.subject_name}
                        </span>
                    </label>
                `;
            }).join('');

            // Apply initial checkbox background/border/checkmark
            document.querySelectorAll(`#subjectContainer-${examId} input[type="checkbox"]`).forEach(checkbox => {
                if (checkbox.checked) {
                    checkbox.style.backgroundColor = '#673ab7'; // Deeper indigo
                    checkbox.style.borderColor = '#673ab7';
                    checkbox.style.backgroundImage = `url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='white' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e")`;
                    checkbox.style.backgroundSize = '80% 80%';
                    checkbox.style.backgroundPosition = 'center';
                    checkbox.style.backgroundRepeat = 'no-repeat';
                }
                if (checkbox.disabled) {
                    checkbox.parentElement.style.opacity = '0.7';
                    checkbox.parentElement.style.cursor = 'not-allowed';
                }
            });


            if (isAlreadyRegistered) {
                statusBox.innerHTML = `<p class="text-green-600" style="color: #28a745; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 10px; border-radius: 8px; text-align: center;">✅ You are already registered for this exam.</p>`;
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.6';
                submitBtn.style.cursor = 'not-allowed';
                submitBtn.innerText = "Already Registered";
                submitBtn.onmouseover = null; // Remove hover effect
                submitBtn.onmouseout = null; // Remove hover effect
                downloadBtn.style.display = 'inline-block'; // Show download button
            } else {
                downloadBtn.style.display = 'none'; // Hide if not registered
            }

        } else {
            container.innerHTML = `<p class="text-red-600" style="color: #dc2626; text-align: center; font-style: italic;">Failed to load subjects.</p>`;
        }

    } catch (err) {
        container.innerHTML = `<p class="text-red-600" style="color: #dc2626; text-align: center; font-style: italic;">Error loading subjects: ${err.message}</p>`;
    }
}

async function submitExamRegistration(examId) {
    const subjectCheckboxes = document.querySelectorAll(`#subjectContainer-${examId} input[type="checkbox"]:checked`);
    const selectedSubjects = Array.from(subjectCheckboxes).map(cb => cb.value);
    const statusBox = document.getElementById(`examStatus-${examId}`);

    if (selectedSubjects.length === 0) {
        statusBox.innerHTML = `<p class="text-red-600" style="color: #dc2626; background-color: #fef2f2; border: 1px solid #fee2e2; padding: 10px; border-radius: 8px; text-align: center;">❌ Please select at least one subject.</p>`;
        return;
    }

    try {
        statusBox.innerHTML = `<p style="color: #555; text-align: center;">Submitting registration...</p>`;
        const res = await fetch('../api/register_exam.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                exam_id: examId,
                subjects: selectedSubjects
            })
        });

        const result = await res.json();
        if (result.status === 'success') {
            statusBox.innerHTML = `<p class="text-green-600" style="color: #28a745; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 10px; border-radius: 8px; text-align: center;">✅ ${result.message}</p>`;
            await fetchSubjectsForExam(examId); // refresh UI to reflect registration
        } else {
            statusBox.innerHTML = `<p class="text-red-600" style="color: #dc2626; background-color: #fef2f2; border: 1px solid #fee2e2; padding: 10px; border-radius: 8px; text-align: center;">❌ Error: ${result.message}</p>`;
        }
    } catch (err) {
        statusBox.innerHTML = `<p class="text-red-600" style="color: #dc2626; background-color: #fef2f2; border: 1px solid #fee2e2; padding: 10px; border-radius: 8px; text-align: center;">Error submitting registration: ${err.message}</p>`;
    }
}

function downloadExamSlip(examId) {
    window.open(`../api/generate_exam_slip.php?exam_id=${examId}`, '_blank');
}