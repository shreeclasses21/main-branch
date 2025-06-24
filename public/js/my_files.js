 function initMyFiles() {
            const filesContainer = document.getElementById('filesContainer');
            const noFilesMessage = document.getElementById('noFilesMessage');

            // Fetch data from the API endpoint
            fetch('../api/student_files.php')
                .then(res => {
                    if (!res.ok) {
                        throw new Error(`HTTP error! status: ${res.status}`);
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.status === 'success' && Object.keys(data.files).length > 0) {
                        let hasVisibleFiles = false;
                        filesContainer.innerHTML = ''; // Clear previous content

                        Object.entries(data.files).forEach(([section, files]) => {
                            // Only create a section block if there are files in it
                            if (files.length > 0) {
                                hasVisibleFiles = true;
                                const sectionBlock = document.createElement('div');
                                sectionBlock.classList.add('mb-8', 'col-span-full'); // Make section headers span all columns

                                sectionBlock.innerHTML = `
                                    <h3 class="text-xl font-bold mb-4 text-indigo-700 border-b-2 border-indigo-200 pb-2">
                                        📚 ${section}
                                    </h3>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                                        ${files.map(file => `
                                            <div class="bg-white rounded-xl p-5 shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                                                <div class="flex items-center mb-2">
                                                    <svg class="w-6 h-6 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                    </svg>
                                                    <div class="font-medium text-gray-800 text-lg truncate flex-grow">
                                                        ${file.file_name}
                                                    </div>
                                                </div>
                                                <div class="text-sm text-gray-500 mt-2">
                                                    Uploaded on: ${new Date(file.uploaded_at).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })}
                                                </div>
                                                <a href="${file.file_path}" download="${file.file_name}" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
                                                    <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l3-3m-3 3L9 13m0 10H5a2 2 0 01-2-2V6a2 2 0 012-2h7.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-3.375a2.25 2.25 0 01-2.25-2.25M12 21.75V15.75M12 21.75a2.25 2.25 0 00-2.25-2.25M12 21.75a2.25 2.25 0 01-2.25-2.25m4.5 0a2.25 2.25 0 01-2.25 2.25m0 0a2.25 2.25 0 002.25 2.25m-2.25-2.25h-3.375c-.621 0-1.125-.504-1.125-1.125V11.25m11.25-1.5v4.5m-1.5-4.5H12a2.25 2.25 0 00-2.25 2.25v.75m6.75-4.5H15m0 0H5.625c-.621 0-1.125.504-1.125 1.125v13.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125Z" />
                                                    </svg>
                                                    Download File
                                                </a>
                                            </div>
                                        `).join('')}
                                    </div>
                                `;
                                filesContainer.appendChild(sectionBlock);
                            }
                        });

                        // Show "no files" message if no sections had files
                        if (!hasVisibleFiles) {
                            noFilesMessage.classList.remove('hidden');
                        } else {
                            noFilesMessage.classList.add('hidden');
                        }

                    } else {
                        filesContainer.innerHTML = ''; // Ensure container is empty
                        noFilesMessage.classList.remove('hidden'); // Show no files message if status is not success or no files
                    }
                })
                .catch(err => {
                    console.error('Error fetching student files:', err);
                    // Display a user-friendly message without using alert()
                    filesContainer.innerHTML = `
                        <div class="col-span-full text-center py-8 text-red-600 bg-red-50 rounded-lg border border-red-200">
                            <p class="font-semibold mb-2">Failed to load your files.</p>
                            <p class="text-sm">Please check your internet connection or try again later. Error: ${err.message}</p>
                        </div>
                    `;
                    noFilesMessage.classList.add('hidden'); // Ensure no files message is hidden if there's an error.
                });
        }

        // Initialize files when the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', initMyFiles);