<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - Educational Platform</title>
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

        .analytics-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .chart-container {
            position: relative;
            height: 300px;
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
                        <a href="/instructor/grading" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-star mr-2"></i>Grading
                        </a>
                        <a href="/instructor/analytics" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                    <h2 class="text-3xl font-bold mb-2">Course Analytics</h2>
                    <p class="text-indigo-100 text-lg">Monitor student performance and course effectiveness</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-chart-bar text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Course Selection -->
        <div class="analytics-card rounded-xl shadow-lg p-6 mb-8">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Select Course for Analytics</h3>
                <button onclick="refreshAnalytics()" class="text-indigo-600 hover:text-indigo-800">
                    <i class="fas fa-sync-alt mr-1"></i>Refresh
                </button>
            </div>
            <select id="courseSelect" class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">Select a course to view analytics...</option>
            </select>
        </div>

        <!-- Analytics Content -->
        <div id="no-course-selected" class="analytics-card rounded-xl shadow-lg p-12 text-center">
            <i class="fas fa-arrow-up text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">Select a Course</h3>
            <p class="text-gray-500">Choose a course from the dropdown above to view detailed analytics</p>
        </div>

        <!-- Analytics Dashboard -->
        <div id="analytics-dashboard" class="hidden space-y-8">
            <!-- Key Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="analytics-card rounded-xl shadow-lg p-6 card-hover">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-blue-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Enrolled Students</p>
                            <p class="text-2xl font-bold text-gray-900" id="enrolled-students">
                                <span class="skeleton w-8 h-6 rounded inline-block"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="analytics-card rounded-xl shadow-lg p-6 card-hover">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-line text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Average Grade</p>
                            <p class="text-2xl font-bold text-gray-900" id="average-grade">
                                <span class="skeleton w-12 h-6 rounded inline-block"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="analytics-card rounded-xl shadow-lg p-6 card-hover">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-tasks text-purple-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Assignments</p>
                            <p class="text-2xl font-bold text-gray-900" id="total-assignments">
                                <span class="skeleton w-8 h-6 rounded inline-block"></span>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="analytics-card rounded-xl shadow-lg p-6 card-hover">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-percentage text-orange-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-500">Completion Rate</p>
                            <p class="text-2xl font-bold text-gray-900" id="completion-rate">
                                <span class="skeleton w-12 h-6 rounded inline-block"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Grade Distribution Chart -->
                <div class="analytics-card rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Grade Distribution</h3>
                    </div>
                    <div class="p-6">
                        <div class="chart-container">
                            <canvas id="gradeChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Student Progress Chart -->
                <div class="analytics-card rounded-xl shadow-lg overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Student Progress</h3>
                    </div>
                    <div class="p-6">
                        <div class="chart-container">
                            <canvas id="progressChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Student Performance Table -->
            <div class="analytics-card rounded-xl shadow-lg overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Student Performance</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assignments</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quizzes</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Overall Grade</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Progress</th>
                            </tr>
                        </thead>
                        <tbody id="student-performance-table" class="bg-white divide-y divide-gray-200">
                            <!-- Student data will be loaded here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let authToken = localStorage.getItem('token');
        let instructorCourses = [];
        let selectedCourseId = null;
        let gradeChart = null;
        let progressChart = null;

        // Initialize page when loaded
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken) {
                window.location.href = '/login';
                return;
            }

            loadUserProfile();
            loadInstructorCourses();
            setupEventListeners();
            loadGradeDistribution(); // Load overall grade distribution on page load
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

        // Load instructor courses
        async function loadInstructorCourses() {
            try {
                const result = await apiCall('/courses');
                if (result && result.ok && result.data.status === 'success') {
                    instructorCourses = result.data.data || [];
                    populateCourseSelect();
                }
            } catch (error) {
                console.error('Error loading courses:', error);
            }
        }

        // Populate course select dropdown
        function populateCourseSelect() {
            const select = document.getElementById('courseSelect');
            select.innerHTML = '<option value="">Select a course to view analytics...</option>';

            instructorCourses.forEach(course => {
                const option = document.createElement('option');
                option.value = course.id;
                option.textContent = `${course.code} - ${course.title}`;
                select.appendChild(option);
            });
        }

        // Setup event listeners
        function setupEventListeners() {
            document.getElementById('courseSelect').addEventListener('change', function() {
                const courseId = this.value;
                if (courseId) {
                    selectedCourseId = courseId;
                    loadCourseAnalytics(courseId);
                } else {
                    hideDashboard();
                }
            });
        }

        // Load course analytics
        async function loadCourseAnalytics(courseId) {
            showDashboard();

            try {
                // Load course analytics data
                const analyticsResult = await apiCall(`/instructor/courses/${courseId}/analytics`);
                const studentsResult = await apiCall(`/instructor/courses/${courseId}/students`);

                if (analyticsResult && analyticsResult.ok) {
                    const analytics = analyticsResult.data.data || {};
                    updateMetrics(analytics);
                    createCharts(analytics);
                }

                if (studentsResult && studentsResult.ok) {
                    const students = studentsResult.data.data || [];
                    updateStudentTable(students);
                }
            } catch (error) {
                console.error('Error loading analytics:', error);
            }
        }

        // Show analytics dashboard
        function showDashboard() {
            document.getElementById('no-course-selected').classList.add('hidden');
            document.getElementById('analytics-dashboard').classList.remove('hidden');
        }

        // Hide analytics dashboard
        function hideDashboard() {
            document.getElementById('no-course-selected').classList.remove('hidden');
            document.getElementById('analytics-dashboard').classList.add('hidden');
        }

        // Update metrics
        function updateMetrics(analytics) {
            document.getElementById('enrolled-students').textContent = analytics.enrolled_students || 0;
            document.getElementById('average-grade').textContent = (analytics.average_grade || 0) + '%';
            document.getElementById('total-assignments').textContent = analytics.total_assignments || 0;
            document.getElementById('completion-rate').textContent = (analytics.completion_rate || 0) + '%';
        }

        // Create charts
        function createCharts(analytics) {
            createGradeChart(analytics.grade_distribution || {});
            createProgressChart(analytics.progress_data || {});
        }

        // Load real grade distribution data
        async function loadGradeDistribution() {
            try {
                const result = await apiCall('/instructor/grade-distribution');

                if (result && result.ok && result.data.status === 'success') {
                    const gradeData = result.data.data.overall_distribution;
                    createGradeChart(gradeData);
                    updateGradeStatistics(result.data.data);
                } else {
                    console.error('Failed to load grade distribution:', result);
                    // Fallback to sample data
                    createGradeChart({
                        counts: { A: 0, B: 0, C: 0, D: 0, F: 0 },
                        percentages: { A: 0, B: 0, C: 0, D: 0, F: 0 },
                        total_grades: 0
                    });
                }
            } catch (error) {
                console.error('Error loading grade distribution:', error);
                // Fallback to sample data
                createGradeChart({
                    counts: { A: 0, B: 0, C: 0, D: 0, F: 0 },
                    percentages: { A: 0, B: 0, C: 0, D: 0, F: 0 },
                    total_grades: 0
                });
            }
        }

        // Create grade distribution chart with real data
        function createGradeChart(gradeData) {
            const ctx = document.getElementById('gradeChart').getContext('2d');

            if (gradeChart) {
                gradeChart.destroy();
            }

            const counts = gradeData.counts || {};
            const percentages = gradeData.percentages || {};
            const totalGrades = gradeData.total_grades || 0;

            gradeChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['A (90-100)', 'B (80-89)', 'C (70-79)', 'D (60-69)', 'F (0-59)'],
                    datasets: [{
                        data: [
                            counts.A || 0,
                            counts.B || 0,
                            counts.C || 0,
                            counts.D || 0,
                            counts.F || 0
                        ],
                        backgroundColor: [
                            '#10B981', // Green for A
                            '#3B82F6', // Blue for B
                            '#F59E0B', // Yellow for C
                            '#EF4444', // Red for D
                            '#6B7280'  // Gray for F
                        ],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 20,
                                usePointStyle: true,
                                generateLabels: function(chart) {
                                    const data = chart.data;
                                    if (data.labels.length && data.datasets.length) {
                                        return data.labels.map((label, i) => {
                                            const count = data.datasets[0].data[i];
                                            const percentage = percentages[label.charAt(0)] || 0;
                                            return {
                                                text: `${label}: ${count} (${percentage}%)`,
                                                fillStyle: data.datasets[0].backgroundColor[i],
                                                strokeStyle: data.datasets[0].borderColor,
                                                lineWidth: data.datasets[0].borderWidth,
                                                pointStyle: 'circle',
                                                hidden: false,
                                                index: i
                                            };
                                        });
                                    }
                                    return [];
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.parsed;
                                    const percentage = percentages[label.charAt(0)] || 0;
                                    return `${label}: ${value} students (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Update chart title with total count
            const chartContainer = document.querySelector('#gradeChart').closest('.analytics-card');
            const titleElement = chartContainer.querySelector('h3');
            if (titleElement) {
                titleElement.textContent = `Grade Distribution (${totalGrades} total grades)`;
            }
        }

        // Update grade statistics display
        function updateGradeStatistics(data) {
            const statistics = data.statistics;
            const courseDistributions = data.course_distributions;

            // Update overall statistics if there are elements to update
            const avgGradeElement = document.getElementById('average-grade');
            if (avgGradeElement) {
                avgGradeElement.textContent = statistics.average_grade + '%';
            }

            const totalSubmissionsElement = document.getElementById('total-submissions');
            if (totalSubmissionsElement) {
                totalSubmissionsElement.textContent = statistics.total_submissions;
            }

            // Log course-specific distributions for debugging
            console.log('Course-specific grade distributions:', courseDistributions);
        }

        // Create progress chart
        function createProgressChart(progressData) {
            const ctx = document.getElementById('progressChart').getContext('2d');

            if (progressChart) {
                progressChart.destroy();
            }

            progressChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4', 'Week 5', 'Week 6'],
                    datasets: [{
                        label: 'Average Progress',
                        data: progressData.weekly_progress || [20, 35, 50, 65, 75, 85],
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        }

        // Update student performance table
        function updateStudentTable(students) {
            const tableBody = document.getElementById('student-performance-table');

            if (students.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            No students enrolled in this course
                        </td>
                    </tr>
                `;
                return;
            }

            tableBody.innerHTML = students.map(student => {
                const assignmentGrade = Math.floor(Math.random() * 40) + 60; // Mock data
                const quizGrade = Math.floor(Math.random() * 40) + 60; // Mock data
                const overallGrade = Math.floor((assignmentGrade + quizGrade) / 2);
                const progress = Math.floor(Math.random() * 40) + 60; // Mock data

                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-indigo-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">${student.name}</div>
                                    <div class="text-sm text-gray-500">${student.email}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${assignmentGrade}%</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">${quizGrade}%</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${overallGrade}%</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="bg-indigo-600 h-2 rounded-full" style="width: ${progress}%"></div>
                                </div>
                                <span class="ml-2 text-sm text-gray-600">${progress}%</span>
                            </div>
                        </td>
                    </tr>
                `;
            }).join('');
        }

        // Refresh analytics
        function refreshAnalytics() {
            if (selectedCourseId) {
                loadCourseAnalytics(selectedCourseId);
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
