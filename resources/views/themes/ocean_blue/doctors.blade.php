@extends('themes.ocean_blue.layout')

@section('title', 'Directory of Specialists')

@section('content')
<div class="bg-primary text-white py-5">
    <div class="container py-5 text-center">
        <h1 class="display-4 fw-bold mb-3 text-uppercase letter-spacing-1">{{ $settings->doctors_title ?? 'Directory of Specialists' }}</h1>
        <p class="lead mb-0 mx-auto opacity-75" style="max-width: 700px;">{{ $settings->doctors_description ?? 'Expert consultants available for your care.' }}</p>
    </div>
</div>

<div class="container py-5 my-5">
    @forelse($categories as $category)
        <div class="mb-5 pb-5 border-bottom">
            <div class="d-flex align-items-center gap-4 mb-5">
                <div class="bg-light d-flex align-items-center justify-content-center border border-primary p-3">
                    @if($category->icon)
                        <i class="{{ $category->icon }} display-6 text-primary"></i>
                    @elseif($category->image_path)
                        <img src="{{ asset('storage/' . $category->image_path) }}" alt="icon" style="width: 50px; height: 50px; object-fit: contain;">
                    @else
                        <i class="bi bi-diagram-3 display-6 text-primary"></i>
                    @endif
                </div>
                <div>
                    <h2 class="fw-bold text-dark text-uppercase mb-1">{{ $category->name }}</h2>
                    @if($category->description)
                        <p class="text-muted mb-0">{{ $category->description }}</p>
                    @endif
                </div>
            </div>

            <div class="row g-4">
                @forelse($category->doctors as $doctor)
                    <div class="col-lg-3 col-md-6">
                        <div class="border bg-white h-100 d-flex flex-column hover-shadow transition">
                            <div class="position-relative">
                                @if($doctor->user->profile_photo)
                                     <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="w-100" style="height: 250px; object-fit: cover; object-position: top;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center w-100" style="height: 250px;">
                                        <i class="bi bi-person text-secondary display-1"></i>
                                    </div>
                                @endif
                                <div class="position-absolute bottom-0 start-0 w-100 bg-primary bg-opacity-75 p-2 text-center">
                                    <span class="text-white text-uppercase small fw-bold tracking-wider">{{ $category->name }}</span>
                                </div>
                            </div>
                            
                            <div class="p-4 d-flex flex-column flex-grow-1">
                                <h4 class="fw-bold text-dark mb-3 text-center">{{ $doctor->user->name }}</h4>
                                <p class="text-muted small text-center mb-4 flex-grow-1">{{ Str::limit($doctor->qualification, 60) }}</p>
                                
                                <div class="mt-auto d-grid">
                                    @auth
                                        <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn btn-outline-primary text-uppercase fw-bold border-2">Book Slot</a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-outline-dark text-uppercase fw-bold border-2">Login</a>
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12"><p class="text-muted">No specialists found in this department.</p></div>
                @endforelse
            </div>
        </div>
    @empty
        <div class="text-center py-5 bg-light border">
            <h4 class="text-muted text-uppercase tracking-wider">No Directory Found</h4>
        </div>
    @endforelse
</div>
@endsection
