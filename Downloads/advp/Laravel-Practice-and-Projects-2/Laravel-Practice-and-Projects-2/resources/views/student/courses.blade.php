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

        .progress-bar {
            transition: width 0.5s ease-in-out;
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
                        <a href="/courses" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="gradient-bg rounded-xl shadow-xl p-8 mb-8 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold mb-2">My Courses</h2>
                    <p class="text-indigo-100 text-lg">Track your progress and access course materials</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-book-open text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-2xl font-bold text-indigo-600" id="total-courses">-</div>
                <div class="text-sm text-gray-500">Enrolled Courses</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-2xl font-bold text-green-600" id="completed-courses">-</div>
                <div class="text-sm text-gray-500">Completed</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-2xl font-bold text-yellow-600" id="in-progress-courses">-</div>
                <div class="text-sm text-gray-500">In Progress</div>
            </div>
            <div class="bg-white rounded-xl shadow-lg p-6 text-center">
                <div class="text-2xl font-bold text-purple-600" id="total-credits">-</div>
                <div class="text-sm text-gray-500">Total Credits</div>
            </div>
        </div>

        <!-- Upcoming Deadlines -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-clock text-red-500 mr-2"></i>
                Upcoming Deadlines
            </h3>
            <div id="upcoming-deadlines" class="space-y-3">
                <!-- Loading state -->
                <div class="animate-pulse">
                    <div class="h-4 bg-gray-200 rounded w-3/4 mb-2"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                </div>
            </div>
        </div>

        <!-- Filter and Search -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between space-y-4 lg:space-y-0 lg:space-x-4">
                <div class="flex-1">
                    <div class="relative">
                        <input type="text" id="search-input" placeholder="Search your courses..."
                               class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
                <div class="flex flex-wrap gap-2">
                    <button onclick="filterCourses('all')" class="filter-btn bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors" data-filter="all">
                        All Courses
                    </button>
                    <button onclick="filterCourses('in-progress')" class="filter-btn bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors" data-filter="in-progress">
                        In Progress
                    </button>
                    <button onclick="filterCourses('completed')" class="filter-btn bg-gray-100 text-gray-700 hover:bg-gray-200 px-4 py-2 rounded-lg text-sm font-medium transition-colors" data-filter="completed">
                        Completed
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
        let enrolledCourses = [];
        let currentFilter = 'all';

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
            loadEnrolledCourses();
            loadUpcomingDeadlines();
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

        // Load enrolled courses
        async function loadEnrolledCourses() {
            try {
                const result = await apiCall('/student/enrolled-courses');
                if (result && result.ok) {
                    enrolledCourses = result.data.data || [];
                    updateStats();
                    displayCourses(enrolledCourses);
                } else {
                    showErrorMessage('Failed to load your courses. Please try again later.');
                }
            } catch (error) {
                console.error('Error loading courses:', error);
                showErrorMessage('An error occurred while loading your courses.');
            }
        }

        // Update statistics
        function updateStats() {
            const totalCourses = enrolledCourses.length;
            const completedCourses = enrolledCourses.filter(course => course.progress >= 100).length;
            const inProgressCourses = totalCourses - completedCourses;
            const totalCredits = enrolledCourses.reduce((sum, course) => sum + (course.credit_hours || 3), 0);

            document.getElementById('total-courses').textContent = totalCourses;
            document.getElementById('completed-courses').textContent = completedCourses;
            document.getElementById('in-progress-courses').textContent = inProgressCourses;
            document.getElementById('total-credits').textContent = totalCredits;
        }

        // Load upcoming deadlines
        async function loadUpcomingDeadlines() {
            try {
                const result = await apiCall('/student/upcoming-assignments');
                const container = document.getElementById('upcoming-deadlines');

                if (result && result.ok) {
                    const assignments = result.data.data || [];

                    if (assignments.length > 0) {
                        container.innerHTML = assignments.slice(0, 3).map(assignment => {
                            const dueDate = new Date(assignment.due_date);
                            const isUrgent = (dueDate - new Date()) < (3 * 24 * 60 * 60 * 1000); // Less than 3 days

                            return `
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">${assignment.title}</h4>
                                        <p class="text-sm text-gray-600">${assignment.course_title}</p>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium ${isUrgent ? 'text-red-600' : 'text-gray-700'}">
                                            ${formatDueDate(assignment.due_date)}
                                        </div>
                                        ${isUrgent ? '<span class="text-xs text-red-500 font-medium">URGENT</span>' : ''}
                                    </div>
                                </div>
                            `;
                        }).join('');
                    } else {
                        container.innerHTML = `
                            <div class="text-center py-4">
                                <i class="fas fa-check-circle text-green-400 text-2xl mb-2"></i>
                                <p class="text-gray-600">No upcoming deadlines</p>
                            </div>
                        `;
                    }
                } else {
                    container.innerHTML = `
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle text-yellow-400 text-2xl mb-2"></i>
                            <p class="text-gray-600">Unable to load deadlines</p>
                        </div>
                    `;
                }
            } catch (error) {
                console.error('Error loading deadlines:', error);
                document.getElementById('upcoming-deadlines').innerHTML = `
                    <div class="text-center py-4">
                        <i class="fas fa-exclamation-triangle text-yellow-400 text-2xl mb-2"></i>
                        <p class="text-gray-600">Error loading deadlines</p>
                    </div>
                `;
            }
        }

        // Display courses
        function displayCourses(courses) {
            const container = document.getElementById('courses-container');

            if (courses.length === 0) {
                container.innerHTML = `
                    <div class="col-span-3 text-center py-12">
                        <i class="fas fa-book-open text-gray-300 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-700 mb-2">No courses found</h3>
                        <p class="text-gray-500 mb-4">You haven't enrolled in any courses yet.</p>
                        <a href="/student/course-enrollment" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium">
                            Browse Courses
                        </a>
                    </div>
                `;
                return;
            }

            container.innerHTML = courses.map(course => {
                // Use real progress data from API instead of mock data
                const progressPercentage = course.progress ? course.progress.percentage : 0;
                const progressColor = progressPercentage >= 100 ? 'bg-green-500' : progressPercentage >= 50 ? 'bg-blue-500' : 'bg-yellow-500';
                const statusBadge = progressPercentage >= 100 ?
                    '<span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full">Completed</span>' :
                    progressPercentage > 0 ?
                    '<span class="bg-blue-100 text-blue-800 text-xs font-medium px-2 py-1 rounded-full">In Progress</span>' :
                    '<span class="bg-gray-100 text-gray-800 text-xs font-medium px-2 py-1 rounded-full">Not Started</span>';

                // Calculate completion status text
                const completedItems = course.progress ? course.progress.completed_items : 0;
                const totalItems = course.progress ? course.progress.total_items : 0;
                const statusText = totalItems > 0 ? `${completedItems}/${totalItems} items completed` : 'No content available';
                const daysEnrolled = course.progress ? course.progress.days_enrolled : 0;

                return `
                    <div class="bg-white rounded-xl shadow-lg overflow-hidden card-hover">
                        <div class="h-48 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
                            <div class="absolute top-4 right-4 bg-white bg-opacity-90 text-indigo-600 text-xs font-bold px-2 py-1 rounded">
                                ${course.credit_hours || 3} Credits
                            </div>
                            <div class="absolute top-4 left-4">
                                ${statusBadge}
                            </div>
                            <div class="absolute bottom-4 left-4 text-white">
                                <div class="text-sm font-medium">${course.code}</div>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="font-bold text-gray-900 mb-2 text-lg">${course.title}</h3>
                            <p class="text-gray-600 text-sm mb-2 line-clamp-2">${course.description || 'Course description not available.'}</p>
                            <p class="text-xs text-gray-500 mb-4">${statusText}</p>

                            <!-- Progress Bar -->
                            <div class="mb-4">
                                <div class="flex justify-between text-sm text-gray-600 mb-1">
                                    <span>Progress</span>
                                    <span>${progressPercentage}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2">
                                    <div class="progress-bar ${progressColor} h-2 rounded-full" style="width: ${progressPercentage}%"></div>
                                </div>
                            </div>

                            <!-- Course Stats -->
                            <div class="mb-4 grid grid-cols-2 gap-2 text-xs text-gray-600">
                                <div class="flex items-center">
                                    <i class="fas fa-calendar-alt mr-1"></i>
                                    ${daysEnrolled} days enrolled
                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-tasks mr-1"></i>
                                    ${completedItems}/${totalItems} completed
                                </div>
                            </div>

                            <!-- Course Actions -->
                            <div class="flex items-center justify-between">
                                <div class="text-sm text-gray-500">
                                    <i class="fas fa-clock mr-1"></i>
                                    Last accessed: ${formatDate(course.updated_at)}
                                </div>
                                <div class="flex space-x-2">
                                    <a href="/student/courses/${course.id}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                        Continue
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Setup search functionality
        function setupSearch() {
            const searchInput = document.getElementById('search-input');
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const filteredCourses = enrolledCourses.filter(course => {
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
                btn.classList.remove('bg-indigo-600', 'text-white');
                btn.classList.add('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');
            });

            const activeBtn = document.querySelector(`[data-filter="${filterType}"]`);
            activeBtn.classList.add('bg-indigo-600', 'text-white');
            activeBtn.classList.remove('bg-gray-100', 'text-gray-700', 'hover:bg-gray-200');

            // Apply filter
            const filteredCourses = applyCurrentFilter(enrolledCourses);
            displayCourses(filteredCourses);
        }

        // Apply current filter to courses
        function applyCurrentFilter(courses) {
            switch(currentFilter) {
                case 'in-progress':
                    return courses.filter(course => {
                        const progress = course.progress ? course.progress.percentage : 0;
                        return progress > 0 && progress < 100;
                    });
                case 'completed':
                    return courses.filter(course => {
                        const progress = course.progress ? course.progress.percentage : 0;
                        return progress >= 100;
                    });
                default:
                    return courses;
            }
        }

        // Format date helper
        function formatDate(dateString) {
            if (!dateString) return 'Never';
            const date = new Date(dateString);
            const now = new Date();
            const diffTime = now - date;
            const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays === 0) {
                return 'Today';
            } else if (diffDays === 1) {
                return 'Yesterday';
            } else if (diffDays < 7) {
                return `${diffDays} days ago`;
            } else {
                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
            }
        }

        // Format due date helper
        function formatDueDate(dateString) {
            if (!dateString) return 'No due date';
            const date = new Date(dateString);
            const now = new Date();
            const diffTime = date - now;
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

            if (diffDays < 0) {
                return 'Overdue';
            } else if (diffDays === 0) {
                return 'Due today';
            } else if (diffDays === 1) {
                return 'Due tomorrow';
            } else if (diffDays < 7) {
                return `Due in ${diffDays} days`;
            } else {
                return `Due ${date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}`;
            }
        }

        // Show error message
        function showErrorMessage(message) {
            const container = document.getElementById('courses-container');
            container.innerHTML = `
                <div class="col-span-3 text-center py-12">
                    <i class="fas fa-exclamation-triangle text-red-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Error Loading Courses</h3>
                    <p class="text-gray-500 mb-4">${message}</p>
                    <button onclick="loadEnrolledCourses()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                        Try Again
                    </button>
                </div>
            `;
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

        // Make functions globally available
        window.filterCourses = filterCourses;
        window.logout = logout;
    </script>
</body>
</html>
