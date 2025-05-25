<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🧪 Assignment Management Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100 min-h-screen p-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">
                <i class="fas fa-flask text-blue-600 mr-2"></i>🧪 Assignment Management Test
            </h1>

            <!-- Test Results -->
            <div id="test-results" class="space-y-4 mb-8">
                <!-- Results will be displayed here -->
            </div>

            <!-- Test Buttons -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <button onclick="testCreateAssignment()" class="bg-green-600 text-white px-4 py-3 rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Create Assignment
                </button>
                <button onclick="testGetAssignments()" class="bg-blue-600 text-white px-4 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-list mr-2"></i>Get Assignments
                </button>
                <button onclick="testUpdateAssignment()" class="bg-yellow-600 text-white px-4 py-3 rounded-lg hover:bg-yellow-700 transition-colors">
                    <i class="fas fa-edit mr-2"></i>Update Assignment
                </button>
                <button onclick="testDeleteAssignment()" class="bg-red-600 text-white px-4 py-3 rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-trash mr-2"></i>Delete Assignment
                </button>
            </div>

            <!-- Assignment List -->
            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">📋 Current Assignments</h2>
                <div id="assignments-list" class="space-y-3">
                    <!-- Assignments will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        // Test configuration
        const authToken = localStorage.getItem('token') || 'test-token';
        const testCourseId = 1; // Use course ID 1 for testing
        let testAssignmentId = null;

        // API call utility
        async function apiCall(endpoint, method = 'GET', data = null, isFormData = false) {
            const config = {
                method: method,
                headers: {
                    'Authorization': `Bearer ${authToken}`,
                    'Accept': 'application/json'
                }
            };

            if (!isFormData) {
                config.headers['Content-Type'] = 'application/json';
            }

            if (data && (method === 'POST' || method === 'PUT' || method === 'PATCH')) {
                if (isFormData) {
                    config.body = data;
                } else {
                    config.body = JSON.stringify(data);
                }
            }

            try {
                const response = await fetch(`/api${endpoint}`, config);
                let result;
                
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    result = await response.json();
                } else {
                    result = { message: await response.text() };
                }

                return {
                    ok: response.ok,
                    status: response.status,
                    data: result
                };
            } catch (error) {
                console.error('API call failed:', error);
                return {
                    ok: false,
                    status: 500,
                    data: { message: 'Network error' }
                };
            }
        }

        // Display test result
        function displayResult(testName, success, message, data = null) {
            const resultsContainer = document.getElementById('test-results');
            const resultDiv = document.createElement('div');
            resultDiv.className = `p-4 rounded-lg border ${success ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'}`;
            
            resultDiv.innerHTML = `
                <div class="flex items-start">
                    <i class="fas fa-${success ? 'check-circle text-green-600' : 'exclamation-circle text-red-600'} mr-3 mt-1"></i>
                    <div class="flex-1">
                        <h3 class="font-semibold ${success ? 'text-green-800' : 'text-red-800'}">${testName}</h3>
                        <p class="text-sm ${success ? 'text-green-700' : 'text-red-700'} mt-1">${message}</p>
                        ${data ? `<pre class="text-xs bg-gray-100 p-2 rounded mt-2 overflow-x-auto">${JSON.stringify(data, null, 2)}</pre>` : ''}
                    </div>
                </div>
            `;
            
            resultsContainer.appendChild(resultDiv);
            resultsContainer.scrollTop = resultsContainer.scrollHeight;
        }

        // Test functions
        async function testCreateAssignment() {
            const testData = {
                title: `Test Assignment ${Date.now()}`,
                description: 'This is a test assignment created by the assignment management test.',
                instructions: 'Complete this test assignment to verify the system is working.',
                due_date: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString(), // 7 days from now
                max_score: 100,
                allow_late_submission: true,
                is_visible: true
            };

            try {
                const result = await apiCall(`/instructor/courses/${testCourseId}/assignments`, 'POST', testData);
                
                if (result.ok) {
                    testAssignmentId = result.data.data?.id || result.data.id;
                    displayResult('✅ Create Assignment', true, 'Assignment created successfully!', result.data);
                    loadAssignments(); // Refresh the list
                } else {
                    displayResult('❌ Create Assignment', false, `Failed: ${result.data.message}`, result.data);
                }
            } catch (error) {
                displayResult('❌ Create Assignment', false, `Error: ${error.message}`);
            }
        }

        async function testGetAssignments() {
            try {
                const result = await apiCall('/instructor/assignments');
                
                if (result.ok) {
                    displayResult('📋 Get Assignments', true, `Found ${result.data.data?.length || 0} assignments`, result.data);
                    loadAssignments(); // Refresh the list
                } else {
                    displayResult('❌ Get Assignments', false, `Failed: ${result.data.message}`, result.data);
                }
            } catch (error) {
                displayResult('❌ Get Assignments', false, `Error: ${error.message}`);
            }
        }

        async function testUpdateAssignment() {
            if (!testAssignmentId) {
                displayResult('⚠️ Update Assignment', false, 'No test assignment ID available. Create an assignment first.');
                return;
            }

            const updateData = {
                title: `Updated Test Assignment ${Date.now()}`,
                description: 'This assignment has been updated by the test system.',
                instructions: 'Updated instructions for the test assignment.',
                due_date: new Date(Date.now() + 14 * 24 * 60 * 60 * 1000).toISOString(), // 14 days from now
                max_score: 150,
                allow_late_submission: false,
                is_visible: true
            };

            try {
                const result = await apiCall(`/instructor/assignments/${testAssignmentId}`, 'PUT', updateData);
                
                if (result.ok) {
                    displayResult('✏️ Update Assignment', true, 'Assignment updated successfully!', result.data);
                    loadAssignments(); // Refresh the list
                } else {
                    displayResult('❌ Update Assignment', false, `Failed: ${result.data.message}`, result.data);
                }
            } catch (error) {
                displayResult('❌ Update Assignment', false, `Error: ${error.message}`);
            }
        }

        async function testDeleteAssignment() {
            if (!testAssignmentId) {
                displayResult('⚠️ Delete Assignment', false, 'No test assignment ID available. Create an assignment first.');
                return;
            }

            if (!confirm('Are you sure you want to delete the test assignment?')) {
                return;
            }

            try {
                const result = await apiCall(`/instructor/assignments/${testAssignmentId}`, 'DELETE');
                
                if (result.ok) {
                    displayResult('🗑️ Delete Assignment', true, 'Assignment deleted successfully!', result.data);
                    testAssignmentId = null;
                    loadAssignments(); // Refresh the list
                } else {
                    displayResult('❌ Delete Assignment', false, `Failed: ${result.data.message}`, result.data);
                }
            } catch (error) {
                displayResult('❌ Delete Assignment', false, `Error: ${error.message}`);
            }
        }

        // Load and display assignments
        async function loadAssignments() {
            try {
                const result = await apiCall('/instructor/assignments');
                const assignmentsList = document.getElementById('assignments-list');
                
                if (result.ok && result.data.data) {
                    const assignments = result.data.data;
                    
                    if (assignments.length === 0) {
                        assignmentsList.innerHTML = '<p class="text-gray-600 text-center py-4">No assignments found</p>';
                        return;
                    }

                    assignmentsList.innerHTML = assignments.map(assignment => `
                        <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-gray-900">${assignment.title}</h3>
                                    <p class="text-sm text-gray-600 mt-1">${assignment.description}</p>
                                    <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                        <span><i class="fas fa-calendar mr-1"></i>Due: ${new Date(assignment.due_date).toLocaleDateString()}</span>
                                        <span><i class="fas fa-star mr-1"></i>Points: ${assignment.max_score || assignment.points || 'N/A'}</span>
                                        <span><i class="fas fa-eye mr-1"></i>${assignment.is_visible ? 'Visible' : 'Hidden'}</span>
                                    </div>
                                </div>
                                <div class="flex space-x-2">
                                    <button onclick="testAssignmentId = ${assignment.id}; testUpdateAssignment()" 
                                            class="text-blue-600 hover:text-blue-800 text-sm">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="testAssignmentId = ${assignment.id}; testDeleteAssignment()" 
                                            class="text-red-600 hover:text-red-800 text-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    assignmentsList.innerHTML = '<p class="text-red-600 text-center py-4">Failed to load assignments</p>';
                }
            } catch (error) {
                console.error('Error loading assignments:', error);
                document.getElementById('assignments-list').innerHTML = '<p class="text-red-600 text-center py-4">Error loading assignments</p>';
            }
        }

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            displayResult('🚀 System Status', true, 'Assignment management test page loaded successfully!');
            loadAssignments();
        });
    </script>
</body>
</html>
