<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} | @yield('title', 'Portal')</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">

    <!-- Custom Professional CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
</head>

<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        @include('layouts.sidebar')

        <!-- Page Content -->
        <div id="page-content-wrapper" class="d-flex flex-column min-vh-100">
            @include('layouts.header')

            <div class="container-fluid px-4 py-4 flex-grow-1">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>

            <footer class="mt-auto">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; {{ config('app.name') }} {{ date('Y') }}</div>
                        <div>
                            <a href="#">Privacy Policy</a>
                            &middot;
                            <a href="#">Terms &amp; Conditions</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- JS Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function () {
            // Apply Select2 to all select elements
            $('select').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: 'Select an option'
            });
        });
    </script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // Sidebar Toggle Logic
            $("#sidebarToggle").click(function (e) {
                e.preventDefault();
                $("#wrapper").toggleClass("toggled");
            });

            $("#sidebar-overlay").click(function () {
                $("#wrapper").removeClass("toggled");
            });

            // Initialize DataTables
            $('.table').DataTable({
                responsive: true,
                autoWidth: false,
                "order": [], // Disable initial sort to respect Backend Sort (ID Desc)
                "language": {
                    "search": "",
                    "searchPlaceholder": "Search records...",
                    "lengthMenu": "Show _MENU_ entries",
                    "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                    "paginate": {
                        "previous": "<i class='bi bi-chevron-left'></i>",
                        "next": "<i class='bi bi-chevron-right'></i>"
                    }
                },
                "dom": "<'row m-0 p-3 align-items-center border-bottom'<'col-md-6 d-flex align-items-center'l><'col-md-6'f>>" +
                    "<'row m-0'<'col-12 p-0'tr>>" +
                    "<'row m-0 p-3 align-items-center border-top'<'col-md-6 text-muted small'i><'col-md-6'p>>",
                "drawCallback": function () {
                    $('.dataTables_paginate > .pagination').addClass('justify-content-end mb-0 gap-1');
                },
                "initComplete": function () {
                    $('.dataTables_filter input').addClass('form-control form-control-sm ms-2').css('width', '250px');
                    $('.dataTables_length select').addClass('form-select form-select-sm mx-2').css('width', 'auto').css('display', 'inline-block');
                    $('.dataTables_length').addClass('d-flex align-items-center text-muted small');
                }
            });

            // Global SweetAlert Toast Handling
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });

            @if(session('success'))
                Toast.fire({
                    icon: 'success',
                    title: "{{ session('success') }}"
                });
            @endif

            @if(session('error'))
                Toast.fire({
                    icon: 'error',
                    title: "{{ session('error') }}"
                });
            @endif

            @if(session('warning'))
                Toast.fire({
                    icon: 'warning',
                    title: "{{ session('warning') }}"
                });
            @endif

            // Notification Polling System
            @auth
                // Define function globally or within scope but accessible
                function fetchNotifications() {
                    $.get("{{ route('notifications.fetch') }}", function (data) {
                        let count = data.unread_count;
                        let badge = $('#notificationCount');
                        let list = $('#notificationList');

                        // Update Badge
                        if (count > 0) {
                            badge.text(count).show();
                            badge.addClass('animate__animated animate__pulse');
                        } else {
                            badge.hide();
                        }

                        // Update List
                        let html = '';
                        if (data.notifications.length === 0) {
                            html = '<li class="text-center p-3 text-muted small">No new notifications</li>';
                        } else {
                            data.notifications.forEach(n => {
                                // Safe navigation for data
                                let icon = n.data && n.data.icon ? n.data.icon : 'bi-bell';
                                let color = n.data && n.data.color ? n.data.color : 'text-primary';
                                let message = n.data && n.data.message ? n.data.message : 'New Notification';

                                html += `
                                                                                    <li>
                                                                                        <a class="dropdown-item d-flex align-items-start gap-2 p-2 border-bottom" href="#" onclick="markAsRead(event, '${n.id}')">
                                                                                            <div class="bg-light rounded-circle p-2 mt-1">
                                                                                                <i class="bi ${icon} ${color}"></i>
                                                                                            </div>
                                                                                            <div>
                                                                                                <p class="mb-0 small fw-semibold text-wrap">${message}</p>
                                                                                                <small class="text-muted" style="font-size: 0.75rem;">${n.created_at}</small>
                                                                                            </div>
                                                                                        </a>
                                                                                    </li>
                                                                                `;
                            });
                        }
                        list.html(html);
                    });
                }

                // Poll every 5 seconds
                setInterval(fetchNotifications, 5000);

                // Unconditional initial call
                fetchNotifications();

                // Global function to mark read
                window.markAsRead = function (event, id) {
                    event.preventDefault(); // Prevent default link behavior
                    $.post("/notifications/" + id + "/read", {
                        _token: "{{ csrf_token() }}"
                    }, function (response) {
                        if (response.url && response.url !== '#') {
                            window.location.href = response.url;
                        } else {
                            // If no URL (e.g. read-only notification), just fetch new list or remove item
                            fetchNotifications();
                        }
                    });
                }
            @endauth

            // Global Confirmation Helper
            window.confirmAction = function (event, formId, title = 'Are you sure?', text = "You won't be able to revert this!", icon = 'warning') {
                event.preventDefault();
                Swal.fire({
                    title: title,
                    text: text,
                    icon: icon,
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, proceed!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById(formId).submit();
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>