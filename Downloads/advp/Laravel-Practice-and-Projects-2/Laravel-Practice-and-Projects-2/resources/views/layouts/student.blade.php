<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Student Dashboard') - EduLearn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0f9ff',
                            100: '#e0f2fe',
                            200: '#bae6fd',
                            300: '#7dd3fc',
                            400: '#38bdf8',
                            500: '#0ea5e9',
                            600: '#0284c7',
                            700: '#0369a1',
                            800: '#075985',
                            900: '#0c4a6e',
                        },
                        secondary: {
                            50: '#f5f3ff',
                            100: '#ede9fe',
                            200: '#ddd6fe',
                            300: '#c4b5fd',
                            400: '#a78bfa',
                            500: '#8b5cf6',
                            600: '#7c3aed',
                            700: '#6d28d9',
                            800: '#5b21b6',
                            900: '#4c1d95',
                        },
                    },
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif'],
                    },
                    boxShadow: {
                        'card': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                        'card-hover': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                    },
                },
            },
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
        }

        .sidebar {
            width: 280px;
            transition: all 0.3s;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar.active {
            margin-left: -280px;
        }

        .main-content {
            transition: all 0.3s;
            margin-left: 280px;
        }

        .main-content.active {
            margin-left: 0;
        }

        .dashboard-card {
            transition: all 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .stat-card {
            transition: all 0.3s ease;
            border-radius: 0.5rem;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        @media (max-width: 1024px) {
            .sidebar {
                margin-left: -280px;
            }

            .sidebar.active {
                margin-left: 0;
            }

            .main-content {
                margin-left: 0;
            }

            .main-content.active {
                margin-left: 280px;
            }
        }
    </style>
    @yield('styles')
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="sidebar bg-gradient-to-b from-primary-800 to-primary-900 text-white fixed h-full z-10">
            <div class="p-6 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998a12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                </svg>
                <h1 class="ml-2 text-xl font-bold">EduLearn Student</h1>
            </div>
            <nav class="mt-6">
                <div class="px-4 py-2 text-xs uppercase tracking-wider text-primary-200 font-semibold">Main</div>
                <a href="{{ route('student.dashboard') }}" class="flex items-center py-3 px-4 {{ request()->routeIs('student.dashboard') ? 'bg-primary-700 border-l-4 border-white' : 'hover:bg-primary-700 hover:border-l-4 hover:border-primary-300' }} transition duration-200">
                    <i class="fas fa-tachometer-alt mr-3 w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('courses') }}" class="flex items-center py-3 px-4 {{ request()->routeIs('courses') ? 'bg-primary-700 border-l-4 border-white' : 'hover:bg-primary-700 hover:border-l-4 hover:border-primary-300' }} transition duration-200">
                    <i class="fas fa-book mr-3 w-5 text-center"></i>
                    <span>Browse Courses</span>
                </a>
                <a href="{{ route('student.courses') }}" class="flex items-center py-3 px-4 {{ request()->routeIs('student.courses') ? 'bg-primary-700 border-l-4 border-white' : 'hover:bg-primary-700 hover:border-l-4 hover:border-primary-300' }} transition duration-200">
                    <i class="fas fa-graduation-cap mr-3 w-5 text-center"></i>
                    <span>My Courses</span>
                </a>

                <div class="px-4 py-2 mt-4 text-xs uppercase tracking-wider text-primary-200 font-semibold">Academics</div>
                <a href="{{ route('student.assignments') }}" class="flex items-center py-3 px-4 {{ request()->routeIs('student.assignments') ? 'bg-primary-700 border-l-4 border-white' : 'hover:bg-primary-700 hover:border-l-4 hover:border-primary-300' }} transition duration-200">
                    <i class="fas fa-tasks mr-3 w-5 text-center"></i>
                    <span>Assignments</span>
                </a>
                <a href="{{ route('student.grades') }}" class="flex items-center py-3 px-4 {{ request()->routeIs('student.grades') ? 'bg-primary-700 border-l-4 border-white' : 'hover:bg-primary-700 hover:border-l-4 hover:border-primary-300' }} transition duration-200">
                    <i class="fas fa-chart-line mr-3 w-5 text-center"></i>
                    <span>Grades</span>
                </a>
                <a href="{{ route('student.calendar') }}" class="flex items-center py-3 px-4 {{ request()->routeIs('student.calendar') ? 'bg-primary-700 border-l-4 border-white' : 'hover:bg-primary-700 hover:border-l-4 hover:border-primary-300' }} transition duration-200">
                    <i class="fas fa-calendar-alt mr-3 w-5 text-center"></i>
                    <span>Calendar</span>
                </a>

                <div class="px-4 py-2 mt-4 text-xs uppercase tracking-wider text-primary-200 font-semibold">Account</div>
                <a href="{{ route('student.profile') }}" class="flex items-center py-3 px-4 {{ request()->routeIs('student.profile') ? 'bg-primary-700 border-l-4 border-white' : 'hover:bg-primary-700 hover:border-l-4 hover:border-primary-300' }} transition duration-200">
                    <i class="fas fa-user mr-3 w-5 text-center"></i>
                    <span>Profile</span>
                </a>
            </nav>
            <div class="absolute bottom-0 w-full p-4 border-t border-primary-700">
                <a href="#" id="logout-button" class="flex items-center text-white hover:text-primary-200 transition duration-200">
                    <i class="fas fa-sign-out-alt mr-3 w-5 text-center"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-1 bg-gray-100 min-h-screen">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm">
                <div class="flex justify-between items-center py-4 px-6">
                    <div class="flex items-center">
                        <button id="sidebar-toggle" class="text-gray-500 focus:outline-none lg:hidden hover:text-primary-600 transition duration-200">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                        <h2 class="text-xl font-semibold text-gray-800 ml-4">@yield('header', 'Student Dashboard')</h2>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative hidden md:block">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" placeholder="Search...">
                        </div>
                        <div class="relative">
                            <button class="text-gray-500 hover:text-primary-600 transition duration-200">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full">3</span>
                            </button>
                        </div>
                        <div class="relative" id="user-profile">
                            <!-- User profile will be loaded here -->
                            <div class="flex items-center">
                                @if(Auth::user()->imgProfilePath)
                                    <img src="/{{ Auth::user()->imgProfilePath }}" alt="Profile" class="h-9 w-9 rounded-full object-cover border-2 border-primary-300">
                                @else
                                    <div class="w-9 h-9 rounded-full bg-gradient-to-r from-primary-500 to-primary-700 flex items-center justify-center text-white border-2 border-primary-300">
                                        <span class="font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                    </div>
                                @endif
                                <span class="ml-2 font-medium hidden md:block">{{ Auth::user()->name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="p-6">
                @yield('content')
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get token from various sources
            const token = localStorage.getItem('token') || getQueryParam('auth_token');

            // Function to get query parameters
            function getQueryParam(param) {
                const urlParams = new URLSearchParams(window.location.search);
                return urlParams.get(param);
            }

            // If no token, redirect to login
            if (!token) {
                // Get the current page to use as redirect parameter
                const currentPage = window.location.pathname.split('/').pop() || 'dashboard';
                window.location.href = `/login?redirect=student_${currentPage}`;
                return;
            }

            // If we have a token from query parameter, store it in localStorage
            const authTokenParam = getQueryParam('auth_token');
            if (authTokenParam) {
                localStorage.setItem('token', authTokenParam);

                // Remove the token from URL for security
                const url = new URL(window.location);
                url.searchParams.delete('auth_token');
                window.history.replaceState({}, document.title, url.toString());
            }

            // Sidebar toggle functionality
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');

            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                mainContent.classList.toggle('active');
            });

            // Handle logout
            document.getElementById('logout-button').addEventListener('click', function(e) {
                e.preventDefault();

                fetch('/api/logout', {
                    method: 'POST',
                    headers: {
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(() => {
                    // Clear local storage
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');

                    // Redirect to login page
                    window.location.href = '/login';
                })
                .catch(error => {
                    console.error('Logout error:', error);
                    // Redirect anyway
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    window.location.href = '/login';
                });
            });
        });

        // Helper function for authenticated API calls
        function fetchWithAuth(url, options = {}) {
            const token = localStorage.getItem('token');

            if (!token) {
                console.error('No authentication token found');
                // Redirect to login if no token is found
                window.location.href = '/login?redirect=student_dashboard';
                return Promise.reject(new Error('No authentication token'));
            }

            const headers = {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            };

            return fetch(url, {
                ...options,
                headers: {
                    ...headers,
                    ...(options.headers || {})
                }
            }).then(response => {
                if (response.status === 401) {
                    // If unauthorized, clear token and redirect to login
                    console.error('Authentication token expired or invalid');
                    localStorage.removeItem('token');
                    localStorage.removeItem('user');
                    window.location.href = '/login?redirect=student_dashboard';
                    throw new Error('Authentication failed');
                }
                return response;
            });
        }
    </script>
    @yield('scripts')
</body>
</html>
