<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Management - Educational Platform</title>
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
                        <a href="/manager/courses" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                    <h2 class="text-3xl font-bold mb-2">Course Management</h2>
                    <p class="text-indigo-100 text-lg">Create, manage, and oversee all courses in the platform</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-book text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Controls -->
        <div class="manager-card rounded-xl shadow-lg p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                    <div class="relative">
                        <input type="text" id="search-input" placeholder="Search courses..."
                               class="search-input pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <select id="filter-instructor" class="search-input px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">All Instructors</option>
                    </select>
                    <select id="filter-status" class="search-input px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="flex space-x-3">
                    <button onclick="showCreateCourseModal()" class="action-button text-white px-6 py-2 rounded-lg hover:shadow-lg transition-all">
                        <i class="fas fa-plus mr-2"></i>Create Course
                    </button>
                    <button onclick="refreshCourses()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-all">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Courses Grid -->
        <div id="courses-container" class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6">
            <!-- Loading skeletons -->
            <div class="skeleton-course">
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
                <i class="fas fa-book text-6xl text-gray-300 mb-6"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No courses found</h3>
                <p class="text-gray-500 mb-6">Get started by creating your first course</p>
                <button onclick="showCreateCourseModal()" class="action-button text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all">
                    <i class="fas fa-plus mr-2"></i>Create First Course
                </button>
            </div>
        </div>
    </div>

    <!-- Create/Edit Course Modal -->
    <div id="courseModal" class="fixed inset-0 bg-black bg-opacity-50 modal hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="manager-card rounded-xl shadow-2xl max-w-2xl w-full max-h-screen overflow-y-auto">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900" id="modal-title">Create New Course</h3>
                        <button onclick="hideCourseModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <form id="courseForm" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="course-title" class="block text-sm font-medium text-gray-700 mb-2">Course Title *</label>
                                <input type="text" id="course-title" name="title" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="course-code" class="block text-sm font-medium text-gray-700 mb-2">Course Code *</label>
                                <input type="text" id="course-code" name="code" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label for="course-description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                            <textarea id="course-description" name="description" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="course-credits" class="block text-sm font-medium text-gray-700 mb-2">Credit Hours</label>
                                <input type="number" id="course-credits" name="credit_hours" min="1" max="6" value="3"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="course-capacity" class="block text-sm font-medium text-gray-700 mb-2">Max Capacity</label>
                                <input type="number" id="course-capacity" name="max_capacity" min="1" value="30"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            </div>
                            <div>
                                <label for="course-instructor" class="block text-sm font-medium text-gray-700 mb-2">Assign Instructor</label>
                                <select id="course-instructor" name="instructor_id"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    <option value="">Select Instructor</option>
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200">
                            <button type="button" onclick="hideCourseModal()"
                                    class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="action-button text-white px-6 py-2 rounded-lg hover:shadow-lg transition-all">
                                <i class="fas fa-save mr-2"></i>Save Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Assign Instructor Modal -->
    <div id="assignInstructorModal" class="fixed inset-0 bg-black bg-opacity-50 modal hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="manager-card rounded-xl shadow-2xl max-w-md w-full">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-gray-900">Assign Instructor</h3>
                        <button onclick="hideAssignInstructorModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>

                    <form id="assignInstructorForm" class="space-y-4">
                        <div>
                            <label for="assign-instructor-select" class="block text-sm font-medium text-gray-700 mb-2">Select Instructor</label>
                            <select id="assign-instructor-select" name="instructor_id" required
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                <option value="">Choose an instructor...</option>
                            </select>
                        </div>

                        <div class="flex justify-end space-x-4 pt-4 border-t border-gray-200">
                            <button type="button" onclick="hideAssignInstructorModal()"
                                    class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-all">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="action-button text-white px-4 py-2 rounded-lg hover:shadow-lg transition-all">
                                <i class="fas fa-user-plus mr-2"></i>Assign
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let authToken = localStorage.getItem('token');
        let allCourses = [];
        let allInstructors = [];
        let filteredCourses = [];
        let currentCourseId = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken) {
                window.location.href = '/login';
                return;
            }

            loadUserProfile();
            loadCourses();
            loadInstructors();
            setupEventListeners();
            handleUrlParameters();
        });

        // Setup event listeners
        function setupEventListeners() {
            // Search and filter
            document.getElementById('search-input').addEventListener('input', filterCourses);
            document.getElementById('filter-instructor').addEventListener('change', filterCourses);
            document.getElementById('filter-status').addEventListener('change', filterCourses);

            // Form submissions
            document.getElementById('courseForm').addEventListener('submit', handleCourseSubmit);
            document.getElementById('assignInstructorForm').addEventListener('submit', handleAssignInstructor);
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

        // Load courses
        async function loadCourses() {
            try {
                const result = await apiCall('/manager/courses');
                if (result && result.ok) {
                    allCourses = result.data.data || [];
                    filteredCourses = [...allCourses];
                    displayCourses();
                    populateInstructorFilter();
                } else {
                    console.error('Failed to load courses:', result);
                    showEmptyState();
                }
            } catch (error) {
                console.error('Error loading courses:', error);
                showEmptyState();
            }
        }

        // Load instructors
        async function loadInstructors() {
            try {
                const result = await apiCall('/users?role=instructor');
                if (result && result.ok) {
                    allInstructors = result.data.data || [];
                    populateInstructorSelects();
                } else {
                    console.error('Failed to load instructors:', result);
                }
            } catch (error) {
                console.error('Error loading instructors:', error);
            }
        }

        // Display courses
        function displayCourses() {
            const container = document.getElementById('courses-container');
            const emptyState = document.getElementById('empty-state');

            if (filteredCourses.length === 0) {
                container.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');
            container.innerHTML = filteredCourses.map(course => `
                <div class="manager-card rounded-xl shadow-lg p-6 card-hover">
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-bold text-gray-900 mb-2">${course.title}</h3>
                            <p class="text-sm text-indigo-600 font-medium mb-2">${course.code}</p>
                            <p class="text-gray-600 text-sm mb-3">${course.description || 'No description available'}</p>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editCourse(${course.id})" class="text-indigo-600 hover:text-indigo-800 p-2 rounded-lg hover:bg-indigo-50 transition-all">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="showAssignInstructorModal(${course.id})" class="text-green-600 hover:text-green-800 p-2 rounded-lg hover:bg-green-50 transition-all">
                                <i class="fas fa-user-plus"></i>
                            </button>
                            <button onclick="deleteCourse(${course.id})" class="text-red-600 hover:text-red-800 p-2 rounded-lg hover:bg-red-50 transition-all">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-xs text-gray-500 mb-1">Enrolled Students</div>
                            <div class="text-lg font-bold text-gray-900">${course.students_count || course.total_students || 0}</div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="text-xs text-gray-500 mb-1">Credit Hours</div>
                            <div class="text-lg font-bold text-gray-900">${course.credit_hours || 3}</div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-chalkboard-teacher text-indigo-600 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">${course.instructor?.name || 'No instructor assigned'}</div>
                                <div class="text-xs text-gray-500">${course.instructor?.email || ''}</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                            <span><i class="fas fa-video mr-1"></i> ${course.lectures_count || 0}</span>
                            <span><i class="fas fa-tasks mr-1"></i> ${course.assignments_count || 0}</span>
                            <span><i class="fas fa-question-circle mr-1"></i> ${course.quizzes_count || 0}</span>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Filter courses
        function filterCourses() {
            const searchTerm = document.getElementById('search-input').value.toLowerCase();
            const instructorFilter = document.getElementById('filter-instructor').value;
            const statusFilter = document.getElementById('filter-status').value;

            filteredCourses = allCourses.filter(course => {
                const matchesSearch = course.title.toLowerCase().includes(searchTerm) ||
                                    course.code.toLowerCase().includes(searchTerm) ||
                                    (course.description && course.description.toLowerCase().includes(searchTerm));

                const matchesInstructor = !instructorFilter ||
                                        (course.instructor && course.instructor.id.toString() === instructorFilter);

                const matchesStatus = !statusFilter ||
                                    (statusFilter === 'active' && course.instructor) ||
                                    (statusFilter === 'inactive' && !course.instructor);

                return matchesSearch && matchesInstructor && matchesStatus;
            });

            displayCourses();
        }

        // Populate instructor filter and selects
        function populateInstructorFilter() {
            const filterSelect = document.getElementById('filter-instructor');
            const assignedInstructors = [...new Set(allCourses
                .filter(course => course.instructor)
                .map(course => course.instructor))];

            filterSelect.innerHTML = '<option value="">All Instructors</option>';
            assignedInstructors.forEach(instructor => {
                const option = document.createElement('option');
                option.value = instructor.id;
                option.textContent = instructor.name;
                filterSelect.appendChild(option);
            });
        }

        function populateInstructorSelects() {
            const selects = ['course-instructor', 'assign-instructor-select'];

            selects.forEach(selectId => {
                const select = document.getElementById(selectId);
                const currentOptions = select.innerHTML;

                select.innerHTML = selectId === 'course-instructor' ?
                    '<option value="">Select Instructor</option>' :
                    '<option value="">Choose an instructor...</option>';

                allInstructors.forEach(instructor => {
                    const option = document.createElement('option');
                    option.value = instructor.id;
                    option.textContent = `${instructor.name} (${instructor.email})`;
                    select.appendChild(option);
                });
            });
        }

        // Show empty state
        function showEmptyState() {
            document.getElementById('courses-container').innerHTML = '';
            document.getElementById('empty-state').classList.remove('hidden');
        }

        // Modal functions
        function showCreateCourseModal() {
            currentCourseId = null;
            document.getElementById('modal-title').textContent = 'Create New Course';
            document.getElementById('courseForm').reset();
            document.getElementById('courseModal').classList.remove('hidden');
        }

        function hideCourseModal() {
            document.getElementById('courseModal').classList.add('hidden');
            currentCourseId = null;
        }

        function showAssignInstructorModal(courseId) {
            currentCourseId = courseId;
            document.getElementById('assignInstructorForm').reset();
            document.getElementById('assignInstructorModal').classList.remove('hidden');
        }

        function hideAssignInstructorModal() {
            document.getElementById('assignInstructorModal').classList.add('hidden');
            currentCourseId = null;
        }

        // Course operations
        async function handleCourseSubmit(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            const courseData = {
                title: formData.get('title'),
                code: formData.get('code'),
                description: formData.get('description'),
                credit_hours: parseInt(formData.get('credit_hours')) || 3,
                max_capacity: parseInt(formData.get('max_capacity')) || 30,
                instructor_id: formData.get('instructor_id') || null
            };

            // Debug: Log the data being sent
            console.log('Course data being sent:', courseData);

            try {
                const endpoint = currentCourseId ? `/courses/${currentCourseId}` : '/courses';
                const method = currentCourseId ? 'PUT' : 'POST';

                console.log(`Making ${method} request to ${endpoint}`);
                const result = await apiCall(endpoint, method, courseData);

                if (result && result.ok) {
                    alert(currentCourseId ? 'Course updated successfully!' : 'Course created successfully!');
                    hideCourseModal();
                    loadCourses();
                } else {
                    console.error('Course save failed:', result);
                    let errorMessage = 'Failed to save course: ' + (result.data.message || 'Unknown error');

                    // Show detailed validation errors
                    if (result.data && result.data.errors) {
                        const errors = Object.entries(result.data.errors).map(([field, messages]) =>
                            `${field}: ${Array.isArray(messages) ? messages.join(', ') : messages}`
                        ).join('\n');
                        errorMessage += '\n\nValidation Errors:\n' + errors;
                    }

                    alert(errorMessage);
                }
            } catch (error) {
                console.error('Error saving course:', error);
                alert('Failed to save course');
            }
        }

        async function handleAssignInstructor(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            const instructorId = formData.get('instructor_id');

            if (!instructorId) {
                alert('Please select an instructor');
                return;
            }

            try {
                const result = await apiCall(`/courses/${currentCourseId}/assign-instructor`, 'POST', {
                    instructor_id: instructorId
                });

                if (result && result.ok) {
                    alert('Instructor assigned successfully!');
                    hideAssignInstructorModal();
                    loadCourses();
                } else {
                    alert('Failed to assign instructor: ' + (result.data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error assigning instructor:', error);
                alert('Failed to assign instructor');
            }
        }

        function editCourse(courseId) {
            const course = allCourses.find(c => c.id === courseId);
            if (!course) return;

            currentCourseId = courseId;
            document.getElementById('modal-title').textContent = 'Edit Course';

            // Populate form
            document.getElementById('course-title').value = course.title;
            document.getElementById('course-code').value = course.code;
            document.getElementById('course-description').value = course.description || '';
            document.getElementById('course-credits').value = course.credit_hours || 3;
            document.getElementById('course-capacity').value = course.max_capacity || 30;
            document.getElementById('course-instructor').value = course.instructor?.id || '';

            document.getElementById('courseModal').classList.remove('hidden');
        }

        async function deleteCourse(courseId) {
            if (!confirm('Are you sure you want to delete this course? This action cannot be undone.')) {
                return;
            }

            try {
                const result = await apiCall(`/courses/${courseId}`, 'DELETE');

                if (result && result.ok) {
                    alert('Course deleted successfully!');
                    loadCourses();
                } else {
                    alert('Failed to delete course: ' + (result.data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error deleting course:', error);
                alert('Failed to delete course');
            }
        }

        function refreshCourses() {
            loadCourses();
        }

        // Handle URL parameters for direct actions
        function handleUrlParameters() {
            const urlParams = new URLSearchParams(window.location.search);
            const editId = urlParams.get('edit');
            const assignId = urlParams.get('assign');

            if (editId) {
                // Wait for courses to load, then open edit modal
                setTimeout(() => {
                    const course = allCourses.find(c => c.id == editId);
                    if (course) {
                        editCourse(course);
                    }
                }, 1000);
            } else if (assignId) {
                // Wait for courses to load, then open assign modal
                setTimeout(() => {
                    const course = allCourses.find(c => c.id == assignId);
                    if (course) {
                        assignInstructor(course.id);
                    }
                }, 1000);
            }
        }

        // Enhanced create course function
        async function createCourse() {
            const formData = {
                title: document.getElementById('course-title').value,
                code: document.getElementById('course-code').value,
                description: document.getElementById('course-description').value,
                credit_hours: parseInt(document.getElementById('course-credits').value) || 3,
                max_capacity: parseInt(document.getElementById('course-capacity').value) || 30,
                instructor_id: document.getElementById('course-instructor').value || null
            };

            // Validation
            if (!formData.title || !formData.code) {
                alert('Please fill in all required fields (Title and Code)');
                return;
            }

            try {
                const result = await apiCall('/courses', 'POST', formData);

                if (result && result.ok) {
                    alert('Course created successfully!');
                    hideCourseModal();
                    loadCourses(); // Refresh the list

                    // Clear form
                    document.getElementById('course-form').reset();
                } else {
                    const errorMessage = result?.data?.message || 'Failed to create course';
                    alert(errorMessage);

                    // Show validation errors if available
                    if (result?.data?.errors) {
                        const errors = Object.values(result.data.errors).flat();
                        alert('Validation errors:\n' + errors.join('\n'));
                    }
                }
            } catch (error) {
                console.error('Error creating course:', error);
                alert('Error creating course. Please try again.');
            }
        }

        // Enhanced update course function
        async function updateCourse() {
            if (!currentCourseId) return;

            const formData = {
                title: document.getElementById('course-title').value,
                code: document.getElementById('course-code').value,
                description: document.getElementById('course-description').value,
                credit_hours: parseInt(document.getElementById('course-credits').value) || 3,
                max_capacity: parseInt(document.getElementById('course-capacity').value) || 30,
                instructor_id: document.getElementById('course-instructor').value || null
            };

            // Validation
            if (!formData.title || !formData.code) {
                alert('Please fill in all required fields (Title and Code)');
                return;
            }

            try {
                const result = await apiCall(`/courses/${currentCourseId}`, 'PUT', formData);

                if (result && result.ok) {
                    alert('Course updated successfully!');
                    hideCourseModal();
                    loadCourses(); // Refresh the list
                    currentCourseId = null;
                } else {
                    const errorMessage = result?.data?.message || 'Failed to update course';
                    alert(errorMessage);

                    // Show validation errors if available
                    if (result?.data?.errors) {
                        const errors = Object.values(result.data.errors).flat();
                        alert('Validation errors:\n' + errors.join('\n'));
                    }
                }
            } catch (error) {
                console.error('Error updating course:', error);
                alert('Error updating course. Please try again.');
            }
        }

        // Save course (create or update)
        function saveCourse() {
            if (currentCourseId) {
                updateCourse();
            } else {
                createCourse();
            }
        }

        // Enhanced assign instructor function
        async function saveInstructorAssignment() {
            if (!currentCourseId) return;

            const instructorId = document.getElementById('assign-instructor-select').value;

            if (!instructorId) {
                alert('Please select an instructor');
                return;
            }

            try {
                const result = await apiCall(`/courses/${currentCourseId}/assign-instructor`, 'POST', {
                    instructor_id: instructorId
                });

                if (result && result.ok) {
                    alert('Instructor assigned successfully!');
                    hideAssignModal();
                    loadCourses(); // Refresh the list
                    currentCourseId = null;
                } else {
                    const errorMessage = result?.data?.message || 'Failed to assign instructor';
                    alert(errorMessage);
                }
            } catch (error) {
                console.error('Error assigning instructor:', error);
                alert('Error assigning instructor. Please try again.');
            }
        }

        // Clear URL parameters after handling
        function clearUrlParameters() {
            const url = new URL(window.location);
            url.search = '';
            window.history.replaceState({}, document.title, url);
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