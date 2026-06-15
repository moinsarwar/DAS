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
            --ocean-dark: #0f172a;
            --ocean-blue: #0284c7;
            --ocean-light: #e0f2fe;
            --text-color: #334155;
        }

        body {
            font-family: var(--theme-font);
            color: var(--text-color);
            background-color: #f8fafc;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        .ocean-topbar {
            background-color: var(--ocean-dark);
            color: white;
            padding: 0.5rem 0;
            font-size: 0.85rem;
        }

        .ocean-navbar {
            background-color: white;
            box-shadow: 0 10px 30px -10px rgba(2, 132, 199, 0.1);
            padding: 1rem 0;
        }

        .ocean-navbar .nav-link {
            color: var(--text-color);
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            font-weight: 600;
            margin: 0 1rem;
            position: relative;
        }

        .ocean-navbar .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background-color: var(--ocean-blue);
            transition: all 0.3s;
            transform: translateX(-50%);
        }

        .ocean-navbar .nav-link:hover::after, .ocean-navbar .nav-link.active::after {
            width: 100%;
        }

        .ocean-btn {
            background-color: var(--ocean-blue);
            color: white;
            border-radius: 4px;
            padding: 0.6rem 2rem;
            text-transform: uppercase;
            font-size: 0.85rem;
            letter-spacing: 1px;
            font-weight: 600;
            border: 2px solid var(--ocean-blue);
            transition: all 0.3s;
        }

        .ocean-btn:hover {
            background-color: transparent;
            color: var(--ocean-blue);
        }

        .ocean-footer {
            background-color: var(--ocean-dark);
            color: #cbd5e1;
            padding: 5rem 0 2rem;
            margin-top: auto;
        }

        .ocean-footer h5 {
            color: white;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        /* Utilities */
        .text-primary { color: var(--ocean-blue) !important; }
        .bg-primary { background-color: var(--ocean-blue) !important; }
        .btn-primary { background-color: var(--ocean-blue); border-color: var(--ocean-blue); }
        .btn-primary:hover { background-color: #0369a1; border-color: #0369a1; }
    </style>
</head>
<body>

    <div class="ocean-topbar d-none d-lg-block">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="d-flex gap-4">
                <span><i class="bi bi-clock me-2 text-info"></i>{{ $clinicSetting->clinic_days ?? 'Mon-Sat' }}, {{ $clinicSetting->clinic_hours ?? '2PM-8PM' }}</span>
                <span><i class="bi bi-geo-alt me-2 text-info"></i>{{ $clinicSetting->address ?? 'Multan' }}</span>
            </div>
            <div class="d-flex gap-3">
                @if($clinicSetting->social_facebook)<a href="{{ $clinicSetting->social_facebook }}" class="text-white"><i class="bi bi-facebook"></i></a>@endif
                @if($clinicSetting->social_twitter)<a href="{{ $clinicSetting->social_twitter }}" class="text-white"><i class="bi bi-twitter-x"></i></a>@endif
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg ocean-navbar sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
                @if(isset($clinicSetting) && $clinicSetting->logo_path)
                    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo" style="height: 45px;">
                @else
                    <i class="bi bi-hospital fs-2 text-primary"></i>
                @endif
                <span class="fw-bold fs-4 text-dark">{{ $clinicSetting->clinic_name ?? 'Clinic' }}</span>
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#oceanNav">
                <i class="bi bi-list fs-2"></i>
            </button>

            <div class="collapse navbar-collapse" id="oceanNav">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.doctors') ? 'active' : '' }}" href="{{ route('public.doctors') }}">Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}" href="{{ route('public.about') }}">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}" href="{{ route('public.contact') }}">Contact</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <div class="d-none d-xl-flex align-items-center gap-2 me-3">
                        <i class="bi bi-telephone fs-3 text-primary"></i>
                        <div>
                            <small class="text-muted d-block lh-1">Call Us Now</small>
                            <strong class="text-dark">{{ $clinicSetting->phone ?? '+92 300 1234567' }}</strong>
                        </div>
                    </div>
                    @auth
                        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDoctor() ? route('doctor.dashboard') : (auth()->user()->isReceptionist() ? route('receptionist.dashboard') : route('patient.dashboard'))) }}" 
                            class="ocean-btn text-decoration-none">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="ocean-btn text-decoration-none">Login</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="flex-grow-1">
        @yield('content')
    </main>

    <footer class="ocean-footer">
        <div class="container">
            <div class="row g-5 justify-content-between">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-4">
                        <h4 class="fw-bold mb-0 text-white">@if(isset($clinicSetting) && $clinicSetting->logo_path)
    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo" style="height: 40px; border-radius: 8px;" class="me-2 bg-white p-1">
@endif{{ $clinicSetting->clinic_name ?? 'Clinic' }}</h4>
                    </div>
                    <p class="mb-4">{{ $clinicSetting->about_short ?? 'Providing excellent healthcare services.' }}</p>
                </div>
                <div class="col-lg-3 col-md-4">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled d-flex flex-column gap-2">
                        <li><a href="{{ url('/') }}" class="text-decoration-none text-light hover-primary">Home</a></li>
                        <li><a href="{{ route('public.doctors') }}" class="text-decoration-none text-light">Doctors</a></li>
                        <li><a href="{{ route('public.about') }}" class="text-decoration-none text-light">About Us</a></li>
                        <li><a href="{{ route('public.contact') }}" class="text-decoration-none text-light">Contact Us</a></li>
                        @foreach($footerPages as $p)
                            <li><a href="{{ url('page/' . $p->slug) }}" class="text-decoration-none text-light">{{ $p->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-4 col-md-4">
                    <h5>Contact Info</h5>
                    <ul class="list-unstyled d-flex flex-column gap-3">
                        <li class="d-flex gap-3"><i class="bi bi-geo-alt text-info fs-5"></i> <span>{{ $clinicSetting->address ?? 'Multan' }}</span></li>
                        <li class="d-flex gap-3"><i class="bi bi-telephone text-info fs-5"></i> <span>{{ $clinicSetting->phone ?? 'Phone' }}</span></li>
                        <li class="d-flex gap-3"><i class="bi bi-envelope text-info fs-5"></i> <span>{{ $clinicSetting->contact_email ?? 'Email' }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="border-top border-secondary mt-5 pt-4 text-center">
                <p class="mb-0">&copy; {{ date('Y') }} {{ $clinicSetting->clinic_name ?? 'Clinic' }}. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @include('layouts.ai_bot')
</body>
</html>
