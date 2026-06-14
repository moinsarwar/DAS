@extends('themes.luxury_gold.layout')

@section('title', 'Welcome')

@section('content')
<div class="position-relative overflow-hidden" style="padding: 10rem 0;">
    <div class="position-absolute inset-0 w-100 h-100 top-0 start-0" style="background: radial-gradient(circle at center, var(--lux-gray) 0%, var(--lux-darker) 100%); z-index: -1;"></div>
    <div class="container text-center">
        <p class="text-uppercase mb-4" style="color: var(--lux-gold); letter-spacing: 4px; font-size: 0.9rem;">
            {{ $clinicSetting->hero_badge ?? 'Excellence in Oncology' }}
        </p>
        <h1 class="display-3 mb-5" style="line-height: 1.2;">
            {{ $clinicSetting->hero_title ?? 'Multan Cancer' }} <br>
            <span style="font-style: italic; color: #e5e5e5;">{{ $clinicSetting->hero_subtitle ?? 'Clinic' }}</span>
        </h1>
        <p class="fs-5 text-muted mx-auto mb-5" style="max-width: 600px; font-weight: 300;">
            {{ $clinicSetting->hero_description ?? 'A sanctuary of healing, combining world-class medical expertise with unparalleled patient care.' }}
        </p>
        <div class="d-flex justify-content-center gap-4">
            @auth
                <a href="{{ route('patient.dashboard') }}" class="btn-lux-filled text-decoration-none px-5 py-3">Access Portal</a>
            @else
                <a href="{{ route('login') }}" class="btn-lux-filled text-decoration-none px-5 py-3">Request Consultation</a>
            @endauth
        </div>
    </div>
</div>

<div class="container my-5 py-5 border-top border-bottom" style="border-color: var(--lux-gray) !important;">
    <div class="row g-5">
        @php
            $features = is_string($clinicSetting->features) ? json_decode($clinicSetting->features, true) : $clinicSetting->features;
            if(empty($features)) $features = [['icon'=>'bi-star','title'=>'Premium'], ['icon'=>'bi-shield','title'=>'Secure'], ['icon'=>'bi-heart','title'=>'Care']];
        @endphp
        @foreach($features as $feat)
        <div class="col-md-4 text-center px-4">
            <i class="bi {{ $feat['icon'] ?? 'bi-check' }} display-5 mb-4 d-block" style="color: var(--lux-gold);"></i>
            <h4 class="mb-3">{{ $feat['title'] ?? '' }}</h4>
            <p class="text-muted" style="font-weight: 300;">{{ $feat['description'] ?? '' }}</p>
        </div>
        @endforeach
    </div>
</div>

<div class="container py-5 my-5">
    <div class="text-center mb-5 pb-4">
        <h2 class="display-5 mb-3">Distinguished Specialists</h2>
        <div style="width: 50px; height: 1px; background: var(--lux-gold); margin: 0 auto;"></div>
    </div>
    
    <div class="row g-5 justify-content-center">
        @forelse($doctors->take(3) as $doctor)
            <div class="col-lg-4 col-md-6">
                <div class="lux-card p-4 h-100 text-center">
                    <div class="mb-4 d-inline-block position-relative p-2" style="border: 1px solid var(--lux-gold);">
                        @if($doctor->user->profile_photo)
                            <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="w-100 h-100" style="width: 150px !important; height: 180px !important; object-fit: cover; filter: sepia(0.3) grayscale(0.5);">
                        @else
                            <div class="d-flex align-items-center justify-content-center" style="width: 150px; height: 180px; background: var(--lux-dark);">
                                <i class="bi bi-person display-1" style="color: var(--lux-gray);"></i>
                            </div>
                        @endif
                    </div>
                    <h4 class="mb-2">{{ $doctor->user->name }}</h4>
                    <p class="text-uppercase small mb-4" style="color: var(--lux-gold); letter-spacing: 2px;">{{ $category->name ?? 'Specialist' }}</p>
                    <p class="text-muted small mb-4" style="font-style: italic;">{{ Str::limit($doctor->qualification, 60) }}</p>
                    @auth
                        <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn-lux w-100 text-decoration-none">Reserve</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-lux w-100 text-decoration-none">Inquire</a>
                    @endauth
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5"><p class="text-muted" style="font-style: italic;">No specialists listed.</p></div>
        @endforelse
    </div>
</div>
@endsection
