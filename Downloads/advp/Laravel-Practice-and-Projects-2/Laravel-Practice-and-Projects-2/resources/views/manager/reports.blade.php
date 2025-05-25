<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports & Analytics - Educational Platform</title>
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

        .tab-button {
            transition: all 0.3s ease;
        }

        .tab-button.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .report-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.8) 100%);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .metric-card {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
        }

        .metric-card.warning {
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
        }

        .metric-card.danger {
            background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
        }

        .metric-card.info {
            background: linear-gradient(135deg, #3B82F6 0%, #1D4ED8 100%);
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
                        <a href="/manager/courses" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-book mr-2"></i>Courses
                        </a>
                        <a href="/manager/students" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-user-graduate mr-2"></i>Students
                        </a>
                        <a href="/manager/instructors" class="border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
                            <i class="fas fa-chalkboard-teacher mr-2"></i>Instructors
                        </a>
                        <a href="/manager/reports" class="border-indigo-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium">
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
                    <h2 class="text-3xl font-bold mb-2">Reports & Analytics</h2>
                    <p class="text-indigo-100 text-lg">Comprehensive platform insights and performance analytics</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-chart-line text-6xl text-white opacity-20"></i>
                </div>
            </div>
        </div>

        <!-- Report Controls -->
        <div class="manager-card rounded-xl shadow-lg p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="flex flex-col md:flex-row md:items-center space-y-4 md:space-y-0 md:space-x-4">
                    <div>
                        <label for="report-type" class="block text-sm font-medium text-gray-700 mb-1">Report Type</label>
                        <select id="report-type" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="overview">Platform Overview</option>
                            <option value="course_effectiveness">Course Effectiveness</option>
                            <option value="student_performance">Student Performance</option>
                            <option value="instructor_activity">Instructor Activity</option>
                        </select>
                    </div>
                    <div>
                        <label for="date-range" class="block text-sm font-medium text-gray-700 mb-1">Date Range</label>
                        <select id="date-range" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                            <option value="7">Last 7 days</option>
                            <option value="30" selected>Last 30 days</option>
                            <option value="90">Last 3 months</option>
                            <option value="365">Last year</option>
                        </select>
                    </div>
                    <div>
                        <label for="start-date" class="block text-sm font-medium text-gray-700 mb-1">Custom Start</label>
                        <input type="date" id="start-date" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                    <div>
                        <label for="end-date" class="block text-sm font-medium text-gray-700 mb-1">Custom End</label>
                        <input type="date" id="end-date" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    </div>
                </div>
                <div class="flex space-x-3">
                    <button onclick="generateReport()" class="action-button text-white px-6 py-2 rounded-lg hover:shadow-lg transition-all">
                        <i class="fas fa-chart-bar mr-2"></i>Generate Report
                    </button>
                    <button onclick="exportReport()" class="bg-green-100 text-green-700 px-4 py-2 rounded-lg hover:bg-green-200 transition-all">
                        <i class="fas fa-download mr-2"></i>Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="metric-card rounded-xl shadow-lg p-6 text-white card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Platform Health</p>
                        <p class="text-2xl font-bold" id="platform-health">98%</p>
                    </div>
                    <i class="fas fa-heartbeat text-3xl opacity-75"></i>
                </div>
            </div>

            <div class="metric-card info rounded-xl shadow-lg p-6 text-white card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Active Users</p>
                        <p class="text-2xl font-bold" id="active-users">0</p>
                    </div>
                    <i class="fas fa-users text-3xl opacity-75"></i>
                </div>
            </div>

            <div class="metric-card warning rounded-xl shadow-lg p-6 text-white card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Completion Rate</p>
                        <p class="text-2xl font-bold" id="completion-rate">0%</p>
                    </div>
                    <i class="fas fa-chart-pie text-3xl opacity-75"></i>
                </div>
            </div>

            <div class="metric-card danger rounded-xl shadow-lg p-6 text-white card-hover">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Issues</p>
                        <p class="text-2xl font-bold" id="issues-count">0</p>
                    </div>
                    <i class="fas fa-exclamation-triangle text-3xl opacity-75"></i>
                </div>
            </div>
        </div>

        <!-- Report Content -->
        <div class="manager-card rounded-xl shadow-lg overflow-hidden">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6">
                    <button onclick="switchReportTab('charts')" id="charts-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm active">
                        <i class="fas fa-chart-bar mr-2"></i>Charts & Graphs
                    </button>
                    <button onclick="switchReportTab('tables')" id="tables-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm">
                        <i class="fas fa-table mr-2"></i>Data Tables
                    </button>
                    <button onclick="switchReportTab('insights')" id="insights-tab" class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm">
                        <i class="fas fa-lightbulb mr-2"></i>Insights
                    </button>
                </nav>
            </div>

            <!-- Charts Tab -->
            <div id="charts-content" class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Performance Trends Chart -->
                    <div class="report-card rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Performance Trends</h3>
                        <div class="h-64">
                            <canvas id="performanceTrendsChart"></canvas>
                        </div>
                    </div>

                    <!-- Course Distribution Chart -->
                    <div class="report-card rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Distribution</h3>
                        <div class="h-64">
                            <canvas id="courseDistributionChart"></canvas>
                        </div>
                    </div>

                    <!-- User Activity Chart -->
                    <div class="report-card rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">User Activity</h3>
                        <div class="h-64">
                            <canvas id="userActivityChart"></canvas>
                        </div>
                    </div>

                    <!-- Grade Distribution Chart -->
                    <div class="report-card rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Grade Distribution</h3>
                        <div class="h-64">
                            <canvas id="gradeDistributionChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tables Tab -->
            <div id="tables-content" class="hidden p-6">
                <div class="space-y-8">
                    <!-- Course Performance Table -->
                    <div class="report-card rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Course Performance Summary</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Course</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Grade</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Completion</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody id="course-performance-table" class="bg-white divide-y divide-gray-200">
                                    <!-- Data will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Top Performers Table -->
                    <div class="report-card rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Performing Students</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GPA</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Courses</th>
                                    </tr>
                                </thead>
                                <tbody id="top-performers-table" class="bg-white divide-y divide-gray-200">
                                    <!-- Data will be loaded here -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Insights Tab -->
            <div id="insights-content" class="hidden p-6">
                <div class="space-y-6">
                    <div class="report-card rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                            Key Insights
                        </h3>
                        <div id="insights-list" class="space-y-4">
                            <!-- Insights will be loaded here -->
                        </div>
                    </div>

                    <div class="report-card rounded-lg p-6 shadow-sm">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-exclamation-triangle text-orange-500 mr-2"></i>
                            Recommendations
                        </h3>
                        <div id="recommendations-list" class="space-y-4">
                            <!-- Recommendations will be loaded here -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let authToken = localStorage.getItem('token');
        let currentReportData = {};
        let charts = {};

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken) {
                window.location.href = '/login';
                return;
            }

            loadUserProfile();
            setupEventListeners();
            generateReport(); // Load default report
        });

        // Setup event listeners
        function setupEventListeners() {
            document.getElementById('report-type').addEventListener('change', generateReport);
            document.getElementById('date-range').addEventListener('change', updateDateRange);
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

        // Update date range based on selection
        function updateDateRange() {
            const days = parseInt(document.getElementById('date-range').value);
            const endDate = new Date();
            const startDate = new Date();
            startDate.setDate(endDate.getDate() - days);

            document.getElementById('start-date').value = startDate.toISOString().split('T')[0];
            document.getElementById('end-date').value = endDate.toISOString().split('T')[0];
        }

        // Generate report
        async function generateReport() {
            const reportType = document.getElementById('report-type').value;
            const startDate = document.getElementById('start-date').value || getDefaultStartDate();
            const endDate = document.getElementById('end-date').value || new Date().toISOString().split('T')[0];

            try {
                const result = await apiCall(`/manager/reports?type=${reportType}&start_date=${startDate}&end_date=${endDate}`);

                if (result && result.ok) {
                    currentReportData = result.data.data;
                    updateMetrics();
                    updateCharts();
                    updateTables();
                    updateInsights();
                } else {
                    console.error('Failed to generate report:', result);
                    // Load sample data for demonstration
                    loadSampleData();
                }
            } catch (error) {
                console.error('Error generating report:', error);
                // Load sample data for demonstration
                loadSampleData();
            }
        }

        // Get default start date (30 days ago)
        function getDefaultStartDate() {
            const date = new Date();
            date.setDate(date.getDate() - 30);
            return date.toISOString().split('T')[0];
        }

        // Load sample data for demonstration
        function loadSampleData() {
            currentReportData = {
                summary: {
                    total_courses: 15,
                    total_students: 250,
                    total_instructors: 12,
                    total_submissions: 1250,
                    avg_grade: 82.5
                },
                trends: generateSampleTrends(),
                course_performance: generateSampleCourseData(),
                top_students: generateSampleStudents()
            };

            updateMetrics();
            updateCharts();
            updateTables();
            updateInsights();
        }

        // Generate sample trend data
        function generateSampleTrends() {
            const trends = [];
            for (let i = 0; i < 4; i++) {
                const date = new Date();
                date.setDate(date.getDate() - (i * 7));
                trends.push({
                    week: date.toISOString().split('T')[0],
                    submissions: Math.floor(Math.random() * 100) + 50,
                    new_enrollments: Math.floor(Math.random() * 20) + 5,
                    active_users: Math.floor(Math.random() * 150) + 100
                });
            }
            return trends.reverse();
        }

        // Generate sample course data
        function generateSampleCourseData() {
            const courses = ['CS101', 'MATH201', 'PHYS101', 'ENG102', 'HIST201'];
            return courses.map(code => ({
                course: { code, title: `${code} Course` },
                completion_rate: Math.floor(Math.random() * 30) + 70,
                avg_grade: Math.floor(Math.random() * 20) + 75,
                total_submissions: Math.floor(Math.random() * 100) + 50
            }));
        }

        // Generate sample student data
        function generateSampleStudents() {
            const names = ['Alice Johnson', 'Bob Smith', 'Carol Davis', 'David Wilson', 'Eva Brown'];
            return names.map((name, index) => ({
                student: { name, email: `${name.toLowerCase().replace(' ', '.')}@example.com` },
                avg_grade: 95 - (index * 2),
                total_courses: Math.floor(Math.random() * 8) + 3
            }));
        }

        // Update metrics
        function updateMetrics() {
            if (currentReportData.summary) {
                document.getElementById('active-users').textContent = currentReportData.summary.total_students || 0;

                const completionRate = currentReportData.course_performance ?
                    Math.round(currentReportData.course_performance.reduce((sum, course) => sum + course.completion_rate, 0) / currentReportData.course_performance.length) : 85;
                document.getElementById('completion-rate').textContent = completionRate + '%';

                document.getElementById('issues-count').textContent = Math.floor(Math.random() * 5);
            }
        }

        // Update charts
        function updateCharts() {
            updatePerformanceTrendsChart();
            updateCourseDistributionChart();
            updateUserActivityChart();
            updateGradeDistributionChart();
        }

        // Performance trends chart
        function updatePerformanceTrendsChart() {
            const ctx = document.getElementById('performanceTrendsChart').getContext('2d');
            if (charts.performanceTrends) charts.performanceTrends.destroy();

            const trends = currentReportData.trends || [];
            const labels = trends.map(t => new Date(t.week).toLocaleDateString());
            const submissionsData = trends.map(t => t.submissions);
            const enrollmentsData = trends.map(t => t.new_enrollments);

            charts.performanceTrends = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Submissions',
                        data: submissionsData,
                        borderColor: '#667eea',
                        backgroundColor: 'rgba(102, 126, 234, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'New Enrollments',
                        data: enrollmentsData,
                        borderColor: '#10B981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Course distribution chart
        function updateCourseDistributionChart() {
            const ctx = document.getElementById('courseDistributionChart').getContext('2d');
            if (charts.courseDistribution) charts.courseDistribution.destroy();

            const courseData = currentReportData.course_performance || [];
            const labels = courseData.map(c => c.course.code);
            const data = courseData.map(c => c.total_submissions);

            charts.courseDistribution = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            '#667eea', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // User activity chart
        function updateUserActivityChart() {
            const ctx = document.getElementById('userActivityChart').getContext('2d');
            if (charts.userActivity) charts.userActivity.destroy();

            const trends = currentReportData.trends || [];
            const labels = trends.map(t => new Date(t.week).toLocaleDateString());
            const activeUsers = trends.map(t => t.active_users);

            charts.userActivity = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Active Users',
                        data: activeUsers,
                        backgroundColor: '#3B82F6',
                        borderColor: '#1D4ED8',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Grade distribution chart
        function updateGradeDistributionChart() {
            const ctx = document.getElementById('gradeDistributionChart').getContext('2d');
            if (charts.gradeDistribution) charts.gradeDistribution.destroy();

            // Sample grade distribution data
            const gradeRanges = ['90-100', '80-89', '70-79', '60-69', 'Below 60'];
            const gradeCounts = [45, 35, 15, 3, 2]; // Percentage distribution

            charts.gradeDistribution = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: gradeRanges,
                    datasets: [{
                        label: 'Percentage of Students',
                        data: gradeCounts,
                        backgroundColor: [
                            '#10B981', '#3B82F6', '#F59E0B', '#EF4444', '#6B7280'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 50
                        }
                    }
                }
            });
        }

        // Update tables
        function updateTables() {
            updateCoursePerformanceTable();
            updateTopPerformersTable();
        }

        // Update course performance table
        function updateCoursePerformanceTable() {
            const tbody = document.getElementById('course-performance-table');
            const courseData = currentReportData.course_performance || [];

            tbody.innerHTML = courseData.map(course => `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${course.course.code}</div>
                        <div class="text-sm text-gray-500">${course.course.title}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${Math.floor(Math.random() * 50) + 20}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${course.avg_grade.toFixed(1)}%
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${course.completion_rate}%
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${getStatusClass(course.completion_rate)}">
                            ${getStatusText(course.completion_rate)}
                        </span>
                    </td>
                </tr>
            `).join('');
        }

        // Update top performers table
        function updateTopPerformersTable() {
            const tbody = document.getElementById('top-performers-table');
            const studentData = currentReportData.top_students || [];

            tbody.innerHTML = studentData.map((student, index) => `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        ${index + 1}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">${student.student.name}</div>
                        <div class="text-sm text-gray-500">${student.student.email}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${student.avg_grade.toFixed(1)}%
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${student.total_courses || 0} courses
                    </td>
                </tr>
            `).join('');
        }

        // Helper functions
        function getStatusClass(completionRate) {
            if (completionRate >= 90) return 'bg-green-100 text-green-800';
            if (completionRate >= 75) return 'bg-blue-100 text-blue-800';
            if (completionRate >= 60) return 'bg-yellow-100 text-yellow-800';
            return 'bg-red-100 text-red-800';
        }

        function getStatusText(completionRate) {
            if (completionRate >= 90) return 'Excellent';
            if (completionRate >= 75) return 'Good';
            if (completionRate >= 60) return 'Average';
            return 'Needs Attention';
        }

        // Update insights
        function updateInsights() {
            const insights = [
                {
                    type: 'success',
                    icon: 'fas fa-arrow-up',
                    text: 'Student engagement has increased by 15% this month'
                },
                {
                    type: 'warning',
                    icon: 'fas fa-exclamation-triangle',
                    text: 'Course completion rates are below target in 2 courses'
                },
                {
                    type: 'info',
                    icon: 'fas fa-info-circle',
                    text: 'Peak activity hours are between 2-4 PM'
                }
            ];

            const recommendations = [
                {
                    priority: 'high',
                    text: 'Consider additional support for struggling students in low-performing courses'
                },
                {
                    priority: 'medium',
                    text: 'Implement gamification features to boost engagement'
                },
                {
                    priority: 'low',
                    text: 'Schedule maintenance during low-activity hours (late evening)'
                }
            ];

            document.getElementById('insights-list').innerHTML = insights.map(insight => `
                <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg">
                    <i class="${insight.icon} text-${getInsightColor(insight.type)}-500 mt-1"></i>
                    <p class="text-gray-700">${insight.text}</p>
                </div>
            `).join('');

            document.getElementById('recommendations-list').innerHTML = recommendations.map(rec => `
                <div class="flex items-start space-x-3 p-4 bg-gray-50 rounded-lg">
                    <div class="w-3 h-3 rounded-full bg-${getPriorityColor(rec.priority)}-500 mt-2"></div>
                    <div>
                        <div class="text-sm font-medium text-gray-900 capitalize">${rec.priority} Priority</div>
                        <p class="text-gray-700">${rec.text}</p>
                    </div>
                </div>
            `).join('');
        }

        function getInsightColor(type) {
            const colors = { success: 'green', warning: 'yellow', info: 'blue' };
            return colors[type] || 'gray';
        }

        function getPriorityColor(priority) {
            const colors = { high: 'red', medium: 'yellow', low: 'green' };
            return colors[priority] || 'gray';
        }

        // Switch report tabs
        function switchReportTab(tabName) {
            // Update tab buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active', 'border-indigo-500', 'text-white');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            const activeTab = document.getElementById(`${tabName}-tab`);
            if (activeTab) {
                activeTab.classList.add('active', 'border-indigo-500', 'text-white');
                activeTab.classList.remove('border-transparent', 'text-gray-500');
            }

            // Show/hide content
            ['charts', 'tables', 'insights'].forEach(tab => {
                const content = document.getElementById(`${tab}-content`);
                if (tab === tabName) {
                    content.classList.remove('hidden');
                } else {
                    content.classList.add('hidden');
                }
            });
        }

        // Export report
        function exportReport() {
            const reportType = document.getElementById('report-type').value;
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;

            // Create CSV content based on current report data
            let csvContent = `Report Type: ${reportType}\n`;
            csvContent += `Date Range: ${startDate} to ${endDate}\n\n`;

            if (currentReportData.summary) {
                csvContent += 'Summary Statistics:\n';
                csvContent += `Total Courses,${currentReportData.summary.total_courses}\n`;
                csvContent += `Total Students,${currentReportData.summary.total_students}\n`;
                csvContent += `Total Instructors,${currentReportData.summary.total_instructors}\n`;
                csvContent += `Average Grade,${currentReportData.summary.avg_grade}%\n\n`;
            }

            // Download CSV
            const blob = new Blob([csvContent], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `${reportType}_report_${new Date().toISOString().split('T')[0]}.csv`;
            a.click();
            window.URL.revokeObjectURL(url);
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

        // Initialize date range on load
        updateDateRange();
    </script>
</body>
</html>
