@extends('themes.royal_purple.layout')

@section('title', 'About Us')

@section('content')
<div class="bg-white py-5 border-bottom border-4" style="border-color: var(--royal-gold) !important;">
    <div class="container py-5 text-center">
        <h1 class="display-4 fw-bold text-uppercase letter-spacing-1" style="color: var(--royal-dark);">Our Story</h1>
    </div>
</div>
<div class="container py-5 my-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-6">
            <div class="royal-card p-5 h-100">
                <i class="bi bi-shield-check display-3 d-block mb-4" style="color: var(--royal-gold);"></i>
                <h3 class="fw-bold mb-4" style="color: var(--royal-dark);">Philosophy of Care</h3>
                <p class="text-muted lh-lg fs-5 mb-0">
                    {{ $clinicSetting->about_short ?? 'At Multan Cancer Clinic, our mission is to provide world-class oncology care through a network of specialized consultants. We leverage modern technology to connect patients with top-tier specialists efficiently and securely.' }}
                </p>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="d-flex flex-column gap-4">
                <div class="royal-card p-4 border-start border-4 border-primary">
                    <h4 class="fw-bold" style="color: var(--royal-dark);">Mission Statement</h4>
                    <p class="text-muted mb-0">Delivering compassionate care.</p>
                </div>
                <div class="royal-card p-4 border-start border-4" style="border-color: var(--royal-gold) !important;">
                    <h4 class="fw-bold" style="color: var(--royal-dark);">Vision Statement</h4>
                    <p class="text-muted mb-0">Leading the region in health.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
