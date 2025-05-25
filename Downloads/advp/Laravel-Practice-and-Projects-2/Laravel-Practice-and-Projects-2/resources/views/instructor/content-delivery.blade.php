<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>📚 Content Delivery Studio - Instructor Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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

        .action-button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .lecture-card {
            background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
            border-color: #3b82f6;
        }

        .assignment-card {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            border-color: #10b981;
        }

        .quiz-card {
            background: linear-gradient(135deg, #e9d5ff 0%, #ddd6fe 100%);
            border-color: #8b5cf6;
        }

        .lab-card {
            background: linear-gradient(135deg, #fed7aa 0%, #fdba74 100%);
            border-color: #f97316;
        }

        .material-card {
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            border-color: #64748b;
        }

        .content-type-selector {
            transition: all 0.3s ease;
        }

        .content-type-selector:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .content-type-selector.active {
            transform: scale(1.05);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
        }

        .form-section {
            transition: all 0.3s ease;
        }

        .form-section.hidden {
            opacity: 0;
            transform: translateY(20px);
            pointer-events: none;
        }

        .progress-step {
            transition: all 0.3s ease;
        }

        .progress-step.active {
            background: #6366f1;
            color: white;
        }

        .progress-step.completed {
            background: #10b981;
            color: white;
        }

        .file-drop-zone {
            border: 2px dashed #d1d5db;
            transition: all 0.3s ease;
        }

        .file-drop-zone.dragover {
            border-color: #6366f1;
            background: #eef2ff;
        }

        .preview-container {
            max-height: 400px;
            overflow-y: auto;
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

        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            padding: 16px 24px;
            border-radius: 8px;
            color: white;
            font-weight: 500;
            transform: translateX(100%);
            transition: transform 0.3s ease;
        }

        .notification.show {
            transform: translateX(0);
        }

        .notification.success {
            background: #10b981;
        }

        .notification.error {
            background: #ef4444;
        }

        .notification.warning {
            background: #f59e0b;
        }

        .notification.info {
            background: #3b82f6;
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
                    <div class="text-white">
                        <i class="fas fa-user-circle mr-2"></i>
                        <span id="user-name">Instructor</span>
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
        <!-- Header -->
        <div class="content-card rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-magic text-indigo-600 mr-3"></i>📚 Content Delivery Studio
                    </h1>
                    <p class="text-gray-600">Create engaging educational content for your students</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Course Selector -->
                    <div class="min-w-[200px]">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Course</label>
                        <select id="course-selector" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Choose a course...</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-indigo-600" id="total-content">0</div>
                        <div class="text-sm text-gray-500">Total Content</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Type Selection -->
        <div class="content-card rounded-xl shadow-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">
                <i class="fas fa-plus-circle text-green-600 mr-2"></i>What would you like to create?
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Lecture -->
                <div class="content-type-selector lecture-card border-2 rounded-xl p-6 cursor-pointer text-center" onclick="selectContentType('lecture')">
                    <div class="text-4xl mb-3">📚</div>
                    <h3 class="font-semibold text-blue-800 mb-2">Lecture</h3>
                    <p class="text-sm text-blue-600">Video lessons with notes</p>
                </div>

                <!-- Assignment -->
                <div class="content-type-selector assignment-card border-2 rounded-xl p-6 cursor-pointer text-center" onclick="selectContentType('assignment')">
                    <div class="text-4xl mb-3">📝</div>
                    <h3 class="font-semibold text-green-800 mb-2">Assignment</h3>
                    <p class="text-sm text-green-600">Tasks and projects</p>
                </div>

                <!-- Quiz -->
                <div class="content-type-selector quiz-card border-2 rounded-xl p-6 cursor-pointer text-center" onclick="selectContentType('quiz')">
                    <div class="text-4xl mb-3">🎯</div>
                    <h3 class="font-semibold text-purple-800 mb-2">Quiz</h3>
                    <p class="text-sm text-purple-600">Interactive assessments</p>
                </div>

                <!-- Lab -->
                <div class="content-type-selector lab-card border-2 rounded-xl p-6 cursor-pointer text-center" onclick="selectContentType('lab')">
                    <div class="text-4xl mb-3">🧪</div>
                    <h3 class="font-semibold text-orange-800 mb-2">Lab</h3>
                    <p class="text-sm text-orange-600">Hands-on experiments</p>
                </div>

                <!-- Material -->
                <div class="content-type-selector material-card border-2 rounded-xl p-6 cursor-pointer text-center" onclick="selectContentType('material')">
                    <div class="text-4xl mb-3">📄</div>
                    <h3 class="font-semibold text-gray-800 mb-2">Material</h3>
                    <p class="text-sm text-gray-600">Documents and resources</p>
                </div>
            </div>
        </div>

        <!-- Content Creation Form -->
        <div id="content-form-container" class="hidden">
            <!-- Progress Steps -->
            <div class="content-card rounded-xl shadow-lg p-6 mb-6">
                <div class="flex items-center justify-center space-x-4">
                    <div class="progress-step active w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium">1</div>
                    <div class="w-16 h-1 bg-gray-200 rounded"></div>
                    <div class="progress-step w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium bg-gray-200 text-gray-600">2</div>
                    <div class="w-16 h-1 bg-gray-200 rounded"></div>
                    <div class="progress-step w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium bg-gray-200 text-gray-600">3</div>
                </div>
                <div class="flex items-center justify-center space-x-8 mt-2">
                    <span class="text-sm font-medium text-indigo-600">Basic Info</span>
                    <span class="text-sm text-gray-500">Content</span>
                    <span class="text-sm text-gray-500">Settings</span>
                </div>
            </div>

            <!-- Dynamic Form Content -->
            <div id="dynamic-form-content">
                <!-- Form sections will be loaded here -->
            </div>
        </div>

        <!-- Recent Content -->
        <div class="content-card rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-semibold text-gray-900">
                    <i class="fas fa-history text-gray-600 mr-2"></i>📋 Recent Content
                </h2>
                <button onclick="refreshRecentContent()" class="text-indigo-600 hover:text-indigo-800 transition-colors">
                    <i class="fas fa-sync-alt mr-1"></i>Refresh
                </button>
            </div>
            <div id="recent-content-list" class="space-y-4">
                <!-- Recent content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notification-container"></div>

    <script>
        // Global variables
        const authToken = localStorage.getItem('token');
        const userRole = localStorage.getItem('role');
        let selectedCourse = null;
        let selectedContentType = null;
        let currentStep = 1;
        let formData = {};

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken || userRole !== 'instructor') {
                window.location.href = '/login';
                return;
            }

            loadUserProfile();
            loadCourses();
            loadRecentContent();
            setupEventListeners();
        });

        // API call utility function
        async function apiCall(endpoint, method = 'GET', data = null, isFormData = false) {
            const config = {
                method: method,
                headers: {
                    'Authorization': `Bearer ${authToken}`,
                    'Accept': 'application/json'
                }
            };

            if (!isFormData) {
                config.headers['Content-Type'] = 'application/json';
            }

            if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
                if (isFormData) {
                    config.body = data;
                } else {
                    config.body = JSON.stringify(data);
                }
            }

            try {
                const response = await fetch(`/api${endpoint}`, config);
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

        // Load instructor courses
        async function loadCourses() {
            try {
                const result = await apiCall('/instructor/courses');
                const courseSelector = document.getElementById('course-selector');

                if (result && result.ok && result.data.data) {
                    const courses = result.data.data;
                    courseSelector.innerHTML = '<option value="">Choose a course...</option>';

                    courses.forEach(course => {
                        const option = document.createElement('option');
                        option.value = course.id;
                        option.textContent = `${course.code} - ${course.title}`;
                        courseSelector.appendChild(option);
                    });
                } else {
                    showNotification('Failed to load courses', 'error');
                }
            } catch (error) {
                console.error('Error loading courses:', error);
                showNotification('Error loading courses', 'error');
            }
        }

        // Setup event listeners
        function setupEventListeners() {
            document.getElementById('course-selector').addEventListener('change', function() {
                const courseId = this.value;
                if (courseId) {
                    selectedCourse = courseId;
                    loadCourseStats(courseId);
                } else {
                    selectedCourse = null;
                    document.getElementById('total-content').textContent = '0';
                }
            });
        }

        // Load course statistics
        async function loadCourseStats(courseId) {
            try {
                const result = await apiCall(`/instructor/courses/${courseId}/details`);
                if (result && result.ok) {
                    const stats = result.data.data.content_stats || {};
                    const total = (stats.lectures || 0) + (stats.assignments || 0) + (stats.quizzes || 0) + (stats.labs || 0);
                    document.getElementById('total-content').textContent = total;
                }
            } catch (error) {
                console.error('Error loading course stats:', error);
            }
        }

        // Select content type
        function selectContentType(type) {
            if (!selectedCourse) {
                showNotification('Please select a course first', 'warning');
                return;
            }

            selectedContentType = type;

            // Update UI
            document.querySelectorAll('.content-type-selector').forEach(el => {
                el.classList.remove('active');
            });
            event.target.closest('.content-type-selector').classList.add('active');

            // Show form
            showContentForm(type);
        }

        // Show content creation form
        function showContentForm(type) {
            const container = document.getElementById('content-form-container');
            const formContent = document.getElementById('dynamic-form-content');

            container.classList.remove('hidden');
            currentStep = 1;
            updateProgressSteps();

            // Generate form based on content type
            formContent.innerHTML = generateFormHTML(type);

            // Scroll to form
            container.scrollIntoView({ behavior: 'smooth' });
        }

        // Generate form HTML based on content type
        function generateFormHTML(type) {
            const typeConfig = {
                lecture: {
                    icon: '📚',
                    title: 'Create Lecture',
                    color: 'blue'
                },
                assignment: {
                    icon: '📝',
                    title: 'Create Assignment',
                    color: 'green'
                },
                quiz: {
                    icon: '🎯',
                    title: 'Create Quiz',
                    color: 'purple'
                },
                lab: {
                    icon: '🧪',
                    title: 'Create Lab',
                    color: 'orange'
                },
                material: {
                    icon: '📄',
                    title: 'Upload Material',
                    color: 'gray'
                }
            };

            const config = typeConfig[type];

            return `
                <div class="content-card rounded-xl shadow-lg p-6">
                    <div class="flex items-center mb-6">
                        <div class="text-3xl mr-3">${config.icon}</div>
                        <h2 class="text-2xl font-bold text-gray-900">${config.title}</h2>
                    </div>

                    <form id="content-creation-form" class="space-y-6">
                        <!-- Step 1: Basic Information -->
                        <div id="step-1" class="form-section">
                            ${generateStep1HTML(type)}
                        </div>

                        <!-- Step 2: Content -->
                        <div id="step-2" class="form-section hidden">
                            ${generateStep2HTML(type)}
                        </div>

                        <!-- Step 3: Settings -->
                        <div id="step-3" class="form-section hidden">
                            ${generateStep3HTML(type)}
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="flex justify-between pt-6 border-t border-gray-200">
                            <button type="button" id="prev-btn" onclick="previousStep()" class="px-6 py-3 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors hidden">
                                <i class="fas fa-arrow-left mr-2"></i>Previous
                            </button>
                            <div class="flex space-x-3">
                                <button type="button" onclick="cancelCreation()" class="px-6 py-3 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">
                                    <i class="fas fa-times mr-2"></i>Cancel
                                </button>
                                <button type="button" id="next-btn" onclick="nextStep()" class="px-6 py-3 bg-${config.color}-600 text-white rounded-lg hover:bg-${config.color}-700 transition-colors">
                                    Next <i class="fas fa-arrow-right ml-2"></i>
                                </button>
                                <button type="submit" id="submit-btn" class="px-6 py-3 bg-${config.color}-600 text-white rounded-lg hover:bg-${config.color}-700 transition-colors hidden">
                                    <i class="fas fa-save mr-2"></i>Create ${config.title.split(' ')[1]}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            `;
        }

        // Show notification
        function showNotification(message, type = 'info') {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;

            container.appendChild(notification);

            setTimeout(() => {
                notification.classList.add('show');
            }, 100);

            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
        }

        // Load recent content
        async function loadRecentContent() {
            try {
                const result = await apiCall('/instructor/recent-content');
                const container = document.getElementById('recent-content-list');

                if (result && result.ok && result.data.data) {
                    const content = result.data.data;

                    if (content.length === 0) {
                        container.innerHTML = '<p class="text-gray-600 text-center py-8">No recent content found</p>';
                        return;
                    }

                    container.innerHTML = content.map(item => `
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div class="flex items-start">
                                    <div class="text-2xl mr-3">${getContentIcon(item.type)}</div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900">${item.title}</h3>
                                        <p class="text-sm text-gray-600 mt-1">${item.course?.title || 'Unknown Course'}</p>
                                        <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                            <span><i class="fas fa-calendar mr-1"></i>${new Date(item.created_at).toLocaleDateString()}</span>
                                            <span><i class="fas fa-eye mr-1"></i>${item.views || 0} views</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="editContent('${item.type}', ${item.id})" class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="deleteContent('${item.type}', ${item.id})" class="text-red-600 hover:text-red-800 text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    container.innerHTML = '<p class="text-gray-600 text-center py-8">Failed to load recent content</p>';
                }
            } catch (error) {
                console.error('Error loading recent content:', error);
                document.getElementById('recent-content-list').innerHTML = '<p class="text-red-600 text-center py-8">Error loading recent content</p>';
            }
        }

        // Get content icon
        function getContentIcon(type) {
            const icons = {
                lecture: '📚',
                assignment: '📝',
                quiz: '🎯',
                lab: '🧪',
                material: '📄'
            };
            return icons[type] || '📄';
        }

        // Generate Step 1 HTML (Basic Information)
        function generateStep1HTML(type) {
            return `
                <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-heading mr-1"></i>Title *
                        </label>
                        <input type="text" id="content-title" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                               placeholder="Enter ${type} title" required>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-align-left mr-1"></i>Description *
                        </label>
                        <textarea id="content-description" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                  placeholder="Describe what students will learn" required></textarea>
                    </div>
                    ${type === 'assignment' || type === 'quiz' || type === 'lab' ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-calendar mr-1"></i>Due Date *
                            </label>
                            <input type="datetime-local" id="content-due-date" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-star mr-1"></i>Maximum Points
                            </label>
                            <input type="number" id="content-max-score" min="1" max="1000" value="100" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    ` : ''}
                    ${type === 'lecture' ? `
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-clock mr-1"></i>Duration (minutes)
                            </label>
                            <input type="number" id="content-duration" min="1" max="300" value="45" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-video mr-1"></i>Video URL
                            </label>
                            <input type="url" id="content-video-url" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="https://youtube.com/watch?v=...">
                        </div>
                    ` : ''}
                </div>
            `;
        }

        // Generate Step 2 HTML (Content)
        function generateStep2HTML(type) {
            if (type === 'lecture') {
                return `
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📝 Lecture Content</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-list-ol mr-1"></i>Learning Objectives
                            </label>
                            <textarea id="content-objectives" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="• Understand key concepts&#10;• Apply knowledge to real scenarios&#10;• Complete practical exercises"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-file-text mr-1"></i>Lecture Notes
                            </label>
                            <textarea id="content-notes" rows="8" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Enter detailed lecture notes, key points, and additional resources..."></textarea>
                        </div>
                    </div>
                `;
            } else if (type === 'assignment') {
                return `
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Assignment Details</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tasks mr-1"></i>Instructions
                            </label>
                            <textarea id="content-instructions" rows="6" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Provide detailed instructions for students..." required></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-paperclip mr-1"></i>Assignment File (Optional)
                            </label>
                            <div class="file-drop-zone border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                                <input type="file" id="content-file" class="hidden" accept=".pdf,.doc,.docx,.zip,.txt">
                                <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-4"></i>
                                <p class="text-gray-600 mb-2">Drop files here or click to browse</p>
                                <p class="text-sm text-gray-500">Supported: PDF, DOC, DOCX, ZIP, TXT (Max: 10MB)</p>
                                <button type="button" onclick="document.getElementById('content-file').click()" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                    Choose File
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            } else if (type === 'quiz') {
                return `
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">❓ Quiz Configuration</h3>
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-clock mr-1"></i>Time Limit (minutes)
                                </label>
                                <input type="number" id="quiz-duration" min="5" max="180" value="60" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-redo mr-1"></i>Attempts Allowed
                                </label>
                                <select id="quiz-attempts" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="1">1 Attempt</option>
                                    <option value="2">2 Attempts</option>
                                    <option value="3" selected>3 Attempts</option>
                                    <option value="unlimited">Unlimited</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-info-circle mr-1"></i>Quiz Instructions
                            </label>
                            <textarea id="quiz-instructions" rows="4" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Instructions for students taking this quiz..."></textarea>
                        </div>
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <p class="text-blue-800 text-sm">
                                <i class="fas fa-lightbulb mr-2"></i>
                                <strong>Note:</strong> You can add questions after creating the quiz in the quiz management section.
                            </p>
                        </div>
                    </div>
                `;
            } else if (type === 'lab') {
                return `
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">🧪 Lab Instructions</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-list-ol mr-1"></i>Lab Instructions
                            </label>
                            <textarea id="lab-instructions" rows="8" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                      placeholder="Step-by-step lab instructions..." required></textarea>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-tools mr-1"></i>Required Equipment
                                </label>
                                <input type="text" id="lab-equipment" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="Computer, Software, etc.">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    <i class="fas fa-clock mr-1"></i>Estimated Duration
                                </label>
                                <input type="number" id="lab-duration" min="30" max="480" value="120" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <span class="text-sm text-gray-500">minutes</span>
                            </div>
                        </div>
                    </div>
                `;
            } else if (type === 'material') {
                return `
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">📄 Upload Material</h3>
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-upload mr-1"></i>Select File *
                            </label>
                            <div class="file-drop-zone border-2 border-dashed border-gray-300 rounded-lg p-8 text-center">
                                <input type="file" id="material-file" class="hidden" accept=".pdf,.doc,.docx,.ppt,.pptx,.zip,.txt,.jpg,.png" required>
                                <i class="fas fa-cloud-upload-alt text-6xl text-gray-400 mb-4"></i>
                                <p class="text-lg text-gray-600 mb-2">Drop your file here or click to browse</p>
                                <p class="text-sm text-gray-500 mb-4">Supported: PDF, DOC, DOCX, PPT, PPTX, ZIP, TXT, Images (Max: 50MB)</p>
                                <button type="button" onclick="document.getElementById('material-file').click()" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                    <i class="fas fa-folder-open mr-2"></i>Choose File
                                </button>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-tag mr-1"></i>Material Type
                            </label>
                            <select id="material-type" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="reading">📖 Reading Material</option>
                                <option value="slides">📊 Presentation Slides</option>
                                <option value="reference">📚 Reference Document</option>
                                <option value="template">📝 Template</option>
                                <option value="other">📄 Other</option>
                            </select>
                        </div>
                    </div>
                `;
            }
        }

        // Generate Step 3 HTML (Settings)
        function generateStep3HTML(type) {
            return `
                <h3 class="text-lg font-semibold text-gray-900 mb-4">⚙️ Settings & Publishing</h3>
                <div class="space-y-6">
                    <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                        <h4 class="font-medium text-gray-900 mb-3">Visibility Settings</h4>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <input type="checkbox" id="content-visible" class="rounded mr-3" checked>
                                <label for="content-visible" class="text-sm text-gray-700">
                                    <i class="fas fa-eye mr-1"></i>Visible to students
                                </label>
                            </div>
                            ${type === 'assignment' || type === 'quiz' || type === 'lab' ? `
                                <div class="flex items-center">
                                    <input type="checkbox" id="allow-late" class="rounded mr-3">
                                    <label for="allow-late" class="text-sm text-gray-700">
                                        <i class="fas fa-clock mr-1"></i>Allow late submissions
                                    </label>
                                </div>
                            ` : ''}
                            <div class="flex items-center">
                                <input type="checkbox" id="send-notification" class="rounded mr-3" checked>
                                <label for="send-notification" class="text-sm text-gray-700">
                                    <i class="fas fa-bell mr-1"></i>Send notification to students
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mr-3 mt-1"></i>
                            <div>
                                <h4 class="font-medium text-blue-800 mb-1">Ready to Create?</h4>
                                <p class="text-blue-700 text-sm">
                                    Review your ${type} details and click "Create ${type.charAt(0).toUpperCase() + type.slice(1)}" to save it to your course.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }

        // Step navigation functions
        function updateProgressSteps() {
            const steps = document.querySelectorAll('.progress-step');
            steps.forEach((step, index) => {
                const stepNumber = index + 1;
                if (stepNumber < currentStep) {
                    step.className = 'progress-step completed w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium';
                } else if (stepNumber === currentStep) {
                    step.className = 'progress-step active w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium';
                } else {
                    step.className = 'progress-step w-10 h-10 rounded-full flex items-center justify-center text-sm font-medium bg-gray-200 text-gray-600';
                }
            });

            // Update navigation buttons
            const prevBtn = document.getElementById('prev-btn');
            const nextBtn = document.getElementById('next-btn');
            const submitBtn = document.getElementById('submit-btn');

            if (currentStep === 1) {
                prevBtn.classList.add('hidden');
            } else {
                prevBtn.classList.remove('hidden');
            }

            if (currentStep === 3) {
                nextBtn.classList.add('hidden');
                submitBtn.classList.remove('hidden');
            } else {
                nextBtn.classList.remove('hidden');
                submitBtn.classList.add('hidden');
            }

            // Show/hide form sections
            for (let i = 1; i <= 3; i++) {
                const section = document.getElementById(`step-${i}`);
                if (i === currentStep) {
                    section.classList.remove('hidden');
                } else {
                    section.classList.add('hidden');
                }
            }
        }

        function nextStep() {
            if (validateCurrentStep()) {
                currentStep++;
                updateProgressSteps();
            }
        }

        function previousStep() {
            currentStep--;
            updateProgressSteps();
        }

        function validateCurrentStep() {
            const requiredFields = document.querySelectorAll(`#step-${currentStep} [required]`);
            let isValid = true;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('border-red-500');
                    isValid = false;
                } else {
                    field.classList.remove('border-red-500');
                }
            });

            if (!isValid) {
                showNotification('Please fill in all required fields', 'warning');
            }

            return isValid;
        }

        function cancelCreation() {
            document.getElementById('content-form-container').classList.add('hidden');
            selectedContentType = null;
            currentStep = 1;

            // Reset content type selection
            document.querySelectorAll('.content-type-selector').forEach(el => {
                el.classList.remove('active');
            });
        }

        // Form submission handler
        document.addEventListener('submit', async function(e) {
            if (e.target.id === 'content-creation-form') {
                e.preventDefault();
                await submitContent();
            }
        });

        // Submit content creation
        async function submitContent() {
            if (!validateCurrentStep()) {
                return;
            }

            const submitBtn = document.getElementById('submit-btn');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating...';
            submitBtn.disabled = true;

            try {
                const contentData = collectFormData();
                const endpoint = getEndpointForContentType(selectedContentType);

                let result;
                if (selectedContentType === 'assignment' || selectedContentType === 'material') {
                    // Use FormData for file uploads
                    const formData = new FormData();
                    Object.keys(contentData).forEach(key => {
                        if (contentData[key] !== null && contentData[key] !== undefined) {
                            formData.append(key, contentData[key]);
                        }
                    });
                    result = await apiCall(endpoint, 'POST', formData, true);
                } else {
                    // Use JSON for other content types
                    result = await apiCall(endpoint, 'POST', contentData);
                }

                if (result && result.ok) {
                    showNotification(`✅ ${selectedContentType.charAt(0).toUpperCase() + selectedContentType.slice(1)} created successfully!`, 'success');

                    // Reset form and reload data
                    cancelCreation();
                    loadCourseStats(selectedCourse);
                    loadRecentContent();

                    // Send notification if enabled
                    if (document.getElementById('send-notification')?.checked) {
                        await sendNotificationToStudents(selectedContentType, contentData.title);
                    }
                } else {
                    showNotification(`❌ Failed to create ${selectedContentType}: ${result.data.message || 'Unknown error'}`, 'error');
                }
            } catch (error) {
                console.error('Error creating content:', error);
                showNotification(`❌ Error creating ${selectedContentType}`, 'error');
            } finally {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }
        }

        // Collect form data based on content type
        function collectFormData() {
            const data = {
                title: document.getElementById('content-title').value,
                description: document.getElementById('content-description').value,
                is_visible: document.getElementById('content-visible')?.checked || true
            };

            // Add content-specific fields
            switch (selectedContentType) {
                case 'lecture':
                    data.duration = parseInt(document.getElementById('content-duration').value) || 45;
                    data.video_url = document.getElementById('content-video-url').value || null;
                    data.objectives = document.getElementById('content-objectives').value || null;
                    data.content = document.getElementById('content-notes').value || null;
                    break;

                case 'assignment':
                    data.due_date = document.getElementById('content-due-date').value;
                    data.max_score = parseInt(document.getElementById('content-max-score').value) || 100;
                    data.instructions = document.getElementById('content-instructions').value;
                    data.allow_late_submission = document.getElementById('allow-late')?.checked || false;

                    const fileInput = document.getElementById('content-file');
                    if (fileInput.files[0]) {
                        data.file = fileInput.files[0];
                    }
                    break;

                case 'quiz':
                    data.due_date = document.getElementById('content-due-date').value;
                    data.max_score = parseInt(document.getElementById('content-max-score').value) || 100;
                    data.duration_minutes = parseInt(document.getElementById('quiz-duration').value) || 60;
                    data.max_attempts = document.getElementById('quiz-attempts').value;
                    data.instructions = document.getElementById('quiz-instructions').value || null;
                    data.start_time = data.due_date; // Use due date as start time for now
                    data.end_time = new Date(new Date(data.due_date).getTime() + 7*24*60*60*1000).toISOString(); // 7 days later
                    data.is_published = data.is_visible;
                    break;

                case 'lab':
                    data.due_date = document.getElementById('content-due-date').value;
                    data.max_score = parseInt(document.getElementById('content-max-score').value) || 100;
                    data.instructions = document.getElementById('lab-instructions').value;
                    data.equipment = document.getElementById('lab-equipment').value || null;
                    data.duration = parseInt(document.getElementById('lab-duration').value) || 120;
                    data.allow_late_submission = document.getElementById('allow-late')?.checked || false;
                    break;

                case 'material':
                    data.material_type = document.getElementById('material-type').value;

                    const materialFile = document.getElementById('material-file');
                    if (materialFile.files[0]) {
                        data.file = materialFile.files[0];
                    }
                    break;
            }

            return data;
        }

        // Get API endpoint for content type
        function getEndpointForContentType(type) {
            const endpoints = {
                lecture: `/instructor/courses/${selectedCourse}/lectures`,
                assignment: `/instructor/courses/${selectedCourse}/assignments`,
                quiz: `/instructor/courses/${selectedCourse}/quizzes`,
                lab: `/instructor/courses/${selectedCourse}/labs`,
                material: `/instructor/courses/${selectedCourse}/materials`
            };
            return endpoints[type];
        }

        // Send notification to students
        async function sendNotificationToStudents(contentType, title) {
            try {
                await apiCall('/instructor/send-notification', 'POST', {
                    course_id: selectedCourse,
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
        async function editContent(type, id) {
            showNotification('Opening edit interface...', 'info');
            // Redirect to appropriate edit page
            window.location.href = `/instructor/content/${type}/${id}/edit`;
        }

        async function deleteContent(type, id) {
            if (!confirm(`Are you sure you want to delete this ${type}?`)) {
                return;
            }

            try {
                const endpoint = `/instructor/${type}s/${id}`;
                const result = await apiCall(endpoint, 'DELETE');

                if (result && result.ok) {
                    showNotification(`✅ ${type.charAt(0).toUpperCase() + type.slice(1)} deleted successfully!`, 'success');
                    loadRecentContent();
                    loadCourseStats(selectedCourse);
                } else {
                    showNotification(`❌ Failed to delete ${type}`, 'error');
                }
            } catch (error) {
                console.error('Error deleting content:', error);
                showNotification(`❌ Error deleting ${type}`, 'error');
            }
        }

        // Refresh recent content
        function refreshRecentContent() {
            loadRecentContent();
            showNotification('📋 Content refreshed', 'info');
        }

        // Logout function
        function logout() {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            localStorage.removeItem('role');
            window.location.href = '/login';
        }
    </script>
