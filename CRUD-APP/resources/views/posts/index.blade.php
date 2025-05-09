@extends('layouts.app')

@section('title', 'All Posts - Joo Blog')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <h1 class="text-center mb-4" data-aos="fade-down">Blog Posts</h1>
        <div class="d-flex justify-content-center mb-4" data-aos="fade-up" data-aos-delay="200">
            <a href="{{ route('posts.create') }}" class="btn btn-success">
                <i class="fas fa-plus-circle me-2"></i>Create Post
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card shadow" data-aos="fade-up" data-aos-delay="300">
            <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <h5 class="mb-0">All Posts</h5>
                <div class="dropdown">
                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-sort me-1"></i>Sort
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="sortDropdown">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-sort-alpha-down me-2"></i>Title (A-Z)</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-sort-alpha-up me-2"></i>Title (Z-A)</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-calendar-alt me-2"></i>Newest First</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fas fa-calendar me-2"></i>Oldest First</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr class="table-light">
                                <th scope="col" class="fw-bold"><i class="fas fa-hashtag me-1"></i>ID</th>
                                <th scope="col" class="fw-bold"><i class="fas fa-heading me-1"></i>Title</th>
                                <th scope="col" class="fw-bold"><i class="fas fa-user me-1"></i>Posted By</th>
                                <th scope="col" class="fw-bold"><i class="fas fa-calendar-day me-1"></i>Created At</th>
                                <th scope="col" class="fw-bold text-center"><i class="fas fa-cogs me-1"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($posts as $post)
                            <tr data-aos="fade-right" data-aos-delay="{{ 100 + $loop->index * 50 }}">
                                <th scope="row">{{ $post->id }}</th>
                                <td>
                                    <span class="fw-medium">{{ $post->title }}</span>
                                </td>
                                <td>{{ $post->posted_by }}</td>
                                <td>{{ $post->created_at->format('M d, Y') }}</td>
                                <td class="text-center action-buttons">
                                    <a href="{{ route('posts.show', $post) }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-eye"></i>
                                        <span class="d-none d-md-inline ms-1">View</span>
                                    </a>
                                    <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-edit"></i>
                                        <span class="d-none d-md-inline ms-1">Edit</span>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-post-id="{{ $post->id }}">
                                        <i class="fas fa-trash-alt"></i>
                                        <span class="d-none d-md-inline ms-1">Delete</span>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-inbox fa-3x mb-3 text-muted"></i>
                                        <h5>No posts found</h5>
                                        <p class="text-muted">Start creating your first post now!</p>
                                        <a href="{{ route('posts.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus-circle me-2"></i>Create Post
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Action Floating Button -->
<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
    <div class="dropdown">
        <button class="btn btn-primary rounded-circle shadow" style="width: 60px; height: 60px;" type="button" id="quickActionButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fas fa-plus fa-lg"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="quickActionButton">
            <li>
                <a class="dropdown-item" href="{{ route('posts.create') }}">
                    <i class="fas fa-plus-circle me-2 text-success"></i>New Post
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-filter me-2 text-primary"></i>Filter Posts
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-search me-2 text-info"></i>Search
                </a>
            </li>
            <li><hr class="dropdown-divider"></li>
            <li>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-question-circle me-2 text-secondary"></i>Help
                </a>
            </li>
        </ul>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Show toast notification when returning from another page with success message
    document.addEventListener('DOMContentLoaded', function() {
        // Check if there's a success message in the session
        const successMessage = document.querySelector('.alert-success');
        if (successMessage) {
            // Create a toast notification
            const message = successMessage.textContent.trim();
            setTimeout(() => {
                showToast(message);
            }, 500);
        }

        // Add hover animation to table rows
        const tableRows = document.querySelectorAll('tbody tr');
        tableRows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.transition = 'all 0.3s ease';
                this.style.transform = 'translateX(5px)';
            });

            row.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0)';
            });
        });
    });
</script>
@endsection