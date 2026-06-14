@extends('themes.luxury_gold.layout')

@section('title', 'Contact Us')

@section('content')
<div class="container py-5 mt-5">
    <div class="row g-5 justify-content-center">
        <div class="col-lg-10 text-center mb-5">
            <h1 class="display-3 mb-4">Contact</h1>
            <div style="width: 50px; height: 1px; background: var(--lux-gold); margin: 0 auto;"></div>
        </div>
        
        <div class="col-lg-4">
            <div class="lux-card p-5 h-100 text-center">
                <i class="bi bi-geo-alt display-4 d-block mb-4" style="color: var(--lux-gold);"></i>
                <h4 class="mb-4">Location</h4>
                <p class="text-muted" style="font-weight: 300;">{{ $clinicSetting->address ?? 'Multan' }}</p>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="lux-card p-5 h-100 text-center">
                <i class="bi bi-telephone display-4 d-block mb-4" style="color: var(--lux-gold);"></i>
                <h4 class="mb-4">Inquiries</h4>
                <p class="text-muted mb-1" style="font-weight: 300;">{{ $clinicSetting->phone ?? '+92 300 1234567' }}</p>
                <p class="text-muted" style="font-weight: 300;">{{ $clinicSetting->contact_email ?? 'info@clinic.com' }}</p>
            </div>
        </div>
        
        <div class="col-lg-8 mt-5">
            <div class="lux-card p-5">
                <h3 class="mb-5 text-center">Send a Message</h3>
                @if(session('success')) <div class="alert alert-success bg-transparent border-0 border-start border-4 border-success text-success rounded-0 mb-5">{{ session('success') }}</div> @endif
                <form action="{{ route('contact.store') }}" method="POST">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <input type="text" class="form-control border-0 border-bottom border-secondary bg-transparent rounded-0 text-white py-3 px-0 shadow-none" name="name" placeholder="Name" required>
                        </div>
                        <div class="col-md-6">
                            <input type="email" class="form-control border-0 border-bottom border-secondary bg-transparent rounded-0 text-white py-3 px-0 shadow-none" name="email" placeholder="Email Address" required>
                        </div>
                        <div class="col-12">
                            <input type="text" class="form-control border-0 border-bottom border-secondary bg-transparent rounded-0 text-white py-3 px-0 shadow-none" name="subject" placeholder="Subject" required>
                        </div>
                        <div class="col-12">
                            <textarea class="form-control border-0 border-bottom border-secondary bg-transparent rounded-0 text-white py-3 px-0 shadow-none" name="message" rows="4" placeholder="Your Message" required></textarea>
                        </div>
                        <div class="col-12 mt-5 text-center">
                            <button type="submit" class="btn-lux-filled w-100 py-3">Submit Inquiry</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
