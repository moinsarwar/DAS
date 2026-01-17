<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multan Cancer Clinic | @yield('title', 'Welcome')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fbff;
            /* Very light medical blue/white */
        }

        .navbar-landing {
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            padding-top: 1rem;
            padding-bottom: 1rem;
        }

        .navbar-landing .nav-link {
            font-weight: 500;
            color: #4b5563;
            /* Gray-600 */
            margin-right: 1.5rem;
            transition: color 0.2s;
        }

        .navbar-landing .nav-link:hover,
        .navbar-landing .nav-link.active {
            color: #d81b60;
            /* Medical Pink/Maroon typical of cancer awareness or primary color */
            color: var(--bs-primary);
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -0.5px;
        }

        footer {
            background-color: #fff;
            padding: 3rem 0;
            border-top: 1px solid #e5e7eb;
        }

        /* Sticky navbar with semi-transparent blur effect */
        .sticky-top {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top navbar-landing fixed-top">
        <div class="container">
            <a class="navbar-brand text-dark d-flex align-items-center gap-2" href="{{ url('/') }}">
                @if(isset($clinicSetting) && $clinicSetting->logo_path)
                    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo"
                        style="height: 100px; width: auto;">
                @else
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center p-2"
                        style="width: 40px; height: 40px;">
                        <i class="bi bi-hospital-fill fs-5"></i>
                    </div>
                @endif
            </a>
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse"
                data-bs-target="#landingNavbar">
                <i class="bi bi-list fs-3"></i>
            </button>
            <div class="collapse navbar-collapse" id="landingNavbar">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.doctors') ? 'active' : '' }}"
                            href="{{ route('public.doctors') }}">Our Doctors</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}"
                            href="{{ route('public.about') }}">About Us</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}"
                            href="{{ route('public.contact') }}">Contact</a>
                    </li>
                    <li class="nav-item ms-md-3 mt-3 mt-md-0">
                        @auth
                            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDoctor() ? route('doctor.dashboard') : (auth()->user()->isReceptionist() ? route('receptionist.dashboard') : route('patient.dashboard'))) }}"
                                class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-primary fw-bold px-4 rounded-pill me-2">
                                Login
                            </a>
                            <a href="{{ route('register') }}" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm">
                                Register
                            </a>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main style="padding-top: 80px;"> {{-- Padding for fixed navbar --}}
        @yield('content')
    </main>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        @if(isset($clinicSetting) && $clinicSetting->logo_path)
                            <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo"
                                style="height: 80px; width: auto;">
                        @else
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 32px; height: 32px;">
                                <i class="bi bi-hospital-fill text-white small"></i>
                            </div>
                        @endif
                        <h5 class="fw-bold mb-0 text-dark">Multan Cancer Clinic</h5>
                    </div>
                    <p class="text-muted small mb-4">
                        Specialized oncology care providing expert consultations and compassion. Connecting patients
                        with top oncologists in Multan.
                    </p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-secondary hover-primary"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-secondary hover-primary"><i class="bi bi-twitter fs-5"></i></a>
                        <a href="#" class="text-secondary hover-primary"><i class="bi bi-instagram fs-5"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-6">
                    <h6 class="fw-bold mb-3 text-dark">Quick Links</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 small">
                        <li><a href="{{ url('/') }}" class="text-muted text-decoration-none hover-primary">Home</a></li>
                        <li><a href="{{ route('public.doctors') }}"
                                class="text-muted text-decoration-none hover-primary">Our Doctors</a></li>
                        <li><a href="{{ route('public.about') }}"
                                class="text-muted text-decoration-none hover-primary">About Us</a></li>
                        <li><a href="{{ route('public.contact') }}"
                                class="text-muted text-decoration-none hover-primary">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-6">
                    <h6 class="fw-bold mb-3 text-dark">Clinic Hours</h6>
                    <ul class="list-unstyled d-flex flex-column gap-2 small">
                        <li class="d-flex justify-content-between text-muted">
                            <span>Monday - Saturday:</span>
                            <span class="fw-medium text-dark">02:00 PM - 08:00 PM</span>
                        </li>
                        <li class="d-flex justify-content-between text-muted">
                            <span>Sunday:</span>
                            <span class="text-danger">Closed</span>
                        </li>
                        <li class="mt-2 text-warning small align-items-start d-flex gap-2">
                            <i class="bi bi-exclamation-triangle-fill flex-shrink-0"></i>
                            <span>No 24-hr Emergency</span>
                        </li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="fw-bold mb-3 text-dark">Contact Us</h6>
                    <ul class="list-unstyled d-flex flex-column gap-3 small">
                        <li class="d-flex gap-3 text-muted">
                            <i class="bi bi-geo-alt-fill text-primary mt-1"></i>
                            <span>{{ $clinicSetting->address ?? 'Nishtar Road, Multan, Pakistan' }}</span>
                        </li>
                        <li class="d-flex gap-3 text-muted">
                            <i class="bi bi-telephone-fill text-primary mt-1"></i>
                            <div>
                                <div class="mb-1">{{ $clinicSetting->phone ?? '+92 300 1234567' }}</div>
                                @if(isset($clinicSetting->landline))
                                    <div>{{ $clinicSetting->landline }} (Landline)</div>
                                @endif
                            </div>
                        </li>
                        <li class="d-flex gap-3 text-muted">
                            <i class="bi bi-envelope-fill text-primary mt-1"></i>
                            <span>{{ $clinicSetting->contact_email ?? 'info@multancancerclinic.com' }}</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="border-top mt-5 pt-4 text-center text-muted small">
                <p class="mb-0">&copy; {{ date('Y') }} Multan Cancer Clinic. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>