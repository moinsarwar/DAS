@extends('themes.sunset_orange.layout')

@section('title', 'Contact Us')

@section('content')
<div class="bg-white py-5 text-center">
    <div class="container py-4">
        <h1 class="display-4 fw-bold" style="color: var(--sunset-dark);">Contact <span class="text-primary">Us</span></h1>
    </div>
</div>
<div class="container py-5 mb-5">
    <div class="sunset-card p-4 p-lg-5">
        <div class="row g-5">
            <div class="col-lg-5">
                <h3 class="fw-bold mb-4" style="color: var(--sunset-dark);">Get In Touch</h3>
                <div class="d-flex flex-column gap-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white text-primary shadow-sm rounded-circle p-3"><i class="bi bi-geo-alt fs-4"></i></div>
                        <div><h6 class="fw-bold mb-0">Location</h6><p class="text-muted mb-0">{{ $clinicSetting->address ?? 'Multan' }}</p></div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white text-primary shadow-sm rounded-circle p-3"><i class="bi bi-telephone fs-4"></i></div>
                        <div><h6 class="fw-bold mb-0">Phone</h6><p class="text-muted mb-0">{{ $clinicSetting->phone ?? '+92 300 1234567' }}</p></div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white text-primary shadow-sm rounded-circle p-3"><i class="bi bi-envelope fs-4"></i></div>
                        <div><h6 class="fw-bold mb-0">Email</h6><p class="text-muted mb-0">{{ $clinicSetting->contact_email ?? 'info@clinic.com' }}</p></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                @if(session('success')) <div class="alert alert-success rounded-4">{{ session('success') }}</div> @endif
                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6"><input type="text" class="form-control rounded-pill bg-light border-0 py-3 px-4" name="name" placeholder="Name" required></div>
                        <div class="col-md-6"><input type="email" class="form-control rounded-pill bg-light border-0 py-3 px-4" name="email" placeholder="Email" required></div>
                        <div class="col-12"><input type="text" class="form-control rounded-pill bg-light border-0 py-3 px-4" name="subject" placeholder="Subject" required></div>
                        <div class="col-12"><textarea class="form-control rounded-4 bg-light border-0 py-3 px-4" name="message" rows="5" placeholder="Message" required></textarea></div>
                        <div class="col-12 mt-3"><button type="submit" class="btn-sunset w-100">Send Message</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
