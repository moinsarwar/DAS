@extends('themes.modern_dark.layout')

@section('title', 'Our Doctors')

@section('content')
<div class="py-5 bg-secondary bg-opacity-10 border-bottom border-secondary">
    <div class="container-fluid px-4 px-lg-5">
        <h1 class="display-4 fw-bold text-white mb-3">{{ $settings->doctors_title ?? 'Meet Our Specialists' }}</h1>
        <p class="lead text-muted mb-0" style="max-width: 600px;">{{ $settings->doctors_description ?? 'Browse our directory of highly qualified oncology consultants.' }}</p>
    </div>
</div>

<div class="py-5">
    <div class="container-fluid px-4 px-lg-5">
        @forelse($categories as $category)
            <div class="mb-5">
                <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom border-secondary">
                    @if($category->icon)
                        <i class="{{ $category->icon }} fs-2 text-primary"></i>
                    @elseif($category->image_path)
                        <img src="{{ asset('storage/' . $category->image_path) }}" alt="icon" style="width: 40px; height: 40px; object-fit: contain;">
                    @else
                        <i class="bi bi-person-badge fs-2 text-primary"></i>
                    @endif
                    <div>
                        <h2 class="fw-bold text-white mb-0">{{ $category->name }}</h2>
                        @if($category->description)
                            <small class="text-muted">{{ $category->description }}</small>
                        @endif
                    </div>
                </div>

                <div class="row g-4">
                    @forelse($category->doctors as $doctor)
                        <div class="col-xl-3 col-lg-4 col-md-6">
                            <div class="modern-card p-4 h-100 glowing-btn">
                                <div class="text-center mb-4">
                                    @if($doctor->user->profile_photo)
                                        <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="rounded-circle border border-primary" style="width: 100px; height: 100px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px;">
                                            <i class="bi bi-person fs-1 text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <h5 class="text-white fw-bold text-center mb-1">{{ $doctor->user->name }}</h5>
                                <p class="text-primary text-center small fw-bold mb-3">{{ $category->name }}</p>
                                
                                <p class="text-muted small mb-4 text-center">{{ Str::limit($doctor->qualification, 60) }}</p>
                                
                                <div class="d-grid">
                                    @auth
                                        <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn btn-outline-primary rounded-3">Book Slot</a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-light rounded-3">Login to Book</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12"><p class="text-muted">No doctors in this category.</p></div>
                    @endforelse
                </div>
            </div>
        @empty
            <div class="modern-card p-5 text-center">
                <h4 class="text-white mb-0">No categories found.</h4>
            </div>
        @endforelse
    </div>
</div>
@endsection
