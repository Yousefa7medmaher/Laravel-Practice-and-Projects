<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Auto Login - Educational Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-purple-50 via-white to-indigo-50 min-h-screen flex items-center justify-center">
    <div class="bg-white rounded-xl shadow-2xl p-8 max-w-md w-full mx-4 border border-purple-100">
        <div class="text-center mb-6">
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-user-shield text-2xl text-purple-600"></i>
            </div>
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Manager Auto Login</h1>
            <p class="text-gray-600">Logging in as Manager Admin...</p>
        </div>

        <div id="login-status" class="space-y-4">
            <div class="flex items-center justify-center">
                <div class="animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-purple-600"></div>
            </div>
            <p class="text-center text-gray-600">Authenticating manager credentials...</p>
        </div>

        <div id="login-success" class="hidden text-center">
            <div class="text-green-600 mb-4">
                <i class="fas fa-check-circle text-4xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Manager Login Successful!</h3>
            <p class="text-gray-600 mb-4">Redirecting to manager dashboard...</p>
            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                <p class="text-sm text-purple-800">
                    <strong>Welcome, <span id="user-name"></span>!</strong><br>
                    Role: <span id="user-role" class="font-semibold"></span><br>
                    Access Level: <span class="text-purple-600 font-semibold">Full Platform Management</span>
                </p>
            </div>
        </div>

        <div id="login-error" class="hidden text-center">
            <div class="text-red-600 mb-4">
                <i class="fas fa-exclamation-circle text-4xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Login Failed</h3>
            <p class="text-red-600 mb-4" id="error-message"></p>
            <button onclick="retryLogin()" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition-all">
                <i class="fas fa-redo mr-2"></i>Retry
            </button>
        </div>

        <div class="mt-6 text-center">
            <div class="bg-gray-50 rounded-lg p-4">
                <h4 class="font-semibold text-gray-900 mb-2">Manager Credentials</h4>
                <div class="text-sm text-gray-600 space-y-1">
                    <p><strong>Email:</strong> manager@gmail.com</p>
                    <p><strong>Password:</strong> manager123</p>
                    <p><strong>Role:</strong> Manager</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Manager auto login credentials
        const credentials = {
            email: 'manager@gmail.com',
            password: 'manager123'
        };

        // Auto login function
        async function autoLogin() {
            try {
                const response = await fetch('/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(credentials)
                });

                const data = await response.json();

                if (response.ok && data.access_token) {
                    // Store authentication data
                    localStorage.setItem('token', data.access_token);
                    localStorage.setItem('user', JSON.stringify(data.user));
                    localStorage.setItem('role', data.user.role);

                    // Show success message
                    document.getElementById('login-status').classList.add('hidden');
                    document.getElementById('login-success').classList.remove('hidden');
                    document.getElementById('user-name').textContent = data.user.name;
                    document.getElementById('user-role').textContent = data.user.role.toUpperCase();

                    // Redirect after 2 seconds
                    setTimeout(() => {
                        window.location.href = '/manager/dashboard';
                    }, 2000);

                } else {
                    throw new Error(data.message || 'Manager login failed');
                }

            } catch (error) {
                console.error('Manager login error:', error);
                
                // Show error message
                document.getElementById('login-status').classList.add('hidden');
                document.getElementById('login-error').classList.remove('hidden');
                document.getElementById('error-message').textContent = error.message;
            }
        }

        // Retry login function
        function retryLogin() {
            document.getElementById('login-error').classList.add('hidden');
            document.getElementById('login-status').classList.remove('hidden');
            autoLogin();
        }

        // Start auto login when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Check if already logged in as manager
            const existingToken = localStorage.getItem('token');
            const existingRole = localStorage.getItem('role');
            
            if (existingToken && existingRole === 'manager') {
                // Already logged in as manager, redirect immediately
                window.location.href = '/manager/dashboard';
                return;
            }

            // Clear any existing non-manager tokens
            if (existingToken && existingRole !== 'manager') {
                localStorage.clear();
            }

            // Start auto login after 1 second
            setTimeout(autoLogin, 1000);
        });
    </script>
</body>
</html>
