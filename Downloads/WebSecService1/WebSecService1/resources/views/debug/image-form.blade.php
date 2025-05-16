@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Debug Image Upload</h2>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        This is a debug page to test image uploads. It will help diagnose any issues with the image upload functionality.
                    </div>

                    <form action="{{ url('/debug-image-upload') }}" method="POST" enctype="multipart/form-data" id="debugForm">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="test_image" class="form-label">Test Image</label>
                            
                            <div class="image-upload-container" id="imageUploadContainer">
                                <div class="image-upload-placeholder" id="imageUploadPlaceholder">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Drag & drop your image here or click to browse</p>
                                    <small class="text-muted">Supports: JPG, PNG, GIF (Max: 2MB)</small>
                                </div>
                                <img src="#" alt="Preview" class="image-preview d-none" id="imagePreview">
                                <input type="file" class="image-upload-input" id="test_image" name="test_image" accept="image/jpeg,image/png,image/jpg,image/gif">
                            </div>

                            <div class="text-center mt-2 mb-3 d-none" id="imageActions">
                                <button type="button" class="btn btn-sm btn-outline-danger" id="removeImageBtn">
                                    <i class="fas fa-trash-alt me-1"></i> Remove Image
                                </button>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Upload Test Image</button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Back to Products</a>
                        </div>
                    </form>

                    <div class="mt-4">
                        <h3>Results</h3>
                        <div id="results" class="border p-3 bg-light">
                            <p class="text-muted">Upload an image to see results here.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const imageInput = document.getElementById('test_image');
    const imagePreview = document.getElementById('imagePreview');
    const imageUploadContainer = document.getElementById('imageUploadContainer');
    const imageUploadPlaceholder = document.getElementById('imageUploadPlaceholder');
    const imageActions = document.getElementById('imageActions');
    const removeImageBtn = document.getElementById('removeImageBtn');
    const form = document.getElementById('debugForm');
    const resultsDiv = document.getElementById('results');

    // Handle drag and drop
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        imageUploadContainer.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        imageUploadContainer.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        imageUploadContainer.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        imageUploadContainer.classList.add('drag-over');
    }

    function unhighlight() {
        imageUploadContainer.classList.remove('drag-over');
    }

    // Handle drop
    imageUploadContainer.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;

        if (files.length) {
            // Validate file type
            const file = files[0];
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            
            if (!validTypes.includes(file.type)) {
                alert('Invalid file type. Please upload a JPG, PNG, or GIF image.');
                return;
            }
            
            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File is too large. Maximum size is 2MB.');
                return;
            }
            
            imageInput.files = files;
            updateImagePreview();
        }
    }

    // Handle file input change
    imageInput.addEventListener('change', function() {
        if (imageInput.files && imageInput.files[0]) {
            // Validate file type
            const file = imageInput.files[0];
            const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            
            if (!validTypes.includes(file.type)) {
                alert('Invalid file type. Please upload a JPG, PNG, or GIF image.');
                imageInput.value = '';
                return;
            }
            
            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('File is too large. Maximum size is 2MB.');
                imageInput.value = '';
                return;
            }
            
            updateImagePreview();
        }
    });

    function updateImagePreview() {
        if (imageInput.files && imageInput.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.classList.remove('d-none');
                imageUploadPlaceholder.classList.add('d-none');
                imageActions.classList.remove('d-none');
            };

            reader.readAsDataURL(imageInput.files[0]);
        }
    }

    // Handle remove image button
    removeImageBtn.addEventListener('click', function() {
        imageInput.value = '';
        imagePreview.src = '#';
        imagePreview.classList.add('d-none');
        imageUploadPlaceholder.classList.remove('d-none');
        imageActions.classList.add('d-none');
    });

    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        
        // Show loading state
        resultsDiv.innerHTML = '<div class="text-center"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Uploading and processing image...</p></div>';
        
        fetch('/debug-image-upload', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Format the JSON response
            const formattedJson = JSON.stringify(data, null, 2);
            
            // Display the results
            resultsDiv.innerHTML = `
                <div class="mb-3">
                    <h4>Status: ${data.success ? '<span class="text-success">Success</span>' : '<span class="text-danger">Failed</span>'}</h4>
                    <p>${data.message}</p>
                </div>
                ${data.url ? `<div class="mb-3"><img src="${data.url}" alt="Uploaded Image" class="img-fluid mb-2" style="max-height: 200px;"><br><a href="${data.url}" target="_blank">${data.url}</a></div>` : ''}
                <pre class="bg-dark text-light p-3 rounded">${formattedJson}</pre>
            `;
        })
        .catch(error => {
            resultsDiv.innerHTML = `
                <div class="alert alert-danger">
                    <h4>Error</h4>
                    <p>${error.message}</p>
                </div>
            `;
        });
    });
});
</script>
@endsection
