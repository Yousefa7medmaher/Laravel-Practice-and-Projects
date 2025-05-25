<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard - Educational Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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

        .manager-card {
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

        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .tab-button {
            transition: all 0.3s ease;
        }

        .tab-button.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
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
                            <i class="fas fa-user-tie mr-2"></i>
                            EduPlatform Manager
                        </h1>
                    </div>
                    <div class="hidden md:ml-6 md:flex md:space-x-8">
                        <a href="/manager/dashboard" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <a href="/manager/courses" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-book mr-2"></i>Courses
                        </a>
                        <a href="/manager/students" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-user-graduate mr-2"></i>Students
                        </a>
                        <a href="/manager/instructors" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>Instructors
                        </a>
                        <a href="/manager/reports" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-chart-line mr-2"></i>Reports
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
                            <i class="fas fa-user-tie text-white text-sm"></i>
                        </div>
                        <div class="hidden md:block">
                            <div class="text-sm font-medium text-gray-700" id="user-name">Loading...</div>
                            <div class="text-xs text-gray-500">Manager</div>
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
                    <h2 class="text-3xl font-bold mb-2">Manager Dashboard</h2>
                    <p class="text-indigo-100 text-lg">Comprehensive platform oversight and management</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-chart-line text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="manager-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Courses</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-courses">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="manager-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-graduate text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Students</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-students">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="manager-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Instructors</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-instructors">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="manager-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tasks text-orange-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Assignments</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-assignments">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Tabs -->
        <div class="manager-card rounded-xl shadow-lg overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6">
                    <button onclick="switchTab('overview')" id="overview-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm">
                        <i class="fas fa-chart-pie mr-2"></i>Overview
                    </button>
                    <button onclick="switchTab('courses')" id="courses-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm">
                        <i class="fas fa-book mr-2"></i>Course Stats
                    </button>
                    <button onclick="switchTab('activity')" id="activity-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm">
                        <i class="fas fa-history mr-2"></i>Recent Activity
                    </button>
                    <button onclick="switchTab('performance')" id="performance-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm">
                        <i class="fas fa-star mr-2"></i>Performance
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Overview Tab -->
                <div id="overview-content" class="hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Submission Status Chart -->
                        <div class="bg-white rounded-lg p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Submission Status</h3>
                            <div class="h-64">
                                <canvas id="submissionChart"></canvas>
                            </div>
                        </div>

                        <!-- Course Distribution Chart -->
                        <div class="bg-white rounded-lg p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Enrollment</h3>
                            <div class="h-64">
                                <canvas id="enrollmentChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Courses Tab -->
                <div id="courses-content" class="hidden">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">Course Management</h3>
                        <div class="flex space-x-3">
                            <button onclick="showCreateCourseModal()" class="action-button text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all">
                                <i class="fas fa-plus mr-2"></i>Create Course
                            </button>
                            <button onclick="refreshCourses()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-all">
                                <i class="fas fa-sync-alt mr-2"></i>Refresh
                            </button>
                        </div>
                    </div>
                    <div id="courses-list" class="space-y-4">
                        <!-- Courses will be loaded here -->
                    </div>
                </div>

                <!-- Activity Tab -->
                <div id="activity-content" class="hidden">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent Platform Activity</h3>
                    <div id="activity-list" class="space-y-4">
                        <!-- Activity will be loaded here -->
                    </div>
                </div>

                <!-- Performance Tab -->
                <div id="performance-content" class="hidden">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <!-- Top Students -->
                        <div class="bg-white rounded-lg p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Performing Students</h3>
                            <div id="top-students-list" class="space-y-3">
                                <!-- Top students will be loaded here -->
                            </div>
                        </div>

                        <!-- Instructor Performance -->
                        <div class="bg-white rounded-lg p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Instructor Performance</h3>
                            <div id="instructor-performance-list" class="space-y-3">
                                <!-- Instructor performance will be loaded here -->
                            </div>
                        </div>

                        <!-- Notification Testing -->
                        <div class="bg-white rounded-lg p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Notification Testing</h3>
                            <div class="space-y-3">
                                <button onclick="testNotifications()" class="w-full action-button text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all">
                                    <i class="fas fa-bell mr-2"></i>Test Notifications
                                </button>
                                <button onclick="getNotificationStats()" class="w-full bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-all">
                                    <i class="fas fa-chart-bar mr-2"></i>Notification Stats
                                </button>
                                <div id="notification-test-results" class="text-xs text-gray-600 mt-3">
                                    <!-- Test results will appear here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let authToken = localStorage.getItem('token');
        let dashboardData = {};
        let submissionChart = null;
        let enrollmentChart = null;

        // Initialize page when loaded
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken) {
                window.location.href = '/login';
                return;
            }

            loadUserProfile();
            loadDashboardData();
            switchTab('overview'); // Default tab
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
                    if (user.role !== 'manager') {
                        alert('Access denied. This page is for managers only.');
                        window.location.href = '/dashboard';
                        return;
                    }
                    document.getElementById('user-name').textContent = user.name;
                }
            } catch (error) {
                console.error('Error loading profile:', error);
            }
        }

        // Load dashboard data
        async function loadDashboardData() {
            try {
                const result = await apiCall('/manager/dashboard-data');
                if (result && result.ok) {
                    dashboardData = result.data.data;
                    updateStats(dashboardData.stats);
                    updateCharts();
                    updateCoursesList(dashboardData.course_stats);
                    updateActivityList(dashboardData.recent_activity);
                    updatePerformanceData(dashboardData.top_students, dashboardData.instructor_performance);
                } else {
                    console.error('Failed to load dashboard data:', result);
                }
            } catch (error) {
                console.error('Error loading dashboard data:', error);
            }
        }

        // Update statistics
        function updateStats(stats) {
            document.getElementById('total-courses').textContent = stats.total_courses || 0;
            document.getElementById('total-students').textContent = stats.total_students || 0;
            document.getElementById('total-instructors').textContent = stats.total_instructors || 0;
            document.getElementById('total-assignments').textContent = stats.total_assignments || 0;
        }

        // Switch tabs
        function switchTab(tabName) {
            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'border-indigo-500', 'text-white');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            const activeTab = document.getElementById(`${tabName}-tab`);
            if (activeTab) {
                activeTab.classList.add('active', 'border-indigo-500', 'text-white');
                activeTab.classList.remove('border-transparent', 'text-gray-500');
            }

            // Show/hide content
            ['overview', 'courses', 'activity', 'performance'].forEach(tab => {
                const content = document.getElementById(`${tab}-content`);
                if (tab === tabName) {
                    content.classList.remove('hidden');
                } else {
                    content.classList.add('hidden');
                }
            });
        }

        // Update charts
        function updateCharts() {
            if (!dashboardData.stats) return;

            // Submission Status Chart
            const submissionCtx = document.getElementById('submissionChart').getContext('2d');
            if (submissionChart) submissionChart.destroy();

            submissionChart = new Chart(submissionCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Graded', 'Pending', 'Not Submitted'],
                    datasets: [{
                        data: [
                            dashboardData.stats.graded_submissions || 0,
                            dashboardData.stats.pending_submissions || 0,
                            (dashboardData.stats.total_assignments * dashboardData.stats.total_students) - dashboardData.stats.total_submissions || 0
                        ],
                        backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });

            // Enrollment Chart
            const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
            if (enrollmentChart) enrollmentChart.destroy();

            const courseLabels = dashboardData.course_stats ? dashboardData.course_stats.slice(0, 5).map(course => course.code) : [];
            const enrollmentData = dashboardData.course_stats ? dashboardData.course_stats.slice(0, 5).map(course => course.students_count || course.enrolled_students_count || 0) : [];

            enrollmentChart = new Chart(enrollmentCtx, {
                type: 'bar',
                data: {
                    labels: courseLabels,
                    datasets: [{
                        label: 'Enrolled Students',
                        data: enrollmentData,
                        backgroundColor: '#667eea',
                        borderColor: '#667eea',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Update courses list
        function updateCoursesList(courses) {
            const container = document.getElementById('courses-list');

            if (!courses || courses.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-book text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No courses created yet</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = courses.map(course => `
                <div class="bg-white rounded-lg border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-4">
                                <div>
                                    <h4 class="font-semibold text-gray-900">${course.title}</h4>
                                    <p class="text-sm text-gray-600">${course.code} • ${course.credit_hours || 3} Credits</p>
                                    <p class="text-sm text-gray-500 mt-1">${course.description || 'No description'}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">${course.students_count || course.enrolled_students_count || 0} Students</p>
                                    <p class="text-xs text-gray-500">Instructor: ${course.instructor?.name || 'Not assigned'}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-6 mt-4 text-xs text-gray-500">
                                <span><i class="fas fa-video mr-1"></i> ${course.lectures_count || 0} lectures</span>
                                <span><i class="fas fa-tasks mr-1"></i> ${course.assignments_count || 0} assignments</span>
                                <span><i class="fas fa-question-circle mr-1"></i> ${course.quizzes_count || 0} quizzes</span>
                                <span><i class="fas fa-flask mr-1"></i> ${course.labs_count || 0} labs</span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editCourse(${course.id})" class="text-indigo-600 hover:text-indigo-800 text-sm">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="assignInstructor(${course.id})" class="text-green-600 hover:text-green-800 text-sm">
                                <i class="fas fa-user-plus"></i>
                            </button>
                            <button onclick="deleteCourse(${course.id})" class="text-red-600 hover:text-red-800 text-sm">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Update activity list
        function updateActivityList(activities) {
            const container = document.getElementById('activity-list');

            if (!activities || activities.length === 0) {
                container.innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-history text-4xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No recent activity</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = activities.map(activity => `
                <div class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-sm transition-shadow">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-indigo-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                ${activity.user?.name || 'Unknown User'}
                                <span class="font-normal text-gray-600">${getActivityDescription(activity.action)}</span>
                            </p>
                            <p class="text-xs text-gray-500">${formatDate(activity.created_at)} • ${activity.user?.role || 'Unknown Role'}</p>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Update performance data
        function updatePerformanceData(topStudents, instructorPerformance) {
            // Top Students
            const studentsContainer = document.getElementById('top-students-list');
            if (topStudents && topStudents.length > 0) {
                studentsContainer.innerHTML = topStudents.map((student, index) => `
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-bold text-indigo-600">${index + 1}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">${student.name}</p>
                                <p class="text-xs text-gray-500">${student.email}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">${(student.avg_grade || 0).toFixed(1)}%</p>
                            <p class="text-xs text-gray-500">Enrolled in ${student.total_courses || 0} courses</p>
                        </div>
                    </div>
                `).join('');
            } else {
                studentsContainer.innerHTML = '<p class="text-gray-500 text-center py-4">No student data available</p>';
            }

            // Instructor Performance
            const instructorsContainer = document.getElementById('instructor-performance-list');
            if (instructorPerformance && instructorPerformance.length > 0) {
                instructorsContainer.innerHTML = instructorPerformance.map(instructor => `
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                        <div>
                            <p class="font-medium text-gray-900">${instructor.name}</p>
                            <p class="text-xs text-gray-500">${instructor.email}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-gray-900">${instructor.courses_assigned_count || 0} Courses</p>
                            <p class="text-xs text-gray-500">${instructor.total_graded_count || 0} Graded</p>
                        </div>
                    </div>
                `).join('');
            } else {
                instructorsContainer.innerHTML = '<p class="text-gray-500 text-center py-4">No instructor data available</p>';
            }
        }

        // Helper functions
        function getActivityDescription(action) {
            const descriptions = {
                'login': 'logged in',
                'logout': 'logged out',
                'grade_submission': 'graded a submission',
                'create_course': 'created a course',
                'create_lecture': 'created a lecture',
                'create_assignment': 'created an assignment',
                'submit_assignment': 'submitted an assignment'
            };
            return descriptions[action] || action;
        }

        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        }

        // Course management functions
        function showCreateCourseModal() {
            // Redirect to course management page
            window.location.href = '/manager/courses';
        }

        function editCourse(courseId) {
            // Redirect to course management page with edit mode
            window.location.href = `/manager/courses?edit=${courseId}`;
        }

        function assignInstructor(courseId) {
            // Redirect to course management page with assign mode
            window.location.href = `/manager/courses?assign=${courseId}`;
        }

        async function deleteCourse(courseId) {
            if (confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
                try {
                    const result = await apiCall(`/courses/${courseId}`, 'DELETE');
                    if (result && result.ok) {
                        alert('Course deleted successfully!');
                        loadDashboardData(); // Refresh data
                    } else {
                        alert(result?.data?.message || 'Failed to delete course');
                    }
                } catch (error) {
                    console.error('Error deleting course:', error);
                    alert('Error deleting course. Please try again.');
                }
            }
        }

        function refreshCourses() {
            loadDashboardData();
        }

        // Notification testing functions
        async function testNotifications() {
            const resultsDiv = document.getElementById('notification-test-results');
            resultsDiv.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Creating test notifications...';

            try {
                const result = await apiCall('/test/notifications/create', 'POST');
                if (result && result.ok) {
                    resultsDiv.innerHTML = `
                        <div class="text-green-600">
                            <i class="fas fa-check mr-2"></i>Test notifications created successfully!
                            <br><small>Check the notification pages for managers and instructors.</small>
                        </div>
                    `;
                } else {
                    resultsDiv.innerHTML = `
                        <div class="text-red-600">
                            <i class="fas fa-times mr-2"></i>Failed to create test notifications
                            <br><small>${result?.data?.message || 'Unknown error'}</small>
                        </div>
                    `;
                }
            } catch (error) {
                resultsDiv.innerHTML = `
                    <div class="text-red-600">
                        <i class="fas fa-times mr-2"></i>Error: ${error.message}
                    </div>
                `;
            }
        }

        async function getNotificationStats() {
            const resultsDiv = document.getElementById('notification-test-results');
            resultsDiv.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Loading notification statistics...';

            try {
                const result = await apiCall('/test/notifications/stats', 'GET');
                if (result && result.ok) {
                    const stats = result.data;
                    resultsDiv.innerHTML = `
                        <div class="text-blue-600">
                            <i class="fas fa-chart-bar mr-2"></i>Notification Statistics:
                            <br><small>Total: ${stats.total_notifications}</small>
                            <br><small>Unread: ${stats.unread_notifications}</small>
                            <br><small>By Role: ${JSON.stringify(stats.notifications_by_role)}</small>
                        </div>
                    `;
                } else {
                    resultsDiv.innerHTML = `
                        <div class="text-red-600">
                            <i class="fas fa-times mr-2"></i>Failed to load statistics
                        </div>
                    `;
                }
            } catch (error) {
                resultsDiv.innerHTML = `
                    <div class="text-red-600">
                        <i class="fas fa-times mr-2"></i>Error: ${error.message}
                    </div>
                `;
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
