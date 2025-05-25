<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications - EduPlatform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .notification-item {
            transition: all 0.3s ease;
        }

        .notification-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .notification-unread {
            border-left: 4px solid #6366f1;
            background: rgba(99, 102, 241, 0.05);
        }

        .notification-read {
            opacity: 0.8;
        }

        .loading-skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .filter-btn.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50">
    <!-- Navigation -->
    <nav class="glassmorphism shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-graduation-cap text-2xl text-indigo-600 mr-2"></i>
                        <span class="text-xl font-bold text-gray-800">EduPlatform</span>
                    </div>
                </div>

                <div class="flex items-center space-x-4">
                    <a href="/dashboard" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                    </a>
                    <a href="/courses" class="text-gray-600 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition-colors">
                        <i class="fas fa-book mr-1"></i> My Courses
                    </a>
                    <a href="/notifications" class="text-indigo-600 px-3 py-2 rounded-md text-sm font-medium bg-indigo-100">
                        <i class="fas fa-bell mr-1"></i> Notifications
                        <span id="notification-badge" class="ml-1 bg-red-500 text-white text-xs rounded-full px-2 py-1 hidden">0</span>
                    </a>
                    <div class="relative">
                        <button onclick="toggleUserMenu()" class="flex items-center text-gray-600 hover:text-indigo-600 focus:outline-none">
                            <img src="https://ui-avatars.com/api/?name=Test+Student&background=6366f1&color=fff" alt="Profile" class="w-8 h-8 rounded-full mr-2">
                            <span class="text-sm font-medium">Test Student</span>
                            <i class="fas fa-chevron-down ml-1 text-xs"></i>
                        </button>
                        <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i> Profile
                            </a>
                            <button onclick="logout()" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">
                        <i class="fas fa-bell text-indigo-600 mr-3"></i>
                        Notifications
                    </h1>
                    <p class="text-gray-600">Stay updated with your educational activities</p>
                </div>
                <div class="flex items-center space-x-3">
                    <button onclick="markAllAsRead()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-check-double mr-2"></i>
                        Mark All Read
                    </button>
                    <button onclick="refreshNotifications()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>
                        Refresh
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-2">
                <button onclick="filterNotifications('all')" class="filter-btn active px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium transition-all">
                    All
                </button>
                <button onclick="filterNotifications('unread')" class="filter-btn px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium transition-all">
                    Unread
                </button>
                <button onclick="filterNotifications('assignment')" class="filter-btn px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium transition-all">
                    <i class="fas fa-tasks mr-1"></i> Assignments
                </button>
                <button onclick="filterNotifications('quiz')" class="filter-btn px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium transition-all">
                    <i class="fas fa-question-circle mr-1"></i> Quizzes
                </button>
                <button onclick="filterNotifications('grade')" class="filter-btn px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium transition-all">
                    <i class="fas fa-star mr-1"></i> Grades
                </button>
                <button onclick="filterNotifications('course')" class="filter-btn px-4 py-2 rounded-lg border border-gray-300 text-sm font-medium transition-all">
                    <i class="fas fa-book mr-1"></i> Courses
                </button>
            </div>
        </div>

        <!-- Notifications Container -->
        <div id="notifications-container" class="space-y-4">
            <!-- Loading skeleton will be inserted here -->
        </div>

        <!-- Empty State -->
        <div id="empty-state" class="hidden text-center py-12">
            <div class="glassmorphism rounded-2xl p-8 max-w-md mx-auto">
                <i class="fas fa-bell-slash text-6xl text-gray-400 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-700 mb-2">No notifications found</h3>
                <p class="text-gray-500">You're all caught up! Check back later for new updates.</p>
            </div>
        </div>

        <!-- Pagination -->
        <div id="pagination-container" class="mt-8 flex justify-center">
            <!-- Pagination will be inserted here -->
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 hidden">
        <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
            <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div>
            <span class="text-gray-700">Processing...</span>
        </div>
    </div>

    <script>
        let currentFilter = 'all';
        let currentPage = 1;
        let notifications = [];

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            updateUnreadCount();

            // Auto-refresh every 30 seconds
            setInterval(() => {
                updateUnreadCount();
            }, 30000);
        });

        // API call helper
        async function apiCall(endpoint, method = 'GET', data = null) {
            const token = localStorage.getItem('token');
            const config = {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${token}`,
                    'Accept': 'application/json'
                }
            };

            if (data && method !== 'GET') {
                config.body = JSON.stringify(data);
            }

            const response = await fetch(`/api${endpoint}`, config);

            if (!response.ok) {
                if (response.status === 401) {
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                    return;
                }
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            return await response.json();
        }

        // Load notifications
        async function loadNotifications(page = 1) {
            try {
                showLoadingSkeleton();

                let endpoint = `/notifications?page=${page}&per_page=10`;

                if (currentFilter === 'unread') {
                    endpoint += '&unread_only=true';
                } else if (currentFilter !== 'all') {
                    endpoint += `&type=${currentFilter}`;
                }

                const response = await apiCall(endpoint);

                if (response.status === 'success') {
                    notifications = response.data.data;
                    renderNotifications(notifications);
                    renderPagination(response.data);

                    if (notifications.length === 0) {
                        showEmptyState();
                    }
                } else {
                    showNotification('Failed to load notifications', 'error');
                }
            } catch (error) {
                console.error('Error loading notifications:', error);
                showNotification('Failed to load notifications', 'error');
                showEmptyState();
            }
        }

        // Render notifications
        function renderNotifications(notificationsList) {
            const container = document.getElementById('notifications-container');
            const emptyState = document.getElementById('empty-state');

            if (notificationsList.length === 0) {
                container.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');

            container.innerHTML = notificationsList.map(notification => `
                <div class="notification-item ${notification.is_read ? 'notification-read' : 'notification-unread'} glassmorphism rounded-xl p-6 cursor-pointer"
                     onclick="handleNotificationClick(${notification.id}, '${notification.action_url || '#'}')">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 rounded-full ${notification.color_class} flex items-center justify-center">
                                <i class="${notification.default_icon} text-lg"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-lg font-semibold text-gray-900 truncate">
                                    ${notification.title}
                                </h3>
                                <div class="flex items-center space-x-2">
                                    ${notification.priority === 'urgent' ? '<span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full">Urgent</span>' : ''}
                                    ${notification.priority === 'high' ? '<span class="bg-orange-100 text-orange-800 text-xs font-medium px-2 py-1 rounded-full">High</span>' : ''}
                                    <span class="text-sm text-gray-500">${notification.time_ago}</span>
                                    <button onclick="deleteNotification(${notification.id}, event)" class="text-gray-400 hover:text-red-500 transition-colors">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </div>
                            </div>
                            <p class="text-gray-700 mb-3">${notification.message}</p>
                            <div class="flex items-center justify-between">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getTypeColor(notification.type)}">
                                    <i class="${notification.default_icon} mr-1"></i>
                                    ${notification.type.charAt(0).toUpperCase() + notification.type.slice(1)}
                                </span>
                                ${!notification.is_read ? `
                                    <button onclick="markAsRead(${notification.id}, event)" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium transition-colors">
                                        Mark as read
                                    </button>
                                ` : ''}
                            </div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Get type color class
        function getTypeColor(type) {
            const colors = {
                'assignment': 'bg-blue-100 text-blue-800',
                'quiz': 'bg-purple-100 text-purple-800',
                'grade': 'bg-green-100 text-green-800',
                'course': 'bg-indigo-100 text-indigo-800',
                'success': 'bg-green-100 text-green-800',
                'warning': 'bg-yellow-100 text-yellow-800',
                'error': 'bg-red-100 text-red-800',
                'system': 'bg-gray-100 text-gray-800',
                'info': 'bg-blue-100 text-blue-800'
            };
            return colors[type] || 'bg-gray-100 text-gray-800';
        }

        // Handle notification click
        async function handleNotificationClick(notificationId, actionUrl) {
            try {
                // Mark as read
                await apiCall(`/notifications/${notificationId}/mark-as-read`, 'POST');

                // Navigate to action URL if provided
                if (actionUrl && actionUrl !== '#') {
                    window.location.href = actionUrl;
                } else {
                    // Just refresh the notifications to show as read
                    loadNotifications(currentPage);
                    updateUnreadCount();
                }
            } catch (error) {
                console.error('Error handling notification click:', error);
            }
        }

        // Mark notification as read
        async function markAsRead(notificationId, event) {
            event.stopPropagation();

            try {
                showLoadingOverlay();
                await apiCall(`/notifications/${notificationId}/mark-as-read`, 'POST');
                showNotification('Notification marked as read', 'success');
                loadNotifications(currentPage);
                updateUnreadCount();
            } catch (error) {
                console.error('Error marking notification as read:', error);
                showNotification('Failed to mark notification as read', 'error');
            } finally {
                hideLoadingOverlay();
            }
        }

        // Mark all notifications as read
        async function markAllAsRead() {
            try {
                showLoadingOverlay();
                await apiCall('/notifications/mark-all-as-read', 'POST');
                showNotification('All notifications marked as read', 'success');
                loadNotifications(currentPage);
                updateUnreadCount();
            } catch (error) {
                console.error('Error marking all notifications as read:', error);
                showNotification('Failed to mark all notifications as read', 'error');
            } finally {
                hideLoadingOverlay();
            }
        }

        // Delete notification
        async function deleteNotification(notificationId, event) {
            event.stopPropagation();

            if (!confirm('Are you sure you want to delete this notification?')) {
                return;
            }

            try {
                showLoadingOverlay();
                await apiCall(`/notifications/${notificationId}`, 'DELETE');
                showNotification('Notification deleted', 'success');
                loadNotifications(currentPage);
                updateUnreadCount();
            } catch (error) {
                console.error('Error deleting notification:', error);
                showNotification('Failed to delete notification', 'error');
            } finally {
                hideLoadingOverlay();
            }
        }

        // Filter notifications
        function filterNotifications(filter) {
            currentFilter = filter;
            currentPage = 1;

            // Update active filter button
            document.querySelectorAll('.filter-btn').forEach(btn => {
                btn.classList.remove('active');
            });
            event.target.classList.add('active');

            loadNotifications(currentPage);
        }

        // Refresh notifications
        function refreshNotifications() {
            loadNotifications(currentPage);
            updateUnreadCount();
            showNotification('Notifications refreshed', 'success');
        }

        // Update unread count
        async function updateUnreadCount() {
            try {
                const response = await apiCall('/notifications/unread-count');
                if (response.status === 'success') {
                    const count = response.data.count;
                    const badge = document.getElementById('notification-badge');

                    if (count > 0) {
                        badge.textContent = count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                }
            } catch (error) {
                console.error('Error updating unread count:', error);
            }
        }

        // Render pagination
        function renderPagination(paginationData) {
            const container = document.getElementById('pagination-container');

            if (paginationData.last_page <= 1) {
                container.innerHTML = '';
                return;
            }

            let paginationHTML = '<div class="flex items-center space-x-2">';

            // Previous button
            if (paginationData.current_page > 1) {
                paginationHTML += `
                    <button onclick="loadNotifications(${paginationData.current_page - 1})"
                            class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Previous
                    </button>
                `;
            }

            // Page numbers
            for (let i = Math.max(1, paginationData.current_page - 2);
                 i <= Math.min(paginationData.last_page, paginationData.current_page + 2);
                 i++) {
                const isActive = i === paginationData.current_page;
                paginationHTML += `
                    <button onclick="loadNotifications(${i})"
                            class="px-3 py-2 text-sm font-medium ${isActive ? 'text-white bg-indigo-600 border-indigo-600' : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-50'} border rounded-md">
                        ${i}
                    </button>
                `;
            }

            // Next button
            if (paginationData.current_page < paginationData.last_page) {
                paginationHTML += `
                    <button onclick="loadNotifications(${paginationData.current_page + 1})"
                            class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                        Next
                    </button>
                `;
            }

            paginationHTML += '</div>';
            container.innerHTML = paginationHTML;
            currentPage = paginationData.current_page;
        }

        // Show loading skeleton
        function showLoadingSkeleton() {
            const container = document.getElementById('notifications-container');
            container.innerHTML = Array(5).fill().map(() => `
                <div class="glassmorphism rounded-xl p-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 rounded-full loading-skeleton"></div>
                        <div class="flex-1 space-y-3">
                            <div class="h-4 loading-skeleton rounded w-3/4"></div>
                            <div class="h-3 loading-skeleton rounded w-full"></div>
                            <div class="h-3 loading-skeleton rounded w-1/2"></div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Show empty state
        function showEmptyState() {
            document.getElementById('notifications-container').innerHTML = '';
            document.getElementById('empty-state').classList.remove('hidden');
        }

        // Show/hide loading overlay
        function showLoadingOverlay() {
            document.getElementById('loading-overlay').classList.remove('hidden');
        }

        function hideLoadingOverlay() {
            document.getElementById('loading-overlay').classList.add('hidden');
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

            setTimeout(() => {
                notification.remove();
            }, 5000);
        }

        // Toggle user menu
        function toggleUserMenu() {
            const menu = document.getElementById('user-menu');
            menu.classList.toggle('hidden');
        }

        // Close user menu when clicking outside
        document.addEventListener('click', function(event) {
            const menu = document.getElementById('user-menu');
            const button = event.target.closest('button');

            if (!button || !button.onclick || button.onclick.toString().indexOf('toggleUserMenu') === -1) {
                menu.classList.add('hidden');
            }
        });

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
    </script>
</body>
</html>
