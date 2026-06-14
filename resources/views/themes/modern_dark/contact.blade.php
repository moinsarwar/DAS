@extends('themes.modern_dark.layout')

@section('title', 'Contact Us')

@section('content')
<style>
    .modern-input {
        background-color: rgba(15, 23, 42, 0.8) !important;
        border: 1px solid var(--border-color) !important;
        color: white !important;
        padding: 1rem;
        border-radius: 0.5rem;
    }
    .modern-input:focus {
        border-color: var(--primary-color) !important;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
    }
    .modern-input::placeholder {
        color: #64748b;
    }
</style>

<div class="py-5 bg-secondary bg-opacity-10 border-bottom border-secondary">
    <div class="container-fluid px-4 px-lg-5">
        <h1 class="display-4 fw-bold text-white mb-3">Contact <span class="text-primary">Us</span></h1>
        <p class="lead text-muted mb-0" style="max-width: 600px;">Get in touch with our support team for inquiries, feedback, or appointment assistance.</p>
    </div>
</div>

<div class="py-5">
    <div class="container-fluid px-4 px-lg-5">
        <div class="row g-5">
            <div class="col-lg-5">
                <div class="modern-card p-5 h-100 glowing-btn">
                    <h3 class="fw-bold text-white mb-5">Connect With Us</h3>
                    
                    <div class="d-flex gap-4 mb-5">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 60px; height: 60px;">
                            <i class="bi bi-geo-alt fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-white mb-1">Location</h5>
                            <p class="text-muted mb-0">{{ $clinicSetting->address ?? 'Multan' }}</p>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-4 mb-5">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 60px; height: 60px;">
                            <i class="bi bi-telephone fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-white mb-1">Phone</h5>
                            <p class="text-muted mb-0">{{ $clinicSetting->phone ?? '+92 300 1234567' }}</p>
                            @if(isset($clinicSetting->landline))
                                <p class="text-muted small mb-0">{{ $clinicSetting->landline }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="d-flex gap-4 mb-5">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 60px; height: 60px;">
                            <i class="bi bi-envelope fs-3"></i>
                        </div>
                        <div>
                            <h5 class="fw-bold text-white mb-1">Email</h5>
                            <p class="text-muted mb-0">{{ $clinicSetting->contact_email ?? 'info@clinic.com' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-7">
                <div class="modern-card p-5 h-100">
                    <h3 class="fw-bold text-white mb-4">Send a Message</h3>
                    
                    @if(session('success'))
                        <div class="alert alert-success bg-success bg-opacity-10 text-success border-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('contact.store') }}" method="POST">
                        @csrf
                        <div class="row g-4">
                            <div class="col-md-6">
                                <input type="text" class="form-control modern-input" name="name" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" class="form-control modern-input" name="email" placeholder="Email Address" required>
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control modern-input" name="phone" placeholder="Phone Number">
                            </div>
                            <div class="col-md-6">
                                <input type="text" class="form-control modern-input" name="subject" placeholder="Subject" required>
                            </div>
                            <div class="col-12">
                                <textarea class="form-control modern-input" name="message" rows="6" placeholder="Your Message..." required></textarea>
                            </div>
                            <div class="col-12 mt-4">
                                <button type="submit" class="btn btn-primary px-5 py-3 rounded-3 fw-bold w-100 glowing-btn">Send Message</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
