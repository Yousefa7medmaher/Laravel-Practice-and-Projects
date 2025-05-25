<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Enrollment - Educational Platform</title>
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

        .filter-active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .enrollment-success {
            animation: enrollmentPulse 0.6s ease-in-out;
        }

        @keyframes enrollmentPulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
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
                        <a href="/student/course-enrollment" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="gradient-bg rounded-xl shadow-xl p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold mb-2">📚 Comprehensive Enrollment Management</h2>
                    <p class="text-indigo-100 text-lg">Advanced course discovery, enrollment tracking, and academic planning</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-graduation-cap text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Enhanced Student Status Dashboard -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <!-- Current Enrollment Status -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-book-open text-blue-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-500">Current Semester</span>
                </div>
                <div class="space-y-2">
                    <div class="text-2xl font-bold text-gray-900" id="enrolled-courses">0</div>
                    <div class="text-sm text-gray-600">Enrolled Courses</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-300" id="enrollment-progress" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- Credit Hours Tracking -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-clock text-green-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-500">Credit Hours</span>
                </div>
                <div class="space-y-2">
                    <div class="flex items-baseline space-x-2">
                        <span class="text-2xl font-bold text-gray-900" id="current-credits">0</span>
                        <span class="text-sm text-gray-500">/ <span id="max-credits">18</span></span>
                    </div>
                    <div class="text-sm text-gray-600">Credit Hours</div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-green-600 h-2 rounded-full transition-all duration-300" id="credit-progress" style="width: 0%"></div>
                    </div>
                </div>
            </div>

            <!-- GPA Status -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-500">Academic Standing</span>
                </div>
                <div class="space-y-2">
                    <div class="text-2xl font-bold text-gray-900" id="current-gpa">N/A</div>
                    <div class="text-sm text-gray-600">Current GPA</div>
                    <div class="text-xs" id="gpa-status">
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full">New Student</span>
                    </div>
                </div>
            </div>

            <!-- Enrollment Actions -->
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-plus-circle text-indigo-600 text-xl"></i>
                    </div>
                    <span class="text-sm font-medium text-gray-500">Quick Actions</span>
                </div>
                <div class="space-y-3">
                    <button onclick="showEnrollmentHistory()" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-history mr-2"></i>View History
                    </button>
                    <button onclick="showRecommendations()" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-lightbulb mr-2"></i>Get Recommendations
                    </button>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0 lg:space-x-4">
                <!-- Search Bar -->
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" id="search-input" placeholder="Search courses by title, code, or description..."
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Filter Buttons -->
                <div class="flex flex-wrap gap-2">
                    <button onclick="filterCourses('all')" class="filter-btn filter-active px-4 py-2 rounded-lg text-sm font-medium transition-colors" data-filter="all">
                        All Courses
                    </button>
                    <button onclick="filterCourses('available')" class="filter-btn bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors" data-filter="available">
                        Available
                    </button>
                    <button onclick="filterCourses('enrolled')" class="filter-btn bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors" data-filter="enrolled">
                        Enrolled
                    </button>
                    <button onclick="filterCourses('computer-science')" class="filter-btn bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors" data-filter="computer-science">
                        Computer Science
                    </button>
                    <button onclick="filterCourses('mathematics')" class="filter-btn bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors" data-filter="mathematics">
                        Mathematics
                    </button>
                    <button onclick="filterCourses('3-credits')" class="filter-btn bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors" data-filter="3-credits">
                        3 Credits
                    </button>
                    <button onclick="filterCourses('4-credits')" class="filter-btn bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors" data-filter="4-credits">
                        4 Credits
                    </button>
                </div>
            </div>
        </div>

        <!-- Courses Grid -->
        <div id="courses-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Loading skeletons -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-pulse">
                <div class="h-48 bg-gray-200"></div>
                <div class="p-6">
                    <div class="h-4 bg-gray-200 rounded mb-2"></div>
                    <div class="h-3 bg-gray-200 rounded w-3/4 mb-4"></div>
                    <div class="h-2 bg-gray-200 rounded mb-2"></div>
                    <div class="h-2 bg-gray-200 rounded w-1/2"></div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-pulse">
                <div class="h-48 bg-gray-200"></div>
                <div class="p-6">
                    <div class="h-4 bg-gray-200 rounded mb-2"></div>
                    <div class="h-3 bg-gray-200 rounded w-3/4 mb-4"></div>
                    <div class="h-2 bg-gray-200 rounded mb-2"></div>
                    <div class="h-2 bg-gray-200 rounded w-1/2"></div>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow-lg overflow-hidden animate-pulse">
                <div class="h-48 bg-gray-200"></div>
                <div class="p-6">
                    <div class="h-4 bg-gray-200 rounded mb-2"></div>
                    <div class="h-3 bg-gray-200 rounded w-3/4 mb-4"></div>
                    <div class="h-2 bg-gray-200 rounded mb-2"></div>
                    <div class="h-2 bg-gray-200 rounded w-1/2"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let authToken = localStorage.getItem('token');
        let allCourses = [];
        let enrolledCourses = [];
        let currentFilter = 'all';
        let studentData = {};

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

            // Load initial data
            loadUserProfile();
            loadStudentData();
            loadAllCourses();

            // Setup search functionality
            setupSearch();
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

        // Load student data (enrollment status)
        async function loadStudentData() {
            try {
                const result = await apiCall('/student/enrolled-courses');
                if (result && result.ok) {
                    enrolledCourses = result.data.data || [];

                    // Calculate current credits
                    const currentCredits = enrolledCourses.reduce((total, course) => {
                        return total + (course.credit_hours || 3);
                    }, 0);

                    // Calculate realistic GPA based on course progress
                    const gpa = calculateRealisticGPA(enrolledCourses);

                    // Determine max credits based on GPA
                    let maxCredits = 15; // Default for new students
                    if (gpa !== null) {
                        if (gpa > 3.0) maxCredits = 21;
                        else if (gpa > 2.0) maxCredits = 18;
                    }

                    studentData = {
                        currentCredits,
                        maxCredits,
                        gpa,
                        enrolledCoursesCount: enrolledCourses.length
                    };

                    // Update status display
                    updateStatusDisplay();

                    // Also load GPA from API for more accurate calculation
                    loadStudentGPA();
                }
            } catch (error) {
                console.error('Error loading student data:', error);
            }
        }

        // Load student GPA from API
        async function loadStudentGPA() {
            try {
                const result = await apiCall('/student/gpa');

                if (result && result.ok && result.data.status === 'success') {
                    const gpaData = result.data.data;

                    // Update student data with API GPA
                    studentData.gpa = gpaData.gpa;

                    // Update display
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

                    // Recalculate max credits based on updated GPA
                    updateMaxCredits();
                }
            } catch (error) {
                console.error('Error loading GPA:', error);
                // Keep the frontend calculation as fallback
            }
        }

        // Update max credits based on GPA
        function updateMaxCredits() {
            let maxCredits = 15; // Default for new students
            if (studentData.gpa !== null) {
                if (studentData.gpa > 3.0) maxCredits = 21;
                else if (studentData.gpa > 2.0) maxCredits = 18;
            }

            studentData.maxCredits = maxCredits;
            document.getElementById('max-credits').textContent = maxCredits;
        }

        // Update status display
        function updateStatusDisplay() {
            const currentCredits = studentData.currentCredits || 0;
            const maxCredits = studentData.maxCredits || 15;
            const enrolledCount = studentData.enrolledCoursesCount || 0;
            const gpa = studentData.gpa;

            // Update text values
            document.getElementById('current-credits').textContent = currentCredits;
            document.getElementById('max-credits').textContent = maxCredits;
            document.getElementById('current-gpa').textContent = gpa ? gpa.toFixed(2) : 'N/A';
            document.getElementById('enrolled-courses').textContent = enrolledCount;

            // Update progress bars
            const creditProgress = Math.min((currentCredits / maxCredits) * 100, 100);
            document.getElementById('credit-progress').style.width = `${creditProgress}%`;

            const enrollmentProgress = Math.min((enrolledCount / 6) * 100, 100); // Assuming max 6 courses
            document.getElementById('enrollment-progress').style.width = `${enrollmentProgress}%`;

            // Update GPA status
            updateGPAStatus(gpa);
        }

        // Update GPA status badge
        function updateGPAStatus(gpa) {
            const statusElement = document.getElementById('gpa-status');

            if (gpa === null || gpa === undefined) {
                statusElement.innerHTML = '<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full">New Student</span>';
            } else if (gpa >= 3.5) {
                statusElement.innerHTML = '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full">Excellent</span>';
            } else if (gpa >= 3.0) {
                statusElement.innerHTML = '<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full">Good Standing</span>';
            } else if (gpa >= 2.0) {
                statusElement.innerHTML = '<span class="px-2 py-1 bg-yellow-100 text-yellow-800 rounded-full">Satisfactory</span>';
            } else {
                statusElement.innerHTML = '<span class="px-2 py-1 bg-red-100 text-red-800 rounded-full">Academic Warning</span>';
            }
        }

        // Load all available courses
        async function loadAllCourses() {
            try {
                const result = await apiCall('/public/courses');
                if (result && result.ok) {
                    allCourses = result.data.data || [];
                    displayCourses(allCourses);
                }
            } catch (error) {
                console.error('Error loading courses:', error);
                showErrorMessage('Failed to load courses. Please try again later.');
            }
        }

        // Display courses in grid
        function displayCourses(courses) {
            const container = document.getElementById('courses-container');

            if (courses.length === 0) {
                container.innerHTML = `
                    <div class="col-span-3 text-center py-12">
                        <i class="fas fa-search text-gray-300 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-700 mb-2">No courses found</h3>
                        <p class="text-gray-500">Try adjusting your search or filter criteria.</p>
                    </div>
                `;
                return;
            }

            container.innerHTML = courses.map(course => {
                const isEnrolled = enrolledCourses.some(enrolled => enrolled.id === course.id);
                const canEnroll = !isEnrolled && canEnrollInCourse(course.credit_hours || 3);

                return `
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
                        <div class="h-48 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
                            <div class="absolute top-4 right-4 bg-white bg-opacity-90 text-indigo-600 text-xs font-bold px-2 py-1 rounded">
                                ${course.credit_hours || 3} Credits
                            </div>
                            <div class="absolute bottom-4 left-4 text-white">
                                <div class="text-sm font-medium">${course.code}</div>
                            </div>
                            ${isEnrolled ? `
                                <div class="absolute top-4 left-4 bg-green-500 text-white text-xs font-bold px-2 py-1 rounded">
                                    <i class="fas fa-check mr-1"></i>Enrolled
                                </div>
                            ` : ''}
                        </div>
                        <div class="p-6">
                            <h3 class="font-bold text-gray-900 mb-2 text-lg">${course.title}</h3>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">${course.description || 'Course description not available.'}</p>

                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-users mr-1"></i>
                                    ${Math.floor(Math.random() * 50) + 10} students
                                </div>

                                ${isEnrolled ? `
                                    <a href="/courses/${course.id}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        View Course
                                    </a>
                                ` : canEnroll ? `
                                    <button onclick="enrollInCourse(${course.id})" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        Enroll Now
                                    </button>
                                ` : `
                                    <button disabled class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg text-sm font-medium cursor-not-allowed">
                                        Credit Limit
                                    </button>
                                `}
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Check if student can enroll in course based on credit limits
        function canEnrollInCourse(creditHours) {
            const totalCredits = (studentData.currentCredits || 0) + creditHours;
            return totalCredits <= (studentData.maxCredits || 15);
        }

        // Setup search functionality
        function setupSearch() {
            const searchInput = document.getElementById('search-input');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const filteredCourses = allCourses.filter(course => {
                    return course.title.toLowerCase().includes(searchTerm) ||
                           course.code.toLowerCase().includes(searchTerm) ||
                           (course.description && course.description.toLowerCase().includes(searchTerm));
                });
                displayCourses(applyCurrentFilter(filteredCourses));
            });
        }

        // Filter courses
        function filterCourses(filterType) {
            currentFilter = filterType;

            // Update filter button states
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('filter-active');
                btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
            });

            const activeBtn = document.querySelector(`[data-filter="${filterType}"]`);
            activeBtn.classList.add('filter-active');
            activeBtn.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');

            // Apply filter
            const filteredCourses = applyCurrentFilter(allCourses);
            displayCourses(filteredCourses);
        }

        // Apply current filter to courses
        function applyCurrentFilter(courses) {
            switch(currentFilter) {
                case 'available':
                    return courses.filter(course => !enrolledCourses.some(enrolled => enrolled.id === course.id));
                case 'enrolled':
                    return courses.filter(course => enrolledCourses.some(enrolled => enrolled.id === course.id));
                case 'computer-science':
                    return courses.filter(course => course.code && course.code.toLowerCase().startsWith('cs'));
                case 'mathematics':
                    return courses.filter(course => course.code && (course.code.toLowerCase().startsWith('math') || course.code.toLowerCase().startsWith('mat')));
                case '3-credits':
                    return courses.filter(course => (course.credit_hours || 3) === 3);
                case '4-credits':
                    return courses.filter(course => (course.credit_hours || 3) === 4);
                default:
                    return courses;
            }
        }

        // Enroll in course function
        async function enrollInCourse(courseId) {
            const course = allCourses.find(c => c.id === courseId);
            if (!course) return;

            // Check credit limit
            if (!canEnrollInCourse(course.credit_hours || 3)) {
                showNotification('Cannot enroll: This would exceed your credit hour limit.', 'error');
                return;
            }

            // Show confirmation
            if (!confirm(`Are you sure you want to enroll in "${course.title}"?`)) {
                return;
            }

            try {
                const result = await apiCall(`/courses/${courseId}/enroll`, 'POST');

                if (result && result.ok) {
                    showNotification(`Successfully enrolled in "${course.title}"!`, 'success');

                    // Refresh data
                    await loadStudentData();
                    await loadAllCourses();

                    // Add enrollment animation
                    const courseCard = document.querySelector(`[onclick="enrollInCourse(${courseId})"]`).closest('.card-hover');
                    if (courseCard) {
                        courseCard.classList.add('enrollment-success');
                    }

                } else {
                    showNotification(result.data?.message || 'Failed to enroll in course.', 'error');
                }
            } catch (error) {
                console.error('Error enrolling in course:', error);
                showNotification('An error occurred while enrolling. Please try again.', 'error');
            }
        }

        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            // Remove after 5 seconds
            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        // Show error message
        function showErrorMessage(message) {
            const container = document.getElementById('courses-container');
            container.innerHTML = `
                <div class="col-span-3 text-center py-12">
                    <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Error Loading Courses</h3>
                    <p class="text-gray-500 mb-4">${message}</p>
                    <button onclick="loadAllCourses()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                        Try Again
                    </button>
                </div>
            `;
        }

        // Calculate realistic GPA based on course progress
        function calculateRealisticGPA(courses) {
            if (!courses || courses.length === 0) {
                return null;
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

            // If no courses have been completed (60%+ progress), return null
            if (!hasCompletedCourses || totalCreditHours === 0) {
                return null;
            }

            return totalGradePoints / totalCreditHours;
        }

        // Logout function
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                apiCall('/logout', 'POST').then(() => {
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                }).catch(() => {
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                });
            }
        }

        // Show enrollment history modal
        function showEnrollmentHistory() {
            showModal('Enrollment History', `
                <div class="space-y-4">
                    <div class="text-sm text-gray-600 mb-4">Track your enrollment activities and academic progress</div>

                    <div id="enrollment-history-content">
                        <div class="animate-pulse space-y-3">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                            <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button onclick="closeModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                            Close
                        </button>
                    </div>
                </div>
            `);

            // Load enrollment history
            loadEnrollmentHistory();
        }

        // Load enrollment history
        async function loadEnrollmentHistory() {
            try {
                const result = await apiCall('/student/enrolled-courses');
                if (result && result.ok) {
                    const courses = result.data.data || [];

                    const historyHTML = courses.length > 0 ? courses.map(course => `
                        <div class="border-l-4 border-blue-500 pl-4 py-3 bg-blue-50 rounded-r-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-semibold text-gray-900">${course.title}</h4>
                                    <p class="text-sm text-gray-600">${course.code} • ${course.credit_hours || 3} Credits</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-medium text-green-600">Enrolled</div>
                                    <div class="text-xs text-gray-500">Active</div>
                                </div>
                            </div>
                        </div>
                    `).join('') : `
                        <div class="text-center py-8">
                            <i class="fas fa-history text-gray-300 text-3xl mb-3"></i>
                            <p class="text-gray-500">No enrollment history yet</p>
                        </div>
                    `;

                    document.getElementById('enrollment-history-content').innerHTML = historyHTML;
                }
            } catch (error) {
                console.error('Error loading enrollment history:', error);
                document.getElementById('enrollment-history-content').innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-red-400 text-3xl mb-3"></i>
                        <p class="text-red-500">Failed to load enrollment history</p>
                    </div>
                `;
            }
        }

        // Show course recommendations
        function showRecommendations() {
            showModal('Course Recommendations', `
                <div class="space-y-4">
                    <div class="text-sm text-gray-600 mb-4">Personalized course suggestions based on your academic profile</div>

                    <div id="recommendations-content">
                        <div class="animate-pulse space-y-3">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                            <div class="h-4 bg-gray-200 rounded w-2/3"></div>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button onclick="closeModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">
                            Close
                        </button>
                    </div>
                </div>
            `);

            // Load recommendations
            loadCourseRecommendations();
        }

        // Load course recommendations
        async function loadCourseRecommendations() {
            try {
                // Get available courses that student is not enrolled in
                const availableCourses = allCourses.filter(course =>
                    !enrolledCourses.some(enrolled => enrolled.id === course.id) &&
                    canEnrollInCourse(course.credit_hours || 3)
                );

                if (availableCourses.length === 0) {
                    document.getElementById('recommendations-content').innerHTML = `
                        <div class="text-center py-8">
                            <i class="fas fa-check-circle text-green-400 text-3xl mb-3"></i>
                            <p class="text-gray-500">You're enrolled in all available courses within your credit limit!</p>
                        </div>
                    `;
                    return;
                }

                // Simple recommendation logic
                const recommendations = availableCourses.slice(0, 3).map(course => {
                    let reason = 'Available for enrollment';
                    let priority = 'Medium';

                    if (course.code.toLowerCase().includes('101')) {
                        reason = 'Foundational course - great for building basics';
                        priority = 'High';
                    } else if (course.credit_hours === 3) {
                        reason = 'Standard credit load - fits well in your schedule';
                        priority = 'Medium';
                    } else if (course.code.toLowerCase().includes('web')) {
                        reason = 'Popular in-demand skill area';
                        priority = 'High';
                    }

                    return { course, reason, priority };
                });

                const recommendationsHTML = recommendations.map(({ course, reason, priority }) => `
                    <div class="border rounded-lg p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900">${course.title}</h4>
                                <p class="text-sm text-gray-600 mb-2">${course.code} • ${course.credit_hours || 3} Credits</p>
                                <p class="text-sm text-blue-600">${reason}</p>
                            </div>
                            <div class="ml-4 text-right">
                                <span class="px-2 py-1 text-xs rounded-full ${
                                    priority === 'High' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'
                                }">${priority} Priority</span>
                                <button onclick="enrollInCourse(${course.id}); closeModal();"
                                        class="block mt-2 bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1 rounded text-sm">
                                    Enroll Now
                                </button>
                            </div>
                        </div>
                    </div>
                `).join('');

                document.getElementById('recommendations-content').innerHTML = recommendationsHTML;
            } catch (error) {
                console.error('Error loading recommendations:', error);
                document.getElementById('recommendations-content').innerHTML = `
                    <div class="text-center py-8">
                        <i class="fas fa-exclamation-triangle text-red-400 text-3xl mb-3"></i>
                        <p class="text-red-500">Failed to load recommendations</p>
                    </div>
                `;
            }
        }

        // Show modal utility function
        function showModal(title, content) {
            const modal = document.createElement('div');
            modal.id = 'enrollment-modal';
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-900">${title}</h3>
                            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>
                        ${content}
                    </div>
                </div>
            `;

            document.body.appendChild(modal);

            // Close on background click
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    closeModal();
                }
            });
        }

        // Close modal utility function
        function closeModal() {
            const modal = document.getElementById('enrollment-modal');
            if (modal) {
                modal.remove();
            }
        }

        // Make functions globally available
        window.filterCourses = filterCourses;
        window.enrollInCourse = enrollInCourse;
        window.logout = logout;
        window.showEnrollmentHistory = showEnrollmentHistory;
        window.showRecommendations = showRecommendations;
        window.closeModal = closeModal;
    </script>
</body>
</html>
