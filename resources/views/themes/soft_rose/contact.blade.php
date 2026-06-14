@extends('themes.soft_rose.layout')

@section('title', 'Contact Us')

@section('content')
<div class="container py-5 mt-4">
    <div class="text-center mb-5 pb-4">
        <h1 class="display-3 mb-3">Contact Us</h1>
        <p class="text-muted fs-5">We're here to answer any questions you may have.</p>
    </div>
    
    <div class="row g-5">
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-4">
                <div class="rose-card text-center p-4">
                    <i class="bi bi-geo-alt display-4 text-primary mb-3 d-block"></i>
                    <h5 class="fw-bold mb-2">Visit Us</h5>
                    <p class="text-muted mb-0">{{ $clinicSetting->address ?? 'Multan' }}</p>
                </div>
                <div class="rose-card text-center p-4">
                    <i class="bi bi-telephone display-4 text-primary mb-3 d-block"></i>
                    <h5 class="fw-bold mb-2">Call Us</h5>
                    <p class="text-muted mb-0">{{ $clinicSetting->phone ?? '+92 300 1234567' }}</p>
                </div>
                <div class="rose-card text-center p-4">
                    <i class="bi bi-envelope display-4 text-primary mb-3 d-block"></i>
                    <h5 class="fw-bold mb-2">Email Us</h5>
                    <p class="text-muted mb-0">{{ $clinicSetting->contact_email ?? 'info@clinic.com' }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="rose-card p-4 p-md-5 h-100">
                <h3 class="fw-bold mb-4">Send a Message</h3>
                @if(session('success')) <div class="alert alert-success rounded-4">{{ session('success') }}</div> @endif
                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold ms-2">Your Name</label>
                            <input type="text" class="form-control rounded-pill bg-light border-0 py-3 px-4" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold ms-2">Your Email</label>
                            <input type="email" class="form-control rounded-pill bg-light border-0 py-3 px-4" name="email" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold ms-2">Subject</label>
                            <input type="text" class="form-control rounded-pill bg-light border-0 py-3 px-4" name="subject" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold ms-2">Message</label>
                            <textarea class="form-control rounded-4 bg-light border-0 py-3 px-4" name="message" rows="5" required></textarea>
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn-rose w-100 py-3 fs-5">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
