@extends('themes.royal_purple.layout')

@section('title', 'Contact Us')

@section('content')
<div class="bg-white py-5 border-bottom border-4" style="border-color: var(--royal-gold) !important;">
    <div class="container py-4 text-center">
        <h1 class="display-4 fw-bold text-uppercase letter-spacing-1" style="color: var(--royal-dark);">Contact Us</h1>
    </div>
</div>
<div class="container py-5 my-5">
    <div class="row g-5">
        <div class="col-lg-4">
            <div class="royal-card p-5 h-100 text-center">
                <i class="bi bi-headset display-1 mb-4 d-block" style="color: var(--royal-gold);"></i>
                <h3 class="fw-bold mb-4" style="color: var(--royal-dark);">Support Center</h3>
                <p class="text-muted mb-4">{{ $clinicSetting->address ?? 'Multan' }}</p>
                <p class="text-muted mb-4">{{ $clinicSetting->phone ?? '+92 300 1234567' }}</p>
                <p class="text-muted mb-0">{{ $clinicSetting->contact_email ?? 'info@clinic.com' }}</p>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="royal-card p-5 h-100">
                <h3 class="fw-bold mb-4 text-uppercase letter-spacing-1" style="color: var(--royal-dark);">Inquiry Form</h3>
                @if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6"><input type="text" class="form-control rounded-1 py-2 border-2" name="name" placeholder="Name" required></div>
                        <div class="col-md-6"><input type="email" class="form-control rounded-1 py-2 border-2" name="email" placeholder="Email" required></div>
                        <div class="col-12"><input type="text" class="form-control rounded-1 py-2 border-2" name="subject" placeholder="Subject" required></div>
                        <div class="col-12"><textarea class="form-control rounded-1 py-2 border-2" name="message" rows="5" placeholder="Message" required></textarea></div>
                        <div class="col-12 mt-4 text-end"><button type="submit" class="btn-royal">Submit</button></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
