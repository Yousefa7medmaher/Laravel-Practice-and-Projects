<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduLearn - Course Administration</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
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
    </style>
</head>
<body class="bg-gray-100">
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
                <a href="/dashboard" class="flex items-center py-3 px-4 hover:bg-indigo-700 transition duration-200">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Dashboard</span>
                </a>
                <a href="/course_admin" class="flex items-center py-3 px-4 bg-indigo-700 text-white">
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
                <a href="/" class="flex items-center text-white hover:text-indigo-200 transition duration-200 mb-3">
                    <i class="fas fa-home mr-3"></i>
                    <span>Home</span>
                </a>
                <a href="#" id="logout-link" class="flex items-center text-white hover:text-indigo-200 transition duration-200">
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
                        <h2 class="text-xl font-semibold text-gray-800 ml-4">Course Administration</h2>
                    </div>
                    <div class="flex items-center">
                        <button id="add-course-btn" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 mr-4">
                            <i class="fas fa-plus mr-2"></i> Add New Course
                        </button>
                        <div class="relative" id="user-profile">
                            <!-- User profile will be populated by JavaScript -->
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <div class="p-6">

        <!-- Course List -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="mb-4 flex justify-between items-center">
                <input type="text" id="course-search" placeholder="Search courses..."
                       class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <select id="status-filter" class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">All Statuses</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="archived">Archived</option>
                </select>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                            <th class="py-3 px-6 text-left">Title</th>
                            <th class="py-3 px-6 text-left">Code</th>
                            <th class="py-3 px-6 text-center">Credits</th>
                            <th class="py-3 px-6 text-center">Status</th>
                            <th class="py-3 px-6 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="courses-table-body">
                        <!-- Course data will be loaded here -->
                    </tbody>
                </table>
            </div>

            <div id="pagination" class="mt-4 flex justify-center">
                <!-- Pagination controls will be added here -->
            </div>
        </div>
    </div>

    <!-- Course Modal -->
    <div id="course-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold text-gray-800" id="modal-title">Add New Course</h3>
                <button id="close-modal" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="course-form" enctype="multipart/form-data">
                <input type="hidden" id="course-id">
                <div class="mb-4">
                    <label for="title" class="block text-gray-700 font-medium mb-2">Course Title</label>
                    <input type="text" id="title" name="title" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="code" class="block text-gray-700 font-medium mb-2">Course Code</label>
                    <input type="text" id="code" name="code" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
                    <textarea id="description" name="description" rows="3" class="w-full px-4 py-2 border rounded-lg" required></textarea>
                </div>
                <div class="mb-4">
                    <label for="credit_hours" class="block text-gray-700 font-medium mb-2">Credit Hours</label>
                    <input type="number" id="credit_hours" name="credit_hours" min="1" max="6" class="w-full px-4 py-2 border rounded-lg" required>
                </div>
                <div class="mb-4">
                    <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
                    <select id="status" name="status" class="w-full px-4 py-2 border rounded-lg" required>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label for="image" class="block text-gray-700 font-medium mb-2">Course Image</label>
                    <input type="file" id="image" name="image" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
                    <p class="text-sm text-gray-500 mt-1">Recommended size: 1200x600 pixels. Max size: 2MB.</p>

                    <!-- Image preview container -->
                    <div id="image-preview-container" class="mt-3 hidden">
                        <p class="text-sm font-medium text-gray-700 mb-1">Image Preview:</p>
                        <div class="relative">
                            <img id="image-preview" src="#" alt="Course image preview" class="max-h-40 rounded-lg border">
                            <button type="button" id="remove-image" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Current image display (for edit mode) -->
                    <div id="current-image-container" class="mt-3 hidden">
                        <p class="text-sm font-medium text-gray-700 mb-1">Current Image:</p>
                        <div class="relative">
                            <img id="current-image" src="#" alt="Current course image" class="max-h-40 rounded-lg border">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-6">
                    <button type="button" id="cancel-btn" class="px-4 py-2 border rounded-md text-gray-700 mr-2">Cancel</button>
                    <button type="submit" id="save-course-btn" class="px-4 py-2 bg-blue-600 text-white rounded-md">Save Course</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Confirm Delete</h3>
            <p class="mb-6">Are you sure you want to delete this course? This action cannot be undone.</p>
            <div class="flex justify-end">
                <button id="cancel-delete" class="px-4 py-2 border rounded-md text-gray-700 mr-2">Cancel</button>
                <button id="confirm-delete" class="px-4 py-2 bg-red-600 text-white rounded-md">Delete</button>
            </div>
        </div>
    </div>

    <!-- Course Modal Template (hidden, used for restoring modal content) -->
    <template id="course-modal-template">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-800" id="modal-title">Add New Course</h3>
            <button id="close-modal" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form id="course-form" enctype="multipart/form-data">
            <input type="hidden" id="course-id">
            <div class="mb-4">
                <label for="title" class="block text-gray-700 font-medium mb-2">Course Title</label>
                <input type="text" id="title" name="title" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="code" class="block text-gray-700 font-medium mb-2">Course Code</label>
                <input type="text" id="code" name="code" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700 font-medium mb-2">Description</label>
                <textarea id="description" name="description" rows="3" class="w-full px-4 py-2 border rounded-lg" required></textarea>
            </div>
            <div class="mb-4">
                <label for="credit_hours" class="block text-gray-700 font-medium mb-2">Credit Hours</label>
                <input type="number" id="credit_hours" name="credit_hours" min="1" max="6" class="w-full px-4 py-2 border rounded-lg" required>
            </div>
            <div class="mb-4">
                <label for="status" class="block text-gray-700 font-medium mb-2">Status</label>
                <select id="status" name="status" class="w-full px-4 py-2 border rounded-lg" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="archived">Archived</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="image" class="block text-gray-700 font-medium mb-2">Course Image</label>
                <input type="file" id="image" name="image" accept="image/*" class="w-full px-4 py-2 border rounded-lg">
                <p class="text-sm text-gray-500 mt-1">Recommended size: 1200x600 pixels. Max size: 2MB.</p>

                <!-- Image preview container -->
                <div id="image-preview-container" class="mt-3 hidden">
                    <p class="text-sm font-medium text-gray-700 mb-1">Image Preview:</p>
                    <div class="relative">
                        <img id="image-preview" src="#" alt="Course image preview" class="max-h-40 rounded-lg border">
                        <button type="button" id="remove-image" class="absolute top-2 right-2 bg-red-500 text-white rounded-full p-1 hover:bg-red-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                <!-- Current image display (for edit mode) -->
                <div id="current-image-container" class="mt-3 hidden">
                    <p class="text-sm font-medium text-gray-700 mb-1">Current Image:</p>
                    <div class="relative">
                        <img id="current-image" src="#" alt="Current course image" class="max-h-40 rounded-lg border">
                    </div>
                </div>
            </div>
            <div class="flex justify-end mt-6">
                <button type="button" id="cancel-btn" class="px-4 py-2 border rounded-md text-gray-700 mr-2">Cancel</button>
                <button type="submit" id="save-course-btn" class="px-4 py-2 bg-blue-600 text-white rounded-md">Save Course</button>
            </div>
        </form>
    </template>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auth token handling
            function getToken() {
                return localStorage.getItem('token') ||
                       (document.cookie.split('; ').find(row => row.startsWith('token=')) || '').split('=')[1];
            }

            // Fetch with auth
            function fetchWithAuth(url, options = {}) {
                const token = getToken();
                const headers = {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    ...(options.headers || {})
                };

                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                return fetch(url, {
                    ...options,
                    headers: headers
                });
            }

            // Check auth on page load
            function checkAuth() {
                const token = getToken();
                if (!token) {
                    window.location.href = '/login?redirect=course_admin';
                    return;
                }

                // Check if role is stored in localStorage first
                const storedRole = localStorage.getItem('role');
                if (storedRole && ['manager', 'instructor'].includes(storedRole)) {
                    // Role is valid, proceed with loading the page
                    console.log('User authenticated with role:', storedRole);
                    return;
                }

                // If no valid role in localStorage, verify token and get role from API
                fetchWithAuth('/api/profile')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Invalid token');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Profile data:', data);
                        // Check if user has manager or instructor role
                        // First check if role is in user object
                        const userRole = data.user?.role || data.role;

                        if (!['manager', 'instructor'].includes(userRole)) {
                            alert('You do not have permission to access this page');
                            window.location.href = '/';
                        } else {
                            // Store the role in localStorage for future checks
                            localStorage.setItem('role', userRole);
                        }
                    })
                    .catch((error) => {
                        console.error('Authentication error:', error);
                        localStorage.removeItem('token');
                        window.location.href = '/login?redirect=course_admin';
                    });
            }

            // Load courses from API
            function loadCourses(page = 1, search = '', status = '') {
                const tableBody = document.getElementById('courses-table-body');
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-8">
                            <div class="flex justify-center items-center">
                                <i class="fas fa-spinner fa-spin text-indigo-600 text-2xl mr-3"></i>
                                <span class="text-gray-600">Loading courses...</span>
                            </div>
                        </td>
                    </tr>
                `;

                let url = `/api/courses?page=${page}`;
                if (search) url += `&search=${encodeURIComponent(search)}`;
                if (status) url += `&status=${encodeURIComponent(status)}`;

                fetchWithAuth(url)
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
                                row.className = 'border-b hover:bg-gray-50';
                                row.innerHTML = `
                                    <td class="py-3 px-6 text-left">
                                        <div class="flex items-center">
                                            ${course.image_path ?
                                                `<img src="/${course.image_path}" alt="${course.title}" class="h-10 w-10 rounded-md object-cover mr-3">` :
                                                `<div class="h-10 w-10 rounded-md bg-gray-200 flex items-center justify-center mr-3">
                                                    <i class="fas fa-book text-gray-400"></i>
                                                </div>`
                                            }
                                            <span class="font-medium">${course.title}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-6 text-left">${course.code}</td>
                                    <td class="py-3 px-6 text-center">${course.credit_hours || '-'}</td>
                                    <td class="py-3 px-6 text-center">
                                        <span class="px-2 py-1 rounded-full text-xs ${getStatusClass(course.status)}">
                                            ${course.status || 'active'}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-center">
                                        <div class="flex item-center justify-center">
                                            <button class="edit-btn mr-3 transform hover:text-blue-500 hover:scale-110"
                                                    data-id="${course.id}" title="Edit course">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="delete-btn transform hover:text-red-500 hover:scale-110"
                                                    data-id="${course.id}" title="Delete course">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </div>
                                    </td>
                                `;
                                tableBody.appendChild(row);
                            });

                            // Add event listeners to edit and delete buttons
                            document.querySelectorAll('.edit-btn').forEach(btn => {
                                btn.addEventListener('click', () => editCourse(btn.dataset.id));
                            });

                            document.querySelectorAll('.delete-btn').forEach(btn => {
                                btn.addEventListener('click', () => showDeleteModal(btn.dataset.id));
                            });

                            // Update pagination
                            updatePagination(data.meta);
                        } else {
                            // No courses found
                            tableBody.innerHTML = `
                                <tr>
                                    <td colspan="5" class="text-center py-8">
                                        <div class="flex flex-col items-center justify-center text-gray-500">
                                            <i class="fas fa-search text-4xl mb-3"></i>
                                            <p class="text-lg">No courses found</p>
                                            ${search || status ?
                                                `<p class="text-sm mt-1">Try adjusting your search or filter criteria</p>` :
                                                `<p class="text-sm mt-1">Click "Add New Course" to create your first course</p>`
                                            }
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }
                    })
                    .catch(error => {
                        console.error('Error loading courses:', error);
                        tableBody.innerHTML = `
                            <tr>
                                <td colspan="5" class="text-center py-8">
                                    <div class="flex flex-col items-center justify-center text-red-500">
                                        <i class="fas fa-exclamation-circle text-4xl mb-3"></i>
                                        <p class="text-lg">Error loading courses</p>
                                        <p class="text-sm mt-1">Please try again later or contact support</p>
                                        <button id="retry-load" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                            <i class="fas fa-sync-alt mr-2"></i> Retry
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        `;

                        // Add event listener to retry button
                        document.getElementById('retry-load')?.addEventListener('click', () => {
                            loadCourses(page, search, status);
                        });
                    });
            }

            // Get status class for styling
            function getStatusClass(status) {
                switch(status) {
                    case 'active': return 'bg-green-100 text-green-800';
                    case 'inactive': return 'bg-yellow-100 text-yellow-800';
                    case 'archived': return 'bg-gray-100 text-gray-800';
                    default: return 'bg-blue-100 text-blue-800';
                }
            }

            // Update pagination controls
            function updatePagination(meta) {
                if (!meta) return;

                const pagination = document.getElementById('pagination');
                pagination.innerHTML = '';

                if (meta.last_page > 1) {
                    // Previous button
                    const prevBtn = document.createElement('button');
                    prevBtn.className = `mx-1 px-3 py-1 rounded ${meta.current_page === 1 ? 'bg-gray-200 cursor-not-allowed' : 'bg-white border hover:bg-gray-50'}`;
                    prevBtn.innerHTML = '&laquo;';
                    prevBtn.disabled = meta.current_page === 1;
                    prevBtn.addEventListener('click', () => loadCourses(meta.current_page - 1));
                    pagination.appendChild(prevBtn);

                    // Page numbers
                    for (let i = 1; i <= meta.last_page; i++) {
                        if (
                            i === 1 ||
                            i === meta.last_page ||
                            (i >= meta.current_page - 1 && i <= meta.current_page + 1)
                        ) {
                            const pageBtn = document.createElement('button');
                            pageBtn.className = `mx-1 px-3 py-1 rounded ${i === meta.current_page ? 'bg-blue-600 text-white' : 'bg-white border hover:bg-gray-50'}`;
                            pageBtn.textContent = i;
                            pageBtn.addEventListener('click', () => loadCourses(i));
                            pagination.appendChild(pageBtn);
                        } else if (
                            i === meta.current_page - 2 ||
                            i === meta.current_page + 2
                        ) {
                            const ellipsis = document.createElement('span');
                            ellipsis.className = 'mx-1 px-3 py-1';
                            ellipsis.textContent = '...';
                            pagination.appendChild(ellipsis);
                        }
                    }

                    // Next button
                    const nextBtn = document.createElement('button');
                    nextBtn.className = `mx-1 px-3 py-1 rounded ${meta.current_page === meta.last_page ? 'bg-gray-200 cursor-not-allowed' : 'bg-white border hover:bg-gray-50'}`;
                    nextBtn.innerHTML = '&raquo;';
                    nextBtn.disabled = meta.current_page === meta.last_page;
                    nextBtn.addEventListener('click', () => loadCourses(meta.current_page + 1));
                    pagination.appendChild(nextBtn);
                }
            }

            // Edit course
            function editCourse(id) {
                // Show loading state in modal
                const modal = document.getElementById('course-modal');
                const modalContent = modal.querySelector('.bg-white');

                // Show modal with loading state
                modal.classList.remove('hidden');
                modalContent.innerHTML = `
                    <div class="p-6 flex flex-col items-center justify-center">
                        <i class="fas fa-spinner fa-spin text-indigo-600 text-3xl mb-4"></i>
                        <p class="text-gray-700">Loading course details...</p>
                    </div>
                `;

                fetchWithAuth(`/api/courses/${id}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Failed to load course details');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.data) {
                            // Reset modal content
                            modal.classList.add('hidden');

                            // Restore original modal content (this is a bit of a hack, but works for this case)
                            setTimeout(() => {
                                modalContent.innerHTML = document.getElementById('course-modal-template').innerHTML;

                                // Now populate the form
                                const course = data.data;
                                document.getElementById('course-id').value = course.id;
                                document.getElementById('title').value = course.title;
                                document.getElementById('code').value = course.code;
                                document.getElementById('description').value = course.description;
                                document.getElementById('credit_hours').value = course.credit_hours || '';
                                document.getElementById('status').value = course.status || 'active';

                                // Handle image display
                                const currentImageContainer = document.getElementById('current-image-container');
                                const currentImage = document.getElementById('current-image');
                                const imagePreviewContainer = document.getElementById('image-preview-container');

                                // Reset image preview
                                imagePreviewContainer.classList.add('hidden');

                                // Show current image if it exists
                                if (course.image_path) {
                                    currentImage.src = '/' + course.image_path;
                                    currentImageContainer.classList.remove('hidden');
                                } else {
                                    currentImageContainer.classList.add('hidden');
                                }

                                document.getElementById('modal-title').textContent = 'Edit Course';
                                modal.classList.remove('hidden');

                                // Re-attach event listeners
                                document.getElementById('close-modal').addEventListener('click', () => {
                                    modal.classList.add('hidden');
                                });

                                document.getElementById('cancel-btn').addEventListener('click', () => {
                                    modal.classList.add('hidden');
                                });

                                document.getElementById('course-form').addEventListener('submit', (e) => {
                                    e.preventDefault();
                                    saveCourse();
                                });

                                // Image preview functionality
                                const imageInput = document.getElementById('image');
                                const imagePreview = document.getElementById('image-preview');

                                // Show preview when image is selected
                                imageInput.addEventListener('change', function() {
                                    if (this.files && this.files[0]) {
                                        const reader = new FileReader();
                                        reader.onload = function(e) {
                                            imagePreview.src = e.target.result;
                                            imagePreviewContainer.classList.remove('hidden');
                                            currentImageContainer.classList.add('hidden');
                                        }
                                        reader.readAsDataURL(this.files[0]);
                                    }
                                });

                                // Remove selected image
                                document.getElementById('remove-image').addEventListener('click', function() {
                                    imageInput.value = '';
                                    imagePreviewContainer.classList.add('hidden');

                                    // Show current image again if it exists
                                    if (course.image_path) {
                                        currentImageContainer.classList.remove('hidden');
                                    }
                                });
                            }, 100);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching course:', error);
                        modal.classList.add('hidden');
                        showNotification('Error loading course details: ' + error.message, 'error');
                    });
            }

            // Show delete confirmation modal
            function showDeleteModal(id) {
                const deleteModal = document.getElementById('delete-modal');
                deleteModal.classList.remove('hidden');

                document.getElementById('confirm-delete').onclick = () => deleteCourse(id);
                document.getElementById('cancel-delete').onclick = () => deleteModal.classList.add('hidden');
            }

            // Delete course
            function deleteCourse(id) {
                // Show loading state in delete button
                const deleteButton = document.getElementById('confirm-delete');
                const originalButtonText = deleteButton.innerHTML;
                deleteButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Deleting...';
                deleteButton.disabled = true;

                fetchWithAuth(`/api/courses/${id}`, {
                    method: 'DELETE'
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Failed to delete course');
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    document.getElementById('delete-modal').classList.add('hidden');
                    loadCourses(); // Reload courses
                    showNotification('Course deleted successfully', 'success');
                })
                .catch(error => {
                    console.error('Error deleting course:', error);
                    showNotification(error.message || 'Error deleting course', 'error');
                })
                .finally(() => {
                    // Reset button state
                    deleteButton.innerHTML = originalButtonText;
                    deleteButton.disabled = false;
                });
            }

            // Save course (create or update)
            function saveCourse() {
                const courseId = document.getElementById('course-id').value;
                const isEdit = !!courseId;

                // Create FormData object for file uploads
                const formData = new FormData();
                formData.append('title', document.getElementById('title').value);
                formData.append('code', document.getElementById('code').value);
                formData.append('description', document.getElementById('description').value);
                formData.append('credit_hours', document.getElementById('credit_hours').value);
                formData.append('status', document.getElementById('status').value);

                // Add image file if selected
                const imageInput = document.getElementById('image');
                if (imageInput.files && imageInput.files[0]) {
                    formData.append('image', imageInput.files[0]);
                }

                const url = isEdit ? `/api/courses/${courseId}` : '/api/courses';
                const method = 'POST'; // Always use POST for FormData

                // For PUT requests with FormData, we need to use the _method parameter
                if (isEdit) {
                    formData.append('_method', 'PUT');
                }

                // Custom headers for FormData - don't set Content-Type
                const headers = {};
                const token = getToken();
                if (token) {
                    headers['Authorization'] = `Bearer ${token}`;
                }

                // Show loading state
                const saveButton = document.getElementById('save-course-btn');
                const originalButtonText = saveButton.innerHTML;
                saveButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
                saveButton.disabled = true;

                fetch(url, {
                    method: method,
                    headers: headers,
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(errorData => {
                            throw new Error(errorData.message || 'Failed to save course');
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    document.getElementById('course-modal').classList.add('hidden');
                    document.getElementById('course-form').reset();

                    // Reset image previews
                    document.getElementById('image-preview-container').classList.add('hidden');
                    document.getElementById('current-image-container').classList.add('hidden');

                    loadCourses(); // Reload courses

                    // Show success message
                    const successMessage = isEdit ? 'Course updated successfully' : 'Course created successfully';
                    showNotification(successMessage, 'success');
                })
                .catch(error => {
                    console.error('Error saving course:', error);
                    showNotification(error.message || 'Error saving course', 'error');
                })
                .finally(() => {
                    // Reset button state
                    saveButton.innerHTML = originalButtonText;
                    saveButton.disabled = false;
                });
            }

            // Show notification function
            function showNotification(message, type = 'info') {
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
                    type === 'success' ? 'bg-green-500 text-white' :
                    type === 'error' ? 'bg-red-500 text-white' :
                    'bg-blue-500 text-white'
                }`;

                notification.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-${
                            type === 'success' ? 'check-circle' :
                            type === 'error' ? 'exclamation-circle' :
                            'info-circle'
                        } mr-2"></i>
                        <span>${message}</span>
                    </div>
                `;

                document.body.appendChild(notification);

                // Remove after 3 seconds
                setTimeout(() => {
                    notification.classList.add('opacity-0', 'transition-opacity', 'duration-500');
                    setTimeout(() => {
                        document.body.removeChild(notification);
                    }, 500);
                }, 3000);
            }

            // Modal functionality
            const courseModal = document.getElementById('course-modal');
            const addCourseBtn = document.getElementById('add-course-btn');

            // Initialize the course modal with the template content
            function initializeCourseModal() {
                const modalContent = courseModal.querySelector('.bg-white');
                modalContent.innerHTML = document.getElementById('course-modal-template').innerHTML;

                const closeModalBtn = document.getElementById('close-modal');
                const cancelBtn = document.getElementById('cancel-btn');
                const courseForm = document.getElementById('course-form');

                closeModalBtn.addEventListener('click', () => {
                    courseModal.classList.add('hidden');
                });

                cancelBtn.addEventListener('click', () => {
                    courseModal.classList.add('hidden');
                });

                courseForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    saveCourse();
                });

                // Image preview functionality
                const imageInput = document.getElementById('image');
                const imagePreview = document.getElementById('image-preview');
                const imagePreviewContainer = document.getElementById('image-preview-container');
                const removeImageBtn = document.getElementById('remove-image');

                imageInput.addEventListener('change', function() {
                    const currentImageContainer = document.getElementById('current-image-container');
                    currentImageContainer.classList.add('hidden');

                    if (this.files && this.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imagePreviewContainer.classList.remove('hidden');
                        }
                        reader.readAsDataURL(this.files[0]);
                    } else {
                        imagePreviewContainer.classList.add('hidden');
                    }
                });

                removeImageBtn.addEventListener('click', function() {
                    imageInput.value = '';
                    imagePreviewContainer.classList.add('hidden');

                    // Show current image again if in edit mode
                    const courseId = document.getElementById('course-id').value;
                    if (courseId) {
                        const currentImageContainer = document.getElementById('current-image-container');
                        if (currentImageContainer.querySelector('img').src) {
                            currentImageContainer.classList.remove('hidden');
                        }
                    }
                });
            }

            // Add new course button click handler
            addCourseBtn.addEventListener('click', () => {
                // Initialize the modal
                initializeCourseModal();

                // Reset form and set for adding new course
                document.getElementById('course-id').value = '';
                document.getElementById('course-form').reset();
                document.getElementById('modal-title').textContent = 'Add New Course';

                // Hide image containers
                document.getElementById('image-preview-container').classList.add('hidden');
                document.getElementById('current-image-container').classList.add('hidden');

                // Show the modal
                courseModal.classList.remove('hidden');
            });

            // Search and filter functionality
            const searchInput = document.getElementById('course-search');
            const statusFilter = document.getElementById('status-filter');

            searchInput.addEventListener('input', debounce(() => {
                loadCourses(1, searchInput.value, statusFilter.value);
            }, 300));

            statusFilter.addEventListener('change', () => {
                loadCourses(1, searchInput.value, statusFilter.value);
            });

            // Debounce function for search input
            function debounce(func, wait) {
                let timeout;
                return function() {
                    const context = this;
                    const args = arguments;
                    clearTimeout(timeout);
                    timeout = setTimeout(() => {
                        func.apply(context, args);
                    }, wait);
                };
            }



            // Sidebar toggle functionality
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                mainContent.classList.toggle('active');
            });

            // Load user profile
            function loadUserProfile() {
                fetchWithAuth('/api/profile')
                    .then(response => response.json())
                    .then(data => {
                        const userProfile = document.getElementById('user-profile');
                        const userData = data.user || data;

                        userProfile.innerHTML = `
                            <button class="flex items-center focus:outline-none">
                                <div class="flex items-center">
                                    ${userData.imgProfilePath
                                        ? `<img src="/${userData.imgProfilePath}" alt="Profile" class="h-8 w-8 rounded-full object-cover mr-2">`
                                        : `<div class="h-8 w-8 rounded-full bg-indigo-600 flex items-center justify-center text-white mr-2">
                                            ${userData.name ? userData.name.charAt(0).toUpperCase() : 'U'}
                                          </div>`
                                    }
                                    <span class="text-gray-700">${userData.name || 'User'}</span>
                                </div>
                                <svg class="h-4 w-4 text-gray-500 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        `;

                        // Setup logout functionality
                        document.getElementById('logout-link').addEventListener('click', function(e) {
                            e.preventDefault();
                            localStorage.removeItem('token');
                            localStorage.removeItem('role');
                            window.location.href = '/login';
                        });
                    })
                    .catch(error => {
                        console.error('Error loading user profile:', error);
                    });
            }

            // Initialize
            checkAuth();
            loadUserProfile();
            loadCourses();
        });
    </script>
</body>
</html>

