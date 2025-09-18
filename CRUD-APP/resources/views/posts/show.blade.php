@extends('layouts.app')

@section('title', $post->title . ' - Joo Blog')

@section('styles')
<style>
    .post-content {
        line-height: 1.8;
        white-space: pre-line;
        font-size: 1.1rem;
    }

    .post-header {
        position: relative;
        padding-bottom: 1.5rem;
        margin-bottom: 2rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    [data-bs-theme="dark"] .post-header {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .post-title {
        font-weight: 700;
        line-height: 1.3;
    }

    .post-meta {
        display: flex;
        align-items: center;
        margin-top: 1rem;
        color: #6c757d;
    }

    .post-meta-item {
        display: flex;
        align-items: center;
        margin-right: 1.5rem;
    }

    .post-meta-icon {
        margin-right: 0.5rem;
        opacity: 0.7;
    }

    .post-actions {
        margin-top: 3rem;
        padding-top: 1.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.1);
    }

    [data-bs-theme="dark"] .post-actions {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .post-card {
        border-radius: 15px;
        overflow: hidden;
    }

    /* Animation for content reveal */
    .content-reveal {
        opacity: 0;
        transform: translateY(20px);
        animation: revealContent 0.8s ease forwards;
    }

    @keyframes revealContent {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Highlight text effect */
    .highlight-text {
        position: relative;
        display: inline-block;
    }

    .highlight-text::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 8px;
        background-color: rgba(59, 130, 246, 0.2);
        z-index: -1;
        transform: translateY(2px);
    }

    /* Share buttons */
    .share-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 0.5rem;
        transition: all 0.3s ease;
    }

    .share-btn:hover {
        transform: translateY(-3px);
    }

    .share-facebook {
        background-color: #3b5998;
        color: white;
    }

    .share-twitter {
        background-color: #1da1f2;
        color: white;
    }

    .share-linkedin {
        background-color: #0077b5;
        color: white;
    }

    /* Reading progress bar */
    .reading-progress-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: transparent;
        z-index: 1030;
    }

    .reading-progress-bar {
        height: 4px;
        background: linear-gradient(to right, #3b82f6, #10b981);
        width: 0%;
        transition: width 0.1s ease;
    }
</style>
@endsection

@section('content')
<!-- Reading Progress Bar -->
<div class="reading-progress-container">
    <div class="reading-progress-bar" id="readingProgressBar"></div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9 col-md-11">
        <div class="card shadow post-card" data-aos="fade-up">
            <div class="card-body p-md-5 p-4">
                <div class="post-header">
                    <h1 class="post-title mb-3" data-aos="fade-up">{{ $post->title }}</h1>

                    <div class="post-meta" data-aos="fade-up" data-aos-delay="100">
                        <div class="post-meta-item">
                            <i class="fas fa-user post-meta-icon"></i>
                            <span>{{ $post->posted_by }}</span>
                        </div>
                        <div class="post-meta-item">
                            <i class="fas fa-calendar-alt post-meta-icon"></i>
                            <span>{{ $post->created_at->format('F j, Y') }}</span>
                        </div>
                        <div class="post-meta-item">
                            <i class="fas fa-clock post-meta-icon"></i>
                            <span>{{ $post->created_at->format('g:i a') }}</span>
                        </div>
                    </div>
                </div>

                <div class="post-content content-reveal" data-aos="fade-up" data-aos-delay="200">
                    {{ $post->content }}
                </div>

                <div class="post-actions">
                    <div class="row">
                        <div class="col-md-6" data-aos="fade-right" data-aos-delay="300">
                            <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Back to Posts
                            </a>
                        </div>
                        <div class="col-md-6 text-md-end" data-aos="fade-left" data-aos-delay="300">
                            <a href="{{ route('posts.edit', $post) }}" class="btn btn-success me-2">
                                <i class="fas fa-edit me-2"></i>Edit
                            </a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-post-id="{{ $post->id }}">
                                <i class="fas fa-trash-alt me-2"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Author and Share Section -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0" data-aos="fade-up" data-aos-delay="400">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-user-circle text-primary me-2"></i>About the Author
                        </h5>
                        <p class="mb-0">Posts by <strong>{{ $post->posted_by }}</strong></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0" data-aos="fade-up" data-aos-delay="500">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="fas fa-share-alt text-primary me-2"></i>Share this Post
                        </h5>
                        <div class="d-flex mt-2">
                            <a href="#" class="share-btn share-facebook" title="Share on Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="share-btn share-twitter" title="Share on Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="share-btn share-linkedin" title="Share on LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            <button class="btn btn-outline-secondary ms-2" id="copyLinkBtn">
                                <i class="fas fa-link me-2"></i>Copy Link
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Posts Section -->
        <div class="card shadow-sm border-0 mt-4" data-aos="fade-up" data-aos-delay="600">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-newspaper text-primary me-2"></i>You might also like
                </h5>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">Another interesting post</h6>
                                <p class="card-text small text-muted">Check out this related content...</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">More to explore</h6>
                                <p class="card-text small text-muted">Discover similar topics...</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h6 class="card-title">Popular post</h6>
                                <p class="card-text small text-muted">See what others are reading...</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Back to top button -->
<button class="btn btn-primary rounded-circle position-fixed bottom-0 end-0 m-4" id="backToTopBtn" style="width: 45px; height: 45px; display: none; z-index: 1000;">
    <i class="fas fa-arrow-up"></i>
</button>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reading progress bar
        const progressBar = document.getElementById('readingProgressBar');
        const totalHeight = document.body.scrollHeight - window.innerHeight;

        window.addEventListener('scroll', function() {
            const progress = (window.scrollY / totalHeight) * 100;
            progressBar.style.width = progress + '%';
        });

        // Back to top button
        const backToTopBtn = document.getElementById('backToTopBtn');

        window.addEventListener('scroll', function() {
            if (window.scrollY > 300) {
                backToTopBtn.style.display = 'flex';
                backToTopBtn.style.alignItems = 'center';
                backToTopBtn.style.justifyContent = 'center';
            } else {
                backToTopBtn.style.display = 'none';
            }
        });

        backToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });

        // Copy link button
        const copyLinkBtn = document.getElementById('copyLinkBtn');
        if (copyLinkBtn) {
            copyLinkBtn.addEventListener('click', function() {
                const url = window.location.href;
                navigator.clipboard.writeText(url).then(function() {
                    // Show toast notification
                    showToast('Link copied to clipboard!');

                    // Change button text temporarily
                    const originalText = copyLinkBtn.innerHTML;
                    copyLinkBtn.innerHTML = '<i class="fas fa-check me-2"></i>Copied!';

                    setTimeout(function() {
                        copyLinkBtn.innerHTML = originalText;
                    }, 2000);
                });
            });
        }

        // Highlight text on selection
        const postContent = document.querySelector('.post-content');
        if (postContent) {
            document.addEventListener('mouseup', function() {
                const selection = window.getSelection();
                if (selection.toString().length > 0 && selection.rangeCount > 0) {
                    const range = selection.getRangeAt(0);
                    const selectionRect = range.getBoundingClientRect();

                    // Only proceed if selection is within post content
                    if (postContent.contains(range.commonAncestorContainer)) {
                        // Create a tooltip for sharing selected text
                        const tooltip = document.createElement('div');
                        tooltip.className = 'position-absolute bg-primary text-white px-3 py-2 rounded shadow';
                        tooltip.style.left = (selectionRect.left + selectionRect.width/2) + 'px';
                        tooltip.style.top = (window.scrollY + selectionRect.top - 40) + 'px';
                        tooltip.style.transform = 'translateX(-50%)';
                        tooltip.style.zIndex = '1000';
                        tooltip.innerHTML = '<i class="fas fa-share-alt me-2"></i>Share selection';

                        document.body.appendChild(tooltip);

                        // Remove tooltip when clicking elsewhere
                        document.addEventListener('mousedown', function removeTooltip() {
                            document.body.removeChild(tooltip);
                            document.removeEventListener('mousedown', removeTooltip);
                        });

                        // Share functionality
                        tooltip.addEventListener('click', function(e) {
                            e.stopPropagation();
                            const text = selection.toString();
                            const shareText = `"${text}" - from ${document.title}`;

                            // Show toast
                            showToast('Quote copied to clipboard!');

                            // Copy to clipboard
                            navigator.clipboard.writeText(shareText);

                            // Remove tooltip
                            document.body.removeChild(tooltip);
                        });
                    }
                }
            });
        }
    });
</script>
@endsection
