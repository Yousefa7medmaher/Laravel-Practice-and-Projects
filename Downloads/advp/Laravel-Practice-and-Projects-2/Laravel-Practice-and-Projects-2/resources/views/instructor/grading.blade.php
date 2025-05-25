<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grading Center - Educational Platform</title>
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

        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% { background-position: 200% 0; }
            100% { background-position: -200% 0; }
        }

        .meal-coin-input {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            border: 2px solid #f59e0b;
        }

        .meal-coin-input:focus {
            border-color: #d97706;
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.1);
        }

        .grading-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
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
                        <a href="/instructor/courses" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-book mr-2"></i>My Courses
                        </a>
                        <a href="/instructor/content" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-plus-circle mr-2"></i>Create Content
                        </a>
                        <a href="/instructor/grading" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                    <h2 class="text-3xl font-bold mb-2">Grading Center</h2>
                    <p class="text-indigo-100 text-lg">Grade assignments and award meals & coins to students</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-star text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-clock text-orange-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Pending Submissions</p>
                        <p class="text-2xl font-bold text-gray-900" id="pending-count">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Graded This Week</p>
                        <p class="text-2xl font-bold text-gray-900" id="graded-count">
                            <span class="skeleton w-8 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg p-6 card-hover">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-chart-line text-purple-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Average Grade</p>
                        <p class="text-2xl font-bold text-gray-900" id="avg-grade">
                            <span class="skeleton w-12 h-6 rounded inline-block"></span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submissions List -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <i class="fas fa-tasks text-indigo-600 mr-3"></i>
                        <h3 class="text-lg font-semibold text-gray-900">Recent Submissions</h3>
                    </div>
                    <div class="flex space-x-2">
                        <select id="courseFilter" class="border border-gray-300 rounded-lg px-3 py-1 text-sm">
                            <option value="">All Courses</option>
                        </select>
                        <select id="statusFilter" class="border border-gray-300 rounded-lg px-3 py-1 text-sm">
                            <option value="">All Status</option>
                            <option value="submitted">Pending</option>
                            <option value="graded">Graded</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="submissions-table" class="bg-white divide-y divide-gray-200">
                        <!-- Loading skeleton -->
                        <tr class="animate-pulse">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-gray-200 rounded-full"></div>
                                    <div class="ml-4">
                                        <div class="h-4 bg-gray-200 rounded w-24"></div>
                                        <div class="h-3 bg-gray-200 rounded w-16 mt-1"></div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="h-4 bg-gray-200 rounded w-32"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="h-4 bg-gray-200 rounded w-24"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="h-4 bg-gray-200 rounded w-20"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="h-6 bg-gray-200 rounded w-16"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="h-4 bg-gray-200 rounded w-12"></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="h-8 bg-gray-200 rounded w-20"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Grade Submission Modal -->
    <div id="gradeModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
        <div class="bg-white rounded-xl p-6 w-full max-w-2xl mx-4 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold">Grade Submission</h3>
                <button onclick="hideGradeModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Student Info -->
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-user text-indigo-600 text-xl"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900" id="studentName">Student Name</h4>
                        <p class="text-sm text-gray-600" id="studentEmail">student@email.com</p>
                        <p class="text-sm text-gray-500" id="assignmentTitle">Assignment Title</p>
                    </div>
                </div>
            </div>

            <form id="gradeForm">
                <input type="hidden" id="submissionId">
                <div class="space-y-6">
                    <!-- Traditional Grade -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h4 class="font-semibold text-blue-900 mb-3">
                            <i class="fas fa-percentage mr-2"></i>Traditional Grade
                        </h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Grade (0-100)</label>
                                <input type="number" id="gradeInput" min="0" max="100" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Max Points</label>
                                <input type="number" id="maxScore" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100" readonly>
                            </div>
                        </div>
                    </div>



                    <!-- Feedback -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Feedback</label>
                        <textarea id="feedbackInput" rows="4" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Provide detailed feedback to help the student improve..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" onclick="hideGradeModal()" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                        <i class="fas fa-save mr-2"></i>Save Grade & Rewards
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let authToken = localStorage.getItem('token');
        let allSubmissions = [];
        let instructorCourses = [];

        // Initialize page when loaded
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken) {
                window.location.href = '/login';
                return;
            }

            loadUserProfile();
            loadGradingData();
            setupEventListeners();
        });

        // Utility function for API calls
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

        // Load all grading data
        async function loadGradingData() {
            await Promise.all([
                loadInstructorCourses(),
                loadSubmissions(),
                loadGradingStats()
            ]);
        }

        // Load instructor courses
        async function loadInstructorCourses() {
            try {
                const result = await apiCall('/courses');
                if (result && result.ok && result.data.status === 'success') {
                    instructorCourses = result.data.data || [];
                    populateCourseFilter();
                }
            } catch (error) {
                console.error('Error loading courses:', error);
            }
        }

        // Load submissions
        async function loadSubmissions() {
            try {
                allSubmissions = [];

                for (const course of instructorCourses) {
                    const assignmentsResult = await apiCall(`/courses/${course.id}/assignments`);
                    if (assignmentsResult && assignmentsResult.ok) {
                        const assignments = assignmentsResult.data.data || [];

                        for (const assignment of assignments) {
                            const submissionsResult = await apiCall(`/instructor/assignments/${assignment.id}/submissions`);
                            if (submissionsResult && submissionsResult.ok) {
                                const submissions = submissionsResult.data.data || [];
                                allSubmissions.push(...submissions.map(sub => ({
                                    ...sub,
                                    course_title: course.title,
                                    course_id: course.id,
                                    assignment_title: assignment.title,
                                    assignment_max_score: assignment.points || 100
                                })));
                            }
                        }
                    }
                }

                displaySubmissions(allSubmissions);
            } catch (error) {
                console.error('Error loading submissions:', error);
            }
        }

        // Display submissions in table
        function displaySubmissions(submissions) {
            const tableBody = document.getElementById('submissions-table');

            if (submissions.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p>No submissions found</p>
                        </td>
                    </tr>
                `;
                return;
            }

            tableBody.innerHTML = submissions.map(submission => {
                const submittedDate = new Date(submission.submitted_at);
                const timeAgo = getTimeAgo(submittedDate);
                const statusColor = submission.status === 'graded' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800';
                const statusText = submission.status === 'graded' ? 'Graded' : 'Pending';

                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-indigo-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">${submission.user?.name || 'Student'}</div>
                                    <div class="text-sm text-gray-500">${submission.user?.email || ''}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${submission.assignment_title}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${submission.course_title}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${timeAgo}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${statusColor}">
                                ${statusText}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">
                                ${submission.grade !== null ? `${submission.grade}/${submission.assignment_max_score}` : '-'}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button onclick="showGradeModal(${submission.id}, '${submission.user?.name}', '${submission.user?.email || ''}', '${submission.assignment_title}', ${submission.assignment_max_score}, ${submission.grade || 0}, '${submission.feedback || ''}', ${submission.meals || 0}, ${submission.coins || 0})"
                                    class="text-indigo-600 hover:text-indigo-900">
                                ${submission.status === 'graded' ? 'Edit Grade' : 'Grade'}
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Load grading statistics
        async function loadGradingStats() {
            try {
                let pendingCount = 0;
                let gradedThisWeek = 0;
                let totalGrades = [];

                const oneWeekAgo = new Date();
                oneWeekAgo.setDate(oneWeekAgo.getDate() - 7);

                allSubmissions.forEach(submission => {
                    if (submission.status === 'submitted') {
                        pendingCount++;
                    } else if (submission.status === 'graded') {
                        const gradedDate = new Date(submission.graded_at);
                        if (gradedDate >= oneWeekAgo) {
                            gradedThisWeek++;
                        }
                        if (submission.grade !== null) {
                            const percentage = (submission.grade / submission.assignment_max_score) * 100;
                            totalGrades.push(percentage);
                        }
                    }
                });

                const avgGrade = totalGrades.length > 0
                    ? (totalGrades.reduce((a, b) => a + b, 0) / totalGrades.length).toFixed(1)
                    : 'N/A';

                document.getElementById('pending-count').textContent = pendingCount;
                document.getElementById('graded-count').textContent = gradedThisWeek;
                document.getElementById('avg-grade').textContent = avgGrade + (avgGrade !== 'N/A' ? '%' : '');

            } catch (error) {
                console.error('Error loading grading stats:', error);
            }
        }

        // Populate course filter
        function populateCourseFilter() {
            const select = document.getElementById('courseFilter');
            select.innerHTML = '<option value="">All Courses</option>';

            instructorCourses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.id;
                option.textContent = `${course.code} - ${course.title}`;
                select.appendChild(option);
            });
        }

        // Setup event listeners
        function setupEventListeners() {
            document.getElementById('courseFilter').addEventListener('change', filterSubmissions);
            document.getElementById('statusFilter').addEventListener('change', filterSubmissions);
            document.getElementById('gradeForm').addEventListener('submit', submitGrade);
        }

        // Filter submissions
        function filterSubmissions() {
            const courseFilter = document.getElementById('courseFilter').value;
            const statusFilter = document.getElementById('statusFilter').value;

            let filtered = allSubmissions;

            if (courseFilter) {
                filtered = filtered.filter(sub => sub.course_id == courseFilter);
            }

            if (statusFilter) {
                filtered = filtered.filter(sub => sub.status === statusFilter);
            }

            displaySubmissions(filtered);
        }

        // Show grade modal
        function showGradeModal(submissionId, studentName, studentEmail, assignmentTitle, maxScore, currentGrade, currentFeedback, currentMeals, currentCoins) {
            document.getElementById('submissionId').value = submissionId;
            document.getElementById('studentName').textContent = studentName;
            document.getElementById('studentEmail').textContent = studentEmail;
            document.getElementById('assignmentTitle').textContent = assignmentTitle;
            document.getElementById('maxScore').value = maxScore;
            document.getElementById('gradeInput').value = currentGrade || '';
            document.getElementById('gradeInput').max = maxScore;
            document.getElementById('feedbackInput').value = currentFeedback || '';
            document.getElementById('mealsReward').value = currentMeals || 0;
            document.getElementById('coinsReward').value = currentCoins || 0;
            document.getElementById('gradeModal').classList.remove('hidden');
        }

        // Hide grade modal
        function hideGradeModal() {
            document.getElementById('gradeModal').classList.add('hidden');
            document.getElementById('gradeForm').reset();
        }

        // Submit grade
        async function submitGrade(e) {
            e.preventDefault();

            const submissionId = document.getElementById('submissionId').value;
            const grade = document.getElementById('gradeInput').value;
            const feedback = document.getElementById('feedbackInput').value;

            try {
                const result = await apiCall(`/instructor/submissions/${submissionId}/grade`, 'POST', {
                    grade: parseFloat(grade),
                    feedback: feedback
                });

                if (result && result.ok) {
                    alert('Grade saved successfully!');
                    hideGradeModal();
                    loadGradingData(); // Refresh data
                } else {
                    alert('Failed to save grade: ' + (result.data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Error saving grade:', error);
                alert('Failed to save grade');
            }
        }

        // Utility functions
        function getTimeAgo(date) {
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);

            if (diffInSeconds < 60) return 'Just now';
            if (diffInSeconds < 3600) {
                const minutes = Math.floor(diffInSeconds / 60);
                return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
            }
            if (diffInSeconds < 86400) {
                const hours = Math.floor(diffInSeconds / 3600);
                return `${hours} hour${hours > 1 ? 's' : ''} ago`;
            }
            const days = Math.floor(diffInSeconds / 86400);
            return `${days} day${days > 1 ? 's' : ''} ago`;
        }

        // Logout function
        async function logout() {
            try {
                await apiCall('/logout', 'POST');
                localStorage.removeItem('token');
                window.location.href = '/login';
            } catch (error) {
                localStorage.removeItem('token');
                window.location.href = '/login';
            }
        }
    </script>
</body>
</html>