<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduLearn - Manager Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <!-- Check authentication status -->
    <script>
        // Function to check if user is authenticated
        function checkAuth() {
            // Check for token in multiple places
            const token = localStorage.getItem('token') ||
                          (document.cookie.split('; ').find(row => row.startsWith('token=')) || '').split('=')[1];

            // If no token found and not already on login page, redirect to login
            if (!token && !window.location.pathname.includes('/login')) {
                window.location.href = '/login?redirect=dashboard';
            }

            // Check if user is a manager
            const userRole = localStorage.getItem('role');
            if (token && userRole !== 'manager') {
                alert('You do not have permission to access the dashboard.');
                window.location.href = '/';
            }
        }

        // Run check immediately
        checkAuth();
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            transition: all 0.3s;
        }

        .main-content {
            margin-left: 250px;
            transition: all 0.3s;
        }

        @media (max-width: 768px) {
            .sidebar {
                margin-left: -250px;
            }

            .sidebar.active {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.active {
                margin-left: 250px;
            }
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="sidebar bg-indigo-800 text-white fixed h-full z-10">
            <div class="p-4 flex items-center border-b border-indigo-700">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998a12.078 12.078 0 01.665-6.479L12 14z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998a12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                </svg>
                <h1 class="ml-2 text-xl font-bold">EduLearn Admin</h1>
            </div>
            <nav class="mt-6">
                <a href="#dashboard" class="flex items-center py-3 px-4 bg-indigo-700 text-white">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="/course_admin" class="flex items-center py-3 px-4 hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-book mr-3"></i>
                    <span>Courses</span>
                </a>
                <a href="#users" class="flex items-center py-3 px-4 hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-users mr-3"></i>
                    <span>Users</span>
                </a>
                <a href="#enrollments" class="flex items-center py-3 px-4 hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-user-graduate mr-3"></i>
                    <span>Enrollments</span>
                </a>
                <a href="#reports" class="flex items-center py-3 px-4 hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-chart-bar mr-3"></i>
                    <span>Reports</span>
                </a>
                <a href="#settings" class="flex items-center py-3 px-4 hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-cog mr-3"></i>
                    <span>Settings</span>
                </a>
            </nav>
            <div class="absolute bottom-0 w-full p-4 border-t border-indigo-700">
                <a href="/logout" class="flex items-center text-white hover:text-indigo-200 transition duration-200">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-1 bg-gray-100 min-h-screen">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center py-4 px-6">
                    <div class="flex items-center">
                        <button id="sidebar-toggle" class="text-gray-500 focus:outline-none lg:hidden">
                            <i class="fas fa-bars"></i>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800 ml-4">Manager Dashboard</h2>
                    </div>
                    <div class="flex items-center">
                        <div class="relative">
                            <button class="flex items-center text-gray-700 focus:outline-none">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white">
                                    <span>{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                                <span class="ml-2">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down ml-2 text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Dashboard Content -->
            <div class="p-6">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="card bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                                <i class="fas fa-book text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-gray-500 text-sm">Total Courses</h3>
                                <p class="text-2xl font-semibold text-gray-800" id="total-courses">Loading...</p>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-users text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-gray-500 text-sm">Total Students</h3>
                                <p class="text-2xl font-semibold text-gray-800" id="total-students">Loading...</p>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-chalkboard-teacher text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-gray-500 text-sm">Total Instructors</h3>
                                <p class="text-2xl font-semibold text-gray-800" id="total-instructors">Loading...</p>
                            </div>
                        </div>
                    </div>

                    <div class="card bg-white rounded-lg shadow-md p-6">
                        <div class="flex items-center">
                            <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-user-graduate text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-gray-500 text-sm">Total Enrollments</h3>
                                <p class="text-2xl font-semibold text-gray-800" id="total-enrollments">Loading...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Course Management Section -->
                <div class="bg-white rounded-lg shadow-md mb-8">
                    <div class="flex justify-between items-center p-6 border-b">
                        <h3 class="text-xl font-semibold text-gray-800">Course Management</h3>
                        <button id="add-course-btn" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-200">
                            <i class="fas fa-plus mr-2"></i> Add New Course
                        </button>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                        <th class="py-3 px-6 text-left">Course</th>
                                        <th class="py-3 px-6 text-left">Code</th>
                                        <th class="py-3 px-6 text-left">Instructor</th>
                                        <th class="py-3 px-6 text-center">Credits</th>
                                        <th class="py-3 px-6 text-center">Status</th>
                                        <th class="py-3 px-6 text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 text-sm" id="courses-table-body">
                                    <tr>
                                        <td colspan="6" class="py-4 px-6 text-center">Loading courses...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Course Modal -->
    <div id="course-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-2xl">
            <div class="flex justify-between items-center p-6 border-b">
                <h3 class="text-xl font-semibold text-gray-800" id="modal-title">Add New Course</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-6">
                <form id="course-form">
                    <input type="hidden" id="course-id">
                    <div class="mb-4">
                        <label for="title" class="block text-gray-700 font-medium mb-2">Course Title</label>
                        <input type="text" id="title" name="title" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="code" class="block text-gray-700 font-medium mb-2">Course Code</label>
                            <input type="text" id="code" name="code" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                        <div>
                            <label for="credit_hours" class="block text-gray-700 font-medium mb-2">Credit Hours</label>
                            <input type="number" id="credit_hours" name="credit_hours" min="1" max="6" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="descri  ption" class="block text-gray-700 font-medium mb-2">Description</label>
                        <textarea id="description" name="description" rows="4" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required></textarea>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="instructor_id" class="block text-gray-700 font-medium mb-2">Instructor</label>
                            <select id="instructor_id" name="instructor_id" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                                <option value="">Select Instructor</option>
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
                            <select id="status" name="status" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="upcoming">Upcoming</option>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-end mt-6">
                        <button type="button" id="cancel-btn" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 mr-2 hover:bg-gray-100">Cancel</button>
                        <button type="submit" id="save-course-btn" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Store server-provided token if available
        @if(isset($auth_token))
            // Store in localStorage for future use
            localStorage.setItem('token', '{{ $auth_token }}');

            // Also set in cookie if not already there
            if (!document.cookie.includes('token=')) {
                document.cookie = `token={{ $auth_token }}; path=/; max-age=86400; SameSite=Strict`;
            }
        @endif

        document.addEventListener('DOMContentLoaded', function() {
            // Get token from multiple sources with priority
            const getToken = () => {
                // Try server-provided token first (from controller)
                @if(isset($auth_token))
                    let serverToken = '{{ $auth_token }}';
                    if (serverToken) return serverToken;
                @endif

                // Try localStorage next
                let token = localStorage.getItem('token');
                if (token) return token;

                // Try cookies last
                const tokenCookie = document.cookie.split('; ').find(row => row.startsWith('token='));
                if (tokenCookie) {
                    return tokenCookie.split('=')[1];
                }

                return null;
            };

            // Set up Authorization header for all fetch requests
            const fetchWithAuth = (url, options = {}) => {
                const token = getToken();
                const headers = {
                    ...(options.headers || {}),
                    'Accept': 'application/json'
                };

                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                return fetch(url, {
                    ...options,
                    headers
                });
            };

            // Toggle sidebar on mobile
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('active');
                    mainContent.classList.toggle('active');
                });
            }

            // Modal functionality
            const courseModal = document.getElementById('course-modal');
            const addCourseBtn = document.getElementById('add-course-btn');
            const closeModalBtn = document.getElementById('close-modal');
            const cancelBtn = document.getElementById('cancel-btn');

            function openModal() {
                courseModal.classList.remove('hidden');
            }

            function closeModal() {
                courseModal.classList.add('hidden');
                document.getElementById('course-form').reset();
                document.getElementById('course-id').value = '';
                document.getElementById('modal-title').textContent = 'Add New Course';
            }

            if (addCourseBtn) {
                addCourseBtn.addEventListener('click', openModal);
            }

            if (closeModalBtn) {
                closeModalBtn.addEventListener('click', closeModal);
            }

            if (cancelBtn) {
                cancelBtn.addEventListener('click', closeModal);
            }

            // Load dashboard data
            loadDashboardData();
            loadCourses();
            loadInstructors();

            // Course form submission
            const courseForm = document.getElementById('course-form');
            if (courseForm) {
                courseForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    saveCourse();
                });
            }
        });

        // Function to load dashboard data
        function loadDashboardData() {
            // Fetch stats data from API using our fetchWithAuth function
            fetchWithAuth('/api/dashboard/stats')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load dashboard data');
                }
                return response.json();
            })
            .then(data => {
                // Update dashboard stats
                document.getElementById('total-courses').textContent = data.courses || 0;
                document.getElementById('total-students').textContent = data.students || 0;
                document.getElementById('total-instructors').textContent = data.instructors || 0;
                document.getElementById('total-enrollments').textContent = data.enrollments || 0;
            })
            .catch(error => {
                console.error('Error loading dashboard data:', error);
                // Set default values if data loading fails
                document.getElementById('total-courses').textContent = '0';
                document.getElementById('total-students').textContent = '0';
                document.getElementById('total-instructors').textContent = '0';
                document.getElementById('total-enrollments').textContent = '0';
            });
        }

        // Function to load courses
        function loadCourses() {
            const tableBody = document.getElementById('courses-table-body');

            // Fetch courses from API using our fetchWithAuth function
            fetchWithAuth('/api/courses')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load courses');
                }
                return response.json();
            })
            .then(data => {
                if (data.data && data.data.length > 0) {
                    tableBody.innerHTML = '';

                    data.data.forEach(course => {
                        const row = document.createElement('tr');
                        row.className = 'border-b border-gray-200 hover:bg-gray-50';

                        row.innerHTML = `
                            <td class="py-3 px-6 text-left">
                                <div class="flex items-center">
                                    <div class="mr-2">
                                        <i class="fas fa-book text-indigo-600"></i>
                                    </div>
                                    <span>${course.title}</span>
                                </div>
                            </td>
                            <td class="py-3 px-6 text-left">${course.code}</td>
                            <td class="py-3 px-6 text-left">${course.instructor ? course.instructor.name : 'Not Assigned'}</td>
                            <td class="py-3 px-6 text-center">${course.credit_hours}</td>
                            <td class="py-3 px-6 text-center">
                                <span class="bg-${getStatusColor(course.status)}-100 text-${getStatusColor(course.status)}-800 py-1 px-3 rounded-full text-xs">
                                    ${capitalizeFirstLetter(course.status)}
                                </span>
                            </td>
                            <td class="py-3 px-6 text-center">
                                <div class="flex item-center justify-center">
                                    <button class="transform hover:text-indigo-600 hover:scale-110 mr-3" onclick="viewCourse(${course.id})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="transform hover:text-blue-600 hover:scale-110 mr-3" onclick="editCourse(${course.id})">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="transform hover:text-red-600 hover:scale-110" onclick="deleteCourse(${course.id})">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </td>
                        `;

                        tableBody.appendChild(row);
                    });
                } else {
                    tableBody.innerHTML = `
                        <tr>
                            <td colspan="6" class="py-4 px-6 text-center">No courses found</td>
                        </tr>
                    `;
                }
            })
            .catch(error => {
                console.error('Error loading courses:', error);
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="py-4 px-6 text-center text-red-600">Failed to load courses</td>
                    </tr>
                `;
            });
        }

        // Function to load instructors for the dropdown
        function loadInstructors() {
            const instructorSelect = document.getElementById('instructor_id');

            // Fetch instructors from API using our fetchWithAuth function
            fetchWithAuth('/api/users?role=instructor')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load instructors');
                }
                return response.json();
            })
            .then(data => {
                if (data.data && data.data.length > 0) {
                    // Clear existing options except the first one
                    while (instructorSelect.options.length > 1) {
                        instructorSelect.remove(1);
                    }

                    // Add instructor options
                    data.data.forEach(instructor => {
                        const option = document.createElement('option');
                        option.value = instructor.id;
                        option.textContent = instructor.name;
                        instructorSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading instructors:', error);
            });
        }

        // Helper functions
        function getStatusColor(status) {
            switch (status) {
                case 'active':
                    return 'green';
                case 'inactive':
                    return 'red';
                case 'upcoming':
                    return 'blue';
                default:
                    return 'gray';
            }
        }

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }

        // Course CRUD functions
        function viewCourse(id) {
            window.location.href = `/courses/${id}`;
        }

        function editCourse(id) {
            // Fetch course details using our fetchWithAuth function
            fetchWithAuth(`/api/courses/${id}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load course details');
                }
                return response.json();
            })
            .then(data => {
                if (data.data) {
                    const course = data.data;

                    // Set form values
                    document.getElementById('course-id').value = course.id;
                    document.getElementById('title').value = course.title;
                    document.getElementById('code').value = course.code;
                    document.getElementById('credit_hours').value = course.credit_hours;
                    document.getElementById('description').value = course.description;
                    document.getElementById('status').value = course.status;

                    if (course.instructor) {
                        document.getElementById('instructor_id').value = course.instructor.id;
                    }

                    // Update modal title
                    document.getElementById('modal-title').textContent = 'Edit Course';

                    // Open modal
                    document.getElementById('course-modal').classList.remove('hidden');
                }
            })
            .catch(error => {
                console.error('Error loading course details:', error);
                alert('Failed to load course details');
            });
        }

        function deleteCourse(id) {
            if (confirm('Are you sure you want to delete this course?')) {
                // Delete course via API using our fetchWithAuth function
                fetchWithAuth(`/api/courses/${id}`, {
                    method: 'DELETE'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to delete course');
                    }
                    return response.json();
                })
                .then(data => {
                    alert('Course deleted successfully');
                    loadCourses(); // Reload courses table
                    loadDashboardData(); // Update dashboard stats
                })
                .catch(error => {
                    console.error('Error deleting course:', error);
                    alert('Failed to delete course');
                });
            }
        }

        function saveCourse() {
            const courseId = document.getElementById('course-id').value;
            const isEdit = courseId !== '';

            // Prepare form data
            const formData = {
                title: document.getElementById('title').value,
                code: document.getElementById('code').value,
                credit_hours: document.getElementById('credit_hours').value,
                description: document.getElementById('description').value,
                instructor_id: document.getElementById('instructor_id').value,
                status: document.getElementById('status').value
            };

            // API endpoint and method
            const url = isEdit ? `/api/courses/${courseId}` : '/api/courses';
            const method = isEdit ? 'PUT' : 'POST';

            // Save course via API using our fetchWithAuth function
            fetchWithAuth(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(formData)
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to save course');
                }
                return response.json();
            })
            .then(data => {
                alert(isEdit ? 'Course updated successfully' : 'Course created successfully');
                document.getElementById('course-modal').classList.add('hidden');
                document.getElementById('course-form').reset();
                loadCourses(); // Reload courses table
                loadDashboardData(); // Update dashboard stats
            })
            .catch(error => {
                console.error('Error saving course:', error);
                alert('Failed to save course');
            });
        }
    </script>
</body>
</html>
