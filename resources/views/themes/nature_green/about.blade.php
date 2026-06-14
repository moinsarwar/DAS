@extends('themes.nature_green.layout')

@section('title', 'Our Story')

@section('content')
<div class="bg-light py-5 text-center">
    <div class="container py-4">
        <h1 class="display-4 fw-bold" style="color: var(--primary-dark);">Our <span style="color: var(--primary-green);">Story</span></h1>
        <p class="lead text-muted mx-auto mb-0" style="max-width: 700px;">Committed to bringing healing, hope, and advanced medical expertise to our community.</p>
    </div>
</div>

<div class="container py-5 my-4">
    <div class="row align-items-center g-5">
        <div class="col-lg-6">
            <div class="pe-lg-4 text-center text-lg-start">
                <i class="bi bi-heart-pulse-fill display-1 mb-4" style="color: var(--primary-green);"></i>
                <h2 class="fw-bold mb-4" style="color: var(--primary-dark);">Healing with Compassion</h2>
                <p class="text-muted lh-lg mb-4" style="font-size: 1.1rem;">
                    {{ $clinicSetting->about_short ?? 'We believe that healthcare should be rooted in empathy and powered by modern science. Our clinic provides a serene environment where patients feel supported throughout their journey.' }}
                </p>
                <div class="d-flex align-items-center justify-content-center justify-content-lg-start gap-4 mt-4">
                    <div class="text-center">
                        <h3 class="fw-bold text-success mb-0">15+</h3>
                        <span class="text-muted small">Specialists</span>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold text-success mb-0">10k+</h3>
                        <span class="text-muted small">Patients</span>
                    </div>
                    <div class="text-center">
                        <h3 class="fw-bold text-success mb-0">24/7</h3>
                        <span class="text-muted small">Support</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row g-4">
                <div class="col-sm-6">
                    <div class="nature-card p-4 text-center h-100" style="background-color: var(--primary-dark); color: white;">
                        <i class="bi bi-bullseye fs-1 mb-3 d-block text-white opacity-75"></i>
                        <h4 class="fw-bold mb-3">Mission</h4>
                        <p class="small text-white opacity-75 mb-0">To deliver compassionate, state-of-the-art care that improves the quality of life.</p>
                    </div>
                </div>
                <div class="col-sm-6 pt-sm-5">
                    <div class="nature-card p-4 text-center h-100" style="background-color: var(--primary-green); color: white;">
                        <i class="bi bi-eye fs-1 mb-3 d-block text-white opacity-75"></i>
                        <h4 class="fw-bold mb-3">Vision</h4>
                        <p class="small text-white opacity-75 mb-0">To be the region's leading center for medical excellence and innovative approaches.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
