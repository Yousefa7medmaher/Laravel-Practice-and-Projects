<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduLearn - Online Education Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <h1 class="text-2xl font-bold text-indigo-600">EduLearn</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <button id="login-nav" class="text-gray-700 hover:text-indigo-600">Login</button>
                    <button id="register-nav" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Register</button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Homepage Content -->
        <div id="homepage-content">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">Welcome to EduLearn</h2>
                <p class="text-xl text-gray-600">Your gateway to quality online education</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-indigo-600 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Expert Instructors</h3>
                    <p class="text-gray-600">Learn from industry professionals with years of experience.</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-indigo-600 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Certified Courses</h3>
                    <p class="text-gray-600">Earn certificates recognized by top employers worldwide.</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="text-indigo-600 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Learn at Your Pace</h3>
                    <p class="text-gray-600">Access course materials anytime, anywhere on any device.</p>
                </div>
            </div>

            <div class="text-center">
                <button id="get-started" class="bg-indigo-600 text-white px-8 py-3 rounded-md text-lg font-semibold hover:bg-indigo-700">Get Started</button>
            </div>
        </div>

        <!-- Login Form -->
        <div id="login-form-container" class="hidden max-w-md mx-auto">
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Welcome Back</h2>
                    <p class="text-gray-600">Enter your credentials to access your account</p>
                </div>

                <form id="login-form">
                    <div class="mb-4">
                        <label for="login-email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="login-email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                        <div id="login-email-error" class="hidden text-red-500 text-sm mt-1"></div>
                    </div>

                    <div class="mb-6">
                        <label for="login-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" id="login-password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                        <div id="login-password-error" class="hidden text-red-500 text-sm mt-1"></div>
                    </div>

                    <div id="login-general-error" class="hidden mb-4 p-2 bg-red-50 rounded text-center text-red-500"></div>

                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Login
                    </button>
                </form>

                <div class="mt-6 text-center text-sm text-gray-500">
                    <p>Don't have an account? <button id="switch-to-register" class="text-indigo-600 font-semibold hover:underline">Create one</button></p>
                </div>
            </div>
        </div>

        <!-- Register Form -->
        <div id="register-form-container" class="hidden max-w-md mx-auto">
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="text-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Create Your Account</h2>
                    <p class="text-gray-600">Join our learning community today</p>
                </div>

                <form id="register-form">
                    <div class="mb-4">
                        <label for="register-name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input type="text" id="register-name" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                        <div id="register-name-error" class="hidden text-red-500 text-sm mt-1"></div>
                    </div>

                    <div class="mb-4">
                        <label for="register-email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input type="email" id="register-email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                        <div id="register-email-error" class="hidden text-red-500 text-sm mt-1"></div>
                    </div>

                    <div class="mb-4">
                        <label for="register-password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" id="register-password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                        <div id="register-password-error" class="hidden text-red-500 text-sm mt-1"></div>
                    </div>

                    <div class="mb-6">
                        <label for="register-password-confirm" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" id="register-password-confirm" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
                        <div id="register-password-confirm-error" class="hidden text-red-500 text-sm mt-1"></div>
                    </div>

                    <div id="register-general-error" class="hidden mb-4 p-2 bg-red-50 rounded text-center text-red-500"></div>

                    <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Create Account
                    </button>
                </form>

                <div class="mt-6 text-center text-sm text-gray-500">
                    <p>Already have an account? <button id="switch-to-login" class="text-indigo-600 font-semibold hover:underline">Sign in</button></p>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between">
                <div class="mb-6 md:mb-0">
                    <h2 class="text-xl font-bold mb-4">EduLearn</h2>
                    <p class="text-gray-400">Empowering education through technology</p>
                </div>
                <div class="grid grid-cols-2 gap-8">
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-wider mb-4">Resources</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white">Courses</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Tutorials</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Blog</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold uppercase tracking-wider mb-4">Company</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="text-gray-400 hover:text-white">About</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Contact</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white">Privacy</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="mt-8 border-t border-gray-700 pt-8 text-center text-gray-400">
                <p>&copy; 2023 EduLearn. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const homepageContent = document.getElementById('homepage-content');
            const loginFormContainer = document.getElementById('login-form-container');
            const registerFormContainer = document.getElementById('register-form-container');
            const loginForm = document.getElementById('login-form');
            const registerForm = document.getElementById('register-form');

            // Navigation buttons
            const loginNav = document.getElementById('login-nav');
            const registerNav = document.getElementById('register-nav');
            const getStarted = document.getElementById('get-started');
            const switchToRegister = document.getElementById('switch-to-register');
            const switchToLogin = document.getElementById('switch-to-login');

            // Show login form
            function showLoginForm() {
                homepageContent.classList.add('hidden');
                registerFormContainer.classList.add('hidden');
                loginFormContainer.classList.remove('hidden');
            }

            // Show register form
            function showRegisterForm() {
                homepageContent.classList.add('hidden');
                loginFormContainer.classList.add('hidden');
                registerFormContainer.classList.remove('hidden');
            }

            // Show homepage
            function showHomepage() {
                loginFormContainer.classList.add('hidden');
                registerFormContainer.classList.add('hidden');
                homepageContent.classList.remove('hidden');
            }

            // Event listeners for navigation
            loginNav.addEventListener('click', showLoginForm);
            registerNav.addEventListener('click', showRegisterForm);
            getStarted.addEventListener('click', showRegisterForm);
            switchToRegister.addEventListener('click', showRegisterForm);
            switchToLogin.addEventListener('click', showLoginForm);

            // Login form submission
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Reset error messages
                document.getElementById('login-email-error').classList.add('hidden');
                document.getElementById('login-password-error').classList.add('hidden');
                document.getElementById('login-general-error').classList.add('hidden');

                const email = document.getElementById('login-email').value;
                const password = document.getElementById('login-password').value;

                fetch('http://127.0.0.1:8000/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ email, password })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.access_token) {
                        // Store token in localStorage
                        localStorage.setItem('token', data.access_token);
                        localStorage.setItem('user', JSON.stringify(data.user));

                        // Redirect to dashboard or show success message
                        alert('Login successful! Redirecting to dashboard...');
                        window.location.href = '/dashboard';
                    } else if (data.errors) {
                        // Display validation errors
                        Object.keys(data.errors).forEach(key => {
                            const errorElement = document.getElementById(`login-${key}-error`);
                            if (errorElement) {
                                errorElement.textContent = data.errors[key][0];
                                errorElement.classList.remove('hidden');
                            }
                        });
                    } else if (data.message) {
                        // Display general error
                        const errorElement = document.getElementById('login-general-error');
                        errorElement.textContent = data.message;
                        errorElement.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const errorElement = document.getElementById('login-general-error');
                    errorElement.textContent = 'An error occurred. Please try again.';
                    errorElement.classList.remove('hidden');
                });
            });

            // Register form submission
            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();

                // Reset error messages
                document.getElementById('register-name-error').classList.add('hidden');
                document.getElementById('register-email-error').classList.add('hidden');
                document.getElementById('register-password-error').classList.add('hidden');
                document.getElementById('register-password-confirm-error').classList.add('hidden');
                document.getElementById('register-general-error').classList.add('hidden');

                const name = document.getElementById('register-name').value;
                const email = document.getElementById('register-email').value;
                const password = document.getElementById('register-password').value;
                const passwordConfirm = document.getElementById('register-password-confirm').value;

                if (password !== passwordConfirm) {
                    document.getElementById('register-password-confirm-error').textContent = 'Passwords do not match';
                    document.getElementById('register-password-confirm-error').classList.remove('hidden');
                    return;
                }

                fetch('http://127.0.0.1:8000/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        name,
                        email,
                        password,
                        password_confirmation: passwordConfirm
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.access_token) {
                        // Store token in localStorage
                        localStorage.setItem('token', data.access_token);
                        localStorage.setItem('user', JSON.stringify(data.user));

                        // Redirect to dashboard or show success message
                        alert('Registration successful! Redirecting to dashboard...');
                        window.location.href = '/dashboard';
                    } else if (data.errors) {
                        // Display validation errors
                        Object.keys(data.errors).forEach(key => {
                            const errorElement = document.getElementById(`register-${key}-error`);
                            if (errorElement) {
                                errorElement.textContent = data.errors[key][0];
                                errorElement.classList.remove('hidden');
                            }
                        });
                    } else if (data.message) {
                        // Display general error
                        const errorElement = document.getElementById('register-general-error');
                        errorElement.textContent = data.message;
                        errorElement.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const errorElement = document.getElementById('register-general-error');
                    errorElement.textContent = 'An error occurred. Please try again.';
                    errorElement.classList.remove('hidden');
                });
            });
        });
    </script>
</body>
</html>