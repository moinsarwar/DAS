@extends('layouts.landing')

@section('title', 'About Us')

@section('content')
    <div class="bg-primary text-white py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">About Multan Cancer Clinic</h1>
            <p class="lead mb-0 text-white-50">Dedicated to providing world-class oncology care with compassion and
                expertise.</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-5 align-items-center mb-5">
            <div class="col-lg-6">
                <h2 class="fw-bold mb-4 text-dark">Our Mission</h2>
                <p class="text-muted lead">
                    To offer comprehensive, patient-centered cancer care through a team of highly skilled oncologists. We
                    believe in early detection, advanced treatment, and holistic support for every patient.
                </p>
                <p class="text-muted">
                    Multan Cancer Clinic was established to bridge the gap in specialized oncology services in the region.
                    We provide a comfortable, professional environment where patients can consult with top experts without
                    the hassle of long waiting times.
                </p>
                <div class="mt-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <span>Expert Consultant Oncologists</span>
                    </div>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <span>Personalized Treatment Plans</span>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2">
                            <i class="bi bi-check-lg"></i>
                        </div>
                        <span>State-of-the-Art Digital Records</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-5 bg-light rounded-4 text-center">
                    <i class="bi bi-hospital text-primary display-1 mb-3"></i>
                    <p class="text-muted small">Excellence in Care</p>
                </div>
            </div>
        </div>

        <div class="row g-4 text-center py-5 border-top">
            <div class="col-md-4">
                <div class="py-4">
                    <h2 class="fw-bold text-primary mb-2">10+</h2>
                    <p class="text-muted">Years of Experience</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="py-4">
                    <h2 class="fw-bold text-primary mb-2">5000+</h2>
                    <p class="text-muted">Patients Treated</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="py-4">
                    <h2 class="fw-bold text-primary mb-2">100%</h2>
                    <p class="text-muted">Patient Satisfaction</p>
                </div>
            </div>
        </div>
    </div>
@endsection