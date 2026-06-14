@extends('themes.ocean_blue.layout')

@section('title', 'About Our Clinic')

@section('content')
<div class="bg-primary text-white py-5">
    <div class="container py-5 text-center">
        <h1 class="display-4 fw-bold mb-3 text-uppercase letter-spacing-1">About Our Clinic</h1>
        <p class="lead mb-0 mx-auto opacity-75" style="max-width: 700px;">Dedicated to providing exceptional healthcare and advancing the boundaries of medical science.</p>
    </div>
</div>

<div class="container py-5 my-5">
    <div class="row align-items-center g-5 mb-5 pb-5 border-bottom">
        <div class="col-lg-6">
            <div class="bg-light p-5 border-start border-4 border-primary h-100">
                <i class="bi bi-quote fs-1 text-primary mb-3 d-block opacity-50"></i>
                <h3 class="fw-bold mb-4 text-dark">Our Philosophy</h3>
                <p class="text-muted lh-lg mb-0" style="font-size: 1.1rem;">
                    {{ $clinicSetting->about_short ?? 'At Multan Cancer Clinic, our philosophy is simple: put the patient first. We combine cutting-edge technology with compassionate care to deliver treatment plans that are as unique as the individuals we serve.' }}
                </p>
            </div>
        </div>
        <div class="col-lg-6">
            <h2 class="fw-bold mb-4 text-dark text-uppercase">The Team Behind the Care</h2>
            <p class="text-muted mb-4">Our clinic was founded on the principle that high-quality oncology care should be accessible, organized, and focused entirely on patient outcomes.</p>
            <div class="d-flex gap-4">
                <div>
                    <h2 class="fw-bold text-primary mb-0">15+</h2>
                    <span class="text-uppercase small fw-bold text-muted">Specialists</span>
                </div>
                <div>
                    <h2 class="fw-bold text-primary mb-0">24/7</h2>
                    <span class="text-uppercase small fw-bold text-muted">Support</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 text-center">
        <div class="col-md-6">
            <div class="p-5 border h-100 position-relative overflow-hidden group">
                <div class="position-absolute top-0 start-0 w-100 h-100 bg-light opacity-0 transition group-hover-opacity-100"></div>
                <div class="position-relative z-1">
                    <i class="bi bi-eye display-4 text-primary mb-3"></i>
                    <h4 class="fw-bold text-uppercase mb-3">Our Vision</h4>
                    <p class="text-muted mb-0">To lead the region in clinical excellence, setting new standards for patient care and medical innovation.</p>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="p-5 border h-100 position-relative overflow-hidden group">
                <div class="position-absolute top-0 start-0 w-100 h-100 bg-light opacity-0 transition group-hover-opacity-100"></div>
                <div class="position-relative z-1">
                    <i class="bi bi-bullseye display-4 text-primary mb-3"></i>
                    <h4 class="fw-bold text-uppercase mb-3">Our Mission</h4>
                    <p class="text-muted mb-0">To provide expert, empathetic care that addresses the physical and emotional needs of every patient.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
