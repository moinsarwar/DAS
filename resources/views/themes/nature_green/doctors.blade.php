@extends('themes.nature_green.layout')

@section('title', 'Specialists')

@section('content')
<div class="bg-light py-5 text-center border-bottom border-success border-opacity-10">
    <div class="container py-4">
        <h1 class="display-4 fw-bold" style="color: var(--primary-dark);">{{ $settings->doctors_title ?? 'Our Specialists' }}</h1>
        <p class="lead text-muted mx-auto mb-0" style="max-width: 600px;">{{ $settings->doctors_description ?? 'Find the right consultant for your care.' }}</p>
    </div>
</div>

<div class="container py-5 my-4">
    @forelse($categories as $category)
        <div class="mb-5 pb-4">
            <div class="text-center mb-5">
                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3 shadow-sm" style="width: 70px; height: 70px; background-color: var(--primary-light); color: var(--primary-dark);">
                    @if($category->icon)
                        <i class="{{ $category->icon }} fs-3"></i>
                    @elseif($category->image_path)
                        <img src="{{ asset('storage/' . $category->image_path) }}" alt="icon" style="width: 35px; height: 35px; object-fit: contain;">
                    @else
                        <i class="bi bi-person-badge fs-3"></i>
                    @endif
                </div>
                <h2 class="fw-bold" style="color: var(--primary-dark);">{{ $category->name }}</h2>
                @if($category->description)
                    <p class="text-muted mx-auto" style="max-width: 500px;">{{ $category->description }}</p>
                @endif
            </div>

            <div class="row g-4 justify-content-center">
                @forelse($category->doctors as $doctor)
                    <div class="col-lg-4 col-md-6">
                        <div class="nature-card h-100 p-4 text-center hover-shadow transition">
                            <div class="mb-4">
                                @if($doctor->user->profile_photo)
                                     <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="rounded-circle shadow-sm" style="width: 100px; height: 100px; object-fit: cover; border: 3px solid white; box-shadow: 0 0 0 3px var(--primary-light);">
                                @else
                                    <div class="rounded-circle shadow-sm d-flex align-items-center justify-content-center mx-auto" style="width: 100px; height: 100px; background-color: var(--primary-light); color: var(--primary-dark);">
                                        <i class="bi bi-person-heart fs-1"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <h4 class="fw-bold mb-1" style="color: var(--primary-dark);">{{ $doctor->user->name }}</h4>
                            <p class="text-muted small mb-3">{{ Str::limit($doctor->qualification, 60) }}</p>
                            
                            <div class="mt-auto pt-3 border-top border-light">
                                @auth
                                    <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn btn-outline-primary rounded-pill w-100 fw-bold">Book Slot</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill w-100 fw-bold">Login to Book</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted"><p>No specialists available in this category yet.</p></div>
                @endforelse
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <h4 class="text-muted">No categories found.</h4>
        </div>
    @endforelse
</div>
@endsection
