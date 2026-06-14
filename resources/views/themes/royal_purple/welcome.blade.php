@extends('themes.royal_purple.layout')

@section('title', 'Welcome')

@section('content')
<style>
    .royal-hero { background: var(--royal-dark); color: white; padding: 6rem 0; position: relative; border-bottom: 8px solid var(--royal-gold); }
    .royal-hero::before { content: ''; position: absolute; inset: 0; background: url('data:image/svg+xml;utf8,<svg opacity="0.05" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path fill="%23d4af37" d="M10 10h80v80H10z"/></svg>') repeat; opacity: 0.1; z-index: 0; }
</style>

<div class="royal-hero text-center">
    <div class="container position-relative z-1">
        <span class="text-uppercase fw-bold mb-3 d-block letter-spacing-1" style="color: var(--royal-gold);">
            <i class="bi bi-award-fill me-2"></i> {{ $clinicSetting->hero_badge ?? 'Premium Care' }}
        </span>
        <h1 class="display-3 fw-bold mb-4">
            {{ $clinicSetting->hero_title ?? 'Multan Cancer' }} <br>
            <span style="color: var(--royal-gold);">{{ $clinicSetting->hero_subtitle ?? 'Clinic' }}</span>
        </h1>
        <p class="lead mx-auto mb-5 opacity-75" style="max-width: 700px;">
            {{ $clinicSetting->hero_description ?? 'Specialized consultant-based oncology services.' }}
        </p>
        <div class="d-flex justify-content-center gap-3">
            @auth
                <a href="{{ route('patient.dashboard') }}" class="btn-royal text-decoration-none btn-lg">Patient Portal</a>
            @else
                <a href="{{ route('login') }}" class="btn-royal text-decoration-none btn-lg">Book Consultation</a>
                <a href="{{ route('public.doctors') }}" class="btn btn-outline-light btn-lg text-uppercase fw-bold rounded-1 border-2">Specialists</a>
            @endauth
        </div>
    </div>
</div>

<div class="container py-5 my-5">
    <div class="row g-4">
        @php
            $features = is_string($clinicSetting->features) ? json_decode($clinicSetting->features, true) : $clinicSetting->features;
            if(empty($features)) $features = [['icon'=>'bi-shield','title'=>'Secure'], ['icon'=>'bi-clock','title'=>'Fast'], ['icon'=>'bi-heart','title'=>'Care']];
        @endphp
        @foreach($features as $feat)
        <div class="col-lg-4">
            <div class="royal-card p-4 text-center h-100">
                <i class="bi {{ $feat['icon'] ?? 'bi-check' }} display-4 mb-3 d-block" style="color: var(--royal-gold);"></i>
                <h4 class="fw-bold mb-2" style="color: var(--royal-dark);">{{ $feat['title'] ?? '' }}</h4>
                <p class="text-muted mb-0">{{ $feat['description'] ?? '' }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="bg-white py-5 border-top border-bottom">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h6 class="text-uppercase fw-bold letter-spacing-1" style="color: var(--royal-gold);">Consultants</h6>
            <h2 class="display-5 fw-bold" style="color: var(--royal-dark);">Our Specialists</h2>
        </div>
        <div class="row g-4">
            @forelse($doctors->take(3) as $doctor)
                <div class="col-lg-4">
                    <div class="royal-card p-0 text-center h-100 overflow-hidden">
                        <div class="bg-light p-4 border-bottom">
                            @if($doctor->user->profile_photo)
                                <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="rounded-circle shadow" style="width: 140px; height: 140px; object-fit: cover; border: 4px solid var(--royal-gold);">
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto" style="width: 140px; height: 140px; background: white; border: 4px solid var(--royal-gold);">
                                    <i class="bi bi-person display-3" style="color: var(--royal-dark);"></i>
                                </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h4 class="fw-bold mb-1" style="color: var(--royal-dark);">{{ $doctor->user->name }}</h4>
                            <p class="text-muted text-uppercase small fw-bold mb-3 letter-spacing-1">{{ $category->name ?? 'Specialist' }}</p>
                            <p class="text-muted small mb-4">{{ Str::limit($doctor->qualification, 80) }}</p>
                            @auth
                                <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn btn-outline-primary w-100 text-uppercase fw-bold rounded-1 border-2">Book Slot</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-dark w-100 text-uppercase fw-bold rounded-1 border-2">Login</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center text-muted"><p>No doctors listed.</p></div>
            @endforelse
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('public.doctors') }}" class="btn-royal text-decoration-none">View Full Directory</a>
        </div>
    </div>
</div>
@endsection
