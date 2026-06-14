<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $clinicSetting->clinic_name ?? config('app.name') }} | @yield('title')</title>
    
    @if(isset($clinicSetting) && $clinicSetting->favicon_path)
        <link rel="icon" href="{{ asset('storage/' . $clinicSetting->favicon_path) }}">
    @endif

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
        :root {
            --theme-font: {!! $fontFamily !!}, sans-serif;
            --primary-green: #10b981;
            --primary-dark: #047857;
            --primary-light: #d1fae5;
            --bg-color: #f0fdf4;
            --text-color: #064e3b;
        }

        body {
            font-family: var(--theme-font);
            background-color: var(--bg-color);
            color: var(--text-color);
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        .nature-nav {
            background-color: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            padding: 1rem 0;
            border-bottom: 4px solid var(--primary-green);
        }

        .nature-nav .nav-link {
            color: var(--text-color);
            font-weight: 500;
            margin: 0 0.5rem;
            border-radius: 20px;
            padding: 0.5rem 1.2rem;
            transition: all 0.3s ease;
        }

        .nature-nav .nav-link:hover, .nature-nav .nav-link.active {
            background-color: var(--primary-light);
            color: var(--primary-dark);
        }

        .btn-nature {
            background-color: var(--primary-green);
            color: white;
            border-radius: 30px;
            border: none;
            padding: 0.5rem 1.5rem;
            font-weight: 600;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.2);
            transition: all 0.3s;
        }

        .btn-nature:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            color: white;
        }

        .nature-footer {
            background-color: #022c22;
            color: #ecfdf5;
            padding: 4rem 0 2rem;
            margin-top: auto;
            border-top: 5px solid var(--primary-green);
        }

        .nature-footer a {
            color: #6ee7b7;
            text-decoration: none;
            transition: color 0.2s;
        }

        .nature-footer a:hover {
            color: white;
        }

        .nature-card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--primary-light);
            box-shadow: 0 10px 25px rgba(4, 120, 87, 0.05);
            overflow: hidden;
        }

        /* Override bootstrap utilities for this theme */
        .text-primary { color: var(--primary-dark) !important; }
        .bg-primary { background-color: var(--primary-green) !important; }
        .btn-primary { background-color: var(--primary-green); border-color: var(--primary-green); }
        .btn-primary:hover { background-color: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-outline-primary { color: var(--primary-dark); border-color: var(--primary-dark); }
        .btn-outline-primary:hover { background-color: var(--primary-dark); color: white; }
        
        .hero-section {
            background: linear-gradient(135deg, var(--primary-light) 0%, white 100%);
            border-radius: 30px;
            padding: 4rem 2rem;
            margin-top: 2rem;
            box-shadow: 0 20px 40px rgba(16, 185, 129, 0.1);
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg nature-nav sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                @if(isset($clinicSetting) && $clinicSetting->logo_path)
                    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo" style="height: 40px; width: auto;">
                @else
                    <i class="bi bi-hospital-fill text-primary fs-3"></i>
                @endif
                <span class="fw-bold" style="color: var(--primary-dark);">{{ $clinicSetting->clinic_name ?? 'Clinic' }}</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#natureNavbar">
                <i class="bi bi-list fs-1 text-primary"></i>
            </button>

            <div class="collapse navbar-collapse" id="natureNavbar">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0 text-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.doctors') ? 'active' : '' }}" href="{{ route('public.doctors') }}">Specialists</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}" href="{{ route('public.about') }}">Our Story</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}" href="{{ route('public.contact') }}">Contact</a>
                    </li>
                    @foreach($navPages as $p)
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('page/' . $p->slug) ? 'active' : '' }}" href="{{ url('page/' . $p->slug) }}">{{ $p->title }}</a>
                        </li>
                    @endforeach
                </ul>

                <div class="d-flex justify-content-center gap-2 mt-3 mt-lg-0">
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDoctor() ? route('doctor.dashboard') : (auth()->user()->isReceptionist() ? route('receptionist.dashboard') : route('patient.dashboard'))) }}" 
                            class="btn-nature text-decoration-none">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-4 fw-bold">Login</a>
                        <a href="{{ route('register') }}" class="btn-nature text-decoration-none">Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">
        @yield('content')
    </main>

    <footer class="nature-footer text-center">
        <div class="container">
            <h4 class="fw-bold mb-4">@if(isset($clinicSetting) && $clinicSetting->logo_path)
    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo" style="height: 40px; border-radius: 8px;" class="me-2 bg-white p-1">
@endif{{ $clinicSetting->clinic_name ?? 'Clinic' }}</h4>
            <div class="d-flex justify-content-center gap-4 mb-4">
                <a href="{{ url('/') }}">Home</a>
                <a href="{{ route('public.doctors') }}">Doctors</a>
                <a href="{{ route('public.about') }}">About</a>
                <a href="{{ route('public.contact') }}">Contact</a>
            </div>
            <p class="small text-white-50 mb-0">
                <i class="bi bi-geo-alt-fill text-success"></i> {{ $clinicSetting->address ?? 'Multan' }} | 
                <i class="bi bi-telephone-fill text-success"></i> {{ $clinicSetting->phone ?? 'Phone' }}
            </p>
            <hr class="border-secondary my-4 opacity-25">
            <p class="small text-white-50">&copy; {{ date('Y') }} All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
