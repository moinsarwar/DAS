@extends('themes.clean_minimal.layout')

@section('title', 'Contact Us')

@section('content')
<div class="container py-5 mt-5">
    <div class="row g-5">
        <div class="col-lg-5">
            <h1 class="display-2 mb-5" style="font-weight: 300; letter-spacing: -2px;">Connect.</h1>
            <div class="mb-5">
                <h6 class="text-uppercase tracking-wider text-muted small mb-3">Address</h6>
                <p class="fs-5 fw-light">{{ $clinicSetting->address ?? 'Multan' }}</p>
            </div>
            <div class="mb-5">
                <h6 class="text-uppercase tracking-wider text-muted small mb-3">Contact</h6>
                <p class="fs-5 fw-light mb-1">{{ $clinicSetting->phone ?? '+92 300 1234567' }}</p>
                <p class="fs-5 fw-light">{{ $clinicSetting->contact_email ?? 'info@clinic.com' }}</p>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="bg-light p-5">
                @if(session('success')) <div class="alert alert-success border-0 rounded-0 bg-transparent text-success border-bottom border-success mb-5 px-0">{{ session('success') }}</div> @endif
                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6"><input type="text" class="form-control" name="name" placeholder="Name" required></div>
                        <div class="col-md-6"><input type="email" class="form-control" name="email" placeholder="Email" required></div>
                        <div class="col-12"><input type="text" class="form-control" name="subject" placeholder="Subject" required></div>
                        <div class="col-12"><textarea class="form-control" name="message" rows="4" placeholder="Message" required></textarea></div>
                        <div class="col-12 mt-5 text-end"><button type="submit" class="btn-min">Submit</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
