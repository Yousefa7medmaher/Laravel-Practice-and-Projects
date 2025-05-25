@extends('layouts.student')

@section('title', 'Course Details')

@section('header', 'Course Details')

@section('content')
    <!-- Loading State -->
    <div id="loading-state" class="flex justify-center items-center py-12">
        <div class="text-center">
            <i class="fas fa-spinner fa-spin text-primary-600 text-3xl mb-4"></i>
            <p class="text-gray-600">Loading course details...</p>
        </div>
    </div>

    <!-- Course Header -->
    <div id="course-header" class="hidden bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div class="flex-1">
                <div class="flex items-center mb-2">
                    <h1 id="course-title" class="text-2xl font-bold text-gray-800 mr-4">Course Title</h1>
                    <span id="enrollment-status" class="px-3 py-1 text-sm rounded-full bg-green-100 text-green-800">Enrolled</span>
                </div>
                <p id="course-code" class="text-gray-600 mb-2">Course Code</p>
                <p id="course-description" class="text-gray-700 mb-4">Course description will appear here...</p>

                <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2 text-gray-400"></i>
                        <span id="credit-hours">3</span> Credit Hours
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-users mr-2 text-gray-400"></i>
                        <span id="student-count">0</span> Students
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-user-tie mr-2 text-gray-400"></i>
                        <span id="instructor-name">Instructor Name</span>
                    </div>
                </div>
            </div>

            <div class="mt-4 lg:mt-0 lg:ml-6">
                <div class="bg-gray-50 rounded-lg p-4 min-w-[200px]">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary-600" id="progress-percentage">0%</div>
                        <div class="text-sm text-gray-600 mb-2">Course Progress</div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div id="progress-bar" class="bg-primary-600 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div id="course-tabs" class="hidden bg-white rounded-lg shadow-md mb-6">
        <div class="border-b border-gray-200">
            <nav class="flex space-x-8 px-6" aria-label="Tabs">
                <button class="tab-button active py-4 px-1 border-b-2 border-primary-500 font-medium text-sm text-primary-600" data-tab="lectures">
                    <i class="fas fa-play-circle mr-2"></i>Lectures
                </button>
                <button class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="assignments">
                    <i class="fas fa-tasks mr-2"></i>Assignments
                </button>
                <button class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="quizzes">
                    <i class="fas fa-question-circle mr-2"></i>Quizzes
                </button>
                <button class="tab-button py-4 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="materials">
                    <i class="fas fa-file-alt mr-2"></i>Materials
                </button>
            </nav>
        </div>
    </div>

    <!-- Content Sections -->
    <div id="course-content" class="hidden">
        <!-- Lectures Tab -->
        <div id="lectures-tab" class="tab-content">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Course Lectures</h2>
                    <span id="lectures-count" class="text-sm text-gray-600">0 lectures</span>
                </div>
                <div id="lectures-container">
                    <div class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-primary-600 text-2xl mb-4"></i>
                        <p class="text-gray-600">Loading lectures...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Assignments Tab -->
        <div id="assignments-tab" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Course Assignments</h2>
                    <span id="assignments-count" class="text-sm text-gray-600">0 assignments</span>
                </div>
                <div id="assignments-container">
                    <div class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-primary-600 text-2xl mb-4"></i>
                        <p class="text-gray-600">Loading assignments...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quizzes Tab -->
        <div id="quizzes-tab" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Course Quizzes</h2>
                    <span id="quizzes-count" class="text-sm text-gray-600">0 quizzes</span>
                </div>
                <div id="quizzes-container">
                    <div class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-primary-600 text-2xl mb-4"></i>
                        <p class="text-gray-600">Loading quizzes...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Materials Tab -->
        <div id="materials-tab" class="tab-content hidden">
            <div class="bg-white rounded-lg shadow-md p-6">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Course Materials</h2>
                    <span id="materials-count" class="text-sm text-gray-600">0 materials</span>
                </div>
                <div id="materials-container">
                    <div class="text-center py-8">
                        <i class="fas fa-spinner fa-spin text-primary-600 text-2xl mb-4"></i>
                        <p class="text-gray-600">Loading materials...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Error State -->
    <div id="error-state" class="hidden bg-white rounded-lg shadow-md p-8 text-center">
        <i class="fas fa-exclamation-triangle text-yellow-400 text-5xl mb-4"></i>
        <h3 class="text-xl font-semibold text-gray-800 mb-2">Error Loading Course</h3>
        <p class="text-gray-600 mb-6">There was a problem loading the course details. Please try again later.</p>
        <button onclick="location.reload()" class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-md transition duration-300">
            Try Again
        </button>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get course ID from URL parameter or passed variable
        const urlParams = new URLSearchParams(window.location.search);
        const courseId = urlParams.get('id') || '{{ $course_id ?? "" }}';

        if (!courseId) {
            showError('Course ID not provided');
            return;
        }

        // DOM Elements
        const loadingState = document.getElementById('loading-state');
        const courseHeader = document.getElementById('course-header');
        const courseTabs = document.getElementById('course-tabs');
        const courseContent = document.getElementById('course-content');
        const errorState = document.getElementById('error-state');

        // Tab functionality
        const tabButtons = document.querySelectorAll('.tab-button');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                const tabName = button.getAttribute('data-tab');
                switchTab(tabName);
            });
        });

        // Load course data
        loadCourseDetails();

        function switchTab(tabName) {
            // Update tab buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active', 'border-primary-500', 'text-primary-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });

            const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
            activeButton.classList.add('active', 'border-primary-500', 'text-primary-600');
            activeButton.classList.remove('border-transparent', 'text-gray-500');

            // Update tab content
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            document.getElementById(`${tabName}-tab`).classList.remove('hidden');
        }

        async function loadCourseDetails() {
            try {
                console.log('Loading course details for course ID:', courseId);

                // Load course basic details using new API
                const response = await fetchWithAuth(`/api/student/courses/${courseId}`);

                console.log('Response status:', response.status);
                console.log('Response ok:', response.ok);

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('API Error Response:', errorText);
                    throw new Error(`Failed to load course details: ${response.status} - ${errorText}`);
                }

                const data = await response.json();
                console.log('Course data received:', data);

                if (data.status === 'success' && data.data) {
                    displayCourseHeader(data.data);
                    loadingState.classList.add('hidden');
                    courseHeader.classList.remove('hidden');
                    courseTabs.classList.remove('hidden');
                    courseContent.classList.remove('hidden');

                    // Load content for each tab
                    await loadLectures();
                    await loadAssignments();
                    await loadQuizzes();
                    await loadMaterials();
                } else {
                    console.error('Invalid course data structure:', data);
                    throw new Error('Invalid course data');
                }
            } catch (error) {
                console.error('Error loading course details:', error);
                showError(`Failed to load course details: ${error.message}`);
            }
        }

        function displayCourseHeader(data) {
            const course = data.course;
            const progress = data.progress;
            const enrollment = data.enrollment;

            document.getElementById('course-title').textContent = course.title;
            document.getElementById('course-code').textContent = course.code;
            document.getElementById('course-description').textContent = course.description;
            document.getElementById('credit-hours').textContent = course.credit_hours || 3;
            document.getElementById('student-count').textContent = course.student_count || 0;
            document.getElementById('instructor-name').textContent = course.instructor ? course.instructor.name : 'No instructor assigned';

            // Set enrollment status
            const statusElement = document.getElementById('enrollment-status');
            if (enrollment) {
                statusElement.textContent = 'Enrolled';
                statusElement.className = 'px-3 py-1 text-sm rounded-full bg-green-100 text-green-800';
            } else {
                statusElement.textContent = 'Not Enrolled';
                statusElement.className = 'px-3 py-1 text-sm rounded-full bg-red-100 text-red-800';
            }

            // Set progress from API
            const progressPercentage = progress ? progress.overall_progress : 0;
            document.getElementById('progress-percentage').textContent = `${progressPercentage}%`;
            document.getElementById('progress-bar').style.width = `${progressPercentage}%`;
        }

        async function loadLectures() {
            try {
                const response = await fetchWithAuth(`/api/student/courses/${courseId}/lectures`);
                const data = await response.json();

                if (data.status === 'success' && data.data) {
                    displayLectures(data.data);
                } else {
                    document.getElementById('lectures-container').innerHTML = '<p class="text-gray-600 text-center py-8">No lectures available</p>';
                }
            } catch (error) {
                console.error('Error loading lectures:', error);
                document.getElementById('lectures-container').innerHTML = '<p class="text-red-600 text-center py-8">Error loading lectures</p>';
            }
        }

        async function loadAssignments() {
            try {
                const response = await fetchWithAuth(`/api/student/courses/${courseId}/assignments`);
                const data = await response.json();

                if (data.status === 'success' && data.data) {
                    displayAssignments(data.data);
                } else {
                    document.getElementById('assignments-container').innerHTML = '<p class="text-gray-600 text-center py-8">No assignments available</p>';
                }
            } catch (error) {
                console.error('Error loading assignments:', error);
                document.getElementById('assignments-container').innerHTML = '<p class="text-red-600 text-center py-8">Error loading assignments</p>';
            }
        }

        async function loadQuizzes() {
            try {
                const response = await fetchWithAuth(`/api/student/courses/${courseId}/quizzes`);
                const data = await response.json();

                if (data.status === 'success' && data.data) {
                    displayQuizzes(data.data);
                } else {
                    document.getElementById('quizzes-container').innerHTML = '<p class="text-gray-600 text-center py-8">No quizzes available</p>';
                }
            } catch (error) {
                console.error('Error loading quizzes:', error);
                document.getElementById('quizzes-container').innerHTML = '<p class="text-red-600 text-center py-8">Error loading quizzes</p>';
            }
        }

        async function loadMaterials() {
            try {
                const response = await fetchWithAuth(`/api/student/courses/${courseId}/materials`);
                const data = await response.json();

                if (data.status === 'success' && data.data) {
                    displayMaterials(data.data);
                } else {
                    document.getElementById('materials-container').innerHTML = '<p class="text-gray-600 text-center py-8">No materials available</p>';
                }
            } catch (error) {
                console.error('Error loading materials:', error);
                document.getElementById('materials-container').innerHTML = '<p class="text-red-600 text-center py-8">Error loading materials</p>';
            }
        }

        function displayLectures(lectures) {
            document.getElementById('lectures-count').textContent = `${lectures.length} lectures`;

            if (lectures.length === 0) {
                document.getElementById('lectures-container').innerHTML = '<p class="text-gray-600 text-center py-8">📚 No lectures available</p>';
                return;
            }

            const container = document.getElementById('lectures-container');
            container.innerHTML = '';

            lectures.forEach((lecture, index) => {
                const lectureCard = document.createElement('div');
                const progress = lecture.progress || {};
                const progressPercentage = progress.progress_percentage || 0;
                const isCompleted = progress.completed || false;
                const isAttended = progress.attended || false;
                const attendanceStatus = progress.attendance_status || 'not_started';
                const attendanceBadge = progress.attendance_badge || {text: 'Not Started', color: 'gray', icon: 'circle'};
                const formattedDuration = progress.formatted_duration || '0 min';
                const lastAccessed = progress.last_accessed ? new Date(progress.last_accessed).toLocaleDateString() : null;
                const difficulty = lecture.difficulty || 3;
                const duration = lecture.duration || 45;

                // Enhanced status styling based on attendance status
                let statusClass = `bg-${attendanceBadge.color}-100 text-${attendanceBadge.color}-800`;
                let statusIcon = '';
                let statusText = attendanceBadge.text;

                // Set appropriate icons
                switch(attendanceStatus) {
                    case 'completed':
                        statusIcon = '✅';
                        break;
                    case 'attended':
                        statusIcon = '👁️';
                        break;
                    case 'in_progress':
                        statusIcon = '⏳';
                        break;
                    default:
                        statusIcon = '🔒';
                }

                lectureCard.className = 'border border-gray-200 rounded-lg p-6 mb-4 hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 bg-gradient-to-br from-blue-50 to-indigo-50';

                lectureCard.innerHTML = `
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-3">
                                <span class="text-2xl mr-3">📚</span>
                                <span class="${statusClass} text-xs font-medium px-2.5 py-1 rounded-full mr-3">
                                    ${statusIcon} ${statusText}
                                </span>
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-1 rounded-full">
                                    Lecture ${index + 1}
                                </span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">${lecture.title}</h3>
                            <p class="text-gray-600 mb-3">${lecture.description || 'Explore fundamental concepts and practical applications.'}</p>

                            <!-- Enhanced Lecture Meta Info -->
                            <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500 mb-3">
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-1"></i>
                                    <span>⏱️ ${duration} min</span>
                                </div>
                                <div class="flex items-center">
                                    <span class="mr-1">Difficulty:</span>
                                    <div class="flex">
                                        ${Array.from({length: 5}, (_, i) =>
                                            `<i class="fas fa-star ${i < difficulty ? 'text-yellow-400' : 'text-gray-300'} text-xs"></i>`
                                        ).join('')}
                                    </div>
                                </div>
                                ${formattedDuration !== '0 min' ? `
                                    <div class="flex items-center">
                                        <i class="fas fa-stopwatch mr-1"></i>
                                        <span>Time spent: ${formattedDuration}</span>
                                    </div>
                                ` : ''}
                                ${lastAccessed ? `
                                    <div class="flex items-center">
                                        <i class="fas fa-calendar-check mr-1"></i>
                                        <span>Last accessed: ${lastAccessed}</span>
                                    </div>
                                ` : ''}
                            </div>

                            <!-- Enhanced Progress Bar -->
                            ${progressPercentage > 0 ? `
                                <div class="mb-3">
                                    <div class="flex items-center justify-between text-xs text-gray-600 mb-1">
                                        <span>Progress</span>
                                        <span>${progressPercentage}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-2 rounded-full transition-all duration-300" style="width: ${progressPercentage}%"></div>
                                    </div>
                                </div>
                            ` : ''}
                        </div>
                        <div class="ml-6 flex flex-col space-y-2">
                            <button onclick="viewLecture(${lecture.id})" class="bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-lg transition-all duration-300 transform hover:scale-105 shadow-lg">
                                <i class="fas fa-play mr-2"></i>
                                ${isCompleted ? 'Review' : isStarted ? 'Continue' : 'Start'} Lecture
                            </button>
                            <button onclick="bookmarkContent('lecture', ${lecture.id})" class="border border-gray-300 text-gray-600 px-4 py-2 rounded-lg hover:bg-gray-50 transition-colors text-sm">
                                <i class="far fa-bookmark mr-1"></i>Bookmark
                            </button>
                        </div>
                    </div>
                `;

                container.appendChild(lectureCard);
            });
        }

        function displayAssignments(assignments) {
            document.getElementById('assignments-count').textContent = `${assignments.length} assignments`;

            if (assignments.length === 0) {
                document.getElementById('assignments-container').innerHTML = '<p class="text-gray-600 text-center py-8">No assignments available</p>';
                return;
            }

            const container = document.getElementById('assignments-container');
            container.innerHTML = '';

            assignments.forEach((assignment, index) => {
                const assignmentCard = document.createElement('div');
                assignmentCard.className = 'border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition-shadow';

                const dueDate = assignment.due_date ? new Date(assignment.due_date).toLocaleDateString() : 'No due date';
                const submissionStatus = assignment.submission_status || 'not_submitted';
                const statusBadge = assignment.status_badge || {text: 'Not Submitted', color: 'gray', icon: 'circle'};
                const isSubmitted = submissionStatus === 'submitted' || submissionStatus === 'graded';
                const grade = assignment.grade;
                const gradeDisplay = assignment.grade_display;
                const formattedSubmissionDate = assignment.formatted_submission_date || 'Not submitted';
                const isOverdue = assignment.is_overdue || false;
                const isLateSubmission = assignment.is_late_submission || false;

                assignmentCard.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">
                                    Assignment ${index + 1}
                                </span>
                                <h3 class="text-lg font-semibold text-gray-800">${assignment.title}</h3>
                                <span class="ml-2 bg-${statusBadge.color}-100 text-${statusBadge.color}-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <i class="fas fa-${statusBadge.icon} mr-1"></i>${statusBadge.text}
                                </span>
                                ${gradeDisplay ? `<span class="ml-2 bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Grade: ${gradeDisplay}</span>` : ''}
                                ${isLateSubmission ? '<span class="ml-2 bg-orange-100 text-orange-800 text-xs font-medium px-2.5 py-0.5 rounded-full"><i class="fas fa-clock mr-1"></i>Late</span>' : ''}
                            </div>
                            <p class="text-gray-600 mb-2">${assignment.description || 'No description available'}</p>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                <span>Due: ${dueDate}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-star mr-1"></i>
                                <span>${assignment.points || assignment.max_score || 'N/A'} points</span>
                                ${isSubmitted ? `<span class="mx-2">•</span><i class="fas fa-check mr-1"></i><span>Submitted: ${formattedSubmissionDate}</span>` : ''}
                                ${assignment.can_submit === false ? '<span class="mx-2">•</span><span class="text-red-500">Cannot submit</span>' : ''}
                            </div>
                        </div>
                        <div class="ml-4 flex space-x-2">
                            <button onclick="viewAssignment(${assignment.id})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-300">
                                <i class="fas fa-eye mr-2"></i>View
                            </button>
                            ${assignment.can_submit !== false ? `
                                <button onclick="submitAssignment(${assignment.id})" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition duration-300">
                                    <i class="fas fa-upload mr-2"></i>${isSubmitted ? 'Resubmit' : 'Submit'}
                                </button>
                            ` : ''}
                        </div>
                    </div>
                `;

                container.appendChild(assignmentCard);
            });
        }

        function displayQuizzes(quizzes) {
            document.getElementById('quizzes-count').textContent = `${quizzes.length} quizzes`;

            if (quizzes.length === 0) {
                document.getElementById('quizzes-container').innerHTML = '<p class="text-gray-600 text-center py-8">No quizzes available</p>';
                return;
            }

            const container = document.getElementById('quizzes-container');
            container.innerHTML = '';

            quizzes.forEach((quiz, index) => {
                const quizCard = document.createElement('div');
                quizCard.className = 'border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition-shadow';

                const startTime = quiz.start_time ? new Date(quiz.start_time).toLocaleDateString() : 'Not specified';
                const endTime = quiz.end_time ? new Date(quiz.end_time).toLocaleDateString() : 'Not specified';
                const isAvailable = quiz.is_available !== false;
                const canAttempt = quiz.can_attempt !== false;
                const attemptsTaken = quiz.attempts_taken || 0;
                const completedAttempts = quiz.completed_attempts || 0;
                const bestScore = quiz.best_score;
                const latestScore = quiz.latest_score;
                const completionStatus = quiz.completion_status || 'not_attempted';
                const completionBadge = quiz.completion_badge || {text: 'Not Attempted', color: 'gray', icon: 'circle'};
                const formattedCompletionDate = quiz.formatted_completion_date;
                const performanceLevel = quiz.performance_level;

                quizCard.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">
                                    Quiz ${index + 1}
                                </span>
                                <h3 class="text-lg font-semibold text-gray-800">${quiz.title}</h3>
                                <span class="ml-2 bg-${completionBadge.color}-100 text-${completionBadge.color}-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                                    <i class="fas fa-${completionBadge.icon} mr-1"></i>${completionBadge.text}
                                </span>
                                ${bestScore !== null ? `<span class="ml-2 bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">Best: ${bestScore}%</span>` : ''}
                                ${performanceLevel ? `<span class="ml-2 bg-${performanceLevel.color}-100 text-${performanceLevel.color}-800 text-xs font-medium px-2.5 py-0.5 rounded-full">${performanceLevel.text}</span>` : ''}
                                ${!isAvailable ? '<span class="ml-2 bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded-full"><i class="fas fa-lock mr-1"></i>Unavailable</span>' : ''}
                            </div>
                            <p class="text-gray-600 mb-2">${quiz.description || 'No description available'}</p>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="fas fa-calendar mr-1"></i>
                                <span>Available: ${startTime} - ${endTime}</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-clock mr-1"></i>
                                <span>${quiz.duration_minutes || 'No time limit'} min</span>
                                <span class="mx-2">•</span>
                                <i class="fas fa-redo mr-1"></i>
                                <span>Attempts: ${attemptsTaken}/${quiz.max_attempts || '∞'} (${completedAttempts} completed)</span>
                                ${formattedCompletionDate ? `<span class="mx-2">•</span><i class="fas fa-check-circle mr-1"></i><span>Completed: ${formattedCompletionDate}</span>` : ''}
                            </div>
                        </div>
                        <div class="ml-4 flex space-x-2">
                            <button onclick="viewQuiz(${quiz.id})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition duration-300">
                                <i class="fas fa-eye mr-2"></i>View
                            </button>
                            <button onclick="takeQuiz(${quiz.id})" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md transition duration-300 ${!canAttempt ? 'opacity-50 cursor-not-allowed' : ''}" ${!canAttempt ? 'disabled' : ''}>
                                <i class="fas fa-play mr-2"></i>${attemptsTaken > 0 ? 'Retake' : 'Take'} Quiz
                            </button>
                        </div>
                    </div>
                `;

                container.appendChild(quizCard);
            });
        }

        function displayMaterials(materials) {
            document.getElementById('materials-count').textContent = `${materials.length} materials`;

            if (materials.length === 0) {
                document.getElementById('materials-container').innerHTML = '<p class="text-gray-600 text-center py-8">No materials available</p>';
                return;
            }

            const container = document.getElementById('materials-container');
            container.innerHTML = '';

            materials.forEach((material, index) => {
                const materialCard = document.createElement('div');
                materialCard.className = 'border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition-shadow';

                const fileIcon = getFileIcon(material.file_type || material.type);

                materialCard.innerHTML = `
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
                                <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded-full mr-3">
                                    Material ${index + 1}
                                </span>
                                <h3 class="text-lg font-semibold text-gray-800">${material.title}</h3>
                            </div>
                            <p class="text-gray-600 mb-2">${material.description || 'No description available'}</p>
                            <div class="flex items-center text-sm text-gray-500">
                                <i class="${fileIcon} mr-1"></i>
                                <span>${material.file_type || material.type || 'Unknown type'}</span>
                                ${material.file_size ? `<span class="mx-2">•</span><span>${formatFileSize(material.file_size)}</span>` : ''}
                            </div>
                        </div>
                        <div class="ml-4">
                            <button onclick="downloadMaterial(${material.id})" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md transition duration-300">
                                <i class="fas fa-download mr-2"></i>Download
                            </button>
                        </div>
                    </div>
                `;

                container.appendChild(materialCard);
            });
        }

        // Action functions
        function viewLecture(lectureId) {
            window.location.href = `/student/courses/${courseId}/lectures/${lectureId}`;
        }

        function viewAssignment(assignmentId) {
            window.location.href = `/student/courses/${courseId}/assignments/${assignmentId}`;
        }

        function submitAssignment(assignmentId) {
            window.location.href = `/student/courses/${courseId}/assignments/${assignmentId}`;
        }

        function viewQuiz(quizId) {
            window.location.href = `/student/courses/${courseId}/quizzes/${quizId}`;
        }

        function takeQuiz(quizId) {
            window.location.href = `/student/courses/${courseId}/quizzes/${quizId}`;
        }

        function downloadMaterial(materialId) {
            // Show loading notification
            showNotification('📥 Downloading material...', 'info');

            // Attempt to download the material
            try {
                window.open(`/api/course-content/courses/${courseId}/materials/${materialId}`, '_blank');
                setTimeout(() => {
                    showNotification('✅ Material download started!', 'success');
                }, 1000);
            } catch (error) {
                showNotification('❌ Download failed. Please try again.', 'error');
            }
        }

        function viewMaterial(materialId) {
            window.location.href = `/student/content-viewer?type=material&id=${materialId}&course=${courseId}`;
        }

        // Enhanced interaction functions
        function bookmarkContent(type, contentId) {
            const bookmarkKey = `bookmark_${type}_${contentId}`;
            const isBookmarked = localStorage.getItem(bookmarkKey) === 'true';

            if (isBookmarked) {
                localStorage.removeItem(bookmarkKey);
                showNotification('Bookmark removed', 'info');
            } else {
                localStorage.setItem(bookmarkKey, 'true');
                showNotification('📌 Content bookmarked!', 'success');
            }
        }

        // Show notification function
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.style.transform = 'translateX(0)';
                notification.style.opacity = '1';
            }, 100);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                notification.style.opacity = '0';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }, 3000);
        }

        // Helper functions
        function getFileIcon(fileType) {
            const type = fileType ? fileType.toLowerCase() : '';
            if (type.includes('pdf')) return 'fas fa-file-pdf text-red-500';
            if (type.includes('doc') || type.includes('word')) return 'fas fa-file-word text-blue-500';
            if (type.includes('ppt') || type.includes('powerpoint')) return 'fas fa-file-powerpoint text-orange-500';
            if (type.includes('xls') || type.includes('excel')) return 'fas fa-file-excel text-green-500';
            if (type.includes('image') || type.includes('jpg') || type.includes('png')) return 'fas fa-file-image text-purple-500';
            if (type.includes('video') || type.includes('mp4')) return 'fas fa-file-video text-red-500';
            if (type.includes('audio') || type.includes('mp3')) return 'fas fa-file-audio text-yellow-500';
            return 'fas fa-file text-gray-500';
        }

        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        function showError(message) {
            loadingState.classList.add('hidden');
            courseHeader.classList.add('hidden');
            courseTabs.classList.add('hidden');
            courseContent.classList.add('hidden');
            errorState.classList.remove('hidden');

            const errorMessage = errorState.querySelector('p');
            if (errorMessage) {
                errorMessage.textContent = message;
            }
        }
    });
</script>
@endsection
