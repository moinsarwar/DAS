@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold"><i class="bi bi-person-gear text-primary me-2"></i>My Professional Profile</h2>
            <p class="text-muted">Manage your medical profile, qualifications, and patient-facing information.</p>
        </div>
    </div>

    <div class="row">
        <!-- Profile Summary Card -->
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
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
                    <h4 class="fw-bold mb-1">Dr. {{ $user->name }}</h4>
                    <p class="text-muted mb-2">{{ $doctor->category->name ?? 'General Practitioner' }}</p>
                    <span class="badge badge-approved px-3 py-2 mb-3">Verified Doctor</span>

                    <div class="d-flex justify-content-center gap-3 text-start mt-3">
                        <div class="text-center px-3 border-end">
                            <h5 class="fw-bold mb-0">{{ $doctor->experience_years ?? 0 }}</h5>
                            <small class="text-muted">Years Exp.</small>
                        </div>
                        <div class="text-center px-3">
                            <h5 class="fw-bold mb-0">
                                {{ $doctor->appointments()->distinct('patient_id')->count('patient_id') }}
                            </h5>
                            <small class="text-muted">Patients</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-clipboard-pulse me-2"></i>Edit Profile Details</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('doctor.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf @method('PUT')

                        <div class="row g-3">
                            <!-- Personal Info -->
                            <div class="col-12">
                                <h6 class="text-uppercase text-muted fw-bold small mb-3">Personal Information</h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required>
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

                            <div class="col-12 my-3">
                                <hr class="text-muted">
                            </div>

                            <!-- Professional Info -->
                            <div class="col-12">
                                <h6 class="text-uppercase text-muted fw-bold small mb-3">Professional Qualification</h6>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Specialization</label>
                                <select name="category_id" class="form-select" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $doctor->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Experience (Years)</label>
                                <input type="number" name="experience_years" class="form-control"
                                    value="{{ old('experience_years', $doctor->experience_years) }}" min="0">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Consultation Fee (Rs)</label>
                                <input type="number" name="fees" class="form-control"
                                    value="{{ old('fees', $doctor->fees) }}" min="0">
                            </div>

                            <div class="col-12">

                                <div class="col-12">
                                    <label class="form-label">Qualification / Degree</label>
                                    <input type="text" name="qualification" class="form-control"
                                        value="{{ old('qualification', $doctor->qualification) }}"
                                        placeholder="e.g. MBBS, FCPS, MD">
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Professional Bio</label>
                                    <textarea name="bio" class="form-control" rows="4"
                                        placeholder="Describe your expertise...">{{ old('bio', $doctor->bio) }}</textarea>
                                </div>

                                <div class="col-12 my-3">
                                    <hr class="text-muted">
                                </div>

                                <!-- Security -->
                                <div class="col-12">
                                    <h6 class="text-uppercase text-muted fw-bold small mb-3">Security (Optional)</h6>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">New Password</label>
                                    <input type="password" name="password" class="form-control"
                                        placeholder="Leave blank to keep current">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control">
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
        </div>
    </div>
@endsection