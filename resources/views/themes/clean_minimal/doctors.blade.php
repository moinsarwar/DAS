@extends('themes.clean_minimal.layout')

@section('title', 'Directory')

@section('content')
<div class="container py-5 mt-5">
    <h1 class="display-2 mb-5 pb-4 border-bottom" style="font-weight: 300; letter-spacing: -2px;">{{ $settings->doctors_title ?? 'Directory.' }}</h1>
    
    @forelse($categories as $category)
        <div class="mb-5 pb-5">
            <div class="text-center mb-4">
                @if(!empty($category->image_path))
                    <img src="{{ asset('storage/' . $category->image_path) }}" alt="{{ $category->name }}" style="width:60px;height:60px;object-fit:contain;" class="mb-3 d-block mx-auto">
                @elseif(!empty($category->icon))
                    <i class="bi {{ $category->icon }} display-4 mb-3 d-block mx-auto text-primary" style="opacity: 0.8;"></i>
                @endif
                <h3 class="fw-bold mb-5">{{ $category->name }}</h3>
            </div>
            <div class="row g-5">
                @forelse($category->doctors as $doctor)
                    <div class="col-lg-3 col-md-4">
                        <div class="text-center group">
                            <div class="bg-light mb-4 mx-auto overflow-hidden" style="width: 200px; height: 250px;">
                                @if($doctor->user->profile_photo)
                                    <img src="{{ asset('storage/' . $doctor->user->profile_photo) }}" class="w-100 h-100 object-fit-cover" style="filter: grayscale(100%);">
                                @else
                                    <div class="w-100 h-100 d-flex align-items-center justify-content-center">
                                        <i class="bi bi-person display-3 text-muted"></i>
                                    </div>
                                @endif
                            </div>
                            <h5 class="fw-light mb-1">{{ $doctor->user->name }}</h5>
                            <p class="text-muted small text-uppercase tracking-wider mb-3">{{ $category->name }}</p>
                            @auth
                                <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="text-dark text-decoration-none border-bottom border-dark pb-1 text-uppercase small">Book Slot</a>
                            @else
                                <a href="{{ route('login') }}" class="text-dark text-decoration-none border-bottom border-dark pb-1 text-uppercase small">Login to Book</a>
                            @endauth
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
