@extends('layouts.landing')

@section('title', 'Our Doctors')

@section('content')
<div class="container py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold display-5 text-dark">Meet Our Specialist Oncologists</h2>
        <p class="text-muted lead mx-auto" style="max-width: 600px;">
            Highly qualified and experienced consultants dedicated to your care.
        </p>
    </div>

    <div class="row g-4">
        @forelse($doctors as $doctor)
            <div class="col-md-4 col-lg-3">
                <div class="card border-0 shadow-sm h-100 p-3 hover-lift doctor-card">
                    <div class="card-body">
                         <div class="text-center mb-3">
                            @if($doctor->user->profile_photo)
                                 <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="rounded-circle border mb-3" style="width: 100px; height: 100px; object-fit: cover;" alt="Doctor">
                            @else
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 100px; height: 100px;">
                                    <i class="bi bi-person-fill fs-1"></i>
                                </div>
                            @endif
                            <h5 class="fw-bold mb-1 text-dark">{{ $doctor->user->name }}</h5>
                            <span class="badge bg-primary bg-opacity-10 text-primary mb-2">{{ $doctor->category->name ?? 'Specialist' }}</span>
                            <p class="text-muted small mb-0">{{ Str::limit($doctor->bio, 80) }}</p>
                        </div>
                        
                        <div class="border-top pt-3 mt-3">
                             <div class="d-flex align-items-center gap-2 mb-2 justify-content-center">
                                 <i class="bi bi-clock text-warning"></i>
                                 <small class="fw-bold text-uppercase" style="font-size: 0.7rem;">Schedule</small>
                            </div>
                            @if($doctor->schedules->count() > 0)
                                <ul class="list-unstyled mb-0 small text-center">
                                    @foreach($doctor->schedules as $schedule)
                                        <li class="text-muted mb-1">
                                            <span class="fw-medium text-dark">{{ $schedule->day }}:</span> 
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('h:i A') }}
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-center text-muted small fst-italic mb-0">Contact for appointments.</p>
                            @endif
                        </div>

                        <div class="mt-4 d-grid">
                            @auth
                                @if(auth()->user()->isPatient())
                                     <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn btn-outline-primary btn-sm rounded-pill">Book Appointment</a>
                                @else
                                     <button class="btn btn-outline-secondary btn-sm rounded-pill" disabled>Patient Only</button>
                                @endif
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm rounded-pill">Login to Book</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="bi bi-people text-muted display-1"></i>
                <h4 class="mt-3 text-muted">No Doctors Available at the Moment</h4>
            </div>
        @endforelse
    </div>
</div>
@endsection
