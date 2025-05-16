@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="mb-0">Edit Profile</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('users_save', $user->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-4 text-center">
                                <div class="profile-image-container mb-4" id="profileImageContainer">
                                    @if($user->profile_image)
                                        <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->name }}" class="profile-image" id="profileImagePreview">
                                    @else
                                        <img src="{{ asset('images/default-profile.png') }}" alt="{{ $user->name }}" class="profile-image" id="profileImagePreview">
                                    @endif
                                    <div class="profile-image-edit" id="profileImageEdit" title="Change profile picture">
                                        <i class="fas fa-camera"></i>
                                    </div>
                                </div>

                                <div class="mb-4 text-center">
                                    <input type="file" class="d-none @error('profile_image') is-invalid @enderror"
                                           id="profile_image" name="profile_image" accept="image/*">
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="changeProfileImageBtn">
                                        <i class="fas fa-upload me-1"></i> Change Profile Picture
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm d-none" id="removeProfileImageBtn">
                                        <i class="fas fa-times me-1"></i> Remove
                                    </button>
                                    <small class="d-block text-muted mt-2">Click to upload a new profile image (JPG, PNG, GIF)</small>
                                    @error('profile_image')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <script>
                                document.addEventListener('DOMContentLoaded', function() {
                                    const profileImageInput = document.getElementById('profile_image');
                                    const profileImagePreview = document.getElementById('profileImagePreview');
                                    const profileImageEdit = document.getElementById('profileImageEdit');
                                    const changeProfileImageBtn = document.getElementById('changeProfileImageBtn');
                                    const removeProfileImageBtn = document.getElementById('removeProfileImageBtn');

                                    // Open file dialog when edit button or change button is clicked
                                    profileImageEdit.addEventListener('click', function() {
                                        profileImageInput.click();
                                    });

                                    changeProfileImageBtn.addEventListener('click', function() {
                                        profileImageInput.click();
                                    });

                                    // Handle image preview when file is selected
                                    profileImageInput.addEventListener('change', function() {
                                        if (this.files && this.files[0]) {
                                            const reader = new FileReader();

                                            reader.onload = function(e) {
                                                profileImagePreview.src = e.target.result;
                                                removeProfileImageBtn.classList.remove('d-none');
                                            };

                                            reader.readAsDataURL(this.files[0]);
                                        }
                                    });

                                    // Handle remove image button
                                    removeProfileImageBtn.addEventListener('click', function() {
                                        profileImageInput.value = '';
                                        profileImagePreview.src = "{{ asset('images/default-profile.png') }}";
                                        removeProfileImageBtn.classList.add('d-none');
                                    });
                                });
                                </script>
                            </div>
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        @if(auth()->user()->hasRole('admin'))
                            <div class="mb-3">
                                <label for="roles" class="form-label">Roles</label>
                                <select multiple class="form-select @error('roles') is-invalid @enderror"
                                        id="roles" name="roles[]">
                                    @foreach($roles as $role)
                                        <option value="{{ $role->name }}"
                                                {{ $role->taken ? 'selected' : '' }}>
                                            {{ ucfirst($role->name) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('roles')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="permissions" class="form-label">Direct Permissions</label>
                                <select multiple class="form-select @error('permissions') is-invalid @enderror"
                                        id="permissions" name="permissions[]">
                                    @foreach($permissions as $permission)
                                        <option value="{{ $permission->name }}"
                                                {{ $permission->taken ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('-', ' ', $permission->name)) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('permissions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('users.profile', $user->id) }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
