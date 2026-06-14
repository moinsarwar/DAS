@extends('themes.sunset_orange.layout')

@section('title', 'About Us')

@section('content')
<div class="bg-white py-5">
    <div class="container py-5 text-center">
        <span class="text-primary fw-bold tracking-wide text-uppercase mb-2 d-block">Who We Are</span>
        <h1 class="display-4 fw-bold" style="color: var(--sunset-dark);">Our Story</h1>
    </div>
</div>
<div class="container py-5 mb-5">
    <div class="sunset-card p-4 p-lg-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h3 class="fw-bold mb-4" style="color: var(--sunset-dark);">Committed to Excellence in Care</h3>
                <p class="text-muted lh-lg fs-5">
                    {{ $clinicSetting->about_short ?? 'At Multan Cancer Clinic, our mission is to provide world-class oncology care through a network of specialized consultants. We leverage modern technology to connect patients with top-tier specialists efficiently and securely.' }}
                </p>
            </div>
            <div class="col-lg-6">
                <div class="row g-4">
                    <div class="col-sm-6">
                        <div class="bg-white shadow-sm rounded-4 p-4 text-center h-100">
                            <i class="bi bi-bullseye display-4 text-primary mb-3"></i>
                            <h4 class="fw-bold" style="color: var(--sunset-dark);">Mission</h4>
                            <p class="small text-muted mb-0">Delivering compassionate care.</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="bg-primary rounded-4 p-4 text-center h-100 text-white shadow-sm">
                            <i class="bi bi-eye display-4 text-white opacity-75 mb-3"></i>
                            <h4 class="fw-bold text-white">Vision</h4>
                            <p class="small text-white-50 mb-0">Leading the region in health.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
