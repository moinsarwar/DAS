@extends('themes.ocean_blue.layout')

@section('title', 'Contact Us')

@section('content')
<div class="bg-primary text-white py-5">
    <div class="container py-5 text-center">
        <h1 class="display-4 fw-bold mb-3 text-uppercase letter-spacing-1">Contact Clinic</h1>
        <p class="lead mb-0 mx-auto opacity-75" style="max-width: 700px;">Our dedicated support staff is available to assist you with your inquiries.</p>
    </div>
</div>

<div class="container py-5 my-5">
    <div class="row g-0 border shadow-sm">
        <div class="col-lg-5 bg-dark text-white p-5">
            <h3 class="fw-bold text-uppercase mb-5 border-bottom border-secondary pb-3">Get in Touch</h3>
            
            <div class="d-flex gap-4 mb-4">
                <i class="bi bi-geo-alt display-6 text-primary"></i>
                <div>
                    <h6 class="text-uppercase text-muted fw-bold mb-1">Office Location</h6>
                    <p class="mb-0 fs-5">{{ $clinicSetting->address ?? 'Multan' }}</p>
                </div>
            </div>
            
            <div class="d-flex gap-4 mb-4">
                <i class="bi bi-telephone display-6 text-primary"></i>
                <div>
                    <h6 class="text-uppercase text-muted fw-bold mb-1">Phone Number</h6>
                    <p class="mb-0 fs-5">{{ $clinicSetting->phone ?? '+92 300 1234567' }}</p>
                    @if(isset($clinicSetting->landline))
                        <p class="text-muted small mb-0">{{ $clinicSetting->landline }}</p>
                    @endif
                </div>
            </div>
            
            <div class="d-flex gap-4 mb-4">
                <i class="bi bi-envelope-at display-6 text-primary"></i>
                <div>
                    <h6 class="text-uppercase text-muted fw-bold mb-1">Email Support</h6>
                    <p class="mb-0 fs-5">{{ $clinicSetting->contact_email ?? 'info@clinic.com' }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-lg-7 bg-white p-5">
            <h3 class="fw-bold text-dark text-uppercase mb-4">Send an Inquiry</h3>
            
            @if(session('success'))
                <div class="alert alert-success border-0 bg-success bg-opacity-10 text-success fw-bold mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contact.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-uppercase small fw-bold text-muted">Full Name</label>
                        <input type="text" class="form-control border-secondary border-opacity-50 rounded-0 py-2" name="name" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-uppercase small fw-bold text-muted">Email Address</label>
                        <input type="email" class="form-control border-secondary border-opacity-50 rounded-0 py-2" name="email" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-uppercase small fw-bold text-muted">Phone Number</label>
                        <input type="text" class="form-control border-secondary border-opacity-50 rounded-0 py-2" name="phone">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-uppercase small fw-bold text-muted">Subject</label>
                        <input type="text" class="form-control border-secondary border-opacity-50 rounded-0 py-2" name="subject" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-uppercase small fw-bold text-muted">Message Detail</label>
                        <textarea class="form-control border-secondary border-opacity-50 rounded-0 py-2" name="message" rows="6" required></textarea>
                    </div>
                    <div class="col-12 mt-4 text-end">
                        <button type="submit" class="btn btn-primary px-5 py-3 text-uppercase fw-bold rounded-0">Submit Message</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
