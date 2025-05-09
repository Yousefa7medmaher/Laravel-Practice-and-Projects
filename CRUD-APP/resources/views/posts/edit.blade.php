@extends('layouts.app')

@section('title', 'Edit Post - Joo Blog')

@section('styles')
<style>
    .form-floating {
        margin-bottom: 1.5rem;
    }

    .form-control:focus {
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25);
    }

    .form-label {
        font-weight: 500;
    }

    .form-control {
        transition: all 0.3s ease;
    }

    .form-control:hover {
        border-color: var(--secondary-color);
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

    /* Edit mode highlight */
    .edit-highlight {
        position: relative;
    }

    .edit-highlight::before {
        content: '';
        position: absolute;
        left: -15px;
        top: 0;
        height: 100%;
        width: 3px;
        background-color: var(--secondary-color);
        border-radius: 3px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .edit-highlight:focus-within::before {
        opacity: 1;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0" data-aos="fade-right">Edit Post</h1>
            <div data-aos="fade-left">
                <a href="{{ route('posts.show', $post) }}" class="btn btn-outline-primary me-2">
                    <i class="fas fa-eye me-2"></i>View Post
                </a>
                <a href="{{ route('posts.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Posts
                </a>
            </div>
        </div>

        <div class="card shadow card-form" data-aos="fade-up" data-aos-delay="200">
            <div class="card-header bg-success text-white">
                <div class="d-flex align-items-center">
                    <i class="fas fa-edit me-2"></i>
                    <h5 class="mb-0">Edit Post #{{ $post->id }}</h5>
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

                <form action="{{ route('posts.update', $post) }}" method="POST" id="editPostForm">
                    @csrf
                    @method('PUT')
                    <div class="mb-4 position-relative form-animate-in edit-highlight" style="animation-delay: 100ms;">
                        <label for="title" class="form-label">
                            <i class="fas fa-heading me-1 text-success"></i>Title
                        </label>
                        <input
                            type="text"
                            class="form-control form-control-lg @error('title') is-invalid @enderror"
                            id="title"
                            name="title"
                            value="{{ old('title', $post->title) }}"
                            required
                            autofocus
                        >
                        <i class="fas fa-pen input-icon"></i>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 form-animate-in edit-highlight" style="animation-delay: 200ms;">
                        <label for="content" class="form-label">
                            <i class="fas fa-align-left me-1 text-success"></i>Content
                        </label>
                        <textarea
                            class="form-control @error('content') is-invalid @enderror"
                            id="content"
                            name="content"
                            rows="8"
                            required
                        >{{ old('content', $post->content) }}</textarea>
                        @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4 position-relative form-animate-in edit-highlight" style="animation-delay: 300ms;">
                        <label for="posted_by" class="form-label">
                            <i class="fas fa-user me-1 text-success"></i>Author
                        </label>
                        <input
                            type="text"
                            class="form-control @error('posted_by') is-invalid @enderror"
                            id="posted_by"
                            name="posted_by"
                            value="{{ old('posted_by', $post->posted_by) }}"
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
                        <button type="submit" class="btn btn-success btn-submit" id="submitBtn">
                            <i class="fas fa-save me-2"></i>Update Post
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card mt-4 shadow-sm border-0 bg-light" data-aos="fade-up" data-aos-delay="400">
            <div class="card-body">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-info-circle fa-2x text-info"></i>
                    </div>
                    <div>
                        <h5 class="card-title">Post Information</h5>
                        <p class="mb-1"><strong>Created:</strong> {{ $post->created_at->format('F j, Y, g:i a') }}</p>
                        <p class="mb-0"><strong>Last Updated:</strong> {{ $post->updated_at->format('F j, Y, g:i a') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add loading animation to submit button
        const form = document.getElementById('editPostForm');
        const submitBtn = document.getElementById('submitBtn');

        form.addEventListener('submit', function() {
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Updating...';
            submitBtn.disabled = true;
        });

        // Highlight changes
        const originalValues = {
            title: "{{ $post->title }}",
            content: "{{ $post->content }}",
            posted_by: "{{ $post->posted_by }}"
        };

        const inputs = {
            title: document.getElementById('title'),
            content: document.getElementById('content'),
            posted_by: document.getElementById('posted_by')
        };

        // Check for changes and highlight
        for (const field in inputs) {
            if (inputs[field]) {
                inputs[field].addEventListener('input', function() {
                    if (this.value !== originalValues[field]) {
                        this.classList.add('border-success');
                        this.style.backgroundColor = 'rgba(16, 185, 129, 0.05)';
                    } else {
                        this.classList.remove('border-success');
                        this.style.backgroundColor = '';
                    }
                });
            }
        }

        // Auto-resize textarea
        const textarea = document.getElementById('content');
        if (textarea) {
            // Set initial height
            textarea.style.height = (textarea.scrollHeight) + 'px';

            textarea.addEventListener('input', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        }
    });
</script>
@endsection
