<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📚 Content Viewer - Student Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://vjs.zencdn.net/8.6.1/video.min.js"></script>
    <link href="https://vjs.zencdn.net/8.6.1/video-js.css" rel="stylesheet">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .content-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .progress-bar {
            background: linear-gradient(90deg, #10b981 0%, #059669 100%);
        }

        .difficulty-star {
            color: #fbbf24;
        }

        .video-js {
            width: 100%;
            height: 500px;
        }

        .content-locked {
            opacity: 0.6;
            pointer-events: none;
            position: relative;
        }

        .content-locked::after {
            content: "🔒 Complete previous content to unlock";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            z-index: 10;
        }

        .breadcrumb-item:not(:last-child)::after {
            content: "›";
            margin: 0 8px;
            color: #6b7280;
        }

        .note-highlight {
            background: rgba(255, 235, 59, 0.3);
            border-left: 4px solid #ffc107;
            padding: 8px 12px;
            margin: 8px 0;
        }

        .bookmark-active {
            color: #f59e0b;
        }

        .completion-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }

        .content-preview {
            transition: all 0.3s ease;
        }

        .content-preview:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .video-thumbnail {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            cursor: pointer;
        }

        .video-thumbnail::before {
            content: "";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60px;
            height: 60px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2;
            transition: all 0.3s ease;
        }

        .video-thumbnail:hover::before {
            background: rgba(0, 0, 0, 0.9);
            width: 70px;
            height: 70px;
        }

        .video-thumbnail::after {
            content: "▶";
            position: absolute;
            top: 50%;
            left: 52%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 20px;
            z-index: 3;
        }

        .content-type-icon {
            font-size: 1.5rem;
        }

        .status-completed {
            color: #10b981;
            background: #d1fae5;
        }
        .status-in-progress {
            color: #f59e0b;
            background: #fef3c7;
        }
        .status-locked {
            color: #6b7280;
            background: #f3f4f6;
        }

        .module-section {
            border-left: 4px solid #e5e7eb;
            padding-left: 1rem;
            margin-left: 0.5rem;
        }

        .module-section.active {
            border-left-color: #6366f1;
        }

        .content-item {
            transition: all 0.2s ease;
        }

        .content-item:hover {
            background: #f8fafc;
            border-color: #6366f1;
        }

        .content-item.active {
            background: #eef2ff;
            border-color: #6366f1;
        }

        .time-estimate {
            background: #f0f9ff;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }

        .difficulty-easy { color: #10b981; }
        .difficulty-medium { color: #f59e0b; }
        .difficulty-hard { color: #ef4444; }

        .engagement-meter {
            background: linear-gradient(90deg, #ef4444 0%, #f59e0b 50%, #10b981 100%);
            height: 4px;
            border-radius: 2px;
        }

        .video-progress-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            background: #6366f1;
            transition: width 0.3s ease;
        }

        .content-navigation {
            position: sticky;
            top: 20px;
        }

        .quiz-preview {
            background: linear-gradient(135deg, #a855f7 0%, #7c3aed 100%);
        }

        .assignment-preview {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .lab-preview {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .lecture-preview {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation -->
    <nav class="gradient-bg shadow-lg sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="/student/dashboard" class="text-white text-xl font-bold">
                        <i class="fas fa-graduation-cap mr-2"></i>Student Portal
                    </a>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Breadcrumb -->
                    <nav class="text-white text-sm">
                        <div class="flex items-center breadcrumb-item">
                            <a href="/student/courses" class="hover:text-indigo-200">My Courses</a>
                            <span id="course-breadcrumb" class="breadcrumb-item">Loading...</span>
                            <span id="content-breadcrumb" class="breadcrumb-item">📚 Content</span>
                        </div>
                    </nav>
                    <div class="text-white">
                        <i class="fas fa-user-circle mr-2"></i>
                        <span id="user-name">Student</span>
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
        <!-- Loading State -->
        <div id="loading-state" class="space-y-6">
            <div class="loading-skeleton h-8 w-3/4 rounded"></div>
            <div class="loading-skeleton h-96 w-full rounded-lg"></div>
            <div class="loading-skeleton h-32 w-full rounded-lg"></div>
        </div>

        <!-- Content Layout -->
        <div id="content-layout" class="hidden">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                <!-- Sidebar - Course Navigation -->
                <div class="lg:col-span-1">
                    <div class="content-navigation">
                        <!-- Course Progress Overview -->
                        <div class="content-card rounded-xl shadow-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-chart-line text-indigo-600 mr-2"></i>Course Progress
                            </h3>
                            <div class="space-y-3">
                                <div class="flex items-center justify-between text-sm">
                                    <span>Overall Progress</span>
                                    <span id="overall-progress" class="font-medium">65%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div id="overall-progress-bar" class="progress-bar h-2 rounded-full" style="width: 65%"></div>
                                </div>
                                <div class="grid grid-cols-2 gap-2 text-xs text-gray-600">
                                    <div class="flex items-center">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-1"></span>
                                        <span id="completed-count">8 Completed</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="w-2 h-2 bg-gray-400 rounded-full mr-1"></span>
                                        <span id="remaining-count">4 Remaining</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Content Navigation -->
                        <div class="content-card rounded-xl shadow-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-list text-indigo-600 mr-2"></i>Course Content
                            </h3>
                            <div id="content-navigation-list" class="space-y-2">
                                <!-- Content items will be loaded here -->
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Content Area -->
                <div class="lg:col-span-3 space-y-6">
                    <!-- Content Header -->
                    <div class="content-card rounded-xl shadow-lg p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-3">
                                    <span id="content-icon" class="text-3xl mr-3">📚</span>
                                    <div class="flex items-center space-x-2">
                                        <span id="content-status" class="px-3 py-1 rounded-full text-sm font-medium status-in-progress">
                                            ⏳ In Progress
                                        </span>
                                        <span id="difficulty-badge" class="px-2 py-1 rounded text-xs font-medium time-estimate">
                                            ⏱️ 45 min
                                        </span>
                                        <div id="difficulty-stars" class="flex">
                                            <i class="fas fa-star difficulty-star"></i>
                                            <i class="fas fa-star difficulty-star"></i>
                                            <i class="fas fa-star difficulty-star"></i>
                                            <i class="far fa-star text-gray-300"></i>
                                            <i class="far fa-star text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                                <h1 id="content-title" class="text-3xl font-bold text-gray-900 mb-2">Introduction to Programming</h1>
                                <p id="content-description" class="text-gray-600 mb-4">Learn the fundamentals of programming concepts and logic.</p>

                                <!-- Content Meta Information -->
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        <span id="content-date">Added 2 days ago</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-eye mr-1"></i>
                                        <span id="content-views">1,234 views</span>
                                    </div>
                                    <div class="flex items-center">
                                        <i class="fas fa-users mr-1"></i>
                                        <span id="content-engagement">89% completion rate</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-2">
                                <button id="bookmark-btn" onclick="toggleBookmark()" class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors" title="Bookmark">
                                    <i class="far fa-bookmark text-gray-600"></i>
                                </button>
                                <button onclick="showNotes()" class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors" title="Take Notes">
                                    <i class="fas fa-sticky-note text-gray-600"></i>
                                </button>
                                <button onclick="downloadResources()" class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors" title="Download Resources">
                                    <i class="fas fa-download text-gray-600"></i>
                                </button>
                                <button onclick="shareContent()" class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition-colors" title="Share">
                                    <i class="fas fa-share text-gray-600"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div class="mt-6">
                            <div class="flex items-center justify-between text-sm text-gray-600 mb-2">
                                <span>Your Progress</span>
                                <span id="progress-percentage">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div id="progress-bar" class="progress-bar h-3 rounded-full transition-all duration-500" style="width: 0%"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Prerequisites Check -->
                    <div id="prerequisites-section" class="content-card rounded-xl shadow-lg p-6 hidden">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-lock text-orange-600 mr-2"></i>🔒 Prerequisites Required
                        </h3>
                        <p class="text-gray-600 mb-4">Complete the following content before accessing this material:</p>
                        <div id="prerequisites-list" class="space-y-2">
                            <!-- Prerequisites will be loaded here -->
                        </div>
                    </div>

                    <!-- Learning Objectives -->
                    <div class="content-card rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-bullseye text-indigo-600 mr-2"></i>🎯 Learning Objectives
                        </h3>
                        <ul id="learning-objectives" class="space-y-3">
                            <!-- Objectives will be loaded here -->
                        </ul>
                    </div>

                    <!-- Main Content Area -->
                    <div id="main-content-area">
                        <!-- Content will be dynamically loaded here based on type -->
                    </div>

                    <!-- Content Navigation -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Previous Content -->
                        <div id="previous-content" class="content-card rounded-xl shadow-lg p-4 content-preview cursor-pointer" onclick="navigateToContent('previous')">
                            <div class="flex items-center text-gray-600 mb-2">
                                <i class="fas fa-chevron-left mr-2"></i>
                                <span class="text-sm">Previous</span>
                            </div>
                            <div class="flex items-center">
                                <span id="prev-icon" class="text-xl mr-3">📝</span>
                                <div>
                                    <h4 id="prev-title" class="font-medium text-gray-900">Previous Content</h4>
                                    <p id="prev-type" class="text-sm text-gray-500">Assignment</p>
                                </div>
                            </div>
                        </div>

                        <!-- Course Overview -->
                        <div class="content-card rounded-xl shadow-lg p-4 text-center">
                            <button onclick="showCourseOverview()" class="w-full">
                                <i class="fas fa-th-large text-indigo-600 text-2xl mb-2"></i>
                                <h4 class="font-medium text-gray-900">Course Overview</h4>
                                <p class="text-sm text-gray-500">View all content</p>
                            </button>
                        </div>

                        <!-- Next Content -->
                        <div id="next-content" class="content-card rounded-xl shadow-lg p-4 content-preview cursor-pointer" onclick="navigateToContent('next')">
                            <div class="flex items-center justify-end text-gray-600 mb-2">
                                <span class="text-sm">Next</span>
                                <i class="fas fa-chevron-right ml-2"></i>
                            </div>
                            <div class="flex items-center justify-end">
                                <div class="text-right mr-3">
                                    <h4 id="next-title" class="font-medium text-gray-900">Next Content</h4>
                                    <p id="next-type" class="text-sm text-gray-500">Quiz</p>
                                </div>
                                <span id="next-icon" class="text-xl">🎯</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notes Modal -->
    <div id="notes-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
        <div class="bg-white rounded-xl max-w-2xl w-full max-h-[80vh] overflow-y-auto">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-bold text-gray-900">
                        <i class="fas fa-sticky-note text-yellow-500 mr-2"></i>📝 My Notes
                    </h3>
                    <button onclick="closeNotes()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="mb-4">
                    <textarea id="notes-content" rows="8" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Take notes about this content..."></textarea>
                </div>

                <div class="flex justify-end space-x-3">
                    <button onclick="closeNotes()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button onclick="saveNotes()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-save mr-2"></i>Save Notes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        const authToken = localStorage.getItem('token');
        const userRole = localStorage.getItem('role');
        let currentContent = null;
        let courseContent = [];
        let currentContentIndex = 0;
        let isBookmarked = false;
        let videoPlayer = null;

        // Get content parameters from URL
        const urlParams = new URLSearchParams(window.location.search);
        const contentId = urlParams.get('id') || 1;
        const contentType = urlParams.get('type') || 'lecture';
        const courseId = urlParams.get('course') || 1;

        // Content type configurations
        const contentTypeConfig = {
            lecture: {
                icon: '📚',
                name: 'Lecture',
                color: 'blue',
                endpoint: 'lectures'
            },
            assignment: {
                icon: '📝',
                name: 'Assignment',
                color: 'green',
                endpoint: 'assignments'
            },
            quiz: {
                icon: '🎯',
                name: 'Quiz',
                color: 'purple',
                endpoint: 'quizzes'
            },
            lab: {
                icon: '🧪',
                name: 'Lab',
                color: 'orange',
                endpoint: 'labs'
            },
            material: {
                icon: '📄',
                name: 'Material',
                color: 'gray',
                endpoint: 'materials'
            }
        };

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken || userRole !== 'student') {
                window.location.href = '/login';
                return;
            }

            loadUserProfile();
            loadContentData();
            setupEventListeners();
        });

        // API call utility function
        async function apiCall(endpoint, method = 'GET', data = null) {
            const headers = {
                'Accept': 'application/json',
                'Authorization': `Bearer ${authToken}`
            };

            if (data && method !== 'GET') {
                headers['Content-Type'] = 'application/json';
            }

            const config = { method, headers };
            if (data && method !== 'GET') {
                config.body = JSON.stringify(data);
            }

            try {
                const response = await fetch(`/api${endpoint}`, config);

                if (response.status === 401) {
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                    return null;
                }

                const result = await response.json();
                return {
                    ok: response.ok,
                    status: response.status,
                    data: result
                };
            } catch (error) {
                console.error('API call failed:', error);
                return {
                    ok: false,
                    status: 0,
                    data: { status: 'error', message: 'Network error occurred' }
                };
            }
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

        // Load content data
        async function loadContentData() {
            try {
                showLoading();

                // Load course content overview
                await loadCourseContent();

                // Load specific content based on type
                await loadSpecificContent();

                hideLoading();
            } catch (error) {
                console.error('Error loading content:', error);
                showError('Failed to load content');
            }
        }

        // Load course content overview
        async function loadCourseContent() {
            try {
                const result = await apiCall(`/course-content/courses/${courseId}/all`);
                if (result && result.ok) {
                    courseContent = result.data.data || result.data;
                    displayCourseNavigation();
                    updateCourseProgress();
                }
            } catch (error) {
                console.error('Error loading course content:', error);
            }
        }

        // Load specific content
        async function loadSpecificContent() {
            try {
                const config = contentTypeConfig[contentType];
                if (!config) {
                    throw new Error('Unknown content type');
                }

                const result = await apiCall(`/course-content/courses/${courseId}/${config.endpoint}/${contentId}`);
                if (result && result.ok) {
                    currentContent = result.data.data || result.data;
                    displayContentData();
                    loadContentNotes();
                } else {
                    showError(result.data?.message || 'Failed to load content');
                }
            } catch (error) {
                console.error('Error loading specific content:', error);
                showError('An error occurred while loading the content');
            }
        }

        // Display content data
        function displayContentData() {
            const config = contentTypeConfig[contentType];

            // Update breadcrumbs
            document.getElementById('course-breadcrumb').textContent = currentContent.course?.title || 'Course';
            document.getElementById('content-breadcrumb').innerHTML = `${config.icon} ${config.name}`;

            // Update header
            document.getElementById('content-icon').textContent = config.icon;
            document.getElementById('content-title').textContent = currentContent.title;
            document.getElementById('content-description').textContent = currentContent.description || 'No description available';

            // Update status and difficulty
            updateContentStatus();
            updateDifficultyRating();

            // Display learning objectives
            displayLearningObjectives();

            // Display main content based on type
            displayMainContent();

            // Update navigation
            updateContentNavigation();

            // Check prerequisites
            checkPrerequisites();
        }

        // Update content status
        function updateContentStatus() {
            const statusElement = document.getElementById('content-status');
            const progressElement = document.getElementById('progress-percentage');
            const progressBarElement = document.getElementById('progress-bar');

            // Mock progress data - in real implementation, this would come from API
            const progress = currentContent.progress || 0;
            const isCompleted = progress >= 100;
            const isStarted = progress > 0;

            if (isCompleted) {
                statusElement.className = 'px-3 py-1 rounded-full text-sm font-medium status-completed';
                statusElement.innerHTML = '✅ Completed';
            } else if (isStarted) {
                statusElement.className = 'px-3 py-1 rounded-full text-sm font-medium status-in-progress';
                statusElement.innerHTML = '⏳ In Progress';
            } else {
                statusElement.className = 'px-3 py-1 rounded-full text-sm font-medium status-locked';
                statusElement.innerHTML = '🔒 Not Started';
            }

            progressElement.textContent = `${progress}%`;
            progressBarElement.style.width = `${progress}%`;
        }

        // Update difficulty rating
        function updateDifficultyRating() {
            const difficultyStars = document.getElementById('difficulty-stars');
            const difficultyBadge = document.getElementById('difficulty-badge');

            // Mock difficulty and duration data
            const difficulty = currentContent.difficulty || 3;
            const duration = currentContent.duration || 45;

            // Update stars
            difficultyStars.innerHTML = '';
            for (let i = 1; i <= 5; i++) {
                const star = document.createElement('i');
                star.className = i <= difficulty ? 'fas fa-star difficulty-star' : 'far fa-star text-gray-300';
                difficultyStars.appendChild(star);
            }

            // Update duration badge
            difficultyBadge.innerHTML = `⏱️ ${duration} min`;
        }

        // Display learning objectives
        function displayLearningObjectives() {
            const objectivesElement = document.getElementById('learning-objectives');

            // Mock objectives - in real implementation, this would come from content data
            const objectives = currentContent.objectives || [
                'Understand the core concepts covered in this content',
                'Apply the knowledge to practical scenarios',
                'Complete the associated activities successfully'
            ];

            objectivesElement.innerHTML = objectives.map(objective => `
                <li class="flex items-start">
                    <i class="fas fa-check-circle text-green-500 mr-2 mt-1"></i>
                    <span>${objective}</span>
                </li>
            `).join('');
        }

        // Display main content based on type
        function displayMainContent() {
            const mainContentArea = document.getElementById('main-content-area');

            switch (contentType) {
                case 'lecture':
                    displayLectureContent(mainContentArea);
                    break;
                case 'assignment':
                    displayAssignmentContent(mainContentArea);
                    break;
                case 'quiz':
                    displayQuizContent(mainContentArea);
                    break;
                case 'lab':
                    displayLabContent(mainContentArea);
                    break;
                case 'material':
                    displayMaterialContent(mainContentArea);
                    break;
                default:
                    mainContentArea.innerHTML = '<p class="text-gray-600">Content type not supported</p>';
            }
        }

        // Display lecture content
        function displayLectureContent(container) {
            container.innerHTML = `
                <!-- Video Player Section -->
                <div class="content-card rounded-xl shadow-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-play-circle text-indigo-600 mr-2"></i>📹 Lecture Video
                        </h3>
                        <div class="flex items-center space-x-2 text-sm text-gray-500">
                            <span>Quality:</span>
                            <select id="quality-selector" class="border border-gray-300 rounded px-2 py-1 text-sm">
                                <option value="1080p">1080p HD</option>
                                <option value="720p" selected>720p HD</option>
                                <option value="480p">480p</option>
                                <option value="360p">360p</option>
                            </select>
                        </div>
                    </div>

                    <div id="video-container" class="mb-4">
                        ${currentContent.video_url ? `
                            <video
                                id="lecture-video"
                                class="video-js vjs-default-skin"
                                controls
                                preload="auto"
                                data-setup='{"fluid": true, "responsive": true}'
                                poster="/images/video-placeholder.jpg">
                                <source src="${currentContent.video_url}" type="video/mp4">
                                <p class="vjs-no-js">
                                    To view this video please enable JavaScript, and consider upgrading to a web browser that
                                    <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>.
                                </p>
                            </video>
                        ` : `
                            <div class="video-thumbnail bg-gray-200 rounded-lg flex items-center justify-center h-64">
                                <div class="text-center">
                                    <i class="fas fa-video text-gray-400 text-4xl mb-4"></i>
                                    <p class="text-gray-600">Video content will be available soon</p>
                                </div>
                            </div>
                        `}
                    </div>

                    <!-- Video Controls -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <button id="play-pause-btn" onclick="togglePlayPause()" class="flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                                <i class="fas fa-play mr-2"></i>
                                <span>Play</span>
                            </button>
                            <div class="flex items-center space-x-2">
                                <button onclick="changePlaybackSpeed(-0.25)" class="p-2 rounded border border-gray-300 hover:bg-gray-50" title="Slower">
                                    <i class="fas fa-backward"></i>
                                </button>
                                <span id="playback-speed" class="text-sm text-gray-600 min-w-[30px] text-center">1x</span>
                                <button onclick="changePlaybackSpeed(0.25)" class="p-2 rounded border border-gray-300 hover:bg-gray-50" title="Faster">
                                    <i class="fas fa-forward"></i>
                                </button>
                            </div>
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <span id="current-time">0:00</span>
                                <span>/</span>
                                <span id="total-time">0:00</span>
                            </div>
                        </div>

                        <div class="flex items-center space-x-2">
                            <button onclick="toggleCaptions()" class="p-2 rounded border border-gray-300 hover:bg-gray-50" title="Captions">
                                <i class="fas fa-closed-captioning"></i>
                            </button>
                            <button onclick="toggleFullscreen()" class="p-2 rounded border border-gray-300 hover:bg-gray-50" title="Fullscreen">
                                <i class="fas fa-expand"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Lecture Content -->
                <div class="content-card rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-file-text text-indigo-600 mr-2"></i>📄 Lecture Notes
                    </h3>
                    <div class="prose max-w-none">
                        ${currentContent.content || '<p class="text-gray-600">No lecture notes available.</p>'}
                    </div>
                </div>
            `;

            // Initialize video player if video URL exists
            if (currentContent.video_url) {
                setTimeout(() => {
                    initializeVideoPlayer();
                }, 100);
            }
        }

        // Display assignment content
        function displayAssignmentContent(container) {
            container.innerHTML = `
                <div class="content-card rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-tasks text-green-600 mr-2"></i>📝 Assignment Details
                    </h3>
                    <div class="space-y-4">
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-green-800">Due Date</span>
                                <span class="text-sm text-green-600">${currentContent.due_date || 'No due date set'}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-green-800">Max Score</span>
                                <span class="text-sm text-green-600">${currentContent.max_score || 100} points</span>
                            </div>
                        </div>
                        <div class="prose max-w-none">
                            ${currentContent.description || '<p class="text-gray-600">No assignment description available.</p>'}
                        </div>
                        <div class="flex space-x-3">
                            <button onclick="startAssignment()" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                                <i class="fas fa-play mr-2"></i>Start Assignment
                            </button>
                            <button onclick="viewSubmissions()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                <i class="fas fa-history mr-2"></i>View Submissions
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Display quiz content
        function displayQuizContent(container) {
            container.innerHTML = `
                <div class="content-card rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-question-circle text-purple-600 mr-2"></i>🎯 Quiz Information
                    </h3>
                    <div class="space-y-4">
                        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium text-purple-800">Duration:</span>
                                    <span class="text-purple-600 ml-2">${currentContent.duration_minutes || 30} minutes</span>
                                </div>
                                <div>
                                    <span class="font-medium text-purple-800">Questions:</span>
                                    <span class="text-purple-600 ml-2">${currentContent.question_count || 10}</span>
                                </div>
                                <div>
                                    <span class="font-medium text-purple-800">Attempts:</span>
                                    <span class="text-purple-600 ml-2">${currentContent.max_attempts || 3} allowed</span>
                                </div>
                                <div>
                                    <span class="font-medium text-purple-800">Max Score:</span>
                                    <span class="text-purple-600 ml-2">${currentContent.max_score || 100} points</span>
                                </div>
                            </div>
                        </div>
                        <div class="prose max-w-none">
                            ${currentContent.description || '<p class="text-gray-600">No quiz description available.</p>'}
                        </div>
                        <div class="flex space-x-3">
                            <button onclick="startQuiz()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                                <i class="fas fa-play mr-2"></i>Start Quiz
                            </button>
                            <button onclick="viewQuizResults()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                <i class="fas fa-chart-bar mr-2"></i>View Results
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Display lab content
        function displayLabContent(container) {
            container.innerHTML = `
                <div class="content-card rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-flask text-orange-600 mr-2"></i>🧪 Lab Instructions
                    </h3>
                    <div class="space-y-4">
                        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-orange-800">Lab Duration</span>
                                <span class="text-sm text-orange-600">${currentContent.duration || 120} minutes</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-orange-800">Equipment Required</span>
                                <span class="text-sm text-orange-600">${currentContent.equipment || 'Computer'}</span>
                            </div>
                        </div>
                        <div class="prose max-w-none">
                            ${currentContent.instructions || '<p class="text-gray-600">No lab instructions available.</p>'}
                        </div>
                        <div class="flex space-x-3">
                            <button onclick="startLab()" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">
                                <i class="fas fa-play mr-2"></i>Start Lab
                            </button>
                            <button onclick="downloadLabFiles()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                <i class="fas fa-download mr-2"></i>Download Files
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Display material content
        function displayMaterialContent(container) {
            container.innerHTML = `
                <div class="content-card rounded-xl shadow-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-file-alt text-gray-600 mr-2"></i>📄 Course Material
                    </h3>
                    <div class="space-y-4">
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-gray-800">File Type</span>
                                <span class="text-sm text-gray-600">${currentContent.file_type || 'PDF'}</span>
                            </div>
                        </div>
                        <div class="prose max-w-none">
                            ${currentContent.description || '<p class="text-gray-600">No material description available.</p>'}
                        </div>
                        <div class="flex space-x-3">
                            <button onclick="viewMaterial()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                                <i class="fas fa-eye mr-2"></i>View Material
                            </button>
                            <button onclick="downloadMaterial()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                                <i class="fas fa-download mr-2"></i>Download
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        // Display course navigation
        function displayCourseNavigation() {
            const navigationList = document.getElementById('content-navigation-list');

            if (!courseContent || courseContent.length === 0) {
                navigationList.innerHTML = '<p class="text-gray-600 text-sm">No content available</p>';
                return;
            }

            // Group content by type
            const groupedContent = {
                lectures: courseContent.filter(item => item.type === 'lecture'),
                assignments: courseContent.filter(item => item.type === 'assignment'),
                quizzes: courseContent.filter(item => item.type === 'quiz'),
                labs: courseContent.filter(item => item.type === 'lab'),
                materials: courseContent.filter(item => item.type === 'material')
            };

            let navigationHTML = '';

            Object.entries(groupedContent).forEach(([type, items]) => {
                if (items.length > 0) {
                    const config = contentTypeConfig[type];
                    navigationHTML += `
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2 flex items-center">
                                <span class="mr-2">${config.icon}</span>
                                ${config.name}s (${items.length})
                            </h4>
                            <div class="space-y-1">
                                ${items.map(item => {
                                    const isActive = item.id == contentId && type === contentType;
                                    const isCompleted = item.progress >= 100;
                                    const isLocked = item.locked || false;

                                    return `
                                        <div class="content-item ${isActive ? 'active' : ''} ${isLocked ? 'content-locked' : ''} border rounded-lg p-2 cursor-pointer hover:bg-gray-50 transition-colors" onclick="navigateToContent('${type}', ${item.id})">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center flex-1">
                                                    <span class="text-xs mr-2">
                                                        ${isCompleted ? '✅' : isLocked ? '🔒' : '⏳'}
                                                    </span>
                                                    <span class="text-sm ${isActive ? 'font-medium text-indigo-600' : 'text-gray-700'} truncate">
                                                        ${item.title}
                                                    </span>
                                                </div>
                                                <span class="text-xs text-gray-500 ml-2">
                                                    ${item.duration || 30}min
                                                </span>
                                            </div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        </div>
                    `;
                }
            });

            navigationList.innerHTML = navigationHTML;
        }

        // Update course progress
        function updateCourseProgress() {
            const totalContent = courseContent.length;
            const completedContent = courseContent.filter(item => item.progress >= 100).length;
            const overallProgress = totalContent > 0 ? Math.round((completedContent / totalContent) * 100) : 0;

            document.getElementById('overall-progress').textContent = `${overallProgress}%`;
            document.getElementById('overall-progress-bar').style.width = `${overallProgress}%`;
            document.getElementById('completed-count').textContent = `${completedContent} Completed`;
            document.getElementById('remaining-count').textContent = `${totalContent - completedContent} Remaining`;
        }

        // Check prerequisites
        function checkPrerequisites() {
            // Mock prerequisite check - in real implementation, this would come from API
            const hasPrerequisites = currentContent.prerequisites && currentContent.prerequisites.length > 0;
            const prerequisitesSection = document.getElementById('prerequisites-section');

            if (hasPrerequisites) {
                const prerequisitesList = document.getElementById('prerequisites-list');
                prerequisitesList.innerHTML = currentContent.prerequisites.map(prereq => `
                    <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                        <div class="flex items-center">
                            <span class="text-lg mr-3">${contentTypeConfig[prereq.type]?.icon || '📄'}</span>
                            <div>
                                <h4 class="font-medium text-gray-900">${prereq.title}</h4>
                                <p class="text-sm text-gray-500">${prereq.type}</p>
                            </div>
                        </div>
                        <span class="text-sm ${prereq.completed ? 'text-green-600' : 'text-orange-600'}">
                            ${prereq.completed ? '✅ Completed' : '⏳ Required'}
                        </span>
                    </div>
                `).join('');

                prerequisitesSection.classList.remove('hidden');
            } else {
                prerequisitesSection.classList.add('hidden');
            }
        }

        // Update content navigation
        function updateContentNavigation() {
            // Find current content index
            currentContentIndex = courseContent.findIndex(item =>
                item.id == contentId && item.type === contentType
            );

            // Update previous content
            const prevContent = courseContent[currentContentIndex - 1];
            const prevContentElement = document.getElementById('previous-content');

            if (prevContent) {
                document.getElementById('prev-icon').textContent = contentTypeConfig[prevContent.type]?.icon || '📄';
                document.getElementById('prev-title').textContent = prevContent.title;
                document.getElementById('prev-type').textContent = contentTypeConfig[prevContent.type]?.name || 'Content';
                prevContentElement.style.display = 'block';
            } else {
                prevContentElement.style.display = 'none';
            }

            // Update next content
            const nextContent = courseContent[currentContentIndex + 1];
            const nextContentElement = document.getElementById('next-content');

            if (nextContent) {
                document.getElementById('next-icon').textContent = contentTypeConfig[nextContent.type]?.icon || '📄';
                document.getElementById('next-title').textContent = nextContent.title;
                document.getElementById('next-type').textContent = contentTypeConfig[nextContent.type]?.name || 'Content';
                nextContentElement.style.display = 'block';
            } else {
                nextContentElement.style.display = 'none';
            }
        }

        // Initialize video player
        function initializeVideoPlayer() {
            if (document.getElementById('lecture-video')) {
                videoPlayer = videojs('lecture-video', {
                    fluid: true,
                    responsive: true,
                    playbackRates: [0.5, 0.75, 1, 1.25, 1.5, 2],
                    controls: true
                });

                // Track video progress
                videoPlayer.on('timeupdate', function() {
                    const currentTime = videoPlayer.currentTime();
                    const duration = videoPlayer.duration();

                    if (duration > 0) {
                        const progress = Math.round((currentTime / duration) * 100);
                        updateProgress(progress);

                        // Update time display
                        document.getElementById('current-time').textContent = formatTime(currentTime);
                        document.getElementById('total-time').textContent = formatTime(duration);
                    }
                });

                videoPlayer.on('ended', function() {
                    markContentComplete();
                });
            }
        }

        // Format time for display
        function formatTime(seconds) {
            const minutes = Math.floor(seconds / 60);
            const remainingSeconds = Math.floor(seconds % 60);
            return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
        }

        // Update progress
        function updateProgress(progress) {
            document.getElementById('progress-percentage').textContent = `${progress}%`;
            document.getElementById('progress-bar').style.width = `${progress}%`;

            // Save progress to API (mock implementation)
            // In real implementation, this would save to the database
            if (currentContent) {
                currentContent.progress = progress;
            }
        }

        // Mark content as complete
        function markContentComplete() {
            updateProgress(100);

            // Show completion notification
            showNotification('🎉 Content completed successfully!', 'success');

            // Update status
            const statusElement = document.getElementById('content-status');
            statusElement.className = 'px-3 py-1 rounded-full text-sm font-medium status-completed';
            statusElement.innerHTML = '✅ Completed';
        }

        // Show/hide loading state
        function showLoading() {
            document.getElementById('loading-state').classList.remove('hidden');
            document.getElementById('content-layout').classList.add('hidden');
        }

        function hideLoading() {
            document.getElementById('loading-state').classList.add('hidden');
            document.getElementById('content-layout').classList.remove('hidden');
        }

        // Show error message
        function showError(message) {
            hideLoading();
            // In a real implementation, you would show a proper error UI
            alert(`Error: ${message}`);
        }

        // Show notification
        function showNotification(message, type = 'info') {
            // Create notification element
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        // Event listeners and user interactions
        function setupEventListeners() {
            // Notes modal functionality
            const notesModal = document.getElementById('notes-modal');

            // Close modal when clicking outside
            notesModal.addEventListener('click', function(e) {
                if (e.target === notesModal) {
                    closeNotes();
                }
            });
        }

        // Navigation functions
        function navigateToContent(type, id) {
            if (typeof type === 'string' && typeof id === 'number') {
                // Navigate to specific content
                window.location.href = `/student/content-viewer?type=${type}&id=${id}&course=${courseId}`;
            } else if (type === 'previous' || type === 'next') {
                // Navigate to previous/next content
                const targetIndex = type === 'previous' ? currentContentIndex - 1 : currentContentIndex + 1;
                const targetContent = courseContent[targetIndex];

                if (targetContent) {
                    window.location.href = `/student/content-viewer?type=${targetContent.type}&id=${targetContent.id}&course=${courseId}`;
                }
            }
        }

        function showCourseOverview() {
            window.location.href = `/student/courses/${courseId}`;
        }

        // Content interaction functions
        function toggleBookmark() {
            isBookmarked = !isBookmarked;
            const bookmarkBtn = document.getElementById('bookmark-btn');
            const icon = bookmarkBtn.querySelector('i');

            if (isBookmarked) {
                icon.className = 'fas fa-bookmark text-yellow-500';
                showNotification('📌 Content bookmarked!', 'success');
            } else {
                icon.className = 'far fa-bookmark text-gray-600';
                showNotification('Bookmark removed', 'info');
            }
        }

        function showNotes() {
            const notesModal = document.getElementById('notes-modal');
            notesModal.classList.remove('hidden');
            notesModal.classList.add('flex');
        }

        function closeNotes() {
            const notesModal = document.getElementById('notes-modal');
            notesModal.classList.add('hidden');
            notesModal.classList.remove('flex');
        }

        function saveNotes() {
            const notesContent = document.getElementById('notes-content').value;

            // In real implementation, save to API
            localStorage.setItem(`notes_${contentType}_${contentId}`, notesContent);

            showNotification('📝 Notes saved successfully!', 'success');
            closeNotes();
        }

        function loadContentNotes() {
            const savedNotes = localStorage.getItem(`notes_${contentType}_${contentId}`);
            if (savedNotes) {
                document.getElementById('notes-content').value = savedNotes;
            }
        }

        function downloadResources() {
            showNotification('📥 Downloading resources...', 'info');
            // In real implementation, trigger download
        }

        function shareContent() {
            if (navigator.share) {
                navigator.share({
                    title: currentContent.title,
                    text: currentContent.description,
                    url: window.location.href
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(window.location.href);
                showNotification('🔗 Link copied to clipboard!', 'success');
            }
        }

        // Video player controls
        function togglePlayPause() {
            if (videoPlayer) {
                if (videoPlayer.paused()) {
                    videoPlayer.play();
                    document.getElementById('play-pause-btn').innerHTML = '<i class="fas fa-pause mr-2"></i><span>Pause</span>';
                } else {
                    videoPlayer.pause();
                    document.getElementById('play-pause-btn').innerHTML = '<i class="fas fa-play mr-2"></i><span>Play</span>';
                }
            }
        }

        function changePlaybackSpeed(delta) {
            if (videoPlayer) {
                const currentRate = videoPlayer.playbackRate();
                const newRate = Math.max(0.25, Math.min(2, currentRate + delta));
                videoPlayer.playbackRate(newRate);
                document.getElementById('playback-speed').textContent = `${newRate}x`;
            }
        }

        function toggleCaptions() {
            if (videoPlayer) {
                const tracks = videoPlayer.textTracks();
                if (tracks.length > 0) {
                    const track = tracks[0];
                    track.mode = track.mode === 'showing' ? 'hidden' : 'showing';
                }
            }
        }

        function toggleFullscreen() {
            if (videoPlayer) {
                if (videoPlayer.isFullscreen()) {
                    videoPlayer.exitFullscreen();
                } else {
                    videoPlayer.requestFullscreen();
                }
            }
        }

        // Content-specific action functions
        function startAssignment() {
            window.location.href = `/student/assignment-submission?id=${contentId}&course=${courseId}`;
        }

        function viewSubmissions() {
            window.location.href = `/student/assignments?course=${courseId}`;
        }

        function startQuiz() {
            window.location.href = `/student/quiz-take?id=${contentId}&course=${courseId}`;
        }

        function viewQuizResults() {
            showNotification('📊 Loading quiz results...', 'info');
            // In real implementation, show results modal or navigate to results page
        }

        function startLab() {
            showNotification('🧪 Starting lab environment...', 'info');
            // In real implementation, start lab environment
        }

        function downloadLabFiles() {
            showNotification('📥 Downloading lab files...', 'info');
            // In real implementation, download lab files
        }

        function viewMaterial() {
            if (currentContent.file_url) {
                window.open(currentContent.file_url, '_blank');
            } else {
                showNotification('📄 Material preview not available', 'info');
            }
        }

        function downloadMaterial() {
            if (currentContent.file_url) {
                const link = document.createElement('a');
                link.href = currentContent.file_url;
                link.download = currentContent.title;
                link.click();
            } else {
                showNotification('📥 Download not available', 'info');
            }
        }

        // Logout function
        async function logout() {
            try {
                await apiCall('/logout', 'POST');
            } catch (error) {
                console.error('Logout error:', error);
            } finally {
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                localStorage.removeItem('role');
                window.location.href = '/login';
            }
        }
    </script>
</body>
</html>
