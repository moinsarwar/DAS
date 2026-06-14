@extends('themes.soft_rose.layout')

@section('title', 'About Us')

@section('content')
<div class="container py-5 mt-4">
    <div class="text-center mb-5 pb-4">
        <span class="text-primary fw-bold text-uppercase tracking-wider mb-2 d-block">Our Story</span>
        <h1 class="display-3">About Us</h1>
    </div>
    
    <div class="rose-card p-4 p-md-5 mb-5">
        <div class="row align-items-center g-5">
            <div class="col-lg-6 text-center">
                <div class="bg-rose-light rounded-circle d-inline-flex align-items-center justify-content-center p-5 mb-4">
                    <i class="bi bi-heart-pulse display-1 text-primary"></i>
                </div>
            </div>
            <div class="col-lg-6">
                <h2 class="mb-4">Compassionate Care</h2>
                <p class="text-muted fs-5 lh-lg mb-4">
                    {{ $clinicSetting->about_short ?? 'At Multan Cancer Clinic, our mission is to provide world-class oncology care through a network of specialized consultants.' }}
                </p>
                <div class="d-flex align-items-center gap-3">
                    <i class="bi bi-check-circle-fill fs-4 text-primary"></i>
                    <span class="fs-5 fw-bold text-dark">Patient-first Approach</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
