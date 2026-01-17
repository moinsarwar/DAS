@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold"><i class="bi bi-calendar-check text-primary me-2"></i>My Appointments</h2>
            <p class="text-muted">View and manage all your patient appointments.</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <form action="{{ route('doctor.appointments') }}" method="GET" class="d-flex gap-2">
                <input type="date" name="date" class="form-control" value="{{ $date ?? '' }}">
                <button type="submit" class="btn btn-primary">Filter</button>
                @if($date)
                    <a href="{{ route('doctor.appointments') }}" class="btn btn-outline-secondary">Clear</a>
                @endif
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">
                @if($date)
                    Appointments for {{ date('F j, Y', strtotime($date)) }}
                @else
                    All Appointments
                @endif
            </h5>
        </div>
        <div class="card-body p-0">
            @if($appointments->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                    <p class="mt-3">No appointments found.</p>
                </div>
            @else
                <table class="table table-hover mb-0 w-100">
                    <thead class="table-light">
                        <tr>
                            <th>Patient</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Prescription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($appointments as $app)
                            <tr>
                                <td>
                                    <div class="fw-bold">{{ $app->patient->name }}</div>
                                    <div class="small text-muted">{{ $app->patient->email }}</div>
                                    <div class="small text-primary fw-bold">{{ $app->patient->mobile_number }}</div>
                                </td>
                                <td>
                                    {{ date('M j, Y', strtotime($app->appointment_date)) }} ({{ $app->schedule->day }})<br>
                                    <span
                                        class="badge bg-light text-dark border">{{ date('h:i A', strtotime($app->time_slot)) }}</span>
                                </td>
                                <td>
                                    @php
                                        $badgeClass = match ($app->status) {
                                            'Pending' => 'badge-pending',
                                            'Approved' => 'badge-approved',
                                            'Checked' => 'badge-checked',
                                            'Denied' => 'badge-denied',
                                            default => 'bg-secondary text-white'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }}">{{ $app->status }}</span>
                                </td>
                                <td>
                                    @if($app->status == 'Checked')
                                        @if($app->prescription)
                                            <div class="d-flex gap-1">
                                                <span class="badge badge-checked me-1"><i class="bi bi-check-circle"></i> Added</span>
                                                <a href="{{ route('doctor.prescription.form', $app->id) }}"
                                                    class="btn btn-sm btn-outline-secondary py-0" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="{{ route('doctor.prescription.print', $app->id) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-dark py-0" title="Print">
                                                    <i class="bi bi-printer"></i>
                                                </a>
                                            </div>
                                        @else
                                            <a href="{{ route('doctor.prescription.form', $app->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-plus-circle"></i> Add
                                            </a>
                                        @endif
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-1 flex-wrap">
                                        @if($app->status == 'Pending')
                                            <form action="{{ route('doctor.appointments.approve', $app->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-success">Approve</button>
                                            </form>
                                            <form action="{{ route('doctor.appointments.deny', $app->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger">Deny</button>
                                            </form>
                                        @endif
                                        @if($app->status == 'Approved')
                                            <form action="{{ route('doctor.appointments.check', $app->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-success">Mark Checked</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('doctor.patient.history', $app->patient->id) }}"
                                            class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-clock-history"></i>
                                        </a>
                                        @if($app->status !== 'Checked')
                                            <form id="delete-app-{{ $app->id }}"
                                                action="{{ route('doctor.appointments.delete', $app->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmAction(event, 'delete-app-{{ $app->id }}', 'Delete Appointment?', 'Are you sure?', 'error')"><i
                                                        class="bi bi-trash"></i></button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Initial check time (formatted as YYYY-MM-DD HH:MM:SS)
            // We use UTC or server time as base. To be safe, let server handle "created > last_check"
            // and we just pass the *current client time* - wait, client time might differ.
            // Safer: Get server time on load.
            let lastCheck = "{{ now()->format('Y-m-d H:i:s') }}";

            setInterval(function () {
                fetch("{{ route('doctor.appointments.check_new') }}?last_check=" + lastCheck)
                    .then(response => response.json())
                    .then(data => {
                        if (data.new_appointments > 0) {
                            // Play sound (optional)
                            // let audio = new Audio('notification.mp3'); audio.play();

                            Swal.fire({
                                title: 'New Appointment!',
                                text: `You have ${data.new_appointments} new appointment request(s).`,
                                icon: 'info',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 5000,
                                timerProgressBar: true,
                                didOpen: (toast) => {
                                    toast.addEventListener('mouseenter', Swal.stopTimer)
                                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                                }
                            }).then(() => {
                                // Reload to show new data
                                window.location.reload();
                            });

                            // Update lastCheck to avoid loop if user doesn't reload immediately
                            // In reality, if we reload, this script restarts.
                        }
                    })
                    .catch(error => console.error('Error checking appointments:', error));
            }, 5000); // Check every 5 seconds
        });
    </script>
@endpush