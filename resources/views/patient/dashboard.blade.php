@extends('layouts.app')

@section('title', 'Patient Dashboard')

@section('content')
    <div class="row mb-5 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold mb-1">Welcome, {{ auth()->user()->name }}</h2>
            <p class="text-muted">Book appointments and manage your healthcare journey.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <a href="{{ route('patient.profile') }}" class="btn btn-outline-primary me-2">
                <i class="bi bi-person-gear"></i> Profile
            </a>
            <a href="{{ route('patient.history') }}" class="btn btn-outline-info">
                <i class="bi bi-clock-history"></i> History
            </a>
        </div>
    </div>

    <!-- My Appointments -->
    <div class="card border-0 shadow-sm mb-5">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold text-heading"><i class="bi bi-calendar-check text-primary me-2"></i>My Appointments</h5>
            <span class="badge bg-primary rounded-pill">{{ $myAppointments->count() }}</span>
        </div>
        <div class="card-body p-0">
            @if($myAppointments->isEmpty())
                <div class="text-center py-5">
                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                        style="width: 80px; height: 80px;">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 2rem;"></i>
                    </div>
                    <p class="text-muted mb-0">No upcoming appointments.</p>
                    <small class="text-muted">Book a consultation with a doctor below.</small>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0 w-100">
                        <thead>
                            <tr>
                                <th>Doctor</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th>Prescription</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($myAppointments as $app)
                                <tr>
                                    <td>
                                        <div class="fw-bold text-heading">Dr. {{ $app->doctor->user->name }}</div>
                                        <small class="text-muted">{{ $app->schedule->day ?? '' }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ date('M j, Y', strtotime($app->appointment_date)) }}</span>
                                            <span class="text-muted small">{{ date('h:i A', strtotime($app->time_slot)) }}</span>
                                        </div>
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
                                        @if($app->prescription)
                                            <a href="{{ route('patient.prescription.view', $app->id) }}"
                                                class="btn btn-sm btn-outline-success">
                                                <i class="bi bi-file-medical"></i> View Rx
                                            </a>
                                        @else
                                            <span class="text-muted small">—</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($app->status !== 'Checked')
                                            <form id="cancel-form-{{ $app->id }}"
                                                action="{{ route('patient.appointments.delete', $app->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmAction(event, 'cancel-form-{{ $app->id }}', 'Cancel Appointment?', 'Are you sure you want to cancel?', 'warning')">Cancel</button>
                                            </form>
                                        @else
                                            <span class="badge bg-light text-muted border">Completed</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>

    <!-- Available Doctors -->
    <div class="mb-4">
        <h4 class="fw-bold text-heading"><i class="bi bi-heart-pulse text-danger me-2"></i>Available Doctors</h4>
        <p class="text-muted">Select a qualified specialist for your consultation.</p>
    </div>

    @foreach($categories as $category)
        @if($category->doctors->isNotEmpty())
            <div class="mb-5">
                <h5 class="fw-bold text-primary mb-3 border-bottom pb-2" style="border-color: var(--border-color) !important;">
                    {{ $category->name }}
                </h5>
                <div class="row g-4">
                    @foreach($category->doctors as $doctor)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 shadow-sm hover-lift">
                                <div class="card-body p-4">
                                    <div class="d-flex align-items-center mb-4">
                                        <div class="bg-primary bg-gradient rounded-circle d-flex align-items-center justify-content-center text-white me-3 shadow-sm"
                                            style="width: 56px; height: 56px; font-size: 1.5rem;">
                                            <i class="bi bi-person-fill"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold text-heading">Dr. {{ $doctor->user->name }}</h6>
                                            <span class="badge bg-light text-primary border">{{ $category->name }}</span>
                                        </div>
                                    </div>

                                    @if($doctor->bio)
                                        <p class="text-muted small mb-4" style="min-height: 40px;">
                                            {{ Str::limit($doctor->bio, 80) }}
                                        </p>
                                    @else
                                        <p class="text-muted small mb-4 fst-italic">No biography available.</p>
                                    @endif

                                    <div class="d-grid">
                                        <a href="{{ route('patient.doctor.details', $doctor->id) }}" class="btn btn-primary">
                                            Book Appointment
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach

    @if($categories->every(fn($c) => $c->doctors->isEmpty()))
        <div class="alert alert-info border-0 shadow-sm">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                <div>
                    <strong>No doctors available at the moment.</strong>
                    <p class="mb-0 small">Please check back later for updated schedules.</p>
                </div>
            </div>
        </div>
    @endif
@endsection