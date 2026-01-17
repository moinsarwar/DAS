@extends('layouts.app')

@section('title', 'Doctor Dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 mb-4">
            <h2 class="fw-bold">Welcome, Dr. {{ auth()->user()->name }}</h2>
            <p class="text-muted">Your daily overview and patient management dashboard.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row text-center mb-5 g-4">
        <div class="col-md-3">
            <a href="{{ route('doctor.schedules') }}" class="text-decoration-none">
                <div class="card stats-card bg-gradient-primary h-100 text-white shadow-sm hover-lift">
                    <div class="card-body py-4">
                        <i class="bi bi-calendar-week mb-2 opacity-75" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mb-0">{{ $stats['schedules'] }}</h2>
                        <p class="small mb-0 opacity-75">Active Schedules</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('doctor.appointments') }}" class="text-decoration-none">
                <div class="card stats-card bg-gradient-success h-100 text-white shadow-sm hover-lift">
                    <div class="card-body py-4">
                        <i class="bi bi-calendar-check mb-2 opacity-75" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mb-0">{{ $stats['total_appointments'] }}</h2>
                        <p class="small mb-0 opacity-75">Total Appointments</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('doctor.appointments', ['status' => 'Pending']) }}" class="text-decoration-none">
                <div class="card stats-card bg-gradient-warning h-100 text-white shadow-sm hover-lift">
                    <div class="card-body py-4">
                        <i class="bi bi-hourglass-split mb-2 opacity-75" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mb-0">{{ $stats['pending'] }}</h2>
                        <p class="small mb-0 opacity-75">Pending Approval</p>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="{{ route('doctor.profile') }}" class="text-decoration-none">
                <div class="card stats-card bg-gradient-info h-100 text-white shadow-sm hover-lift">
                    <div class="card-body py-4">
                        <i class="bi bi-person-gear mb-2 opacity-75" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mb-0"><i class="bi bi-arrow-right"></i></h2>
                        <p class="small mb-0 opacity-75">Update Profile</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Today's Waiting Patients -->
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-people text-primary me-2"></i>Today's Waiting Patients</h5>
                    <span class="badge bg-primary rounded-pill">{{ $stats['todays_count'] }} patients</span>
                </div>
                <div class="card-body p-0">
                    @if($todaysPatients->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-calendar-x" style="font-size: 3rem;"></i>
                            <p class="mt-3 mb-0">No patients scheduled for today.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Time Slot</th>
                                        <th>Patient</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todaysPatients as $app)
                                        <tr>
                                            <td>
                                                <span class="badge bg-light text-dark border fw-bold">
                                                    {{ date('h:i A', strtotime($app->time_slot)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $app->patient->name }}</div>
                                            </td>
                                            <td>
                                                <div class="small text-muted">{{ $app->patient->email }}</div>
                                                <div class="small text-primary fw-bold">{{ $app->patient->mobile_number }}</div>
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
                                                <div class="d-flex gap-1">
                                                    @if($app->status == 'Pending')
                                                        <form action="{{ route('doctor.appointments.approve', $app->id) }}"
                                                            method="POST">
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
                                                        <i class="bi bi-clock-history"></i> History
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection