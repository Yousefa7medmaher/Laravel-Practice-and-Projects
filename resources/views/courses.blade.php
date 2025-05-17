<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduLearn - Courses</title>
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

        .course-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .course-description {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .btn-primary {
            @apply bg-indigo-600 text-white px-6 py-3 rounded-md font-semibold hover:bg-indigo-700 transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50;
        }

        .btn-secondary {
            @apply bg-white text-indigo-600 border border-indigo-600 px-6 py-3 rounded-md font-semibold hover:bg-indigo-50 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50;
        }
    </style>
</head>
<body>
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
                        <a href="/courses" class="text-indigo-600 font-medium border-b-2 border-indigo-600 pb-1">Courses</a>
                        <a href="/#about" class="text-gray-700 font-medium hover:text-indigo-600 transition duration-300">About</a>
                        <a href="/#contact" class="text-gray-700 font-medium hover:text-indigo-600 transition duration-300">Contact</a>
                    </nav>
                    <div class="flex items-center space-x-4" id="auth-buttons">
                        <a href="/login" class="text-gray-700 hover:text-indigo-600 font-medium transition duration-300">Login</a>
                        <a href="/register" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300">Register</a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Hero Section -->
        <section class="bg-gradient-to-r from-indigo-600 to-indigo-800 text-white py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6 animate__animated animate__fadeInUp">Explore Our Courses</h1>
                    <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto animate__animated animate__fadeInUp animate__delay-1s">Discover a wide range of high-quality courses taught by industry experts to advance your skills and career</p>
                </div>
            </div>
        </section>

        <!-- Search and Filter Section -->
        <section class="py-8 bg-white shadow-md sticky top-16 z-40">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row gap-4 items-center">
                    <div class="flex-grow w-full md:w-auto">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" id="search-input" placeholder="Search courses by title, code, or instructor..." class="w-full pl-10 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>
                    <div class="w-full md:w-auto">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <div class="relative">
                                <select id="credit-filter" class="pl-4 pr-10 py-3 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                    <option value="">All Credit Hours</option>
                                    <option value="1">1 Credit Hour</option>
                                    <option value="2">2 Credit Hours</option>
                                    <option value="3">3 Credit Hours</option>
                                    <option value="4">4 Credit Hours</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                            <div class="relative">
                                <select id="sort-filter" class="pl-4 pr-10 py-3 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 bg-white">
                                    <option value="title">Sort by Title</option>
                                    <option value="credit">Sort by Credit Hours</option>
                                    <option value="code">Sort by Course Code</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Courses Section -->
        <section class="py-12 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Loading Indicator -->
                <div id="loading" class="text-center py-16">
                    <div class="inline-block animate-spin rounded-full h-16 w-16 border-t-2 border-b-2 border-indigo-600"></div>
                    <p class="mt-6 text-xl text-gray-600">Loading courses...</p>
                </div>

                <!-- Courses Grid -->
                <div id="courses-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 hidden">
                    <!-- Courses will be dynamically added here -->
                </div>

                <!-- No Results Message -->
                <div id="no-results" class="hidden text-center py-16">
                    <div class="text-indigo-600 mb-6">
                        <i class="fas fa-search text-6xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2">No courses found</h3>
                    <p class="text-gray-600 mb-8">Try adjusting your search or filter criteria</p>
                    <button id="reset-filters" class="btn-secondary">Reset Filters</button>
                </div>

                <!-- Error Message -->
                <div id="error-message" class="hidden text-center py-16">
                    <div class="text-red-600 mb-6">
                        <i class="fas fa-exclamation-triangle text-6xl"></i>
                    </div>
                    <h3 class="text-2xl font-semibold text-gray-800 mb-2">Unable to load courses</h3>
                    <p class="text-gray-600 mb-8">Please try again later or contact support if the problem persists.</p>
                    <button id="retry-loading" class="btn-primary">Try Again</button>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 rounded-2xl shadow-xl overflow-hidden">
                    <div class="md:flex">
                        <div class="md:w-2/3 p-12 md:p-16 text-white">
                            <h2 class="text-3xl md:text-4xl font-bold mb-6">Ready to Start Learning?</h2>
                            <p class="text-xl opacity-90 mb-8">Join thousands of students who are already advancing their careers with EduLearn. Get started today!</p>
                            <div class="flex flex-wrap gap-4">
                                <a href="/register" class="bg-white text-indigo-600 px-6 py-3 rounded-md font-semibold hover:bg-gray-100 transition duration-300">
                                    Create Free Account
                                </a>
                                <a href="/#contact" class="border border-white text-white px-6 py-3 rounded-md font-semibold hover:bg-indigo-700 transition duration-300">
                                    Contact Us
                                </a>
                            </div>
                        </div>
                        <div class="md:w-1/3 hidden md:block">
                            <img src="https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="Students learning" class="w-full h-full object-cover">
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
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
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-facebook-f text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-twitter text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-instagram text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-linkedin-in text-lg"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-youtube text-lg"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-white mb-6">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="/" class="text-gray-400 hover:text-white transition duration-300">Home</a></li>
                        <li><a href="/courses" class="text-gray-400 hover:text-white transition duration-300">Courses</a></li>
                        <li><a href="/#about" class="text-gray-400 hover:text-white transition duration-300">About Us</a></li>
                        <li><a href="/#contact" class="text-gray-400 hover:text-white transition duration-300">Contact</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Blog</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-white mb-6">Resources</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Help Center</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Student Forum</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Webinars</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Tutorials</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300">Career Resources</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold text-white mb-6">Newsletter</h3>
                    <p class="text-gray-400 mb-4">Subscribe to our newsletter for the latest updates and offers.</p>
                    <form class="space-y-3">
                        <div>
                            <input type="email" placeholder="Your email address" class="w-full px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 text-gray-300">
                        </div>
                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded-lg transition duration-300">
                            Subscribe
                        </button>
                    </form>
                </div>
            </div>

            <div class="mt-12 pt-8 border-t border-gray-800">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400">&copy; 2024 EduLearn. All rights reserved.</p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">Cookie Policy</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // DOM Elements
            const coursesGrid = document.getElementById('courses-grid');
            const loading = document.getElementById('loading');
            const errorMessage = document.getElementById('error-message');
            const noResults = document.getElementById('no-results');
            const searchInput = document.getElementById('search-input');
            const creditFilter = document.getElementById('credit-filter');
            const sortFilter = document.getElementById('sort-filter');
            const resetFiltersBtn = document.getElementById('reset-filters');
            const retryLoadingBtn = document.getElementById('retry-loading');

            // Store all courses for filtering
            let allCourses = [];

            // Fetch courses from API
            fetchCourses();

            // Event listeners for filtering
            searchInput.addEventListener('input', filterAndSortCourses);
            creditFilter.addEventListener('change', filterAndSortCourses);
            sortFilter.addEventListener('change', filterAndSortCourses);

            // Reset filters
            if (resetFiltersBtn) {
                resetFiltersBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    creditFilter.value = '';
                    sortFilter.value = 'title';
                    filterAndSortCourses();
                });
            }

            // Retry loading
            if (retryLoadingBtn) {
                retryLoadingBtn.addEventListener('click', fetchCourses);
            }



            // Function to fetch courses
            function fetchCourses() {
                loading.classList.remove('hidden');
                coursesGrid.classList.add('hidden');
                errorMessage.classList.add('hidden');
                noResults.classList.add('hidden');

                fetch('/api/public/courses')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success' && data.data) {
                            allCourses = data.data;
                            filterAndSortCourses();
                            loading.classList.add('hidden');
                        } else {
                            throw new Error('Invalid data format');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching courses:', error);
                        loading.classList.add('hidden');
                        errorMessage.classList.remove('hidden');
                    });
            }

            // Function to filter and sort courses
            function filterAndSortCourses() {
                const searchTerm = searchInput.value.toLowerCase();
                const creditValue = creditFilter.value;
                const sortValue = sortFilter.value;

                // Filter courses
                const filteredCourses = allCourses.filter(course => {
                    // Filter by search term
                    const matchesSearch =
                        course.title.toLowerCase().includes(searchTerm) ||
                        course.description.toLowerCase().includes(searchTerm) ||
                        course.code.toLowerCase().includes(searchTerm) ||
                        (course.instructor && course.instructor.name.toLowerCase().includes(searchTerm));

                    // Filter by credit hours
                    const matchesCredit = creditValue === '' || course.credit_hours.toString() === creditValue;

                    return matchesSearch && matchesCredit;
                });

                // Sort courses
                const sortedCourses = [...filteredCourses].sort((a, b) => {
                    if (sortValue === 'title') {
                        return a.title.localeCompare(b.title);
                    } else if (sortValue === 'credit') {
                        return a.credit_hours - b.credit_hours;
                    } else if (sortValue === 'code') {
                        return a.code.localeCompare(b.code);
                    }
                    return 0;
                });

                // Display courses
                displayCourses(sortedCourses);
            }

            // Function to display courses
            function displayCourses(courses) {
                coursesGrid.innerHTML = '';

                if (courses.length === 0) {
                    coursesGrid.classList.add('hidden');
                    noResults.classList.remove('hidden');
                    return;
                }

                noResults.classList.add('hidden');
                coursesGrid.classList.remove('hidden');

                courses.forEach(course => {
                    const courseCard = document.createElement('div');
                    courseCard.className = 'course-card bg-white rounded-xl shadow-md overflow-hidden hover:shadow-xl transition-all duration-300';

                    const imagePath = course.image_path || `https://source.unsplash.com/random/600x400/?${encodeURIComponent(course.title.split(' ')[0])},education`;
                    const instructorName = course.instructor ? course.instructor.name : 'Unknown Instructor';
                    const instructorImage = course.instructor && course.instructor.imgProfilePath ? course.instructor.imgProfilePath : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(instructorName);

                    // Truncate description to 150 characters
                    const shortDescription = course.description.length > 150 ?
                        course.description.substring(0, 150) + '...' :
                        course.description;

                    courseCard.innerHTML = `
                        <div class="relative h-48 bg-gray-200">
                            <img src="${imagePath}" alt="${course.title}" class="w-full h-full object-cover">
                            <div class="absolute top-4 right-4">
                                <span class="bg-indigo-600 text-white text-xs font-bold px-2.5 py-1.5 rounded-md">${course.code}</span>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold text-gray-800 mb-2 line-clamp-2">${course.title}</h3>
                            <div class="flex items-center mb-4">
                                <img src="${instructorImage}" alt="${instructorName}" class="w-8 h-8 rounded-full mr-2">
                                <p class="text-sm text-gray-600">${instructorName}</p>
                            </div>
                            <p class="text-gray-600 mb-4 course-description">${shortDescription}</p>
                            <div class="flex justify-between items-center pt-4 border-t border-gray-100">
                                <div class="flex items-center">
                                    <i class="fas fa-book text-indigo-600 mr-2"></i>
                                    <span class="text-sm font-medium text-gray-700">${course.credit_hours} Credit Hour${course.credit_hours !== 1 ? 's' : ''}</span>
                                </div>
                                <a href="/courses/${course.id}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 font-medium">
                                    View Details
                                    <i class="fas fa-arrow-right ml-1 text-xs"></i>
                                </a>
                            </div>
                        </div>
                    `;

                    coursesGrid.appendChild(courseCard);
                });
            }

            // Check if user is already logged in
            const token = localStorage.getItem('token');
            if (token) {
                // Update the UI to show the user's name and a logout button
                const authButtons = document.getElementById('auth-buttons');

                // Get user info
                const user = JSON.parse(localStorage.getItem('user'));
                const userRole = localStorage.getItem('role');

                if (user) {
                    // Clear existing buttons
                    authButtons.innerHTML = '';

                    // If manager, add dashboard button
                    if (userRole === 'manager') {
                        const dashboardLink = document.createElement('a');
                        dashboardLink.href = '/dashboard';
                        dashboardLink.className = 'bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition duration-300 mr-4';
                        dashboardLink.innerHTML = '<i class="fas fa-tachometer-alt mr-2"></i>Dashboard';
                        authButtons.appendChild(dashboardLink);
                    }

                    // Add user name
                    const userNameElement = document.createElement('span');
                    userNameElement.className = 'text-gray-700 font-medium mr-4';
                    userNameElement.textContent = user.name;
                    authButtons.appendChild(userNameElement);

                    // Add logout button
                    const logoutButton = document.createElement('button');
                    logoutButton.className = 'text-gray-700 hover:text-indigo-600 font-medium transition duration-300';
                    logoutButton.innerHTML = 'Logout';
                    logoutButton.onclick = function() {
                        // Clear localStorage
                        localStorage.removeItem('token');
                        localStorage.removeItem('user');
                        localStorage.removeItem('role');

                        // Reload page
                        window.location.reload();
                    };
                    authButtons.appendChild(logoutButton);
                }
            }
        });
    </script>
</body>
</html>
