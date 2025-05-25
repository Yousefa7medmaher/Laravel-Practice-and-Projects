<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Take Quiz - Educational Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .timer-warning {
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .question-card {
            transition: all 0.3s ease;
        }

        .question-card.active {
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .progress-bar {
            transition: width 0.3s ease;
        }

        .quiz-navigation {
            position: sticky;
            top: 20px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navigation Header -->
    <nav class="bg-white shadow-lg border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <h1 class="text-2xl font-bold text-indigo-600">
                            <i class="fas fa-graduation-cap mr-2"></i>
                            EduPlatform
                        </h1>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Timer Display -->
                    <div id="timer-display" class="bg-indigo-100 text-indigo-800 px-4 py-2 rounded-lg font-medium">
                        <i class="fas fa-clock mr-2"></i>
                        <span id="timer-text">Loading...</span>
                    </div>
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                        <div class="hidden md:block">
                            <div class="text-sm font-medium text-gray-700" id="user-name">Loading...</div>
                            <div class="text-xs text-gray-500">Student</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Quiz Header -->
    <div id="quiz-header" class="gradient-bg text-white py-8 hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div>
                    <div class="flex items-center mb-2">
                        <span class="bg-white bg-opacity-20 text-white text-sm font-medium px-3 py-1 rounded-full mr-3" id="course-code">
                            Loading...
                        </span>
                        <span class="bg-purple-500 text-white text-xs font-medium px-2.5 py-0.5 rounded-full">
                            Quiz
                        </span>
                    </div>
                    <h1 class="text-3xl font-bold mb-2" id="quiz-title">Loading Quiz...</h1>
                    <p class="text-indigo-100" id="quiz-description">Loading quiz description...</p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold" id="quiz-score">-/-</div>
                    <div class="text-sm text-indigo-100">Max Score</div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="mt-6">
                <div class="flex justify-between text-sm mb-2">
                    <span>Progress</span>
                    <span id="progress-text">Question 0 of 0</span>
                </div>
                <div class="w-full bg-white bg-opacity-20 rounded-full h-2">
                    <div class="progress-bar bg-white h-2 rounded-full" id="progress-bar" style="width: 0%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Indicator -->
    <div id="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-indigo-500"></div>
        <p class="mt-4 text-gray-600">Loading quiz...</p>
    </div>

    <!-- Quiz Start Screen -->
    <div id="quiz-start" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 hidden">
        <div class="bg-white rounded-xl shadow-lg p-8 text-center">
            <div class="mb-6">
                <i class="fas fa-play-circle text-indigo-600 text-6xl mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Ready to Start Quiz?</h2>
                <p class="text-gray-600">Please review the quiz information before starting.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-indigo-600" id="start-questions-count">0</div>
                    <div class="text-sm text-gray-600">Questions</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-indigo-600" id="start-time-limit">No Limit</div>
                    <div class="text-sm text-gray-600">Time Limit</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-indigo-600" id="start-max-score">0</div>
                    <div class="text-sm text-gray-600">Max Score</div>
                </div>
            </div>

            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mr-3"></i>
                    <div class="text-left">
                        <h4 class="font-medium text-yellow-800">Important Instructions</h4>
                        <ul class="text-sm text-yellow-700 mt-2 list-disc list-inside">
                            <li>Once started, you cannot pause the quiz</li>
                            <li>Your answers are automatically saved</li>
                            <li>You can navigate between questions</li>
                            <li>Submit before time runs out</li>
                        </ul>
                    </div>
                </div>
            </div>

            <button id="start-quiz-btn" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg text-lg font-medium">
                <i class="fas fa-play mr-2"></i>Start Quiz
            </button>
        </div>
    </div>

    <!-- Quiz Content -->
    <div id="quiz-content" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 hidden">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Questions Area -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <div id="question-container">
                        <!-- Questions will be loaded here -->
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-8 pt-6 border-t border-gray-200">
                        <button id="prev-question" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium" disabled>
                            <i class="fas fa-chevron-left mr-2"></i>Previous
                        </button>
                        <div class="flex space-x-3">
                            <button id="save-answer" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                                <i class="fas fa-save mr-2"></i>Save Answer
                            </button>
                            <button id="next-question" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-medium">
                                Next<i class="fas fa-chevron-right ml-2"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quiz Navigation Sidebar -->
            <div class="lg:col-span-1">
                <div class="quiz-navigation">
                    <!-- Question Navigator -->
                    <div class="bg-white rounded-xl shadow-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-list text-indigo-600 mr-2"></i>
                            Questions
                        </h3>
                        <div id="question-navigator" class="grid grid-cols-5 gap-2">
                            <!-- Question numbers will be loaded here -->
                        </div>
                    </div>

                    <!-- Quiz Actions -->
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">
                            <i class="fas fa-cog text-indigo-600 mr-2"></i>
                            Actions
                        </h3>
                        <div class="space-y-3">
                            <button id="review-answers" class="w-full bg-yellow-600 hover:bg-yellow-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                <i class="fas fa-eye mr-2"></i>Review Answers
                            </button>
                            <button id="submit-quiz" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                <i class="fas fa-paper-plane mr-2"></i>Submit Quiz
                            </button>
                            <button id="save-draft" class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                <i class="fas fa-save mr-2"></i>Save Draft
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quiz Results -->
    <div id="quiz-results" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 hidden">
        <div class="bg-white rounded-xl shadow-lg p-8 text-center">
            <div class="mb-6">
                <i class="fas fa-check-circle text-green-600 text-6xl mb-4"></i>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Quiz Completed!</h2>
                <p class="text-gray-600">Your quiz has been submitted successfully.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-green-600" id="final-score">0</div>
                    <div class="text-sm text-gray-600">Your Score</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-indigo-600" id="final-percentage">0%</div>
                    <div class="text-sm text-gray-600">Percentage</div>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-2xl font-bold text-purple-600" id="final-grade">-</div>
                    <div class="text-sm text-gray-600">Grade</div>
                </div>
            </div>

            <div class="flex justify-center space-x-4">
                <button onclick="goToCourse()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Course
                </button>
                <button onclick="viewResults()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-medium">
                    <i class="fas fa-eye mr-2"></i>View Detailed Results
                </button>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <div id="error-message" class="hidden text-center py-12">
        <div class="text-red-500 mb-4">
            <i class="fas fa-exclamation-triangle text-5xl"></i>
        </div>
        <p class="text-xl font-semibold text-gray-800">Unable to load quiz</p>
        <p class="text-gray-600 mt-2 mb-6">Please try again later or contact support if the problem persists.</p>
        <button id="retry-button" onclick="loadQuiz()" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">Try Again</button>
    </div>

    <!-- JavaScript -->
    <script>
        // Global variables
        let authToken = localStorage.getItem('token');
        let courseId = null;
        let quizId = null;
        let quizData = null;
        let questions = [];
        let currentQuestionIndex = 0;
        let answers = {};
        let timeRemaining = null;
        let timerInterval = null;
        let quizStarted = false;

        // Initialize page when DOM loads
        document.addEventListener('DOMContentLoaded', function() {
            // Check authentication
            if (!authToken) {
                window.location.href = '/login';
                return;
            }

            // Get course and quiz IDs from URL
            const urlParams = new URLSearchParams(window.location.search);
            courseId = urlParams.get('course');
            quizId = urlParams.get('quiz');

            if (!courseId || !quizId) {
                showError('Course or quiz ID not found in URL');
                return;
            }

            // Load initial data
            loadUserProfile();
            loadQuiz();
        });

        // API call utility function
        async function apiCall(endpoint, method = 'GET', data = null) {
            const headers = {
                'Accept': 'application/json',
                'Authorization': `Bearer ${authToken}`
            };

            if (data && method !== 'GET') {
                headers['Content-Type'] = 'application/json';
            }

            const config = { method, headers };
            if (data && method !== 'GET') {
                config.body = JSON.stringify(data);
            }

            try {
                const response = await fetch(`/api${endpoint}`, config);

                if (response.status === 401) {
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                    return null;
                }

                const result = await response.json();
                return {
                    ok: response.ok,
                    status: response.status,
                    data: result
                };
            } catch (error) {
                console.error('API call failed:', error);
                return {
                    ok: false,
                    status: 0,
                    data: { status: 'error', message: 'Network error occurred' }
                };
            }
        }

        // Load user profile
        async function loadUserProfile() {
            try {
                const result = await apiCall('/profile');
                if (result && result.ok) {
                    const user = result.data.user;
                    document.getElementById('user-name').textContent = user.name;
                }
            } catch (error) {
                console.error('Error loading profile:', error);
            }
        }

        // Load quiz data
        async function loadQuiz() {
            try {
                showLoading();

                const result = await apiCall(`/courses/${courseId}/quizzes/${quizId}`);

                if (result && result.ok) {
                    quizData = result.data.data || result.data;

                    // Generate mock questions for demo
                    generateMockQuestions();

                    displayQuizStart();
                    hideLoading();
                } else {
                    showError(result.data?.message || 'Failed to load quiz');
                }
            } catch (error) {
                console.error('Error loading quiz:', error);
                showError('An error occurred while loading the quiz');
            }
        }

        // Generate mock questions for demonstration
        function generateMockQuestions() {
            const questionTypes = ['multiple_choice', 'true_false', 'short_answer'];
            const sampleQuestions = [
                {
                    type: 'multiple_choice',
                    question: 'What is the primary purpose of object-oriented programming?',
                    options: [
                        'To make code more complex',
                        'To organize code into reusable objects',
                        'To eliminate the need for functions',
                        'To make programs run faster'
                    ],
                    correct_answer: 1
                },
                {
                    type: 'true_false',
                    question: 'JavaScript is a compiled programming language.',
                    correct_answer: false
                },
                {
                    type: 'multiple_choice',
                    question: 'Which of the following is NOT a valid HTML tag?',
                    options: [
                        '<div>',
                        '<span>',
                        '<section>',
                        '<loop>'
                    ],
                    correct_answer: 3
                },
                {
                    type: 'short_answer',
                    question: 'Explain the difference between let and var in JavaScript.',
                    correct_answer: 'Sample answer about scope and hoisting'
                },
                {
                    type: 'multiple_choice',
                    question: 'What does CSS stand for?',
                    options: [
                        'Computer Style Sheets',
                        'Cascading Style Sheets',
                        'Creative Style Sheets',
                        'Colorful Style Sheets'
                    ],
                    correct_answer: 1
                }
            ];

            questions = sampleQuestions.map((q, index) => ({
                id: index + 1,
                ...q,
                points: 10
            }));
        }

        // Display quiz start screen
        function displayQuizStart() {
            document.getElementById('quiz-header').classList.remove('hidden');
            document.getElementById('quiz-start').classList.remove('hidden');

            // Update header
            document.getElementById('course-code').textContent = quizData.course?.code || 'Course';
            document.getElementById('quiz-title').textContent = quizData.title;
            document.getElementById('quiz-description').textContent = quizData.description || 'No description available';
            document.getElementById('quiz-score').textContent = `${quizData.max_score || questions.length * 10}`;

            // Update start screen
            document.getElementById('start-questions-count').textContent = questions.length;
            document.getElementById('start-time-limit').textContent = quizData.duration || 'No Limit';
            document.getElementById('start-max-score').textContent = quizData.max_score || questions.length * 10;

            // Setup start button
            document.getElementById('start-quiz-btn').addEventListener('click', startQuiz);
        }

        // Start the quiz
        function startQuiz() {
            quizStarted = true;

            // Hide start screen, show quiz content
            document.getElementById('quiz-start').classList.add('hidden');
            document.getElementById('quiz-content').classList.remove('hidden');

            // Initialize timer if duration is set
            if (quizData.duration && quizData.duration !== 'No Limit') {
                const duration = parseInt(quizData.duration);
                if (!isNaN(duration)) {
                    timeRemaining = duration * 60; // Convert minutes to seconds
                    startTimer();
                }
            } else {
                document.getElementById('timer-text').textContent = 'No Time Limit';
            }

            // Initialize quiz interface
            setupQuizInterface();
            displayQuestion(0);
        }

        // Setup quiz interface
        function setupQuizInterface() {
            // Create question navigator
            const navigator = document.getElementById('question-navigator');
            navigator.innerHTML = questions.map((q, index) => `
                <button class="question-nav-btn w-8 h-8 rounded border-2 border-gray-300 text-sm font-medium hover:border-indigo-500 transition-colors"
                        data-question="${index}" onclick="goToQuestion(${index})">
                    ${index + 1}
                </button>
            `).join('');

            // Setup navigation buttons
            document.getElementById('prev-question').addEventListener('click', () => {
                if (currentQuestionIndex > 0) {
                    displayQuestion(currentQuestionIndex - 1);
                }
            });

            document.getElementById('next-question').addEventListener('click', () => {
                if (currentQuestionIndex < questions.length - 1) {
                    displayQuestion(currentQuestionIndex + 1);
                }
            });

            document.getElementById('save-answer').addEventListener('click', saveCurrentAnswer);
            document.getElementById('submit-quiz').addEventListener('click', submitQuiz);
            document.getElementById('save-draft').addEventListener('click', saveDraft);
            document.getElementById('review-answers').addEventListener('click', reviewAnswers);
        }

        // Display a specific question
        function displayQuestion(index) {
            currentQuestionIndex = index;
            const question = questions[index];

            // Update progress
            updateProgress();

            // Update navigation buttons
            document.getElementById('prev-question').disabled = index === 0;
            document.getElementById('next-question').disabled = index === questions.length - 1;

            // Update question navigator
            document.querySelectorAll('.question-nav-btn').forEach((btn, i) => {
                btn.classList.remove('bg-indigo-600', 'text-white', 'border-indigo-600');
                btn.classList.add('border-gray-300');

                if (i === index) {
                    btn.classList.add('bg-indigo-600', 'text-white', 'border-indigo-600');
                    btn.classList.remove('border-gray-300');
                } else if (answers[i] !== undefined) {
                    btn.classList.add('bg-green-100', 'border-green-300');
                    btn.classList.remove('border-gray-300');
                }
            });

            // Display question content
            const container = document.getElementById('question-container');
            container.innerHTML = generateQuestionHTML(question, index);

            // Load saved answer if exists
            if (answers[index] !== undefined) {
                loadSavedAnswer(index);
            }
        }

        // Generate HTML for different question types
        function generateQuestionHTML(question, index) {
            let html = `
                <div class="question-card active">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">
                            Question ${index + 1} of ${questions.length}
                        </h3>
                        <span class="bg-indigo-100 text-indigo-800 text-sm font-medium px-3 py-1 rounded-full">
                            ${question.points} points
                        </span>
                    </div>
                    <div class="mb-6">
                        <p class="text-gray-800 text-lg mb-4">${question.question}</p>
            `;

            switch (question.type) {
                case 'multiple_choice':
                    html += `<div class="space-y-3">`;
                    question.options.forEach((option, optionIndex) => {
                        html += `
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="question_${index}" value="${optionIndex}" class="mr-3">
                                <span class="text-gray-700">${option}</span>
                            </label>
                        `;
                    });
                    html += `</div>`;
                    break;

                case 'true_false':
                    html += `
                        <div class="space-y-3">
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="question_${index}" value="true" class="mr-3">
                                <span class="text-gray-700">True</span>
                            </label>
                            <label class="flex items-center p-3 border border-gray-200 rounded-lg hover:bg-gray-50 cursor-pointer">
                                <input type="radio" name="question_${index}" value="false" class="mr-3">
                                <span class="text-gray-700">False</span>
                            </label>
                        </div>
                    `;
                    break;

                case 'short_answer':
                    html += `
                        <textarea name="question_${index}" rows="4"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Enter your answer here..."></textarea>
                    `;
                    break;
            }

            html += `
                    </div>
                </div>
            `;

            return html;
        }

        // Load saved answer for a question
        function loadSavedAnswer(index) {
            const question = questions[index];
            const savedAnswer = answers[index];

            if (savedAnswer === undefined) return;

            switch (question.type) {
                case 'multiple_choice':
                case 'true_false':
                    const radio = document.querySelector(`input[name="question_${index}"][value="${savedAnswer}"]`);
                    if (radio) radio.checked = true;
                    break;

                case 'short_answer':
                    const textarea = document.querySelector(`textarea[name="question_${index}"]`);
                    if (textarea) textarea.value = savedAnswer;
                    break;
            }
        }

        // Save current answer
        function saveCurrentAnswer() {
            const question = questions[currentQuestionIndex];
            let answer = null;

            switch (question.type) {
                case 'multiple_choice':
                case 'true_false':
                    const radio = document.querySelector(`input[name="question_${currentQuestionIndex}"]:checked`);
                    if (radio) answer = radio.value;
                    break;

                case 'short_answer':
                    const textarea = document.querySelector(`textarea[name="question_${currentQuestionIndex}"]`);
                    if (textarea) answer = textarea.value.trim();
                    break;
            }

            if (answer !== null && answer !== '') {
                answers[currentQuestionIndex] = answer;
                showNotification('Answer saved!');

                // Update question navigator
                displayQuestion(currentQuestionIndex);
            } else {
                showNotification('Please provide an answer before saving.', 'warning');
            }
        }

        // Navigate to specific question
        function goToQuestion(index) {
            displayQuestion(index);
        }

        // Update progress display
        function updateProgress() {
            const progress = ((currentQuestionIndex + 1) / questions.length) * 100;
            document.getElementById('progress-bar').style.width = `${progress}%`;
            document.getElementById('progress-text').textContent = `Question ${currentQuestionIndex + 1} of ${questions.length}`;
        }

        // Start timer
        function startTimer() {
            timerInterval = setInterval(() => {
                timeRemaining--;
                updateTimerDisplay();

                if (timeRemaining <= 0) {
                    clearInterval(timerInterval);
                    autoSubmitQuiz();
                }
            }, 1000);
        }

        // Update timer display
        function updateTimerDisplay() {
            const minutes = Math.floor(timeRemaining / 60);
            const seconds = timeRemaining % 60;
            const timeText = `${minutes}:${seconds.toString().padStart(2, '0')}`;

            document.getElementById('timer-text').textContent = timeText;

            // Add warning styling when time is low
            const timerDisplay = document.getElementById('timer-display');
            if (timeRemaining <= 300) { // 5 minutes
                timerDisplay.classList.add('timer-warning', 'bg-red-100', 'text-red-800');
                timerDisplay.classList.remove('bg-indigo-100', 'text-indigo-800');
            }
        }

        // Auto-submit quiz when time runs out
        function autoSubmitQuiz() {
            showNotification('Time is up! Quiz submitted automatically.', 'warning');
            submitQuiz();
        }

        // Submit quiz
        async function submitQuiz() {
            if (!confirm('Are you sure you want to submit your quiz? You cannot change your answers after submission.')) {
                return;
            }

            try {
                // Save current answer before submitting
                saveCurrentAnswer();

                // Calculate score (mock calculation)
                const score = calculateScore();

                // Mock API call for submission
                const result = await apiCall(`/courses/${courseId}/quizzes/${quizId}/submit`, 'POST', {
                    answers: answers,
                    time_taken: quizData.duration ? (parseInt(quizData.duration) * 60 - timeRemaining) : null
                });

                // Stop timer
                if (timerInterval) {
                    clearInterval(timerInterval);
                }

                // Show results
                showQuizResults(score);

            } catch (error) {
                console.error('Error submitting quiz:', error);
                showNotification('Failed to submit quiz. Please try again.', 'error');
            }
        }

        // Calculate score (mock implementation)
        function calculateScore() {
            let correctAnswers = 0;
            let totalQuestions = questions.length;

            questions.forEach((question, index) => {
                const userAnswer = answers[index];
                if (userAnswer !== undefined) {
                    switch (question.type) {
                        case 'multiple_choice':
                            if (parseInt(userAnswer) === question.correct_answer) {
                                correctAnswers++;
                            }
                            break;
                        case 'true_false':
                            if ((userAnswer === 'true') === question.correct_answer) {
                                correctAnswers++;
                            }
                            break;
                        case 'short_answer':
                            // For demo, give credit if answer is not empty
                            if (userAnswer.trim().length > 10) {
                                correctAnswers++;
                            }
                            break;
                    }
                }
            });

            return {
                correct: correctAnswers,
                total: totalQuestions,
                percentage: Math.round((correctAnswers / totalQuestions) * 100),
                points: correctAnswers * 10
            };
        }

        // Show quiz results
        function showQuizResults(score) {
            document.getElementById('quiz-content').classList.add('hidden');
            document.getElementById('quiz-results').classList.remove('hidden');

            document.getElementById('final-score').textContent = `${score.points}/${score.total * 10}`;
            document.getElementById('final-percentage').textContent = `${score.percentage}%`;

            // Calculate letter grade
            let letterGrade = 'F';
            if (score.percentage >= 90) letterGrade = 'A';
            else if (score.percentage >= 80) letterGrade = 'B';
            else if (score.percentage >= 70) letterGrade = 'C';
            else if (score.percentage >= 60) letterGrade = 'D';

            document.getElementById('final-grade').textContent = letterGrade;
        }

        // Save draft
        async function saveDraft() {
            try {
                // Mock API call for saving draft
                const result = await apiCall(`/courses/${courseId}/quizzes/${quizId}/save-draft`, 'POST', {
                    answers: answers,
                    current_question: currentQuestionIndex
                });

                showNotification('Draft saved successfully!');
            } catch (error) {
                console.error('Error saving draft:', error);
                showNotification('Failed to save draft.', 'error');
            }
        }

        // Review answers
        function reviewAnswers() {
            // Show summary of all answers
            let summary = 'Answer Summary:\n\n';
            questions.forEach((question, index) => {
                const answer = answers[index];
                summary += `Question ${index + 1}: ${answer !== undefined ? 'Answered' : 'Not answered'}\n`;
            });

            alert(summary);
        }

        // Utility functions
        function goToCourse() {
            window.location.href = `/courses/${courseId}`;
        }

        function viewResults() {
            showNotification('Detailed results feature coming soon!');
        }

        // Show loading state
        function showLoading() {
            document.getElementById('loading').classList.remove('hidden');
            document.getElementById('quiz-header').classList.add('hidden');
            document.getElementById('quiz-start').classList.add('hidden');
            document.getElementById('quiz-content').classList.add('hidden');
            document.getElementById('quiz-results').classList.add('hidden');
            document.getElementById('error-message').classList.add('hidden');
        }

        // Hide loading state
        function hideLoading() {
            document.getElementById('loading').classList.add('hidden');
        }

        // Show error message
        function showError(message) {
            document.getElementById('loading').classList.add('hidden');
            document.getElementById('quiz-header').classList.add('hidden');
            document.getElementById('quiz-start').classList.add('hidden');
            document.getElementById('quiz-content').classList.add('hidden');
            document.getElementById('quiz-results').classList.add('hidden');
            document.getElementById('error-message').classList.remove('hidden');
            document.getElementById('error-message').querySelector('p:last-of-type').textContent = message;
        }

        // Show notification
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            const bgColor = type === 'error' ? 'bg-red-500' : type === 'warning' ? 'bg-yellow-500' : 'bg-green-500';
            notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg ${bgColor} text-white`;
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }
    </script>
</body>
</html>
