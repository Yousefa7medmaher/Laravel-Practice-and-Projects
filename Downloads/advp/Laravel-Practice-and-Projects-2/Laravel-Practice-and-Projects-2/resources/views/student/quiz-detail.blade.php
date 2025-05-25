<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Details - Educational Platform</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .glassmorphism {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            border: 1px solid rgba(255, 255, 255, 0.18);
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .timer-display {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        }
    </style>
</head>
<body class="min-h-screen gradient-bg">
    <!-- Navigation -->
    <nav class="glassmorphism m-4 p-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button onclick="goBack()" class="text-white hover:text-indigo-200 transition-colors">
                    <i class="fas fa-arrow-left text-xl"></i>
                </button>
                <h1 class="text-xl font-bold text-white">Quiz Details</h1>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Timer (shown during quiz) -->
                <div id="quiz-timer" class="hidden timer-display text-white px-4 py-2 rounded-lg font-bold">
                    <i class="fas fa-clock mr-2"></i>
                    <span id="timer-display">00:00</span>
                </div>
                <span class="text-white text-sm" id="student-name">Loading...</span>
                <button onclick="logout()" class="text-white hover:text-red-300 transition-colors">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Loading State -->
    <div id="loading" class="flex items-center justify-center min-h-[60vh]">
        <div class="glassmorphism p-8 text-center">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-white mx-auto mb-4"></div>
            <p class="text-white">Loading quiz details...</p>
        </div>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="hidden mx-4 mb-6">
        <!-- Quiz Header -->
        <div class="glassmorphism p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                <div class="flex-1">
                    <h1 id="quiz-title" class="text-3xl font-bold text-white mb-2">Quiz Title</h1>
                    <p id="quiz-description" class="text-white/80 mb-4">Quiz description...</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
                        <div class="bg-white/20 rounded-lg p-3">
                            <div class="text-indigo-200 text-sm">Duration</div>
                            <div class="text-white font-semibold" id="quiz-duration">Loading...</div>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <div class="text-indigo-200 text-sm">Max Score</div>
                            <div class="text-white font-semibold" id="max-score">Loading...</div>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <div class="text-indigo-200 text-sm">Attempts</div>
                            <div class="text-white font-semibold" id="attempts-info">Loading...</div>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <div class="text-indigo-200 text-sm">Best Score</div>
                            <div class="text-white font-semibold" id="best-score">Loading...</div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 lg:mt-0 lg:ml-6">
                    <div id="quiz-actions" class="space-y-2">
                        <!-- Action buttons will be populated here -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Quiz Instructions -->
        <div class="glassmorphism p-6 mb-6">
            <h3 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-info-circle mr-2"></i>Instructions
            </h3>
            <div id="quiz-instructions" class="text-white/90 prose prose-invert max-w-none">
                Loading instructions...
            </div>
        </div>

        <!-- Quiz Taking Interface -->
        <div id="quiz-interface" class="hidden">
            <div class="glassmorphism p-6 mb-6">
                <h3 class="text-xl font-bold text-white mb-4">
                    <i class="fas fa-question-circle mr-2"></i>Quiz Questions
                </h3>
                <div id="quiz-questions">
                    <!-- Quiz questions will be loaded here -->
                    <div class="text-center py-8">
                        <p class="text-white/80 mb-4">This is a demo quiz interface.</p>
                        <p class="text-white/60 text-sm">In a full implementation, questions would be loaded from the database.</p>
                        
                        <!-- Sample Question -->
                        <div class="bg-white/10 rounded-lg p-6 mt-6 text-left">
                            <h4 class="text-white font-semibold mb-4">Sample Question 1:</h4>
                            <p class="text-white/90 mb-4">What is the primary purpose of HTML in web development?</p>
                            
                            <div class="space-y-2">
                                <label class="flex items-center text-white/80 cursor-pointer">
                                    <input type="radio" name="q1" value="a" class="mr-3">
                                    <span>To style web pages</span>
                                </label>
                                <label class="flex items-center text-white/80 cursor-pointer">
                                    <input type="radio" name="q1" value="b" class="mr-3">
                                    <span>To structure web content</span>
                                </label>
                                <label class="flex items-center text-white/80 cursor-pointer">
                                    <input type="radio" name="q1" value="c" class="mr-3">
                                    <span>To add interactivity</span>
                                </label>
                                <label class="flex items-center text-white/80 cursor-pointer">
                                    <input type="radio" name="q1" value="d" class="mr-3">
                                    <span>To manage databases</span>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-4 mt-6">
                    <button onclick="submitQuiz()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors">
                        <i class="fas fa-paper-plane mr-2"></i>Submit Quiz
                    </button>
                </div>
            </div>
        </div>

        <!-- Previous Attempts -->
        <div id="previous-attempts" class="glassmorphism p-6 mb-6">
            <h3 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-history mr-2"></i>Previous Attempts
            </h3>
            <div id="attempts-list">
                <!-- Previous attempts will be loaded here -->
            </div>
        </div>

        <!-- Quiz Results -->
        <div id="quiz-results" class="glassmorphism p-6 hidden">
            <h3 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-chart-bar mr-2"></i>Quiz Results
            </h3>
            <div id="results-content">
                <!-- Results will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50"></div>

    <script>
        // Global variables
        const courseId = {{ $course_id }};
        const quizId = {{ $quiz_id }};
        let authToken = localStorage.getItem('token');
        let quizData = null;
        let currentAttempt = null;
        let timerInterval = null;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken) {
                window.location.href = '/login';
                return;
            }
            
            loadQuizData();
            loadUserInfo();
        });

        // API call utility function
        async function apiCall(endpoint, method = 'GET', data = null) {
            const headers = {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': `Bearer ${authToken}`
            };

            const config = { method, headers };
            if (data) config.body = JSON.stringify(data);

            try {
                const response = await fetch(`/api${endpoint}`, config);

                if (response.status === 401) {
                    localStorage.removeItem('token');
                    window.location.href = '/login';
                    return null;
                }

                const result = await response.json();
                return { status: response.status, ok: response.ok, data: result };
            } catch (error) {
                console.error('API call error:', error);
                return { status: 0, ok: false, error: error.message };
            }
        }

        // Load quiz data
        async function loadQuizData() {
            try {
                const result = await apiCall(`/student/quizzes/${quizId}`);
                
                if (result && result.ok) {
                    quizData = result.data;
                    displayQuizData();
                    loadPreviousAttempts();
                    hideLoading();
                } else {
                    showNotification('Failed to load quiz data', 'error');
                    setTimeout(() => {
                        goBack();
                    }, 2000);
                }
            } catch (error) {
                console.error('Error loading quiz data:', error);
                showNotification('Error loading quiz data', 'error');
            }
        }

        // Display quiz data
        function displayQuizData() {
            if (!quizData) return;

            document.getElementById('quiz-title').textContent = quizData.title;
            document.getElementById('quiz-description').textContent = quizData.description || 'No description provided';
            document.getElementById('quiz-instructions').innerHTML = quizData.instructions || 'No specific instructions provided';
            
            const duration = quizData.duration_minutes ? `${quizData.duration_minutes} minutes` : 'No time limit';
            document.getElementById('quiz-duration').textContent = duration;
            document.getElementById('max-score').textContent = `${quizData.max_score || 100} points`;
            
            const attemptsText = `${quizData.attempts_taken || 0}/${quizData.max_attempts || '∞'}`;
            document.getElementById('attempts-info').textContent = attemptsText;
            
            const bestScore = quizData.best_score ? `${quizData.best_score}%` : 'No attempts';
            document.getElementById('best-score').textContent = bestScore;
            
            updateQuizActions();
        }

        // Update quiz actions based on availability
        function updateQuizActions() {
            const actionsElement = document.getElementById('quiz-actions');
            
            if (quizData.can_attempt !== false && quizData.is_available !== false) {
                actionsElement.innerHTML = `
                    <button onclick="startQuiz()" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg transition-colors">
                        <i class="fas fa-play mr-2"></i>${quizData.attempts_taken > 0 ? 'Retake Quiz' : 'Start Quiz'}
                    </button>
                `;
            } else {
                let reason = 'Quiz not available';
                if (quizData.can_attempt === false) {
                    reason = 'Maximum attempts reached';
                } else if (quizData.is_available === false) {
                    reason = 'Quiz not available at this time';
                }
                
                actionsElement.innerHTML = `
                    <div class="text-red-300 text-sm">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        ${reason}
                    </div>
                `;
            }
        }

        // Start quiz
        async function startQuiz() {
            try {
                const result = await apiCall(`/student/quizzes/${quizId}/start`, 'POST');
                
                if (result && result.ok) {
                    currentAttempt = result.data;
                    showNotification('Quiz started successfully!', 'success');
                    showQuizInterface();
                    startTimer();
                } else {
                    const errorMessage = result?.data?.message || 'Failed to start quiz';
                    showNotification(errorMessage, 'error');
                }
            } catch (error) {
                console.error('Error starting quiz:', error);
                showNotification('Error starting quiz', 'error');
            }
        }

        // Show quiz interface
        function showQuizInterface() {
            document.getElementById('quiz-interface').classList.remove('hidden');
            document.getElementById('quiz-timer').classList.remove('hidden');
        }

        // Start timer
        function startTimer() {
            if (!currentAttempt || !currentAttempt.expires_at) return;
            
            const expiresAt = new Date(currentAttempt.expires_at);
            
            timerInterval = setInterval(() => {
                const now = new Date();
                const timeLeft = expiresAt - now;
                
                if (timeLeft <= 0) {
                    clearInterval(timerInterval);
                    showNotification('Time is up! Quiz will be auto-submitted.', 'warning');
                    submitQuiz();
                    return;
                }
                
                const minutes = Math.floor(timeLeft / 60000);
                const seconds = Math.floor((timeLeft % 60000) / 1000);
                document.getElementById('timer-display').textContent = 
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }, 1000);
        }

        // Submit quiz
        async function submitQuiz() {
            if (!currentAttempt) {
                showNotification('No active quiz attempt', 'error');
                return;
            }

            try {
                // Collect answers (demo implementation)
                const answers = {
                    q1: document.querySelector('input[name="q1"]:checked')?.value || null
                };

                const result = await apiCall(`/student/quizzes/${quizId}/submit`, 'POST', { answers });
                
                if (result && result.ok) {
                    clearInterval(timerInterval);
                    showNotification('Quiz submitted successfully!', 'success');
                    
                    // Show results
                    displayQuizResults(result.data);
                    
                    // Hide quiz interface
                    document.getElementById('quiz-interface').classList.add('hidden');
                    document.getElementById('quiz-timer').classList.add('hidden');
                    
                    // Reload quiz data
                    setTimeout(() => {
                        loadQuizData();
                    }, 2000);
                } else {
                    const errorMessage = result?.data?.message || 'Failed to submit quiz';
                    showNotification(errorMessage, 'error');
                }
            } catch (error) {
                console.error('Error submitting quiz:', error);
                showNotification('Error submitting quiz', 'error');
            }
        }

        // Display quiz results
        function displayQuizResults(resultData) {
            const resultsElement = document.getElementById('quiz-results');
            const resultsContent = document.getElementById('results-content');
            
            resultsContent.innerHTML = `
                <div class="bg-white/10 rounded-lg p-6">
                    <div class="text-center mb-6">
                        <div class="text-4xl font-bold text-white mb-2">${resultData.score}%</div>
                        <div class="text-indigo-200">Your Score</div>
                    </div>
                    <div class="text-center text-white/80">
                        <p>Quiz completed successfully!</p>
                        <p class="text-sm mt-2">Attempt #${resultData.attempt.attempt_number}</p>
                    </div>
                </div>
            `;
            
            resultsElement.classList.remove('hidden');
        }

        // Load previous attempts
        async function loadPreviousAttempts() {
            try {
                const result = await apiCall(`/student/quizzes/${quizId}/attempts`);
                
                if (result && result.ok) {
                    displayPreviousAttempts(result.data);
                }
            } catch (error) {
                console.error('Error loading previous attempts:', error);
            }
        }

        // Display previous attempts
        function displayPreviousAttempts(attempts) {
            const attemptsList = document.getElementById('attempts-list');
            
            if (attempts.length === 0) {
                attemptsList.innerHTML = '<p class="text-white/60 text-center py-4">No previous attempts</p>';
                return;
            }
            
            attemptsList.innerHTML = attempts.map((attempt, index) => `
                <div class="bg-white/10 rounded-lg p-4 mb-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-white font-medium">Attempt #${attempt.attempt_number}</div>
                            <div class="text-indigo-200 text-sm">${new Date(attempt.started_at).toLocaleString()}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-white font-bold">${attempt.score || 'N/A'}%</div>
                            <div class="text-indigo-200 text-sm">${attempt.status}</div>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Load user info
        async function loadUserInfo() {
            const user = JSON.parse(localStorage.getItem('user') || '{}');
            if (user.name) {
                document.getElementById('student-name').textContent = user.name;
            }
        }

        // Utility functions
        function hideLoading() {
            document.getElementById('loading').classList.add('hidden');
            document.getElementById('main-content').classList.remove('hidden');
        }

        function goBack() {
            window.location.href = `/student/courses/${courseId}`;
        }

        function logout() {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            localStorage.removeItem('role');
            window.location.href = '/login';
        }

        function showNotification(message, type = 'info') {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');
            
            const bgColor = type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
            
            notification.className = `${bgColor} text-white px-6 py-3 rounded-lg shadow-lg mb-4 transform transition-all duration-300 translate-x-full`;
            notification.textContent = message;
            
            container.appendChild(notification);
            
            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            // Remove after 5 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    container.removeChild(notification);
                }, 300);
            }, 5000);
        }
    </script>
</body>
</html>
