<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Content Management - Educational Platform</title>
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

        .content-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .action-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
        }

        .action-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .tab-button {
            transition: all 0.3s ease;
        }

        .tab-button.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-indigo-50 via-white to-purple-50 min-h-screen">
    <!-- Navigation Header -->
    <nav class="glassmorphism shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-indigo-600">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>
                            EduPlatform
                        </h1>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="/instructor/dashboard" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <a href="/instructor/courses" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-book mr-2"></i>My Courses
                        </a>
                        <a href="/instructor/content" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-plus-circle mr-2"></i>Create Content
                        </a>
                        <a href="/instructor/grading" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-star mr-2"></i>Grading
                        </a>
                        <a href="/instructor/analytics" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-chart-bar mr-2"></i>Analytics
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <a href="/notifications" class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-bell text-xl"></i>
                            <span id="notification-badge" class="absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full hidden"></span>
                        </a>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-white text-sm"></i>
                        </div>
                        <div class="hidden md:block">
                            <div class="text-sm font-medium text-gray-700" id="user-name">Loading...</div>
                            <div class="text-xs text-gray-500">Instructor</div>
                        </div>
                        <button onclick="logout()" class="text-gray-400 hover:text-gray-500">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="gradient-bg rounded-xl shadow-xl p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold mb-2">Content Management</h2>
                    <p class="text-indigo-100 text-lg">Create and manage lectures, assignments, quizzes, and materials</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-plus-circle text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Course Selection -->
        <div class="content-card rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Select Course</h3>
                <button onclick="refreshCourses()" class="text-indigo-600 hover:text-indigo-800">
                    <i class="fas fa-sync-alt mr-1"></i>Refresh
                </button>
            </div>
            <select id="courseSelect" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Select a course to manage content...</option>
            </select>
        </div>

        <!-- Content Tabs -->
        <div class="content-card rounded-xl shadow-lg overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6">
                    <button onclick="switchTab('lectures')" id="lectures-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm">
                        <i class="fas fa-video mr-2"></i>Lectures
                    </button>
                    <button onclick="switchTab('assignments')" id="assignments-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm">
                        <i class="fas fa-tasks mr-2"></i>Assignments
                    </button>
                    <button onclick="switchTab('quizzes')" id="quizzes-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm">
                        <i class="fas fa-question-circle mr-2"></i>Quizzes
                    </button>
                    <button onclick="switchTab('labs')" id="labs-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm">
                        <i class="fas fa-flask mr-2"></i>Labs
                    </button>
                    <button onclick="switchTab('materials')" id="materials-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm">
                        <i class="fas fa-file-alt mr-2"></i>Materials
                    </button>
                </nav>
            </div>

            <!-- Content Area -->
            <div class="p-6">
                <!-- Course Selection Message -->
                <div id="no-course-selected" class="text-center py-12">
                    <i class="fas fa-arrow-up text-4xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">Select a Course</h3>
                    <p class="text-gray-500">Choose a course from the dropdown above to start managing content</p>
                </div>

                <!-- Lectures Tab -->
                <div id="lectures-content" class="hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Course Lectures</h3>
                        <button onclick="showCreateLectureModal()" class="action-button text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all">
                            <i class="fas fa-plus mr-2"></i>Add Lecture
                        </button>
                    </div>
                    <div id="lectures-list" class="space-y-4">
                        <!-- Lectures will be loaded here -->
                    </div>
                </div>

                <!-- Assignments Tab -->
                <div id="assignments-content" class="hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Course Assignments</h3>
                        <button onclick="showCreateAssignmentModal()" class="action-button text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all">
                            <i class="fas fa-plus mr-2"></i>Add Assignment
                        </button>
                    </div>
                    <div id="assignments-list" class="space-y-4">
                        <!-- Assignments will be loaded here -->
                    </div>
                </div>

                <!-- Quizzes Tab -->
                <div id="quizzes-content" class="hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Course Quizzes</h3>
                        <button onclick="showCreateQuizModal()" class="action-button text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all">
                            <i class="fas fa-plus mr-2"></i>Add Quiz
                        </button>
                    </div>
                    <div id="quizzes-list" class="space-y-4">
                        <!-- Quizzes will be loaded here -->
                    </div>
                </div>

                <!-- Labs Tab -->
                <div id="labs-content" class="hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Course Labs</h3>
                        <button onclick="showCreateLabModal()" class="action-button text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all">
                            <i class="fas fa-plus mr-2"></i>Add Lab
                        </button>
                    </div>
                    <div id="labs-list" class="space-y-4">
                        <!-- Labs will be loaded here -->
                    </div>
                </div>

                <!-- Materials Tab -->
                <div id="materials-content" class="hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Course Materials</h3>
                        <button onclick="showUploadMaterialModal()" class="action-button text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all">
                            <i class="fas fa-plus mr-2"></i>Upload Material
                        </button>
                    </div>
                    <div id="materials-list" class="space-y-4">
                        <!-- Materials will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Lecture Modal -->
    <div id="createLectureModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Create New Lecture</h3>
                <button onclick="hideCreateLectureModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="createLectureForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" id="lectureTitle" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="lectureDescription" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Video URL</label>
                        <input type="url" id="lectureVideoUrl" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Duration (minutes)</label>
                        <input type="number" id="lectureDuration" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="hideCreateLectureModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Create Lecture
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Assignment Modal -->
    <div id="createAssignmentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Create New Assignment</h3>
                <button onclick="hideCreateAssignmentModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="createAssignmentForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" id="assignmentTitle" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="assignmentDescription" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                        <input type="datetime-local" id="assignmentDueDate" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Points</label>
                        <input type="number" id="assignmentPoints" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="hideCreateAssignmentModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Create Assignment
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Quiz Modal -->
    <div id="createQuizModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Create New Quiz</h3>
                <button onclick="hideCreateQuizModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="createQuizForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" id="quizTitle" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="quizDescription" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Duration (minutes)</label>
                        <input type="number" id="quizDuration" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Score</label>
                        <input type="number" id="quizMaxScore" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="hideCreateQuizModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Create Quiz
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Create Lab Modal -->
    <div id="createLabModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Create New Lab</h3>
                <button onclick="hideCreateLabModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="createLabForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" id="labTitle" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="labDescription" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Instructions</label>
                        <textarea id="labInstructions" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                        <input type="datetime-local" id="labDueDate" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="hideCreateLabModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Create Lab
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Upload Material Modal -->
    <div id="uploadMaterialModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 w-full max-w-md mx-4">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Upload Material</h3>
                <button onclick="hideUploadMaterialModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <form id="uploadMaterialForm">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                        <input type="text" id="materialTitle" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="materialDescription" rows="3" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">File URL</label>
                        <input type="url" id="materialFileUrl" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                        <select id="materialType" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="pdf">PDF Document</option>
                            <option value="video">Video</option>
                            <option value="audio">Audio</option>
                            <option value="image">Image</option>
                            <option value="document">Document</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="hideUploadMaterialModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        Upload Material
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let authToken = localStorage.getItem('token');
        let instructorCourses = [];
        let selectedCourseId = null;
        let currentTab = 'lectures';

        // Initialize page when loaded
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken) {
                window.location.href = '/login';
                return;
            }

            loadUserProfile();
            loadInstructorCourses();
            setupEventListeners();
            switchTab('lectures'); // Default tab
        });

        // Utility function for API calls
        async function apiCall(endpoint, method = 'GET', data = null) {
            const currentToken = localStorage.getItem('token');

            if (!currentToken) {
                window.location.href = '/login';
                return null;
            }

            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${currentToken}`
            };

            const config = { method, headers };
            if (data) config.body = JSON.stringify(data);

            try {
                const response = await fetch(`/api${endpoint}`, config);
                if (response.status === 401) {
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    localStorage.removeItem('role');
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

        // Load user profile
        async function loadUserProfile() {
            try {
                const result = await apiCall('/profile');
                if (result && result.ok) {
                    const user = result.data.user;
                    if (!['instructor', 'manager'].includes(user.role)) {
                        alert('Access denied. This page is for instructors only.');
                        window.location.href = '/dashboard';
                        return;
                    }
                    document.getElementById('user-name').textContent = user.name;
                }
            } catch (error) {
                console.error('Error loading profile:', error);
            }
        }

        // Load instructor courses
        async function loadInstructorCourses() {
            try {
                const result = await apiCall('/courses');
                if (result && result.ok && result.data.status === 'success') {
                    instructorCourses = result.data.data || [];
                    populateCourseSelect();
                }
            } catch (error) {
                console.error('Error loading courses:', error);
            }
        }

        // Populate course select dropdown
        function populateCourseSelect() {
            const select = document.getElementById('courseSelect');
            select.innerHTML = '<option value="">Select a course to manage content...</option>';

            instructorCourses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.id;
                option.textContent = `${course.code} - ${course.title}`;
                select.appendChild(option);
            });
        }

        // Setup event listeners
        function setupEventListeners() {
            document.getElementById('courseSelect').addEventListener('change', function() {
                const courseId = this.value;
                if (courseId) {
                    selectedCourseId = courseId;
                    loadCourseContent(courseId);
                } else {
                    hideContentSections();
                }
            });

            // Form submissions
            document.getElementById('createLectureForm').addEventListener('submit', createLecture);
            document.getElementById('createAssignmentForm').addEventListener('submit', createAssignment);
            document.getElementById('createQuizForm').addEventListener('submit', createQuiz);
            document.getElementById('createLabForm').addEventListener('submit', createLab);
            document.getElementById('uploadMaterialForm').addEventListener('submit', uploadMaterial);
        }

        // Switch tabs
        function switchTab(tabName) {
            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            const activeTab = document.getElementById(`${tabName}-tab`);
            if (activeTab) {
                activeTab.classList.add('active', 'border-indigo-500', 'text-indigo-600');
                activeTab.classList.remove('border-transparent', 'text-gray-500');
            }

            // Show/hide content
            ['lectures', 'assignments', 'quizzes', 'labs', 'materials'].forEach(tab => {
                const content = document.getElementById(`${tab}-content`);
                if (tab === tabName) {
                    content.classList.remove('hidden');
                } else {
                    content.classList.add('hidden');
                }
            });

            currentTab = tabName;

            // Load content if course is selected
            if (selectedCourseId) {
                showContentSections();
                loadCourseContent(selectedCourseId);
            }
        }

        // Show content sections
        function showContentSections() {
            document.getElementById('no-course-selected').classList.add('hidden');
        }

        // Hide content sections
        function hideContentSections() {
            document.getElementById('no-course-selected').classList.remove('hidden');
            ['lectures', 'assignments', 'quizzes', 'labs', 'materials'].forEach(tab => {
                document.getElementById(`${tab}-content`).classList.add('hidden');
            });
        }

        // Load course content
        async function loadCourseContent(courseId) {
            showContentSections();

            // Load content based on current tab
            switch(currentTab) {
                case 'lectures':
                    await loadLectures(courseId);
                    break;
                case 'assignments':
                    await loadAssignments(courseId);
                    break;
                case 'quizzes':
                    await loadQuizzes(courseId);
                    break;
                case 'labs':
                    await loadLabs(courseId);
                    break;
                case 'materials':
                    await loadMaterials(courseId);
                    break;
            }
        }

        // Load lectures
        async function loadLectures(courseId) {
            try {
                const result = await apiCall(`/course-content/courses/${courseId}/lectures`);
                if (result && result.ok) {
                    const lectures = result.data.data || [];
                    displayLectures(lectures);
                }
            } catch (error) {
                console.error('Error loading lectures:', error);
            }
        }

        // Display lectures
        function displayLectures(lectures) {
            const container = document.getElementById('lectures-list');

            if (lectures.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-video text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No lectures created yet</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = lectures.map(lecture => `
                <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">${lecture.title}</h4>
                            <p class="text-sm text-gray-600 mt-1">${lecture.description || 'No description'}</p>
                            ${lecture.video_url ? `<p class="text-xs text-blue-600 mt-2"><i class="fas fa-video mr-1"></i>Video available</p>` : ''}
                            ${lecture.duration ? `<p class="text-xs text-gray-500 mt-1"><i class="fas fa-clock mr-1"></i>${lecture.duration} minutes</p>` : ''}
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editLecture(${lecture.id})" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteLecture(${lecture.id})" class="text-red-600 hover:text-red-800 text-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Modal functions
        function showCreateLectureModal() {
            document.getElementById('createLectureModal').classList.remove('hidden');
        }

        function hideCreateLectureModal() {
            document.getElementById('createLectureModal').classList.add('hidden');
            document.getElementById('createLectureForm').reset();
        }

        function showCreateAssignmentModal() {
            document.getElementById('createAssignmentModal').classList.remove('hidden');
        }

        function hideCreateAssignmentModal() {
            document.getElementById('createAssignmentModal').classList.add('hidden');
            document.getElementById('createAssignmentForm').reset();
        }

        function showCreateQuizModal() {
            document.getElementById('createQuizModal').classList.remove('hidden');
        }

        function hideCreateQuizModal() {
            document.getElementById('createQuizModal').classList.add('hidden');
            document.getElementById('createQuizForm').reset();
        }

        function showCreateLabModal() {
            document.getElementById('createLabModal').classList.remove('hidden');
        }

        function hideCreateLabModal() {
            document.getElementById('createLabModal').classList.add('hidden');
            document.getElementById('createLabForm').reset();
        }

        function showUploadMaterialModal() {
            document.getElementById('uploadMaterialModal').classList.remove('hidden');
        }

        function hideUploadMaterialModal() {
            document.getElementById('uploadMaterialModal').classList.add('hidden');
            document.getElementById('uploadMaterialForm').reset();
        }

        // Create lecture
        async function createLecture(e) {
            e.preventDefault();

            if (!selectedCourseId) {
                alert('Please select a course first');
                return;
            }

            const title = document.getElementById('lectureTitle').value;
            const description = document.getElementById('lectureDescription').value;
            const videoUrl = document.getElementById('lectureVideoUrl').value;
            const duration = document.getElementById('lectureDuration').value;

            try {
                const result = await apiCall(`/instructor/courses/${selectedCourseId}/lectures`, 'POST', {
                    title,
                    description,
                    video_url: videoUrl,
                    duration: duration ? parseInt(duration) : null
                });

                if (result && result.ok) {
                    alert('Lecture created successfully!');
                    hideCreateLectureModal();
                    loadLectures(selectedCourseId);
                } else {
                    alert('Failed to create lecture: ' + (result.data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error creating lecture:', error);
                alert('Failed to create lecture');
            }
        }

        // Placeholder functions for other content types
        async function createAssignment(e) {
            e.preventDefault();
            alert('Assignment creation will be implemented');
        }

        async function createQuiz(e) {
            e.preventDefault();

            if (!selectedCourseId) {
                alert('Please select a course first');
                return;
            }

            const title = document.getElementById('quizTitle').value;
            const description = document.getElementById('quizDescription').value;
            const duration = document.getElementById('quizDuration').value;
            const maxScore = document.getElementById('quizMaxScore').value;

            try {
                const result = await apiCall(`/instructor/courses/${selectedCourseId}/quizzes`, 'POST', {
                    title,
                    description,
                    duration_minutes: duration ? parseInt(duration) : 60,
                    max_score: maxScore ? parseInt(maxScore) : 100,
                    start_time: new Date(Date.now() + 24*60*60*1000).toISOString(), // Tomorrow
                    end_time: new Date(Date.now() + 7*24*60*60*1000).toISOString(), // Next week
                    is_published: false
                });

                if (result && result.ok) {
                    alert('Quiz created successfully!');
                    hideCreateQuizModal();
                    loadQuizzes(selectedCourseId);
                } else {
                    alert('Failed to create quiz: ' + (result.data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error creating quiz:', error);
                alert('Failed to create quiz');
            }
        }

        async function createLab(e) {
            e.preventDefault();

            if (!selectedCourseId) {
                alert('Please select a course first');
                return;
            }

            const title = document.getElementById('labTitle').value;
            const description = document.getElementById('labDescription').value;
            const instructions = document.getElementById('labInstructions').value;
            const dueDate = document.getElementById('labDueDate').value;

            try {
                const result = await apiCall(`/instructor/courses/${selectedCourseId}/labs`, 'POST', {
                    title,
                    description,
                    instructions,
                    due_date: dueDate || null,
                    max_score: 100
                });

                if (result && result.ok) {
                    alert('Lab created successfully!');
                    hideCreateLabModal();
                    loadLabs(selectedCourseId);
                } else {
                    alert('Failed to create lab: ' + (result.data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error creating lab:', error);
                alert('Failed to create lab');
            }
        }

        async function uploadMaterial(e) {
            e.preventDefault();
            alert('Material upload will be implemented with file handling');
        }

        // Placeholder load functions
        async function loadAssignments(courseId) {
            document.getElementById('assignments-list').innerHTML = '<p class="text-gray-500 text-center py-8">Assignments loading...</p>';
        }

        async function loadQuizzes(courseId) {
            document.getElementById('quizzes-list').innerHTML = '<p class="text-gray-500 text-center py-8">Quizzes loading...</p>';
        }

        async function loadLabs(courseId) {
            document.getElementById('labs-list').innerHTML = '<p class="text-gray-500 text-center py-8">Labs loading...</p>';
        }

        async function loadMaterials(courseId) {
            document.getElementById('materials-list').innerHTML = '<p class="text-gray-500 text-center py-8">Materials loading...</p>';
        }

        // Delete functions
        async function deleteLecture(lectureId) {
            if (!confirm('Are you sure you want to delete this lecture?')) return;

            try {
                const result = await apiCall(`/instructor/lectures/${lectureId}`, 'DELETE');
                if (result && result.ok) {
                    alert('Lecture deleted successfully!');
                    loadLectures(selectedCourseId);
                } else {
                    alert('Failed to delete lecture');
                }
            } catch (error) {
                console.error('Error deleting lecture:', error);
                alert('Failed to delete lecture');
            }
        }

        // Edit functions (placeholder)
        function editLecture(lectureId) {
            alert('Edit lecture functionality will be implemented');
        }

        // Refresh content
        function refreshContent() {
            if (selectedCourseId) {
                loadCourseContent(selectedCourseId);
            }
        }

        // Logout function
        async function logout() {
            try {
                await apiCall('/logout', 'POST');
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                localStorage.removeItem('role');
                window.location.href = '/login';
            } catch (error) {
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                localStorage.removeItem('role');
                window.location.href = '/login';
            }
        }
    </script>
</body>
</html>