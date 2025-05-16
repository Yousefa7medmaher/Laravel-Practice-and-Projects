@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Create New Product</h2>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Product Code</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                           id="code" name="code" value="{{ old('code') }}" required>
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="model" class="form-label">Model</label>
                                    <input type="text" class="form-control @error('model') is-invalid @enderror"
                                           id="model" name="model" value="{{ old('model') }}" required>
                                    @error('model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description" rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" class="form-control @error('price') is-invalid @enderror"
                                               id="price" name="price" value="{{ old('price') }}" step="0.01" min="0" required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock_quantity" class="form-label">Stock Quantity</label>
                                    <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                           id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}" min="0" required>
                                    @error('stock_quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="image" class="form-label">Product Image</label>

                            <div class="image-upload-container" id="imageUploadContainer">
                                <div class="image-upload-placeholder" id="imageUploadPlaceholder">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <p>Drag & drop your image here or click to browse</p>
                                    <small class="text-muted">Supports: JPG, PNG, GIF (Max: 2MB)</small>
                                </div>
                                <img src="#" alt="Preview" class="image-preview d-none" id="imagePreview">
                                <input type="file" class="image-upload-input @error('image') is-invalid @enderror"
                                       id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif">
                            </div>

                            <div class="text-center mt-2 mb-3 d-none" id="imageActions">
                                <button type="button" class="btn btn-sm btn-outline-danger" id="removeImageBtn">
                                    <i class="fas fa-trash-alt me-1"></i> Remove Image
                                </button>
                            </div>

                            @error('image')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror

                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const imageInput = document.getElementById('image');
                                const imagePreview = document.getElementById('imagePreview');
                                const imageUploadContainer = document.getElementById('imageUploadContainer');
                                const imageUploadPlaceholder = document.getElementById('imageUploadPlaceholder');
                                const imageActions = document.getElementById('imageActions');
                                const removeImageBtn = document.getElementById('removeImageBtn');

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

                                // Add a hidden input to track form submission
                                const form = imageInput.closest('form');
                                form.addEventListener('submit', function() {
                                    // Add a hidden field to indicate form was submitted properly
                                    const hiddenField = document.createElement('input');
                                    hiddenField.type = 'hidden';
                                    hiddenField.name = 'form_submitted';
                                    hiddenField.value = '1';
                                    form.appendChild(hiddenField);

                                    // Log to console for debugging
                                    console.log('Form submitted with image:', imageInput.files && imageInput.files[0] ? imageInput.files[0].name : 'none');
                                });
                            });
                            </script>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Create Product</button>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection