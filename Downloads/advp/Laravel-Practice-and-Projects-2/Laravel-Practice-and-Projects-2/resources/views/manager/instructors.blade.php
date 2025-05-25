<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Management - Educational Platform</title>
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

        .modal {
            backdrop-filter: blur(10px);
        }

        .search-input {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .performance-badge {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }

        .activity-badge {
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
        }

        .courses-badge {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
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
                        <a href="/manager/dashboard" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <a href="/manager/courses" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-book mr-2"></i>Courses
                        </a>
                        <a href="/manager/students" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-user-graduate mr-2"></i>Students
                        </a>
                        <a href="/manager/instructors" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>Instructors
                        </a>
                        <a href="/manager/reports" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-chart-line mr-2"></i>Reports
                        </a>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
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
                    <h2 class="text-3xl font-bold mb-2">Instructor Management</h2>
                    <p class="text-indigo-100 text-lg">Manage instructor assignments, performance tracking, and course oversight</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-chalkboard-teacher text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="manager-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chalkboard-teacher text-blue-600 text-xl"></i>
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
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-book text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Assigned Courses</p>
                        <p class="text-2xl font-bold text-gray-900" id="assigned-courses">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="manager-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tasks text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Graded</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-graded">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="manager-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-orange-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Active This Week</p>
                        <p class="text-2xl font-bold text-gray-900" id="active-week">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <div class="manager-card rounded-xl shadow-lg p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                    <div class="relative">
                        <input type="text" id="search-input" placeholder="Search instructors..."
                               class="search-input pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <select id="filter-status" class="search-input px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="assigned">Has Assignments</option>
                        <option value="unassigned">No Assignments</option>
                        <option value="active">Recently Active</option>
                    </select>
                    <select id="filter-performance" class="search-input px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">All Performance</option>
                        <option value="high">High Performers</option>
                        <option value="medium">Medium Performers</option>
                        <option value="low">Needs Attention</option>
                    </select>
                </div>
                <div class="flex space-x-3">
                    <button onclick="exportInstructorData()" class="bg-green-100 text-green-700 px-4 py-2 rounded-lg hover:bg-green-200 transition-all">
                        <i class="fas fa-download mr-2"></i>Export
                    </button>
                    <button onclick="refreshInstructors()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-all">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Instructors Grid -->
        <div id="instructors-container" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Loading skeletons -->
            <div class="skeleton-instructor">
                <div class="manager-card rounded-xl shadow-lg p-6 card-hover">
                    <div class="skeleton h-6 w-3/4 mb-4 rounded"></div>
                    <div class="skeleton h-4 w-1/2 mb-2 rounded"></div>
                    <div class="skeleton h-4 w-full mb-4 rounded"></div>
                    <div class="flex justify-between items-center">
                        <div class="skeleton h-4 w-1/3 rounded"></div>
                        <div class="skeleton h-8 w-20 rounded"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="hidden text-center py-12">
            <div class="manager-card rounded-xl shadow-lg p-12">
                <i class="fas fa-chalkboard-teacher text-6xl text-gray-300 mb-6"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No instructors found</h3>
                <p class="text-gray-500 mb-6">No instructors match your current search criteria</p>
                <button onclick="clearFilters()" class="action-button text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all">
                    <i class="fas fa-filter mr-2"></i>Clear Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Instructor Detail Modal -->
    <div id="instructorDetailModal" class="fixed inset-0 bg-black bg-opacity-50 modal hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="manager-card rounded-xl shadow-2xl max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900" id="instructor-detail-name">Instructor Details</h3>
                        <button onclick="hideInstructorDetailModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <div id="instructor-detail-content">
                        <!-- Instructor details will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Assignment Modal -->
    <div id="courseAssignmentModal" class="fixed inset-0 bg-black bg-opacity-50 modal hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="manager-card rounded-xl shadow-2xl max-w-2xl w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Manage Course Assignments</h3>
                        <button onclick="hideCourseAssignmentModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <div id="course-assignment-content">
                        <!-- Course assignment content will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let authToken = localStorage.getItem('token');
        let allInstructors = [];
        let filteredInstructors = [];
        let allCourses = [];
        let currentInstructorId = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken) {
                window.location.href = '/login';
                return;
            }

            loadUserProfile();
            loadInstructors();
            loadCourses();
            setupEventListeners();
        });

        // Setup event listeners
        function setupEventListeners() {
            // Search and filter
            document.getElementById('search-input').addEventListener('input', filterInstructors);
            document.getElementById('filter-status').addEventListener('change', filterInstructors);
            document.getElementById('filter-performance').addEventListener('change', filterInstructors);
        }

        // API call utility
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

        // Load instructors
        async function loadInstructors() {
            try {
                const result = await apiCall('/manager/instructors');
                if (result && result.ok) {
                    allInstructors = result.data.data || [];
                    filteredInstructors = [...allInstructors];
                    updateStats();
                    displayInstructors();
                } else {
                    console.error('Failed to load instructors:', result);
                    // Fallback to users API
                    const fallbackResult = await apiCall('/users?role=instructor');
                    if (fallbackResult && fallbackResult.ok) {
                        allInstructors = fallbackResult.data.data || [];
                        // Add default performance data
                        allInstructors = allInstructors.map(instructor => ({
                            ...instructor,
                            courses_assigned_count: 0,
                            total_graded_count: 0,
                            assigned_courses: []
                        }));
                        filteredInstructors = [...allInstructors];
                        updateStats();
                        displayInstructors();
                    } else {
                        showEmptyState();
                    }
                }
            } catch (error) {
                console.error('Error loading instructors:', error);
                showEmptyState();
            }
        }

        // Load courses
        async function loadCourses() {
            try {
                const result = await apiCall('/manager/courses');
                if (result && result.ok) {
                    allCourses = result.data.data || [];
                }
            } catch (error) {
                console.error('Error loading courses:', error);
            }
        }

        // Update statistics
        function updateStats() {
            const totalInstructors = allInstructors.length;
            const assignedCourses = allInstructors.reduce((sum, instructor) => sum + (instructor.courses_assigned_count || 0), 0);
            const totalGraded = allInstructors.reduce((sum, instructor) => sum + (instructor.total_graded_count || 0), 0);
            const activeThisWeek = allInstructors.filter(instructor => instructor.total_graded_count > 0).length;

            document.getElementById('total-instructors').textContent = totalInstructors;
            document.getElementById('assigned-courses').textContent = assignedCourses;
            document.getElementById('total-graded').textContent = totalGraded.toLocaleString();
            document.getElementById('active-week').textContent = activeThisWeek;
        }

        // Filter instructors
        function filterInstructors() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const statusFilter = document.getElementById('filter-status').value;
            const performanceFilter = document.getElementById('filter-performance').value;

            filteredInstructors = allInstructors.filter(instructor => {
                const matchesSearch = instructor.name.toLowerCase().includes(searchTerm) ||
                                    instructor.email.toLowerCase().includes(searchTerm);

                let matchesStatus = true;
                if (statusFilter) {
                    switch(statusFilter) {
                        case 'assigned':
                            matchesStatus = instructor.courses_assigned_count > 0;
                            break;
                        case 'unassigned':
                            matchesStatus = instructor.courses_assigned_count === 0;
                            break;
                        case 'active':
                            matchesStatus = instructor.total_graded_count > 0;
                            break;
                    }
                }

                let matchesPerformance = true;
                if (performanceFilter) {
                    const gradedCount = instructor.total_graded_count || 0;
                    switch(performanceFilter) {
                        case 'high':
                            matchesPerformance = gradedCount >= 50;
                            break;
                        case 'medium':
                            matchesPerformance = gradedCount >= 10 && gradedCount < 50;
                            break;
                        case 'low':
                            matchesPerformance = gradedCount < 10;
                            break;
                    }
                }

                return matchesSearch && matchesStatus && matchesPerformance;
            });

            displayInstructors();
        }

        // Display instructors
        function displayInstructors() {
            const container = document.getElementById('instructors-container');
            const emptyState = document.getElementById('empty-state');

            if (filteredInstructors.length === 0) {
                container.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');
            container.innerHTML = filteredInstructors.map(instructor => `
                <div class="manager-card rounded-xl shadow-lg p-6 card-hover">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-chalkboard-teacher text-indigo-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900">${instructor.name}</h3>
                                <p class="text-sm text-gray-500">${instructor.email}</p>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="viewInstructorDetails(${instructor.id})" class="text-indigo-600 hover:text-indigo-800 p-2 rounded-lg hover:bg-indigo-50 transition-all">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button onclick="manageCourseAssignments(${instructor.id})" class="text-green-600 hover:text-green-800 p-2 rounded-lg hover:bg-green-50 transition-all">
                                <i class="fas fa-book"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-3 mb-4">
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-xs text-gray-500 mb-1">Courses</div>
                            <div class="text-lg font-bold text-gray-900">${instructor.courses_assigned_count || 0}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-xs text-gray-500 mb-1">Graded</div>
                            <div class="text-lg font-bold text-gray-900">${instructor.total_graded_count || 0}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-xs text-gray-500 mb-1">Status</div>
                            <div class="text-sm font-medium ${getInstructorStatusClass(instructor)}">
                                ${getInstructorStatus(instructor)}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-500">Performance:</span>
                            <span class="performance-badge text-white text-xs font-medium px-2 py-1 rounded-full">
                                ${getPerformanceLevel(instructor.total_graded_count)}
                            </span>
                        </div>
                        ${instructor.assigned_courses && instructor.assigned_courses.length > 0 ? `
                            <div class="text-xs text-gray-500">
                                <strong>Courses:</strong> ${instructor.assigned_courses.slice(0, 2).map(c => c.code || c.title).join(', ')}
                                ${instructor.assigned_courses.length > 2 ? '...' : ''}
                            </div>
                        ` : '<div class="text-xs text-gray-500">No course assignments</div>'}
                    </div>
                </div>
            `).join('');
        }

        // Helper functions
        function getInstructorStatusClass(instructor) {
            if (instructor.courses_assigned_count > 0 && instructor.total_graded_count > 0) {
                return 'text-green-600';
            } else if (instructor.courses_assigned_count > 0) {
                return 'text-yellow-600';
            }
            return 'text-gray-600';
        }

        function getInstructorStatus(instructor) {
            if (instructor.courses_assigned_count > 0 && instructor.total_graded_count > 0) {
                return 'Active';
            } else if (instructor.courses_assigned_count > 0) {
                return 'Assigned';
            }
            return 'Available';
        }

        function getPerformanceLevel(gradedCount) {
            if (gradedCount >= 50) return 'High';
            if (gradedCount >= 10) return 'Medium';
            return 'Low';
        }

        // Show empty state
        function showEmptyState() {
            document.getElementById('instructors-container').innerHTML = '';
            document.getElementById('empty-state').classList.remove('hidden');
        }

        // Instructor operations
        function viewInstructorDetails(instructorId) {
            const instructor = allInstructors.find(i => i.id === instructorId);
            if (!instructor) return;

            document.getElementById('instructor-detail-name').textContent = instructor.name;

            const detailContent = `
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Name:</span>
                                <span class="font-medium">${instructor.name}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Email:</span>
                                <span class="font-medium">${instructor.email}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Role:</span>
                                <span class="font-medium">Instructor</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Status:</span>
                                <span class="font-medium ${getInstructorStatusClass(instructor)}">${getInstructorStatus(instructor)}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Performance Metrics</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Assigned Courses:</span>
                                <span class="courses-badge text-white text-sm font-medium px-3 py-1 rounded-full">
                                    ${instructor.courses_assigned_count || 0}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Total Graded:</span>
                                <span class="activity-badge text-white text-sm font-medium px-3 py-1 rounded-full">
                                    ${instructor.total_graded_count || 0}
                                </span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Performance Level:</span>
                                <span class="performance-badge text-white text-sm font-medium px-3 py-1 rounded-full">
                                    ${getPerformanceLevel(instructor.total_graded_count)}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Course Assignments</h4>
                    <div class="space-y-3">
                        ${instructor.assigned_courses && instructor.assigned_courses.length > 0 ?
                            instructor.assigned_courses.map(course => `
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <div class="font-medium">${course.title || 'Course Title'}</div>
                                        <div class="text-sm text-gray-500">${course.code || 'Course Code'} • ${course.credit_hours || 3} Credits</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium">Active</div>
                                        <div class="text-xs text-gray-500">Assigned by Manager</div>
                                    </div>
                                </div>
                            `).join('') :
                            '<p class="text-gray-500">No course assignments found</p>'
                        }
                    </div>
                </div>
            `;

            document.getElementById('instructor-detail-content').innerHTML = detailContent;
            document.getElementById('instructorDetailModal').classList.remove('hidden');
        }

        function hideInstructorDetailModal() {
            document.getElementById('instructorDetailModal').classList.add('hidden');
        }

        function manageCourseAssignments(instructorId) {
            currentInstructorId = instructorId;
            const instructor = allInstructors.find(i => i.id === instructorId);
            if (!instructor) return;

            const assignedCourseIds = instructor.assigned_courses ? instructor.assigned_courses.map(c => c.id) : [];
            const availableCourses = allCourses.filter(course => !course.instructor || course.instructor.id === instructorId);

            const assignmentContent = `
                <div class="space-y-6">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Assign Courses to ${instructor.name}</h4>
                        <p class="text-gray-600 mb-4">Select courses to assign or unassign from this instructor.</p>
                    </div>

                    <div class="space-y-3">
                        ${availableCourses.map(course => `
                            <div class="flex items-center justify-between p-3 border border-gray-200 rounded-lg">
                                <div class="flex items-center space-x-3">
                                    <input type="checkbox" id="course-${course.id}"
                                           ${assignedCourseIds.includes(course.id) ? 'checked' : ''}
                                           class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                                    <label for="course-${course.id}" class="flex-1">
                                        <div class="font-medium">${course.title}</div>
                                        <div class="text-sm text-gray-500">${course.code} • ${course.credit_hours || 3} Credits</div>
                                    </label>
                                </div>
                                <div class="text-sm text-gray-500">
                                    ${course.total_students || 0} students
                                </div>
                            </div>
                        `).join('')}
                    </div>

                    <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                        <button onclick="hideCourseAssignmentModal()"
                                class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all">
                            Cancel
                        </button>
                        <button onclick="saveCourseAssignments()"
                                class="action-button text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all">
                            <i class="fas fa-save mr-2"></i>Save Assignments
                        </button>
                    </div>
                </div>
            `;

            document.getElementById('course-assignment-content').innerHTML = assignmentContent;
            document.getElementById('courseAssignmentModal').classList.remove('hidden');
        }

        function hideCourseAssignmentModal() {
            document.getElementById('courseAssignmentModal').classList.add('hidden');
            currentInstructorId = null;
        }

        async function saveCourseAssignments() {
            if (!currentInstructorId) {
                alert('No instructor selected');
                return;
            }

            // Get selected course IDs
            const checkboxes = document.querySelectorAll('#course-assignment-content input[type="checkbox"]:checked');
            const selectedCourseIds = Array.from(checkboxes).map(cb => parseInt(cb.id.replace('course-', '')));

            try {
                // Show loading state
                const saveButton = document.querySelector('button[onclick="saveCourseAssignments()"]');
                const originalText = saveButton.innerHTML;
                saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
                saveButton.disabled = true;

                const result = await apiCall(`/instructors/${currentInstructorId}/assign-courses`, 'POST', {
                    course_ids: selectedCourseIds
                });

                if (result && result.ok) {
                    // Show success message
                    const instructor = allInstructors.find(i => i.id === currentInstructorId);
                    const data = result.data.data;

                    let message = `Course assignments updated for ${data.instructor}!\n\n`;

                    if (data.assigned_courses.length > 0) {
                        message += `✅ Assigned: ${data.assigned_courses.join(', ')}\n`;
                    }

                    if (data.unassigned_courses.length > 0) {
                        message += `❌ Unassigned: ${data.unassigned_courses.join(', ')}\n`;
                    }

                    message += `\n📊 Total assigned courses: ${data.total_assigned}`;
                    message += `\n\n🔔 Notifications have been sent to:`;
                    message += `\n   • The instructor about their assignment changes`;
                    message += `\n   • Other managers about this update`;

                    alert(message);

                    // Refresh instructor data
                    await loadInstructors();

                    // Close modal
                    hideCourseAssignmentModal();
                } else {
                    throw new Error(result?.data?.message || 'Failed to update course assignments');
                }
            } catch (error) {
                console.error('Error saving course assignments:', error);
                alert('Failed to update course assignments: ' + error.message);
            } finally {
                // Reset button state
                const saveButton = document.querySelector('button[onclick="saveCourseAssignments()"]');
                if (saveButton) {
                    saveButton.innerHTML = '<i class="fas fa-save mr-2"></i>Save Assignments';
                    saveButton.disabled = false;
                }
            }
        }

        function clearFilters() {
            document.getElementById('search-input').value = '';
            document.getElementById('filter-status').value = '';
            document.getElementById('filter-performance').value = '';
            filterInstructors();
        }

        function exportInstructorData() {
            // Create CSV content
            const headers = ['Name', 'Email', 'Assigned Courses', 'Total Graded', 'Performance Level', 'Status'];
            const csvContent = [
                headers.join(','),
                ...filteredInstructors.map(instructor => [
                    instructor.name,
                    instructor.email,
                    instructor.courses_assigned_count || 0,
                    instructor.total_graded_count || 0,
                    getPerformanceLevel(instructor.total_graded_count),
                    getInstructorStatus(instructor)
                ].join(','))
            ].join('\n');

            // Download CSV
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `instructors_data_${new Date().toISOString().split('T')[0]}.csv`;
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function refreshInstructors() {
            loadInstructors();
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
