<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Management - Educational Platform</title>
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

        .grade-badge {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
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
                        <a href="/manager/dashboard" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                        </a>
                        <a href="/manager/courses" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-book mr-2"></i>Courses
                        </a>
                        <a href="/manager/students" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                    <h2 class="text-3xl font-bold mb-2">Student Management</h2>
                    <p class="text-indigo-100 text-lg">Monitor student performance, academic records, and enrollment status</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-user-graduate text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="manager-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-graduate text-blue-600 text-xl"></i>
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
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Average GPA</p>
                        <p class="text-2xl font-bold text-gray-900" id="average-gpa">
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
                        <input type="text" id="search-input" placeholder="Search students..."
                               class="search-input pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <select id="filter-course" class="search-input px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">All Courses</option>
                    </select>
                    <select id="filter-performance" class="search-input px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">All Performance</option>
                        <option value="excellent">Excellent (90%+)</option>
                        <option value="good">Good (80-89%)</option>
                        <option value="average">Average (70-79%)</option>
                        <option value="below">Below Average (<70%)</option>
                    </select>
                </div>
                <div class="flex space-x-3">
                    <button onclick="exportStudentData()" class="bg-green-100 text-green-700 px-4 py-2 rounded-lg hover:bg-green-200 transition-all">
                        <i class="fas fa-download mr-2"></i>Export
                    </button>
                    <button onclick="refreshStudents()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-all">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Students Table -->
        <div class="manager-card rounded-xl shadow-lg overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6">
                    <button onclick="switchView('table')" id="table-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm active">
                        <i class="fas fa-table mr-2"></i>Table View
                    </button>
                    <button onclick="switchView('cards')" id="cards-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm">
                        <i class="fas fa-th-large mr-2"></i>Card View
                    </button>
                    <button onclick="switchView('analytics')" id="analytics-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm">
                        <i class="fas fa-chart-bar mr-2"></i>Analytics
                    </button>
                </nav>
            </div>

            <!-- Table View -->
            <div id="table-view" class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Courses</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average Grade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="students-table-body" class="bg-white divide-y divide-gray-200">
                            <!-- Students will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Card View -->
            <div id="cards-view" class="hidden p-6">
                <div id="students-cards-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Student cards will be loaded here -->
                </div>
            </div>

            <!-- Analytics View -->
            <div id="analytics-view" class="hidden p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Performance Distribution Chart -->
                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Distribution</h3>
                        <div class="h-64">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>

                    <!-- Course Enrollment Chart -->
                    <div class="bg-white rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Enrollment</h3>
                        <div class="h-64">
                            <canvas id="enrollmentChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Detail Modal -->
    <div id="studentDetailModal" class="fixed inset-0 bg-black bg-opacity-50 modal hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="manager-card rounded-xl shadow-2xl max-w-4xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900" id="student-detail-name">Student Details</h3>
                        <button onclick="hideStudentDetailModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <div id="student-detail-content">
                        <!-- Student details will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let authToken = localStorage.getItem('token');
        let allStudents = [];
        let filteredStudents = [];
        let allCourses = [];
        let currentView = 'table';
        let performanceChart = null;
        let enrollmentChart = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken) {
                window.location.href = '/login';
                return;
            }

            loadUserProfile();
            loadStudents();
            loadCourses();
            setupEventListeners();
        });

        // Setup event listeners
        function setupEventListeners() {
            // Search and filter
            document.getElementById('search-input').addEventListener('input', filterStudents);
            document.getElementById('filter-course').addEventListener('change', filterStudents);
            document.getElementById('filter-performance').addEventListener('change', filterStudents);
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

        // Load students
        async function loadStudents() {
            try {
                console.log('Loading students from API...');
                const result = await apiCall('/manager/students');
                console.log('Raw API result:', result);

                if (result && result.ok) {
                    console.log('Students loaded successfully:', result.data);

                    // Handle different response structures
                    let studentsData = [];
                    if (result.data && Array.isArray(result.data)) {
                        studentsData = result.data;
                    } else if (result.data && result.data.data && Array.isArray(result.data.data)) {
                        studentsData = result.data.data;
                    } else if (result.data && result.data.students && Array.isArray(result.data.students)) {
                        studentsData = result.data.students;
                    }

                    console.log('Extracted students data:', studentsData);
                    allStudents = studentsData;

                    // Process student data to ensure all required fields exist
                    allStudents = allStudents.map(student => {
                        console.log('Processing student data:', student);
                        return {
                            ...student,
                            avg_grade: student.avg_grade || 0,
                            total_courses: student.total_courses || 0,
                            enrollments: student.enrolled_courses || student.enrolledCourses || [],
                            submissions: student.submissions || []
                        };
                    });

                    console.log('Processed allStudents:', allStudents);
                    filteredStudents = [...allStudents];
                    console.log('Set filteredStudents:', filteredStudents);

                    updateStats();
                    displayStudents();
                    updateCharts();
                } else {
                    console.error('Failed to load students:', result);
                    // Try fallback API
                    const fallbackResult = await apiCall('/users?role=student');
                    if (fallbackResult && fallbackResult.ok) {
                        console.log('Students loaded from fallback API:', fallbackResult.data);
                        allStudents = fallbackResult.data.data || [];

                        // Process fallback data
                        allStudents = allStudents.map(student => ({
                            ...student,
                            avg_grade: student.avg_grade || 0,
                            total_courses: student.total_courses || 0,
                            enrollments: student.enrollments || [],
                            submissions: student.submissions || []
                        }));

                        filteredStudents = [...allStudents];
                        updateStats();
                        displayStudents();
                        updateCharts();
                    } else {
                        showEmptyState();
                    }
                }
            } catch (error) {
                console.error('Error loading students:', error);
                showEmptyState();
            }
        }

        // Load courses for filter
        async function loadCourses() {
            try {
                console.log('Loading courses from API...');
                const result = await apiCall('/manager/courses');
                if (result && result.ok) {
                    console.log('Courses loaded successfully:', result.data);
                    allCourses = result.data.data || [];
                    populateCourseFilter();
                } else {
                    console.error('Failed to load courses:', result);
                    allCourses = [];
                }
            } catch (error) {
                console.error('Error loading courses:', error);
                allCourses = [];
            }
        }

        // Update statistics
        function updateStats() {
            const totalStudents = allStudents.length;
            const averageGPA = allStudents.length > 0 ?
                (allStudents.reduce((sum, student) => sum + (student.avg_grade || 0), 0) / allStudents.length).toFixed(2) : 0;

            document.getElementById('total-students').textContent = totalStudents;
            document.getElementById('average-gpa').textContent = averageGPA + '%';
        }

        // Populate course filter
        function populateCourseFilter() {
            const filterSelect = document.getElementById('filter-course');
            filterSelect.innerHTML = '<option value="">All Courses</option>';

            allCourses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.id;
                option.textContent = `${course.code} - ${course.title}`;
                filterSelect.appendChild(option);
            });
        }

        // Filter students
        function filterStudents() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const courseFilter = document.getElementById('filter-course').value;
            const performanceFilter = document.getElementById('filter-performance').value;

            filteredStudents = allStudents.filter(student => {
                const matchesSearch = student.name.toLowerCase().includes(searchTerm) ||
                                    student.email.toLowerCase().includes(searchTerm);

                const matchesCourse = !courseFilter ||
                                    (student.enrollments && student.enrollments.some(enrollment =>
                                        enrollment.course.id.toString() === courseFilter));

                let matchesPerformance = true;
                if (performanceFilter) {
                    const avgGrade = student.avg_grade || 0;
                    switch(performanceFilter) {
                        case 'excellent':
                            matchesPerformance = avgGrade >= 90;
                            break;
                        case 'good':
                            matchesPerformance = avgGrade >= 80 && avgGrade < 90;
                            break;
                        case 'average':
                            matchesPerformance = avgGrade >= 70 && avgGrade < 80;
                            break;
                        case 'below':
                            matchesPerformance = avgGrade < 70;
                            break;
                    }
                }

                return matchesSearch && matchesCourse && matchesPerformance;
            });

            displayStudents();
        }

        // Display students based on current view
        function displayStudents() {
            if (currentView === 'table') {
                displayStudentsTable();
            } else if (currentView === 'cards') {
                displayStudentsCards();
            }
        }

        // Display students in table format
        function displayStudentsTable() {
            const tbody = document.getElementById('students-table-body');

            console.log('displayStudentsTable called with filteredStudents:', filteredStudents);

            if (filteredStudents.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-user-graduate text-4xl mb-4"></i>
                            <p>No students found</p>
                        </td>
                    </tr>
                `;
                return;
            }

            try {
                tbody.innerHTML = filteredStudents.map(student => {
                    console.log('Processing student:', student);
                    console.log('Student avg_grade:', student.avg_grade, 'type:', typeof student.avg_grade);

                    // Safe enrollment display
                    let enrollmentDisplay = 'No enrollments';
                    if (student.enrollments && Array.isArray(student.enrollments) && student.enrollments.length > 0) {
                        try {
                            const courseCodes = student.enrollments.slice(0, 2).map(course => {
                                // Handle direct course objects (from enrolledCourses relationship)
                                if (course && course.code) {
                                    return course.code;
                                }
                                // Handle pivot relationship structure
                                else if (course && course.course && course.course.code) {
                                    return course.course.code;
                                }
                                else {
                                    return null;
                                }
                            }).filter(code => code !== null);

                            if (courseCodes.length > 0) {
                                enrollmentDisplay = courseCodes.join(', ');
                                if (student.enrollments.length > 2) {
                                    enrollmentDisplay += '...';
                                }
                            }
                        } catch (e) {
                            console.error('Error processing enrollments for student:', student.id, e);
                            enrollmentDisplay = 'Error loading courses';
                        }
                    }

                    return `
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-user text-indigo-600"></i>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">${student.name || 'Unknown'}</div>
                                        <div class="text-sm text-gray-500">${student.email || 'No email'}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">${student.total_courses || 0} courses</div>
                                <div class="text-sm text-gray-500">${enrollmentDisplay}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="grade-badge text-white text-sm font-medium px-3 py-1 rounded-full">
                                        ${(student.avg_grade ? parseFloat(student.avg_grade).toFixed(1) : '0.0')}%
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusBadgeClass(student.avg_grade || 0)}">
                                    ${getPerformanceStatus(student.avg_grade || 0)}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button onclick="viewStudentDetails(${student.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button onclick="manageEnrollment(${student.id})" class="text-green-600 hover:text-green-900">
                                    <i class="fas fa-user-plus"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                }).join('');
            } catch (error) {
                console.error('Error in displayStudentsTable:', error);
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-red-500">
                            <i class="fas fa-exclamation-triangle text-4xl mb-4"></i>
                            <p>Error displaying students. Check console for details.</p>
                        </td>
                    </tr>
                `;
            }
        }

        // Display students in card format
        function displayStudentsCards() {
            const container = document.getElementById('students-cards-container');

            if (filteredStudents.length === 0) {
                container.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-user-graduate text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No students found</h3>
                        <p class="text-gray-500">Try adjusting your search or filter criteria</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = filteredStudents.map(student => `
                <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-indigo-600 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">${student.name}</h3>
                                <p class="text-sm text-gray-500">${student.email}</p>
                            </div>
                        </div>
                        <div class="grade-badge text-white text-sm font-medium px-3 py-1 rounded-full">
                            ${(student.avg_grade ? parseFloat(student.avg_grade).toFixed(1) : '0.0')}%
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-xs text-gray-500 mb-1">Courses</div>
                            <div class="text-lg font-bold text-gray-900">${student.total_courses || 0}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3 text-center">
                            <div class="text-xs text-gray-500 mb-1">Status</div>
                            <div class="text-sm font-medium ${getStatusTextClass(student.avg_grade || 0)}">
                                ${getPerformanceStatus(student.avg_grade || 0)}
                            </div>
                        </div>
                    </div>



                    <div class="flex space-x-2">
                        <button onclick="viewStudentDetails(${student.id})" class="flex-1 bg-indigo-100 text-indigo-700 px-3 py-2 rounded-lg hover:bg-indigo-200 transition-all text-sm">
                            <i class="fas fa-eye mr-2"></i>View Details
                        </button>
                        <button onclick="manageEnrollment(${student.id})" class="flex-1 bg-green-100 text-green-700 px-3 py-2 rounded-lg hover:bg-green-200 transition-all text-sm">
                            <i class="fas fa-user-plus mr-2"></i>Manage
                        </button>
                    </div>
                </div>
            `).join('');
        }

        // Helper functions for status display
        function getStatusBadgeClass(avgGrade) {
            if (avgGrade >= 90) return 'bg-green-100 text-green-800';
            if (avgGrade >= 80) return 'bg-blue-100 text-blue-800';
            if (avgGrade >= 70) return 'bg-yellow-100 text-yellow-800';
            return 'bg-red-100 text-red-800';
        }

        function getStatusTextClass(avgGrade) {
            if (avgGrade >= 90) return 'text-green-600';
            if (avgGrade >= 80) return 'text-blue-600';
            if (avgGrade >= 70) return 'text-yellow-600';
            return 'text-red-600';
        }

        function getPerformanceStatus(avgGrade) {
            if (avgGrade >= 90) return 'Excellent';
            if (avgGrade >= 80) return 'Good';
            if (avgGrade >= 70) return 'Average';
            return 'Below Average';
        }

        // Switch view
        function switchView(viewType) {
            currentView = viewType;

            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'border-indigo-500', 'text-white');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            const activeTab = document.getElementById(`${viewType}-tab`);
            if (activeTab) {
                activeTab.classList.add('active', 'border-indigo-500', 'text-white');
                activeTab.classList.remove('border-transparent', 'text-gray-500');
            }

            // Show/hide views
            ['table-view', 'cards-view', 'analytics-view'].forEach(view => {
                const element = document.getElementById(view);
                if (view === `${viewType}-view`) {
                    element.classList.remove('hidden');
                } else {
                    element.classList.add('hidden');
                }
            });

            if (viewType === 'analytics') {
                updateCharts();
            } else {
                displayStudents();
            }
        }

        // Update charts
        function updateCharts() {
            // Performance Distribution Chart
            const performanceCtx = document.getElementById('performanceChart').getContext('2d');
            if (performanceChart) performanceChart.destroy();

            const performanceData = {
                excellent: allStudents.filter(s => (s.avg_grade || 0) >= 90).length,
                good: allStudents.filter(s => (s.avg_grade || 0) >= 80 && (s.avg_grade || 0) < 90).length,
                average: allStudents.filter(s => (s.avg_grade || 0) >= 70 && (s.avg_grade || 0) < 80).length,
                below: allStudents.filter(s => (s.avg_grade || 0) < 70).length
            };

            performanceChart = new Chart(performanceCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Excellent (90%+)', 'Good (80-89%)', 'Average (70-79%)', 'Below Average (<70%)'],
                    datasets: [{
                        data: [performanceData.excellent, performanceData.good, performanceData.average, performanceData.below],
                        backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444'],
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

            // Course Enrollment Chart
            const enrollmentCtx = document.getElementById('enrollmentChart').getContext('2d');
            if (enrollmentChart) enrollmentChart.destroy();

            const courseEnrollments = {};
            allStudents.forEach(student => {
                if (student.enrollments) {
                    student.enrollments.forEach(course => {
                        // Handle direct course objects (from enrolledCourses relationship)
                        const courseCode = course.code || (course.course && course.course.code);
                        if (courseCode) {
                            courseEnrollments[courseCode] = (courseEnrollments[courseCode] || 0) + 1;
                        }
                    });
                }
            });

            const topCourses = Object.entries(courseEnrollments)
                .sort(([,a], [,b]) => b - a)
                .slice(0, 8);

            enrollmentChart = new Chart(enrollmentCtx, {
                type: 'bar',
                data: {
                    labels: topCourses.map(([course]) => course),
                    datasets: [{
                        label: 'Enrolled Students',
                        data: topCourses.map(([,count]) => count),
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

        // Student operations
        function viewStudentDetails(studentId) {
            const student = allStudents.find(s => s.id === studentId);
            if (!student) return;

            document.getElementById('student-detail-name').textContent = student.name;

            const detailContent = `
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Personal Information</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Name:</span>
                                <span class="font-medium">${student.name}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Email:</span>
                                <span class="font-medium">${student.email}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Total Courses:</span>
                                <span class="font-medium">${student.total_courses || 0}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Average Grade:</span>
                                <span class="font-medium">${(student.avg_grade ? parseFloat(student.avg_grade).toFixed(1) : '0.0')}%</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Academic Performance</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Performance Status:</span>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusBadgeClass(student.avg_grade || 0)}">
                                    ${getPerformanceStatus(student.avg_grade || 0)}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Course Enrollments</h4>
                    <div class="space-y-3">
                        ${student.enrollments && student.enrollments.length > 0 ? student.enrollments.map(course => {
                            // Handle direct course objects (from enrolledCourses relationship)
                            const courseTitle = course.title || (course.course && course.course.title) || 'Unknown Course';
                            const courseCode = course.code || (course.course && course.course.code) || 'N/A';
                            const creditHours = course.credit_hours || (course.course && course.course.credit_hours) || 3;

                            return `
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div>
                                        <div class="font-medium">${courseTitle}</div>
                                        <div class="text-sm text-gray-500">${courseCode} • ${creditHours} Credits</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium">Status: Active</div>
                                        <div class="text-xs text-gray-500">Enrolled: ${new Date().toLocaleDateString()}</div>
                                    </div>
                                </div>
                            `;
                        }).join('') : '<p class="text-gray-500">No course enrollments found</p>'}
                    </div>
                </div>
            `;

            document.getElementById('student-detail-content').innerHTML = detailContent;
            document.getElementById('studentDetailModal').classList.remove('hidden');
        }

        function hideStudentDetailModal() {
            document.getElementById('studentDetailModal').classList.add('hidden');
        }

        function manageEnrollment(studentId) {
            alert(`Enrollment management for student ${studentId} will be implemented`);
        }

        function exportStudentData() {
            // Create CSV content
            const headers = ['Name', 'Email', 'Courses', 'Average Grade'];
            const csvContent = [
                headers.join(','),
                ...filteredStudents.map(student => [
                    student.name,
                    student.email,
                    student.total_courses || 0,
                    (student.avg_grade ? parseFloat(student.avg_grade).toFixed(1) : '0.0')
                ].join(','))
            ].join('\n');

            // Download CSV
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `students_data_${new Date().toISOString().split('T')[0]}.csv`;
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function refreshStudents() {
            loadStudents();
        }

        // Show empty state
        function showEmptyState() {
            const container = document.getElementById('students-container');
            const emptyState = document.getElementById('empty-state');

            if (container) container.innerHTML = '';
            if (emptyState) emptyState.classList.remove('hidden');

            // Also clear charts
            if (performanceChart) {
                performanceChart.destroy();
                performanceChart = null;
            }
            if (enrollmentChart) {
                enrollmentChart.destroy();
                enrollmentChart = null;
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
