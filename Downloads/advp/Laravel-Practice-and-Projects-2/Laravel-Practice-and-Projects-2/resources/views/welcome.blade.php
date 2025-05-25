<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduLearn - Online Education Platform</title>
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

        .hero-section {
            background: linear-gradient(rgba(67, 56, 202, 0.8), rgba(79, 70, 229, 0.9)), url('https://images.unsplash.com/photo-1501504905252-473c47e087f8?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1074&q=80');
            background-size: cover;
            background-position: center;
        }

        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .course-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .testimonial-card {
            transition: transform 0.3s ease;
        }

        .testimonial-card:hover {
            transform: translateY(-5px);
        }

        .stats-item {
            transition: transform 0.3s ease;
        }

        .stats-item:hover {
            transform: scale(1.05);
        }

        .btn-primary {
            background-color: #4f46e5; /* bg-indigo-600 */
            color: white;
            padding: 0.75rem 1.5rem; /* px-6 py-3 */
            border-radius: 0.375rem; /* rounded-md */
            font-weight: 600; /* font-semibold */
            transition: all 0.3s ease-in-out;
        }

        .btn-primary:hover {
            background-color: #4338ca; /* hover:bg-indigo-700 */
            transform: scale(1.05); /* hover:scale-105 */
        }

        .btn-primary:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.5); /* focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 */
        }

        .btn-secondary {
            background-color: white;
            color: #4f46e5; /* text-indigo-600 */
            border: 1px solid #4f46e5; /* border border-indigo-600 */
            padding: 0.75rem 1.5rem; /* px-6 py-3 */
            border-radius: 0.375rem; /* rounded-md */
            font-weight: 600; /* font-semibold */
            transition: all 0.3s ease-in-out;
        }

        .btn-secondary:hover {
            background-color: #eef2ff; /* hover:bg-indigo-50 */
        }

        .btn-secondary:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.5); /* focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50 */
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
                        <a href="/" class="text-gray-800 font-medium hover:text-indigo-600 transition duration-300">Home</a>
                        <a href="/courses" class="text-gray-700 font-medium hover:text-indigo-600 transition duration-300">Courses</a>
                        <a href="#about" class="text-gray-700 font-medium hover:text-indigo-600 transition duration-300">About</a>
                        <a href="#contact" class="text-gray-700 font-medium hover:text-indigo-600 transition duration-300">Contact</a>
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
        <!-- Homepage Content -->
        <div id="homepage-content">
            <!-- Hero Section -->
            <section class="hero-section text-white py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="md:flex md:items-center md:justify-between">
                        <div class="md:w-1/2 mb-10 md:mb-0">
                            <h1 class="text-4xl md:text-5xl font-bold mb-6 animate__animated animate__fadeInUp">Transform Your Future with Quality Education</h1>
                            <p class="text-xl mb-8 animate__animated animate__fadeInUp animate__delay-1s">Access world-class courses taught by industry experts and advance your career with recognized certifications.</p>
                            <div class="flex flex-wrap gap-4 animate__animated animate__fadeInUp animate__delay-2s">
                                <a href="/courses" class="btn-primary">
                                    Explore Courses
                                </a>
                                <a href="/register" class="btn-secondary">
                                    Get Started
                                </a>
                            </div>
                        </div>
                        <div class="md:w-1/2 animate__animated animate__fadeInRight animate__delay-1s">
                            <img src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80" alt="Students learning" class="rounded-lg shadow-2xl">
                        </div>
                    </div>
                </div>
            </section>

            <!-- Features Section -->
            <section class="py-16 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Why Choose EduLearn?</h2>
                        <p class="text-xl text-gray-600 max-w-3xl mx-auto">Our platform offers a comprehensive learning experience designed to help you succeed.</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <div class="feature-card bg-white p-8 rounded-xl shadow-md border border-gray-100">
                            <div class="text-indigo-600 mb-4">
                                <i class="fas fa-chalkboard-teacher text-4xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Expert Instructors</h3>
                            <p class="text-gray-600">Learn from industry professionals with years of experience and proven expertise in their fields.</p>
                        </div>

                        <div class="feature-card bg-white p-8 rounded-xl shadow-md border border-gray-100">
                            <div class="text-indigo-600 mb-4">
                                <i class="fas fa-certificate text-4xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Certified Courses</h3>
                            <p class="text-gray-600">Earn certificates recognized by top employers worldwide and boost your career prospects.</p>
                        </div>

                        <div class="feature-card bg-white p-8 rounded-xl shadow-md border border-gray-100">
                            <div class="text-indigo-600 mb-4">
                                <i class="fas fa-clock text-4xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold mb-3">Learn at Your Pace</h3>
                            <p class="text-gray-600">Access course materials anytime, anywhere on any device. Study when it's convenient for you.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Featured Courses Section -->
            <section class="py-16 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center mb-12">
                        <div>
                            <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Featured Courses</h2>
                            <p class="text-xl text-gray-600">Explore our most popular and highly-rated courses</p>
                        </div>
                        <a href="/courses" class="hidden md:inline-block btn-primary">View All Courses</a>
                    </div>

                    <div id="featured-courses-container" class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-10">
                        <!-- Loading state -->
                        <div id="courses-loading" class="col-span-3 py-12 text-center">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-indigo-600"></div>
                            <p class="mt-2 text-gray-600">Loading courses...</p>
                        </div>

                        <!-- Error state -->
                        <div id="courses-error" class="hidden col-span-3 py-12 text-center">
                            <div class="text-red-500 mb-2">
                                <i class="fas fa-exclamation-circle text-3xl"></i>
                            </div>
                            <p class="text-gray-700 font-medium">Failed to load courses</p>
                            <p class="text-gray-600 mt-1">Please try again later</p>
                            <button id="retry-courses" class="mt-4 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-300">
                                Retry
                            </button>
                        </div>

                        <!-- Course cards will be dynamically loaded here -->
                    </div>

                    <div class="text-center md:hidden">
                        <a href="/courses" class="btn-primary">View All Courses</a>
                    </div>
                </div>
            </section>
        </div>
    </div>
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
                        <li><a href="#about" class="text-gray-400 hover:text-white transition duration-300">About Us</a></li>
                        <li><a href="#contact" class="text-gray-400 hover:text-white transition duration-300">Contact</a></li>
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
            // Check URL parameters for login/register
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('login') === 'true') {
                window.location.href = '/login';
            } else if (urlParams.get('register') === 'true') {
                window.location.href = '/register';
            }

            // Load featured courses
            loadFeaturedCourses();

            // Add event listener for retry button
            document.getElementById('retry-courses').addEventListener('click', loadFeaturedCourses);

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

                    // Add user profile/name
                    const userProfileElement = document.createElement('div');
                    userProfileElement.className = 'flex items-center';

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

        // Function to load featured courses
        function loadFeaturedCourses() {
            const coursesContainer = document.getElementById('featured-courses-container');
            const loadingElement = document.getElementById('courses-loading');
            const errorElement = document.getElementById('courses-error');

            // Show loading, hide error
            loadingElement.classList.remove('hidden');
            errorElement.classList.add('hidden');

            // Remove any existing course cards
            const existingCards = coursesContainer.querySelectorAll('.course-card');
            existingCards.forEach(card => card.remove());

            // Fetch featured courses from API
            fetch('/api/public/featured-courses')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Hide loading
                    loadingElement.classList.add('hidden');

                    if (data.status === 'success' && data.data && data.data.length > 0) {
                        // Create and append course cards
                        data.data.forEach(course => {
                            const courseCard = createCourseCard(course);
                            coursesContainer.appendChild(courseCard);
                        });
                    } else {
                        // Show no courses message
                        const noCoursesElement = document.createElement('div');
                        noCoursesElement.className = 'col-span-3 py-12 text-center';
                        noCoursesElement.innerHTML = `
                            <p class="text-gray-700 font-medium">No featured courses available</p>
                            <p class="text-gray-600 mt-1">Check back later for new courses</p>
                        `;
                        coursesContainer.appendChild(noCoursesElement);
                    }
                })
                .catch(error => {
                    console.error('Error fetching featured courses:', error);
                    // Hide loading, show error
                    loadingElement.classList.add('hidden');
                    errorElement.classList.remove('hidden');
                });
        }

        // Function to create a course card
        function createCourseCard(course) {
            const card = document.createElement('div');
            card.className = 'course-card bg-white rounded-xl shadow-md overflow-hidden';

            // Default image if none provided
            const imagePath = course.image_path || 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1170&q=80';

            // Instructor name
            const instructorName = course.instructor ? course.instructor.name : 'Unknown Instructor';

            card.innerHTML = `
                <div class="h-48 bg-gray-200">
                    <img src="${imagePath}" alt="${course.title}" class="w-full h-full object-cover">
                </div>
                <div class="p-6">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-xl font-semibold text-gray-800">${course.title}</h3>
                        <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-2.5 py-0.5 rounded">${course.code}</span>
                    </div>
                    <p class="text-sm text-gray-500 mb-4">Instructor: ${instructorName}</p>
                    <p class="text-gray-600 mb-4 line-clamp-3">${course.description}</p>
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-600">${course.credit_hours} Credit Hours</span>
                        <a href="/courses/${course.id}" class="text-indigo-600 hover:text-indigo-800 font-medium">View Details</a>
                    </div>
                </div>
            `;

            return card;
        }
    </script>
</body>
</html>
