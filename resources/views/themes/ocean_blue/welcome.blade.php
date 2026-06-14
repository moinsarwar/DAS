@extends('themes.ocean_blue.layout')

@section('title', 'Welcome')

@section('content')
<style>
    .ocean-hero {
        background-color: var(--ocean-dark);
        color: white;
        padding: 8rem 0 6rem;
        position: relative;
        overflow: hidden;
    }
    .ocean-hero::after {
        content: '';
        position: absolute;
        bottom: -50px;
        right: -50px;
        width: 300px;
        height: 300px;
        background-color: var(--ocean-blue);
        border-radius: 50%;
        opacity: 0.1;
        z-index: 0;
    }
    .ocean-hero-content {
        position: relative;
        z-index: 1;
    }
    .ocean-feature-card {
        background: white;
        padding: 2rem;
        border-top: 4px solid var(--ocean-blue);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        height: 100%;
        transition: transform 0.3s;
    }
    .ocean-feature-card:hover {
        transform: translateY(-5px);
    }
    .ocean-doctor-card {
        background: white;
        border: 1px solid #e2e8f0;
        padding: 2rem;
        height: 100%;
        position: relative;
    }
    .ocean-doctor-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 4px; height: 100%;
        background-color: var(--ocean-blue);
        transform: scaleY(0);
        transform-origin: bottom;
        transition: transform 0.3s ease;
    }
    .ocean-doctor-card:hover::before {
        transform: scaleY(1);
    }
</style>

<div class="ocean-hero">
    <div class="container ocean-hero-content">
        <div class="row">
            <div class="col-lg-8">
                <span class="text-info text-uppercase tracking-wider fw-bold mb-3 d-block letter-spacing-1">
                    <i class="bi bi-shield-plus me-2"></i> {{ $clinicSetting->hero_badge ?? 'Trusted Oncology' }}
                </span>
                <h1 class="display-3 fw-bold mb-4 text-white">
                    {{ $clinicSetting->hero_title ?? 'Multan Cancer' }} <br>
                    <span class="text-info">{{ $clinicSetting->hero_subtitle ?? 'Clinic' }}</span>
                </h1>
                <p class="lead mb-5 opacity-75" style="max-width: 600px;">
                    {{ $clinicSetting->hero_description ?? 'Specialized consultant-based oncology services. We connect patients with leading oncologists through a streamlined digital appointment system.' }}
                </p>
                
                <div class="d-flex gap-3">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDoctor() ? route('doctor.dashboard') : (auth()->user()->isReceptionist() ? route('receptionist.dashboard') : route('patient.dashboard'))) }}" 
                           class="btn btn-primary px-5 py-3 text-uppercase fw-bold">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary px-5 py-3 text-uppercase fw-bold">
                            Book Appointment
                        </a>
                        <a href="{{ route('public.doctors') }}" class="btn btn-outline-light px-4 py-3 text-uppercase fw-bold">
                            Our Doctors
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-top: -3rem; position: relative; z-index: 2;">
    <div class="row g-4">
        @php
            $features = is_string($clinicSetting->features) ? json_decode($clinicSetting->features, true) : $clinicSetting->features;
            if(empty($features)) $features = [['icon'=>'bi-shield','title'=>'Secure'], ['icon'=>'bi-clock','title'=>'Fast'], ['icon'=>'bi-heart','title'=>'Care']];
        @endphp
        @foreach($features as $feat)
        <div class="col-lg-4 col-md-6">
            <div class="ocean-feature-card">
                <div class="text-primary mb-3"><i class="bi {{ $feat['icon'] ?? 'bi-check' }} display-4"></i></div>
                <h4 class="fw-bold mb-3 text-dark">{{ $feat['title'] ?? '' }}</h4>
                <p class="text-muted mb-0">{{ $feat['description'] ?? '' }}</p>
            </div>
        </div>
        @endforeach
    </div>
</div>

<div class="py-5 my-5">
    <div class="container">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-end mb-5 pb-3 border-bottom">
            <div>
                <h6 class="text-primary text-uppercase fw-bold">Medical Staff</h6>
                <h2 class="fw-bold display-6 mb-0 text-dark">Meet Our Specialists</h2>
            </div>
            <div class="mt-3 mt-md-0">
                <a href="{{ route('public.doctors') }}" class="btn btn-outline-primary text-uppercase fw-bold">View Directory</a>
            </div>
        </div>

        <div class="row g-4">
            @forelse($doctors->take(3) as $doctor)
                <div class="col-md-4">
                    <div class="ocean-doctor-card d-flex flex-column">
                        <div class="mb-4">
                            @if($doctor->user->profile_photo)
                                 <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="img-fluid border" style="max-height: 250px; width: 100%; object-fit: cover; object-position: top;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center w-100" style="height: 200px;">
                                    <i class="bi bi-person text-secondary display-1"></i>
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <span class="text-primary text-uppercase small fw-bold d-block mb-1">{{ $doctor->category->name ?? 'Specialist' }}</span>
                            <h4 class="fw-bold text-dark mb-0">{{ $doctor->user->name }}</h4>
                        </div>
                        
                        <p class="text-muted small mb-4 flex-grow-1">{{ Str::limit($doctor->bio ?? $doctor->qualification, 100) }}</p>
                        
                        <div class="mt-auto pt-3 border-top d-grid">
                            @auth
                                <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn btn-primary text-uppercase fw-bold">Schedule Consultation</a>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-dark text-uppercase fw-bold">Login to Schedule</a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted">No specialists currently listed.</h5>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="bg-light py-5">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-5 pe-lg-5 mb-5 mb-lg-0">
                <h6 class="text-primary text-uppercase fw-bold">Support</h6>
                <h2 class="fw-bold display-6 mb-4 text-dark">Frequently Asked Questions</h2>
                <p class="text-muted mb-4">Find quick answers to common questions about our clinic, appointments, and services.</p>
                
                <div class="p-4 bg-white border-start border-4 border-primary shadow-sm">
                    <h5 class="fw-bold text-dark mb-2">Still have questions?</h5>
                    <p class="text-muted small mb-3">Contact our support team directly.</p>
                    <a href="{{ route('public.contact') }}" class="text-primary text-uppercase fw-bold text-decoration-none">Contact Us <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="accordion" id="faqAccordion">
                    @php
                        $faqs = is_string($clinicSetting->faqs) ? json_decode($clinicSetting->faqs, true) : $clinicSetting->faqs;
                        if(empty($faqs)) $faqs = [['question'=>'Q1?','answer'=>'A1']];
                    @endphp
                    @foreach($faqs as $index => $faq)
                        <div class="accordion-item mb-3 border-0 shadow-sm">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed fw-bold text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq-{{ $index }}">
                                    {{ $faq['question'] ?? '' }}
                                </button>
                            </h2>
                            <div id="faq-{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted">
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
@endsection
