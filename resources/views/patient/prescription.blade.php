@extends('layouts.app')

@section('title', 'View Prescription')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('patient.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('patient.history') }}">History</a></li>
                    <li class="breadcrumb-item active">Prescription</li>
                </ol>
            </nav>
            <h2 class="fw-bold"><i class="bi bi-file-medical text-primary me-2"></i>Prescription Details</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Appointment Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold">Appointment Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Doctor:</strong> Dr. {{ $appointment->doctor->user->name ?? 'N/A' }}</p>
                            <p class="mb-0"><strong>Specialization:</strong>
                                {{ $appointment->doctor->category->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Date:</strong>
                                {{ date('F j, Y', strtotime($appointment->appointment_date)) }}</p>
                            <p class="mb-0"><strong>Time:</strong> {{ date('h:i A', strtotime($appointment->time_slot)) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if($appointment->prescription)
                <!-- Prescription -->
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success text-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-clipboard2-pulse me-2"></i>Prescription</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <h6 class="fw-bold text-muted text-uppercase small">Diagnosis / Doctor's Notes</h6>
                            <div class="bg-light rounded p-3">
                                {{ $appointment->prescription->notes ?? 'No notes provided.' }}
                            </div>
                        </div>

                        <div>
                            <h6 class="fw-bold text-muted text-uppercase small">Prescribed Medicines</h6>
                            <div class="bg-light rounded p-3">
                                <pre
                                    style="white-space: pre-wrap; font-family: inherit; margin: 0;">{{ $appointment->prescription->medicines ?? 'No medicines prescribed.' }}</pre>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-light text-muted small">
                        Prescribed on: {{ $appointment->prescription->created_at->format('F j, Y \a\t h:i A') }}
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    No prescription has been added for this appointment yet.
                </div>
            @endif
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('patient.history') }}" class="btn btn-outline-primary">
                            <i class="bi bi-clock-history me-1"></i> View All History
                        </a>
                        <a href="{{ route('patient.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-house me-1"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection