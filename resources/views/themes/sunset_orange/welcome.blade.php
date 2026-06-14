@extends('themes.sunset_orange.layout')

@section('title', 'Welcome')

@section('content')
<style>
    .sunset-hero { background: linear-gradient(135deg, #fff7ed 0%, white 100%); padding: 5rem 0 8rem; position: relative; overflow: hidden; }
    .sunset-blob { position: absolute; width: 600px; height: 600px; background: linear-gradient(135deg, var(--sunset-primary), var(--sunset-secondary)); border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; opacity: 0.05; right: -100px; top: -100px; z-index: 0; animation: morph 15s ease-in-out infinite; }
    @keyframes morph { 0% { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; } 34% { border-radius: 70% 30% 50% 50% / 30% 30% 70% 70%; } 67% { border-radius: 100% 60% 60% 100% / 100% 100% 60% 60%; } 100% { border-radius: 40% 60% 70% 30% / 40% 50% 60% 50%; } }
</style>

<div class="sunset-hero">
    <div class="sunset-blob"></div>
    <div class="container position-relative z-1 text-center">
        <span class="badge bg-white text-primary px-4 py-2 rounded-pill shadow-sm mb-4 fw-bold tracking-wide">
            <i class="bi bi-star-fill text-warning me-2"></i> {{ $clinicSetting->hero_badge ?? 'Premium Oncology Care' }}
        </span>
        <h1 class="display-3 fw-bold mb-4" style="color: var(--sunset-dark);">
            {{ $clinicSetting->hero_title ?? 'Multan Cancer' }} <br>
            <span class="text-primary">{{ $clinicSetting->hero_subtitle ?? 'Clinic' }}</span>
        </h1>
        <p class="lead text-muted mx-auto mb-5" style="max-width: 650px;">
            {{ $clinicSetting->hero_description ?? 'Specialized consultant-based oncology services.' }}
        </p>
        <div class="d-flex justify-content-center gap-3">
            @auth
                <a href="{{ route('patient.dashboard') }}" class="btn-sunset text-decoration-none btn-lg px-5">My Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-sunset text-decoration-none btn-lg px-5">Book Appointment</a>
                <a href="{{ route('public.about') }}" class="btn btn-outline-primary btn-lg px-5 bg-white">Learn More</a>
            @endauth
        </div>
    </div>
</div>

<div class="container" style="margin-top: -4rem; position: relative; z-index: 2;">
    <div class="sunset-card p-5">
        <div class="row g-4 text-center">
            @php
                $features = is_string($clinicSetting->features) ? json_decode($clinicSetting->features, true) : $clinicSetting->features;
                if(empty($features)) $features = [['icon'=>'bi-shield','title'=>'Secure'], ['icon'=>'bi-clock','title'=>'Fast'], ['icon'=>'bi-heart','title'=>'Care']];
            @endphp
            @foreach($features as $feat)
            <div class="col-md-4 {{ !$loop->last ? 'border-end border-light' : '' }}">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle bg-white text-primary shadow-sm mb-3" style="width: 70px; height: 70px;">
                    <i class="bi {{ $feat['icon'] ?? 'bi-check' }} fs-2"></i>
                </div>
                <h4 class="fw-bold" style="color: var(--sunset-dark);">{{ $feat['title'] ?? '' }}</h4>
                <p class="text-muted mb-0 px-3">{{ $feat['description'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="py-5 my-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="display-6 fw-bold" style="color: var(--sunset-dark);">Our Specialists</h2>
            <p class="text-muted">Compassionate experts dedicated to your health.</p>
        </div>
        <div class="row g-4">
            @forelse($doctors->take(3) as $doctor)
                <div class="col-md-4">
                    <div class="sunset-card p-4 h-100 text-center">
                        @if($doctor->user->profile_photo)
                            <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="rounded-circle shadow-sm mb-4" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 120px; height: 120px;">
                                <i class="bi bi-person fs-1 text-muted"></i>
                            </div>
                        @endif
                        <h4 class="fw-bold mb-1" style="color: var(--sunset-dark);">{{ $doctor->user->name }}</h4>
                        <span class="badge bg-white text-primary shadow-sm rounded-pill px-3 py-2 mb-3">{{ $category->name ?? 'Specialist' }}</span>
                        <p class="text-muted small mb-4">{{ Str::limit($doctor->qualification, 80) }}</p>
                        @auth
                            <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn btn-outline-primary w-100">Schedule</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Login to Schedule</a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted"><p>No doctors available.</p></div>
            @endforelse
        </div>
    </div>
</div>
@endsection
