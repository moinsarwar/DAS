@extends('themes.royal_purple.layout')

@section('title', 'Specialists')

@section('content')
<div class="bg-white py-5 border-bottom border-4" style="border-color: var(--royal-gold) !important;">
    <div class="container py-4 text-center">
        <h1 class="display-4 fw-bold text-uppercase letter-spacing-1" style="color: var(--royal-dark);">{{ $settings->doctors_title ?? 'Directory' }}</h1>
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
                <h3 class="fw-bold mb-5" style="color: var(--royal-dark);">{{ $category->name }}</h3>
            </div>
            <div class="row g-4">
                @forelse($category->doctors as $doctor)
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="royal-card p-4 text-center h-100 d-flex flex-column">
                            @if($doctor->user->profile_photo)
                                <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="rounded-circle mx-auto mb-3 border border-3" style="width: 100px; height: 100px; object-fit: cover; border-color: var(--royal-gold) !important;">
                            @else
                                <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center border border-3" style="width: 100px; height: 100px; border-color: var(--royal-gold) !important;">
                                    <i class="bi bi-person fs-1" style="color: var(--royal-dark);"></i>
                                </div>
                            @endif
                            <h5 class="fw-bold mb-1" style="color: var(--royal-dark);">{{ $doctor->user->name }}</h5>
                            <p class="small text-muted text-uppercase letter-spacing-1 mb-3">{{ $category->name }}</p>
                            <div class="mt-auto">
                                @auth
                                    <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn btn-sm btn-outline-primary w-100 rounded-1 text-uppercase fw-bold border-2">Book</a>
                                @else
                                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-dark w-100 rounded-1 text-uppercase fw-bold border-2">Login</a>
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
