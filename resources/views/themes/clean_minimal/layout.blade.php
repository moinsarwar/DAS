<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $clinicSetting->clinic_name ?? config('app.name') }} | @yield('title')</title>
    @if(isset($clinicSetting) && $clinicSetting->favicon_path)<link rel="icon" href="{{ asset('storage/' . $clinicSetting->favicon_path) }}">@endif
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
            @php
        $fontFamily = $clinicSetting->font_family ?? 'Inter';
        $fontImport = match($fontFamily) {
            'Roboto' => 'https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap',
            'Poppins' => 'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap',
            "'Playfair Display'" => 'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap',
            'Montserrat' => 'https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap',
            'Lora' => 'https://fonts.googleapis.com/css2?family=Lora:wght@400;500;600;700&display=swap',
            'Nunito' => 'https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap',
            'Raleway' => 'https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;500;600;700&display=swap',
            'Merriweather' => 'https://fonts.googleapis.com/css2?family=Merriweather:wght@300;400;700;900&display=swap',
            'Ubuntu' => 'https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap',
            default => 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap'
        };
    @endphp
    <link href="{{ $fontImport }}" rel="stylesheet">
    <style>
        :root { --theme-font: {!! $fontFamily !!}, sans-serif; --min-dark: #000000; --min-gray: #f8f9fa; --min-muted: #6c757d; }
        body { font-family: var(--theme-font); background-color: #ffffff; color: var(--min-dark); display: flex; min-height: 100vh; flex-direction: column; font-weight: 300; }
        h1, h2, h3, h4, h5, h6 { font-weight: 400; letter-spacing: -1px; }
        .min-nav { padding: 2rem 0; background: rgba(255,255,255,0.9); backdrop-filter: blur(10px); }
        .min-nav .nav-link { color: var(--min-dark); font-weight: 400; margin: 0 1rem; position: relative; }
        .min-nav .nav-link::after { content: ''; position: absolute; width: 0; height: 1px; background: var(--min-dark); bottom: 0; left: 0; transition: width 0.3s; }
        .min-nav .nav-link:hover::after, .min-nav .nav-link.active::after { width: 100%; }
        .btn-min { background: var(--min-dark); color: white; border-radius: 0; padding: 0.8rem 2rem; border: 1px solid var(--min-dark); transition: all 0.3s; font-weight: 400; letter-spacing: 1px; text-transform: uppercase; font-size: 0.8rem; }
        .btn-min:hover { background: transparent; color: var(--min-dark); }
        .min-footer { padding: 6rem 0 3rem; margin-top: auto; border-top: 1px solid #eee; }
        .text-primary { color: var(--min-dark) !important; }
        .bg-primary { background-color: var(--min-dark) !important; }
        .btn-primary { background-color: var(--min-dark); border-color: var(--min-dark); border-radius: 0; }
        .btn-outline-primary { color: var(--min-dark); border-color: var(--min-dark); border-radius: 0; }
        .btn-outline-primary:hover { background-color: var(--min-dark); color: white; }
        .form-control { border: 0; border-bottom: 1px solid #ccc; border-radius: 0; padding: 1rem 0; background: transparent; }
        .form-control:focus { box-shadow: none; border-color: var(--min-dark); background: transparent; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg min-nav sticky-top">
        <div class="container">
            <a class="navbar-brand text-dark fs-3 fw-light" href="{{ url('/') }}">
                @if(isset($clinicSetting) && $clinicSetting->logo_path)
                    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo" style="height: 40px; filter: grayscale(100%);">
                @else
                    {{ $clinicSetting->clinic_name ?? 'Clinic' }}
                @endif
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#minNav">
                <i class="bi bi-list fs-2 text-dark"></i>
            </button>
            <div class="collapse navbar-collapse" id="minNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-5">
                    <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.doctors') ? 'active' : '' }}" href="{{ route('public.doctors') }}">Directory</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}" href="{{ route('public.about') }}">Studio</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}" href="{{ route('public.contact') }}">Connect</a></li>
                </ul>
                <div class="d-flex gap-3">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDoctor() ? route('doctor.dashboard') : (auth()->user()->isReceptionist() ? route('receptionist.dashboard') : route('patient.dashboard'))) }}" class="btn-min text-decoration-none">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-dark text-decoration-none fw-light d-flex align-items-center">Sign In</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <main class="flex-grow-1">@yield('content')</main>
    <footer class="min-footer">
        <div class="container">
            <div class="row g-5 mb-5 justify-content-between">
                <div class="col-lg-5">
                    <div class="d-flex align-items-center mb-4">@if(isset($clinicSetting) && $clinicSetting->logo_path)
    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo" style="height: 40px; border-radius: 8px;" class="me-2 bg-white p-1">
@endif<h4 class="fw-light mb-0 text-uppercase tracking-wider" style="letter-spacing: 2px;">{{ $clinicSetting->clinic_name ?? 'Clinic' }}</h4></div>
                    <p class="text-muted fw-light lh-lg pe-lg-5 mb-4" style="max-width: 450px;">{{ $clinicSetting->about_short ?? 'Pioneering health with a clean, transparent, and ultra-modern approach to patient care.' }}</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-dark fs-5 hover-opacity"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-dark fs-5 hover-opacity"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-dark fs-5 hover-opacity"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h6 class="text-uppercase tracking-wider fw-bold small mb-4">Index</h6>
                    <ul class="list-unstyled">
                        <li class="mb-3"><a href="{{ url('/') }}" class="text-muted text-decoration-none hover-dark fw-light">Home</a></li>
                        <li class="mb-3"><a href="{{ route('public.doctors') }}" class="text-muted text-decoration-none hover-dark fw-light">Directory</a></li>
                        <li class="mb-3"><a href="{{ route('public.about') }}" class="text-muted text-decoration-none hover-dark fw-light">Studio</a></li>
                        <li class="mb-3"><a href="{{ route('public.contact') }}" class="text-muted text-decoration-none hover-dark fw-light">Connect</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-4">
                    <h6 class="text-uppercase tracking-wider fw-bold small mb-4">Information</h6>
                    <ul class="list-unstyled">
                        <li class="mb-3 fw-light text-muted"><span class="d-block text-dark fw-normal small text-uppercase tracking-wider mb-1">Address</span> {{ $clinicSetting->address ?? 'Multan' }}</li>
                        <li class="mb-3 fw-light text-muted"><span class="d-block text-dark fw-normal small text-uppercase tracking-wider mb-1">Contact</span> {{ $clinicSetting->phone ?? '+92 300 1234567' }}</li>
                        <li class="mb-3 fw-light text-muted"><span class="d-block text-dark fw-normal small text-uppercase tracking-wider mb-1">Email</span> {{ $clinicSetting->contact_email ?? 'info@clinic.com' }}</li>
                    </ul>
                </div>
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center pt-4 border-top">
                <p class="small text-muted mb-0 fw-light">&copy; {{ date('Y') }} {{ $clinicSetting->clinic_name ?? 'Clinic' }}. All rights reserved.</p>
                
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @include('layouts.ai_bot')
</body>
</html>
