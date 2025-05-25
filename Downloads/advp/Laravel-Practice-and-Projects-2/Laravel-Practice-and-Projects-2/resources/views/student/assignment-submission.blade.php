<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Submission - Educational Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .file-drop-zone {
            border: 2px dashed #cbd5e0;
            transition: all 0.3s ease;
        }

        .file-drop-zone.dragover {
            border-color: #667eea;
            background-color: rgba(102, 126, 234, 0.1);
        }

        .status-draft { @apply bg-gray-100 text-gray-800; }
        .status-submitted { @apply bg-blue-100 text-blue-800; }
        .status-graded { @apply bg-green-100 text-green-800; }
        .status-returned { @apply bg-yellow-100 text-yellow-800; }

        .due-upcoming { @apply text-blue-600; }
        .due-overdue { @apply text-red-600; }
        .due-submitted-on-time { @apply text-green-600; }
        .due-submitted-late { @apply text-orange-600; }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            min-width: 300px;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            @apply bg-green-500 text-white;
        }

        .notification.error {
            @apply bg-red-500 text-white;
        }

        .notification.warning {
            @apply bg-yellow-500 text-white;
        }

        .notification.info {
            @apply bg-blue-500 text-white;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation Header -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-indigo-600">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            EduPlatform
                        </h1>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="/dashboard" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <a href="/courses" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-book mr-2"></i>My Courses
                        </a>
                        <a href="/student/course-enrollment" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-plus-circle mr-2"></i>Enroll
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-bell text-xl"></i>
                            <span class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full"></span>
                        </button>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="hidden md:block">
                            <div class="text-sm font-medium text-gray-700" id="user-name">Loading...</div>
                            <div class="text-xs text-gray-500">Student</div>
                        </div>
                        <button onclick="logout()" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Breadcrumb Navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/dashboard" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-indigo-600">
                        <i class="fas fa-home mr-2"></i>Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <a href="/courses" class="ml-1 text-sm font-medium text-gray-700 hover:text-indigo-600">My Courses</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="ml-1 text-sm font-medium text-gray-700" id="breadcrumb-course-title">Course</span>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500" id="breadcrumb-assignment-title">Assignment Submission</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Loading Indicator -->
    <div id="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
        <p class="mt-4 text-gray-600">Loading assignment details...</p>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8 hidden">
        <!-- Assignment Header -->
        <div class="gradient-bg rounded-xl shadow-xl p-8 mb-8 text-white">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-4">
                        <span class="bg-white bg-opacity-20 text-white text-sm font-medium px-3 py-1 rounded-full mr-3" id="course-code">
                            Loading...
                        </span>
                        <span class="px-3 py-1 rounded-full text-xs font-medium" id="submission-status-badge">
                            Loading...
                        </span>
                    </div>
                    <h1 class="text-3xl font-bold mb-2" id="assignment-title">Loading Assignment...</h1>
                    <p class="text-indigo-100 text-lg mb-4" id="assignment-description">Loading assignment description...</p>

                    <!-- Due Date Info -->
                    <div class="flex items-center mb-4">
                        <i class="fas fa-clock mr-2"></i>
                        <span id="due-date-info" class="font-medium">Loading due date...</span>
                    </div>
                </div>

                <!-- Assignment Stats -->
                <div class="lg:ml-8 mt-6 lg:mt-0">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="bg-white bg-opacity-10 rounded-lg p-4">
                            <div class="text-2xl font-bold" id="max-score">-</div>
                            <div class="text-sm text-indigo-100">Max Points</div>
                        </div>
                        <div class="bg-white bg-opacity-10 rounded-lg p-4">
                            <div class="text-2xl font-bold" id="current-grade">-</div>
                            <div class="text-sm text-indigo-100">Your Grade</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignment Instructions -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                Assignment Instructions
            </h3>
            <div id="assignment-instructions" class="text-gray-600 leading-relaxed">
                Loading instructions...
            </div>
        </div>

        <!-- Submission Form -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-800">
                    <i class="fas fa-upload text-indigo-600 mr-2"></i>
                    Your Submission
                </h3>
                <div class="flex space-x-3">
                    <button id="save-draft-btn" onclick="saveSubmission('save_draft')" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-save mr-2"></i>Save Draft
                    </button>
                    <button id="submit-btn" onclick="saveSubmission('submit')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Assignment
                    </button>
                </div>
            </div>

            <form id="submission-form" enctype="multipart/form-data">
                <!-- Text Submission -->
                <div class="mb-6">
                    <label for="submission-text" class="block text-sm font-medium text-gray-700 mb-2">
                        Written Response
                    </label>
                    <textarea
                        id="submission-text"
                        name="submission_text"
                        rows="8"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Enter your written response here..."
                    ></textarea>
                </div>

                <!-- File Upload -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        File Attachments
                    </label>
                    <div id="file-drop-zone" class="file-drop-zone rounded-lg p-8 text-center">
                        <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                        <p class="text-lg font-medium text-gray-700 mb-2">Drop files here or click to browse</p>
                        <p class="text-sm text-gray-500 mb-4">Supported formats: PDF, DOC, DOCX, TXT, ZIP, JPG, PNG (Max 10MB each)</p>
                        <input type="file" id="file-input" name="files[]" multiple accept=".pdf,.doc,.docx,.txt,.zip,.jpg,.jpeg,.png" class="hidden">
                        <button type="button" onclick="document.getElementById('file-input').click()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            Choose Files
                        </button>
                    </div>
                </div>

                <!-- Uploaded Files List -->
                <div id="uploaded-files" class="hidden">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">Uploaded Files</h4>
                    <div id="files-list" class="space-y-2">
                        <!-- Files will be listed here -->
                    </div>
                </div>
            </form>
        </div>

        <!-- Submission History -->
        <div id="submission-history" class="bg-white rounded-xl shadow-lg p-6 hidden">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-history text-indigo-600 mr-2"></i>
                Submission History
            </h3>
            <div id="history-content">
                <!-- History will be loaded here -->
            </div>
        </div>

        <!-- Grade and Feedback -->
        <div id="grade-feedback" class="bg-white rounded-xl shadow-lg p-6 hidden">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                <i class="fas fa-star text-indigo-600 mr-2"></i>
                Grade and Feedback
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="text-center">
                    <div class="text-3xl font-bold text-indigo-600" id="grade-score">-</div>
                    <div class="text-sm text-gray-500">Score</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-indigo-600" id="grade-percentage">-</div>
                    <div class="text-sm text-gray-500">Percentage</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-indigo-600" id="letter-grade">-</div>
                    <div class="text-sm text-gray-500">Letter Grade</div>
                </div>
            </div>
            <div>
                <h4 class="font-medium text-gray-800 mb-2">Instructor Feedback</h4>
                <div id="instructor-feedback" class="bg-gray-50 rounded-lg p-4 text-gray-600">
                    No feedback available yet.
                </div>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <div id="error-message" class="hidden text-center py-12">
        <div class="text-red-500 mb-4">
            <i class="fas fa-exclamation-triangle text-5xl"></i>
        </div>
        <p class="text-xl font-semibold text-gray-800">Unable to load assignment</p>
        <p class="text-gray-600 mt-2 mb-6">Please try again later or contact support if the problem persists.</p>
        <button id="retry-button" onclick="loadAssignmentSubmission()" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Try Again</button>
    </div>

    <!-- Notification -->
    <div id="notification" class="notification rounded-lg shadow-lg p-4">
        <div class="flex items-center">
            <i id="notification-icon" class="fas fa-info-circle mr-3"></i>
            <div>
                <div id="notification-title" class="font-medium"></div>
                <div id="notification-message" class="text-sm opacity-90"></div>
            </div>
            <button onclick="hideNotification()" class="ml-auto text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let authToken = localStorage.getItem('token');
        let assignmentId = null;
        let submissionData = null;
        let selectedFiles = [];

        // Initialize page when DOM loads
        document.addEventListener('DOMContentLoaded', function() {
            // Check authentication
            if (!authToken) {
                window.location.href = '/login';
                return;
            }

            // Get assignment ID from URL
            const urlParams = new URLSearchParams(window.location.search);
            assignmentId = urlParams.get('assignment');

            if (!assignmentId) {
                showError('Assignment ID not found in URL');
                return;
            }

            // Load initial data
            loadUserProfile();
            loadAssignmentSubmission();

            // Set up file upload handlers
            setupFileUpload();
        });

        // Enhanced API call utility function with better error handling
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

                // Handle authentication errors
                if (response.status === 401) {
                    localStorage.removeItem('token');
                    showNotification('error', 'Session Expired', 'Please login again to continue');
                    setTimeout(() => {
                        window.location.href = '/login';
                    }, 2000);
                    return null;
                }

                // Handle forbidden access
                if (response.status === 403) {
                    showNotification('error', 'Access Denied', 'You do not have permission to access this resource');
                    return {
                        ok: false,
                        status: 403,
                        data: { status: 'error', message: 'Access denied' }
                    };
                }

                // Handle not found errors
                if (response.status === 404) {
                    showNotification('error', 'Not Found', 'The requested resource was not found');
                    return {
                        ok: false,
                        status: 404,
                        data: { status: 'error', message: 'Resource not found' }
                    };
                }

                // Handle server errors
                if (response.status >= 500) {
                    showNotification('error', 'Server Error', 'A server error occurred. Please try again later');
                    return {
                        ok: false,
                        status: response.status,
                        data: { status: 'error', message: 'Server error occurred' }
                    };
                }

                const result = await response.json();
                return {
                    ok: response.ok,
                    status: response.status,
                    data: result
                };
            } catch (error) {
                console.error('API call failed:', error);

                // Handle network errors
                if (error.name === 'TypeError' && error.message.includes('fetch')) {
                    showNotification('error', 'Network Error', 'Please check your internet connection and try again');
                } else {
                    showNotification('error', 'Error', 'An unexpected error occurred');
                }

                return {
                    ok: false,
                    status: 0,
                    data: { status: 'error', message: 'Network error occurred' }
                };
            }
        }

        // Enhanced API validation function
        function validateApiResponse(result, expectedStatus = 'success') {
            if (!result) {
                return { valid: false, message: 'No response received' };
            }

            if (!result.ok) {
                return {
                    valid: false,
                    message: result.data?.message || 'Request failed'
                };
            }

            if (result.data?.status !== expectedStatus) {
                return {
                    valid: false,
                    message: result.data?.message || 'Unexpected response status'
                };
            }

            return { valid: true, data: result.data };
        }

        // Load user profile
        async function loadUserProfile() {
            try {
                const result = await apiCall('/profile');
                if (result && result.ok) {
                    const user = result.data.user;
                    document.getElementById('user-name').textContent = user.name;
                }
            } catch (error) {
                console.error('Error loading profile:', error);
            }
        }

        // Load assignment and submission data with enhanced error handling
        async function loadAssignmentSubmission() {
            try {
                showLoading();

                const result = await apiCall(`/assignments/${assignmentId}/submission`);
                const validation = validateApiResponse(result);

                if (validation.valid) {
                    submissionData = validation.data.data;
                    displayAssignmentData();
                    displaySubmissionData();
                    hideLoading();

                    // Show success notification for first load
                    if (!window.assignmentLoaded) {
                        showNotification('success', 'Assignment Loaded', 'Assignment details loaded successfully');
                        window.assignmentLoaded = true;
                    }
                } else {
                    showError(validation.message);
                }
            } catch (error) {
                console.error('Error loading assignment:', error);
                showError('An unexpected error occurred while loading the assignment');
            }
        }

        // Display assignment data
        function displayAssignmentData() {
            const assignment = submissionData.assignment;

            // Update breadcrumbs
            document.getElementById('breadcrumb-course-title').textContent = assignment.course.title;
            document.getElementById('breadcrumb-assignment-title').textContent = assignment.title;

            // Update header
            document.getElementById('course-code').textContent = assignment.course.code;
            document.getElementById('assignment-title').textContent = assignment.title;
            document.getElementById('assignment-description').textContent = assignment.description;
            document.getElementById('max-score').textContent = assignment.max_score || 100;

            // Update due date
            const dueDateInfo = document.getElementById('due-date-info');
            const dueStatus = assignment.due_date_status;
            dueDateInfo.textContent = dueStatus.text;
            dueDateInfo.className = `font-medium due-${dueStatus.status}`;

            // Update instructions
            document.getElementById('assignment-instructions').innerHTML =
                assignment.instructions || assignment.description || 'No specific instructions provided.';
        }

        // Display submission data
        function displaySubmissionData() {
            const submission = submissionData.submission;

            // Update status badge
            const statusBadge = document.getElementById('submission-status-badge');
            statusBadge.textContent = submission.status_text;
            statusBadge.className = `px-3 py-1 rounded-full text-xs font-medium status-${submission.status}`;

            // Update current grade
            const gradeElement = document.getElementById('current-grade');
            if (submission.grade !== null) {
                gradeElement.textContent = `${submission.grade}/${submissionData.assignment.max_score}`;
            } else {
                gradeElement.textContent = 'Not Graded';
            }

            // Fill form with existing data
            if (submission.submission_text) {
                document.getElementById('submission-text').value = submission.submission_text;
            }

            // Display uploaded files
            if (submission.files && submission.files.length > 0) {
                displayUploadedFiles(submission.files);
            }

            // Update button states
            updateButtonStates();

            // Show grade and feedback if available
            if (submission.status === 'graded' && submission.grade !== null) {
                showGradeAndFeedback();
            }
        }

        // Display uploaded files
        function displayUploadedFiles(files) {
            const filesContainer = document.getElementById('uploaded-files');
            const filesList = document.getElementById('files-list');

            if (files.length === 0) {
                filesContainer.classList.add('hidden');
                return;
            }

            filesContainer.classList.remove('hidden');
            filesList.innerHTML = files.map(file => `
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center">
                        <i class="${file.file_icon} mr-3"></i>
                        <div>
                            <div class="font-medium text-gray-900">${file.original_name}</div>
                            <div class="text-sm text-gray-500">${file.formatted_file_size} • Uploaded ${file.uploaded_at}</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="${file.download_url}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                            <i class="fas fa-download mr-1"></i>Download
                        </a>
                        ${submissionData.submission.status === 'draft' ? `
                            <button onclick="deleteFile(${file.id})" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                <i class="fas fa-trash mr-1"></i>Delete
                            </button>
                        ` : ''}
                    </div>
                </div>
            `).join('');
        }

        // Update button states based on submission status
        function updateButtonStates() {
            const submission = submissionData.submission;
            const saveDraftBtn = document.getElementById('save-draft-btn');
            const submitBtn = document.getElementById('submit-btn');
            const form = document.getElementById('submission-form');

            if (submission.status === 'draft' && submission.can_submit) {
                // Can edit and submit
                saveDraftBtn.disabled = false;
                submitBtn.disabled = false;
                form.style.opacity = '1';
                form.style.pointerEvents = 'auto';
            } else if (submission.status === 'submitted' || submission.status === 'graded') {
                // Read-only mode
                saveDraftBtn.disabled = true;
                submitBtn.disabled = true;
                form.style.opacity = '0.6';
                form.style.pointerEvents = 'none';

                // Update button text
                submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Submitted';
                submitBtn.className = 'bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed';
            }
        }

        // Show grade and feedback section
        function showGradeAndFeedback() {
            const submission = submissionData.submission;
            const gradeSection = document.getElementById('grade-feedback');

            document.getElementById('grade-score').textContent = `${submission.grade}/${submissionData.assignment.max_score}`;
            document.getElementById('grade-percentage').textContent = `${submission.grade_percentage}%`;
            document.getElementById('letter-grade').textContent = submission.letter_grade || 'N/A';
            document.getElementById('instructor-feedback').textContent = submission.feedback || 'No feedback provided yet.';

            gradeSection.classList.remove('hidden');
        }

        // Setup file upload functionality
        function setupFileUpload() {
            const dropZone = document.getElementById('file-drop-zone');
            const fileInput = document.getElementById('file-input');

            // Drag and drop handlers
            dropZone.addEventListener('dragover', (e) => {
                e.preventDefault();
                dropZone.classList.add('dragover');
            });

            dropZone.addEventListener('dragleave', (e) => {
                e.preventDefault();
                dropZone.classList.remove('dragover');
            });

            dropZone.addEventListener('drop', (e) => {
                e.preventDefault();
                dropZone.classList.remove('dragover');

                const files = Array.from(e.dataTransfer.files);
                handleFileSelection(files);
            });

            // File input change handler
            fileInput.addEventListener('change', (e) => {
                const files = Array.from(e.target.files);
                handleFileSelection(files);
            });
        }

        // Handle file selection
        function handleFileSelection(files) {
            // Validate files
            const validFiles = files.filter(file => {
                const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'text/plain', 'application/zip', 'image/jpeg', 'image/png'];
                const maxSize = 10 * 1024 * 1024; // 10MB

                if (!validTypes.includes(file.type)) {
                    showNotification('error', 'Invalid File Type', `${file.name} is not a supported file type.`);
                    return false;
                }

                if (file.size > maxSize) {
                    showNotification('error', 'File Too Large', `${file.name} exceeds the 10MB size limit.`);
                    return false;
                }

                return true;
            });

            if (validFiles.length > 0) {
                selectedFiles = validFiles;
                showNotification('success', 'Files Selected', `${validFiles.length} file(s) ready for upload.`);
            }
        }

        // Enhanced save submission function with better validation and error handling
        async function saveSubmission(action) {
            try {
                // Validate inputs before submission
                const submissionText = document.getElementById('submission-text').value.trim();

                if (!submissionText && selectedFiles.length === 0) {
                    showNotification('warning', 'Empty Submission', 'Please add some text or upload files before saving');
                    return;
                }

                // Confirm final submission
                if (action === 'submit') {
                    const confirmMessage = 'Are you sure you want to submit this assignment? You will not be able to edit it after submission.';
                    if (!confirm(confirmMessage)) {
                        return;
                    }
                }

                const formData = new FormData();

                // Add text content
                if (submissionText) {
                    formData.append('submission_text', submissionText);
                }

                // Add files with validation
                if (selectedFiles.length > 0) {
                    // Validate total file size (max 50MB total)
                    const totalSize = selectedFiles.reduce((sum, file) => sum + file.size, 0);
                    const maxTotalSize = 50 * 1024 * 1024; // 50MB

                    if (totalSize > maxTotalSize) {
                        showNotification('error', 'Files Too Large', 'Total file size cannot exceed 50MB');
                        return;
                    }

                    selectedFiles.forEach(file => {
                        formData.append('files[]', file);
                    });
                }

                // Add action
                formData.append('action', action);

                // Show loading state
                const button = action === 'submit' ? document.getElementById('submit-btn') : document.getElementById('save-draft-btn');
                const originalText = button.innerHTML;
                const loadingText = action === 'submit' ?
                    '<i class="fas fa-spinner fa-spin mr-2"></i>Submitting...' :
                    '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';

                button.innerHTML = loadingText;
                button.disabled = true;

                // Disable other button during submission
                const otherButton = action === 'submit' ? document.getElementById('save-draft-btn') : document.getElementById('submit-btn');
                otherButton.disabled = true;

                const result = await apiCall(`/assignments/${assignmentId}/submit`, 'POST', formData, true);
                const validation = validateApiResponse(result);

                if (validation.valid) {
                    const successMessage = action === 'submit' ?
                        'Assignment submitted successfully!' :
                        'Draft saved successfully!';

                    showNotification('success', 'Success', validation.data.message || successMessage);

                    // Clear selected files
                    selectedFiles = [];
                    document.getElementById('file-input').value = '';

                    // Reload submission data to reflect changes
                    await loadAssignmentSubmission();

                    // Show additional success actions for final submission
                    if (action === 'submit') {
                        setTimeout(() => {
                            showNotification('info', 'What\'s Next?', 'Your instructor will review and grade your submission');
                        }, 2000);
                    }
                } else {
                    // Handle validation errors specifically
                    if (result?.data?.errors) {
                        const errorMessages = Object.values(result.data.errors).flat();
                        showNotification('error', 'Validation Error', errorMessages.join(', '));
                    } else {
                        showNotification('error', 'Error', validation.message);
                    }
                }

                // Restore buttons
                button.innerHTML = originalText;
                button.disabled = false;
                otherButton.disabled = false;

            } catch (error) {
                console.error('Error saving submission:', error);
                showNotification('error', 'Error', 'An unexpected error occurred while saving your submission');

                // Restore buttons in case of error
                const buttons = [document.getElementById('submit-btn'), document.getElementById('save-draft-btn')];
                buttons.forEach(btn => {
                    if (btn) {
                        btn.disabled = false;
                        // Restore original text based on button type
                        if (btn.id === 'submit-btn') {
                            btn.innerHTML = '<i class="fas fa-paper-plane mr-2"></i>Submit Assignment';
                        } else {
                            btn.innerHTML = '<i class="fas fa-save mr-2"></i>Save Draft';
                        }
                    }
                });
            }
        }

        // Delete uploaded file
        async function deleteFile(fileId) {
            if (!confirm('Are you sure you want to delete this file?')) {
                return;
            }

            try {
                const result = await apiCall(`/submissions/${submissionData.submission.id}/files/${fileId}`, 'DELETE');

                if (result && result.ok && result.data.status === 'success') {
                    showNotification('success', 'File Deleted', 'File has been removed from your submission');
                    await loadAssignmentSubmission(); // Reload to update file list
                } else {
                    showNotification('error', 'Error', result.data?.message || 'Failed to delete file');
                }
            } catch (error) {
                console.error('Error deleting file:', error);
                showNotification('error', 'Error', 'An error occurred while deleting the file');
            }
        }

        // Show loading state
        function showLoading() {
            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('main-content').classList.add('hidden');
            document.getElementById('error-message').classList.add('hidden');
        }

        // Hide loading state
        function hideLoading() {
            document.getElementById('loading').classList.add('hidden');
            document.getElementById('main-content').classList.remove('hidden');
        }

        // Show error message
        function showError(message) {
            document.getElementById('loading').classList.add('hidden');
            document.getElementById('main-content').classList.add('hidden');
            document.getElementById('error-message').classList.remove('hidden');
            document.getElementById('error-message').querySelector('p:last-of-type').textContent = message;
        }

        // Show notification
        function showNotification(type, title, message) {
            const notification = document.getElementById('notification');
            const icon = document.getElementById('notification-icon');
            const titleEl = document.getElementById('notification-title');
            const messageEl = document.getElementById('notification-message');

            // Set content
            titleEl.textContent = title;
            messageEl.textContent = message;

            // Set icon and style based on type
            notification.className = `notification ${type} rounded-lg shadow-lg p-4`;

            switch(type) {
                case 'success':
                    icon.className = 'fas fa-check-circle mr-3';
                    break;
                case 'error':
                    icon.className = 'fas fa-exclamation-circle mr-3';
                    break;
                case 'warning':
                    icon.className = 'fas fa-exclamation-triangle mr-3';
                    break;
                default:
                    icon.className = 'fas fa-info-circle mr-3';
            }

            // Show notification
            notification.classList.add('show');

            // Auto hide after 5 seconds
            setTimeout(() => {
                hideNotification();
            }, 5000);
        }

        // Hide notification
        function hideNotification() {
            document.getElementById('notification').classList.remove('show');
        }

        // Logout function
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                apiCall('/logout', 'POST').then(() => {
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                }).catch(() => {
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                });
            }
        }
    </script>
</body>
</html>
