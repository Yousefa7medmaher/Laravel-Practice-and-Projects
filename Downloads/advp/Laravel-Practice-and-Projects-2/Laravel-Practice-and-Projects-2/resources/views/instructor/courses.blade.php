<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Courses - Educational Platform</title>
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

        .course-card {
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
                        <a href="/instructor/courses" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                    <h2 class="text-3xl font-bold mb-2">My Assigned Courses</h2>
                    <p class="text-indigo-100 text-lg">Manage courses assigned to you by managers</p>
                    <div class="flex items-center mt-3 text-sm text-indigo-200">
                        <i class="fas fa-shield-alt mr-2"></i>
                        <span>Only courses assigned by managers are shown here</span>
                    </div>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-chalkboard-teacher text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="course-card rounded-xl shadow-lg p-6 card-hover">
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

            <div class="course-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-green-600 text-xl"></i>
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

            <div class="course-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-video text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Content</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-content">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="course-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-star text-orange-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Avg. Rating</p>
                        <p class="text-2xl font-bold text-gray-900" id="avg-rating">
                            <span class="skeleton w-12 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Courses Grid -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-book text-indigo-600 mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-900">Course Management</h3>
                    </div>
                    <div class="flex space-x-3">
                        <div class="bg-yellow-100 text-yellow-800 px-4 py-2 rounded-lg text-sm">
                            <i class="fas fa-info-circle mr-2"></i>Courses are assigned by managers
                        </div>
                        <button onclick="refreshCourses()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-all">
                            <i class="fas fa-sync-alt mr-2"></i>Refresh
                        </button>
                    </div>
                </div>
            </div>
            <div class="p-6">
                <div id="courses-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Loading skeleton -->
                    <div class="course-card rounded-lg p-6 animate-pulse">
                        <div class="h-32 bg-gray-200 rounded mb-4"></div>
                        <div class="h-4 bg-gray-200 rounded mb-2"></div>
                        <div class="h-3 bg-gray-200 rounded w-3/4 mb-4"></div>
                        <div class="flex justify-between">
                            <div class="h-6 bg-gray-200 rounded w-16"></div>
                            <div class="h-6 bg-gray-200 rounded w-20"></div>
                        </div>
                    </div>
                    <div class="course-card rounded-lg p-6 animate-pulse">
                        <div class="h-32 bg-gray-200 rounded mb-4"></div>
                        <div class="h-4 bg-gray-200 rounded mb-2"></div>
                        <div class="h-3 bg-gray-200 rounded w-3/4 mb-4"></div>
                        <div class="flex justify-between">
                            <div class="h-6 bg-gray-200 rounded w-16"></div>
                            <div class="h-6 bg-gray-200 rounded w-20"></div>
                        </div>
                    </div>
                    <div class="course-card rounded-lg p-6 animate-pulse">
                        <div class="h-32 bg-gray-200 rounded mb-4"></div>
                        <div class="h-4 bg-gray-200 rounded mb-2"></div>
                        <div class="h-3 bg-gray-200 rounded w-3/4 mb-4"></div>
                        <div class="flex justify-between">
                            <div class="h-6 bg-gray-200 rounded w-16"></div>
                            <div class="h-6 bg-gray-200 rounded w-20"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Note: Course creation/editing modals removed - only managers can create/edit courses -->

    <!-- JavaScript -->
    <script>
        // Global variables
        let authToken = localStorage.getItem('token');
        let instructorCourses = [];

        // Initialize page when loaded
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken) {
                window.location.href = '/login';
                return;
            }

            loadUserProfile();
            loadInstructorCourses();
            setupEventListeners();
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

        // Load instructor assigned courses only
        async function loadInstructorCourses() {
            try {
                console.log('Loading assigned courses...');
                const result = await apiCall('/instructor/assigned-courses');

                if (result && result.ok && result.data.status === 'success') {
                    instructorCourses = result.data.data || [];
                    console.log('Assigned courses loaded:', instructorCourses);
                    displayCourses(instructorCourses);
                    updateStats(instructorCourses);
                } else {
                    console.error('Failed to load assigned courses:', result);
                    displayNoCoursesMessage(result);
                }
            } catch (error) {
                console.error('Error loading assigned courses:', error);
                displayError('Error loading assigned courses');
            }
        }

        // Display courses in grid
        function displayCourses(courses) {
            const coursesGrid = document.getElementById('courses-grid');

            if (courses.length === 0) {
                displayNoCoursesAssigned();
                return;
            }

            coursesGrid.innerHTML = courses.map(course => {
                const enrollmentCount = course.student_count || 0;
                const contentStats = course.content_stats || {};
                const lecturesCount = contentStats.lectures || 0;
                const assignmentsCount = contentStats.assignments || 0;
                const quizzesCount = contentStats.quizzes || 0;
                const labsCount = contentStats.labs || 0;
                const materialsCount = contentStats.materials || 0;
                const totalContent = lecturesCount + assignmentsCount + quizzesCount + labsCount + materialsCount;

                const submissionStats = course.submission_stats || {};
                const totalSubmissions = submissionStats.total_submissions || 0;
                const pendingGrading = submissionStats.pending_grading || 0;

                return `
                    <div class="course-card rounded-lg shadow-lg overflow-hidden card-hover">
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
                        <div class="p-6">
                            <h4 class="font-bold text-lg text-gray-900 mb-1">${course.title}</h4>
                            <p class="text-sm text-gray-600 mb-2 flex items-center">
                                <i class="fas fa-tag mr-1 text-indigo-500"></i> ${course.code}
                            </p>
                            <p class="text-sm text-gray-700 mb-4 line-clamp-2">${course.description || 'No description available'}</p>

                            <div class="grid grid-cols-2 gap-2 text-xs text-gray-500 mb-4">
                                <span><i class="fas fa-video mr-1 text-blue-500"></i> ${lecturesCount} lectures</span>
                                <span><i class="fas fa-tasks mr-1 text-green-500"></i> ${assignmentsCount} assignments</span>
                                <span><i class="fas fa-question-circle mr-1 text-purple-500"></i> ${quizzesCount} quizzes</span>
                                <span><i class="fas fa-flask mr-1 text-orange-500"></i> ${labsCount} labs</span>
                            </div>

                            ${pendingGrading > 0 ? `
                                <div class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs mb-3">
                                    <i class="fas fa-clock mr-1"></i> ${pendingGrading} pending grading
                                </div>
                            ` : ''}

                            <div class="flex justify-between items-center">
                                <div class="text-xs text-gray-500">
                                    <i class="fas fa-shield-alt mr-1 text-green-500"></i>Manager assigned
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="viewCourseDetails(${course.id})" class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-xs hover:bg-gray-200 transition-all">
                                        <i class="fas fa-eye mr-1"></i>View
                                    </button>
                                    <a href="/instructor/courses/${course.id}/manage" class="action-button text-white px-2 py-1 rounded text-xs hover:shadow-lg transition-all">
                                        <i class="fas fa-cog mr-1"></i>Manage
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Display no courses assigned message
        function displayNoCoursesAssigned() {
            const coursesGrid = document.getElementById('courses-grid');
            coursesGrid.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <div class="max-w-md mx-auto">
                        <i class="fas fa-chalkboard-teacher text-6xl text-gray-300 mb-6"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-3">No Courses Assigned Yet</h3>
                        <p class="text-gray-500 mb-6">You haven't been assigned any courses yet. Contact your manager to get courses assigned to you.</p>

                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center justify-center text-blue-800 mb-2">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span class="font-medium">How Course Assignment Works</span>
                            </div>
                            <p class="text-blue-700 text-sm">
                                Only managers can assign courses to instructors. Once assigned, you'll see your courses here and can manage their content, students, and grading.
                            </p>
                        </div>

                        <div class="flex justify-center space-x-3">
                            <button onclick="refreshCourses()" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition-all">
                                <i class="fas fa-sync-alt mr-2"></i>Refresh
                            </button>
                            <a href="/instructor/dashboard" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition-all">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            `;
        }

        // Display no courses message with API result info
        function displayNoCoursesMessage(result) {
            const coursesGrid = document.getElementById('courses-grid');

            if (result && result.status === 401) {
                coursesGrid.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-lock text-6xl text-red-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-red-600 mb-2">Authentication Required</h3>
                        <p class="text-red-500 mb-6">Your session has expired. Please login again.</p>
                        <button onclick="window.location.href='/login'" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                            Login Again
                        </button>
                    </div>
                `;
            } else if (result && result.status === 403) {
                coursesGrid.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-ban text-6xl text-red-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-red-600 mb-2">Access Denied</h3>
                        <p class="text-red-500 mb-6">You don't have permission to access courses.</p>
                        <button onclick="window.location.href='/dashboard'" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                            Go to Dashboard
                        </button>
                    </div>
                `;
            } else {
                displayNoCoursesAssigned();
            }
        }

        // View course details modal
        async function viewCourseDetails(courseId) {
            try {
                const result = await apiCall(`/instructor/courses/${courseId}/details`);
                if (result && result.ok) {
                    const course = result.data.data;
                    showCourseDetailsModal(course);
                } else {
                    alert('Failed to load course details');
                }
            } catch (error) {
                console.error('Error loading course details:', error);
                alert('Error loading course details');
            }
        }

        // Show course details modal
        function showCourseDetailsModal(course) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4';
            modal.innerHTML = `
                <div class="bg-white rounded-xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-xl font-bold text-gray-900">${course.title}</h3>
                            <button onclick="this.closest('.fixed').remove()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times text-xl"></i>
                            </button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Course Information</h4>
                                <div class="space-y-2 text-sm">
                                    <p><span class="font-medium">Code:</span> ${course.code}</p>
                                    <p><span class="font-medium">Credit Hours:</span> ${course.credit_hours}</p>
                                    <p><span class="font-medium">Status:</span>
                                        <span class="px-2 py-1 rounded text-xs ${course.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                            ${course.status}
                                        </span>
                                    </p>
                                    <p><span class="font-medium">Students:</span> ${course.student_count}</p>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-900 mb-2">Content Statistics</h4>
                                <div class="space-y-2 text-sm">
                                    <p><i class="fas fa-video text-blue-500 mr-2"></i>${course.content?.lectures?.length || 0} Lectures</p>
                                    <p><i class="fas fa-tasks text-green-500 mr-2"></i>${course.content?.assignments?.length || 0} Assignments</p>
                                    <p><i class="fas fa-question-circle text-purple-500 mr-2"></i>${course.content?.quizzes?.length || 0} Quizzes</p>
                                    <p><i class="fas fa-flask text-orange-500 mr-2"></i>${course.content?.labs?.length || 0} Labs</p>
                                    <p><i class="fas fa-file text-gray-500 mr-2"></i>${course.content?.materials?.length || 0} Materials</p>
                                </div>
                            </div>
                        </div>

                        ${course.description ? `
                            <div class="mt-6">
                                <h4 class="font-semibold text-gray-900 mb-2">Description</h4>
                                <p class="text-gray-700 text-sm">${course.description}</p>
                            </div>
                        ` : ''}

                        <div class="mt-6 flex justify-end space-x-3">
                            <button onclick="this.closest('.fixed').remove()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                                Close
                            </button>
                            <a href="/instructor/courses/${course.id}/manage" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                                Manage Course
                            </a>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // Update statistics
        function updateStats(courses) {
            const totalCourses = courses.length;
            let totalStudents = 0;
            let totalContent = 0;
            let pendingGrading = 0;

            courses.forEach(course => {
                totalStudents += course.student_count || 0;

                const contentStats = course.content_stats || {};
                totalContent += (contentStats.lectures || 0) +
                               (contentStats.assignments || 0) +
                               (contentStats.quizzes || 0) +
                               (contentStats.labs || 0) +
                               (contentStats.materials || 0);

                const submissionStats = course.submission_stats || {};
                pendingGrading += submissionStats.pending_grading || 0;
            });

            // Update the statistics display
            document.getElementById('total-courses').textContent = totalCourses;
            document.getElementById('total-students').textContent = totalStudents;
            document.getElementById('total-content').textContent = totalContent;

            // Change avg-rating to show pending grading instead
            const avgRatingElement = document.getElementById('avg-rating');
            if (avgRatingElement) {
                avgRatingElement.textContent = pendingGrading;
                // Update the label too
                const ratingLabel = avgRatingElement.closest('.course-card').querySelector('.text-gray-500');
                if (ratingLabel && ratingLabel.textContent.includes('Avg. Rating')) {
                    ratingLabel.textContent = 'Pending Grading';
                }
                // Update the icon
                const ratingIcon = avgRatingElement.closest('.course-card').querySelector('.fa-star');
                if (ratingIcon) {
                    ratingIcon.className = 'fas fa-clock text-orange-600 text-xl';
                }
            }
        }

        // Display error message
        function displayError(message) {
            const coursesGrid = document.getElementById('courses-grid');
            coursesGrid.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <i class="fas fa-exclamation-triangle text-6xl text-red-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-red-600 mb-2">Error</h3>
                    <p class="text-red-500 mb-6">${message}</p>
                    <button onclick="refreshCourses()" class="action-button text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all">
                        <i class="fas fa-sync-alt mr-2"></i>Try Again
                    </button>
                </div>
            `;
        }

        // Setup event listeners
        function setupEventListeners() {
            // Instructors can only manage assigned courses, not create/edit/delete them
            // Only managers have course creation/editing permissions
        }

        // Refresh courses
        function refreshCourses() {
            loadInstructorCourses();
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
