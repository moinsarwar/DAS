@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="row align-items-center hero-section">
        <div class="col-lg-6 mb-5 mb-lg-0">
            <span class="hero-badge mb-3">
                <i class="bi bi-star-fill me-1"></i> #1 Trusted Healthcare Platform
            </span>
            <h1 class="display-3 fw-bold mb-4 text-dark">
                Quality Healthcare <br>
                <span class="text-primary position-relative">
                    Made Simple
                    <svg class="underline-decoration" fill="currentColor" viewBox="0 0 100 20" preserveAspectRatio="none">
                        <path d="M0 10 Q 50 20 100 10 T 200 10" />
                    </svg>
                </span>
            </h1>
            <p class="lead text-muted mb-5" style="max-width: 500px;">
                Book appointments with top-rated doctors, manage your health history, and get prescriptions online in a
                seamless experience.
            </p>
            <div class="d-flex gap-3">
                @auth
                    <div class="card border-0 shadow-sm mb-4 bg-light">
                        <div class="card-body p-4">
                            <h4 class="fw-bold text-primary mb-3">Welcome, {{ auth()->user()->name }}</h4>

                            @if(auth()->user()->isAdmin())
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="bg-white p-2 rounded border">
                                            <small class="text-muted d-block">Doctors</small>
                                            <span class="fw-bold">{{ $stats['doctors'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-white p-2 rounded border">
                                            <small class="text-muted d-block">Patients</small>
                                            <span class="fw-bold">{{ $stats['patients'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-primary w-100">
                                    <i class="bi bi-speedometer2 me-2"></i>Admin Dashboard
                                </a>
                            @elseif(auth()->user()->isDoctor())
                                <div class="row g-2 mb-3">
                                    <div class="col-4">
                                        <div class="bg-white p-2 rounded border text-center">
                                            <small class="text-muted d-block" style="font-size: 0.7rem;">Pending</small>
                                            <span class="fw-bold text-warning">{{ $stats['pending'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="bg-white p-2 rounded border text-center">
                                            <small class="text-muted d-block" style="font-size: 0.7rem;">Approved</small>
                                            <span class="fw-bold text-success">{{ $stats['approved'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="bg-white p-2 rounded border text-center">
                                            <small class="text-muted d-block" style="font-size: 0.7rem;">Checked</small>
                                            <span class="fw-bold text-primary">{{ $stats['checked'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                                <a href="{{ route('doctor.dashboard') }}" class="btn btn-primary w-100">
                                    <i class="bi bi-journal-medical me-2"></i>Doctor Dashboard
                                </a>
                            @elseif(auth()->user()->isReceptionist())
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="bg-white p-2 rounded border">
                                            <small class="text-muted d-block">Today's Appts</small>
                                            <span class="fw-bold">{{ $stats['today_booked'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-white p-2 rounded border">
                                            <small class="text-muted d-block">Pending</small>
                                            <span class="fw-bold text-warning">{{ $stats['pending'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('receptionist.dashboard') }}" class="btn btn-primary">
                                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                    </a>
                                </div>
                            @elseif(auth()->user()->isPatient())
                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="bg-white p-2 rounded border">
                                            <small class="text-muted d-block">Upcoming</small>
                                            <span class="fw-bold">{{ $stats['upcoming'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="bg-white p-2 rounded border">
                                            <small class="text-muted d-block">History</small>
                                            <span class="fw-bold">{{ $stats['history'] ?? 0 }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-grid gap-2">
                                    <a href="{{ route('patient.dashboard') }}" class="btn btn-primary">
                                        <i class="bi bi-calendar-check me-2"></i>My Dashboard
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 py-3 fw-bold shadow-sm">
                        <i class="bi bi-box-arrow-in-right me-2"></i> Login Now
                    </a>
                @endauth
            </div>

            <div class="mt-5 pt-4 border-top">
                <div class="d-flex align-items-center gap-4 text-muted small">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success"></i> Verified Doctors
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success"></i> Instant Booking
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-check-circle-fill text-success"></i> Secure Records
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="position-relative">
                <div class="decoration-blob"></div>

                <div class="card border-0 shadow-lg p-4 bg-white bg-opacity-75 backdrop-blur">
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <div class="bg-primary bg-opacity-10 text-primary d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-headset" style="font-size: 2.5rem;"></i>
                            </div>
                            <h3 class="fw-bold">24/7 Access</h3>
                            <p class="text-muted">Manage your health anytime, anywhere.</p>
                        </div>

                        <div class="row g-3">
                            <div class="col-4">
                                <div class="p-3 bg-light rounded-3 text-center">
                                    <h4 class="fw-bold text-dark mb-0">100+</h4>
                                    <small class="text-muted">Doctors</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 bg-light rounded-3 text-center">
                                    <h4 class="fw-bold text-dark mb-0">50+</h4>
                                    <small class="text-muted">Specialties</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="p-3 bg-light rounded-3 text-center">
                                    <h4 class="fw-bold text-dark mb-0">1k+</h4>
                                    <small class="text-muted">Users</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow mb-3 position-absolute start-0 top-0 translate-middle-y d-none d-lg-block"
                    style="width: 200px; transform: translateX(-30px);">
                    <div class="card-body p-3 d-flex align-items-center gap-3">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 40px; height: 40px;">
                            <i class="bi bi-calendar-check"></i>
                        </div>
                        <div>
                            <div class="fw-bold small">Easy Scheduling</div>
                            <div class="text-xs text-muted">Book in 3 clicks</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 py-5 mb-5">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-lift p-3">
                <div class="card-body">
                    <div class="mb-3 text-primary">
                        <i class="bi bi-search" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold">Find Doctors</h5>
                    <p class="text-muted">Search by specialty, name, or location to find the right expert for your needs.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-lift p-3">
                <div class="card-body">
                    <div class="mb-3 text-primary">
                        <i class="bi bi-calendar-plus" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold">Book Appointments</h5>
                    <p class="text-muted">Select a time slot that fits your schedule and book instantly without phone calls.
                    </p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm hover-lift p-3">
                <div class="card-body">
                    <div class="mb-3 text-primary">
                        <i class="bi bi-file-medical" style="font-size: 2rem;"></i>
                    </div>
                    <h5 class="fw-bold">Digital Records</h5>
                    <p class="text-muted">Keep track of your medical history and prescriptions in one secure place.</p>
                </div>
            </div>
        </div>
    </div>
@endsection