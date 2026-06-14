@extends('themes.clean_minimal.layout')

@section('title', 'About Us')

@section('content')
<div class="container py-5 mt-5">
    <div class="row justify-content-center mb-5 pb-5 border-bottom">
        <div class="col-lg-8 text-center">
            <h1 class="display-2 mb-4" style="font-weight: 300; letter-spacing: -2px;">About.</h1>
            <p class="fs-4 text-muted fw-light lh-base">
                {{ $clinicSetting->about_short ?? 'At Multan Cancer Clinic, our mission is to provide world-class oncology care through a network of specialized consultants.' }}
            </p>
        </div>
    </div>
    
    <div class="row g-5 py-5">
        <div class="col-md-6">
            <div class="bg-light p-5 h-100">
                <h3 class="fw-light mb-4">Mission.</h3>
                <p class="text-muted fw-light">To deliver compassionate, state-of-the-art cancer care that improves the quality of life for all our patients.</p>
            </div>
        </div>
        <div class="col-md-6">
            <div class="bg-light p-5 h-100">
                <h3 class="fw-light mb-4">Vision.</h3>
                <p class="text-muted fw-light">To be the region's leading center for oncology excellence and innovative treatment approaches.</p>
            </div>
        </div>
    </div>
</div>
@endsection
