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
        :root { --theme-font: {!! $fontFamily !!}, sans-serif; --sunset-primary: #f97316; --sunset-secondary: #ea580c; --sunset-light: #fff7ed; --sunset-dark: #431407; }
        body { font-family: var(--theme-font); background-color: #fafaf9; color: #292524; display: flex; min-height: 100vh; flex-direction: column; }
        .sunset-nav { background: white; padding: 1.5rem 0; border-bottom: 1px solid #f5f5f4; position: relative; z-index: 10; }
        .sunset-nav .nav-link { color: var(--sunset-dark); font-weight: 600; padding: 0.5rem 1.5rem; border-radius: 99px; transition: all 0.3s; }
        .sunset-nav .nav-link:hover, .sunset-nav .nav-link.active { background: var(--sunset-light); color: var(--sunset-primary); }
        .btn-sunset { background: linear-gradient(135deg, var(--sunset-primary), var(--sunset-secondary)); color: white; border-radius: 99px; border: none; padding: 0.8rem 2rem; font-weight: 600; transition: transform 0.2s; box-shadow: 0 10px 15px -3px rgba(249, 115, 22, 0.3); }
        .btn-sunset:hover { transform: translateY(-2px); color: white; }
        .sunset-footer { background: var(--sunset-dark); color: #ffedd5; padding: 4rem 0 2rem; margin-top: auto; border-top-left-radius: 50px; border-top-right-radius: 50px; }
        .sunset-card { background: white; border-radius: 24px; border: none; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.05); }
        .text-primary { color: var(--sunset-primary) !important; }
        .bg-primary { background-color: var(--sunset-primary) !important; }
        .btn-primary { background-color: var(--sunset-primary); border-color: var(--sunset-primary); border-radius: 99px;}
        .btn-outline-primary { color: var(--sunset-primary); border-color: var(--sunset-primary); border-radius: 99px;}
        .btn-outline-primary:hover { background-color: var(--sunset-primary); color: white; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sunset-nav sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                @if(isset($clinicSetting) && $clinicSetting->logo_path)
                    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo" style="height: 45px;">
                @else
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 45px; height: 45px;">
                        <i class="bi bi-heart-pulse-fill fs-4"></i>
                    </div>
                @endif
                <span class="fw-bold fs-4 text-dark">{{ $clinicSetting->clinic_name ?? 'Clinic' }}</span>
            </a>
            <button class="navbar-toggler border-0 bg-light rounded-circle p-2" type="button" data-bs-toggle="collapse" data-bs-target="#sunsetNav">
                <i class="bi bi-grid-3x3-gap-fill text-primary"></i>
            </button>
            <div class="collapse navbar-collapse" id="sunsetNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.doctors') ? 'active' : '' }}" href="{{ route('public.doctors') }}">Specialists</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}" href="{{ route('public.about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}" href="{{ route('public.contact') }}">Contact</a></li>
                </ul>
                <div class="d-flex gap-3 mt-3 mt-lg-0">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDoctor() ? route('doctor.dashboard') : (auth()->user()->isReceptionist() ? route('receptionist.dashboard') : route('patient.dashboard'))) }}" class="btn-sunset text-decoration-none">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary px-4 fw-bold">Login</a>
                        <a href="{{ route('register') }}" class="btn-sunset text-decoration-none">Sign Up</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
    <main class="flex-grow-1">@yield('content')</main>
    <footer class="sunset-footer">
        <div class="container">
            <div class="row g-5 mb-5 justify-content-between">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        @if(isset($clinicSetting) && $clinicSetting->logo_path)
    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo" style="height: 40px; border-radius: 8px;" class="me-2 bg-white p-1">
@endif
<h3 class="fw-bold text-white mb-0">{{ $clinicSetting->clinic_name ?? 'Clinic' }}</h3>
                    </div>
                    <p class="text-white-75 mb-4 pe-lg-4 lh-lg">{{ $clinicSetting->about_short ?? 'Providing excellent healthcare with a modern approach.' }}</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="btn btn-outline-light rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 40px; height: 40px;"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="btn btn-outline-light rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 40px; height: 40px;"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="btn btn-outline-light rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 40px; height: 40px;"><i class="bi bi-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5 class="text-white fw-bold mb-4">Quick Links</h5>
                    <ul class="list-unstyled d-flex flex-column gap-3">
                        <li><a href="{{ url('/') }}" class="text-white-75 text-decoration-none hover-white transition">Home</a></li>
                        <li><a href="{{ route('public.about') }}" class="text-white-75 text-decoration-none hover-white transition">About Us</a></li>
                        <li><a href="{{ route('public.doctors') }}" class="text-white-75 text-decoration-none hover-white transition">Specialists</a></li>
                        <li><a href="{{ route('public.contact') }}" class="text-white-75 text-decoration-none hover-white transition">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3">
                    <h5 class="text-white fw-bold mb-4">Contact Info</h5>
                    <ul class="list-unstyled d-flex flex-column gap-3">
                        <li class="text-white-75 d-flex gap-2"><i class="bi bi-geo-alt text-white mt-1"></i> <span>{{ $clinicSetting->address ?? 'Multan' }}</span></li>
                        <li class="text-white-75 d-flex gap-2"><i class="bi bi-telephone text-white mt-1"></i> <span>{{ $clinicSetting->phone ?? '+92 300 1234567' }}</span></li>
                        <li class="text-white-75 d-flex gap-2"><i class="bi bi-envelope text-white mt-1"></i> <span>{{ $clinicSetting->contact_email ?? 'info@clinic.com' }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="border-top border-white border-opacity-25 pt-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
                <p class="small text-white-75 mb-0">&copy; {{ date('Y') }} {{ $clinicSetting->clinic_name ?? 'Clinic' }}. All Rights Reserved.</p>
                
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
