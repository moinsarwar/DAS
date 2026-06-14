@extends('themes.nature_green.layout')

@section('title', 'Contact Us')

@section('content')
<div class="bg-light py-5 text-center border-bottom border-success border-opacity-10">
    <div class="container py-4">
        <h1 class="display-4 fw-bold" style="color: var(--primary-dark);">Get In <span style="color: var(--primary-green);">Touch</span></h1>
        <p class="lead text-muted mx-auto mb-0" style="max-width: 600px;">Reach out to our clinic for inquiries, support, or feedback. We're here to help.</p>
    </div>
</div>

<div class="container py-5 my-4">
    <div class="row g-5">
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-4">
                <div class="nature-card p-4 text-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px; background-color: var(--primary-light); color: var(--primary-dark);">
                        <i class="bi bi-geo-alt fs-3"></i>
                    </div>
                    <h5 class="fw-bold" style="color: var(--primary-dark);">Visit Us</h5>
                    <p class="text-muted mb-0">{{ $clinicSetting->address ?? 'Multan' }}</p>
                </div>
                
                <div class="nature-card p-4 text-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px; background-color: var(--primary-light); color: var(--primary-dark);">
                        <i class="bi bi-telephone fs-3"></i>
                    </div>
                    <h5 class="fw-bold" style="color: var(--primary-dark);">Call Us</h5>
                    <p class="text-muted mb-0">{{ $clinicSetting->phone ?? '+92 300 1234567' }}</p>
                    @if(isset($clinicSetting->landline))
                        <p class="text-muted small mb-0">{{ $clinicSetting->landline }}</p>
                    @endif
                </div>
                
                <div class="nature-card p-4 text-center">
                    <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px; background-color: var(--primary-light); color: var(--primary-dark);">
                        <i class="bi bi-envelope fs-3"></i>
                    </div>
                    <h5 class="fw-bold" style="color: var(--primary-dark);">Email Us</h5>
                    <p class="text-muted mb-0">{{ $clinicSetting->contact_email ?? 'info@clinic.com' }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="nature-card p-4 p-md-5 h-100">
                <h3 class="fw-bold mb-4" style="color: var(--primary-dark);">Send us a Message</h3>
                
                @if(session('success'))
                    <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success rounded-3 mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: var(--primary-dark);">Your Name</label>
                            <input type="text" class="form-control rounded-pill bg-light border-0 py-3 px-4" name="name" placeholder="John Doe" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: var(--primary-dark);">Email Address</label>
                            <input type="email" class="form-control rounded-pill bg-light border-0 py-3 px-4" name="email" placeholder="john@example.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: var(--primary-dark);">Phone Number</label>
                            <input type="text" class="form-control rounded-pill bg-light border-0 py-3 px-4" name="phone" placeholder="Optional">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold" style="color: var(--primary-dark);">Subject</label>
                            <input type="text" class="form-control rounded-pill bg-light border-0 py-3 px-4" name="subject" placeholder="Inquiry" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-bold" style="color: var(--primary-dark);">Your Message</label>
                            <textarea class="form-control rounded-4 bg-light border-0 py-3 px-4" name="message" rows="5" placeholder="How can we help?" required></textarea>
                        </div>
                        <div class="col-12 mt-4 text-end">
                            <button type="submit" class="btn btn-nature px-5 py-3 shadow-sm rounded-pill fw-bold">Send Message</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
