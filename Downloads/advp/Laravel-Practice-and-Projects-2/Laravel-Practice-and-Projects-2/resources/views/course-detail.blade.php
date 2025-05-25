<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Details - Educational Platform</title>
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

        .progress-bar {
            transition: width 0.8s ease-in-out;
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

        .tab-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .lecture-item:hover {
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
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
                <li aria-current="page">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                        <span class="ml-1 text-sm font-medium text-gray-500" id="breadcrumb-course-title">Course Details</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-8">
        <!-- Loading Indicator -->
        <div id="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
            <p class="mt-4 text-gray-600">Loading course details...</p>
        </div>

        <!-- Course Header -->
        <div id="course-header" class="gradient-bg rounded-xl shadow-xl p-8 mb-8 text-white hidden">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <span class="bg-white bg-opacity-20 text-white text-sm font-medium px-3 py-1 rounded-full mr-3" id="course-code">
                            Loading...
                        </span>
                        <span class="bg-green-500 text-white text-xs font-medium px-2 py-1 rounded-full" id="enrollment-status">
                            <i class="fas fa-check mr-1"></i>Enrolled
                        </span>
                    </div>
                    <h1 class="text-3xl font-bold mb-2" id="course-title">Loading Course...</h1>
                    <p class="text-indigo-100 text-lg mb-4" id="course-description">Loading course description...</p>

                    <!-- Instructor Info -->
                    <div class="flex items-center mb-4">
                        <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-white"></i>
                        </div>
                        <div>
                            <p class="text-sm text-indigo-100">Instructor</p>
                            <p class="font-medium" id="instructor-name">Loading...</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mb-4">
                        <div class="flex justify-between text-sm mb-2">
                            <span>Course Progress</span>
                            <span id="progress-percentage">0%</span>
                        </div>
                        <div class="w-full bg-white bg-opacity-20 rounded-full h-3">
                            <div class="progress-bar bg-white h-3 rounded-full" style="width: 0%" id="progress-bar"></div>
                        </div>
                    </div>
                </div>

                <!-- Course Stats -->
                <div class="lg:ml-8 mt-6 lg:mt-0">
                    <div class="grid grid-cols-2 gap-4 text-center">
                        <div class="bg-white bg-opacity-10 rounded-lg p-4">
                            <div class="text-2xl font-bold" id="credit-hours">-</div>
                            <div class="text-sm text-indigo-100">Credit Hours</div>
                        </div>
                        <div class="bg-white bg-opacity-10 rounded-lg p-4">
                            <div class="text-2xl font-bold" id="total-students">-</div>
                            <div class="text-sm text-indigo-100">Students</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabbed Interface -->
        <div id="course-content" class="hidden">
            <!-- Tab Navigation -->
            <div class="bg-white rounded-xl shadow-lg mb-8">
                <div class="border-b border-gray-200">
                    <nav class="flex space-x-8 px-6" aria-label="Tabs">
                        <button onclick="switchTab('overview')" class="tab-btn tab-active py-4 px-1 border-b-2 border-indigo-500 font-medium text-sm" data-tab="overview">
                            <i class="fas fa-info-circle mr-2"></i>Overview
                        </button>
                        <button onclick="switchTab('lectures')" class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm" data-tab="lectures">
                            <i class="fas fa-play-circle mr-2"></i>Lectures
                        </button>
                        <button onclick="switchTab('assignments')" class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm" data-tab="assignments">
                            <i class="fas fa-tasks mr-2"></i>Assignments
                        </button>
                        <button onclick="switchTab('quizzes')" class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm" data-tab="quizzes">
                            <i class="fas fa-question-circle mr-2"></i>Quizzes
                        </button>
                        <button onclick="switchTab('labs')" class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm" data-tab="labs">
                            <i class="fas fa-flask mr-2"></i>Labs
                        </button>
                        <button onclick="switchTab('materials')" class="tab-btn py-4 px-1 border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 font-medium text-sm" data-tab="materials">
                            <i class="fas fa-download mr-2"></i>Materials
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Tab Content -->

            <!-- Overview Tab -->
            <div id="overview-tab" class="tab-content active">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Course Information -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                                <i class="fas fa-info-circle text-indigo-600 mr-2"></i>
                                Course Description
                            </h3>
                            <div id="course-full-description" class="text-gray-600 leading-relaxed">
                                Loading course description...
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                                <i class="fas fa-bullseye text-indigo-600 mr-2"></i>
                                Learning Objectives
                            </h3>
                            <div id="learning-objectives" class="space-y-2">
                                <div class="animate-pulse">
                                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                                    <div class="h-4 bg-gray-200 rounded w-1/2 mb-2"></div>
                                    <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                                <i class="fas fa-file-alt text-indigo-600 mr-2"></i>
                                Course Syllabus
                            </h3>
                            <div id="course-syllabus" class="space-y-3">
                                <div class="animate-pulse">
                                    <div class="h-4 bg-gray-200 rounded w-full mb-2"></div>
                                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                                    <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="lg:col-span-1">
                        <!-- Quick Stats -->
                        <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Quick Stats</h3>
                            <div class="space-y-4">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Total Lectures</span>
                                    <span class="font-medium" id="total-lectures">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Assignments</span>
                                    <span class="font-medium" id="total-assignments">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Quizzes</span>
                                    <span class="font-medium" id="total-quizzes">-</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Labs</span>
                                    <span class="font-medium" id="total-labs">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="bg-white rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Recent Activity</h3>
                            <div id="recent-activity" class="space-y-3">
                                <div class="animate-pulse">
                                    <div class="h-3 bg-gray-200 rounded w-full mb-2"></div>
                                    <div class="h-3 bg-gray-200 rounded w-2/3"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Lectures Tab -->
            <div id="lectures-tab" class="tab-content">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-play-circle text-indigo-600 mr-2"></i>
                            Course Lectures
                        </h3>
                        <div class="text-sm text-gray-500">
                            <span id="completed-lectures">0</span> of <span id="total-lectures-count">0</span> completed
                        </div>
                    </div>
                    <div id="lectures-list" class="space-y-4">
                        <!-- Loading skeleton -->
                        <div class="animate-pulse">
                            <div class="h-16 bg-gray-200 rounded-lg mb-4"></div>
                            <div class="h-16 bg-gray-200 rounded-lg mb-4"></div>
                            <div class="h-16 bg-gray-200 rounded-lg"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Assignments Tab -->
            <div id="assignments-tab" class="tab-content">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-tasks text-indigo-600 mr-2"></i>
                            Course Assignments
                        </h3>
                        <div class="text-sm text-gray-500">
                            <span id="submitted-assignments">0</span> of <span id="total-assignments-count">0</span> submitted
                        </div>
                    </div>
                    <div id="assignments-list" class="space-y-4">
                        <!-- Loading skeleton -->
                        <div class="animate-pulse">
                            <div class="h-20 bg-gray-200 rounded-lg mb-4"></div>
                            <div class="h-20 bg-gray-200 rounded-lg mb-4"></div>
                            <div class="h-20 bg-gray-200 rounded-lg"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quizzes Tab -->
            <div id="quizzes-tab" class="tab-content">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-question-circle text-indigo-600 mr-2"></i>
                            Course Quizzes
                        </h3>
                        <div class="text-sm text-gray-500">
                            <span id="completed-quizzes">0</span> of <span id="total-quizzes-count">0</span> completed
                        </div>
                    </div>
                    <div id="quizzes-list" class="space-y-4">
                        <!-- Loading skeleton -->
                        <div class="animate-pulse">
                            <div class="h-16 bg-gray-200 rounded-lg mb-4"></div>
                            <div class="h-16 bg-gray-200 rounded-lg mb-4"></div>
                            <div class="h-16 bg-gray-200 rounded-lg"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Labs Tab -->
            <div id="labs-tab" class="tab-content">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-flask text-indigo-600 mr-2"></i>
                            Laboratory Exercises
                        </h3>
                        <div class="text-sm text-gray-500">
                            <span id="completed-labs">0</span> of <span id="total-labs-count">0</span> completed
                        </div>
                    </div>
                    <div id="labs-list" class="space-y-4">
                        <!-- Loading skeleton -->
                        <div class="animate-pulse">
                            <div class="h-20 bg-gray-200 rounded-lg mb-4"></div>
                            <div class="h-20 bg-gray-200 rounded-lg mb-4"></div>
                            <div class="h-20 bg-gray-200 rounded-lg"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Materials Tab -->
            <div id="materials-tab" class="tab-content">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-xl font-semibold text-gray-800">
                            <i class="fas fa-download text-indigo-600 mr-2"></i>
                            Course Materials
                        </h3>
                        <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                            <i class="fas fa-download mr-2"></i>Download All
                        </button>
                    </div>
                    <div id="materials-list" class="space-y-4">
                        <!-- Loading skeleton -->
                        <div class="animate-pulse">
                            <div class="h-16 bg-gray-200 rounded-lg mb-4"></div>
                            <div class="h-16 bg-gray-200 rounded-lg mb-4"></div>
                            <div class="h-16 bg-gray-200 rounded-lg"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <div id="error-message" class="hidden text-center py-12">
            <div class="text-red-500 mb-4">
                <i class="fas fa-exclamation-triangle text-5xl"></i>
            </div>
            <p class="text-xl font-semibold text-gray-800">Unable to load course details</p>
            <p class="text-gray-600 mt-2 mb-6">Please try again later or contact support if the problem persists.</p>
            <button id="retry-button" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Try Again</button>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let authToken = localStorage.getItem('token');
        let courseId = "{{ $courseId ?? '' }}";
        let courseData = {};
        let currentTab = 'overview';

        // Initialize page when DOM loads
        document.addEventListener('DOMContentLoaded', function() {
            // Check authentication
            if (!authToken) {
                const urlParams = new URLSearchParams(window.location.search);
                const tokenFromUrl = urlParams.get('token');
                if (tokenFromUrl) {
                    authToken = tokenFromUrl;
                    localStorage.setItem('token', authToken);
                } else {
                    window.location.href = '/login';
                    return;
                }
            }

            // Get course ID from URL if not provided
            if (!courseId) {
                const pathParts = window.location.pathname.split('/');
                courseId = pathParts[pathParts.length - 1];
            }

            if (!courseId) {
                showError('Course ID not found');
                return;
            }

            // Load initial data
            loadUserProfile();
            loadCourseDetails();
        });

        // API call utility function
        async function apiCall(endpoint, method = 'GET', data = null) {
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${authToken}`
            };

            const config = { method, headers };
            if (data) config.body = JSON.stringify(data);

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

        // Load user profile
        async function loadUserProfile() {
            try {
                const result = await apiCall('/profile');
                if (result && result.ok) {
                    document.getElementById('user-name').textContent = result.data.user.name;
                }
            } catch (error) {
                console.error('Error loading profile:', error);
            }
        }

        // Load course details
        async function loadCourseDetails() {
            try {
                console.log('Loading course details for course ID:', courseId);
                console.log('Using auth token:', authToken ? 'Present' : 'Missing');

                const result = await apiCall(`/courses/${courseId}`);
                console.log('API result:', result);

                if (result && result.ok) {
                    courseData = result.data.data || result.data;
                    console.log('Course data:', courseData);
                    displayCourseDetails(courseData);

                    // Hide loading and show content
                    document.getElementById('loading').classList.add('hidden');
                    document.getElementById('course-header').classList.remove('hidden');
                    document.getElementById('course-content').classList.remove('hidden');

                    // Load tab content
                    loadTabContent(currentTab);
                } else {
                    console.error('API call failed:', result);
                    let errorMessage = 'Failed to load course details';
                    if (result && result.data && result.data.message) {
                        errorMessage = result.data.message;
                    } else if (result && result.status === 403) {
                        errorMessage = 'You must be enrolled in this course to access its content';
                    } else if (result && result.status === 404) {
                        errorMessage = 'Course not found';
                    }
                    throw new Error(errorMessage);
                }
            } catch (error) {
                console.error('Error loading course details:', error);
                showError(error.message || 'Failed to load course details. Please try again later.');
            }
        }

        // Display course details in header
        function displayCourseDetails(course) {
            document.getElementById('course-code').textContent = course.code || 'COURSE';
            document.getElementById('course-title').textContent = course.title || 'Course Title';
            document.getElementById('breadcrumb-course-title').textContent = course.title || 'Course Details';
            document.getElementById('course-description').textContent = course.description || 'Course description not available.';
            document.getElementById('instructor-name').textContent = course.instructor?.name || 'Instructor';
            document.getElementById('credit-hours').textContent = course.credit_hours || '3';

            // Use real enrollment statistics from API
            if (course.enrollment_stats) {
                document.getElementById('total-students').textContent = course.enrollment_stats.total_students || 0;
            } else {
                document.getElementById('total-students').textContent = '0';
            }

            // Use real progress data from API
            if (course.user_progress) {
                const progress = course.user_progress.percentage || 0;
                document.getElementById('progress-percentage').textContent = `${progress}%`;
                document.getElementById('progress-bar').style.width = `${progress}%`;
            } else {
                document.getElementById('progress-percentage').textContent = '0%';
                document.getElementById('progress-bar').style.width = '0%';
            }

            // Update overview tab content
            document.getElementById('course-full-description').textContent = course.description || 'Detailed course description will be available here.';

            // Store course data globally for use in overview tab
            window.currentCourseData = course;
        }

        // Tab switching functionality
        function switchTab(tabName) {
            currentTab = tabName;

            // Update tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('tab-active', 'border-indigo-500', 'text-indigo-600');
                btn.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });

            const activeBtn = document.querySelector(`[data-tab="${tabName}"]`);
            activeBtn.classList.add('tab-active', 'border-indigo-500', 'text-indigo-600');
            activeBtn.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');

            // Update tab content
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });

            document.getElementById(`${tabName}-tab`).classList.add('active');

            // Load tab content if not already loaded
            loadTabContent(tabName);
        }

        // Load tab content
        async function loadTabContent(tabName) {
            switch(tabName) {
                case 'overview':
                    loadOverviewContent();
                    break;
                case 'lectures':
                    loadLecturesContent();
                    break;
                case 'assignments':
                    loadAssignmentsContent();
                    break;
                case 'quizzes':
                    loadQuizzesContent();
                    break;
                case 'labs':
                    loadLabsContent();
                    break;
                case 'materials':
                    loadMaterialsContent();
                    break;
            }
        }

        // Load overview content
        function loadOverviewContent() {
            const course = window.currentCourseData;

            // Generate course-specific learning objectives based on course title
            let objectives = [];
            if (course && course.title) {
                if (course.title.toLowerCase().includes('web')) {
                    objectives = [
                        'Master HTML, CSS, and JavaScript fundamentals',
                        'Build responsive and accessible web interfaces',
                        'Understand modern web development frameworks',
                        'Implement best practices in web security',
                        'Deploy web applications to production environments'
                    ];
                } else if (course.title.toLowerCase().includes('javascript')) {
                    objectives = [
                        'Master advanced JavaScript ES6+ features',
                        'Understand asynchronous programming concepts',
                        'Work with modern JavaScript frameworks',
                        'Implement testing strategies for JavaScript code',
                        'Optimize JavaScript performance and debugging'
                    ];
                } else if (course.title.toLowerCase().includes('database')) {
                    objectives = [
                        'Design efficient database schemas',
                        'Master SQL query optimization',
                        'Understand database normalization principles',
                        'Implement database security best practices',
                        'Work with both relational and NoSQL databases'
                    ];
                } else {
                    objectives = [
                        'Understand core concepts and principles',
                        'Apply theoretical knowledge to practical problems',
                        'Develop critical thinking and analytical skills',
                        'Master industry-standard tools and techniques',
                        'Build real-world projects and applications'
                    ];
                }
            }

            const objectivesHtml = objectives.map(obj =>
                `<div class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-3 mt-1"></i>
                    <span class="text-gray-600">${obj}</span>
                </div>`
            ).join('');

            document.getElementById('learning-objectives').innerHTML = objectivesHtml;

            // Update syllabus with course description
            const syllabusContent = course && course.description ?
                `<div class="space-y-3">
                    <p class="text-gray-600">${course.description}</p>
                    <p class="text-gray-600 mt-4">This course includes comprehensive coverage of:</p>
                    <ul class="list-disc list-inside text-gray-600 space-y-1 ml-4">
                        <li>Theoretical foundations and core concepts</li>
                        <li>Practical exercises and hands-on projects</li>
                        <li>Industry best practices and standards</li>
                        <li>Real-world applications and case studies</li>
                        <li>Assessment through assignments, quizzes, and labs</li>
                    </ul>
                </div>` :
                `<div class="space-y-3">
                    <p class="text-gray-600">Course syllabus will be available soon.</p>
                </div>`;

            document.getElementById('course-syllabus').innerHTML = syllabusContent;

            // Update stats with real data from API
            if (course && course.content_counts) {
                document.getElementById('total-lectures').textContent = course.content_counts.lectures || 0;
                document.getElementById('total-assignments').textContent = course.content_counts.assignments || 0;
                document.getElementById('total-quizzes').textContent = course.content_counts.quizzes || 0;
                document.getElementById('total-labs').textContent = course.content_counts.labs || 0;
            } else {
                document.getElementById('total-lectures').textContent = '0';
                document.getElementById('total-assignments').textContent = '0';
                document.getElementById('total-quizzes').textContent = '0';
                document.getElementById('total-labs').textContent = '0';
            }

            // Update recent activity with realistic content
            const recentActivity = course && course.user_progress ?
                `<div class="space-y-3">
                    <div class="text-sm">
                        <div class="font-medium text-gray-800">Course enrollment confirmed</div>
                        <div class="text-gray-500">${course.user_progress.days_enrolled || 0} days ago</div>
                    </div>
                    <div class="text-sm">
                        <div class="font-medium text-gray-800">Progress updated</div>
                        <div class="text-gray-500">${course.user_progress.percentage || 0}% completed</div>
                    </div>
                    <div class="text-sm">
                        <div class="font-medium text-gray-800">Course materials available</div>
                        <div class="text-gray-500">${course.content_counts?.materials || 0} files ready for download</div>
                    </div>
                </div>` :
                `<div class="space-y-3">
                    <div class="text-sm">
                        <div class="font-medium text-gray-800">Welcome to the course!</div>
                        <div class="text-gray-500">Get started with the course materials</div>
                    </div>
                </div>`;

            document.getElementById('recent-activity').innerHTML = recentActivity;
        }

        // Load lectures content
        async function loadLecturesContent() {
            try {
                const result = await apiCall(`/courses/${courseId}/lectures`);
                const lecturesHtml = generateLecturesHtml(result?.data?.data || []);
                document.getElementById('lectures-list').innerHTML = lecturesHtml;
            } catch (error) {
                console.error('Error loading lectures:', error);
                document.getElementById('lectures-list').innerHTML = '<p class="text-gray-500">Failed to load lectures.</p>';
            }
        }

        // Load assignments content
        async function loadAssignmentsContent() {
            try {
                const result = await apiCall(`/courses/${courseId}/assignments`);
                const assignmentsHtml = generateAssignmentsHtml(result?.data?.data || []);
                document.getElementById('assignments-list').innerHTML = assignmentsHtml;
            } catch (error) {
                console.error('Error loading assignments:', error);
                document.getElementById('assignments-list').innerHTML = '<p class="text-gray-500">Failed to load assignments.</p>';
            }
        }

        // Load quizzes content
        async function loadQuizzesContent() {
            try {
                const result = await apiCall(`/courses/${courseId}/quizzes`);
                const quizzesHtml = generateQuizzesHtml(result?.data?.data || []);
                document.getElementById('quizzes-list').innerHTML = quizzesHtml;
            } catch (error) {
                console.error('Error loading quizzes:', error);
                document.getElementById('quizzes-list').innerHTML = '<p class="text-gray-500">Failed to load quizzes.</p>';
            }
        }

        // Load labs content
        async function loadLabsContent() {
            try {
                const result = await apiCall(`/courses/${courseId}/labs`);
                const labsHtml = generateLabsHtml(result?.data?.data || []);
                document.getElementById('labs-list').innerHTML = labsHtml;
            } catch (error) {
                console.error('Error loading labs:', error);
                document.getElementById('labs-list').innerHTML = '<p class="text-gray-500">Failed to load labs.</p>';
            }
        }

        // Load materials content
        async function loadMaterialsContent() {
            try {
                const result = await apiCall(`/courses/${courseId}/materials`);
                const materialsHtml = generateMaterialsHtml(result?.data?.data || []);
                document.getElementById('materials-list').innerHTML = materialsHtml;
            } catch (error) {
                console.error('Error loading materials:', error);
                document.getElementById('materials-list').innerHTML = '<p class="text-gray-500">Failed to load materials.</p>';
            }
        }

        // HTML generation functions
        function generateLecturesHtml(lectures) {
            if (!lectures.length) {
                return '<div class="text-center py-8"><i class="fas fa-video text-gray-400 text-3xl mb-4"></i><p class="text-gray-500">No lectures available yet.</p></div>';
            }

            return lectures.map(lecture => `
                <div class="lecture-item bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-play text-indigo-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">${lecture.title}</h4>
                                <p class="text-sm text-gray-500">${lecture.duration || '45 min'} • ${lecture.type || 'Video'}</p>
                            </div>
                        </div>
                        <a href="/student/lecture-view?course=${courseId}&lecture=${lecture.id}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm inline-flex items-center">
                            <i class="fas fa-play mr-2"></i>Watch
                        </a>
                    </div>
                </div>
            `).join('');
        }

        function generateAssignmentsHtml(assignments) {
            if (!assignments.length) {
                return '<div class="text-center py-8"><i class="fas fa-tasks text-gray-400 text-3xl mb-4"></i><p class="text-gray-500">No assignments available yet.</p></div>';
            }

            return assignments.map(assignment => {
                const dueDate = assignment.due_date ? new Date(assignment.due_date) : null;
                const now = new Date();
                const isOverdue = dueDate && now > dueDate;
                const dueDateText = dueDate ? formatDate(assignment.due_date) : 'No due date';
                const dueDateClass = isOverdue ? 'text-red-600' : 'text-gray-500';

                return `
                    <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                                    <i class="fas fa-file-alt text-orange-600"></i>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-800">${assignment.title}</h4>
                                    <p class="text-sm ${dueDateClass}">
                                        <i class="fas fa-clock mr-1"></i>Due: ${dueDateText}
                                        ${isOverdue ? ' (Overdue)' : ''}
                                    </p>
                                    <p class="text-xs text-gray-600 mt-1">
                                        <i class="fas fa-star mr-1"></i>Max Score: ${assignment.max_score || 100} points
                                    </p>
                                    <span class="inline-block px-2 py-1 text-xs rounded-full ${getStatusColor(assignment.status)} mt-1">
                                        ${assignment.status || 'Not Started'}
                                    </span>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <a href="/assignment-submission?assignment=${assignment.id}" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg text-sm inline-flex items-center">
                                    <i class="fas fa-upload mr-2"></i>Submit
                                </a>
                            </div>
                        </div>
                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <p class="text-sm text-gray-600 line-clamp-2">${assignment.description || 'No description available.'}</p>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function generateQuizzesHtml(quizzes) {
            if (!quizzes.length) {
                return '<div class="text-center py-8"><i class="fas fa-question-circle text-gray-400 text-3xl mb-4"></i><p class="text-gray-500">No quizzes available yet.</p></div>';
            }

            return quizzes.map(quiz => `
                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-question-circle text-green-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">${quiz.title}</h4>
                                <p class="text-sm text-gray-500">${quiz.questions_count || 10} questions • ${quiz.duration || 30} min</p>
                                <span class="inline-block px-2 py-1 text-xs rounded-full ${getStatusColor(quiz.status)} mt-1">
                                    ${quiz.status || 'Available'}
                                </span>
                            </div>
                        </div>
                        <a href="/student/quiz-take?course=${courseId}&quiz=${quiz.id}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm inline-flex items-center">
                            <i class="fas fa-play mr-2"></i>Start
                        </a>
                    </div>
                </div>
            `).join('');
        }

        function generateLabsHtml(labs) {
            if (!labs.length) {
                return '<div class="text-center py-8"><i class="fas fa-flask text-gray-400 text-3xl mb-4"></i><p class="text-gray-500">No labs available yet.</p></div>';
            }

            return labs.map(lab => `
                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-flask text-purple-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">${lab.title}</h4>
                                <p class="text-sm text-gray-500">Due: ${formatDate(lab.due_date)}</p>
                                <span class="inline-block px-2 py-1 text-xs rounded-full ${getStatusColor(lab.status)} mt-1">
                                    ${lab.status || 'Not Started'}
                                </span>
                            </div>
                        </div>
                        <button class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                            <i class="fas fa-code mr-2"></i>Open
                        </button>
                    </div>
                </div>
            `).join('');
        }

        function generateMaterialsHtml(materials) {
            if (!materials.length) {
                return '<div class="text-center py-8"><i class="fas fa-download text-gray-400 text-3xl mb-4"></i><p class="text-gray-500">No materials available yet.</p></div>';
            }

            return materials.map(material => `
                <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-file text-blue-600"></i>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-800">${material.title}</h4>
                                <p class="text-sm text-gray-500">${material.file_type?.toUpperCase() || 'FILE'} • ${material.formatted_file_size || 'Unknown size'}</p>
                                <p class="text-xs text-gray-400 mt-1">Uploaded: ${material.uploaded_date || 'Unknown date'}</p>
                            </div>
                        </div>
                        <a href="${material.download_url}" target="_blank" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm inline-flex items-center">
                            <i class="fas fa-download mr-2"></i>Download
                        </a>
                    </div>
                </div>
            `).join('');
        }

        // Utility functions
        function formatDate(dateString) {
            if (!dateString) return 'No due date';
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        function getStatusColor(status) {
            switch(status?.toLowerCase()) {
                case 'completed':
                case 'submitted':
                    return 'bg-green-100 text-green-800';
                case 'in_progress':
                case 'started':
                    return 'bg-yellow-100 text-yellow-800';
                case 'overdue':
                    return 'bg-red-100 text-red-800';
                case 'available':
                    return 'bg-blue-100 text-blue-800';
                default:
                    return 'bg-gray-100 text-gray-800';
            }
        }

        function showError(message) {
            document.getElementById('loading').classList.add('hidden');
            document.getElementById('course-header').classList.add('hidden');
            document.getElementById('course-content').classList.add('hidden');
            document.getElementById('error-message').classList.remove('hidden');

            // Update error message text
            const errorMessageElement = document.querySelector('#error-message p:nth-child(2)');
            if (errorMessageElement) {
                errorMessageElement.textContent = message;
            }

            // Add retry functionality
            document.getElementById('retry-button').onclick = () => {
                document.getElementById('error-message').classList.add('hidden');
                document.getElementById('loading').classList.remove('hidden');
                loadCourseDetails();
            };
        }

        // Logout function
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                localStorage.removeItem('token');
                window.location.href = '/login';
            }
        }
    </script>
</body>
</html>
