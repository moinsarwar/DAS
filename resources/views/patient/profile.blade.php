@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold"><i class="bi bi-person-gear text-primary me-2"></i>My Profile</h2>
            <p class="text-muted">Update your personal information and credentials.</p>
        </div>
    </div>

    <div class="row">
        <!-- Profile Summary Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center py-5">
                    <div class="mb-4 position-relative d-inline-block">
                        @if($user->profile_photo)
                            <img src="{{ asset('storage/' . $user->profile_photo) }}" class="rounded-circle shadow-sm"
                                style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                style="width: 150px; height: 150px;">
                                <i class="bi bi-person-fill display-1"></i>
                            </div>
                        @endif
                    </div>
                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>
                    <span class="badge badge-approved px-3 py-2">Patient Account</span>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="row g-3">
                <div class="col-6">
                    <div class="card border-0 shadow-sm text-center py-3">
                        <i class="bi bi-calendar-check text-primary fs-3 mb-2"></i>
                        <h5 class="fw-bold mb-0">{{ $user->patientAppointments()->count() }}</h5>
                        <small class="text-muted">Appointments</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-sm text-center py-3">
                        <i class="bi bi-file-medical text-success fs-3 mb-2"></i>
                        <h5 class="fw-bold mb-0">{{ $user->prescriptions()->count() }}</h5>
                        <small class="text-muted">Prescriptions</small>
                    </div>
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
                    <form action="{{ route('patient.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="row g-3">
                            <!-- Personal Info -->
                            <div class="col-12">
                                <h6 class="text-uppercase text-muted fw-bold small mb-3">Personal Information</h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">MR Number</label>
                                <input type="text" class="form-control bg-light" value="{{ $user->mr_number }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">CNIC</label>
                                <input type="text" class="form-control bg-light" value="{{ $user->cnic }}" readonly>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email Address (Optional)</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" placeholder="Add email address">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile_number" class="form-control"
                                    value="{{ old('mobile_number', $user->mobile_number) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Profile Photo</label>
                                <input type="file" name="profile_photo" class="form-control" accept="image/*">
                            </div>


                        </div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-check-circle me-1"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2">
                        <a href="{{ route('patient.dashboard') }}" class="btn btn-outline-primary">
                            <i class="bi bi-house me-1"></i> Dashboard
                        </a>
                        <a href="{{ route('patient.history') }}" class="btn btn-outline-info">
                            <i class="bi bi-clock-history me-1"></i> Medical History
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection