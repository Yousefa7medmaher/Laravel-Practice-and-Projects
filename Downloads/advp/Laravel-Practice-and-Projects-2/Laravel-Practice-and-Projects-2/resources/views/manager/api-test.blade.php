<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager API Test - Educational Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto py-8 px-4">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Manager API Connection Test</h1>
            
            <!-- Test Results -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-gray-50 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Dashboard API</h2>
                    <div id="dashboard-test" class="space-y-2">
                        <div class="flex items-center">
                            <i class="fas fa-spinner fa-spin text-blue-500 mr-2"></i>
                            <span>Testing dashboard data...</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Students API</h2>
                    <div id="students-test" class="space-y-2">
                        <div class="flex items-center">
                            <i class="fas fa-spinner fa-spin text-blue-500 mr-2"></i>
                            <span>Testing students data...</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Courses API</h2>
                    <div id="courses-test" class="space-y-2">
                        <div class="flex items-center">
                            <i class="fas fa-spinner fa-spin text-blue-500 mr-2"></i>
                            <span>Testing courses data...</span>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Instructors API</h2>
                    <div id="instructors-test" class="space-y-2">
                        <div class="flex items-center">
                            <i class="fas fa-spinner fa-spin text-blue-500 mr-2"></i>
                            <span>Testing instructors data...</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Actions -->
            <div class="bg-gray-50 rounded-lg p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Test Actions</h2>
                <div class="flex space-x-4">
                    <button onclick="runAllTests()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-play mr-2"></i>Run All Tests
                    </button>
                    <button onclick="testCourseCreation()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        <i class="fas fa-plus mr-2"></i>Test Course Creation
                    </button>
                    <button onclick="clearResults()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                        <i class="fas fa-trash mr-2"></i>Clear Results
                    </button>
                </div>
            </div>

            <!-- Raw Data Display -->
            <div class="bg-gray-50 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Raw API Responses</h2>
                <div id="raw-data" class="bg-black text-green-400 p-4 rounded-lg font-mono text-sm max-h-96 overflow-y-auto">
                    <div>Waiting for test results...</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let authToken = localStorage.getItem('token');

        // API call utility
        async function apiCall(endpoint, method = 'GET', data = null) {
            const currentToken = localStorage.getItem('token');
            
            if (!currentToken) {
                return { status: 401, ok: false, error: 'No auth token' };
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
                const result = await response.json();
                return { status: response.status, ok: response.ok, data: result };
            } catch (error) {
                return { status: 0, ok: false, error: error.message };
            }
        }

        // Test functions
        async function testDashboardAPI() {
            const container = document.getElementById('dashboard-test');
            try {
                const result = await apiCall('/manager/dashboard-data');
                if (result.ok) {
                    container.innerHTML = `
                        <div class="flex items-center text-green-600">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Dashboard API: SUCCESS</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            Found ${result.data.data?.stats?.total_courses || 0} courses, 
                            ${result.data.data?.stats?.total_students || 0} students
                        </div>
                    `;
                    logData('Dashboard API', result.data);
                } else {
                    container.innerHTML = `
                        <div class="flex items-center text-red-600">
                            <i class="fas fa-times-circle mr-2"></i>
                            <span>Dashboard API: FAILED</span>
                        </div>
                        <div class="text-sm text-gray-600">${result.error || 'Unknown error'}</div>
                    `;
                }
            } catch (error) {
                container.innerHTML = `
                    <div class="flex items-center text-red-600">
                        <i class="fas fa-times-circle mr-2"></i>
                        <span>Dashboard API: ERROR</span>
                    </div>
                    <div class="text-sm text-gray-600">${error.message}</div>
                `;
            }
        }

        async function testStudentsAPI() {
            const container = document.getElementById('students-test');
            try {
                const result = await apiCall('/manager/students');
                if (result.ok) {
                    container.innerHTML = `
                        <div class="flex items-center text-green-600">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Students API: SUCCESS</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            Found ${result.data.data?.length || 0} students
                        </div>
                    `;
                    logData('Students API', result.data);
                } else {
                    container.innerHTML = `
                        <div class="flex items-center text-red-600">
                            <i class="fas fa-times-circle mr-2"></i>
                            <span>Students API: FAILED</span>
                        </div>
                        <div class="text-sm text-gray-600">${result.error || 'Unknown error'}</div>
                    `;
                }
            } catch (error) {
                container.innerHTML = `
                    <div class="flex items-center text-red-600">
                        <i class="fas fa-times-circle mr-2"></i>
                        <span>Students API: ERROR</span>
                    </div>
                    <div class="text-sm text-gray-600">${error.message}</div>
                `;
            }
        }

        async function testCoursesAPI() {
            const container = document.getElementById('courses-test');
            try {
                const result = await apiCall('/manager/courses');
                if (result.ok) {
                    container.innerHTML = `
                        <div class="flex items-center text-green-600">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Courses API: SUCCESS</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            Found ${result.data.data?.length || 0} courses
                        </div>
                    `;
                    logData('Courses API', result.data);
                } else {
                    container.innerHTML = `
                        <div class="flex items-center text-red-600">
                            <i class="fas fa-times-circle mr-2"></i>
                            <span>Courses API: FAILED</span>
                        </div>
                        <div class="text-sm text-gray-600">${result.error || 'Unknown error'}</div>
                    `;
                }
            } catch (error) {
                container.innerHTML = `
                    <div class="flex items-center text-red-600">
                        <i class="fas fa-times-circle mr-2"></i>
                        <span>Courses API: ERROR</span>
                    </div>
                    <div class="text-sm text-gray-600">${error.message}</div>
                `;
            }
        }

        async function testInstructorsAPI() {
            const container = document.getElementById('instructors-test');
            try {
                const result = await apiCall('/manager/instructors');
                if (result.ok) {
                    container.innerHTML = `
                        <div class="flex items-center text-green-600">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>Instructors API: SUCCESS</span>
                        </div>
                        <div class="text-sm text-gray-600">
                            Found ${result.data.data?.length || 0} instructors
                        </div>
                    `;
                    logData('Instructors API', result.data);
                } else {
                    container.innerHTML = `
                        <div class="flex items-center text-red-600">
                            <i class="fas fa-times-circle mr-2"></i>
                            <span>Instructors API: FAILED</span>
                        </div>
                        <div class="text-sm text-gray-600">${result.error || 'Unknown error'}</div>
                    `;
                }
            } catch (error) {
                container.innerHTML = `
                    <div class="flex items-center text-red-600">
                        <i class="fas fa-times-circle mr-2"></i>
                        <span>Instructors API: ERROR</span>
                    </div>
                    <div class="text-sm text-gray-600">${error.message}</div>
                `;
            }
        }

        async function testCourseCreation() {
            const testCourse = {
                title: 'Test Course API',
                code: 'TEST' + Date.now(),
                description: 'This is a test course created via API',
                credit_hours: 3,
                max_capacity: 25
            };

            try {
                const result = await apiCall('/courses', 'POST', testCourse);
                if (result.ok) {
                    alert('Course creation test: SUCCESS\nCourse ID: ' + result.data.data.id);
                    logData('Course Creation', result.data);
                } else {
                    alert('Course creation test: FAILED\n' + (result.data?.message || 'Unknown error'));
                }
            } catch (error) {
                alert('Course creation test: ERROR\n' + error.message);
            }
        }

        function logData(label, data) {
            const container = document.getElementById('raw-data');
            const timestamp = new Date().toLocaleTimeString();
            const logEntry = `[${timestamp}] ${label}:\n${JSON.stringify(data, null, 2)}\n\n`;
            container.innerHTML += logEntry;
            container.scrollTop = container.scrollHeight;
        }

        function clearResults() {
            document.getElementById('raw-data').innerHTML = '<div>Cleared...</div>';
        }

        async function runAllTests() {
            clearResults();
            logData('Test Session', { started: new Date().toISOString() });
            
            await testDashboardAPI();
            await testStudentsAPI();
            await testCoursesAPI();
            await testInstructorsAPI();
            
            logData('Test Session', { completed: new Date().toISOString() });
        }

        // Auto-run tests on page load
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken) {
                alert('No authentication token found. Please login first.');
                window.location.href = '/login';
                return;
            }
            
            setTimeout(runAllTests, 1000);
        });
    </script>
</body>
</html>
