@extends('layouts.app')

@section('title', 'Create New Post - Joo Blog')

@section('styles')
<style>
    .form-floating {
        margin-bottom: 1.5rem;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
    }

    .form-label {
        font-weight: 500;
    }

    .form-control {
        transition: all 0.3s ease;
    }

    .form-control:hover {
        border-color: var(--primary-color);
    }

    .btn-submit {
        min-width: 120px;
    }

    .card-form {
        border-radius: 15px;
        overflow: hidden;
    }

    .card-header {
        padding: 1.25rem 1.5rem;
    }

    .input-icon {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        right: 10px;
        color: #6c757d;
    }

    .input-with-icon {
        padding-right: 40px;
    }

    /* Animation for form elements */
    .form-animate-in {
        animation: formSlideUp 0.5s ease forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    @keyframes formSlideUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" data-aos="fade-right">Create New Post</h1>
            <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary" data-aos="fade-left">
                <i class="fas fa-arrow-left me-2"></i>Back to Posts
            </a>
        </div>

        <div class="card shadow card-form" data-aos="fade-up" data-aos-delay="200">
            <div class="card-header bg-primary text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-pen-fancy me-2"></i>
                    <h5 class="mb-0">Post Details</h5>
                </div>
            </div>
            <div class="card-body p-4">
                @if ($errors->any())
                <div class="alert alert-danger" data-aos="fade-in">
                    <div class="d-flex">
                        <i class="fas fa-exclamation-circle me-2 mt-1"></i>
                        <div>
                            <strong>Oops! There were some problems with your input.</strong>
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                @endif

                <form action="{{ route('posts.store') }}" method="POST" id="createPostForm">
                    @csrf
                    <div class="mb-4 position-relative form-animate-in" style="animation-delay: 100ms;">
                        <label for="title" class="form-label">
                            <i class="fas fa-heading me-1 text-primary"></i>Title
                        </label>
                        <input
                            type="text"
                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
                            placeholder="Enter post title"
                            required
                            autofocus
                        >
                        <i class="fas fa-pen input-icon"></i>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">Choose a clear, descriptive title for your post.</div>
                    </div>

                    <div class="mb-4 form-animate-in" style="animation-delay: 200ms;">
                        <label for="content" class="form-label">
                            <i class="fas fa-align-left me-1 text-primary"></i>Content
                        </label>
                        <textarea
                            class="form-control @error('content') is-invalid @enderror"
                            id="content"
                            name="content"
                            rows="8"
                            placeholder="Write your post content here..."
                            required
                        >{{ old('content') }}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 position-relative form-animate-in" style="animation-delay: 300ms;">
                        <label for="posted_by" class="form-label">
                            <i class="fas fa-user me-1 text-primary"></i>Author
                        </label>
                        <input
                            type="text"
                            class="form-control @error('posted_by') is-invalid @enderror"
                            id="posted_by"
                            name="posted_by"
                            value="{{ old('posted_by') }}"
                            placeholder="Your name"
                            required
                        >
                        <i class="fas fa-signature input-icon"></i>
                        @error('posted_by')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between mt-5 form-animate-in" style="animation-delay: 400ms;">
                        <button type="button" class="btn btn-outline-secondary" onclick="window.location.href='{{ route('posts.index') }}'">
                            <i class="fas fa-times me-2"></i>Cancel
                        </button>
                        <button type="submit" class="btn btn-primary btn-submit" id="submitBtn">
                            <i class="fas fa-paper-plane me-2"></i>Create Post
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4 shadow-sm border-0" data-aos="fade-up" data-aos-delay="400">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fas fa-lightbulb text-warning me-2"></i>Tips for a Great Post
                </h5>
                <ul class="mb-0">
                    <li>Use a clear and descriptive title</li>
                    <li>Structure your content with paragraphs</li>
                    <li>Include relevant details and examples</li>
                    <li>Proofread before submitting</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading animation to submit button
        const form = document.getElementById('createPostForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Creating...';
            submitBtn.disabled = true;
        });

        // Character counter for title
        const titleInput = document.getElementById('title');
        if (titleInput) {
            titleInput.addEventListener('input', function() {
                const maxLength = 255;
                const currentLength = this.value.length;

                if (currentLength > maxLength - 50) {
                    this.classList.add('border-warning');
                } else {
                    this.classList.remove('border-warning');
                }

                if (currentLength > maxLength) {
                    this.classList.add('border-danger');
                } else {
                    this.classList.remove('border-danger');
                }
            });
        }

        // Auto-resize textarea
        const textarea = document.getElementById('content');
        if (textarea) {
            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }
    });
</script>
@endsection
