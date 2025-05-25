<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management - Instructor Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .action-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .tab-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .tab-inactive {
            background: rgba(255, 255, 255, 0.1);
            color: #6b7280;
        }

        .content-section {
            display: none;
        }

        .content-section.active {
            display: block;
        }

        .loading-spinner {
            border: 3px solid #f3f4f6;
            border-top: 3px solid #667eea;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            padding: 16px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            transform: translateX(400px);
            transition: transform 0.3s ease;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .notification.error {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }

        .notification.warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="gradient-bg shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="/instructor/dashboard" class="text-white text-xl font-bold">
                        <i class="fas fa-chalkboard-teacher mr-2"></i>Instructor Portal
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/instructor/courses" class="text-white hover:text-indigo-200 transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Courses
                    </a>
                    <div class="text-white">
                        <i class="fas fa-user-circle mr-2"></i>
                        <span id="user-name">Loading...</span>
                    </div>
                    <button onclick="logout()" class="text-white hover:text-red-200 transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Course Header -->
        <div id="course-header" class="gradient-bg rounded-xl shadow-xl p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-2">
                        <i class="fas fa-shield-alt text-green-300 mr-2"></i>
                        <span class="text-sm text-indigo-200">Manager Assigned Course</span>
                    </div>
                    <h1 id="course-title" class="text-3xl font-bold mb-2">Loading Course...</h1>
                    <p id="course-code" class="text-indigo-100 text-lg mb-2">Course Code</p>
                    <p id="course-description" class="text-indigo-100">Course Description</p>
                </div>
                <div class="hidden md:block">
                    <div class="text-right">
                        <div class="text-sm text-indigo-200 mb-1">Students Enrolled</div>
                        <div id="student-count" class="text-3xl font-bold">0</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div id="loading-state" class="text-center py-12">
            <div class="loading-spinner mx-auto mb-4"></div>
            <p class="text-gray-600">Loading course management interface...</p>
        </div>

        <!-- Access Denied State -->
        <div id="access-denied" class="text-center py-12 hidden">
            <i class="fas fa-ban text-6xl text-red-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-red-600 mb-2">Access Denied</h3>
            <p class="text-red-500 mb-6">You don't have permission to manage this course.</p>
            <a href="/instructor/courses" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                Back to My Courses
            </a>
        </div>

        <!-- Management Interface -->
        <div id="management-interface" class="hidden">
            <!-- Tab Navigation -->
            <div class="bg-white rounded-xl shadow-lg mb-8">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button onclick="switchTab('overview')" class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors" data-tab="overview">
                            <i class="fas fa-chart-line mr-2"></i>Overview
                        </button>
                        <button onclick="switchTab('content')" class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors" data-tab="content">
                            <i class="fas fa-book mr-2"></i>Content
                        </button>
                        <button onclick="switchTab('students')" class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors" data-tab="students">
                            <i class="fas fa-users mr-2"></i>Students
                        </button>
                        <button onclick="switchTab('grading')" class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors" data-tab="grading">
                            <i class="fas fa-graduation-cap mr-2"></i>Grading
                        </button>
                        <button onclick="switchTab('settings')" class="tab-button py-4 px-1 border-b-2 font-medium text-sm transition-colors" data-tab="settings">
                            <i class="fas fa-cog mr-2"></i>Settings
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Tab Content Sections -->

            <!-- Overview Section -->
            <div id="overview-section" class="content-section active">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Quick Stats -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Statistics</h3>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="text-center p-4 bg-blue-50 rounded-lg">
                                    <i class="fas fa-video text-blue-600 text-2xl mb-2"></i>
                                    <div id="lectures-count" class="text-2xl font-bold text-blue-600">0</div>
                                    <div class="text-sm text-gray-600">Lectures</div>
                                </div>
                                <div class="text-center p-4 bg-green-50 rounded-lg">
                                    <i class="fas fa-tasks text-green-600 text-2xl mb-2"></i>
                                    <div id="assignments-count" class="text-2xl font-bold text-green-600">0</div>
                                    <div class="text-sm text-gray-600">Assignments</div>
                                </div>
                                <div class="text-center p-4 bg-purple-50 rounded-lg">
                                    <i class="fas fa-question-circle text-purple-600 text-2xl mb-2"></i>
                                    <div id="quizzes-count" class="text-2xl font-bold text-purple-600">0</div>
                                    <div class="text-sm text-gray-600">Quizzes</div>
                                </div>
                                <div class="text-center p-4 bg-orange-50 rounded-lg">
                                    <i class="fas fa-flask text-orange-600 text-2xl mb-2"></i>
                                    <div id="labs-count" class="text-2xl font-bold text-orange-600">0</div>
                                    <div class="text-sm text-gray-600">Labs</div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Activity</h3>
                            <div id="recent-activity" class="space-y-4">
                                <!-- Activity items will be loaded here -->
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div>
                        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                            <div class="space-y-3">
                                <button onclick="switchTab('content')" class="w-full action-button text-white px-4 py-3 rounded-lg hover:shadow-lg transition-all">
                                    <i class="fas fa-plus mr-2"></i>Add Content
                                </button>
                                <button onclick="switchTab('grading')" class="w-full bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-all">
                                    <i class="fas fa-check mr-2"></i>Grade Submissions
                                </button>
                                <button onclick="switchTab('students')" class="w-full bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-all">
                                    <i class="fas fa-bullhorn mr-2"></i>Send Announcement
                                </button>
                            </div>
                        </div>

                        <!-- Pending Tasks -->
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pending Tasks</h3>
                            <div id="pending-tasks" class="space-y-3">
                                <!-- Pending tasks will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div id="content-section" class="content-section">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Course Content Management</h3>
                        <div class="flex space-x-3">
                            <button onclick="showCreateContentModal('lecture')" class="action-button text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all">
                                <i class="fas fa-video mr-2"></i>Add Lecture
                            </button>
                            <button onclick="showCreateContentModal('assignment')" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-all">
                                <i class="fas fa-tasks mr-2"></i>Add Assignment
                            </button>
                            <button onclick="showCreateContentModal('quiz')" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-all">
                                <i class="fas fa-question-circle mr-2"></i>Add Quiz
                            </button>
                        </div>
                    </div>

                    <!-- Content Tabs -->
                    <div class="border-b border-gray-200 mb-6">
                        <nav class="flex space-x-8" aria-label="Content Tabs">
                            <button onclick="switchContentTab('lectures')" class="content-tab-button py-2 px-1 border-b-2 font-medium text-sm transition-colors" data-content-tab="lectures">
                                <i class="fas fa-video mr-2"></i>Lectures
                            </button>
                            <button onclick="switchContentTab('assignments')" class="content-tab-button py-2 px-1 border-b-2 font-medium text-sm transition-colors" data-content-tab="assignments">
                                <i class="fas fa-tasks mr-2"></i>Assignments
                            </button>
                            <button onclick="switchContentTab('quizzes')" class="content-tab-button py-2 px-1 border-b-2 font-medium text-sm transition-colors" data-content-tab="quizzes">
                                <i class="fas fa-question-circle mr-2"></i>Quizzes
                            </button>
                            <button onclick="switchContentTab('labs')" class="content-tab-button py-2 px-1 border-b-2 font-medium text-sm transition-colors" data-content-tab="labs">
                                <i class="fas fa-flask mr-2"></i>Labs
                            </button>
                            <button onclick="switchContentTab('materials')" class="content-tab-button py-2 px-1 border-b-2 font-medium text-sm transition-colors" data-content-tab="materials">
                                <i class="fas fa-file mr-2"></i>Materials
                            </button>
                        </nav>
                    </div>

                    <!-- Content Lists -->
                    <div id="lectures-content" class="content-tab-section">
                        <div id="lectures-list" class="space-y-4">
                            <!-- Lectures will be loaded here -->
                        </div>
                    </div>

                    <div id="assignments-content" class="content-tab-section hidden">
                        <div id="assignments-list" class="space-y-4">
                            <!-- Assignments will be loaded here -->
                        </div>
                    </div>

                    <div id="quizzes-content" class="content-tab-section hidden">
                        <div id="quizzes-list" class="space-y-4">
                            <!-- Quizzes will be loaded here -->
                        </div>
                    </div>

                    <div id="labs-content" class="content-tab-section hidden">
                        <div id="labs-list" class="space-y-4">
                            <!-- Labs will be loaded here -->
                        </div>
                    </div>

                    <div id="materials-content" class="content-tab-section hidden">
                        <div id="materials-list" class="space-y-4">
                            <!-- Materials will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Section -->
            <div id="students-section" class="content-section">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Students List -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-semibold text-gray-900">Enrolled Students</h3>
                                <button onclick="showAnnouncementModal()" class="action-button text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all">
                                    <i class="fas fa-bullhorn mr-2"></i>Send Announcement
                                </button>
                            </div>
                            <div id="students-list" class="space-y-4">
                                <!-- Students will be loaded here -->
                            </div>
                        </div>
                    </div>

                    <!-- Student Analytics -->
                    <div>
                        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Analytics</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Total Enrolled</span>
                                    <span id="total-students" class="font-semibold">0</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Active Students</span>
                                    <span id="active-students" class="font-semibold text-green-600">0</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Average Grade</span>
                                    <span id="average-grade" class="font-semibold">0%</span>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Submissions</h3>
                            <div id="recent-submissions" class="space-y-3">
                                <!-- Recent submissions will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grading Section -->
            <div id="grading-section" class="content-section">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Gradebook -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold text-gray-900">Gradebook</h3>
                            <button onclick="exportGrades()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-all">
                                <i class="fas fa-download mr-2"></i>Export Grades
                            </button>
                        </div>
                        <div id="gradebook-content">
                            <!-- Gradebook will be loaded here -->
                        </div>
                    </div>

                    <!-- Grading Tools -->
                    <div>
                        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Grading Tools</h3>
                            <div class="space-y-3">
                                <button onclick="showBulkGradingModal()" class="w-full action-button text-white px-4 py-3 rounded-lg hover:shadow-lg transition-all">
                                    <i class="fas fa-check-double mr-2"></i>Bulk Grading
                                </button>
                                <button onclick="showGradeAnalytics()" class="w-full bg-purple-600 text-white px-4 py-3 rounded-lg hover:bg-purple-700 transition-all">
                                    <i class="fas fa-chart-bar mr-2"></i>Grade Analytics
                                </button>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pending Grading</h3>
                            <div id="pending-grading" class="space-y-3">
                                <!-- Pending grading items will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Section -->
            <div id="settings-section" class="content-section">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Course Settings</h3>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Course Information -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-800 mb-4">Course Information</h4>
                            <form id="course-settings-form" class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Course Title</label>
                                    <input type="text" id="settings-course-title" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Course Code</label>
                                    <input type="text" id="settings-course-code" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <textarea id="settings-course-description" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                </div>
                                <button type="submit" class="action-button text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all">
                                    <i class="fas fa-save mr-2"></i>Save Changes
                                </button>
                            </form>
                        </div>

                        <!-- Course Preferences -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-800 mb-4">Course Preferences</h4>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-700">Allow late submissions</span>
                                    <input type="checkbox" id="allow-late-submissions" class="rounded">
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-700">Auto-grade quizzes</span>
                                    <input type="checkbox" id="auto-grade-quizzes" class="rounded">
                                </div>
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-700">Send grade notifications</span>
                                    <input type="checkbox" id="send-grade-notifications" class="rounded">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Late penalty (%)</label>
                                    <input type="number" id="late-penalty" min="0" max="100" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notification-container"></div>

    <script>
        // Global variables
        const authToken = localStorage.getItem('token');
        const userRole = localStorage.getItem('role');
        let currentCourseId = null;
        let courseData = null;
        let currentTab = 'overview';
        let currentContentTab = 'lectures';

        // Get course ID from URL
        const pathParts = window.location.pathname.split('/');
        const courseIdIndex = pathParts.indexOf('courses') + 1;
        if (courseIdIndex > 0 && pathParts[courseIdIndex]) {
            currentCourseId = pathParts[courseIdIndex];
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken || userRole !== 'instructor') {
                window.location.href = '/login';
                return;
            }

            if (!currentCourseId) {
                showNotification('Invalid course ID', 'error');
                setTimeout(() => {
                    window.location.href = '/instructor/courses';
                }, 2000);
                return;
            }

            loadUserProfile();
            loadCourseData();
        });

        // Utility function for API calls
        async function apiCall(endpoint, method = 'GET', data = null, isFormData = false) {
            const currentToken = localStorage.getItem('token');

            const config = {
                method: method,
                headers: {
                    'Authorization': `Bearer ${currentToken}`,
                    'Accept': 'application/json'
                }
            };

            // Only set Content-Type for JSON data, not for FormData
            if (!isFormData) {
                config.headers['Content-Type'] = 'application/json';
            }

            if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
                if (isFormData) {
                    config.body = data; // FormData object
                } else {
                    config.body = JSON.stringify(data); // JSON data
                }
            }

            try {
                const response = await fetch(`/api${endpoint}`, config);

                // Handle different response types
                let result;
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    result = { message: await response.text() };
                }

                return {
                    ok: response.ok,
                    status: response.status,
                    data: result
                };
            } catch (error) {
                console.error('API call failed:', error);
                return {
                    ok: false,
                    status: 500,
                    data: { message: 'Network error' }
                };
            }
        }

        // Load user profile
        async function loadUserProfile() {
            try {
                const result = await apiCall('/profile');
                if (result && result.ok) {
                    document.getElementById('user-name').textContent = result.data.name || 'Instructor';
                }
            } catch (error) {
                console.error('Error loading user profile:', error);
            }
        }

        // Load course data and verify access
        async function loadCourseData() {
            try {
                const result = await apiCall(`/instructor/courses/${currentCourseId}/details`);

                if (result && result.ok && result.data.status === 'success') {
                    courseData = result.data.data;
                    displayCourseData();
                    loadCourseContent();
                    hideLoading();
                } else if (result && result.status === 403) {
                    showAccessDenied();
                } else {
                    showNotification('Failed to load course data', 'error');
                    setTimeout(() => {
                        window.location.href = '/instructor/courses';
                    }, 2000);
                }
            } catch (error) {
                console.error('Error loading course data:', error);
                showNotification('Error loading course data', 'error');
            }
        }

        // Display course data in header
        function displayCourseData() {
            if (!courseData) return;

            document.getElementById('course-title').textContent = courseData.title || 'Course Title';
            document.getElementById('course-code').textContent = courseData.code || 'Course Code';
            document.getElementById('course-description').textContent = courseData.description || 'No description available';
            document.getElementById('student-count').textContent = courseData.student_count || 0;

            // Update statistics
            const contentStats = courseData.content_stats || {};
            document.getElementById('lectures-count').textContent = contentStats.lectures || 0;
            document.getElementById('assignments-count').textContent = contentStats.assignments || 0;
            document.getElementById('quizzes-count').textContent = contentStats.quizzes || 0;
            document.getElementById('labs-count').textContent = contentStats.labs || 0;

            // Update settings form
            document.getElementById('settings-course-title').value = courseData.title || '';
            document.getElementById('settings-course-code').value = courseData.code || '';
            document.getElementById('settings-course-description').value = courseData.description || '';
        }

        // Load course content for all sections
        async function loadCourseContent() {
            await Promise.all([
                loadLectures(),
                loadAssignments(),
                loadQuizzes(),
                loadLabs(),
                loadMaterials(),
                loadStudents(),
                loadRecentActivity(),
                loadPendingTasks(),
                loadGradebook()
            ]);
        }

        // Tab switching functionality
        function switchTab(tabName) {
            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(button => {
                if (button.dataset.tab === tabName) {
                    button.className = 'tab-button py-4 px-1 border-b-2 border-indigo-500 font-medium text-sm text-indigo-600';
                } else {
                    button.className = 'tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300';
                }
            });

            // Update content sections
            document.querySelectorAll('.content-section').forEach(section => {
                section.classList.remove('active');
            });

            const targetSection = document.getElementById(`${tabName}-section`);
            if (targetSection) {
                targetSection.classList.add('active');
            }

            currentTab = tabName;
        }

        // Content tab switching
        function switchContentTab(tabName) {
            // Update content tab buttons
            document.querySelectorAll('.content-tab-button').forEach(button => {
                if (button.dataset.contentTab === tabName) {
                    button.className = 'content-tab-button py-2 px-1 border-b-2 border-indigo-500 font-medium text-sm text-indigo-600';
                } else {
                    button.className = 'content-tab-button py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300';
                }
            });

            // Update content tab sections
            document.querySelectorAll('.content-tab-section').forEach(section => {
                section.classList.add('hidden');
            });

            const targetSection = document.getElementById(`${tabName}-content`);
            if (targetSection) {
                targetSection.classList.remove('hidden');
            }

            currentContentTab = tabName;
        }

        // Show/hide loading state
        function hideLoading() {
            document.getElementById('loading-state').classList.add('hidden');
            document.getElementById('management-interface').classList.remove('hidden');
        }

        function showAccessDenied() {
            document.getElementById('loading-state').classList.add('hidden');
            document.getElementById('access-denied').classList.remove('hidden');
        }

        // Notification system
        function showNotification(message, type = 'success') {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'exclamation-triangle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;

            container.appendChild(notification);

            // Show notification
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);

            // Hide notification after 5 seconds
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    container.removeChild(notification);
                }, 300);
            }, 5000);
        }

        // Content loading functions
        async function loadLectures() {
            try {
                const result = await apiCall(`/instructor/courses/${currentCourseId}/lectures`);
                if (result && result.ok) {
                    displayLectures(result.data.data || []);
                }
            } catch (error) {
                console.error('Error loading lectures:', error);
            }
        }

        async function loadAssignments() {
            try {
                const result = await apiCall(`/instructor/courses/${currentCourseId}/assignments`);
                if (result && result.ok) {
                    displayAssignments(result.data.data || []);
                }
            } catch (error) {
                console.error('Error loading assignments:', error);
            }
        }

        async function loadQuizzes() {
            try {
                const result = await apiCall(`/instructor/courses/${currentCourseId}/quizzes`);
                if (result && result.ok) {
                    displayQuizzes(result.data.data || []);
                }
            } catch (error) {
                console.error('Error loading quizzes:', error);
            }
        }

        async function loadLabs() {
            try {
                const result = await apiCall(`/instructor/courses/${currentCourseId}/labs`);
                if (result && result.ok) {
                    displayLabs(result.data.data || []);
                }
            } catch (error) {
                console.error('Error loading labs:', error);
            }
        }

        async function loadMaterials() {
            try {
                const result = await apiCall(`/instructor/courses/${currentCourseId}/materials`);
                if (result && result.ok) {
                    displayMaterials(result.data.data || []);
                }
            } catch (error) {
                console.error('Error loading materials:', error);
            }
        }

        async function loadStudents() {
            try {
                const result = await apiCall(`/instructor/courses/${currentCourseId}/students`);
                if (result && result.ok) {
                    displayStudents(result.data.data || []);
                    updateStudentAnalytics(result.data.analytics || {});
                }
            } catch (error) {
                console.error('Error loading students:', error);
            }
        }

        async function loadRecentActivity() {
            try {
                const result = await apiCall(`/instructor/courses/${currentCourseId}/activity`);
                if (result && result.ok) {
                    displayRecentActivity(result.data.data || []);
                }
            } catch (error) {
                console.error('Error loading recent activity:', error);
            }
        }

        async function loadPendingTasks() {
            try {
                const result = await apiCall(`/instructor/courses/${currentCourseId}/pending-tasks`);
                if (result && result.ok) {
                    displayPendingTasks(result.data.data || []);
                }
            } catch (error) {
                console.error('Error loading pending tasks:', error);
            }
        }

        async function loadGradebook() {
            try {
                const result = await apiCall(`/instructor/courses/${currentCourseId}/gradebook`);
                if (result && result.ok) {
                    displayGradebook(result.data.data || {});
                }
            } catch (error) {
                console.error('Error loading gradebook:', error);
            }
        }

        // Display functions
        function displayLectures(lectures) {
            const container = document.getElementById('lectures-list');

            if (lectures.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-video text-4xl mb-4"></i>
                        <p>No lectures created yet</p>
                        <button onclick="showCreateContentModal('lecture')" class="mt-3 action-button text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all">
                            <i class="fas fa-plus mr-2"></i>Create First Lecture
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = lectures.map(lecture => `
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">${lecture.title}</h4>
                            <p class="text-sm text-gray-600 mt-1">${lecture.description || 'No description'}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                <span>${lecture.duration || 'No duration set'}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-calendar mr-1"></i>
                                <span>${lecture.scheduled_date || 'Not scheduled'}</span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editLecture(${lecture.id})" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteLecture(${lecture.id})" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function displayAssignments(assignments) {
            const container = document.getElementById('assignments-list');

            if (assignments.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-tasks text-4xl mb-4"></i>
                        <p>No assignments created yet</p>
                        <button onclick="showCreateContentModal('assignment')" class="mt-3 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-all">
                            <i class="fas fa-plus mr-2"></i>Create First Assignment
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = assignments.map(assignment => `
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">${assignment.title}</h4>
                            <p class="text-sm text-gray-600 mt-1">${assignment.description || 'No description'}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                <span>Due: ${assignment.due_date || 'No due date'}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-star mr-1"></i>
                                <span>${assignment.points || 0} points</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-users mr-1"></i>
                                <span>${assignment.submissions_count || 0} submissions</span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="viewSubmissions(${assignment.id})" class="text-green-600 hover:text-green-800">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editAssignment(${assignment.id})" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteAssignment(${assignment.id})" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function displayQuizzes(quizzes) {
            const container = document.getElementById('quizzes-list');

            if (quizzes.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-question-circle text-4xl mb-4"></i>
                        <p>No quizzes created yet</p>
                        <button onclick="showCreateContentModal('quiz')" class="mt-3 bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-all">
                            <i class="fas fa-plus mr-2"></i>Create First Quiz
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = quizzes.map(quiz => `
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">${quiz.title}</h4>
                            <p class="text-sm text-gray-600 mt-1">${quiz.description || 'No description'}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i class="fas fa-clock mr-1"></i>
                                <span>${quiz.time_limit || 'No time limit'} minutes</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-question mr-1"></i>
                                <span>${quiz.questions_count || 0} questions</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-users mr-1"></i>
                                <span>${quiz.attempts_count || 0} attempts</span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="viewQuizResults(${quiz.id})" class="text-green-600 hover:text-green-800">
                                <i class="fas fa-chart-bar"></i>
                            </button>
                            <button onclick="editQuiz(${quiz.id})" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteQuiz(${quiz.id})" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function displayLabs(labs) {
            const container = document.getElementById('labs-list');

            if (labs.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-flask text-4xl mb-4"></i>
                        <p>No labs created yet</p>
                        <button onclick="showCreateContentModal('lab')" class="mt-3 bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-all">
                            <i class="fas fa-plus mr-2"></i>Create First Lab
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = labs.map(lab => `
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">${lab.title}</h4>
                            <p class="text-sm text-gray-600 mt-1">${lab.description || 'No description'}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                <span>Due: ${lab.due_date || 'No due date'}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-users mr-1"></i>
                                <span>${lab.submissions_count || 0} submissions</span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="viewLabSubmissions(${lab.id})" class="text-green-600 hover:text-green-800">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="editLab(${lab.id})" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteLab(${lab.id})" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function displayMaterials(materials) {
            const container = document.getElementById('materials-list');

            if (materials.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-file text-4xl mb-4"></i>
                        <p>No materials uploaded yet</p>
                        <button onclick="showUploadMaterialModal()" class="mt-3 bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-all">
                            <i class="fas fa-upload mr-2"></i>Upload First Material
                        </button>
                    </div>
                `;
                return;
            }

            container.innerHTML = materials.map(material => `
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">${material.title}</h4>
                            <p class="text-sm text-gray-600 mt-1">${material.description || 'No description'}</p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <i class="fas fa-file mr-1"></i>
                                <span>${material.file_type || 'Unknown'}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-download mr-1"></i>
                                <span>${material.download_count || 0} downloads</span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="downloadMaterial(${material.id})" class="text-green-600 hover:text-green-800">
                                <i class="fas fa-download"></i>
                            </button>
                            <button onclick="editMaterial(${material.id})" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteMaterial(${material.id})" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Display students function
        function displayStudents(students) {
            const container = document.getElementById('students-list');

            if (students.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-users text-4xl mb-4"></i>
                        <p>No students enrolled yet</p>
                        <p class="text-sm mt-2">Students will appear here once they enroll in the course</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = students.map(student => `
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-indigo-600"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">${student.name}</h4>
                                <p class="text-sm text-gray-600">${student.email}</p>
                                <div class="flex items-center mt-1 text-xs text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    <span>Enrolled: ${student.enrolled_date || 'N/A'}</span>
                                    <span class="mx-2">•</span>
                                    <i class="fas fa-chart-line mr-1"></i>
                                    <span>Grade: ${student.current_grade || 'N/A'}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="viewStudentProgress(${student.id})" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-chart-line"></i>
                            </button>
                            <button onclick="contactStudent(${student.id})" class="text-green-600 hover:text-green-800">
                                <i class="fas fa-envelope"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Update student analytics
        function updateStudentAnalytics(analytics) {
            document.getElementById('total-students').textContent = analytics.total_students || 0;
            document.getElementById('active-students').textContent = analytics.active_students || 0;
            document.getElementById('average-grade').textContent = (analytics.average_grade || 0) + '%';
        }

        // Display recent activity
        function displayRecentActivity(activities) {
            const container = document.getElementById('recent-activity');

            if (activities.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-clock text-2xl mb-2"></i>
                        <p class="text-sm">No recent activity</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = activities.slice(0, 5).map(activity => `
                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-${getActivityIcon(activity.type)} text-blue-600 text-xs"></i>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm text-gray-900">${activity.description}</p>
                        <p class="text-xs text-gray-500">${activity.created_at}</p>
                    </div>
                </div>
            `).join('');
        }

        // Display pending tasks
        function displayPendingTasks(tasks) {
            const container = document.getElementById('pending-tasks');

            if (tasks.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-check-circle text-2xl mb-2"></i>
                        <p class="text-sm">All caught up!</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = tasks.slice(0, 5).map(task => `
                <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg border border-yellow-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation text-yellow-600 text-xs"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">${task.title}</p>
                            <p class="text-xs text-gray-600">${task.description}</p>
                        </div>
                    </div>
                    <button onclick="handleTask('${task.type}', ${task.id})" class="text-yellow-600 hover:text-yellow-800">
                        <i class="fas fa-arrow-right"></i>
                    </button>
                </div>
            `).join('');
        }

        // Display gradebook
        function displayGradebook(gradebook) {
            const container = document.getElementById('gradebook-content');

            if (!gradebook.students || gradebook.students.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8 text-gray-500">
                        <i class="fas fa-graduation-cap text-4xl mb-4"></i>
                        <p>No students to grade yet</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = `
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overall Grade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Meals</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Coins</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            ${gradebook.students.map(student => `
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user text-indigo-600 text-xs"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">${student.name}</div>
                                                <div class="text-sm text-gray-500">${student.email}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm font-medium text-gray-900">${student.overall_grade || 0}%</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                            ${student.total_meals || 0} meals
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            ${student.total_coins || 0} coins
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="gradeStudent(${student.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">Grade</button>
                                        <button onclick="viewStudentDetails(${student.id})" class="text-green-600 hover:text-green-900">View</button>
                                    </td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            `;
        }

        // Helper functions
        function getActivityIcon(type) {
            const icons = {
                'submission': 'file-upload',
                'grade': 'star',
                'lecture': 'video',
                'assignment': 'tasks',
                'quiz': 'question-circle',
                'announcement': 'bullhorn',
                'default': 'clock'
            };
            return icons[type] || icons.default;
        }

        // Content creation and management functions
        function showCreateContentModal(type) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.innerHTML = getCreateContentModalHTML(type);
            document.body.appendChild(modal);

            // Set default due date for assignments to 1 week from now
            if (type === 'assignment') {
                setTimeout(() => {
                    const dueDateInput = document.getElementById('assignment-due-date');
                    if (dueDateInput && !dueDateInput.value) {
                        const oneWeekFromNow = new Date();
                        oneWeekFromNow.setDate(oneWeekFromNow.getDate() + 7);
                        dueDateInput.value = oneWeekFromNow.toISOString().slice(0, 16);
                    }
                }, 100);
            }
        }

        function getCreateContentModalHTML(type) {
            const typeConfig = {
                lecture: {
                    title: 'Create New Lecture',
                    icon: 'video',
                    color: 'blue',
                    fields: `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lecture Title</label>
                            <input type="text" id="lecture-title" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="lecture-description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Duration (minutes)</label>
                                <input type="number" id="lecture-duration" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Scheduled Date</label>
                                <input type="datetime-local" id="lecture-date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    `
                },
                assignment: {
                    title: 'Create New Assignment',
                    icon: 'tasks',
                    color: 'green',
                    fields: `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-heading mr-1"></i>Assignment Title *
                            </label>
                            <input type="text" id="assignment-title"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                   placeholder="Enter assignment title" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-1"></i>Description *
                            </label>
                            <textarea id="assignment-description" rows="4"
                                      class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                      placeholder="Describe the assignment objectives and requirements" required></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-list-ol mr-1"></i>Instructions
                            </label>
                            <textarea id="assignment-instructions" rows="3"
                                      class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                      placeholder="Detailed step-by-step instructions for students"></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-star mr-1"></i>Points
                                </label>
                                <input type="number" id="assignment-points" value="100" min="1" max="1000"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                       placeholder="100">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-calendar mr-1"></i>Due Date *
                                </label>
                                <input type="datetime-local" id="assignment-due-date"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                       required>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-file-upload mr-1"></i>Assignment File (Optional)
                            </label>
                            <input type="file" id="assignment-file"
                                   class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                   accept=".pdf,.doc,.docx,.zip,.txt">
                            <p class="text-xs text-gray-500 mt-1">Supported: PDF, DOC, DOCX, ZIP, TXT (Max: 10MB)</p>
                        </div>
                    `
                },
                quiz: {
                    title: 'Create New Quiz',
                    icon: 'question-circle',
                    color: 'purple',
                    fields: `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quiz Title</label>
                            <input type="text" id="quiz-title" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="quiz-description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instructions</label>
                            <textarea id="quiz-instructions" rows="2" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Instructions for taking the quiz"></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Time Limit (minutes)</label>
                                <input type="number" id="quiz-time-limit" value="60" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Max Score</label>
                                <input type="number" id="quiz-max-score" value="100" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Attempts Allowed</label>
                            <select id="quiz-attempts" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                                <option value="1">1 Attempt</option>
                                <option value="2">2 Attempts</option>
                                <option value="3" selected>3 Attempts</option>
                                <option value="unlimited">Unlimited</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                                <input type="datetime-local" id="quiz-start-date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                                <input type="datetime-local" id="quiz-end-date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            </div>
                        </div>
                    `
                },
                lab: {
                    title: 'Create New Lab',
                    icon: 'flask',
                    color: 'orange',
                    fields: `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lab Title</label>
                            <input type="text" id="lab-title" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                            <textarea id="lab-description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Instructions</label>
                            <textarea id="lab-instructions" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Step-by-step lab instructions..."></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Max Score</label>
                                <input type="number" id="lab-max-score" value="100" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                                <input type="datetime-local" id="lab-due-date" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Required Equipment</label>
                            <input type="text" id="lab-equipment" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" placeholder="Computer, Software, etc.">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Lab File (Optional)</label>
                            <input type="file" id="lab-file" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 focus:border-orange-500" accept=".pdf,.doc,.docx,.zip,.txt">
                            <p class="text-xs text-gray-500 mt-1">Supported: PDF, DOC, DOCX, ZIP, TXT (Max: 10MB)</p>
                        </div>
                    `
                }
            };

            const config = typeConfig[type];

            return `
                <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-${config.color}-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-${config.icon} text-${config.color}-600"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">${config.title}</h3>
                            </div>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <form onsubmit="createContent(event, '${type}')" class="space-y-4">
                            ${config.fields}

                            <!-- Content Settings -->
                            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                <h4 class="font-medium text-gray-900 mb-3">
                                    <i class="fas fa-cog mr-1"></i>Content Settings
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="${type}-visible" class="rounded mr-2" checked>
                                        <label for="${type}-visible" class="text-sm text-gray-700">Visible to students</label>
                                    </div>
                                    ${type === 'assignment' || type === 'lab' ? `
                                        <div class="flex items-center">
                                            <input type="checkbox" id="${type}-allow-late" class="rounded mr-2">
                                            <label for="${type}-allow-late" class="text-sm text-gray-700">Allow late submissions</label>
                                        </div>
                                    ` : ''}
                                    <div class="flex items-center">
                                        <input type="checkbox" id="${type}-notify" class="rounded mr-2" checked>
                                        <label for="${type}-notify" class="text-sm text-gray-700">Notify students</label>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                                <button type="button" onclick="this.closest('.fixed').remove()" class="px-6 py-3 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </button>
                                <button type="submit" class="px-6 py-3 bg-${config.color}-600 text-white rounded-lg hover:bg-${config.color}-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Create ${config.title.split(' ')[2]}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
        }

        async function createContent(event, type) {
            event.preventDefault();
            const form = event.target;

            // Show loading state
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
            submitBtn.disabled = true;

            try {
                let formData;
                let isFormDataType = false;

                // Handle different content types
                switch(type) {
                    case 'lecture':
                        formData = {
                            title: form.querySelector('#lecture-title').value,
                            description: form.querySelector('#lecture-description').value,
                            duration: parseInt(form.querySelector('#lecture-duration').value) || null,
                            scheduled_date: form.querySelector('#lecture-date').value || null,
                            is_visible: form.querySelector('#lecture-visible').checked,
                            course_id: currentCourseId
                        };
                        break;

                    case 'assignment':
                        formData = new FormData();
                        formData.append('title', form.querySelector('#assignment-title').value);
                        formData.append('description', form.querySelector('#assignment-description').value);
                        formData.append('instructions', form.querySelector('#assignment-instructions').value || '');
                        formData.append('max_score', form.querySelector('#assignment-points').value || 100);
                        formData.append('due_date', form.querySelector('#assignment-due-date').value);
                        formData.append('is_visible', form.querySelector('#assignment-visible').checked ? '1' : '0');
                        formData.append('allow_late_submission', form.querySelector('#assignment-allow-late').checked ? '1' : '0');
                        formData.append('course_id', currentCourseId);

                        const assignmentFile = form.querySelector('#assignment-file');
                        if (assignmentFile.files[0]) {
                            formData.append('file', assignmentFile.files[0]);
                        }
                        isFormDataType = true;
                        break;

                    case 'quiz':
                        formData = {
                            title: form.querySelector('#quiz-title').value,
                            description: form.querySelector('#quiz-description').value,
                            instructions: form.querySelector('#quiz-instructions')?.value || '',
                            duration_minutes: parseInt(form.querySelector('#quiz-time-limit').value) || 60,
                            max_score: parseInt(form.querySelector('#quiz-max-score').value) || 100,
                            max_attempts: form.querySelector('#quiz-attempts').value,
                            start_time: form.querySelector('#quiz-start-date').value || new Date().toISOString(),
                            end_time: form.querySelector('#quiz-end-date').value || new Date(Date.now() + 7*24*60*60*1000).toISOString(),
                            is_published: form.querySelector('#quiz-visible').checked,
                            course_id: currentCourseId
                        };
                        break;

                    case 'lab':
                        formData = new FormData();
                        formData.append('title', form.querySelector('#lab-title').value);
                        formData.append('description', form.querySelector('#lab-description').value);
                        formData.append('instructions', form.querySelector('#lab-instructions').value);
                        formData.append('max_score', form.querySelector('#lab-max-score').value || 100);
                        formData.append('due_date', form.querySelector('#lab-due-date').value);
                        formData.append('equipment', form.querySelector('#lab-equipment').value || '');
                        formData.append('is_visible', form.querySelector('#lab-visible').checked ? '1' : '0');
                        formData.append('allow_late_submission', form.querySelector('#lab-allow-late').checked ? '1' : '0');
                        formData.append('course_id', currentCourseId);

                        const labFile = form.querySelector('#lab-file');
                        if (labFile.files[0]) {
                            formData.append('file', labFile.files[0]);
                        }
                        isFormDataType = true;
                        break;
                }

                // Map content types to correct API endpoints
                const endpointMap = {
                    'lecture': 'lectures',
                    'assignment': 'assignments',
                    'quiz': 'quizzes',
                    'lab': 'labs',
                    'material': 'materials'
                };

                const endpoint = endpointMap[type] || `${type}s`;
                const result = await apiCall(`/instructor/courses/${currentCourseId}/${endpoint}`, 'POST', formData, isFormDataType);

                if (result && result.ok) {
                    showNotification(`✅ ${type.charAt(0).toUpperCase() + type.slice(1)} created successfully!`, 'success');
                    form.closest('.fixed').remove();

                    // Reload the specific content type
                    switch(type) {
                        case 'lecture':
                            loadLectures();
                            break;
                        case 'assignment':
                            loadAssignments();
                            break;
                        case 'quiz':
                            loadQuizzes();
                            break;
                        case 'lab':
                            loadLabs();
                            break;
                    }

                    // Reload course data to update statistics
                    loadCourseData();

                    // Send notification if enabled
                    if (form.querySelector(`#${type}-notify`).checked) {
                        await sendContentNotification(type, formData.title || formData.get('title'));
                    }
                } else {
                    showNotification(`❌ Failed to create ${type}: ${result.data.message || 'Unknown error'}`, 'error');
                }
            } catch (error) {
                console.error(`Error creating ${type}:`, error);
                showNotification(`❌ Error creating ${type}`, 'error');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }

        // Send notification to students about new content
        async function sendContentNotification(contentType, title) {
            try {
                await apiCall('/instructor/send-notification', 'POST', {
                    course_id: currentCourseId,
                    type: 'content_added',
                    title: `New ${contentType.charAt(0).toUpperCase() + contentType.slice(1)} Added`,
                    message: `A new ${contentType} "${title}" has been added to your course.`,
                    content_type: contentType
                });
            } catch (error) {
                console.error('Error sending notification:', error);
            }
        }

        // Content management functions
        async function editLecture(lectureId) {
            try {
                // Get lecture data
                const result = await apiCall(`/instructor/lectures/${lectureId}`);
                if (!result || !result.ok) {
                    showNotification('Failed to load lecture data', 'error');
                    return;
                }

                const lecture = result.data.data || result.data;
                showEditLectureModal(lecture);
            } catch (error) {
                console.error('Error loading lecture:', error);
                showNotification('Error loading lecture data', 'error');
            }
        }

        function showEditLectureModal(lecture) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-edit text-blue-600"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">📚 Edit Lecture</h3>
                            </div>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <form onsubmit="updateLecture(event, ${lecture.id})" class="space-y-6">
                            <!-- Lecture Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-heading mr-1"></i>Lecture Title *
                                </label>
                                <input type="text" id="edit-lecture-title" value="${lecture.title || ''}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="Enter lecture title" required>
                            </div>

                            <!-- Lecture Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-align-left mr-1"></i>Description
                                </label>
                                <textarea id="edit-lecture-description" rows="4"
                                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                          placeholder="Describe the lecture content">${lecture.description || ''}</textarea>
                            </div>

                            <!-- Duration and Date -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-clock mr-1"></i>Duration (minutes)
                                    </label>
                                    <input type="number" id="edit-lecture-duration" value="${lecture.duration || ''}" min="1" max="300"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                           placeholder="45">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-calendar mr-1"></i>Scheduled Date
                                    </label>
                                    <input type="datetime-local" id="edit-lecture-date"
                                           value="${lecture.scheduled_date ? new Date(lecture.scheduled_date).toISOString().slice(0, 16) : ''}"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                                </div>
                            </div>

                            <!-- Video URL -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-video mr-1"></i>Video URL
                                </label>
                                <input type="url" id="edit-lecture-video" value="${lecture.video_url || ''}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                       placeholder="https://youtube.com/watch?v=...">
                            </div>

                            <!-- Lecture Settings -->
                            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                <h4 class="font-medium text-gray-900 mb-3">
                                    <i class="fas fa-cog mr-1"></i>Lecture Settings
                                </h4>
                                <div class="flex items-center">
                                    <input type="checkbox" id="edit-lecture-visible" class="rounded mr-2"
                                           ${lecture.is_visible !== false ? 'checked' : ''}>
                                    <label for="edit-lecture-visible" class="text-sm text-gray-700">Visible to students</label>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                                <button type="button" onclick="this.closest('.fixed').remove()"
                                        class="px-6 py-3 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </button>
                                <button type="submit"
                                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-save mr-2"></i>Update Lecture
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        async function updateLecture(event, lectureId) {
            event.preventDefault();

            const formData = {
                title: document.getElementById('edit-lecture-title').value,
                description: document.getElementById('edit-lecture-description').value,
                duration: parseInt(document.getElementById('edit-lecture-duration').value) || null,
                scheduled_date: document.getElementById('edit-lecture-date').value || null,
                video_url: document.getElementById('edit-lecture-video').value || null,
                is_visible: document.getElementById('edit-lecture-visible').checked
            };

            try {
                const result = await apiCall(`/instructor/lectures/${lectureId}`, 'PUT', formData);
                if (result && result.ok) {
                    showNotification('✅ Lecture updated successfully!', 'success');
                    loadLectures();
                    loadCourseData();
                    event.target.closest('.fixed').remove();
                } else {
                    showNotification('❌ Failed to update lecture', 'error');
                }
            } catch (error) {
                console.error('Error updating lecture:', error);
                showNotification('❌ Error updating lecture', 'error');
            }
        }

        async function deleteLecture(lectureId) {
            if (!confirm('Are you sure you want to delete this lecture?')) return;

            try {
                const result = await apiCall(`/instructor/lectures/${lectureId}`, 'DELETE');
                if (result && result.ok) {
                    showNotification('✅ Lecture deleted successfully', 'success');
                    loadLectures();
                    loadCourseData();
                } else {
                    const errorMessage = result?.data?.message || 'Failed to delete lecture';
                    showNotification(`❌ ${errorMessage}`, 'error');
                    console.error('Delete lecture error:', result);
                }
            } catch (error) {
                console.error('Error deleting lecture:', error);
                showNotification('❌ Network error while deleting lecture', 'error');
            }
        }

        async function editAssignment(assignmentId) {
            try {
                // First, get the assignment data using instructor endpoint
                const result = await apiCall(`/instructor/assignments/${assignmentId}`);
                if (!result || !result.ok) {
                    showNotification('Failed to load assignment data', 'error');
                    return;
                }

                const assignment = result.data.data || result.data;
                showEditAssignmentModal(assignment);
            } catch (error) {
                console.error('Error loading assignment:', error);
                showNotification('Error loading assignment data', 'error');
            }
        }

        function showEditAssignmentModal(assignment) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-edit text-green-600"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">📝 Edit Assignment</h3>
                            </div>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <form onsubmit="updateAssignment(event, ${assignment.id})" class="space-y-6">
                            <!-- Assignment Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-heading mr-1"></i>Assignment Title *
                                </label>
                                <input type="text" id="edit-assignment-title" value="${assignment.title || ''}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                       placeholder="Enter assignment title" required>
                            </div>

                            <!-- Assignment Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-align-left mr-1"></i>Description *
                                </label>
                                <textarea id="edit-assignment-description" rows="4"
                                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                          placeholder="Describe the assignment requirements and objectives" required>${assignment.description || ''}</textarea>
                            </div>

                            <!-- Assignment Instructions -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-list-ol mr-1"></i>Instructions
                                </label>
                                <textarea id="edit-assignment-instructions" rows="3"
                                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                          placeholder="Detailed instructions for students">${assignment.instructions || ''}</textarea>
                            </div>

                            <!-- Due Date and Points -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-calendar mr-1"></i>Due Date *
                                    </label>
                                    <input type="datetime-local" id="edit-assignment-due-date"
                                           value="${assignment.due_date ? new Date(assignment.due_date).toISOString().slice(0, 16) : ''}"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-star mr-1"></i>Maximum Points
                                    </label>
                                    <input type="number" id="edit-assignment-points" value="${assignment.max_score || assignment.points || 100}" min="1" max="1000"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                           placeholder="100">
                                </div>
                            </div>

                            <!-- Assignment Settings -->
                            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                <h4 class="font-medium text-gray-900 mb-3">
                                    <i class="fas fa-cog mr-1"></i>Assignment Settings
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="edit-assignment-allow-late" class="rounded mr-2"
                                               ${assignment.allow_late_submission ? 'checked' : ''}>
                                        <label for="edit-assignment-allow-late" class="text-sm text-gray-700">Allow late submissions</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="edit-assignment-visible" class="rounded mr-2"
                                               ${assignment.is_visible !== false ? 'checked' : ''}>
                                        <label for="edit-assignment-visible" class="text-sm text-gray-700">Visible to students</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Current File Display -->
                            ${assignment.file_path ? `
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-file text-blue-600 mr-2"></i>
                                            <span class="text-sm text-blue-800">Current file: ${assignment.file_path.split('/').pop()}</span>
                                        </div>
                                        <button type="button" onclick="removeAssignmentFile(${assignment.id})"
                                                class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Remove
                                        </button>
                                    </div>
                                </div>
                            ` : ''}

                            <!-- File Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-paperclip mr-1"></i>Assignment File (Optional)
                                </label>
                                <input type="file" id="edit-assignment-file"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-colors"
                                       accept=".pdf,.doc,.docx,.zip,.txt">
                                <p class="text-xs text-gray-500 mt-1">Supported formats: PDF, DOC, DOCX, ZIP, TXT (Max: 10MB)</p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                                <button type="button" onclick="this.closest('.fixed').remove()"
                                        class="px-6 py-3 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </button>
                                <button type="submit"
                                        class="px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                    <i class="fas fa-save mr-2"></i>Update Assignment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        async function updateAssignment(event, assignmentId) {
            event.preventDefault();

            const formData = new FormData();
            formData.append('title', document.getElementById('edit-assignment-title').value);
            formData.append('description', document.getElementById('edit-assignment-description').value);
            formData.append('instructions', document.getElementById('edit-assignment-instructions').value);
            formData.append('due_date', document.getElementById('edit-assignment-due-date').value);
            formData.append('max_score', document.getElementById('edit-assignment-points').value || 100);
            formData.append('allow_late_submission', document.getElementById('edit-assignment-allow-late').checked);
            formData.append('is_visible', document.getElementById('edit-assignment-visible').checked);

            const fileInput = document.getElementById('edit-assignment-file');
            if (fileInput.files[0]) {
                formData.append('file', fileInput.files[0]);
            }

            try {
                const result = await apiCall(`/instructor/assignments/${assignmentId}`, 'PUT', formData, true);
                if (result && result.ok) {
                    showNotification('✅ Assignment updated successfully!', 'success');
                    loadAssignments();
                    loadCourseData();
                    event.target.closest('.fixed').remove();
                } else {
                    showNotification('❌ Failed to update assignment', 'error');
                }
            } catch (error) {
                console.error('Error updating assignment:', error);
                showNotification('❌ Error updating assignment', 'error');
            }
        }

        async function removeAssignmentFile(assignmentId) {
            if (!confirm('Are you sure you want to remove the assignment file?')) return;

            try {
                const result = await apiCall(`/instructor/assignments/${assignmentId}/remove-file`, 'POST');
                if (result && result.ok) {
                    showNotification('📎 Assignment file removed successfully', 'success');
                    // Refresh the modal
                    document.querySelector('.fixed').remove();
                    editAssignment(assignmentId);
                } else {
                    showNotification('❌ Failed to remove assignment file', 'error');
                }
            } catch (error) {
                console.error('Error removing assignment file:', error);
                showNotification('❌ Error removing assignment file', 'error');
            }
        }

        async function deleteAssignment(assignmentId) {
            if (!confirm('Are you sure you want to delete this assignment?')) return;

            try {
                const result = await apiCall(`/instructor/assignments/${assignmentId}`, 'DELETE');
                if (result && result.ok) {
                    showNotification('✅ Assignment deleted successfully', 'success');
                    loadAssignments();
                    loadCourseData();
                } else {
                    const errorMessage = result?.data?.message || 'Failed to delete assignment';
                    showNotification(`❌ ${errorMessage}`, 'error');
                    console.error('Delete assignment error:', result);
                }
            } catch (error) {
                console.error('Error deleting assignment:', error);
                showNotification('❌ Network error while deleting assignment', 'error');
            }
        }

        async function viewSubmissions(assignmentId) {
            switchTab('grading');
            showNotification('Viewing submissions for assignment', 'success');
        }

        async function editQuiz(quizId) {
            try {
                // Get quiz data
                const result = await apiCall(`/instructor/quizzes/${quizId}`);
                if (!result || !result.ok) {
                    showNotification('Failed to load quiz data', 'error');
                    return;
                }

                const quiz = result.data.data || result.data;
                showEditQuizModal(quiz);
            } catch (error) {
                console.error('Error loading quiz:', error);
                showNotification('Error loading quiz data', 'error');
            }
        }

        function showEditQuizModal(quiz) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-edit text-purple-600"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">🎯 Edit Quiz</h3>
                            </div>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <form onsubmit="updateQuiz(event, ${quiz.id})" class="space-y-6">
                            <!-- Quiz Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-heading mr-1"></i>Quiz Title *
                                </label>
                                <input type="text" id="edit-quiz-title" value="${quiz.title || ''}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                       placeholder="Enter quiz title" required>
                            </div>

                            <!-- Quiz Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-align-left mr-1"></i>Description
                                </label>
                                <textarea id="edit-quiz-description" rows="4"
                                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors"
                                          placeholder="Describe the quiz content">${quiz.description || ''}</textarea>
                            </div>

                            <!-- Quiz Settings -->
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-clock mr-1"></i>Time Limit (minutes)
                                    </label>
                                    <input type="number" id="edit-quiz-duration" value="${quiz.duration_minutes || quiz.time_limit || 60}" min="5" max="300"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-star mr-1"></i>Maximum Score
                                    </label>
                                    <input type="number" id="edit-quiz-max-score" value="${quiz.max_score || 100}" min="1" max="1000"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-redo mr-1"></i>Attempts Allowed
                                    </label>
                                    <select id="edit-quiz-attempts" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                        <option value="1" ${quiz.max_attempts == 1 ? 'selected' : ''}>1 Attempt</option>
                                        <option value="2" ${quiz.max_attempts == 2 ? 'selected' : ''}>2 Attempts</option>
                                        <option value="3" ${quiz.max_attempts == 3 ? 'selected' : ''}>3 Attempts</option>
                                        <option value="unlimited" ${quiz.max_attempts == 'unlimited' ? 'selected' : ''}>Unlimited</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Quiz Timing -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-play mr-1"></i>Start Date
                                    </label>
                                    <input type="datetime-local" id="edit-quiz-start"
                                           value="${quiz.start_time ? new Date(quiz.start_time).toISOString().slice(0, 16) : ''}"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-stop mr-1"></i>End Date
                                    </label>
                                    <input type="datetime-local" id="edit-quiz-end"
                                           value="${quiz.end_time ? new Date(quiz.end_time).toISOString().slice(0, 16) : ''}"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-purple-500 focus:border-purple-500 transition-colors">
                                </div>
                            </div>

                            <!-- Quiz Settings -->
                            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                <h4 class="font-medium text-gray-900 mb-3">
                                    <i class="fas fa-cog mr-1"></i>Quiz Settings
                                </h4>
                                <div class="flex items-center">
                                    <input type="checkbox" id="edit-quiz-published" class="rounded mr-2"
                                           ${quiz.is_published !== false ? 'checked' : ''}>
                                    <label for="edit-quiz-published" class="text-sm text-gray-700">Published and visible to students</label>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                                <button type="button" onclick="this.closest('.fixed').remove()"
                                        class="px-6 py-3 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </button>
                                <button type="submit"
                                        class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                    <i class="fas fa-save mr-2"></i>Update Quiz
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        async function updateQuiz(event, quizId) {
            event.preventDefault();

            const formData = {
                title: document.getElementById('edit-quiz-title').value,
                description: document.getElementById('edit-quiz-description').value,
                duration_minutes: parseInt(document.getElementById('edit-quiz-duration').value) || 60,
                max_score: parseInt(document.getElementById('edit-quiz-max-score').value) || 100,
                max_attempts: document.getElementById('edit-quiz-attempts').value,
                start_time: document.getElementById('edit-quiz-start').value || null,
                end_time: document.getElementById('edit-quiz-end').value || null,
                is_published: document.getElementById('edit-quiz-published').checked
            };

            try {
                const result = await apiCall(`/instructor/quizzes/${quizId}`, 'PUT', formData);
                if (result && result.ok) {
                    showNotification('✅ Quiz updated successfully!', 'success');
                    loadQuizzes();
                    loadCourseData();
                    event.target.closest('.fixed').remove();
                } else {
                    showNotification('❌ Failed to update quiz', 'error');
                }
            } catch (error) {
                console.error('Error updating quiz:', error);
                showNotification('❌ Error updating quiz', 'error');
            }
        }

        async function deleteQuiz(quizId) {
            if (!confirm('Are you sure you want to delete this quiz?')) return;

            try {
                const result = await apiCall(`/instructor/quizzes/${quizId}`, 'DELETE');
                if (result && result.ok) {
                    showNotification('✅ Quiz deleted successfully', 'success');
                    loadQuizzes();
                    loadCourseData();
                } else {
                    const errorMessage = result?.data?.message || 'Failed to delete quiz';
                    showNotification(`❌ ${errorMessage}`, 'error');
                    console.error('Delete quiz error:', result);
                }
            } catch (error) {
                console.error('Error deleting quiz:', error);
                showNotification('❌ Network error while deleting quiz', 'error');
            }
        }

        async function viewQuizResults(quizId) {
            showNotification('Quiz results functionality coming soon', 'warning');
        }

        async function editLab(labId) {
            try {
                // Get lab data
                const result = await apiCall(`/instructor/labs/${labId}`);
                if (!result || !result.ok) {
                    showNotification('Failed to load lab data', 'error');
                    return;
                }

                const lab = result.data.data || result.data;
                showEditLabModal(lab);
            } catch (error) {
                console.error('Error loading lab:', error);
                showNotification('Error loading lab data', 'error');
            }
        }

        function showEditLabModal(lab) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-edit text-orange-600"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">🧪 Edit Lab</h3>
                            </div>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600 transition-colors">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <form onsubmit="updateLab(event, ${lab.id})" class="space-y-6">
                            <!-- Lab Title -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-heading mr-1"></i>Lab Title *
                                </label>
                                <input type="text" id="edit-lab-title" value="${lab.title || ''}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                       placeholder="Enter lab title" required>
                            </div>

                            <!-- Lab Description -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-align-left mr-1"></i>Description
                                </label>
                                <textarea id="edit-lab-description" rows="4"
                                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                          placeholder="Describe the lab objectives">${lab.description || ''}</textarea>
                            </div>

                            <!-- Lab Instructions -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-list-ol mr-1"></i>Instructions
                                </label>
                                <textarea id="edit-lab-instructions" rows="6"
                                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                          placeholder="Step-by-step lab instructions...">${lab.instructions || ''}</textarea>
                            </div>

                            <!-- Lab Settings -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-star mr-1"></i>Maximum Score
                                    </label>
                                    <input type="number" id="edit-lab-max-score" value="${lab.max_score || 100}" min="1" max="1000"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        <i class="fas fa-calendar mr-1"></i>Due Date
                                    </label>
                                    <input type="datetime-local" id="edit-lab-due-date"
                                           value="${lab.due_date ? new Date(lab.due_date).toISOString().slice(0, 16) : ''}"
                                           class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors">
                                </div>
                            </div>

                            <!-- Equipment -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-tools mr-1"></i>Required Equipment
                                </label>
                                <input type="text" id="edit-lab-equipment" value="${lab.equipment || ''}"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                       placeholder="Computer, Software, etc.">
                            </div>

                            <!-- Current File Display -->
                            ${lab.file_path ? `
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-file text-blue-600 mr-2"></i>
                                            <span class="text-sm text-blue-800">Current file: ${lab.file_path.split('/').pop()}</span>
                                        </div>
                                        <button type="button" onclick="removeLabFile(${lab.id})"
                                                class="text-red-600 hover:text-red-800 text-sm">
                                            <i class="fas fa-trash mr-1"></i>Remove
                                        </button>
                                    </div>
                                </div>
                            ` : ''}

                            <!-- File Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-paperclip mr-1"></i>Lab File (Optional)
                                </label>
                                <input type="file" id="edit-lab-file"
                                       class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                                       accept=".pdf,.doc,.docx,.zip,.txt">
                                <p class="text-xs text-gray-500 mt-1">Supported formats: PDF, DOC, DOCX, ZIP, TXT (Max: 10MB)</p>
                            </div>

                            <!-- Lab Settings -->
                            <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                                <h4 class="font-medium text-gray-900 mb-3">
                                    <i class="fas fa-cog mr-1"></i>Lab Settings
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="flex items-center">
                                        <input type="checkbox" id="edit-lab-visible" class="rounded mr-2"
                                               ${lab.is_visible !== false ? 'checked' : ''}>
                                        <label for="edit-lab-visible" class="text-sm text-gray-700">Visible to students</label>
                                    </div>
                                    <div class="flex items-center">
                                        <input type="checkbox" id="edit-lab-allow-late" class="rounded mr-2"
                                               ${lab.allow_late_submission ? 'checked' : ''}>
                                        <label for="edit-lab-allow-late" class="text-sm text-gray-700">Allow late submissions</label>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                                <button type="button" onclick="this.closest('.fixed').remove()"
                                        class="px-6 py-3 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </button>
                                <button type="submit"
                                        class="px-6 py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">
                                    <i class="fas fa-save mr-2"></i>Update Lab
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        async function updateLab(event, labId) {
            event.preventDefault();

            const formData = new FormData();
            formData.append('title', document.getElementById('edit-lab-title').value);
            formData.append('description', document.getElementById('edit-lab-description').value);
            formData.append('instructions', document.getElementById('edit-lab-instructions').value);
            formData.append('max_score', document.getElementById('edit-lab-max-score').value || 100);
            formData.append('due_date', document.getElementById('edit-lab-due-date').value);
            formData.append('equipment', document.getElementById('edit-lab-equipment').value || '');
            formData.append('is_visible', document.getElementById('edit-lab-visible').checked);
            formData.append('allow_late_submission', document.getElementById('edit-lab-allow-late').checked);

            const fileInput = document.getElementById('edit-lab-file');
            if (fileInput.files[0]) {
                formData.append('file', fileInput.files[0]);
            }

            try {
                const result = await apiCall(`/instructor/labs/${labId}`, 'PUT', formData, true);
                if (result && result.ok) {
                    showNotification('✅ Lab updated successfully!', 'success');
                    loadLabs();
                    loadCourseData();
                    event.target.closest('.fixed').remove();
                } else {
                    showNotification('❌ Failed to update lab', 'error');
                }
            } catch (error) {
                console.error('Error updating lab:', error);
                showNotification('❌ Error updating lab', 'error');
            }
        }

        async function removeLabFile(labId) {
            if (!confirm('Are you sure you want to remove the lab file?')) return;

            try {
                const result = await apiCall(`/instructor/labs/${labId}/remove-file`, 'POST');
                if (result && result.ok) {
                    showNotification('📎 Lab file removed successfully', 'success');
                    // Refresh the modal
                    document.querySelector('.fixed').remove();
                    editLab(labId);
                } else {
                    showNotification('❌ Failed to remove lab file', 'error');
                }
            } catch (error) {
                console.error('Error removing lab file:', error);
                showNotification('❌ Error removing lab file', 'error');
            }
        }

        async function deleteLab(labId) {
            if (!confirm('Are you sure you want to delete this lab?')) return;

            try {
                const result = await apiCall(`/instructor/labs/${labId}`, 'DELETE');
                if (result && result.ok) {
                    showNotification('✅ Lab deleted successfully', 'success');
                    loadLabs();
                    loadCourseData();
                } else {
                    const errorMessage = result?.data?.message || 'Failed to delete lab';
                    showNotification(`❌ ${errorMessage}`, 'error');
                    console.error('Delete lab error:', result);
                }
            } catch (error) {
                console.error('Error deleting lab:', error);
                showNotification('❌ Network error while deleting lab', 'error');
            }
        }

        async function viewLabSubmissions(labId) {
            switchTab('grading');
            showNotification('Viewing lab submissions', 'success');
        }

        // Material management functions
        function showUploadMaterialModal() {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-xl max-w-md w-full">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-upload text-gray-600"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Upload Material</h3>
                            </div>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <form onsubmit="uploadMaterial(event)" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Material Title</label>
                                <input type="text" id="material-title" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-500 focus:border-gray-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                <textarea id="material-description" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-500 focus:border-gray-500"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">File</label>
                                <input type="file" id="material-file" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-gray-500 focus:border-gray-500" required>
                            </div>

                            <div class="flex justify-end space-x-3 pt-4">
                                <button type="button" onclick="this.closest('.fixed').remove()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                                    <i class="fas fa-upload mr-2"></i>Upload Material
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        async function uploadMaterial(event) {
            event.preventDefault();
            showNotification('File upload functionality coming soon', 'warning');
            event.target.closest('.fixed').remove();
        }

        async function downloadMaterial(materialId) {
            showNotification('Download functionality coming soon', 'warning');
        }

        async function editMaterial(materialId) {
            showNotification('Edit material functionality coming soon', 'warning');
        }

        async function deleteMaterial(materialId) {
            if (!confirm('Are you sure you want to delete this material?')) return;
            showNotification('Delete material functionality coming soon', 'warning');
        }

        // Student management functions
        async function viewStudentProgress(studentId) {
            showNotification('Student progress view coming soon', 'warning');
        }

        async function contactStudent(studentId) {
            showNotification('Contact student functionality coming soon', 'warning');
        }

        function showAnnouncementModal() {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-xl max-w-2xl w-full">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                    <i class="fas fa-bullhorn text-blue-600"></i>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900">Send Announcement</h3>
                            </div>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <form onsubmit="sendAnnouncement(event)" class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Subject</label>
                                <input type="text" id="announcement-subject" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                                <textarea id="announcement-message" rows="6" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required></textarea>
                            </div>
                            <div class="flex items-center">
                                <input type="checkbox" id="announcement-urgent" class="rounded mr-2">
                                <label for="announcement-urgent" class="text-sm text-gray-700">Mark as urgent</label>
                            </div>

                            <div class="flex justify-end space-x-3 pt-4">
                                <button type="button" onclick="this.closest('.fixed').remove()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                                    <i class="fas fa-paper-plane mr-2"></i>Send Announcement
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        async function sendAnnouncement(event) {
            event.preventDefault();
            showNotification('Announcement sent successfully!', 'success');
            event.target.closest('.fixed').remove();
        }

        // Grading functions
        async function gradeStudent(studentId) {
            showNotification('Individual grading functionality coming soon', 'warning');
        }

        async function viewStudentDetails(studentId) {
            showNotification('Student details view coming soon', 'warning');
        }

        function showBulkGradingModal() {
            showNotification('Bulk grading functionality coming soon', 'warning');
        }

        function showGradeAnalytics() {
            showNotification('Grade analytics functionality coming soon', 'warning');
        }

        async function exportGrades() {
            showNotification('Grade export functionality coming soon', 'warning');
        }

        // Task handling
        function handleTask(taskType, taskId) {
            switch(taskType) {
                case 'grading':
                    switchTab('grading');
                    break;
                case 'content':
                    switchTab('content');
                    break;
                default:
                    showNotification('Task handling functionality coming soon', 'warning');
            }
        }

        // Settings form submission
        document.addEventListener('DOMContentLoaded', function() {
            const settingsForm = document.getElementById('course-settings-form');
            if (settingsForm) {
                settingsForm.addEventListener('submit', async function(event) {
                    event.preventDefault();

                    const formData = {
                        title: document.getElementById('settings-course-title').value,
                        description: document.getElementById('settings-course-description').value
                    };

                    try {
                        const result = await apiCall(`/instructor/courses/${currentCourseId}/settings`, 'PUT', formData);
                        if (result && result.ok) {
                            showNotification('Course settings updated successfully!', 'success');
                            loadCourseData(); // Reload course data
                        } else {
                            showNotification('Failed to update course settings', 'error');
                        }
                    } catch (error) {
                        showNotification('Error updating course settings', 'error');
                    }
                });
            }
        });

        // Initialize content tab on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Set initial content tab
            switchContentTab('lectures');
        });

        // Logout function
        function logout() {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            localStorage.removeItem('role');
            window.location.href = '/login';
        }
    </script>
