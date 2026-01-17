@extends('layouts.landing')

@section('title', 'Welcome')

@section('content')
<div class="container py-5">
    {{-- Hero Section --}}
        <div class="row align-items-center hero-section">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <span class="hero-badge mb-3">
                    <i class="bi bi-shield-check me-2"></i> Excellence in Oncology
                </span>
                <h1 class="display-3 fw-bold mb-4 text-dark">
                    Multan Cancer <br>
                    <span class="text-primary position-relative">
                        Clinic
                        <svg class="underline-decoration" fill="currentColor" viewBox="0 0 100 20" preserveAspectRatio="none">
                            <path d="M0 10 Q 50 20 100 10 T 200 10" />
                        </svg>
                    </span>
                </h1>
                <p class="lead text-muted mb-4" style="max-width: 540px;">
                    Specialized consultant-based oncology services. We connect patients with leading oncologists through a streamlined appointment system.
                </p>

                <div class="d-flex align-items-start gap-2 mb-5">
                     <i class="bi bi-info-circle-fill text-warning mt-1"></i>
                     <p class="text-muted small mb-0" style="max-width: 500px;">
                        <strong>Please Note:</strong> We provide scheduled consultant services only. <br>
                        <span class="text-danger">24-hour emergency services are NOT available.</span>
                     </p>
                </div>

                <div class="d-flex gap-3">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDoctor() ? route('doctor.dashboard') : (auth()->user()->isReceptionist() ? route('receptionist.dashboard') : route('patient.dashboard'))) }}" 
                           class="btn btn-primary btn-lg px-4 py-3 fw-bold shadow-sm hover-lift">
                            <i class="bi bi-speedometer2 me-2"></i> Go to Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-5 py-3 fw-bold shadow-sm hover-lift">
                            <i class="bi bi-calendar-check me-2"></i> Book Appointment
                        </a>
                        <a href="#schedule" class="btn btn-outline-secondary btn-lg px-4 py-3 fw-bold shadow-sm hover-lift">
                            View Schedule
                        </a>
                    @endauth
                </div>

                <div class="mt-5 pt-4 border-top">
                    <div class="d-flex align-items-center flex-wrap gap-4 text-muted small fw-medium">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-clock text-primary"></i> 02:00 PM - 08:00 PM
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-calendar-event text-primary"></i> Mon - Sat
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Side Visuals --}}
            <div class="col-lg-6 mt-lg-0 mt-5">
                <div class="position-relative">
                    <div class="decoration-blob"></div>

                    <div class="card border-0 shadow-lg p-0 bg-white bg-opacity-75 backdrop-blur overflow-hidden">
                        <div class="card-header bg-transparent border-bottom p-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-hospital fs-4"></i>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-0">Clinic Overview</h5>
                                    <small class="text-muted">{{ $clinicSetting->address ?? 'Nishtar Road, Multan' }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                             <div class="d-flex align-items-start gap-3 mb-4">
                                <i class="bi bi-check-circle-fill text-success fs-5 flex-shrink-0"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Oncologist Consultants</h6>
                                    <p class="text-muted small mb-0">Specialized doctors available for detailed consultations and treatment planning.</p>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3 mb-4">
                                 <i class="bi bi-check-circle-fill text-success fs-5 flex-shrink-0"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Scheduled Slots</h6>
                                    <p class="text-muted small mb-0">No waiting in long queues. Book your specific time slot with your preferred doctor.</p>
                                </div>
                            </div>
                             <div class="d-flex align-items-start gap-3">
                                 <i class="bi bi-check-circle-fill text-success fs-5 flex-shrink-0"></i>
                                <div>
                                    <h6 class="fw-bold mb-1">Digital Records</h6>
                                    <p class="text-muted small mb-0">Your medical history and prescriptions are securely stored and easily accessible.</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-light p-3 text-center border-top">
                            <small class="text-muted">Trusted by thousands of patients in Multan</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Doctor Schedule Overview --}}
        <div id="schedule" class="py-5">
            <div class="row justify-content-center mb-5">
                <div class="col-lg-8 text-center">
                    <span class="badge bg-primary bg-opacity-10 text-primary mb-2">Availability</span>
                    <h2 class="fw-bold mb-3">Doctor Schedule</h2>
                    <p class="text-muted">Different specialists are available at different times between <strong>02:00 PM and 08:00 PM</strong>. Please login to view exact slots.</p>
                </div>
            </div>

            <div class="row g-4">
            @forelse($doctors as $doctor)
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm h-100 p-3 hover-lift doctor-card">
                        <div class="card-body">
                            <div class="d-flex align-items-center gap-3 mb-3">
                                @if($doctor->user->profile_photo)
                                     <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="rounded-circle border" style="width: 60px; height: 60px; object-fit: cover;" alt="Doctor">
                                @else
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                        <i class="bi bi-person-fill fs-3"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">{{ $doctor->user->name }}</h6>
                                    <small class="text-primary fw-semibold">{{ $doctor->category->name ?? 'Specialist' }}</small>
                                    <div class="text-muted" style="font-size: 0.75rem;">{{ $doctor->qualification }}</div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                     <i class="bi bi-clock text-warning"></i>
                                     <small class="fw-bold text-uppercase" style="font-size: 0.7rem;">Availability</small>
                                </div>
                                @if($doctor->schedules->count() > 0)
                                    <ul class="list-unstyled mb-0">
                                        @foreach($doctor->schedules->take(2) as $schedule)
                                            <li class="d-flex justify-content-between text-muted small mb-1">
                                                <span>{{ $schedule->day }}</span>
                                                <span class="fw-medium">{{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}</span>
                                            </li>
                                        @endforeach
                                        @if($doctor->schedules->count() > 2)
                                            <li class="text-center text-primary small mt-1" style="font-size: 0.7rem;">+ more slots available</li>
                                        @endif
                                    </ul>
                                @else
                                    <p class="text-muted small fst-italic mb-0">Contact for appointment schedule.</p>
                                @endif
                            </div>

                            <div class="d-grid">
                                @auth
                                    @if(auth()->user()->isPatient())
                                         <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn btn-outline-primary btn-sm rounded-pill">Book Appointment</a>
                                    @else
                                         <button class="btn btn-outline-secondary btn-sm rounded-pill" disabled>Patient Only</button>
                                    @endif
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm rounded-pill">Login to Book</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <div class="d-inline-block p-4 bg-light rounded-circle mb-3">
                        <i class="bi bi-calendar-x text-muted fs-1"></i>
                    </div>
                    <h5 class="text-muted">No Doctors Scheduled Yet</h5>
                    <p class="text-muted small">Please check back later for updated availability.</p>
                </div>
            @endforelse
        </div>
        </div>

        {{-- FAQs / Info Section --}}
        <div class="row g-4 py-5 mb-5 align-items-center">
            <div class="col-lg-6">
                <h3 class="fw-bold mb-4">Patient Information</h3>
                <div class="accordion accordion-flush shadow-sm rounded-3 overflow-hidden border" id="faqAccordion">
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                                Do you offer emergency services?
                            </button>
                        </h2>
                        <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small">
                                No, Multan Cancer Clinic is a consultant-based clinic. We do not have a 24-hour emergency department. Please visit a general hospital for emergencies.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                                How do I book an appointment?
                            </button>
                        </h2>
                        <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small">
                                You must register or login to our portal. Once logged in, you can view available doctors and select a time slot that suits you.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                                What are the clinic timings?
                            </button>
                        </h2>
                        <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-muted small">
                                Our clinic operates from 02:00 PM to 08:00 PM, Monday through Saturday. Doctors have specific slots within these hours.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-4 bg-primary bg-opacity-10 rounded-4 text-center">
                    <div class="mb-3 text-primary">
                        <i class="bi bi-headset fs-1"></i>
                    </div>
                    <h4 class="fw-bold text-dark">Need Help?</h4>
                    <p class="text-muted mb-4">Our support staff is available during clinic hours to assist you.</p>

                    <div class="d-flex flex-column gap-3 justify-content-center">
                        <div class="d-flex align-items-center justify-content-center gap-2 text-dark fw-medium">
                            <i class="bi bi-telephone-fill text-primary"></i>
                            <span>{{ $clinicSetting->phone ?? '+92 300 1234567' }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-2 text-dark fw-medium">
                            <i class="bi bi-envelope-fill text-primary"></i>
                            <span>{{ $clinicSetting->contact_email ?? 'info@multancancerclinic.com' }}</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-2 text-dark fw-medium">
                            <i class="bi bi-geo-alt-fill text-primary"></i>
                            <span>{{ $clinicSetting->address ?? 'Nishtar Road, Multan' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection