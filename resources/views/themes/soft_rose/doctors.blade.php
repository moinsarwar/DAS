@extends('themes.soft_rose.layout')

@section('title', 'Specialists')

@section('content')
<div class="container py-5 mt-4">
    <div class="text-center mb-5 pb-4">
        <h1 class="display-3 mb-3">{{ $settings->doctors_title ?? 'Our Specialists' }}</h1>
        <p class="text-muted fs-5">{{ $settings->doctors_description ?? 'Expert consultants available for your care.' }}</p>
    </div>
    
    @forelse($categories as $category)
                <div class="mb-5 pb-5">
            <div class="text-center mb-4">
                @if(!empty($category->image_path))
                    <img src="{{ asset('storage/' . $category->image_path) }}" alt="{{ $category->name }}" style="width:60px;height:60px;object-fit:contain;" class="mb-3 d-block mx-auto">
                @elseif(!empty($category->icon))
                    <i class="bi {{ $category->icon }} display-4 mb-3 d-block mx-auto text-primary" style="opacity: 0.8;"></i>
                @endif
                <h3 class="fw-bold mb-5" >{{ $category->name }}</h3>
            </div>
            <div class="row g-4 justify-content-center">
                @forelse($category->doctors as $doctor)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="rose-card text-center h-100 d-flex flex-column">
                            @if($doctor->user->profile_photo)
                                <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="rounded-circle shadow-sm mx-auto mb-4 border border-3 border-white" style="width: 110px; height: 110px; object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-4 border border-3 border-white shadow-sm" style="width: 110px; height: 110px;">
                                    <i class="bi bi-person display-5 text-muted"></i>
                                </div>
                            @endif
                            <h5 class="mb-2">{{ $doctor->user->name }}</h5>
                            <div class="badge bg-rose-light text-primary rounded-pill px-3 py-1 mb-3 mx-auto fw-bold">{{ $category->name }}</div>
                            <div class="mt-auto pt-3">
                                @auth
                                    <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn-outline-rose w-100 text-decoration-none d-block py-2">Book</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn-outline-rose w-100 text-decoration-none d-block py-2">Login</a>
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
