@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold"><i class="bi bi-person-badge text-primary me-2"></i>Administrator Profile</h2>
            <p class="text-muted">Manage your personal information and security settings.</p>
        </div>
    </div>

    <div class="row">
        <!-- Profile Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center py-5">
                    <div class="mb-4 position-relative d-inline-block">
                        @if($admin->profile_photo)
                            <img src="{{ asset('storage/' . $admin->profile_photo) }}" class="rounded-circle shadow-sm"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                style="width: 150px; height: 150px;">
                                <i class="bi bi-person-fill display-1"></i>
                            </div>
                        @endif
                    </div>
                    <h4 class="fw-bold mb-1">{{ $admin->name }}</h4>
                    <p class="text-muted mb-3">{{ $admin->email }}</p>
                    <span class="badge bg-dark px-3 py-2">Administrator</span>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Profile Details</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="row g-3">
                            <!-- Personal Info -->
                            <div class="col-12">
                                <h6 class="text-uppercase text-muted fw-bold small mb-3">Personal Information</h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $admin->name }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ $admin->email }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" name="profile_photo" class="form-control" accept="image/*">
                                <small class="text-muted">Recommended size: 500x500px. Max: 2MB.</small>
                            </div>

                            <div class="col-12 my-4">
                                <hr class="text-muted">
                            </div>

                            <!-- Security -->
                            <div class="col-12">
                                <h6 class="text-uppercase text-muted fw-bold small mb-3">Security (Optional)</h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">New Password</label>
                                <input type="password" name="password" class="form-control"
                                    placeholder="Leave empty to keep current">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="form-control"
                                    placeholder="Confirm new password">
                            </div>
                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection