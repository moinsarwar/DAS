@extends('themes.modern_dark.layout')

@section('title', 'Welcome')

@section('content')
<style>
    .hero-modern {
        background: radial-gradient(circle at 10% 20%, rgba(59, 130, 246, 0.1) 0%, transparent 50%);
        padding: 6rem 0;
        border-bottom: 1px solid var(--border-color);
    }
    .glowing-btn {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
        border: 1px solid rgba(59, 130, 246, 0.8);
    }
    .modern-stat-card {
        background: rgba(30, 41, 59, 0.5);
        backdrop-filter: blur(10px);
        border: 1px solid var(--border-color);
        border-radius: 1rem;
        transition: transform 0.3s;
    }
    .modern-stat-card:hover {
        transform: translateY(-5px);
        border-color: var(--primary-color);
    }
</style>

<div class="hero-modern">
    <div class="container-fluid px-4 px-lg-5">
        <div class="row align-items-center">
            <div class="col-lg-7 mb-5 mb-lg-0">
                <div class="badge bg-primary bg-opacity-10 text-primary mb-3 px-3 py-2 rounded-pill border border-primary">
                    <i class="bi bi-activity"></i> {{ $clinicSetting->hero_badge ?? 'Advanced Oncology Care' }}
                </div>
                <h1 class="display-3 fw-bold mb-4 text-white lh-sm">
                    {{ $clinicSetting->hero_title ?? 'Multan Cancer' }} <br>
                    <span class="text-primary">{{ $clinicSetting->hero_subtitle ?? 'Clinic' }}</span>
                </h1>
                <p class="lead text-muted mb-5" style="max-width: 600px; font-weight: 300;">
                    {{ $clinicSetting->hero_description ?? 'Specialized consultant-based oncology services. We connect patients with leading oncologists through a streamlined digital appointment system.' }}
                </p>
                
                <div class="d-flex flex-wrap gap-3">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDoctor() ? route('doctor.dashboard') : (auth()->user()->isReceptionist() ? route('receptionist.dashboard') : route('patient.dashboard'))) }}" 
                           class="btn btn-primary glowing-btn px-5 py-3 rounded-3 fw-bold">
                            Enter Dashboard <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary glowing-btn px-5 py-3 rounded-3 fw-bold">
                            Book Appointment <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                        <a href="#specialists" class="btn btn-outline-light px-5 py-3 rounded-3 fw-bold">
                            View Specialists
                        </a>
                    @endauth
                </div>
            </div>
            
            <div class="col-lg-5">
                <div class="row g-4">
                    @php
                        $features = is_string($clinicSetting->features) ? json_decode($clinicSetting->features, true) : $clinicSetting->features;
                        if(empty($features)) $features = [['icon'=>'bi-shield','title'=>'Secure'], ['icon'=>'bi-clock','title'=>'Fast']];
                    @endphp
                    @foreach($features as $feat)
                    <div class="col-sm-6">
                        <div class="modern-stat-card p-4 h-100 text-center">
                            <i class="bi {{ $feat['icon'] ?? 'bi-check' }} fs-1 text-primary mb-3 d-block"></i>
                            <h5 class="fw-bold text-white mb-2">{{ $feat['title'] ?? '' }}</h5>
                            <p class="small text-muted mb-0">{{ $feat['description'] ?? '' }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div id="specialists" class="py-5">
    <div class="container-fluid px-4 px-lg-5">
        <div class="d-flex justify-content-between align-items-end mb-5">
            <div>
                <h6 class="text-primary text-uppercase tracking-wider fw-bold">Our Medical Team</h6>
                <h2 class="display-6 fw-bold text-white mb-0">Expert Oncologists</h2>
            </div>
            <a href="{{ route('public.doctors') }}" class="btn btn-outline-secondary d-none d-md-block">View All Specialists</a>
        </div>

        <div class="row g-4">
            @forelse($doctors->take(3) as $doctor)
                <div class="col-xl-4 col-md-6">
                    <div class="modern-card p-4 h-100">
                        <div class="d-flex align-items-center gap-4 mb-4">
                            @if($doctor->user->profile_photo)
                                 <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="rounded-circle border border-secondary" style="width: 80px; height: 80px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                                    <i class="bi bi-person fs-1 text-muted"></i>
                                </div>
                            @endif
                            <div>
                                <h4 class="text-white fw-bold mb-1">{{ $doctor->user->name }}</h4>
                                <span class="badge bg-primary bg-opacity-25 text-primary">{{ $doctor->category->name ?? 'Specialist' }}</span>
                            </div>
                        </div>
                        <p class="text-muted small mb-4">{{ Str::limit($doctor->bio ?? $doctor->qualification, 100) }}</p>
                        
                        <div class="border-top border-secondary pt-3 mt-auto">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small"><i class="bi bi-clock me-1"></i> Check Availability</span>
                                @auth
                                    <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn btn-sm btn-primary">Book Now</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">Login</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="modern-card p-5 text-center">
                        <h4 class="text-muted">No specialists available at the moment.</h4>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>

<div class="py-5 bg-secondary bg-opacity-10 border-top border-bottom border-secondary">
    <div class="container-fluid px-4 px-lg-5">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <h2 class="display-6 fw-bold text-white mb-4">Frequently Asked Questions</h2>
                <div class="accordion accordion-flush" id="faqAccordion" data-bs-theme="dark">
                    @php
                        $faqs = is_string($clinicSetting->faqs) ? json_decode($clinicSetting->faqs, true) : $clinicSetting->faqs;
                        if(empty($faqs)) $faqs = [['question'=>'Q1?','answer'=>'A1']];
                    @endphp
                    @foreach($faqs as $index => $faq)
                        <div class="accordion-item bg-transparent border-secondary">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-transparent text-white fw-bold py-4" type="button" data-bs-toggle="collapse" data-bs-target="#faq-{{ $index }}">
                                    {{ $faq['question'] ?? '' }}
                                </button>
                            </h2>
                            <div id="faq-{{ $index }}" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                <div class="accordion-body text-muted pb-4 pt-0">
                                    {{ $faq['answer'] ?? '' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <div class="modern-card p-5">
                    <h4 class="text-white fw-bold mb-4">Contact Information</h4>
                    <ul class="list-unstyled d-flex flex-column gap-4 mb-0">
                        <li class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary"><i class="bi bi-geo-alt fs-4"></i></div>
                            <div>
                                <small class="text-muted d-block">Location</small>
                                <span class="text-white">{{ $clinicSetting->address ?? 'Multan' }}</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary"><i class="bi bi-telephone fs-4"></i></div>
                            <div>
                                <small class="text-muted d-block">Phone</small>
                                <span class="text-white">{{ $clinicSetting->phone ?? 'Phone' }}</span>
                            </div>
                        </li>
                        <li class="d-flex align-items-center gap-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary"><i class="bi bi-clock fs-4"></i></div>
                            <div>
                                <small class="text-muted d-block">Hours ({{ $clinicSetting->clinic_days ?? 'Mon-Sat' }})</small>
                                <span class="text-white">{{ $clinicSetting->clinic_hours ?? '2PM-8PM' }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
