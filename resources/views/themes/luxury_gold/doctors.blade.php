@extends('themes.luxury_gold.layout')

@section('title', 'Directory')

@section('content')
<div class="container py-5 mt-5 text-center">
    <h1 class="display-3 mb-4">{{ $settings->doctors_title ?? 'Directory' }}</h1>
    <div style="width: 50px; height: 1px; background: var(--lux-gold); margin: 0 auto 3rem;"></div>
    <p class="text-muted fs-5 mb-5" style="font-weight: 300;">{{ $settings->doctors_description ?? 'Expert consultants available for your care.' }}</p>
    
    @forelse($categories as $category)
        <div class="mb-5 pb-5">
                    <div class="text-center mb-4">
                            @if(!empty($category->image_path))
                    <img src="{{ asset('storage/' . $category->image_path) }}" alt="{{ $category->name }}" style="width:60px;height:60px;object-fit:contain;" class="mb-3 d-block mx-auto">
                @elseif(!empty($category->icon))
                    <i class="bi {{ $category->icon }} display-4 mb-3 d-block mx-auto text-primary" style="opacity: 0.8;"></i>
                @endif
                    <div class="text-center mb-4">
                            @if(!empty($category->image_path))
                    <img src="{{ asset('storage/' . $category->image_path) }}" alt="{{ $category->name }}" style="width:60px;height:60px;object-fit:contain;" class="mb-3 d-block mx-auto">
                @elseif(!empty($category->icon))
                    <i class="bi {{ $category->icon }} display-4 mb-3 d-block mx-auto text-primary" style="opacity: 0.8;"></i>
                @endif
            <h3 class="fw-bold mb-5">{{ $category->name }}</h3>
        </div>
        </div>
            <div class="row g-5 justify-content-center">
                @forelse($category->doctors as $doctor)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="lux-card p-4 h-100 text-center d-flex flex-column">
                            <div class="mb-4 d-inline-block position-relative p-2 mx-auto" style="border: 1px solid var(--lux-gold);">
                                @if($doctor->user->profile_photo)
                                    <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="w-100 h-100" style="width: 120px !important; height: 150px !important; object-fit: cover; filter: sepia(0.3) grayscale(0.5);">
                                @else
                                    <div class="d-flex align-items-center justify-content-center" style="width: 120px; height: 150px; background: var(--lux-dark);">
                                        <i class="bi bi-person display-3" style="color: var(--lux-gray);"></i>
                                    </div>
                                @endif
                            </div>
                            <h5 class="mb-2">{{ $doctor->user->name }}</h5>
                            <p class="small mb-4" style="color: var(--lux-gold); letter-spacing: 1px; text-transform: uppercase;">{{ $category->name }}</p>
                            <div class="mt-auto">
                                @auth
                                    <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn-lux w-100 text-decoration-none">Reserve</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn-lux w-100 text-decoration-none">Inquire</a>
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
