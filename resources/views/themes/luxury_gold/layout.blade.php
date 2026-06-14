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
        $headerFont = $fontFamily == "'Playfair Display'" ? "'Playfair Display', serif" : "$fontFamily, sans-serif";
        $bodyFont = $fontFamily == "'Playfair Display'" ? "'Lato', sans-serif" : "$fontFamily, sans-serif";
    @endphp
    <link href="{{ $fontImport }}" rel="stylesheet">
    <style>
        :root { --lux-gold: #d4af37; --lux-gold-light: #f3e5ab; --lux-dark: #171717; --lux-darker: #0a0a0a; --lux-gray: #262626; --header-font: {!! $headerFont !!}; --body-font: {!! $bodyFont !!}; }
        body { font-family: var(--body-font); background-color: var(--lux-dark); color: #e5e5e5; display: flex; min-height: 100vh; flex-direction: column; }
        h1, h2, h3, h4, h5, h6 { font-family: var(--header-font); color: var(--lux-gold); font-weight: 500; letter-spacing: 0.5px; }
        .lux-nav { background: var(--lux-darker); border-bottom: 1px solid var(--lux-gray); padding: 1.5rem 0; }
        .lux-nav .nav-link { color: #a3a3a3; font-weight: 300; margin: 0 0.5rem; text-transform: uppercase; font-size: 0.8rem; letter-spacing: 2px; transition: color 0.3s; }
        .lux-nav .nav-link:hover, .lux-nav .nav-link.active { color: var(--lux-gold); }
        .btn-lux { background: transparent; color: var(--lux-gold); border: 1px solid var(--lux-gold); padding: 0.6rem 2rem; text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; transition: all 0.4s; }
        .btn-lux:hover { background: var(--lux-gold); color: var(--lux-darker); }
        .btn-lux-filled { background: var(--lux-gold); color: var(--lux-darker); border: 1px solid var(--lux-gold); padding: 0.6rem 2rem; text-transform: uppercase; letter-spacing: 2px; font-size: 0.8rem; transition: all 0.4s; }
        .btn-lux-filled:hover { background: transparent; color: var(--lux-gold); }
        .lux-footer { background: var(--lux-darker); padding: 4rem 0; border-top: 1px solid var(--lux-gray); margin-top: auto; }
        .lux-card { background: var(--lux-darker); border: 1px solid var(--lux-gray); transition: border-color 0.4s; }
        .lux-card:hover { border-color: var(--lux-gold); }
        .text-primary { color: var(--lux-gold) !important; }
        .bg-primary { background-color: var(--lux-gold) !important; color: var(--lux-darker) !important; }
        .btn-primary { background-color: var(--lux-gold); border-color: var(--lux-gold); color: var(--lux-darker); border-radius: 0; }
        .btn-outline-primary { color: var(--lux-gold); border-color: var(--lux-gold); border-radius: 0; }
        .btn-outline-primary:hover { background-color: var(--lux-gold); color: var(--lux-darker); }
        .text-muted { color: #a3a3a3 !important; }
        ::placeholder { color: #a3a3a3 !important; opacity: 0.8 !important; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg lux-nav sticky-top">
        <div class="container d-flex flex-column align-items-center">
            <a class="navbar-brand text-center mb-4" href="{{ url('/') }}">
                @if(isset($clinicSetting) && $clinicSetting->logo_path)
                    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo" style="height: 60px; filter: drop-shadow(0px 0px 2px rgba(212, 175, 55, 0.5));">
                @else
                    <i class="bi bi-gem fs-2" style="color: var(--lux-gold);"></i>
                    <h2 class="mt-2 mb-0 text-uppercase letter-spacing-1" style="font-size: 1.5rem;">{{ $clinicSetting->clinic_name ?? 'Clinic' }}</h2>
                @endif
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#luxNav">
                <i class="bi bi-list display-6" style="color: var(--lux-gold);"></i>
            </button>
            <div class="collapse navbar-collapse w-100 justify-content-center" id="luxNav">
                <ul class="navbar-nav mb-2 mb-lg-0 align-items-center">
                    <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item d-none d-lg-block text-muted px-2">|</li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.doctors') ? 'active' : '' }}" href="{{ route('public.doctors') }}">Specialists</a></li>
                    <li class="nav-item d-none d-lg-block text-muted px-2">|</li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}" href="{{ route('public.about') }}">About</a></li>
                    <li class="nav-item d-none d-lg-block text-muted px-2">|</li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}" href="{{ route('public.contact') }}">Contact</a></li>
                    <li class="nav-item d-none d-lg-block text-muted px-2">|</li>
                    <li class="nav-item mt-3 mt-lg-0 ms-lg-3">
                        @auth
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDoctor() ? route('doctor.dashboard') : (auth()->user()->isReceptionist() ? route('receptionist.dashboard') : route('patient.dashboard'))) }}" class="btn-lux text-decoration-none">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="btn-lux text-decoration-none">PORTAL</a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="flex-grow-1">@yield('content')</main>
    <footer class="lux-footer">
        <div class="container">
            <div class="row g-5 mb-5 pb-5 border-bottom justify-content-between" style="border-color: var(--lux-gray) !important;">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        @if(isset($clinicSetting) && $clinicSetting->logo_path)
    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo" style="height: 40px; border-radius: 8px;" class="me-2 bg-white p-1">
@endif
<h4 class="text-uppercase mb-0" style="letter-spacing: 2px;">{{ $clinicSetting->clinic_name ?? 'Clinic' }}</h4>
                    </div>
                    <p class="text-muted pe-lg-4 mb-5" style="line-height: 2; font-weight: 300;">{{ $clinicSetting->about_short ?? 'A sanctuary of healing, combining world-class medical expertise with unparalleled patient care.' }}</p>
                    <div class="d-flex gap-4">
                        <a href="#" class="text-muted fs-5 transition" style="color: var(--lux-gold) !important;"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-muted fs-5 transition" style="color: var(--lux-gold) !important;"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-muted fs-5 transition" style="color: var(--lux-gold) !important;"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-muted fs-5 transition" style="color: var(--lux-gold) !important;"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h6 class="text-uppercase mb-4 pb-2 border-bottom d-inline-block" style="color: var(--lux-gold); letter-spacing: 2px; border-color: var(--lux-gray) !important;">Menu</h6>
                    <ul class="list-unstyled d-flex flex-column gap-3">
                        <li><a href="{{ url('/') }}" class="text-muted text-decoration-none transition hover-gold" style="font-weight: 300;">Home</a></li>
                        <li><a href="{{ route('public.about') }}" class="text-muted text-decoration-none transition hover-gold" style="font-weight: 300;">Heritage</a></li>
                        <li><a href="{{ route('public.doctors') }}" class="text-muted text-decoration-none transition hover-gold" style="font-weight: 300;">Specialists</a></li>
                        <li><a href="{{ route('public.contact') }}" class="text-muted text-decoration-none transition hover-gold" style="font-weight: 300;">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-4 col-md-4">
                    <h6 class="text-uppercase mb-4 pb-2 border-bottom d-inline-block" style="color: var(--lux-gold); letter-spacing: 2px; border-color: var(--lux-gray) !important;">Contact</h6>
                    <ul class="list-unstyled d-flex flex-column gap-4">
                        <li class="d-flex align-items-start gap-3 text-muted" style="font-weight: 300;">
                            <i class="bi bi-geo-alt mt-1" style="color: var(--lux-gold);"></i>
                            <span>{{ $clinicSetting->address ?? 'Multan' }}</span>
                        </li>
                        <li class="d-flex align-items-center gap-3 text-muted" style="font-weight: 300;">
                            <i class="bi bi-telephone" style="color: var(--lux-gold);"></i>
                            <span>{{ $clinicSetting->phone ?? '+92 300 1234567' }}</span>
                        </li>
                        <li class="d-flex align-items-center gap-3 text-muted" style="font-weight: 300;">
                            <i class="bi bi-envelope" style="color: var(--lux-gold);"></i>
                            <span>{{ $clinicSetting->contact_email ?? 'info@clinic.com' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
                <p class="text-muted small mb-0" style="letter-spacing: 1px; font-weight: 300;">&copy; {{ date('Y') }} {{ $clinicSetting->clinic_name ?? 'Clinic' }}. ALL RIGHTS RESERVED.</p>
                
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
