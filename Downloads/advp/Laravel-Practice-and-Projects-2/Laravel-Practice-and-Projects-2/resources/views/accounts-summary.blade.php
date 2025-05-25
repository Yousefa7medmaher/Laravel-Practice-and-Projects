<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accounts Summary - Educational Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-users text-3xl text-blue-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Educational Platform</h1>
            <p class="text-xl text-gray-600">Test Accounts & Auto-Login</p>
        </div>

        <!-- Accounts Grid -->
        <div class="grid md:grid-cols-2 gap-8 mb-8">
            <!-- Student Account -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-indigo-100">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user-graduate text-xl text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Student Account</h3>
                        <p class="text-gray-600">Full student access</p>
                    </div>
                </div>
                
                <div class="bg-indigo-50 rounded-lg p-4 mb-4">
                    <div class="space-y-2 text-sm">
                        <p><strong>Name:</strong> Zeyad</p>
                        <p><strong>Email:</strong> zeyad@gmail.com</p>
                        <p><strong>Password:</strong> 16102005</p>
                        <p><strong>Role:</strong> Student</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <a href="/auto-login" class="block w-full bg-indigo-600 text-white text-center py-3 rounded-lg hover:bg-indigo-700 transition-all">
                        <i class="fas fa-rocket mr-2"></i>Auto-Login as Student
                    </a>
                    <a href="/student/dashboard" class="block w-full bg-gray-100 text-gray-700 text-center py-2 rounded-lg hover:bg-gray-200 transition-all">
                        <i class="fas fa-tachometer-alt mr-2"></i>Student Dashboard
                    </a>
                </div>

                <div class="mt-4 text-xs text-gray-500">
                    <p><strong>Features:</strong> Course enrollment, assignments, profile, notifications</p>
                </div>
            </div>

            <!-- Manager Account -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-purple-100">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user-shield text-xl text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Manager Account</h3>
                        <p class="text-gray-600">Full platform management</p>
                    </div>
                </div>
                
                <div class="bg-purple-50 rounded-lg p-4 mb-4">
                    <div class="space-y-2 text-sm">
                        <p><strong>Name:</strong> Manager Admin</p>
                        <p><strong>Email:</strong> manager@gmail.com</p>
                        <p><strong>Password:</strong> manager123</p>
                        <p><strong>Role:</strong> Manager</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <a href="/manager-auto-login" class="block w-full bg-purple-600 text-white text-center py-3 rounded-lg hover:bg-purple-700 transition-all">
                        <i class="fas fa-rocket mr-2"></i>Auto-Login as Manager
                    </a>
                    <a href="/manager/dashboard" class="block w-full bg-gray-100 text-gray-700 text-center py-2 rounded-lg hover:bg-gray-200 transition-all">
                        <i class="fas fa-chart-line mr-2"></i>Manager Dashboard
                    </a>
                </div>

                <div class="mt-4 text-xs text-gray-500">
                    <p><strong>Features:</strong> Student management, course management, instructor oversight, reports</p>
                </div>
            </div>
        </div>

        <!-- Quick Access Links -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-link mr-2 text-blue-600"></i>Quick Access Links
            </h3>
            
            <div class="grid md:grid-cols-3 gap-4">
                <!-- Authentication -->
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Authentication</h4>
                    <div class="space-y-2 text-sm">
                        <a href="/login" class="block text-blue-600 hover:text-blue-800">Manual Login</a>
                        <a href="/register" class="block text-blue-600 hover:text-blue-800">Register New Account</a>
                    </div>
                </div>

                <!-- Student Pages -->
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Student Pages</h4>
                    <div class="space-y-2 text-sm">
                        <a href="/student/courses" class="block text-indigo-600 hover:text-indigo-800">My Courses</a>
                        <a href="/student/course-enrollment" class="block text-indigo-600 hover:text-indigo-800">Course Enrollment</a>
                        <a href="/student/assignments" class="block text-indigo-600 hover:text-indigo-800">Assignments</a>
                        <a href="/student/profile" class="block text-indigo-600 hover:text-indigo-800">Profile</a>
                        <a href="/student/notifications" class="block text-indigo-600 hover:text-indigo-800">Notifications</a>
                    </div>
                </div>

                <!-- Manager Pages -->
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Manager Pages</h4>
                    <div class="space-y-2 text-sm">
                        <a href="/manager/students" class="block text-purple-600 hover:text-purple-800">Manage Students</a>
                        <a href="/manager/courses" class="block text-purple-600 hover:text-purple-800">Manage Courses</a>
                        <a href="/manager/instructors" class="block text-purple-600 hover:text-purple-800">Manage Instructors</a>
                        <a href="/manager/reports" class="block text-purple-600 hover:text-purple-800">Reports & Analytics</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- API Testing -->
        <div class="mt-8 bg-gray-50 rounded-xl p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-code mr-2 text-green-600"></i>API Testing
            </h3>
            <div class="grid md:grid-cols-2 gap-4">
                <a href="/manager/api-test" class="block bg-green-100 text-green-800 text-center py-3 rounded-lg hover:bg-green-200 transition-all">
                    <i class="fas fa-flask mr-2"></i>Manager API Test
                </a>
                <a href="/api/documentation" class="block bg-blue-100 text-blue-800 text-center py-3 rounded-lg hover:bg-blue-200 transition-all">
                    <i class="fas fa-book mr-2"></i>API Documentation
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-gray-500">
            <p class="text-sm">Educational Platform - Test Environment</p>
            <p class="text-xs mt-1">All accounts are for testing purposes</p>
        </div>
    </div>
</body>
</html>
