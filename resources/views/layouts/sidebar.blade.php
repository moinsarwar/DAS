<div id="sidebar-wrapper">
    <div class="sidebar-heading">
        <i class="bi bi-hospital-fill text-success"></i>
        <span>{{ config('app.name') }}</span>
    </div>

    <div class="list-group list-group-flush my-3">
        @auth
            @if(auth()->user()->isAdmin())
                <small class="text-uppercase text-muted fw-bold px-3 mb-2"
                    style="font-size: 0.75rem; letter-spacing: 0.05em;">Administration</small>
                <a href="{{ route('admin.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('admin.doctors') }}"
                    class="sidebar-link {{ request()->routeIs('admin.doctors*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i> Manage Doctors
                </a>
                <a href="{{ route('admin.receptionists') }}"
                    class="sidebar-link {{ request()->routeIs('admin.receptionists*') ? 'active' : '' }}">
                    <i class="bi bi-person-workspace"></i> Manage Receptionists
                </a>
                <a href="{{ route('admin.patients') }}"
                    class="sidebar-link {{ request()->routeIs('admin.patients*') ? 'active' : '' }}">
                    <i class="bi bi-person-wheelchair"></i> Manage Patients
                </a>
                <a href="{{ route('admin.appointments') }}"
                    class="sidebar-link {{ request()->routeIs('admin.appointments*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Appointments
                </a>
                <a href="{{ route('admin.settings') }}"
                    class="sidebar-link {{ request()->routeIs('admin.settings*') ? 'active' : '' }}">
                    <i class="bi bi-gear-fill"></i> Clinic Settings
                </a>
            @elseif(auth()->user()->isDoctor())
                <small class="text-uppercase text-muted fw-bold px-3 mb-2"
                    style="font-size: 0.75rem; letter-spacing: 0.05em;">Doctor Panel</small>
                <a href="{{ route('doctor.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('doctor.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
                <a href="{{ route('doctor.schedules') }}"
                    class="sidebar-link {{ request()->routeIs('doctor.schedules*') ? 'active' : '' }}">
                    <i class="bi bi-clock-history"></i> My Schedule
                </a>
                <a href="{{ route('doctor.appointments') }}"
                    class="sidebar-link {{ request()->routeIs('doctor.appointments*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event"></i> Appointments
                </a>
                <a href="{{ route('doctor.profile') }}"
                    class="sidebar-link {{ request()->routeIs('doctor.profile') ? 'active' : '' }}">
                    <i class="bi bi-person-badge"></i> My Profile
                </a>
            @elseif(auth()->user()->isPatient())
                <small class="text-uppercase text-muted fw-bold px-3 mb-2"
                    style="font-size: 0.75rem; letter-spacing: 0.05em;">Patient Portal</small>
                <a href="{{ route('patient.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('patient.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-house-door"></i> Dashboard
                </a>
                <a href="{{ route('patient.history') }}"
                    class="sidebar-link {{ request()->routeIs('patient.history') ? 'active' : '' }}">
                    <i class="bi bi-journal-medical"></i> Medical History
                </a>
                <a href="{{ route('patient.profile') }}"
                    class="sidebar-link {{ request()->routeIs('patient.profile') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i> My Profile
                </a>
            @elseif(auth()->user()->isReceptionist())
                <small class="text-uppercase text-muted fw-bold px-3 mb-2"
                    style="font-size: 0.75rem; letter-spacing: 0.05em;">Reception Desk</small>
                <a href="{{ route('receptionist.dashboard') }}"
                    class="sidebar-link {{ request()->routeIs('receptionist.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-person-workspace"></i> Desk
                </a>
                <a href="{{ route('receptionist.appointments') }}"
                    class="sidebar-link {{ request()->routeIs('receptionist.appointments*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-check"></i> Manage Appointments
                </a>
            @endif
        @else
            <!-- Guest Links -->
            <a href="{{ route('login') }}" class="sidebar-link">
                <i class="bi bi-box-arrow-in-right"></i> Login
            </a>
        @endauth
    </div>

    <!-- Bottom Actions -->
    <div class="mt-auto p-3 border-top border-secondary" style="border-color: rgba(255,255,255,0.1) !important;">
        @auth
            <a href="#" class="sidebar-link text-danger"
                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        @endauth
    </div>
</div>

<!-- Overlay for Mobile -->
<div id="sidebar-overlay"></div>