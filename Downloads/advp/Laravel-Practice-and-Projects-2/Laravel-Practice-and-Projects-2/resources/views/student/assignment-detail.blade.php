<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Details - Educational Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .file-drop-zone {
            border: 2px dashed #cbd5e0;
            transition: all 0.3s ease;
        }
        .file-drop-zone.dragover {
            border-color: #4f46e5;
            background-color: rgba(79, 70, 229, 0.1);
        }
    </style>
</head>
<body class="min-h-screen gradient-bg">
    <!-- Navigation -->
    <nav class="glassmorphism m-4 p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button onclick="goBack()" class="text-white hover:text-indigo-200 transition-colors">
                    <i class="fas fa-arrow-left text-xl"></i>
                </button>
                <h1 class="text-xl font-bold text-white">Assignment Details</h1>
            </div>
            <div class="flex items-center space-x-4">
                <span class="text-white text-sm" id="student-name">Loading...</span>
                <button onclick="logout()" class="text-white hover:text-red-300 transition-colors">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Loading State -->
    <div id="loading" class="flex items-center justify-center min-h-[60vh]">
        <div class="glassmorphism p-8 text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white mx-auto mb-4"></div>
            <p class="text-white">Loading assignment details...</p>
        </div>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="hidden mx-4 mb-6">
        <!-- Assignment Header -->
        <div class="glassmorphism p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                <div class="flex-1">
                    <h1 id="assignment-title" class="text-3xl font-bold text-white mb-2">Assignment Title</h1>
                    <p id="assignment-description" class="text-white/80 mb-4">Assignment description...</p>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="bg-white/20 rounded-lg p-3">
                            <div class="text-indigo-200 text-sm">Due Date</div>
                            <div class="text-white font-semibold" id="due-date">Loading...</div>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <div class="text-indigo-200 text-sm">Max Score</div>
                            <div class="text-white font-semibold" id="max-score">Loading...</div>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <div class="text-indigo-200 text-sm">Status</div>
                            <div class="text-white font-semibold" id="submission-status">Loading...</div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 lg:mt-0 lg:ml-6">
                    <div id="submission-actions" class="space-y-2">
                        <!-- Action buttons will be populated here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignment Instructions -->
        <div class="glassmorphism p-6 mb-6">
            <h3 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-list-ul mr-2"></i>Instructions
            </h3>
            <div id="assignment-instructions" class="text-white/90 prose prose-invert max-w-none">
                Loading instructions...
            </div>
        </div>

        <!-- Current Submission -->
        <div id="current-submission" class="glassmorphism p-6 mb-6 hidden">
            <h3 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-file-alt mr-2"></i>Your Submission
            </h3>
            <div id="submission-content">
                <!-- Submission details will be loaded here -->
            </div>
        </div>

        <!-- Submission Form -->
        <div id="submission-form" class="glassmorphism p-6 mb-6 hidden">
            <h3 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-upload mr-2"></i>Submit Assignment
            </h3>

            <form id="assignment-submission-form" class="space-y-6">
                <!-- Text Submission -->
                <div>
                    <label for="submission-text" class="block text-white font-medium mb-2">
                        Written Response (Optional)
                    </label>
                    <textarea
                        id="submission-text"
                        name="submission_text"
                        rows="6"
                        class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
                        placeholder="Enter your written response here..."></textarea>
                </div>

                <!-- File Upload -->
                <div>
                    <label class="block text-white font-medium mb-2">
                        File Upload (Optional)
                    </label>
                    <div id="file-drop-zone" class="file-drop-zone rounded-lg p-8 text-center bg-white/10">
                        <input type="file" id="file-input" name="file" class="hidden" accept=".pdf,.doc,.docx,.txt,.zip">
                        <div id="drop-zone-content">
                            <i class="fas fa-cloud-upload-alt text-4xl text-white/60 mb-4"></i>
                            <p class="text-white/80 mb-2">Drag and drop your file here, or click to browse</p>
                            <p class="text-white/60 text-sm">Supported formats: PDF, DOC, DOCX, TXT, ZIP (Max: 10MB)</p>
                        </div>
                        <div id="file-preview" class="hidden">
                            <!-- File preview will be shown here -->
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="cancelSubmission()" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                        Cancel
                    </button>
                    <button type="submit" id="submit-btn" class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Assignment
                    </button>
                </div>
            </form>
        </div>

        <!-- Grading Information -->
        <div id="grading-info" class="glassmorphism p-6 hidden">
            <h3 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-star mr-2"></i>Grading Information
            </h3>
            <div id="grade-content">
                <!-- Grade and feedback will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50"></div>

    <script>
        // Global variables
        const courseId = {{ $course_id }};
        const assignmentId = {{ $assignment_id }};
        let authToken = localStorage.getItem('token');
        let assignmentData = null;
        let submissionData = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken) {
                window.location.href = '/login';
                return;
            }

            loadAssignmentData();
            loadUserInfo();
            setupFileUpload();
        });

        // API call utility function
        async function apiCall(endpoint, method = 'GET', data = null, isFormData = false) {
            const headers = {
                'Accept': 'application/json',
                'Authorization': `Bearer ${authToken}`
            };

            if (!isFormData) {
                headers['Content-Type'] = 'application/json';
            }

            const config = { method, headers };
            if (data) {
                config.body = isFormData ? data : JSON.stringify(data);
            }

            try {
                const response = await fetch(`/api${endpoint}`, config);

                if (response.status === 401) {
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                    return null;
                }

                const result = await response.json();
                return { status: response.status, ok: response.ok, data: result };
            } catch (error) {
                console.error('API call error:', error);
                return { status: 0, ok: false, error: error.message };
            }
        }

        // Load assignment data
        async function loadAssignmentData() {
            try {
                const result = await apiCall(`/student/assignments/${assignmentId}`);

                if (result && result.ok) {
                    assignmentData = result.data;
                    displayAssignmentData();

                    // Load submission if exists
                    if (assignmentData.submission) {
                        submissionData = assignmentData.submission;
                        displaySubmission();
                    }

                    hideLoading();
                } else {
                    showNotification('Failed to load assignment data', 'error');
                    setTimeout(() => {
                        goBack();
                    }, 2000);
                }
            } catch (error) {
                console.error('Error loading assignment data:', error);
                showNotification('Error loading assignment data', 'error');
            }
        }

        // Display assignment data
        function displayAssignmentData() {
            if (!assignmentData) return;

            document.getElementById('assignment-title').textContent = assignmentData.title;
            document.getElementById('assignment-description').textContent = assignmentData.description || 'No description provided';
            document.getElementById('assignment-instructions').innerHTML = assignmentData.instructions || 'No specific instructions provided';

            const dueDate = assignmentData.due_date ? new Date(assignmentData.due_date).toLocaleString() : 'No due date';
            document.getElementById('due-date').textContent = dueDate;
            document.getElementById('max-score').textContent = `${assignmentData.max_score || 'N/A'} points`;

            // Update status and actions
            updateSubmissionStatus();
        }

        // Update submission status and available actions
        function updateSubmissionStatus() {
            const statusElement = document.getElementById('submission-status');
            const actionsElement = document.getElementById('submission-actions');

            if (submissionData) {
                const status = submissionData.status;
                const grade = submissionData.grade;

                if (status === 'graded' && grade !== null) {
                    statusElement.innerHTML = `<span class="text-green-300">Graded (${grade}%)</span>`;
                    document.getElementById('grading-info').classList.remove('hidden');
                    displayGrading();
                } else if (status === 'submitted') {
                    statusElement.innerHTML = `<span class="text-blue-300">Submitted</span>`;
                } else {
                    statusElement.innerHTML = `<span class="text-yellow-300">Draft</span>`;
                }

                document.getElementById('current-submission').classList.remove('hidden');
            } else {
                statusElement.innerHTML = `<span class="text-gray-300">Not Submitted</span>`;
            }

            // Show appropriate actions
            if (assignmentData.can_submit !== false) {
                actionsElement.innerHTML = `
                    <button onclick="showSubmissionForm()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors">
                        <i class="fas fa-upload mr-2"></i>${submissionData ? 'Update Submission' : 'Submit Assignment'}
                    </button>
                `;
            } else {
                actionsElement.innerHTML = `
                    <div class="text-red-300 text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Submission not allowed
                    </div>
                `;
            }
        }

        // Continue with more functions...
        function showSubmissionForm() {
            document.getElementById('submission-form').classList.remove('hidden');

            // Pre-fill form if submission exists
            if (submissionData) {
                document.getElementById('submission-text').value = submissionData.submission_text || '';
            }
        }

        function cancelSubmission() {
            document.getElementById('submission-form').classList.add('hidden');
        }

        // Load user info
        async function loadUserInfo() {
            const user = JSON.parse(localStorage.getItem('user') || '{}');
            if (user.name) {
                document.getElementById('student-name').textContent = user.name;
            }
        }

        // Utility functions
        function hideLoading() {
            document.getElementById('loading').classList.add('hidden');
            document.getElementById('main-content').classList.remove('hidden');
        }

        function goBack() {
            window.location.href = `/student/courses/${courseId}`;
        }

        function logout() {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            localStorage.removeItem('role');
            window.location.href = '/login';
        }

        // File upload setup
        function setupFileUpload() {
            const dropZone = document.getElementById('file-drop-zone');
            const fileInput = document.getElementById('file-input');
            const dropZoneContent = document.getElementById('drop-zone-content');
            const filePreview = document.getElementById('file-preview');

            // Click to browse
            dropZone.addEventListener('click', () => {
                fileInput.click();
            });

            // Drag and drop events
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('dragover');
            });

            dropZone.addEventListener('dragleave', () => {
                dropZone.classList.remove('dragover');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('dragover');
                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    handleFileSelect(files[0]);
                }
            });

            // File input change
            fileInput.addEventListener('change', (e) => {
                if (e.target.files.length > 0) {
                    handleFileSelect(e.target.files[0]);
                }
            });
        }

        function handleFileSelect(file) {
            const maxSize = 10 * 1024 * 1024; // 10MB
            const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain', 'application/zip'];

            if (file.size > maxSize) {
                showNotification('File size must be less than 10MB', 'error');
                return;
            }

            if (!allowedTypes.includes(file.type)) {
                showNotification('File type not supported. Please use PDF, DOC, DOCX, TXT, or ZIP files.', 'error');
                return;
            }

            // Show file preview
            const dropZoneContent = document.getElementById('drop-zone-content');
            const filePreview = document.getElementById('file-preview');

            dropZoneContent.classList.add('hidden');
            filePreview.classList.remove('hidden');

            filePreview.innerHTML = `
                <div class="flex items-center justify-between bg-white/20 rounded-lg p-4">
                    <div class="flex items-center">
                        <i class="fas fa-file text-2xl text-white/80 mr-3"></i>
                        <div>
                            <div class="text-white font-medium">${file.name}</div>
                            <div class="text-white/60 text-sm">${(file.size / 1024 / 1024).toFixed(2)} MB</div>
                        </div>
                    </div>
                    <button type="button" onclick="removeFile()" class="text-red-300 hover:text-red-200">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
        }

        function removeFile() {
            const fileInput = document.getElementById('file-input');
            const dropZoneContent = document.getElementById('drop-zone-content');
            const filePreview = document.getElementById('file-preview');

            fileInput.value = '';
            dropZoneContent.classList.remove('hidden');
            filePreview.classList.add('hidden');
        }

        // Form submission
        document.getElementById('assignment-submission-form').addEventListener('submit', async function(e) {
            e.preventDefault();

            const submitBtn = document.getElementById('submit-btn');
            const originalText = submitBtn.innerHTML;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...';

            try {
                const formData = new FormData();
                const submissionText = document.getElementById('submission-text').value;
                const fileInput = document.getElementById('file-input');

                if (submissionText.trim()) {
                    formData.append('submission_text', submissionText);
                }

                if (fileInput.files[0]) {
                    formData.append('file', fileInput.files[0]);
                }

                const result = await apiCall(`/student/assignments/${assignmentId}/submit`, 'POST', formData, true);

                if (result && result.ok) {
                    showNotification('Assignment submitted successfully!', 'success');
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                } else {
                    const errorMessage = result?.data?.message || 'Failed to submit assignment';
                    showNotification(errorMessage, 'error');
                }
            } catch (error) {
                console.error('Submission error:', error);
                showNotification('Error submitting assignment', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });

        // Display current submission
        function displaySubmission() {
            if (!submissionData) return;

            const submissionContent = document.getElementById('submission-content');
            const submittedAt = new Date(submissionData.submitted_at).toLocaleString();

            let content = `
                <div class="bg-white/10 rounded-lg p-4 mb-4">
                    <div class="flex items-center justify-between mb-3">
                        <div class="text-white font-medium">Submission Details</div>
                        <div class="text-indigo-200 text-sm">Submitted: ${submittedAt}</div>
                    </div>
            `;

            if (submissionData.submission_text) {
                content += `
                    <div class="mb-3">
                        <div class="text-indigo-200 text-sm mb-1">Written Response:</div>
                        <div class="text-white/90 bg-white/10 rounded p-3">${submissionData.submission_text}</div>
                    </div>
                `;
            }

            if (submissionData.submission_data) {
                try {
                    const data = JSON.parse(submissionData.submission_data);
                    if (data.file_path) {
                        content += `
                            <div class="mb-3">
                                <div class="text-indigo-200 text-sm mb-1">Uploaded File:</div>
                                <div class="flex items-center bg-white/10 rounded p-3">
                                    <i class="fas fa-file text-white/60 mr-2"></i>
                                    <span class="text-white">${data.file_path.split('/').pop()}</span>
                                </div>
                            </div>
                        `;
                    }
                } catch (e) {
                    console.error('Error parsing submission data:', e);
                }
            }

            content += `</div>`;
            submissionContent.innerHTML = content;
        }

        // Display grading information
        function displayGrading() {
            if (!submissionData || submissionData.grade === null) return;

            const gradeContent = document.getElementById('grade-content');
            const grade = submissionData.grade;
            const feedback = submissionData.feedback;

            let content = `
                <div class="bg-white/10 rounded-lg p-4">
                    <div class="flex items-center justify-between mb-4">
                        <div class="text-2xl font-bold text-white">${grade}%</div>
                        <div class="text-indigo-200">out of ${assignmentData.max_score} points</div>
                    </div>
            `;

            if (feedback) {
                content += `
                    <div>
                        <div class="text-indigo-200 text-sm mb-2">Instructor Feedback:</div>
                        <div class="text-white/90 bg-white/10 rounded p-3">${feedback}</div>
                    </div>
                `;
            }

            content += `</div>`;
            gradeContent.innerHTML = content;
        }

        function showNotification(message, type = 'info') {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');

            const bgColor = type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500';

            notification.className = `${bgColor} text-white px-6 py-3 rounded-lg shadow-lg mb-4 transform transition-all duration-300 translate-x-full`;
            notification.textContent = message;

            container.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    container.removeChild(notification);
                }, 300);
            }, 5000);
        }
    </script>
</body>
</html>
