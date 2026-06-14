@extends('themes.nature_green.layout')

@section('title', 'Welcome')

@section('content')
<div class="container py-5">
    <div class="hero-section text-center">
        @if($clinicSetting->hero_badge)
            <span class="badge bg-white text-success rounded-pill px-4 py-2 mb-4 shadow-sm border border-success border-opacity-25" style="font-size: 0.9rem;">
                <i class="bi bi-tree-fill me-2"></i>{{ $clinicSetting->hero_badge }}
            </span>
        @endif
        
        <h1 class="display-4 fw-bold mb-4" style="color: var(--primary-dark);">
            {{ $clinicSetting->hero_title ?? 'Multan Cancer' }} <br>
            <span style="color: var(--primary-green);">{{ $clinicSetting->hero_subtitle ?? 'Clinic' }}</span>
        </h1>
        
        <p class="lead text-muted mx-auto mb-5" style="max-width: 700px;">
            {{ $clinicSetting->hero_description ?? 'Specialized consultant-based oncology services.' }}
        </p>

        <div class="d-flex justify-content-center gap-3 mb-5">
            @auth
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDoctor() ? route('doctor.dashboard') : (auth()->user()->isReceptionist() ? route('receptionist.dashboard') : route('patient.dashboard'))) }}" 
                   class="btn btn-nature btn-lg px-5 shadow">
                    My Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-nature btn-lg px-5 shadow">
                    Book Consultation
                </a>
            @endauth
        </div>

        <div class="d-flex justify-content-center gap-4 text-muted mt-5 pt-4 border-top border-success border-opacity-10">
            <span class="d-flex align-items-center gap-2">
                <i class="bi bi-clock-fill fs-5" style="color: var(--primary-green);"></i> 
                {{ $clinicSetting->clinic_days ?? 'Mon-Sat' }}, {{ $clinicSetting->clinic_hours ?? '2PM-8PM' }}
            </span>
            <span class="d-flex align-items-center gap-2">
                <i class="bi bi-geo-alt-fill fs-5" style="color: var(--primary-green);"></i> 
                {{ $clinicSetting->address ?? 'Multan' }}
            </span>
        </div>
    </div>
</div>

<div class="container py-5 mb-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold" style="color: var(--primary-dark);">Our Medical Specialists</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Compassionate care delivered by highly trained professionals.</p>
    </div>

    <div class="row g-4 justify-content-center">
        @forelse($doctors->take(3) as $doctor)
            <div class="col-lg-4 col-md-6">
                <div class="nature-card h-100 p-4 text-center hover-shadow transition">
                    <div class="position-relative d-inline-block mb-4">
                        @if($doctor->user->profile_photo)
                             <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="rounded-circle shadow-sm" style="width: 120px; height: 120px; object-fit: cover; border: 4px solid var(--primary-light);">
                        @else
                            <div class="rounded-circle shadow-sm d-flex align-items-center justify-content-center mx-auto" style="width: 120px; height: 120px; background-color: var(--primary-light); color: var(--primary-dark);">
                                <i class="bi bi-person-heart fs-1"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h4 class="fw-bold mb-1" style="color: var(--primary-dark);">{{ $doctor->user->name }}</h4>
                    <p class="text-success fw-medium mb-3">{{ $doctor->category->name ?? 'Specialist' }}</p>
                    <p class="text-muted small mb-4">{{ Str::limit($doctor->bio ?? $doctor->qualification, 90) }}</p>
                    
                    <div class="mt-auto">
                        @auth
                            <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn btn-outline-primary rounded-pill w-100 fw-bold">Schedule Visit</a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill w-100 fw-bold">Login to Book</a>
                        @endauth
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">
                <i class="bi bi-info-circle fs-1 mb-3 d-block"></i>
                <p>No specialists available at the moment.</p>
            </div>
        @endforelse
    </div>
    
    <div class="text-center mt-5">
        <a href="{{ route('public.doctors') }}" class="text-success fw-bold text-decoration-none">Explore All Doctors <i class="bi bi-arrow-right"></i></a>
    </div>
</div>

<div style="background-color: white; border-top: 1px solid var(--primary-light);" class="py-5">
    <div class="container py-4">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <div class="pe-lg-5">
                    <h2 class="fw-bold mb-4" style="color: var(--primary-dark);">Why Choose Us?</h2>
                    
                    @php
                        $features = is_string($clinicSetting->features) ? json_decode($clinicSetting->features, true) : $clinicSetting->features;
                        if(empty($features)) $features = [['icon'=>'bi-heart','title'=>'Care','description'=>'Best care']];
                    @endphp
                    
                    <div class="d-flex flex-column gap-4">
                        @foreach($features as $feat)
                        <div class="d-flex gap-4 align-items-start">
                            <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 50px; height: 50px; background-color: var(--primary-light); color: var(--primary-dark);">
                                <i class="bi {{ $feat['icon'] ?? 'bi-check' }} fs-4"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1" style="color: var(--primary-dark);">{{ $feat['title'] ?? '' }}</h5>
                                <p class="text-muted mb-0">{{ $feat['description'] ?? '' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="nature-card p-5" style="background-color: var(--primary-dark); color: white;">
                    <h3 class="fw-bold text-white mb-4">Patient Information</h3>
                    
                    <div class="accordion accordion-flush nature-accordion" id="faqAccordion">
                        @php
                            $faqs = is_string($clinicSetting->faqs) ? json_decode($clinicSetting->faqs, true) : $clinicSetting->faqs;
                            if(empty($faqs)) $faqs = [['question'=>'Q1?','answer'=>'A1']];
                        @endphp
                        @foreach($faqs as $index => $faq)
                            <div class="accordion-item bg-transparent border-0 mb-3">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-white text-dark rounded-3 fw-bold shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#faq-{{ $index }}">
                                        {{ $faq['question'] ?? '' }}
                                    </button>
                                </h2>
                                <div id="faq-{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-white opacity-75 pb-0 pt-3">
                                        {{ $faq['answer'] ?? '' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
