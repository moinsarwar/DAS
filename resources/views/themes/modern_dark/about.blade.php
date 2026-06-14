@extends('themes.modern_dark.layout')

@section('title', 'About Us')

@section('content')
<div class="py-5 bg-secondary bg-opacity-10 border-bottom border-secondary">
    <div class="container-fluid px-4 px-lg-5">
        <h1 class="display-4 fw-bold text-white mb-3">About <span class="text-primary">Us</span></h1>
        <p class="lead text-muted mb-0" style="max-width: 600px;">Learn more about our mission, vision, and the expert team driving modern oncology care.</p>
    </div>
</div>

<div class="py-5">
    <div class="container-fluid px-4 px-lg-5">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <div class="position-relative">
                    <div class="modern-stat-card p-5 border border-primary border-opacity-50 glowing-btn">
                        <i class="bi bi-hospital fs-1 text-primary mb-4 d-block"></i>
                        <h2 class="fw-bold text-white mb-4">Our Commitment to Care</h2>
                        <p class="text-muted lh-lg mb-0" style="font-size: 1.1rem;">
                            {{ $clinicSetting->about_short ?? 'At Multan Cancer Clinic, our mission is to provide world-class oncology care through a network of specialized consultants. We leverage modern technology to connect patients with top-tier specialists efficiently and securely.' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="modern-card p-4 h-100 border-start border-4 border-primary">
                            <h3 class="fw-bold text-white mb-3">Mission</h3>
                            <p class="text-muted small mb-0">To deliver compassionate, state-of-the-art cancer care that improves the quality of life for all our patients.</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="modern-card p-4 h-100 border-start border-4 border-info">
                            <h3 class="fw-bold text-white mb-3">Vision</h3>
                            <p class="text-muted small mb-0">To be the region's leading center for oncology excellence and innovative treatment approaches.</p>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="modern-card p-4">
                            <h3 class="fw-bold text-white mb-4">Core Values</h3>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-primary bg-opacity-10 border border-primary text-primary py-2 px-3">Excellence</span>
                                <span class="badge bg-primary bg-opacity-10 border border-primary text-primary py-2 px-3">Compassion</span>
                                <span class="badge bg-primary bg-opacity-10 border border-primary text-primary py-2 px-3">Innovation</span>
                                <span class="badge bg-primary bg-opacity-10 border border-primary text-primary py-2 px-3">Integrity</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
