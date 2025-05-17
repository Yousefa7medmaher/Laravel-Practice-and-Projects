<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduLearn - Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
            min-height: 100vh;
        }

        .btn-primary {
            @apply bg-indigo-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-indigo-700 transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50;
        }

        .btn-secondary {
            @apply bg-white text-indigo-600 border border-indigo-600 px-6 py-3 rounded-md font-semibold hover:bg-indigo-50 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998a12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998a12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                        <h1 class="ml-2 text-2xl font-bold text-indigo-600">EduLearn</h1>
                    </a>
                </div>
                <div class="flex items-center space-x-6">
                    <nav class="hidden md:flex space-x-6">
                        <a href="/" class="text-gray-700 font-medium hover:text-indigo-600 transition duration-300">Home</a>
                        <a href="/courses" class="text-gray-700 font-medium hover:text-indigo-600 transition duration-300">Courses</a>
                        <a href="/#about" class="text-gray-700 font-medium hover:text-indigo-600 transition duration-300">About</a>
                        <a href="/#contact" class="text-gray-700 font-medium hover:text-indigo-600 transition duration-300">Contact</a>
                    </nav>
                    <div class="flex items-center space-x-4">
                        <a href="/login" class="text-indigo-600 font-medium transition duration-300">Login</a>
                        <a href="/register" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-16">
        <div class="max-w-md mx-auto px-4 sm:px-6">
            <div class="bg-white rounded-xl shadow-xl p-8">
                <div class="text-center mb-8">
                    <a href="/" class="inline-flex items-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998a12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998a12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                        <h1 class="ml-2 text-2xl font-bold text-indigo-600">EduLearn</h1>
                    </a>
                    <h2 class="text-3xl font-bold text-gray-800 mb-2">Welcome Back</h2>
                    <p class="text-gray-600">Enter your credentials to access your account</p>
                </div>

                <form id="login-form" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400"></i>
                            </div>
                            <input type="email" id="email" name="email" class="w-full pl-10 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="you@example.com" required>
                        </div>
                        <div id="email-error" class="hidden text-red-500 text-sm mt-1"></div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                            <a href="#" class="text-sm text-indigo-600 hover:text-indigo-800">Forgot password?</a>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-gray-400"></i>
                            </div>
                            <input type="password" id="password" name="password" class="w-full pl-10 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" placeholder="••••••••" required>
                        </div>
                        <div id="password-error" class="hidden text-red-500 text-sm mt-1"></div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">Remember me</label>
                    </div>

                    <div id="login-error" class="hidden p-3 bg-red-50 rounded-lg text-center text-red-500"></div>

                    <button type="submit" class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-300 transform hover:scale-[1.02]">
                        Sign In
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-gray-600">Don't have an account?</p>
                    <a href="/register" class="mt-2 inline-block text-indigo-600 font-semibold hover:text-indigo-800 transition duration-300">Create a free account</a>
                </div>

                <div class="mt-8 pt-6 border-t border-gray-200">
                    <div class="text-center text-sm text-gray-600 mb-4">Or continue with</div>
                    <div class="flex gap-4">
                        <button class="flex-1 flex justify-center items-center gap-2 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-300">
                            <i class="fab fa-google text-red-500"></i>
                            <span class="text-sm font-medium">Google</span>
                        </button>
                        <button class="flex-1 flex justify-center items-center gap-2 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50 transition duration-300">
                            <i class="fab fa-facebook-f text-blue-600"></i>
                            <span class="text-sm font-medium">Facebook</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="text-center mt-8">
                <a href="/" class="text-gray-600 hover:text-indigo-600 transition duration-300">
                    <i class="fas fa-arrow-left mr-2"></i> Back to Home
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12">
                <div>
                    <div class="flex items-center mb-6">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998a12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998a12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                        </svg>
                        <h2 class="ml-2 text-2xl font-bold text-white">EduLearn</h2>
                    </div>
                    <p class="text-gray-400 mb-6">Empowering education through technology. Our mission is to provide accessible, high-quality education to learners worldwide.</p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-white mb-6">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="/" class="text-gray-400 hover:text-white transition duration-300">Home</a></li>
                        <li><a href="/courses" class="text-gray-400 hover:text-white transition duration-300">Courses</a></li>
                        <li><a href="/#about" class="text-gray-400 hover:text-white transition duration-300">About Us</a></li>
                        <li><a href="/#contact" class="text-gray-400 hover:text-white transition duration-300">Contact</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-white mb-6">Resources</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Student Forum</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Webinars</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-white mb-6">Contact Us</h3>
                    <ul class="space-y-3">
                        <li class="flex items-start">
                            <i class="fas fa-envelope text-indigo-400 mt-1 mr-3"></i>
                            <span class="text-gray-400">info@edulearn.com</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-phone text-indigo-400 mt-1 mr-3"></i>
                            <span class="text-gray-400">+1 (555) 123-4567</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-800">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400">&copy; 2024 EduLearn. All rights reserved.</p>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('login-form');

            // Login form submission
            if (loginForm) {
                loginForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Reset error messages
                    document.getElementById('email-error').classList.add('hidden');
                    document.getElementById('password-error').classList.add('hidden');
                    document.getElementById('login-error').classList.add('hidden');

                    // Show loading state
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Signing in...';

                    const email = document.getElementById('email').value;
                    const password = document.getElementById('password').value;
                    const remember = document.getElementById('remember').checked;

                    // Make API request
                    fetch('/api/login', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        credentials: 'include',
                        body: JSON.stringify({ email, password, remember })
                    })
                    .then(response => {
                        return response.json().then(data => {
                            if (!response.ok) {
                                if (data.errors) {
                                    // Handle validation errors
                                    Object.keys(data.errors).forEach(key => {
                                        const errorElement = document.getElementById(`${key}-error`);
                                        if (errorElement) {
                                            errorElement.textContent = data.errors[key][0];
                                            errorElement.classList.remove('hidden');
                                        }
                                    });
                                    throw new Error('Validation failed');
                                } else {
                                    throw new Error(data.message || 'Login failed');
                                }
                            }
                            return data;
                        });
                    })
                    .then(data => {
                        if (data.access_token) {
                            // Store token in localStorage
                            localStorage.setItem('token', data.access_token);
                            localStorage.setItem('user', JSON.stringify(data.user));

                            // Store user role if available
                            if (data.role) {
                                localStorage.setItem('role', data.role);
                            }

                            // Set token in cookie for server-side authentication
                            // Use secure and httpOnly flags for better security in production
                            document.cookie = `token=${data.access_token}; path=/; max-age=86400; SameSite=Strict`;

                            // Also set XSRF token for Laravel
                            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                            document.cookie = `XSRF-TOKEN=${csrfToken}; path=/; max-age=86400; SameSite=Strict`;

                            // Show success message
                            submitButton.innerHTML = '<i class="fas fa-check mr-2"></i> Success!';

                            // Redirect based on role
                            setTimeout(() => {
                                // Check for redirect parameter in URL
                                const urlParams = new URLSearchParams(window.location.search);
                                const redirectParam = urlParams.get('redirect');

                                // For manager role, redirect to dashboard
                                if (data.role === 'manager') {
                                    // Redirect to dashboard with token in query param as fallback
                                    window.location.href = `/dashboard?auth_token=${data.access_token}`;
                                } else if (redirectParam === 'dashboard' && data.role === 'manager') {
                                    // If redirect parameter is dashboard and user is manager
                                    window.location.href = `/dashboard?auth_token=${data.access_token}`;
                                } else if (data.redirect_url) {
                                    // Use the redirect URL from the API response if available
                                    window.location.href = data.redirect_url;
                                } else {
                                    // Default redirect to courses page
                                    window.location.href = '/courses';
                                }
                            }, 1000);
                        } else {
                            throw new Error('Login failed');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);

                        // Reset button
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonText;

                        // Display error message
                        const errorElement = document.getElementById('login-error');

                        // Check for middleware error
                        if (error.message && error.message.includes('Target class [App\\Http\\Middleware\\EncryptCookies] does not exist')) {
                            errorElement.textContent = 'Server configuration error. Please try again later or contact support.';
                        } else if (error.message !== 'Validation failed') {
                            errorElement.textContent = error.message || 'An error occurred. Please try again.';
                        }

                        errorElement.classList.remove('hidden');
                    });
                });
            }
        });
    </script>
</body>
</html>
