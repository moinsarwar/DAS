@extends('themes.clean_minimal.layout')

@section('title', 'Welcome')

@section('content')
<div class="container py-5 mt-5">
    <div class="row align-items-center min-vh-75">
        <div class="col-lg-8">
            <span class="text-muted small text-uppercase tracking-wider mb-4 d-block">{{ $clinicSetting->hero_badge ?? 'Modern Care' }}</span>
            <h1 class="display-1 mb-5" style="font-weight: 300; letter-spacing: -2px; line-height: 1;">
                {{ $clinicSetting->hero_title ?? 'Multan Cancer' }}<br>
                <span class="text-muted">{{ $clinicSetting->hero_subtitle ?? 'Clinic.' }}</span>
            </h1>
            <p class="fs-4 text-muted fw-light mb-5" style="max-width: 600px;">
                {{ $clinicSetting->hero_description ?? 'Specialized consultant-based oncology services designed for clarity and peace of mind.' }}
            </p>
            <div class="d-flex gap-4">
                @auth
                    <a href="{{ route('patient.dashboard') }}" class="btn-min text-decoration-none">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-min text-decoration-none">Book Appointment</a>
                    <a href="{{ route('public.doctors') }}" class="text-dark text-decoration-none fw-light d-flex align-items-center border-bottom border-dark pb-1">View Specialists <i class="bi bi-arrow-right ms-2"></i></a>
                @endauth
            </div>
        </div>
    </div>
</div>

<div class="bg-light py-5 mt-5">
    <div class="container py-5">
        <div class="row g-5">
            @php
                $features = is_string($clinicSetting->features) ? json_decode($clinicSetting->features, true) : $clinicSetting->features;
                if(empty($features)) $features = [['icon'=>'bi-shield','title'=>'Secure'], ['icon'=>'bi-clock','title'=>'Fast'], ['icon'=>'bi-heart','title'=>'Care']];
            @endphp
            @foreach($features as $feat)
            <div class="col-md-4">
                <div class="p-4 bg-white h-100">
                    <i class="bi {{ $feat['icon'] ?? 'bi-check' }} fs-1 mb-4 d-block text-muted"></i>
                    <h4 class="mb-3 fw-light">{{ $feat['title'] ?? '' }}</h4>
                    <p class="text-muted fw-light">{{ $feat['description'] ?? '' }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div class="container py-5 my-5">
    <div class="d-flex justify-content-between align-items-end mb-5 pb-4 border-bottom">
        <h2 class="display-4 fw-light mb-0">Specialists.</h2>
        <a href="{{ route('public.doctors') }}" class="text-dark text-decoration-none text-uppercase small letter-spacing-1">View All <i class="bi bi-arrow-right"></i></a>
    </div>
    
    <div class="row g-5">
        @forelse($doctors->take(3) as $doctor)
            <div class="col-md-4">
                <div class="text-center group">
                    <div class="bg-light mb-4 mx-auto overflow-hidden" style="width: 250px; height: 300px;">
                        @if($doctor->user->profile_photo)
                            <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="w-100 h-100 object-fit-cover" style="filter: grayscale(100%); transition: filter 0.5s;">
                        @else
                            <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                <i class="bi bi-person display-1 text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <h4 class="fw-light mb-1">{{ $doctor->user->name }}</h4>
                    <p class="text-muted small text-uppercase tracking-wider mb-4">{{ $category->name ?? 'Specialist' }}</p>
                    @auth
                        <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="text-dark text-decoration-none border-bottom border-dark pb-1 text-uppercase small">Book</a>
                    @else
                        <a href="{{ route('login') }}" class="text-dark text-decoration-none border-bottom border-dark pb-1 text-uppercase small">Login</a>
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5"><p class="text-muted fw-light">No specialists listed.</p></div>
        @endforelse
    </div>
</div>
@endsection
