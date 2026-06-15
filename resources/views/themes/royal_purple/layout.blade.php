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
        :root { --theme-font: {!! $fontFamily !!}, sans-serif; --royal-primary: #581c87; --royal-light: #f3e8ff; --royal-gold: #d4af37; --royal-dark: #3b0764; }
        body { font-family: var(--theme-font); background-color: #faf5ff; color: #1e293b; display: flex; min-height: 100vh; flex-direction: column; }
        .royal-topbar { background: var(--royal-dark); color: var(--royal-gold); padding: 8px 0; font-size: 0.85rem; border-bottom: 2px solid var(--royal-gold); }
        .royal-nav { background: white; padding: 1rem 0; box-shadow: 0 4px 6px -1px rgba(88, 28, 135, 0.1); }
        .royal-nav .nav-link { color: var(--royal-dark); font-weight: 600; padding: 0.5rem 1rem; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 1px; border-bottom: 2px solid transparent; transition: all 0.3s; }
        .royal-nav .nav-link:hover, .royal-nav .nav-link.active { color: var(--royal-primary); border-bottom-color: var(--royal-primary); }
        .btn-royal { background: var(--royal-primary); color: white; border-radius: 4px; padding: 0.6rem 2rem; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; border: 1px solid var(--royal-primary); transition: all 0.3s; }
        .btn-royal:hover { background: var(--royal-dark); color: var(--royal-gold); border-color: var(--royal-gold); }
        .royal-footer { background: var(--royal-dark); color: #e9d5ff; padding: 5rem 0 2rem; margin-top: auto; border-top: 4px solid var(--royal-gold); }
        .royal-card { background: white; border: 1px solid #e2e8f0; border-top: 4px solid var(--royal-primary); border-radius: 4px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
        .text-primary { color: var(--royal-primary) !important; }
        .bg-primary { background-color: var(--royal-primary) !important; }
        .btn-primary { background-color: var(--royal-primary); border-color: var(--royal-primary); border-radius: 4px; }
        .btn-outline-primary { color: var(--royal-primary); border-color: var(--royal-primary); border-radius: 4px; }
        .btn-outline-primary:hover { background-color: var(--royal-primary); color: white; }
    </style>
</head>
<body>
    <div class="royal-topbar d-none d-lg-block">
        <div class="container d-flex justify-content-between">
            <div><i class="bi bi-clock me-2"></i>{{ $clinicSetting->clinic_days ?? 'Mon-Sat' }}</div>
            <div><i class="bi bi-envelope me-2"></i>{{ $clinicSetting->contact_email ?? 'info@clinic.com' }}</div>
        </div>
    </div>
    <nav class="navbar navbar-expand-lg royal-nav sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                @if(isset($clinicSetting) && $clinicSetting->logo_path)
                    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo" style="height: 50px;">
                @else
                    <i class="bi bi-shield-plus display-6" style="color: var(--royal-gold);"></i>
                @endif
                <div>
                    <h4 class="fw-bold mb-0 text-dark text-uppercase letter-spacing-1">{{ $clinicSetting->clinic_name ?? 'Clinic' }}</h4>
                </div>
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#royalNav">
                <i class="bi bi-list fs-1 text-primary"></i>
            </button>
            <div class="collapse navbar-collapse" id="royalNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0 me-4">
                    <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.doctors') ? 'active' : '' }}" href="{{ route('public.doctors') }}">Doctors</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}" href="{{ route('public.about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}" href="{{ route('public.contact') }}">Contact</a></li>
                </ul>
                <div class="d-flex gap-2">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDoctor() ? route('doctor.dashboard') : (auth()->user()->isReceptionist() ? route('receptionist.dashboard') : route('patient.dashboard'))) }}" class="btn-royal text-decoration-none">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-royal text-decoration-none">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <main class="flex-grow-1">@yield('content')</main>
    <footer class="royal-footer">
        <div class="container">
            <div class="row g-5 mb-5 justify-content-between">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        @if(isset($clinicSetting) && $clinicSetting->logo_path)
    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo" style="height: 40px; border-radius: 8px;" class="me-2 bg-white p-1">
@endif
<h4 class="fw-bold text-white mb-0 text-uppercase letter-spacing-1">{{ $clinicSetting->clinic_name ?? 'Clinic' }}</h4>
                    </div>
                    <p class="mb-4 pe-lg-4" style="color: #cbd5e1; line-height: 1.8;">{{ $clinicSetting->about_short ?? 'Excellence in Care. We provide the highest standard of specialized medical treatment in a premium environment.' }}</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white text-decoration-none bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center transition" style="width: 40px; height: 40px;"><i class="bi bi-linkedin"></i></a>
                        <a href="#" class="text-white text-decoration-none bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center transition" style="width: 40px; height: 40px;"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-white text-decoration-none bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center transition" style="width: 40px; height: 40px;"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h6 class="text-uppercase fw-bold mb-4 letter-spacing-1" style="color: var(--royal-gold);">Explore</h6>
                    <ul class="list-unstyled d-flex flex-column gap-3">
                        <li><a href="{{ url('/') }}" class="text-decoration-none transition" style="color: #cbd5e1;">Homepage</a></li>
                        <li><a href="{{ route('public.about') }}" class="text-decoration-none transition" style="color: #cbd5e1;">Our Heritage</a></li>
                        <li><a href="{{ route('public.doctors') }}" class="text-decoration-none transition" style="color: #cbd5e1;">Specialists</a></li>
                        <li><a href="{{ route('public.contact') }}" class="text-decoration-none transition" style="color: #cbd5e1;">Contact Desk</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3">
                    <h6 class="text-uppercase fw-bold mb-4 letter-spacing-1" style="color: var(--royal-gold);">Get In Touch</h6>
                    <ul class="list-unstyled d-flex flex-column gap-3 mb-0">
                        <li class="d-flex gap-3 align-items-start">
                            <i class="bi bi-geo-alt mt-1" style="color: var(--royal-gold);"></i>
                            <span style="color: #cbd5e1;">{{ $clinicSetting->address ?? 'Multan' }}</span>
                        </li>
                        <li class="d-flex gap-3 align-items-center">
                            <i class="bi bi-telephone" style="color: var(--royal-gold);"></i>
                            <span style="color: #cbd5e1;">{{ $clinicSetting->phone ?? '+92 300 1234567' }}</span>
                        </li>
                        <li class="d-flex gap-3 align-items-center">
                            <i class="bi bi-envelope" style="color: var(--royal-gold);"></i>
                            <span style="color: #cbd5e1;">{{ $clinicSetting->contact_email ?? 'info@clinic.com' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-top pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center" style="border-color: rgba(212, 175, 55, 0.2) !important;">
                <p class="small mb-0" style="color: #94a3b8;">&copy; {{ date('Y') }} {{ $clinicSetting->clinic_name ?? 'Clinic' }}. All Rights Reserved.</p>
                
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @include('layouts.ai_bot')
</body>
</html>
