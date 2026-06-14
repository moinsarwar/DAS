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
        $headerFont = $fontFamily == "'Nunito'" ? "'Nunito', sans-serif" : "$fontFamily, sans-serif";
    @endphp
    <link href="{{ $fontImport }}" rel="stylesheet">
    <style>
        :root { --rose-primary: #f43f5e; --rose-light: #ffe4e6; --rose-soft: #fff1f2; --rose-dark: #881337; --theme-font: {!! $headerFont !!}; }
        body { font-family: var(--theme-font); background-color: var(--rose-soft); color: #4c0519; display: flex; min-height: 100vh; flex-direction: column; }
        h1, h2, h3, h4, h5, h6 { font-weight: 700; color: var(--rose-dark); }
        .rose-nav { background: white; padding: 1rem 0; box-shadow: 0 4px 20px -5px rgba(244, 63, 94, 0.15); border-radius: 0 0 30px 30px; margin-bottom: 2rem; position: sticky; top: 0; z-index: 1000; }
        .rose-nav .nav-link { color: var(--rose-dark); font-weight: 600; padding: 0.5rem 1.2rem; border-radius: 20px; transition: all 0.3s; margin: 0 0.2rem; }
        .rose-nav .nav-link:hover, .rose-nav .nav-link.active { background: var(--rose-light); color: var(--rose-primary); }
        .btn-rose { background: var(--rose-primary); color: white; border-radius: 30px; padding: 0.7rem 2rem; font-weight: 700; border: none; transition: transform 0.2s, box-shadow 0.2s; box-shadow: 0 8px 15px -3px rgba(244, 63, 94, 0.3); }
        .btn-rose:hover { transform: translateY(-3px); color: white; box-shadow: 0 12px 20px -3px rgba(244, 63, 94, 0.4); }
        .btn-outline-rose { background: transparent; color: var(--rose-primary); border: 2px solid var(--rose-primary); border-radius: 30px; padding: 0.7rem 2rem; font-weight: 700; transition: all 0.3s; }
        .btn-outline-rose:hover { background: var(--rose-light); color: var(--rose-primary); }
        .rose-footer { background: white; padding: 4rem 0 2rem; margin-top: auto; border-radius: 40px 40px 0 0; box-shadow: 0 -10px 30px -10px rgba(244, 63, 94, 0.1); }
        .rose-card { background: white; border-radius: 30px; border: none; box-shadow: 0 15px 35px -10px rgba(244, 63, 94, 0.1); padding: 2rem; transition: transform 0.3s; }
        .rose-card:hover { transform: translateY(-5px); }
        .text-primary { color: var(--rose-primary) !important; }
        .bg-primary { background-color: var(--rose-primary) !important; }
        .btn-primary { background-color: var(--rose-primary); border-color: var(--rose-primary); border-radius: 30px; }
        .btn-outline-primary { color: var(--rose-primary); border-color: var(--rose-primary); border-radius: 30px; }
        .btn-outline-primary:hover { background-color: var(--rose-primary); color: white; }
    
        .bg-rose-light { background-color: var(--rose-light) !important; }
        .bg-rose-primary { background-color: var(--rose-primary) !important; color: white !important; }
    </style>
</head>
<body>
    <div class="container px-3 px-md-0">
        <nav class="navbar navbar-expand-lg rose-nav px-4">
            <a class="navbar-brand d-flex align-items-center gap-3" href="{{ url('/') }}">
                @if(isset($clinicSetting) && $clinicSetting->logo_path)
                    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo" style="height: 45px; border-radius: 10px;">
                @else
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                        <i class="bi bi-balloon-heart-fill fs-4"></i>
                    </div>
                @endif
                <h4 class="fw-bold mb-0 text-dark">{{ $clinicSetting->clinic_name ?? 'Clinic' }}</h4>
            </a>
            <button class="navbar-toggler border-0 bg-light rounded-circle p-2" type="button" data-bs-toggle="collapse" data-bs-target="#roseNav">
                <i class="bi bi-list fs-2 text-primary"></i>
            </button>
            <div class="collapse navbar-collapse" id="roseNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.doctors') ? 'active' : '' }}" href="{{ route('public.doctors') }}">Doctors</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}" href="{{ route('public.about') }}">About Us</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}" href="{{ route('public.contact') }}">Contact</a></li>
                </ul>
                <div class="d-flex gap-3 mt-3 mt-lg-0">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDoctor() ? route('doctor.dashboard') : (auth()->user()->isReceptionist() ? route('receptionist.dashboard') : route('patient.dashboard'))) }}" class="btn-outline-rose text-decoration-none">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn-outline-rose text-decoration-none">Login</a>
                    @endauth
                </div>
            </div>
        </nav>
    </div>
    
    <main class="flex-grow-1">@yield('content')</main>
    
    <footer class="rose-footer pt-5">
        <div class="container">
            <div class="row g-5 mb-5 justify-content-between">
                <div class="col-lg-4 text-center text-lg-start">
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start gap-3 mb-4">
                        @if(isset($clinicSetting) && $clinicSetting->logo_path)
    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo" style="height: 40px; border-radius: 8px;" class="me-2 bg-white p-1">
@endif
<h3 class="fw-bold mb-0">{{ $clinicSetting->clinic_name ?? 'Clinic' }}</h3>
                    </div>
                    <p class="text-muted mb-4 pe-lg-4 lh-lg">{{ $clinicSetting->about_short ?? 'Providing gentle, compassionate, and expert care when you need it most.' }}</p>
                    <div class="d-flex justify-content-center justify-content-lg-start gap-3">
                        <a href="#" class="btn btn-outline-rose rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 45px; height: 45px;"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="btn btn-outline-rose rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 45px; height: 45px;"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="#" class="btn btn-outline-rose rounded-circle d-flex align-items-center justify-content-center p-0" style="width: 45px; height: 45px;"><i class="bi bi-twitter-x fs-5"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 text-center text-md-start">
                    <h5 class="fw-bold mb-4">Menu</h5>
                    <ul class="list-unstyled d-flex flex-column gap-3">
                        <li><a href="{{ url('/') }}" class="text-muted text-decoration-none hover-primary fw-bold transition">Home</a></li>
                        <li><a href="{{ route('public.about') }}" class="text-muted text-decoration-none hover-primary fw-bold transition">About Us</a></li>
                        <li><a href="{{ route('public.doctors') }}" class="text-muted text-decoration-none hover-primary fw-bold transition">Doctors</a></li>
                        <li><a href="{{ route('public.contact') }}" class="text-muted text-decoration-none hover-primary fw-bold transition">Contact</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-4 text-center text-md-start">
                    <h5 class="fw-bold mb-4">Contact</h5>
                    <ul class="list-unstyled d-flex flex-column gap-3">
                        <li class="d-flex align-items-center justify-content-center justify-content-md-start gap-3">
                            <i class="bi bi-geo-alt fs-5 text-primary"></i>
                            <span class="text-muted fw-bold">{{ $clinicSetting->address ?? 'Multan' }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-center justify-content-md-start gap-3">
                            <i class="bi bi-telephone fs-5 text-primary"></i>
                            <span class="text-muted fw-bold">{{ $clinicSetting->phone ?? '+92 300 1234567' }}</span>
                        </li>
                        <li class="d-flex align-items-center justify-content-center justify-content-md-start gap-3">
                            <i class="bi bi-envelope fs-5 text-primary"></i>
                            <span class="text-muted fw-bold">{{ $clinicSetting->contact_email ?? 'info@clinic.com' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-top pt-4 pb-3 d-flex flex-column flex-md-row justify-content-between align-items-center border-opacity-10" style="border-color: var(--rose-primary) !important;">
                <p class="text-muted small fw-bold mb-0">&copy; {{ date('Y') }} {{ $clinicSetting->clinic_name ?? 'Clinic' }}. All Rights Reserved.</p>
                
            </div>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
