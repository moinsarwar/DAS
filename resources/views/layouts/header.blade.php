<nav class="top-header">
    <div class="d-flex align-items-center gap-3">
        <button class="header-toggle-btn" id="sidebarToggle">
            <i class="bi bi-list"></i>
        </button>
        <h5 class="mb-0 fw-bold text-heading d-none d-md-block">
            @if(auth()->check())
                {{ config('app.name') }} | @yield('title', 'Dashboard')
            @else
                {{ config('app.name') }} | Welcome
            @endif
        </h5>
    </div>

    <div class="d-flex align-items-center gap-3">
        @auth
            <!-- Notifications -->
            <div class="dropdown" id="notificationDropdown">
                <a class="nav-link position-relative p-2 text-muted hover-lift" href="#" role="button"
                    data-bs-toggle="dropdown">
                    <i class="bi bi-bell fs-5"></i>
                    <span
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger border border-white"
                        id="notificationCount" style="display: none;">
                        0
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-3 p-0" style="width: 320px;">
                    <li class="dropdown-header d-flex justify-content-between align-items-center bg-light border-bottom">
                        <h6 class="mb-0 fw-bold text-heading">Notifications</h6>
                        <form action="{{ route('notifications.readAll') }}" method="POST">
                            @csrf
                            <button class="btn btn-link btn-sm text-decoration-none p-0 text-primary"
                                style="font-size: 0.8rem;">Mark all read</button>
                        </form>
                    </li>
                    <div id="notificationList" style="max-height: 300px; overflow-y: auto;">
                        <li class="text-center p-4 text-muted small">No new notifications</li>
                    </div>
                </ul>
            </div>

            <!-- User Profile -->
            <div class="dropdown">
                <a class="d-flex align-items-center gap-2 text-decoration-none dropdown-toggle p-1 rounded hover-bg-light"
                    href="#" role="button" data-bs-toggle="dropdown">
                    <div
                        class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white header-profile-img fs-6">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="d-none d-md-block text-start">
                        <div class="fw-bold text-heading small mb-0">{{ auth()->user()->name }}</div>
                        <div class="text-muted small" style="font-size: 0.75rem;">{{ auth()->user()->email }}</div>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2 p-2" style="min-width: 200px;">
                    <li>
                        <div class="d-flex align-items-center gap-3 px-3 py-2 border-bottom mb-2">
                            <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 40px; height: 40px;">
                                {{ substr(auth()->user()->name, 0, 1) }}
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">{{ auth()->user()->name }}</h6>
                                <p class="mb-0 text-muted small">Access Details</p>
                            </div>
                        </div>
                    </li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item rounded text-danger d-flex align-items-center gap-2">
                                <i class="bi bi-box-arrow-right"></i> Sign Out
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Login</a>
        @endauth
    </div>
</nav>