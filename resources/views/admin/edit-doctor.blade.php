@extends('layouts.app')

@section('title', 'Edit Doctor')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.doctors') }}">Doctors</a></li>
                    <li class="breadcrumb-item active">Edit Doctor</li>
                </ol>
            </nav>
            <h2 class="fw-bold"><i class="bi bi-pencil-square text-primary me-2"></i>Edit Doctor</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Doctor Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.doctors.update', $doctor->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control"
                                value="{{ old('name', $doctor->user->name) }}" required>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control"
                                value="{{ old('email', $doctor->user->email) }}" required>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Mobile Number</label>
                                <input type="text" name="mobile_number" class="form-control"
                                    value="{{ old('mobile_number', $doctor->user->mobile_number) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Category</label>
                                <select name="category_id" class="form-select" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $doctor->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Qualification</label>
                                <input type="text" name="qualification" class="form-control"
                                    value="{{ old('qualification', $doctor->qualification) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Experience (Years)</label>
                                <input type="number" name="experience_years" class="form-control"
                                    value="{{ old('experience_years', $doctor->experience_years) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Consultation Fee</label>
                                <input type="number" name="fees" class="form-control"
                                    value="{{ old('fees', $doctor->fees) }}" min="0">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Bio / Description</label>
                            <textarea name="bio" class="form-control" rows="3">{{ old('bio', $doctor->bio) }}</textarea>
                            @error('bio') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Update Doctor
                            </button>
                            <a href="{{ route('admin.doctors') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-info text-white py-3">
                    <h6 class="mb-0 fw-bold">Doctor Stats</h6>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>Member Since:</strong> {{ $doctor->user->created_at->format('M j, Y') }}</p>
                    <p class="mb-2"><strong>Schedules:</strong> {{ $doctor->schedules->count() }}</p>
                    <p class="mb-0"><strong>Appointments:</strong> {{ $doctor->appointments->count() }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection