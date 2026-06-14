@extends('layouts.landing')

@section('title', 'Our Doctors')

@section('content')
<div class="container py-5">
    
    {{-- Header Section --}}
    <div class="text-center mb-5">
        <span class="badge bg-primary bg-opacity-10 text-primary mb-2 px-3 py-2 rounded-pill fw-semibold">
            <i class="bi bi-people-fill me-1"></i> Medical Specialists
        </span>
        <h2 class="fw-bold display-5 text-dark mt-2">{{ $clinicSetting->doctors_title ?? 'Meet Our Specialist Oncologists' }}</h2>
        <p class="text-muted lead mx-auto mt-3" style="max-width: 650px;">
            {{ $clinicSetting->doctors_description ?? 'Highly qualified and experienced consultants dedicated to your care.' }}
        </p>
    </div>

    {{-- Category Sections --}}
    @forelse($categories as $category)
        <div class="mb-5 category-section">
            <div class="d-flex align-items-center mb-4 pb-2 border-bottom">
                @if($category->image_path)
                    <img src="{{ asset('storage/' . $category->image_path) }}" class="rounded shadow-sm me-3 border" style="width: 48px; height: 48px; object-fit: cover;" alt="Category">
                @else
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 48px; height: 48px;">
                        <i class="bi {{ $category->icon ?? 'bi-heart-pulse-fill' }} fs-4"></i>
                    </div>
                @endif
                <div>
                    <h3 class="fw-bold text-dark mb-0">{{ Str::contains(strtolower($category->name), 'specialist') ? $category->name : $category->name . ' Specialists' }}</h3>
                    <p class="text-muted small mb-0">{{ $category->description ?? 'Highly qualified experts specialized in ' . strtolower($category->name) . ' treatments & consultations' }}</p>
                </div>
            </div>
            
            <div class="row g-4 justify-content-start">
                @foreach($category->doctors as $doctor)
                    <div class="col-sm-6 col-md-4 col-lg-3">
                        <div class="card border-0 shadow-sm h-100 p-3 hover-lift doctor-card bg-white" style="border-radius: 20px; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);">
                            <div class="card-body d-flex flex-column h-100">
                                 <div class="text-center mb-3">
                                    @if($doctor->user->profile_photo)
                                         <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="rounded-circle border mb-3 shadow-sm" style="width: 90px; height: 90px; object-fit: cover;" alt="Doctor">
                                    @else
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 90px; height: 90px;">
                                            <i class="bi bi-person-fill fs-2"></i>
                                        </div>
                                    @endif
                                    <h5 class="fw-bold mb-1 text-dark" style="font-size: 1.05rem;">{{ $doctor->user->name }}</h5>
                                    <span class="badge bg-primary bg-opacity-10 text-primary mb-2.5 px-2.5 py-1.5 rounded-pill" style="font-size: 0.75rem;">
                                        {{ $category->name }}
                                    </span>
                                    <p class="text-muted small mb-0 px-2" style="font-size: 0.8rem; line-height: 1.4; min-height: 48px;">
                                        {{ Str::limit($doctor->bio, 90) }}
                                    </p>
                                </div>
                                
                                <div class="border-top pt-3 mt-auto">
                                    <div class="d-flex align-items-center gap-2 mb-2 justify-content-center">
                                         <i class="bi bi-clock-fill text-warning" style="font-size: 0.85rem;"></i>
                                         <small class="fw-bold text-uppercase text-secondary" style="font-size: 0.65rem; letter-spacing: 0.05em;">Schedule Availability</small>
                                    </div>
                                    @if($doctor->schedules->count() > 0)
                                        <ul class="list-unstyled mb-0 small text-center" style="font-size: 0.78rem;">
                                            @foreach($doctor->schedules->take(2) as $schedule)
                                                <li class="text-muted mb-1">
                                                    <span class="fw-semibold text-dark">{{ $schedule->day }}:</span> 
                                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                                </li>
                                            @endforeach
                                            @if($doctor->schedules->count() > 2)
                                                <li class="text-primary small mt-1 font-monospace" style="font-size: 0.7rem;">+ more slots available</li>
                                            @endif
                                        </ul>
                                    @else
                                        <p class="text-center text-muted small fst-italic mb-0" style="font-size: 0.75rem;">Contact for appointments.</p>
                                    @endif
                                </div>

                                <div class="mt-3.5 d-grid">
                                    @auth
                                        @if(auth()->user()->isPatient())
                                             <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn btn-outline-primary btn-sm rounded-pill py-2 fw-semibold" style="font-size: 0.8rem;">Book Appointment</a>
                                        @else
                                             <button class="btn btn-outline-secondary btn-sm rounded-pill py-2 fw-semibold" style="font-size: 0.8rem;" disabled>Patient Only</button>
                                        @endif
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm rounded-pill py-2 fw-semibold" style="font-size: 0.8rem;">Login to Book</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @empty
        <div class="col-12 text-center py-5">
            <div class="p-4 bg-light d-inline-block rounded-circle mb-3">
                <i class="bi bi-people text-muted display-4"></i>
            </div>
            <h4 class="mt-2 text-muted fw-bold">No Doctors Available</h4>
            <p class="text-muted small">We are updating our schedule. Please check back shortly.</p>
        </div>
    @endforelse
</div>

<style>
    .doctor-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
    }
    .category-section {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endsection
