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
            --bg-color: #0f172a;
            --text-color: #f8fafc;
            --primary-color: #3b82f6;
            --secondary-bg: #1e293b;
            --border-color: #334155;
        }

        body {
            font-family: var(--theme-font);
            background-color: var(--bg-color);
            color: var(--text-color);
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        /* Modern Dark Specific Layout: Vertical Left Navbar for Desktop */
        .modern-wrapper {
            display: flex;
            flex-grow: 1;
        }

        .modern-sidebar {
            width: 280px;
            background-color: var(--secondary-bg);
            border-right: 1px solid var(--border-color);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        .modern-main {
            margin-left: 280px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .modern-nav-link {
            color: #cbd5e1;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            transition: all 0.2s;
            font-weight: 500;
        }

        .modern-nav-link:hover, .modern-nav-link.active {
            background-color: var(--primary-color);
            color: white;
        }

        .modern-card {
            background-color: var(--secondary-bg);
            border: 1px solid var(--border-color);
            border-radius: 1rem;
        }

        .text-dark { color: #f8fafc !important; }
        .bg-light { background-color: var(--secondary-bg) !important; }
        .text-muted { color: #94a3b8 !important; }
        .border-bottom, .border-top { border-color: var(--border-color) !important; }
        .card { background-color: var(--secondary-bg); border-color: var(--border-color); color: var(--text-color); }
        .accordion-item { background-color: var(--secondary-bg); border-color: var(--border-color); color: var(--text-color); }
        .accordion-button { background-color: var(--secondary-bg); color: var(--text-color); }
        .accordion-button:not(.collapsed) { background-color: rgba(59, 130, 246, 0.1); color: var(--primary-color); }

        @media (max-width: 991.98px) {
            .modern-sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            .modern-sidebar.show {
                transform: translateX(0);
            }
            .modern-main {
                margin-left: 0;
            }
            .mobile-header {
                display: flex !important;
                background-color: var(--secondary-bg);
                padding: 1rem;
                border-bottom: 1px solid var(--border-color);
            }
        }

        .mobile-header { display: none; }
    </style>
</head>
<body>

    <div class="mobile-header align-items-center justify-content-between position-sticky top-0 z-3">
        <h5 class="mb-0 fw-bold">@if(isset($clinicSetting) && $clinicSetting->logo_path)
    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo" style="height: 40px; border-radius: 8px;" class="me-2 bg-white p-1">
@endif{{ $clinicSetting->clinic_name ?? 'Clinic' }}</h5>
        <button class="btn btn-outline-light" onclick="document.getElementById('sidebar').classList.toggle('show')">
            <i class="bi bi-list"></i>
        </button>
    </div>

    <div class="modern-wrapper">
        <nav class="modern-sidebar" id="sidebar">
            <div class="mb-5 text-center">
                @if(isset($clinicSetting) && $clinicSetting->logo_path)
                    <img src="{{ asset('storage/' . $clinicSetting->logo_path) }}" alt="Logo" style="height: 60px; max-width: 100%; object-fit: contain; margin-bottom: 1rem;">
                @else
                    <i class="bi bi-hospital-fill text-primary fs-1 mb-2 d-block"></i>
                @endif
                <h5 class="fw-bold mb-0 text-white">{{ $clinicSetting->clinic_name ?? 'Multan Cancer Clinic' }}</h5>
            </div>

            <div class="flex-grow-1">
                <a href="{{ url('/') }}" class="modern-nav-link {{ request()->is('/') ? 'active' : '' }}">
                    <i class="bi bi-house"></i> Home
                </a>
                <a href="{{ route('public.doctors') }}" class="modern-nav-link {{ request()->routeIs('public.doctors') ? 'active' : '' }}">
                    <i class="bi bi-people"></i> Doctors
                </a>
                <a href="{{ route('public.about') }}" class="modern-nav-link {{ request()->routeIs('public.about') ? 'active' : '' }}">
                    <i class="bi bi-info-circle"></i> About
                </a>
                <a href="{{ route('public.contact') }}" class="modern-nav-link {{ request()->routeIs('public.contact') ? 'active' : '' }}">
                    <i class="bi bi-envelope"></i> Contact
                </a>
                @foreach($navPages as $p)
                    <a href="{{ url('page/' . $p->slug) }}" class="modern-nav-link {{ request()->is('page/' . $p->slug) ? 'active' : '' }}">
                        <i class="bi bi-file-text"></i> {{ $p->title }}
                    </a>
                @endforeach
            </div>

            <div class="mt-auto pt-4 border-top">
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isDoctor() ? route('doctor.dashboard') : (auth()->user()->isReceptionist() ? route('receptionist.dashboard') : route('patient.dashboard'))) }}" 
                        class="btn btn-primary w-100 fw-bold rounded-3">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light w-100 mb-2 rounded-3">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary w-100 rounded-3">Register</a>
                @endauth
            </div>
        </nav>

        <main class="modern-main">
            @yield('content')

            <footer class="mt-auto py-4 border-top text-center text-muted" style="background-color: var(--secondary-bg);">
                <div class="container-fluid">
                    <p class="mb-0 small">&copy; {{ date('Y') }} {{ $clinicSetting->clinic_name ?? 'Clinic' }}. All rights reserved.</p>
                </div>
            </footer>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
