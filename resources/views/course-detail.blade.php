<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>EduLearn - Course Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            min-height: 100vh;
        }

        .course-container {
            max-width: 1000px;
            margin: 0 auto;
        }

        .section-header {
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .section-header:hover {
            background-color: #2d2d2d;
        }

        .section-content {
            display: none;
        }

        .section-content.active {
            display: block;
        }

        .item-row {
            transition: background-color 0.3s;
        }

        .item-row:hover {
            background-color: #2d2d2d;
        }

        .mark-done-btn {
            background-color: #00a86b;
            color: white;
            border-radius: 4px;
            padding: 4px 10px;
            font-size: 12px;
            transition: background-color 0.3s;
        }

        .mark-done-btn:hover {
            background-color: #008f5d;
        }

        .done-badge {
            background-color: #4a4a4a;
            color: white;
            border-radius: 4px;
            padding: 4px 10px;
            font-size: 12px;
        }

        .nav-item {
            padding: 8px 16px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .nav-item:hover, .nav-item.active {
            background-color: #2d2d2d;
        }

        .search-box {
            background-color: #2d2d2d;
            border: none;
            color: white;
            border-radius: 4px;
        }

        .search-box:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.5);
        }

        .item-icon {
            color: #3b82f6;
        }
    </style>
</head>
<body class="text-gray-200">
    <!-- Header -->
    <header class="bg-[#1a1a1a] border-b border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/" class="text-2xl font-bold text-indigo-500">EduLearn</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/" class="text-gray-300 hover:text-indigo-500">Home</a>
                    <a href="/courses" class="text-gray-300 hover:text-indigo-500">Courses</a>
                    <button id="login-nav" class="text-gray-300 hover:text-indigo-500">Login</button>
                    <button id="register-nav" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Register</button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="course-container px-4 py-8">
        <!-- Loading Indicator -->
        <div id="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
            <p class="mt-4 text-gray-400">Loading course details...</p>
        </div>

        <!-- Course Details -->
        <div id="course-details" class="hidden">
            <!-- Course Header -->
            <div class="mb-6">
                <h1 id="course-title" class="text-3xl font-bold text-white mb-2">Advanced Web Programming 2024-2</h1>
                <div class="flex flex-wrap items-center gap-3 text-sm text-gray-400 mb-4">
                    <div class="flex items-center">
                        <i class="fas fa-user-tie mr-2"></i>
                        <span>Instructor: <span id="instructor-name" class="text-gray-300">Prof. John Smith</span></span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        <span><span id="credit-hours">3</span> Credits</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        <span>Semester: Fall 2024</span>
                    </div>
                </div>
            </div>

            <!-- Course Navigation -->
            <div class="flex justify-between items-center mb-6">
                <div class="flex space-x-2">
                    <button class="nav-item active flex items-center">
                        <i class="fas fa-book mr-2"></i>
                        <span>Course</span>
                    </button>
                    <button class="nav-item flex items-center">
                        <i class="fas fa-users mr-2"></i>
                        <span>Participants</span>
                    </button>
                    <button class="nav-item flex items-center">
                        <i class="fas fa-chart-bar mr-2"></i>
                        <span>Grades</span>
                    </button>
                    <button class="nav-item flex items-center">
                        <i class="fas fa-cog mr-2"></i>
                        <span>Competencies</span>
                    </button>
                </div>
                <div>
                    <input type="text" placeholder="Search course materials..." class="search-box px-4 py-2 w-64">
                </div>
            </div>

            <!-- Collapse All Button -->
            <div class="mb-6">
                <button class="flex items-center text-sm text-gray-400 hover:text-white">
                    <i class="fas fa-chevron-down mr-2"></i>
                    <span>Collapse all</span>
                </button>
            </div>

            <!-- Course Sections -->

            <!-- General Information Section -->
            <div class="mb-6">
                <div class="section-header flex items-center justify-between bg-[#1a1a1a] rounded-md p-3 mb-2">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-down mr-3 text-gray-400"></i>
                        <h2 class="text-white font-medium">General Information</h2>
                        <span class="ml-3 bg-blue-600 text-white text-xs px-2 py-1 rounded">3 items</span>
                    </div>
                </div>

                <div class="section-content active space-y-2">
                    <!-- Course Syllabus -->
                    <div class="item-row flex items-center justify-between bg-[#1a1a1a] rounded-md p-3 pl-10">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt item-icon mr-3"></i>
                            <div>
                                <h3 class="text-white">Course Syllabus</h3>
                                <p class="text-xs text-gray-400">Updated 1 day ago</p>
                            </div>
                        </div>
                        <button class="mark-done-btn">
                            Mark as done
                        </button>
                    </div>

                    <!-- Course Policies -->
                    <div class="item-row flex items-center justify-between bg-[#1a1a1a] rounded-md p-3 pl-10">
                        <div class="flex items-center">
                            <i class="fas fa-clipboard-list item-icon mr-3"></i>
                            <div>
                                <h3 class="text-white">Course Policies</h3>
                                <p class="text-xs text-gray-400">Updated 1 week ago</p>
                            </div>
                        </div>
                        <button class="mark-done-btn">
                            Mark as done
                        </button>
                    </div>

                    <!-- Reading List -->
                    <div class="item-row flex items-center justify-between bg-[#1a1a1a] rounded-md p-3 pl-10">
                        <div class="flex items-center">
                            <i class="fas fa-book-open item-icon mr-3"></i>
                            <div>
                                <h3 class="text-white">Reading List</h3>
                                <p class="text-xs text-gray-400">Updated 3 days ago</p>
                            </div>
                        </div>
                        <span class="done-badge">
                            Done
                        </span>
                    </div>
                </div>
            </div>

            <!-- Lab Quizzes Section -->
            <div class="mb-6">
                <div class="section-header flex items-center justify-between bg-[#1a1a1a] rounded-md p-3 mb-2">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-down mr-3 text-gray-400"></i>
                        <h2 class="text-white font-medium">Lab Quizzes</h2>
                        <span class="ml-3 bg-blue-600 text-white text-xs px-2 py-1 rounded">4 items</span>
                    </div>
                </div>

                <div class="section-content active space-y-2">
                    <!-- Module C -->
                    <div class="item-row flex items-center justify-between bg-[#1a1a1a] rounded-md p-3 pl-10">
                        <div class="flex items-center">
                            <i class="fas fa-code item-icon mr-3"></i>
                            <div>
                                <h3 class="text-white">Module C</h3>
                                <p class="text-xs text-gray-400">Due Sep 15</p>
                            </div>
                        </div>
                        <button class="mark-done-btn">
                            Mark as done
                        </button>
                    </div>

                    <!-- DOM Manipulation -->
                    <div class="item-row flex items-center justify-between bg-[#1a1a1a] rounded-md p-3 pl-10">
                        <div class="flex items-center">
                            <i class="fas fa-code item-icon mr-3"></i>
                            <div>
                                <h3 class="text-white">DOM Manipulation</h3>
                                <p class="text-xs text-gray-400">Due Sep 22</p>
                            </div>
                        </div>
                        <button class="mark-done-btn">
                            Mark as done
                        </button>
                    </div>

                    <!-- API Integration -->
                    <div class="item-row flex items-center justify-between bg-[#1a1a1a] rounded-md p-3 pl-10">
                        <div class="flex items-center">
                            <i class="fas fa-code item-icon mr-3"></i>
                            <div>
                                <h3 class="text-white">API Integration</h3>
                                <p class="text-xs text-gray-400">Due Oct 6</p>
                            </div>
                        </div>
                        <button class="mark-done-btn">
                            Mark as done
                        </button>
                    </div>

                    <!-- State Management -->
                    <div class="item-row flex items-center justify-between bg-[#1a1a1a] rounded-md p-3 pl-10">
                        <div class="flex items-center">
                            <i class="fas fa-code item-icon mr-3"></i>
                            <div>
                                <h3 class="text-white">State Management</h3>
                                <p class="text-xs text-gray-400">Due Oct 20</p>
                            </div>
                        </div>
                        <button class="mark-done-btn">
                            Mark as done
                        </button>
                    </div>
                </div>
            </div>

            <!-- Quizzes Section -->
            <div class="mb-6">
                <div class="section-header flex items-center justify-between bg-[#1a1a1a] rounded-md p-3 mb-2">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-down mr-3 text-gray-400"></i>
                        <h2 class="text-white font-medium">Quizzes</h2>
                        <span class="ml-3 bg-blue-600 text-white text-xs px-2 py-1 rounded">2 items</span>
                    </div>
                </div>

                <div class="section-content active space-y-2">
                    <!-- Quiz 1 (A) -->
                    <div class="item-row flex items-center justify-between bg-[#1a1a1a] rounded-md p-3 pl-10">
                        <div class="flex items-center">
                            <i class="fas fa-question-circle item-icon mr-3"></i>
                            <div>
                                <h3 class="text-white">Quiz 1 (A)</h3>
                                <p class="text-xs text-gray-400">Due Sep 10</p>
                            </div>
                        </div>
                        <span class="done-badge">
                            Done
                        </span>
                    </div>

                    <!-- Quiz 1 (B) -->
                    <div class="item-row flex items-center justify-between bg-[#1a1a1a] rounded-md p-3 pl-10">
                        <div class="flex items-center">
                            <i class="fas fa-question-circle item-icon mr-3"></i>
                            <div>
                                <h3 class="text-white">Quiz 1 (B)</h3>
                                <p class="text-xs text-gray-400">Due Sep 17</p>
                            </div>
                        </div>
                        <button class="mark-done-btn">
                            Mark as done
                        </button>
                    </div>
                </div>
            </div>

            <!-- Lectures Section -->
            <div class="mb-6">
                <div class="section-header flex items-center justify-between bg-[#1a1a1a] rounded-md p-3 mb-2">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-down mr-3 text-gray-400"></i>
                        <h2 class="text-white font-medium">Lectures</h2>
                    </div>
                </div>

                <div class="section-content active">
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <i class="fas fa-book-reader text-4xl text-gray-600 mb-4"></i>
                        <p class="text-gray-400">Lecture materials will appear here when available</p>
                    </div>
                </div>
            </div>

            <!-- Assignments Section -->
            <div class="mb-6">
                <div class="section-header flex items-center justify-between bg-[#1a1a1a] rounded-md p-3 mb-2">
                    <div class="flex items-center">
                        <i class="fas fa-chevron-down mr-3 text-gray-400"></i>
                        <h2 class="text-white font-medium">Assignments</h2>
                    </div>
                </div>

                <div class="section-content active">
                    <div class="flex flex-col items-center justify-center py-10 text-center">
                        <i class="fas fa-clipboard-check text-4xl text-gray-600 mb-4"></i>
                        <p class="text-gray-400">Assignment details will appear here when available</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Error Message -->
        <div id="error-message" class="hidden text-center py-12">
            <div class="text-red-500 mb-4">
                <i class="fas fa-exclamation-triangle text-5xl"></i>
            </div>
            <p class="text-xl font-semibold text-gray-200">Unable to load course details</p>
            <p class="text-gray-400 mt-2 mb-6">Please try again later or contact support if the problem persists.</p>
            <button id="retry-button" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Try Again</button>
        </div>

    </main>

    <!-- Footer -->
    <footer class="bg-[#1a1a1a] text-gray-400 py-6 mt-8 border-t border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="mb-4 md:mb-0">
                    <p>&copy; 2024 EduLearn. All rights reserved.</p>
                </div>
                <div class="flex space-x-4">
                    <a href="#" class="text-gray-400 hover:text-white">Privacy Policy</a>
                    <a href="#" class="text-gray-400 hover:text-white">Terms of Service</a>
                    <a href="#" class="text-gray-400 hover:text-white">Help Center</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get course ID from URL
            const courseId = {{ $courseId }};

            // DOM Elements
            const loading = document.getElementById('loading');
            const courseDetails = document.getElementById('course-details');
            const errorMessage = document.getElementById('error-message');
            const retryButton = document.getElementById('retry-button');
            const sectionHeaders = document.querySelectorAll('.section-header');
            const collapseAllBtn = document.querySelector('button:has(.fa-chevron-down)');
            const markDoneButtons = document.querySelectorAll('.mark-done-btn');

            // Fetch course details
            fetchCourseDetails(courseId);

            // Add event listener for retry button
            if (retryButton) {
                retryButton.addEventListener('click', function() {
                    fetchCourseDetails(courseId);
                });
            }

            // Section toggle functionality
            sectionHeaders.forEach(header => {
                header.addEventListener('click', () => {
                    const content = header.nextElementSibling;
                    const icon = header.querySelector('i.fas');

                    // Toggle content visibility
                    content.classList.toggle('active');

                    // Toggle icon
                    if (content.classList.contains('active')) {
                        icon.classList.remove('fa-chevron-right');
                        icon.classList.add('fa-chevron-down');
                    } else {
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-right');
                    }
                });
            });

            // Collapse/Expand all sections
            if (collapseAllBtn) {
                collapseAllBtn.addEventListener('click', function() {
                    const allContents = document.querySelectorAll('.section-content');
                    const isCollapsing = this.querySelector('span').textContent.includes('Collapse');

                    allContents.forEach(content => {
                        if (isCollapsing) {
                            content.classList.remove('active');
                        } else {
                            content.classList.add('active');
                        }
                    });

                    // Update button text and icon
                    const icon = this.querySelector('i.fas');
                    const text = this.querySelector('span');

                    if (isCollapsing) {
                        text.textContent = 'Expand all';
                        icon.classList.remove('fa-chevron-down');
                        icon.classList.add('fa-chevron-right');

                        // Update all section icons
                        document.querySelectorAll('.section-header i.fas').forEach(icon => {
                            icon.classList.remove('fa-chevron-down');
                            icon.classList.add('fa-chevron-right');
                        });
                    } else {
                        text.textContent = 'Collapse all';
                        icon.classList.remove('fa-chevron-right');
                        icon.classList.add('fa-chevron-down');

                        // Update all section icons
                        document.querySelectorAll('.section-header i.fas').forEach(icon => {
                            icon.classList.remove('fa-chevron-right');
                            icon.classList.add('fa-chevron-down');
                        });
                    }
                });
            }

            // Mark as done button functionality
            markDoneButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation(); // Prevent triggering section toggle

                    const itemRow = this.closest('.item-row');
                    const doneBadge = document.createElement('span');
                    doneBadge.className = 'done-badge';
                    doneBadge.textContent = 'Done';

                    // Replace button with done badge
                    this.parentNode.replaceChild(doneBadge, this);
                });
            });

            // Function to fetch course details
            function fetchCourseDetails(id) {
                loading.classList.remove('hidden');
                courseDetails.classList.add('hidden');
                errorMessage.classList.add('hidden');

                fetch(`/api/public/courses/${id}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.status === 'success' && data.data) {
                            displayCourseDetails(data.data);
                            loading.classList.add('hidden');
                            courseDetails.classList.remove('hidden');
                        } else {
                            throw new Error('Invalid data format');
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching course details:', error);
                        loading.classList.add('hidden');
                        errorMessage.classList.remove('hidden');
                    });
            }

            // Function to display course details
            function displayCourseDetails(course) {
                // Set course details
                document.getElementById('course-title').textContent = course.title || 'Advanced Web Programming 2024-2';
                document.getElementById('instructor-name').textContent = course.instructor ? course.instructor.name : 'Prof. John Smith';
                document.getElementById('credit-hours').textContent = course.credit_hours || '3';

                // Update page title
                document.title = `EduLearn - ${course.title || 'Course Details'}`;
            }
        });
    </script>
</body>
</html>
