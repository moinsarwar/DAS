@extends('layouts.landing')

@section('title', 'Contact Us')

@section('content')
    <div class="bg-light py-5">
        <div class="container text-center">
            <h1 class="display-4 fw-bold mb-3">Get in Touch</h1>
            <p class="lead mb-0 text-muted">We are here to answer any questions you may have about our services.</p>
        </div>
    </div>

    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-5">
                <h3 class="fw-bold mb-4">Contact Information</h3>
                <p class="text-muted mb-5">
                    Visit our clinic during working hours or reach out to us via phone or email for appointments and
                    inquiries.
                </p>

                <div class="d-flex gap-3 mb-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 50px; height: 50px;">
                        <i class="bi bi-geo-alt-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Clinic Address</h5>
                        <p class="text-muted mb-0">{{ $clinicSetting->address ?? 'Nishtar Road, Multan, Pakistan' }}</p>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 50px; height: 50px;">
                        <i class="bi bi-telephone-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Phone Number</h5>
                        <p class="text-muted mb-0">{{ $clinicSetting->phone ?? '+92 300 1234567' }}</p>
                        @if(isset($clinicSetting->landline))
                            <p class="text-muted mb-0 small">{{ $clinicSetting->landline }} (Landline)</p>
                        @endif
                    </div>
                </div>

                <div class="d-flex gap-3 mb-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 50px; height: 50px;">
                        <i class="bi bi-envelope-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Email Address</h5>
                        <p class="text-muted mb-0">{{ $clinicSetting->contact_email ?? 'info@multancancerclinic.com' }}</p>
                    </div>
                </div>

                <div class="d-flex gap-3 mb-4">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                        style="width: 50px; height: 50px;">
                        <i class="bi bi-clock-fill fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">Working Hours</h5>
                        <p class="text-muted mb-0">Mon - Sat: 02:00 PM - 08:00 PM</p>
                        <p class="text-danger mb-0 small">Sunday: Closed</p>
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                <div class="card border-0 shadow-sm p-4">
                    <div class="card-body">
                        <h4 class="fw-bold mb-4">Send us a Message</h4>

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form action="{{ route('contact.store') }}" method="POST">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Your Name <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="John Doe"
                                        required>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email Address <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="john@example.com" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Phone Number (Optional)</label>
                                    <input type="text" class="form-control" name="phone" id="phone"
                                        placeholder="+92 300 1234567">
                                </div>
                                <div class="col-12">
                                    <label for="subject" class="form-label">Subject <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" name="subject" id="subject"
                                        placeholder="Appointment Inquiry" required>
                                </div>
                                <div class="col-12">
                                    <label for="message" class="form-label">Message <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" name="message" id="message" rows="5"
                                        placeholder="How can we help you?" required></textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold">Send Message</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection