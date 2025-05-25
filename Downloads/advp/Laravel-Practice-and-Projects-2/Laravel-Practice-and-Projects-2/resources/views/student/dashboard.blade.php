<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - Educational Platform</title>
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
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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

        .progress-bar {
            transition: width 0.5s ease-in-out;
        }

        .notification-badge {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
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
                        <a href="/dashboard" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <a href="/courses" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-book mr-2"></i>Courses
                        </a>
                        <a href="/student/course-enrollment" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-plus-circle mr-2"></i>Enroll
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <a href="/notifications" class="bg-white p-1 rounded-full text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <i class="fas fa-bell text-xl"></i>
                            <span id="notification-badge" class="notification-badge absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full hidden"></span>
                        </a>
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

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Welcome Section -->
        <div class="gradient-bg rounded-xl shadow-xl p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold mb-2">Welcome back, <span id="welcome-name">Student</span>!</h2>
                    <p class="text-indigo-100 text-lg">Ready to continue your learning journey?</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-graduation-cap text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>
        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Enrolled Courses Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Enrolled Courses</p>
                        <p class="text-2xl font-bold text-gray-900" id="enrolled-courses-count">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Upcoming Assignments Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tasks text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Assignments</p>
                        <p class="text-2xl font-bold text-gray-900" id="upcoming-assignments-count">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Upcoming Quizzes Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-question-circle text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Quizzes</p>
                        <p class="text-2xl font-bold text-gray-900" id="upcoming-quizzes-count">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- GPA Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Current GPA</p>
                        <p class="text-2xl font-bold text-gray-900" id="current-gpa">
                            <span class="skeleton w-12 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Courses and Assignments -->
            <div class="lg:col-span-2 space-y-8">
                <!-- My Courses Section -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-graduation-cap text-indigo-600 mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">My Enrolled Courses</h3>
                            </div>
                            <a href="/courses" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                                View All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="my-courses-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Loading skeleton -->
                            <div class="bg-gray-100 rounded-lg p-4 animate-pulse">
                                <div class="h-32 bg-gray-200 rounded mb-4"></div>
                                <div class="h-4 bg-gray-200 rounded mb-2"></div>
                                <div class="h-3 bg-gray-200 rounded w-3/4"></div>
                            </div>
                            <div class="bg-gray-100 rounded-lg p-4 animate-pulse">
                                <div class="h-32 bg-gray-200 rounded mb-4"></div>
                                <div class="h-4 bg-gray-200 rounded mb-2"></div>
                                <div class="h-3 bg-gray-200 rounded w-3/4"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Assignments Section -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-tasks text-green-600 mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">Upcoming Assignments</h3>
                            </div>
                            <button class="text-green-600 hover:text-green-800 text-sm font-medium">
                                View All
                            </button>
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="upcoming-assignments">
                            <!-- Loading skeleton -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-4 animate-pulse">
                                    <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
                                    <div class="flex-1">
                                        <div class="h-4 bg-gray-200 rounded mb-2"></div>
                                        <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4 animate-pulse">
                                    <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
                                    <div class="flex-1">
                                        <div class="h-4 bg-gray-200 rounded mb-2"></div>
                                        <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Activity and Quick Actions -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Recent Activity -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <i class="fas fa-history text-purple-600 mr-3"></i>
                            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="recent-activity">
                            <!-- Loading skeleton -->
                            <div class="space-y-4">
                                <div class="flex items-start space-x-3 animate-pulse">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full"></div>
                                    <div class="flex-1">
                                        <div class="h-3 bg-gray-200 rounded mb-1"></div>
                                        <div class="h-2 bg-gray-200 rounded w-3/4"></div>
                                    </div>
                                </div>
                                <div class="flex items-start space-x-3 animate-pulse">
                                    <div class="w-8 h-8 bg-gray-200 rounded-full"></div>
                                    <div class="flex-1">
                                        <div class="h-3 bg-gray-200 rounded mb-1"></div>
                                        <div class="h-2 bg-gray-200 rounded w-3/4"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                    </div>
                    <div class="p-6 space-y-3">
                        <a href="/courses" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-search text-blue-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Browse Courses</p>
                                <p class="text-sm text-gray-500">Find new courses to enroll</p>
                            </div>
                        </a>
                        <a href="/student/course-enrollment" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-plus-circle text-green-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Enroll in Course</p>
                                <p class="text-sm text-gray-500">Join a new course</p>
                            </div>
                        </a>
                        <button onclick="refreshDashboard()" class="flex items-center p-3 rounded-lg hover:bg-gray-50 transition-colors w-full text-left">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                <i class="fas fa-sync-alt text-purple-600"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Refresh Dashboard</p>
                                <p class="text-sm text-gray-500">Update all data</p>
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let authToken = localStorage.getItem('token');

        // Initialize dashboard when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is authenticated
            if (!authToken) {
                // Try to get token from URL or redirect to login
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

            // Load user profile first
            loadUserProfile();

            // Load dashboard data
            loadDashboardData();

            // Load notification count
            updateNotificationCount();
        });

        // Utility function for API calls with authentication
        async function apiCall(endpoint, method = 'GET', data = null) {
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${authToken}`
            };

            const config = {
                method,
                headers
            };

            if (data) {
                config.body = JSON.stringify(data);
            }

            try {
                const response = await fetch(`/api${endpoint}`, config);

                if (response.status === 401) {
                    // Token expired, redirect to login
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                    return null;
                }

                const result = await response.json();
                return {
                    status: response.status,
                    ok: response.ok,
                    data: result
                };
            } catch (error) {
                console.error('API call error:', error);
                return {
                    status: 0,
                    ok: false,
                    error: error.message
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
                    document.getElementById('welcome-name').textContent = user.name;
                }
            } catch (error) {
                console.error('Error loading profile:', error);
            }
        }

        // Load all dashboard data
        async function loadDashboardData() {
            await Promise.all([
                loadEnrolledCourses(),
                loadUpcomingAssignments(),
                loadUpcomingQuizzes(),
                loadRecentActivity()
            ]);
        }

        // Load enrolled courses
        async function loadEnrolledCourses() {
            try {
                const result = await apiCall('/student/enrolled-courses');
                const coursesContainer = document.getElementById('my-courses-container');

                if (result && result.ok && result.data.status === 'success') {
                    const courses = result.data.data || [];

                    // Store courses data for GPA calculation
                    enrolledCoursesData = courses;

                    // Update enrolled courses count
                    document.getElementById('enrolled-courses-count').textContent = courses.length;

                    // Calculate and display realistic GPA
                    calculateRealisticGPA(courses);

                    // Also try to get GPA from API for more accurate calculation
                    loadStudentGPA();

                    if (courses.length > 0) {
                        // Display courses (limit to 4 for dashboard)
                        const coursesToShow = courses.slice(0, 4);

                        coursesContainer.innerHTML = coursesToShow.map(course => {
                            // Use real progress data from API instead of random values
                            const progressPercentage = course.progress ? course.progress.percentage : 0;
                            const progressColor = progressPercentage >= 75 ? 'bg-green-500' :
                                                 progressPercentage >= 50 ? 'bg-blue-500' :
                                                 progressPercentage >= 25 ? 'bg-yellow-500' : 'bg-red-500';

                            // Calculate completion status text
                            const completedItems = course.progress ? course.progress.completed_items : 0;
                            const totalItems = course.progress ? course.progress.total_items : 0;
                            const statusText = totalItems > 0 ? `${completedItems}/${totalItems} items completed` : 'No content available';

                            return `
                                <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                                    <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
                                        <div class="absolute top-2 right-2 bg-white bg-opacity-90 text-indigo-600 text-xs font-bold px-2 py-1 rounded">
                                            ${course.credit_hours || 3} Credits
                                        </div>
                                        <div class="absolute bottom-2 left-2 text-white">
                                            <i class="fas fa-graduation-cap text-2xl"></i>
                                        </div>
                                        <div class="absolute top-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                            ${course.progress ? course.progress.days_enrolled : 0} days enrolled
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h4 class="font-semibold text-gray-900 mb-1 truncate">${course.title}</h4>
                                        <p class="text-sm text-gray-600 mb-2 flex items-center">
                                            <i class="fas fa-tag mr-1 text-indigo-500"></i> ${course.code}
                                        </p>
                                        <p class="text-xs text-gray-500 mb-3">${statusText}</p>
                                        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
                                            <div class="${progressColor} h-2 rounded-full progress-bar" style="width: ${progressPercentage}%"></div>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs font-medium text-gray-600">
                                                ${progressPercentage}% Complete
                                            </span>
                                            <a href="/courses/${course.id}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                View →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    } else {
                        coursesContainer.innerHTML = `
                            <div class="col-span-2 bg-white rounded-lg shadow-md p-8 text-center">
                                <i class="fas fa-book-open text-gray-300 text-4xl mb-4"></i>
                                <h4 class="text-lg font-semibold text-gray-700 mb-2">No Courses Enrolled</h4>
                                <p class="text-gray-500 mb-4">Start your learning journey by enrolling in a course!</p>
                                <a href="/courses" class="inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-colors">
                                    Browse Courses
                                </a>
                            </div>
                        `;
                        document.getElementById('enrolled-courses-count').textContent = '0';
                    }
                } else {
                    throw new Error('Failed to load courses');
                }
            } catch (error) {
                console.error('Error loading courses:', error);
                document.getElementById('my-courses-container').innerHTML = `
                    <div class="col-span-2 bg-white rounded-lg shadow-md p-8 text-center">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-4xl mb-4"></i>
                        <h4 class="text-lg font-semibold text-gray-700 mb-2">Error Loading Courses</h4>
                        <p class="text-gray-500">Please try refreshing the page.</p>
                    </div>
                `;
                document.getElementById('enrolled-courses-count').textContent = '0';
            }
        }

        // Load upcoming assignments
        async function loadUpcomingAssignments() {
            try {
                const result = await apiCall('/student/upcoming-assignments');
                const assignmentsContainer = document.getElementById('upcoming-assignments');

                if (result && result.ok && result.data.status === 'success') {
                    const assignments = result.data.data || [];

                    // Update upcoming assignments count
                    document.getElementById('upcoming-assignments-count').textContent = assignments.length;

                    if (assignments.length > 0) {
                        assignmentsContainer.innerHTML = `
                            <div class="space-y-4">
                                ${assignments.slice(0, 5).map(assignment => {
                                    const dueDate = new Date(assignment.due_date);
                                    const isUrgent = isDateSoon(assignment.due_date);

                                    return `
                                        <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-50 transition-colors">
                                            <div class="flex-shrink-0">
                                                <div class="w-10 h-10 ${isUrgent ? 'bg-red-100' : 'bg-green-100'} rounded-full flex items-center justify-center">
                                                    <i class="fas fa-file-alt ${isUrgent ? 'text-red-600' : 'text-green-600'}"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-900 truncate">${assignment.title}</h4>
                                                <p class="text-xs text-gray-500 flex items-center mt-1">
                                                    <i class="fas fa-book mr-1"></i>
                                                    ${assignment.course ? assignment.course.title : 'Course'}
                                                </p>
                                            </div>
                                            <div class="text-right">
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium ${
                                                    isUrgent ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800'
                                                }">
                                                    ${formatDate(assignment.due_date)}
                                                </span>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    ${isUrgent ? 'Due soon!' : 'Upcoming'}
                                                </p>
                                            </div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        `;
                    } else {
                        assignmentsContainer.innerHTML = `
                            <div class="text-center py-8">
                                <i class="fas fa-check-circle text-green-400 text-3xl mb-3"></i>
                                <h4 class="text-sm font-medium text-gray-700 mb-1">All caught up!</h4>
                                <p class="text-xs text-gray-500">No upcoming assignments</p>
                            </div>
                        `;
                        document.getElementById('upcoming-assignments-count').textContent = '0';
                    }
                } else {
                    throw new Error('Failed to load assignments');
                }
            } catch (error) {
                console.error('Error loading assignments:', error);
                document.getElementById('upcoming-assignments').innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-3xl mb-3"></i>
                        <p class="text-sm text-gray-600">Error loading assignments</p>
                    </div>
                `;
                document.getElementById('upcoming-assignments-count').textContent = '0';
            }
        }

        // Load upcoming quizzes
        async function loadUpcomingQuizzes() {
            try {
                const result = await apiCall('/student/upcoming-quizzes');

                if (result && result.ok && result.data.status === 'success') {
                    const quizzes = result.data.data || [];
                    document.getElementById('upcoming-quizzes-count').textContent = quizzes.length;
                } else {
                    document.getElementById('upcoming-quizzes-count').textContent = '0';
                }
            } catch (error) {
                console.error('Error loading quizzes:', error);
                document.getElementById('upcoming-quizzes-count').textContent = '0';
            }
        }

        // Load recent activity
        async function loadRecentActivity() {
            try {
                const result = await apiCall('/student/recent-activity');
                const activityContainer = document.getElementById('recent-activity');

                if (result && result.ok && result.data.status === 'success') {
                    const activities = result.data.data || [];

                    if (activities.length > 0) {
                        activityContainer.innerHTML = `
                            <div class="space-y-4">
                                ${activities.slice(0, 5).map(activity => {
                                    const activityIcon = getActivityIcon(activity.type);
                                    const activityColor = getActivityColor(activity.type);

                                    return `
                                        <div class="flex items-start space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-8 h-8 ${activityColor} rounded-full flex items-center justify-center">
                                                    <i class="fas ${activityIcon} text-white text-xs"></i>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900">${activity.description}</p>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    ${activity.course_title || 'General Activity'}
                                                </p>
                                                <p class="text-xs text-gray-400 mt-1">
                                                    ${formatTimeAgo(activity.timestamp)}
                                                </p>
                                            </div>
                                        </div>
                                    `;
                                }).join('')}
                            </div>
                        `;
                    } else {
                        activityContainer.innerHTML = `
                            <div class="text-center py-8">
                                <i class="fas fa-history text-gray-300 text-3xl mb-3"></i>
                                <h4 class="text-sm font-medium text-gray-700 mb-1">No recent activity</h4>
                                <p class="text-xs text-gray-500">Your activity will appear here</p>
                            </div>
                        `;
                    }
                } else {
                    throw new Error('Failed to load activity');
                }
            } catch (error) {
                console.error('Error loading activity:', error);
                document.getElementById('recent-activity').innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-3xl mb-3"></i>
                        <p class="text-sm text-gray-600">Error loading activity</p>
                    </div>
                `;
            }
        }

        // Helper functions
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
        }

        function isDateSoon(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffTime = date - now;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            return diffDays <= 3 && diffDays >= 0;
        }

        function getActivityIcon(type) {
            switch(type) {
                case 'enrollment': return 'fa-user-plus';
                case 'assignment': return 'fa-tasks';
                case 'quiz': return 'fa-question-circle';
                case 'lecture': return 'fa-book';
                default: return 'fa-bell';
            }
        }

        function getActivityColor(type) {
            switch(type) {
                case 'enrollment': return 'bg-blue-500';
                case 'assignment': return 'bg-green-500';
                case 'quiz': return 'bg-purple-500';
                case 'lecture': return 'bg-yellow-500';
                default: return 'bg-gray-500';
            }
        }

        function formatTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffTime = now - date;
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays === 0) {
                const diffHours = Math.floor(diffTime / (1000 * 60 * 60));
                if (diffHours === 0) {
                    const diffMinutes = Math.floor(diffTime / (1000 * 60));
                    return `${diffMinutes} minute${diffMinutes !== 1 ? 's' : ''} ago`;
                }
                return `${diffHours} hour${diffHours !== 1 ? 's' : ''} ago`;
            } else if (diffDays === 1) {
                return 'Yesterday';
            } else if (diffDays < 7) {
                return `${diffDays} day${diffDays !== 1 ? 's' : ''} ago`;
            } else {
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            }
        }

        // Utility functions
        function refreshDashboard() {
            // Show loading state
            document.getElementById('enrolled-courses-count').innerHTML = '<span class="skeleton w-8 h-6 rounded inline-block"></span>';
            document.getElementById('upcoming-assignments-count').innerHTML = '<span class="skeleton w-8 h-6 rounded inline-block"></span>';
            document.getElementById('upcoming-quizzes-count').innerHTML = '<span class="skeleton w-8 h-6 rounded inline-block"></span>';
            document.getElementById('current-gpa').innerHTML = '<span class="skeleton w-12 h-6 rounded inline-block"></span>';

            // Reload all data
            loadDashboardData();
        }

        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                // Call logout API
                apiCall('/logout', 'POST').then(() => {
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                }).catch(() => {
                    // Even if API fails, remove token and redirect
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                });
            }
        }

        // Calculate and display realistic GPA based on course progress
        function calculateRealisticGPA(courses) {
            if (!courses || courses.length === 0) {
                document.getElementById('current-gpa').textContent = 'N/A';
                return;
            }

            // Calculate GPA based on course progress and credit hours
            let totalGradePoints = 0;
            let totalCreditHours = 0;
            let hasCompletedCourses = false;

            courses.forEach(course => {
                const creditHours = course.credit_hours || 3;
                const progress = course.progress ? course.progress.percentage : 0;

                // Only include courses that have significant progress (at least 60% to get a grade)
                if (progress >= 60) {
                    hasCompletedCourses = true;

                    // Convert progress percentage to GPA scale (0-4.0)
                    // 90-100% = 4.0 (A), 80-89% = 3.0 (B), 70-79% = 2.0 (C), 60-69% = 1.0 (D)
                    let gradePoint;
                    if (progress >= 90) gradePoint = 4.0;
                    else if (progress >= 80) gradePoint = 3.0;
                    else if (progress >= 70) gradePoint = 2.0;
                    else gradePoint = 1.0;

                    totalGradePoints += gradePoint * creditHours;
                    totalCreditHours += creditHours;
                }
            });

            // If no courses have been completed (60%+ progress), show N/A
            if (!hasCompletedCourses || totalCreditHours === 0) {
                document.getElementById('current-gpa').textContent = 'N/A';
                return;
            }

            const gpa = (totalGradePoints / totalCreditHours).toFixed(2);
            document.getElementById('current-gpa').textContent = gpa;
        }

        // Load student GPA from API
        async function loadStudentGPA() {
            try {
                const result = await apiCall('/student/gpa');

                if (result && result.ok && result.data.status === 'success') {
                    const gpaData = result.data.data;

                    if (gpaData.gpa === null) {
                        document.getElementById('current-gpa').textContent = 'N/A';

                        // Add tooltip or additional info
                        const gpaElement = document.getElementById('current-gpa');
                        gpaElement.title = gpaData.message || 'No completed courses yet';
                    } else {
                        document.getElementById('current-gpa').textContent = gpaData.gpa.toFixed(2);

                        // Add tooltip with additional info
                        const gpaElement = document.getElementById('current-gpa');
                        gpaElement.title = `Based on ${gpaData.completed_courses || 0} completed courses`;
                    }
                }
            } catch (error) {
                console.error('Error loading GPA:', error);
                // Keep the frontend calculation as fallback
            }
        }

        // Store courses data globally for GPA calculation
        let enrolledCoursesData = [];

        // Update notification count
        async function updateNotificationCount() {
            try {
                const result = await apiCall('/notifications/unread-count');
                if (result && result.ok && result.data.status === 'success') {
                    const count = result.data.data.count;
                    const badge = document.getElementById('notification-badge');

                    if (count > 0) {
                        badge.textContent = count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('Error updating notification count:', error);
            }
        }

        // Auto-refresh notification count every 30 seconds
        setInterval(updateNotificationCount, 30000);
    </script>
</body>
</html>
