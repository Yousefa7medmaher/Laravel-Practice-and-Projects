<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard - Educational Platform</title>
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
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .instructor-gradient {
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

        .stat-card {
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

        .chart-container {
            position: relative;
            height: 300px;
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
                        <a href="/instructor/dashboard" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <a href="/instructor/courses" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-book mr-2"></i>My Courses
                        </a>
                        <a href="/instructor/content" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                            <span id="notification-badge" class="notification-badge absolute -top-1 -right-1 h-3 w-3 bg-red-500 rounded-full hidden"></span>
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
        <!-- Welcome Section -->
        <div class="instructor-gradient rounded-xl shadow-xl p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold mb-2">Welcome back, <span id="welcome-name">Instructor</span>!</h2>
                    <p class="text-indigo-100 text-lg">Ready to inspire and educate your students?</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-chalkboard-teacher text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Courses Card -->
            <div class="stat-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">My Courses</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-courses-count">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Total Students Card -->
            <div class="stat-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Students</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-students-count">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Pending Submissions Card -->
            <div class="stat-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-orange-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending Grading</p>
                        <p class="text-2xl font-bold text-gray-900" id="pending-submissions-count">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Average Grade Card -->
            <div class="stat-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Avg. Grade</p>
                        <p class="text-2xl font-bold text-gray-900" id="average-grade">
                            <span class="skeleton w-12 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="mb-8">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <button onclick="showCreateLectureModal()" class="action-button text-white p-4 rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center">
                        <i class="fas fa-video text-2xl mr-3"></i>
                        <div class="text-left">
                            <div class="font-semibold">Create Lecture</div>
                            <div class="text-sm opacity-90">Add new video content</div>
                        </div>
                    </div>
                </button>

                <button onclick="showCreateQuizModal()" class="action-button text-white p-4 rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center">
                        <i class="fas fa-question-circle text-2xl mr-3"></i>
                        <div class="text-left">
                            <div class="font-semibold">Create Quiz</div>
                            <div class="text-sm opacity-90">Add assessment</div>
                        </div>
                    </div>
                </button>

                <button onclick="showCreateAssignmentModal()" class="action-button text-white p-4 rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center">
                        <i class="fas fa-tasks text-2xl mr-3"></i>
                        <div class="text-left">
                            <div class="font-semibold">Create Assignment</div>
                            <div class="text-sm opacity-90">Add homework</div>
                        </div>
                    </div>
                </button>

                <button onclick="window.location.href='/instructor/grading'" class="action-button text-white p-4 rounded-xl shadow-lg hover:shadow-xl transition-all">
                    <div class="flex items-center">
                        <i class="fas fa-star text-2xl mr-3"></i>
                        <div class="text-left">
                            <div class="font-semibold">Grade Work</div>
                            <div class="text-sm opacity-90">Review submissions</div>
                        </div>
                    </div>
                </button>
            </div>
        </div>

        <!-- Main Dashboard Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Courses and Recent Activity -->
            <div class="lg:col-span-2 space-y-8">
                <!-- My Courses Section -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-book text-indigo-600 mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">My Courses</h3>
                            </div>
                            <a href="/instructor/courses" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                                Manage All <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="instructor-courses-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

                <!-- Recent Submissions Section -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-clock text-orange-600 mr-3"></i>
                                <h3 class="text-lg font-semibold text-gray-900">Recent Submissions</h3>
                            </div>
                            <a href="/instructor/grading" class="text-orange-600 hover:text-orange-800 text-sm font-medium">
                                View All
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="recent-submissions">
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

            <!-- Right Column - Analytics and Student Activity -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Grade Distribution Chart -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <i class="fas fa-chart-pie text-purple-600 mr-3"></i>
                            <h3 class="text-lg font-semibold text-gray-900">Grade Distribution</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="chart-container">
                            <canvas id="gradeChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Student Activity -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center">
                            <i class="fas fa-users text-green-600 mr-3"></i>
                            <h3 class="text-lg font-semibold text-gray-900">Student Activity</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        <div id="student-activity">
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
            </div>
        </div>
    </div>

    <!-- Create Content Modals -->
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                        <select id="lectureCoursesSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select a course...</option>
                        </select>
                    </div>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                        <select id="quizCoursesSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select a course...</option>
                        </select>
                    </div>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Course</label>
                        <select id="assignmentCoursesSelect" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Select a course...</option>
                        </select>
                    </div>
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
                        <label class="block text-sm font-medium text-gray-700 mb-1">Max Score</label>
                        <input type="number" id="assignmentMaxScore" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
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

    <!-- JavaScript -->
    <script>
        // Global variables
        let authToken = localStorage.getItem('token');
        let instructorCourses = [];
        let gradeChart = null;

        // Initialize dashboard when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is authenticated and is an instructor
            if (!authToken) {
                window.location.href = '/login';
                return;
            }

            // Load user profile first
            loadUserProfile();

            // Load dashboard data
            loadInstructorDashboardData();

            // Load notification count
            updateNotificationCount();

            // Setup form handlers
            setupFormHandlers();
        });

        // Utility function for API calls with authentication and token rotation handling
        async function apiCall(endpoint, method = 'GET', data = null) {
            // Get fresh token from localStorage (in case it was updated)
            const currentToken = localStorage.getItem('token');

            if (!currentToken) {
                console.error('No authentication token found');
                window.location.href = '/login';
                return null;
            }

            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${currentToken}`
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
                    // Token is invalid or expired
                    console.warn('Token invalid or expired, clearing auth data and redirecting to login');

                    // Clear all authentication data
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    localStorage.removeItem('role');
                    localStorage.removeItem('token_created_at');

                    // Clear cookies
                    document.cookie = 'token=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
                    document.cookie = 'XSRF-TOKEN=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';

                    window.location.href = '/login';
                    return null;
                }

                const result = await response.json();

                // Log successful API calls for debugging
                if (endpoint !== '/notifications/unread-count') { // Don't log frequent notification checks
                    console.log(`API call successful: ${method} ${endpoint}`, {
                        status: response.status,
                        token_prefix: currentToken.substring(0, 10) + '...'
                    });
                }

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

        // Load user profile and verify instructor role
        async function loadUserProfile() {
            try {
                const result = await apiCall('/profile');
                if (result && result.ok) {
                    const user = result.data.user;

                    // Check if user is instructor or manager
                    if (!['instructor', 'manager'].includes(user.role)) {
                        alert('Access denied. This page is for instructors only.');
                        window.location.href = '/dashboard';
                        return;
                    }

                    document.getElementById('user-name').textContent = user.name;
                    document.getElementById('welcome-name').textContent = user.name;
                }
            } catch (error) {
                console.error('Error loading profile:', error);
            }
        }

        // Load all instructor dashboard data
        async function loadInstructorDashboardData() {
            await Promise.all([
                loadInstructorCourses(),
                loadInstructorStats(),
                loadRecentSubmissions(),
                loadStudentActivity(),
                loadGradeDistribution()
            ]);
        }

        // Load instructor courses (only assigned courses)
        async function loadInstructorCourses() {
            try {
                const result = await apiCall('/instructor/assigned-courses');
                const coursesContainer = document.getElementById('instructor-courses-container');

                if (result && result.ok && result.data.status === 'success') {
                    const courses = result.data.data || [];
                    instructorCourses = courses;

                    // Update total courses count
                    document.getElementById('total-courses-count').textContent = courses.length;

                    // Populate course selects in modals
                    populateCourseSelects(courses);

                    if (courses.length > 0) {
                        // Display courses (limit to 4 for dashboard)
                        const coursesToShow = courses.slice(0, 4);

                        coursesContainer.innerHTML = coursesToShow.map(course => {
                            const enrollmentCount = course.enrolled_students_count || 0;
                            const contentCount = (course.lectures_count || 0) + (course.assignments_count || 0) + (course.quizzes_count || 0);

                            return `
                                <div class="bg-white rounded-lg shadow-md overflow-hidden card-hover">
                                    <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
                                        <div class="absolute top-2 right-2 bg-white bg-opacity-90 text-indigo-600 text-xs font-bold px-2 py-1 rounded">
                                            ${course.credit_hours || 3} Credits
                                        </div>
                                        <div class="absolute bottom-2 left-2 text-white">
                                            <i class="fas fa-chalkboard-teacher text-2xl"></i>
                                        </div>
                                        <div class="absolute top-2 left-2 bg-black bg-opacity-50 text-white text-xs px-2 py-1 rounded">
                                            ${enrollmentCount} students
                                        </div>
                                    </div>
                                    <div class="p-4">
                                        <h4 class="font-semibold text-gray-900 mb-1 truncate">${course.title}</h4>
                                        <p class="text-sm text-gray-600 mb-2 flex items-center">
                                            <i class="fas fa-tag mr-1 text-indigo-500"></i> ${course.code}
                                        </p>
                                        <p class="text-xs text-gray-500 mb-3">${contentCount} content items</p>
                                        <div class="flex justify-between items-center">
                                            <span class="text-xs font-medium text-gray-600">
                                                ${enrollmentCount} enrolled
                                            </span>
                                            <a href="/instructor/courses/${course.id}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">
                                                Manage →
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }).join('');
                    } else {
                        coursesContainer.innerHTML = `
                            <div class="col-span-2 text-center py-8">
                                <i class="fas fa-book text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">No courses found. Create your first course to get started!</p>
                            </div>
                        `;
                    }
                } else {
                    // Check if it's an authentication error
                    if (result && result.status === 401) {
                        coursesContainer.innerHTML = `
                            <div class="col-span-2 text-center py-8">
                                <i class="fas fa-lock text-4xl text-red-300 mb-4"></i>
                                <p class="text-red-500">Authentication required</p>
                                <button onclick="window.location.href='/login'" class="mt-2 px-4 py-2 bg-indigo-600 text-white rounded-lg">
                                    Login Again
                                </button>
                            </div>
                        `;
                    } else {
                        coursesContainer.innerHTML = `
                            <div class="col-span-2 text-center py-8">
                                <i class="fas fa-chalkboard-teacher text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">No courses assigned yet</p>
                                <p class="text-sm text-gray-400 mt-2">Contact your manager to get courses assigned to you</p>
                                <button onclick="location.reload()" class="mt-3 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                                    Refresh
                                </button>
                            </div>
                        `;
                    }
                }
            } catch (error) {
                console.error('Error loading instructor courses:', error);
            }
        }

        // Load instructor statistics
        async function loadInstructorStats() {
            try {
                // Load total students across all courses
                let totalStudents = 0;
                let pendingSubmissions = 0;
                let totalGrades = [];

                for (const course of instructorCourses) {
                    // Get course students
                    const studentsResult = await apiCall(`/instructor/courses/${course.id}/students`);
                    if (studentsResult && studentsResult.ok) {
                        totalStudents += studentsResult.data.data?.length || 0;
                    }

                    // Get course analytics for grades
                    const analyticsResult = await apiCall(`/instructor/courses/${course.id}/analytics`);
                    if (analyticsResult && analyticsResult.ok) {
                        const analytics = analyticsResult.data.data;
                        if (analytics.pending_submissions) {
                            pendingSubmissions += analytics.pending_submissions;
                        }
                        if (analytics.average_grade) {
                            totalGrades.push(analytics.average_grade);
                        }
                    }
                }

                // Update stats
                document.getElementById('total-students-count').textContent = totalStudents;
                document.getElementById('pending-submissions-count').textContent = pendingSubmissions;

                // Calculate average grade
                const avgGrade = totalGrades.length > 0
                    ? (totalGrades.reduce((a, b) => a + b, 0) / totalGrades.length).toFixed(1)
                    : 'N/A';
                document.getElementById('average-grade').textContent = avgGrade + (avgGrade !== 'N/A' ? '%' : '');

            } catch (error) {
                console.error('Error loading instructor stats:', error);
                // Set fallback values
                document.getElementById('total-students-count').textContent = '0';
                document.getElementById('pending-submissions-count').textContent = '0';
                document.getElementById('average-grade').textContent = 'N/A';
            }
        }

        // Load recent submissions
        async function loadRecentSubmissions() {
            try {
                const submissionsContainer = document.getElementById('recent-submissions');
                let allSubmissions = [];

                // Get submissions from all courses
                for (const course of instructorCourses) {
                    // Get course assignments
                    const assignmentsResult = await apiCall(`/courses/${course.id}/assignments`);
                    if (assignmentsResult && assignmentsResult.ok) {
                        const assignments = assignmentsResult.data.data || [];

                        for (const assignment of assignments) {
                            const submissionsResult = await apiCall(`/instructor/assignments/${assignment.id}/submissions`);
                            if (submissionsResult && submissionsResult.ok) {
                                const submissions = submissionsResult.data.data || [];
                                allSubmissions.push(...submissions.map(sub => ({
                                    ...sub,
                                    course_title: course.title,
                                    assignment_title: assignment.title
                                })));
                            }
                        }
                    }
                }

                // Sort by submission date and take recent 5
                allSubmissions.sort((a, b) => new Date(b.submitted_at) - new Date(a.submitted_at));
                const recentSubmissions = allSubmissions.slice(0, 5);

                if (recentSubmissions.length > 0) {
                    submissionsContainer.innerHTML = recentSubmissions.map(submission => {
                        const submittedDate = new Date(submission.submitted_at);
                        const timeAgo = getTimeAgo(submittedDate);
                        const statusColor = submission.status === 'graded' ? 'text-green-600' : 'text-orange-600';
                        const statusIcon = submission.status === 'graded' ? 'fa-check-circle' : 'fa-clock';

                        return `
                            <div class="flex items-center space-x-4 p-3 hover:bg-gray-50 rounded-lg">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-indigo-600"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900">${submission.user?.name || 'Student'}</p>
                                    <p class="text-sm text-gray-600">${submission.assignment_title} - ${submission.course_title}</p>
                                    <p class="text-xs text-gray-500">${timeAgo}</p>
                                </div>
                                <div class="text-right">
                                    <i class="fas ${statusIcon} ${statusColor}"></i>
                                    <p class="text-xs ${statusColor} font-medium">${submission.status}</p>
                                </div>
                            </div>
                        `;
                    }).join('');
                } else {
                    submissionsContainer.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-inbox text-3xl text-gray-300 mb-2"></i>
                            <p class="text-gray-500">No recent submissions</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading recent submissions:', error);
                document.getElementById('recent-submissions').innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-3xl text-red-300 mb-2"></i>
                        <p class="text-red-500">Failed to load submissions</p>
                    </div>
                `;
            }
        }

        // Load student activity
        async function loadStudentActivity() {
            try {
                const activityContainer = document.getElementById('student-activity');
                let activities = [];

                // Mock student activity data for now
                activities = [
                    {
                        type: 'enrollment',
                        student: 'John Doe',
                        course: 'Introduction to Programming',
                        time: new Date(Date.now() - 2 * 60 * 60 * 1000) // 2 hours ago
                    },
                    {
                        type: 'submission',
                        student: 'Jane Smith',
                        assignment: 'Hello World Program',
                        time: new Date(Date.now() - 4 * 60 * 60 * 1000) // 4 hours ago
                    },
                    {
                        type: 'quiz_completion',
                        student: 'Bob Johnson',
                        quiz: 'Programming Fundamentals',
                        score: 85,
                        time: new Date(Date.now() - 6 * 60 * 60 * 1000) // 6 hours ago
                    }
                ];

                if (activities.length > 0) {
                    activityContainer.innerHTML = activities.map(activity => {
                        const timeAgo = getTimeAgo(activity.time);
                        let icon, color, text;

                        switch (activity.type) {
                            case 'enrollment':
                                icon = 'fa-user-plus';
                                color = 'text-green-600';
                                text = `${activity.student} enrolled in ${activity.course}`;
                                break;
                            case 'submission':
                                icon = 'fa-file-upload';
                                color = 'text-blue-600';
                                text = `${activity.student} submitted ${activity.assignment}`;
                                break;
                            case 'quiz_completion':
                                icon = 'fa-check-circle';
                                color = 'text-purple-600';
                                text = `${activity.student} completed ${activity.quiz} (${activity.score}%)`;
                                break;
                            default:
                                icon = 'fa-info-circle';
                                color = 'text-gray-600';
                                text = 'Unknown activity';
                        }

                        return `
                            <div class="flex items-start space-x-3 p-2 hover:bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 ${color.replace('text-', 'bg-').replace('-600', '-100')} rounded-full flex items-center justify-center">
                                    <i class="fas ${icon} ${color} text-sm"></i>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900">${text}</p>
                                    <p class="text-xs text-gray-500">${timeAgo}</p>
                                </div>
                            </div>
                        `;
                    }).join('');
                } else {
                    activityContainer.innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-history text-3xl text-gray-300 mb-2"></i>
                            <p class="text-gray-500">No recent activity</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading student activity:', error);
            }
        }

        // Load grade distribution chart with real data
        async function loadGradeDistribution() {
            try {
                const result = await apiCall('/instructor/grade-distribution');

                if (result && result.ok && result.data.status === 'success') {
                    const gradeData = result.data.data.overall_distribution;
                    createDashboardGradeChart(gradeData);
                } else {
                    console.error('Failed to load grade distribution:', result);
                    // Fallback to empty data
                    createDashboardGradeChart({
                        counts: { A: 0, B: 0, C: 0, D: 0, F: 0 },
                        percentages: { A: 0, B: 0, C: 0, D: 0, F: 0 },
                        total_grades: 0
                    });
                }
            } catch (error) {
                console.error('Error loading grade distribution:', error);
                // Fallback to empty data
                createDashboardGradeChart({
                    counts: { A: 0, B: 0, C: 0, D: 0, F: 0 },
                    percentages: { A: 0, B: 0, C: 0, D: 0, F: 0 },
                    total_grades: 0
                });
            }
        }

        // Create grade chart for dashboard
        function createDashboardGradeChart(gradeData) {
            const ctx = document.getElementById('gradeChart').getContext('2d');

            const counts = gradeData.counts || {};
            const percentages = gradeData.percentages || {};
            const totalGrades = gradeData.total_grades || 0;

            const chartData = {
                labels: ['A', 'B', 'C', 'D', 'F'],
                datasets: [{
                    data: [
                        counts.A || 0,
                        counts.B || 0,
                        counts.C || 0,
                        counts.D || 0,
                        counts.F || 0
                    ],
                    backgroundColor: [
                        '#10B981', // Green for A
                        '#3B82F6', // Blue for B
                        '#F59E0B', // Yellow for C
                        '#EF4444', // Red for D
                        '#6B7280'  // Gray for F
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            };

            if (gradeChart) {
                gradeChart.destroy();
            }

            gradeChart = new Chart(ctx, {
                type: 'doughnut',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    if (data.labels.length && data.datasets.length) {
                                        return data.labels.map((label, i) => {
                                            const count = data.datasets[0].data[i];
                                            const percentage = percentages[label] || 0;
                                            return {
                                                text: `${label}: ${count} (${percentage}%)`,
                                                fillStyle: data.datasets[0].backgroundColor[i],
                                                strokeStyle: data.datasets[0].borderColor,
                                                lineWidth: data.datasets[0].borderWidth,
                                                pointStyle: 'circle',
                                                hidden: false,
                                                index: i
                                            };
                                        });
                                    }
                                    return [];
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const percentage = percentages[label] || 0;
                                    return `${label}: ${value} students (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Update chart title with total count
            const chartContainer = document.querySelector('#gradeChart').closest('.bg-white');
            const titleElement = chartContainer.querySelector('h3');
            if (titleElement && titleElement.textContent.includes('Grade Distribution')) {
                titleElement.textContent = `Grade Distribution (${totalGrades} total)`;
            }
        }

        // Populate course selects in modals
        function populateCourseSelects(courses) {
            const selects = ['lectureCoursesSelect', 'quizCoursesSelect', 'assignmentCoursesSelect'];

            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                select.innerHTML = '<option value="">Select a course...</option>';

                courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id;
                    option.textContent = `${course.code} - ${course.title}`;
                    select.appendChild(option);
                });
            });
        }

        // Setup form handlers
        function setupFormHandlers() {
            // Lecture form
            document.getElementById('createLectureForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                await createLecture();
            });

            // Quiz form
            document.getElementById('createQuizForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                await createQuiz();
            });

            // Assignment form
            document.getElementById('createAssignmentForm').addEventListener('submit', async (e) => {
                e.preventDefault();
                await createAssignment();
            });
        }

        // Create lecture
        async function createLecture() {
            try {
                const courseId = document.getElementById('lectureCoursesSelect').value;
                const title = document.getElementById('lectureTitle').value;
                const description = document.getElementById('lectureDescription').value;
                const videoUrl = document.getElementById('lectureVideoUrl').value;
                const duration = document.getElementById('lectureDuration').value;

                if (!courseId || !title) {
                    alert('Please fill in required fields');
                    return;
                }

                const result = await apiCall(`/instructor/courses/${courseId}/lectures`, 'POST', {
                    title,
                    description,
                    video_url: videoUrl,
                    duration: duration ? `${duration} minutes` : null,
                    is_published: true
                });

                if (result && result.ok) {
                    alert('Lecture created successfully!');
                    hideCreateLectureModal();
                    loadInstructorDashboardData(); // Refresh data
                } else {
                    alert('Failed to create lecture: ' + (result.data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error creating lecture:', error);
                alert('Failed to create lecture');
            }
        }

        // Create quiz
        async function createQuiz() {
            try {
                const courseId = document.getElementById('quizCoursesSelect').value;
                const title = document.getElementById('quizTitle').value;
                const description = document.getElementById('quizDescription').value;
                const duration = document.getElementById('quizDuration').value;
                const maxScore = document.getElementById('quizMaxScore').value;

                if (!courseId || !title) {
                    alert('Please fill in required fields');
                    return;
                }

                const result = await apiCall(`/instructor/courses/${courseId}/quizzes`, 'POST', {
                    title,
                    description,
                    duration: duration ? parseInt(duration) : null,
                    max_score: maxScore ? parseInt(maxScore) : 100,
                    is_published: true
                });

                if (result && result.ok) {
                    alert('Quiz created successfully!');
                    hideCreateQuizModal();
                    loadInstructorDashboardData(); // Refresh data
                } else {
                    alert('Failed to create quiz: ' + (result.data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error creating quiz:', error);
                alert('Failed to create quiz');
            }
        }

        // Create assignment
        async function createAssignment() {
            try {
                const courseId = document.getElementById('assignmentCoursesSelect').value;
                const title = document.getElementById('assignmentTitle').value;
                const description = document.getElementById('assignmentDescription').value;
                const dueDate = document.getElementById('assignmentDueDate').value;
                const maxScore = document.getElementById('assignmentMaxScore').value;

                if (!courseId || !title) {
                    alert('Please fill in required fields');
                    return;
                }

                const result = await apiCall(`/instructor/courses/${courseId}/assignments`, 'POST', {
                    title,
                    description,
                    due_date: dueDate,
                    points: maxScore ? parseInt(maxScore) : 100
                });

                if (result && result.ok) {
                    alert('Assignment created successfully!');
                    hideCreateAssignmentModal();
                    loadInstructorDashboardData(); // Refresh data
                } else {
                    alert('Failed to create assignment: ' + (result.data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error creating assignment:', error);
                alert('Failed to create assignment');
            }
        }

        // Modal functions
        function showCreateLectureModal() {
            document.getElementById('createLectureModal').classList.remove('hidden');
        }

        function hideCreateLectureModal() {
            document.getElementById('createLectureModal').classList.add('hidden');
            document.getElementById('createLectureForm').reset();
        }

        function showCreateQuizModal() {
            document.getElementById('createQuizModal').classList.remove('hidden');
        }

        function hideCreateQuizModal() {
            document.getElementById('createQuizModal').classList.add('hidden');
            document.getElementById('createQuizForm').reset();
        }

        function showCreateAssignmentModal() {
            document.getElementById('createAssignmentModal').classList.remove('hidden');
        }

        function hideCreateAssignmentModal() {
            document.getElementById('createAssignmentModal').classList.add('hidden');
            document.getElementById('createAssignmentForm').reset();
        }

        // Utility functions
        function getTimeAgo(date) {
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);

            if (diffInSeconds < 60) {
                return 'Just now';
            } else if (diffInSeconds < 3600) {
                const minutes = Math.floor(diffInSeconds / 60);
                return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
            } else if (diffInSeconds < 86400) {
                const hours = Math.floor(diffInSeconds / 3600);
                return `${hours} hour${hours > 1 ? 's' : ''} ago`;
            } else {
                const days = Math.floor(diffInSeconds / 86400);
                return `${days} day${days > 1 ? 's' : ''} ago`;
            }
        }

        // Update notification count
        async function updateNotificationCount() {
            try {
                const result = await apiCall('/notifications/unread-count');
                if (result && result.ok) {
                    const count = result.data.count || 0;
                    const badge = document.getElementById('notification-badge');
                    if (count > 0) {
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('Error updating notification count:', error);
            }
        }

        // Logout function with proper token cleanup
        async function logout() {
            try {
                // Log the logout attempt
                console.log('Logging out user...');

                // Call logout API to invalidate token on server
                const result = await apiCall('/logout', 'POST');

                if (result && result.ok) {
                    console.log('Server logout successful:', result.data);
                }

                // Clear all authentication data from client
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                localStorage.removeItem('role');
                localStorage.removeItem('token_created_at');

                // Clear cookies
                document.cookie = 'token=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
                document.cookie = 'XSRF-TOKEN=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';

                console.log('Client logout completed, redirecting to login...');
                window.location.href = '/login';
            } catch (error) {
                console.error('Error during logout:', error);

                // Even if server logout fails, clear client data
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                localStorage.removeItem('role');
                localStorage.removeItem('token_created_at');

                // Clear cookies
                document.cookie = 'token=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
                document.cookie = 'XSRF-TOKEN=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT;';

                window.location.href = '/login';
            }
        }

        // Refresh dashboard
        function refreshDashboard() {
            loadInstructorDashboardData();
        }
    </script>
</body>
</html>
