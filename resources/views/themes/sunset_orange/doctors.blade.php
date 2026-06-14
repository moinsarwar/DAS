@extends('themes.sunset_orange.layout')

@section('title', 'Specialists')

@section('content')
<div class="bg-white py-5 text-center">
    <div class="container py-4">
        <h1 class="display-4 fw-bold" style="color: var(--sunset-dark);">{{ $settings->doctors_title ?? 'Our Specialists' }}</h1>
        <p class="text-muted fs-5">{{ $settings->doctors_description ?? 'Expert consultants available for your care.' }}</p>
    </div>
</div>
<div class="container py-5 my-4">
    @forelse($categories as $category)
                <div class="mb-5 pb-5">
            <div class="text-center mb-4">
                @if(!empty($category->image_path))
                    <img src="{{ asset('storage/' . $category->image_path) }}" alt="{{ $category->name }}" style="width:60px;height:60px;object-fit:contain;" class="mb-3 d-block mx-auto">
                @elseif(!empty($category->icon))
                    <i class="bi {{ $category->icon }} display-4 mb-3 d-block mx-auto text-primary" style="opacity: 0.8;"></i>
                @endif
                <h3 class="fw-bold mb-5" style="color: var(--sunset-dark);">{{ $category->name }}</h3>
            </div>
            <div class="row g-4 justify-content-center">
                @forelse($category->doctors as $doctor)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="sunset-card p-4 text-center h-100 d-flex flex-column hover-shadow">
                            @if($doctor->user->profile_photo)
                                <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="rounded-circle mx-auto mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px;">
                                    <i class="bi bi-person fs-1 text-muted"></i>
                                </div>
                            @endif
                            <h5 class="fw-bold mb-1" style="color: var(--sunset-dark);">{{ $doctor->user->name }}</h5>
                            <p class="text-primary small fw-bold">{{ $category->name }}</p>
                            <div class="mt-auto pt-3">
                                @auth
                                    <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn btn-sm btn-outline-primary w-100 rounded-pill">Book</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-dark w-100 rounded-pill">Login</a>
                                @endauth
                            </div>
                        </div>
                    </div>
                @empty
                @endforelse
            </div>
        </div>
    @empty
    @endforelse
</div>
@endsection
