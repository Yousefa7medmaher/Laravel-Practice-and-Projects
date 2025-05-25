<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - Manager Dashboard</title>
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

        .notification-unread {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.1) 0%, rgba(139, 92, 246, 0.1) 100%);
            border-left: 4px solid #6366f1;
        }

        .notification-read {
            background: rgba(255, 255, 255, 0.8);
            border-left: 4px solid #e5e7eb;
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
                            <i class="fas fa-bell mr-2"></i>
                            Manager Notifications
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
                        <a href="/manager/instructors" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>Instructors
                        </a>
                        <a href="/manager/notifications" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-bell mr-2"></i>Notifications
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
                    <h2 class="text-3xl font-bold mb-2">Notification Center</h2>
                    <p class="text-indigo-100 text-lg">Stay updated with system activities, course management, and important alerts</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-bell text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="manager-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-bell text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total Notifications</p>
                        <p class="text-2xl font-bold text-gray-900" id="total-notifications">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="manager-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-circle text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Unread</p>
                        <p class="text-2xl font-bold text-gray-900" id="unread-notifications">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="manager-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">High Priority</p>
                        <p class="text-2xl font-bold text-gray-900" id="high-priority">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="manager-card rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Today</p>
                        <p class="text-2xl font-bold text-gray-900" id="today-notifications">
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
                    <select id="filter-type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">All Types</option>
                        <option value="system">System</option>
                        <option value="course">Course</option>
                        <option value="assignment">Assignment</option>
                        <option value="grade">Grade</option>
                        <option value="quiz">Quiz</option>
                        <option value="success">Success</option>
                        <option value="warning">Warning</option>
                        <option value="error">Error</option>
                    </select>
                    <select id="filter-priority" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">All Priorities</option>
                        <option value="urgent">Urgent</option>
                        <option value="high">High</option>
                        <option value="normal">Normal</option>
                        <option value="low">Low</option>
                    </select>
                    <select id="filter-status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                        <option value="">All Status</option>
                        <option value="unread">Unread Only</option>
                        <option value="read">Read Only</option>
                    </select>
                </div>
                <div class="flex space-x-3">
                    <button onclick="markAllAsRead()" class="bg-green-100 text-green-700 px-4 py-2 rounded-lg hover:bg-green-200 transition-all">
                        <i class="fas fa-check-double mr-2"></i>Mark All Read
                    </button>
                    <button onclick="refreshNotifications()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition-all">
                        <i class="fas fa-sync-alt mr-2"></i>Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Notifications List -->
        <div class="manager-card rounded-xl shadow-lg">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent Notifications</h3>
                <div id="notifications-container">
                    <!-- Loading skeletons -->
                    <div class="space-y-4" id="loading-skeleton">
                        <div class="skeleton h-20 rounded-lg"></div>
                        <div class="skeleton h-20 rounded-lg"></div>
                        <div class="skeleton h-20 rounded-lg"></div>
                    </div>
                </div>

                <!-- Pagination -->
                <div id="pagination-container" class="mt-6 hidden">
                    <!-- Pagination will be loaded here -->
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="hidden text-center py-12">
            <div class="manager-card rounded-xl shadow-lg p-12">
                <i class="fas fa-bell-slash text-6xl text-gray-300 mb-6"></i>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No notifications found</h3>
                <p class="text-gray-500 mb-6">You're all caught up! No notifications match your current filters.</p>
                <button onclick="clearFilters()" class="action-button text-white px-6 py-3 rounded-lg hover:shadow-lg transition-all">
                    <i class="fas fa-filter mr-2"></i>Clear Filters
                </button>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let authToken = localStorage.getItem('token');
        let allNotifications = [];
        let filteredNotifications = [];
        let currentPage = 1;
        let totalPages = 1;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken) {
                window.location.href = '/login';
                return;
            }

            loadUserProfile();
            loadNotifications();
            setupEventListeners();
        });

        // Setup event listeners
        function setupEventListeners() {
            document.getElementById('filter-type').addEventListener('change', filterNotifications);
            document.getElementById('filter-priority').addEventListener('change', filterNotifications);
            document.getElementById('filter-status').addEventListener('change', filterNotifications);
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

        // Load notifications
        async function loadNotifications(page = 1) {
            try {
                const result = await apiCall(`/notifications?page=${page}&per_page=10`);
                if (result && result.ok) {
                    allNotifications = result.data.data || [];
                    currentPage = result.data.current_page || 1;
                    totalPages = result.data.last_page || 1;

                    filteredNotifications = [...allNotifications];
                    updateStats();
                    displayNotifications();
                    updatePagination();
                } else {
                    console.error('Failed to load notifications:', result);
                    showEmptyState();
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
                showEmptyState();
            }
        }

        // Update statistics
        function updateStats() {
            // These will be calculated from the loaded notifications
            const totalCount = allNotifications.length;
            const unreadCount = allNotifications.filter(n => !n.is_read).length;
            const highPriorityCount = allNotifications.filter(n => n.priority === 'high' || n.priority === 'urgent').length;
            const todayCount = allNotifications.filter(n => {
                const today = new Date().toDateString();
                const notificationDate = new Date(n.created_at).toDateString();
                return today === notificationDate;
            }).length;

            document.getElementById('total-notifications').textContent = totalCount;
            document.getElementById('unread-notifications').textContent = unreadCount;
            document.getElementById('high-priority').textContent = highPriorityCount;
            document.getElementById('today-notifications').textContent = todayCount;
        }

        // Filter notifications
        function filterNotifications() {
            const typeFilter = document.getElementById('filter-type').value;
            const priorityFilter = document.getElementById('filter-priority').value;
            const statusFilter = document.getElementById('filter-status').value;

            filteredNotifications = allNotifications.filter(notification => {
                const matchesType = !typeFilter || notification.type === typeFilter;
                const matchesPriority = !priorityFilter || notification.priority === priorityFilter;

                let matchesStatus = true;
                if (statusFilter === 'unread') {
                    matchesStatus = !notification.is_read;
                } else if (statusFilter === 'read') {
                    matchesStatus = notification.is_read;
                }

                return matchesType && matchesPriority && matchesStatus;
            });

            displayNotifications();
        }

        // Display notifications
        function displayNotifications() {
            const container = document.getElementById('notifications-container');
            const loadingSkeleton = document.getElementById('loading-skeleton');
            const emptyState = document.getElementById('empty-state');

            // Hide loading skeleton
            if (loadingSkeleton) {
                loadingSkeleton.style.display = 'none';
            }

            if (filteredNotifications.length === 0) {
                container.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');
            container.innerHTML = `
                <div class="space-y-4">
                    ${filteredNotifications.map(notification => `
                        <div class="notification-item ${notification.is_read ? 'notification-read' : 'notification-unread'} rounded-lg p-4 transition-all hover:shadow-md cursor-pointer"
                             onclick="handleNotificationClick(${notification.id})">
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center ${getNotificationBgClass(notification.type)}">
                                        <i class="${notification.default_icon || getNotificationIcon(notification.type)} ${getNotificationTextClass(notification.type)}"></i>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-sm font-semibold text-gray-900 truncate">${notification.title}</h4>
                                        <div class="flex items-center space-x-2">
                                            ${notification.priority === 'urgent' || notification.priority === 'high' ?
                                                `<span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    ${notification.priority.toUpperCase()}
                                                </span>` : ''
                                            }
                                            <span class="text-xs text-gray-500">${formatTimeAgo(notification.created_at)}</span>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 mt-1">${notification.message}</p>
                                    <div class="flex items-center justify-between mt-3">
                                        <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full ${getTypeBadgeClass(notification.type)}">
                                            ${notification.type.charAt(0).toUpperCase() + notification.type.slice(1)}
                                        </span>
                                        <div class="flex space-x-2">
                                            ${!notification.is_read ?
                                                `<button onclick="markAsRead(${notification.id}, event)" class="text-indigo-600 hover:text-indigo-800 text-xs">
                                                    <i class="fas fa-check mr-1"></i>Mark Read
                                                </button>` : ''
                                            }
                                            <button onclick="deleteNotification(${notification.id}, event)" class="text-red-600 hover:text-red-800 text-xs">
                                                <i class="fas fa-trash mr-1"></i>Delete
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        // Helper functions for notification styling
        function getNotificationIcon(type) {
            const icons = {
                'system': 'fas fa-cog',
                'course': 'fas fa-book',
                'assignment': 'fas fa-tasks',
                'grade': 'fas fa-star',
                'quiz': 'fas fa-question-circle',
                'success': 'fas fa-check-circle',
                'warning': 'fas fa-exclamation-triangle',
                'error': 'fas fa-times-circle',
                'info': 'fas fa-info-circle'
            };
            return icons[type] || 'fas fa-bell';
        }

        function getNotificationBgClass(type) {
            const classes = {
                'system': 'bg-gray-100',
                'course': 'bg-indigo-100',
                'assignment': 'bg-blue-100',
                'grade': 'bg-green-100',
                'quiz': 'bg-purple-100',
                'success': 'bg-green-100',
                'warning': 'bg-yellow-100',
                'error': 'bg-red-100',
                'info': 'bg-blue-100'
            };
            return classes[type] || 'bg-gray-100';
        }

        function getNotificationTextClass(type) {
            const classes = {
                'system': 'text-gray-600',
                'course': 'text-indigo-600',
                'assignment': 'text-blue-600',
                'grade': 'text-green-600',
                'quiz': 'text-purple-600',
                'success': 'text-green-600',
                'warning': 'text-yellow-600',
                'error': 'text-red-600',
                'info': 'text-blue-600'
            };
            return classes[type] || 'text-gray-600';
        }

        function getTypeBadgeClass(type) {
            const classes = {
                'system': 'bg-gray-100 text-gray-800',
                'course': 'bg-indigo-100 text-indigo-800',
                'assignment': 'bg-blue-100 text-blue-800',
                'grade': 'bg-green-100 text-green-800',
                'quiz': 'bg-purple-100 text-purple-800',
                'success': 'bg-green-100 text-green-800',
                'warning': 'bg-yellow-100 text-yellow-800',
                'error': 'bg-red-100 text-red-800',
                'info': 'bg-blue-100 text-blue-800'
            };
            return classes[type] || 'bg-gray-100 text-gray-800';
        }

        function formatTimeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);

            if (diffInSeconds < 60) return 'Just now';
            if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
            if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
            if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)}d ago`;
            return date.toLocaleDateString();
        }

        // Notification actions
        async function handleNotificationClick(notificationId) {
            const notification = allNotifications.find(n => n.id === notificationId);
            if (!notification) return;

            // Mark as read if unread
            if (!notification.is_read) {
                await markAsRead(notificationId);
            }

            // Navigate to action URL if available
            if (notification.action_url) {
                window.location.href = notification.action_url;
            }
        }

        async function markAsRead(notificationId, event = null) {
            if (event) {
                event.stopPropagation();
            }

            try {
                const result = await apiCall(`/notifications/${notificationId}/mark-as-read`, 'POST');
                if (result && result.ok) {
                    // Update local data
                    const notification = allNotifications.find(n => n.id === notificationId);
                    if (notification) {
                        notification.is_read = true;
                        notification.read_at = new Date().toISOString();
                    }

                    updateStats();
                    displayNotifications();
                }
            } catch (error) {
                console.error('Error marking notification as read:', error);
            }
        }

        async function markAllAsRead() {
            try {
                const result = await apiCall('/notifications/mark-all-as-read', 'POST');
                if (result && result.ok) {
                    // Update all notifications to read
                    allNotifications.forEach(notification => {
                        notification.is_read = true;
                        notification.read_at = new Date().toISOString();
                    });

                    updateStats();
                    displayNotifications();

                    alert('All notifications marked as read!');
                }
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
                alert('Failed to mark all notifications as read');
            }
        }

        async function deleteNotification(notificationId, event) {
            event.stopPropagation();

            if (!confirm('Are you sure you want to delete this notification?')) {
                return;
            }

            try {
                const result = await apiCall(`/notifications/${notificationId}`, 'DELETE');
                if (result && result.ok) {
                    // Remove from local data
                    allNotifications = allNotifications.filter(n => n.id !== notificationId);
                    filteredNotifications = filteredNotifications.filter(n => n.id !== notificationId);

                    updateStats();
                    displayNotifications();
                }
            } catch (error) {
                console.error('Error deleting notification:', error);
                alert('Failed to delete notification');
            }
        }

        function updatePagination() {
            const container = document.getElementById('pagination-container');
            if (totalPages <= 1) {
                container.classList.add('hidden');
                return;
            }

            container.classList.remove('hidden');
            container.innerHTML = `
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Page ${currentPage} of ${totalPages}
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="loadNotifications(${currentPage - 1})"
                                ${currentPage <= 1 ? 'disabled' : ''}
                                class="px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            Previous
                        </button>
                        <button onclick="loadNotifications(${currentPage + 1})"
                                ${currentPage >= totalPages ? 'disabled' : ''}
                                class="px-3 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            Next
                        </button>
                    </div>
                </div>
            `;
        }

        function showEmptyState() {
            document.getElementById('notifications-container').innerHTML = '';
            document.getElementById('empty-state').classList.remove('hidden');
        }

        function clearFilters() {
            document.getElementById('filter-type').value = '';
            document.getElementById('filter-priority').value = '';
            document.getElementById('filter-status').value = '';
            filterNotifications();
        }

        function refreshNotifications() {
            loadNotifications(currentPage);
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
