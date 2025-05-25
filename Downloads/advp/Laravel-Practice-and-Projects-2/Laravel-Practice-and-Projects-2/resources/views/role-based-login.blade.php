<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role-Based Login - Educational Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-purple-50 min-h-screen py-8">
    <div class="container mx-auto px-4 max-w-6xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-users-cog text-3xl text-blue-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Role-Based Login System</h1>
            <p class="text-xl text-gray-600">Test accounts with automatic role-based redirection</p>
        </div>

        <!-- Role Cards -->
        <div class="grid md:grid-cols-3 gap-8 mb-8">
            <!-- Student Role -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-indigo-100">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user-graduate text-xl text-indigo-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Student</h3>
                        <p class="text-gray-600">Learning & Assignments</p>
                    </div>
                </div>
                
                <div class="bg-indigo-50 rounded-lg p-4 mb-4">
                    <div class="space-y-2 text-sm">
                        <p><strong>Name:</strong> Zeyad</p>
                        <p><strong>Email:</strong> zeyad@gmail.com</p>
                        <p><strong>Password:</strong> 16102005</p>
                        <p><strong>Role:</strong> Student</p>
                        <p><strong>Redirects to:</strong> /student/dashboard</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <button onclick="loginAs('student')" class="block w-full bg-indigo-600 text-white text-center py-3 rounded-lg hover:bg-indigo-700 transition-all">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login as Student
                    </button>
                    <a href="/student/dashboard" class="block w-full bg-gray-100 text-gray-700 text-center py-2 rounded-lg hover:bg-gray-200 transition-all">
                        <i class="fas fa-tachometer-alt mr-2"></i>Student Dashboard
                    </a>
                </div>

                <div class="mt-4 text-xs text-gray-500">
                    <p><strong>Access:</strong> Courses, assignments, profile, notifications</p>
                </div>
            </div>

            <!-- Manager Role -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-purple-100">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user-shield text-xl text-purple-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Manager</h3>
                        <p class="text-gray-600">Platform Administration</p>
                    </div>
                </div>
                
                <div class="bg-purple-50 rounded-lg p-4 mb-4">
                    <div class="space-y-2 text-sm">
                        <p><strong>Name:</strong> Manager Admin</p>
                        <p><strong>Email:</strong> manager@gmail.com</p>
                        <p><strong>Password:</strong> manager123</p>
                        <p><strong>Role:</strong> Manager</p>
                        <p><strong>Redirects to:</strong> /manager/dashboard</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <button onclick="loginAs('manager')" class="block w-full bg-purple-600 text-white text-center py-3 rounded-lg hover:bg-purple-700 transition-all">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login as Manager
                    </button>
                    <a href="/manager/dashboard" class="block w-full bg-gray-100 text-gray-700 text-center py-2 rounded-lg hover:bg-gray-200 transition-all">
                        <i class="fas fa-chart-line mr-2"></i>Manager Dashboard
                    </a>
                </div>

                <div class="mt-4 text-xs text-gray-500">
                    <p><strong>Access:</strong> Full platform management, analytics, user oversight</p>
                </div>
            </div>

            <!-- Instructor Role -->
            <div class="bg-white rounded-xl shadow-lg p-6 border border-green-100">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-chalkboard-teacher text-xl text-green-600"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Instructor</h3>
                        <p class="text-gray-600">Teaching & Grading</p>
                    </div>
                </div>
                
                <div class="bg-green-50 rounded-lg p-4 mb-4">
                    <div class="space-y-2 text-sm">
                        <p><strong>Name:</strong> Dr. Instructor</p>
                        <p><strong>Email:</strong> instructor@gmail.com</p>
                        <p><strong>Password:</strong> instructor123</p>
                        <p><strong>Role:</strong> Instructor</p>
                        <p><strong>Redirects to:</strong> /instructor/dashboard</p>
                    </div>
                </div>

                <div class="space-y-3">
                    <button onclick="loginAs('instructor')" class="block w-full bg-green-600 text-white text-center py-3 rounded-lg hover:bg-green-700 transition-all">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login as Instructor
                    </button>
                    <a href="/instructor/dashboard" class="block w-full bg-gray-100 text-gray-700 text-center py-2 rounded-lg hover:bg-gray-200 transition-all">
                        <i class="fas fa-graduation-cap mr-2"></i>Instructor Dashboard
                    </a>
                </div>

                <div class="mt-4 text-xs text-gray-500">
                    <p><strong>Access:</strong> Course management, grading, student progress</p>
                </div>
            </div>
        </div>

        <!-- Login Status -->
        <div id="login-status" class="hidden bg-white rounded-xl shadow-lg p-6 mb-8">
            <div class="text-center">
                <div id="login-loading" class="hidden">
                    <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-600 mx-auto mb-4"></div>
                    <p class="text-gray-600">Logging in...</p>
                </div>
                <div id="login-success" class="hidden">
                    <div class="text-green-600 mb-4">
                        <i class="fas fa-check-circle text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Login Successful!</h3>
                    <p class="text-gray-600 mb-4">Redirecting to your dashboard...</p>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <p class="text-sm text-green-800">
                            <strong>Welcome, <span id="user-name"></span>!</strong><br>
                            Role: <span id="user-role"></span><br>
                            Redirecting to: <span id="redirect-url"></span>
                        </p>
                    </div>
                </div>
                <div id="login-error" class="hidden">
                    <div class="text-red-600 mb-4">
                        <i class="fas fa-exclamation-circle text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Login Failed</h3>
                    <p class="text-red-600 mb-4" id="error-message"></p>
                </div>
            </div>
        </div>

        <!-- Manual Login -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4">
                <i class="fas fa-key mr-2 text-blue-600"></i>Manual Login
            </h3>
            <p class="text-gray-600 mb-4">Or use the regular login page with any of the credentials above:</p>
            <a href="/login" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-all">
                <i class="fas fa-sign-in-alt mr-2"></i>Go to Login Page
            </a>
        </div>
    </div>

    <script>
        // Account credentials
        const accounts = {
            student: {
                email: 'zeyad@gmail.com',
                password: '16102005',
                name: 'Zeyad',
                role: 'student'
            },
            manager: {
                email: 'manager@gmail.com',
                password: 'manager123',
                name: 'Manager Admin',
                role: 'manager'
            },
            instructor: {
                email: 'instructor@gmail.com',
                password: 'instructor123',
                name: 'Dr. Instructor',
                role: 'instructor'
            }
        };

        async function loginAs(role) {
            const account = accounts[role];
            if (!account) {
                alert('Invalid role');
                return;
            }

            // Show login status
            document.getElementById('login-status').classList.remove('hidden');
            document.getElementById('login-loading').classList.remove('hidden');
            document.getElementById('login-success').classList.add('hidden');
            document.getElementById('login-error').classList.add('hidden');

            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: account.email,
                        password: account.password
                    })
                });

                const data = await response.json();

                if (response.ok && data.access_token) {
                    // Store authentication data
                    localStorage.setItem('token', data.access_token);
                    localStorage.setItem('user', JSON.stringify(data.user));
                    localStorage.setItem('role', data.user.role);

                    // Show success
                    document.getElementById('login-loading').classList.add('hidden');
                    document.getElementById('login-success').classList.remove('hidden');
                    document.getElementById('user-name').textContent = data.user.name;
                    document.getElementById('user-role').textContent = data.user.role.toUpperCase();
                    document.getElementById('redirect-url').textContent = data.redirect_url;

                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = data.redirect_url;
                    }, 2000);

                } else {
                    throw new Error(data.message || 'Login failed');
                }

            } catch (error) {
                console.error('Login error:', error);
                
                // Show error
                document.getElementById('login-loading').classList.add('hidden');
                document.getElementById('login-error').classList.remove('hidden');
                document.getElementById('error-message').textContent = error.message;
            }
        }
    </script>
</body>
</html>
