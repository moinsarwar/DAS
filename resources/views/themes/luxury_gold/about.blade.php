@extends('themes.luxury_gold.layout')

@section('title', 'About Us')

@section('content')
<div class="container py-5 mt-5">
    <div class="row align-items-center justify-content-center mb-5 pb-5">
        <div class="col-lg-8 text-center">
            <h1 class="display-3 mb-4">Heritage & Excellence</h1>
            <div style="width: 50px; height: 1px; background: var(--lux-gold); margin: 0 auto 2rem;"></div>
            <p class="fs-4 text-muted lh-lg" style="font-weight: 300;">
                {{ $clinicSetting->about_short ?? 'At Multan Cancer Clinic, our mission is to provide world-class oncology care through a network of specialized consultants.' }}
            </p>
        </div>
    </div>
    
    <div class="row g-5 py-5 border-top" style="border-color: var(--lux-gray) !important;">
        <div class="col-md-6">
            <div class="lux-card p-5 h-100 text-center">
                <i class="bi bi-gem display-4 d-block mb-4" style="color: var(--lux-gold);"></i>
                <h3 class="mb-4">Our Mission</h3>
                <p class="text-muted" style="font-weight: 300;">To deliver compassionate, state-of-the-art cancer care that improves the quality of life for all our patients.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="lux-card p-5 h-100 text-center">
                <i class="bi bi-star display-4 d-block mb-4" style="color: var(--lux-gold);"></i>
                <h3 class="mb-4">Our Vision</h3>
                <p class="text-muted" style="font-weight: 300;">To be the region's leading center for oncology excellence and innovative treatment approaches.</p>
            </div>
        </div>
    </div>
</div>
@endsection
