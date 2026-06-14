@extends('themes.soft_rose.layout')

@section('title', 'Welcome')

@section('content')
<div class="container py-5">
    <div class="row align-items-center g-5 min-vh-75">
        <div class="col-lg-6 text-center text-lg-start order-2 order-lg-1">
            <span class="badge bg-rose-light text-primary rounded-pill px-4 py-2 fs-6 mb-4 fw-bold">
                <i class="bi bi-heart-pulse-fill me-2"></i> {{ $clinicSetting->hero_badge ?? 'Gentle Care' }}
            </span>
            <h1 class="display-3 mb-4">
                {{ $clinicSetting->hero_title ?? 'Multan Cancer' }} <br>
                <span class="text-primary">{{ $clinicSetting->hero_subtitle ?? 'Clinic' }}</span>
            </h1>
            <p class="fs-5 text-muted mb-5 lh-lg" style="max-width: 500px; margin: 0 auto 0 0;">
                {{ $clinicSetting->hero_description ?? 'A welcoming environment dedicated to specialized oncology care with compassion and expertise.' }}
            </p>
            <div class="d-flex justify-content-center justify-content-lg-start gap-3">
                @auth
                    <a href="{{ route('patient.dashboard') }}" class="btn-rose text-decoration-none btn-lg px-5">My Portal</a>
                @else
                    <a href="{{ route('login') }}" class="btn-rose text-decoration-none btn-lg px-5">Book Appointment</a>
                @endauth
            </div>
        </div>
        <div class="col-lg-6 order-1 order-lg-2">
            <div class="position-relative">
                <div class="position-absolute bg-primary rounded-circle opacity-10" style="width: 400px; height: 400px; top: 10%; right: 10%; z-index: -1; filter: blur(50px);"></div>
                <div class="row g-4">
                    <div class="col-6 mt-5">
                        <div class="rose-card text-center">
                            <i class="bi bi-emoji-smile display-3 text-primary mb-3"></i>
                            <h5 class="fw-bold mb-0">Friendly Staff</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="rose-card text-center">
                            <i class="bi bi-shield-check display-3 text-primary mb-3"></i>
                            <h5 class="fw-bold mb-0">Safe Care</h5>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="rose-card text-center bg-rose-primary border-0" style="box-shadow: 0 15px 35px -10px rgba(244, 63, 94, 0.4);">
                            <i class="bi bi-star-fill display-3 mb-3 text-white"></i>
                            <h4 class="fw-bold text-white mb-0">Top Specialists</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="py-5 my-5">
    <div class="container">
        <div class="text-center mb-5 pb-3">
            <h2 class="display-5 mb-3">Meet Our Doctors</h2>
            <p class="text-muted fs-5">Gentle, expert care when you need it most.</p>
        </div>
        
        <div class="row g-4 justify-content-center">
            @forelse($doctors->take(3) as $doctor)
                <div class="col-lg-4 col-md-6">
                    <div class="rose-card text-center h-100">
                        <div class="position-relative d-inline-block mb-4">
                            @if($doctor->user->profile_photo)
                                <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="rounded-circle shadow" style="width: 130px; height: 130px; object-fit: cover; border: 5px solid white;">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center shadow" style="width: 130px; height: 130px; border: 5px solid white;">
                                    <i class="bi bi-person display-4 text-muted"></i>
                                </div>
                            @endif
                            <div class="position-absolute bottom-0 end-0 bg-primary rounded-circle p-2 text-white border border-2 border-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i class="bi bi-heart-fill"></i>
                            </div>
                        </div>
                        <h4 class="mb-2">{{ $doctor->user->name }}</h4>
                        <div class="badge bg-rose-light text-primary rounded-pill px-3 py-2 mb-3 fw-bold">{{ $category->name ?? 'Specialist' }}</div>
                        <p class="text-muted small mb-4 px-3">{{ Str::limit($doctor->qualification, 60) }}</p>
                        @auth
                            <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn-outline-rose w-100 text-decoration-none d-block">Schedule Visit</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-outline-rose w-100 text-decoration-none d-block">Login to Schedule</a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5"><p class="text-muted fw-bold">No doctors listed yet.</p></div>
            @endforelse
        </div>
        
        <div class="text-center mt-5 pt-3">
            <a href="{{ route('public.doctors') }}" class="btn-rose text-decoration-none fs-5">See All Specialists</a>
        </div>
    </div>
</div>
@endsection
