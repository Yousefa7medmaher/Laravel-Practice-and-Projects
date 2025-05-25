<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lecture Details - Educational Platform</title>
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
        .progress-bar {
            background: linear-gradient(90deg, #4f46e5 0%, #7c3aed 100%);
        }
        .video-container {
            position: relative;
            width: 100%;
            height: 0;
            padding-bottom: 56.25%; /* 16:9 aspect ratio */
        }
        .video-container iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
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
                <h1 class="text-xl font-bold text-white">Lecture Details</h1>
            </div>
            <div class="flex items-center space-x-4">
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
            <p class="text-white">Loading lecture details...</p>
        </div>
    </div>

    <!-- Main Content -->
    <div id="main-content" class="hidden mx-4 mb-6">
        <!-- Lecture Header -->
        <div class="glassmorphism p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between">
                <div class="flex-1">
                    <h1 id="lecture-title" class="text-3xl font-bold text-white mb-2">Lecture Title</h1>
                    <p id="lecture-description" class="text-white/80 mb-4">Lecture description...</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                        <div class="bg-white/20 rounded-lg p-3">
                            <div class="text-indigo-200 text-sm">Duration</div>
                            <div class="text-white font-semibold" id="lecture-duration">Loading...</div>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <div class="text-indigo-200 text-sm">Progress</div>
                            <div class="text-white font-semibold" id="lecture-progress">Loading...</div>
                        </div>
                        <div class="bg-white/20 rounded-lg p-3">
                            <div class="text-indigo-200 text-sm">Last Accessed</div>
                            <div class="text-white font-semibold" id="last-accessed">Loading...</div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 lg:mt-0 lg:ml-6">
                    <div class="bg-white/20 rounded-lg p-4 text-center">
                        <div class="text-2xl font-bold text-white mb-1" id="progress-percentage">0%</div>
                        <div class="text-indigo-200 text-sm mb-2">Completed</div>
                        <div class="w-24 h-2 bg-white/20 rounded-full overflow-hidden">
                            <div id="progress-bar" class="h-full progress-bar transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Content -->
        <div id="video-section" class="glassmorphism p-6 mb-6">
            <h3 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-play-circle mr-2"></i>Lecture Video
            </h3>
            <div id="video-content">
                <!-- Video will be loaded here -->
            </div>
        </div>

        <!-- Lecture Content -->
        <div class="glassmorphism p-6 mb-6">
            <h3 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-file-text mr-2"></i>Lecture Content
            </h3>
            <div id="lecture-content" class="text-white/90 prose prose-invert max-w-none">
                Loading content...
            </div>
        </div>

        <!-- Progress Controls -->
        <div class="glassmorphism p-6 mb-6">
            <h3 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-tasks mr-2"></i>Progress Tracking
            </h3>
            
            <div class="space-y-4">
                <div>
                    <label for="progress-slider" class="block text-white font-medium mb-2">
                        Update your progress:
                    </label>
                    <input 
                        type="range" 
                        id="progress-slider" 
                        min="0" 
                        max="100" 
                        value="0" 
                        class="w-full h-2 bg-white/20 rounded-lg appearance-none cursor-pointer"
                        onchange="updateProgress(this.value)">
                    <div class="flex justify-between text-sm text-indigo-200 mt-1">
                        <span>0%</span>
                        <span>50%</span>
                        <span>100%</span>
                    </div>
                </div>
                
                <div class="flex space-x-4">
                    <button onclick="markAsCompleted()" class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg transition-colors">
                        <i class="fas fa-check mr-2"></i>Mark as Completed
                    </button>
                    <button onclick="saveProgress()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg transition-colors">
                        <i class="fas fa-save mr-2"></i>Save Progress
                    </button>
                </div>
            </div>
        </div>

        <!-- Notes Section -->
        <div class="glassmorphism p-6">
            <h3 class="text-xl font-bold text-white mb-4">
                <i class="fas fa-sticky-note mr-2"></i>My Notes
            </h3>
            
            <textarea 
                id="lecture-notes" 
                rows="6" 
                class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-transparent"
                placeholder="Add your notes about this lecture..."></textarea>
            
            <div class="flex justify-end mt-4">
                <button onclick="saveNotes()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg transition-colors">
                    <i class="fas fa-save mr-2"></i>Save Notes
                </button>
            </div>
        </div>
    </div>

    <!-- Notification Container -->
    <div id="notification-container" class="fixed top-4 right-4 z-50"></div>

    <script>
        // Global variables
        const courseId = {{ $course_id }};
        const lectureId = {{ $lecture_id }};
        let authToken = localStorage.getItem('token');
        let lectureData = null;
        let currentProgress = 0;

        // Initialize page
        document.addEventListener('DOMContentLoaded', function() {
            if (!authToken) {
                window.location.href = '/login';
                return;
            }
            
            loadLectureData();
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

        // Load lecture data
        async function loadLectureData() {
            try {
                const result = await apiCall(`/student/lectures/${lectureId}`);
                
                if (result && result.ok) {
                    lectureData = result.data;
                    displayLectureData();
                    hideLoading();
                } else {
                    showNotification('Failed to load lecture data', 'error');
                    setTimeout(() => {
                        goBack();
                    }, 2000);
                }
            } catch (error) {
                console.error('Error loading lecture data:', error);
                showNotification('Error loading lecture data', 'error');
            }
        }

        // Display lecture data
        function displayLectureData() {
            if (!lectureData) return;

            document.getElementById('lecture-title').textContent = lectureData.title;
            document.getElementById('lecture-description').textContent = lectureData.description || 'No description provided';
            document.getElementById('lecture-content').innerHTML = lectureData.content || 'No content available for this lecture.';
            
            const duration = lectureData.duration ? `${lectureData.duration} minutes` : 'Not specified';
            document.getElementById('lecture-duration').textContent = duration;
            
            // Update progress information
            const progress = lectureData.progress || {};
            currentProgress = progress.progress_percentage || 0;
            
            document.getElementById('lecture-progress').textContent = `${currentProgress}%`;
            document.getElementById('progress-percentage').textContent = `${currentProgress}%`;
            document.getElementById('progress-bar').style.width = `${currentProgress}%`;
            document.getElementById('progress-slider').value = currentProgress;
            
            const lastAccessed = progress.last_accessed ? new Date(progress.last_accessed).toLocaleDateString() : 'Never';
            document.getElementById('last-accessed').textContent = lastAccessed;
            
            // Load video if available
            if (lectureData.video_url) {
                loadVideo(lectureData.video_url);
            } else {
                document.getElementById('video-content').innerHTML = `
                    <div class="bg-white/10 rounded-lg p-8 text-center">
                        <i class="fas fa-video text-4xl text-white/60 mb-4"></i>
                        <p class="text-white/80">No video available for this lecture</p>
                    </div>
                `;
            }
            
            // Load notes if available
            if (progress.notes) {
                document.getElementById('lecture-notes').value = progress.notes;
            }
        }

        // Load video
        function loadVideo(videoUrl) {
            const videoContent = document.getElementById('video-content');
            
            // Check if it's a YouTube URL
            if (videoUrl.includes('youtube.com') || videoUrl.includes('youtu.be')) {
                const videoId = extractYouTubeId(videoUrl);
                if (videoId) {
                    videoContent.innerHTML = `
                        <div class="video-container">
                            <iframe 
                                src="https://www.youtube.com/embed/${videoId}" 
                                frameborder="0" 
                                allowfullscreen>
                            </iframe>
                        </div>
                    `;
                    return;
                }
            }
            
            // For other video URLs or direct video files
            videoContent.innerHTML = `
                <video controls class="w-full rounded-lg">
                    <source src="${videoUrl}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            `;
        }

        // Extract YouTube video ID
        function extractYouTubeId(url) {
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
            const match = url.match(regExp);
            return (match && match[2].length === 11) ? match[2] : null;
        }

        // Update progress
        function updateProgress(percentage) {
            currentProgress = parseInt(percentage);
            document.getElementById('progress-percentage').textContent = `${currentProgress}%`;
            document.getElementById('progress-bar').style.width = `${currentProgress}%`;
        }

        // Mark as completed
        function markAsCompleted() {
            updateProgress(100);
            saveProgress();
        }

        // Save progress
        async function saveProgress() {
            try {
                const result = await apiCall(`/student/lectures/${lectureId}/progress`, 'POST', {
                    progress_percentage: currentProgress,
                    completed: currentProgress >= 100
                });
                
                if (result && result.ok) {
                    showNotification('Progress saved successfully!', 'success');
                    document.getElementById('lecture-progress').textContent = `${currentProgress}%`;
                } else {
                    showNotification('Failed to save progress', 'error');
                }
            } catch (error) {
                console.error('Error saving progress:', error);
                showNotification('Error saving progress', 'error');
            }
        }

        // Save notes
        async function saveNotes() {
            const notes = document.getElementById('lecture-notes').value;
            
            try {
                const result = await apiCall(`/student/lectures/${lectureId}/progress`, 'POST', {
                    progress_percentage: currentProgress,
                    completed: currentProgress >= 100,
                    notes: notes
                });
                
                if (result && result.ok) {
                    showNotification('Notes saved successfully!', 'success');
                } else {
                    showNotification('Failed to save notes', 'error');
                }
            } catch (error) {
                console.error('Error saving notes:', error);
                showNotification('Error saving notes', 'error');
            }
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
            
            const bgColor = type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-blue-500';
            
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
