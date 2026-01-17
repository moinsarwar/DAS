@extends('layouts.app')

@section('title', 'Patient Details')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.patients') }}">Patients</a></li>
                    <li class="breadcrumb-item active">{{ $patient->name }}</li>
                </ol>
            </nav>
            <h2 class="fw-bold"><i class="bi bi-person-lines-fill text-primary me-2"></i>Patient Details</h2>
        </div>
    </div>

    <div class="row">
        <!-- Patient Info Card -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-circle me-2"></i>Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 100px; height: 100px;">
                            <i class="bi bi-person" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    <h4 class="text-center fw-bold">{{ $patient->name }}</h4>
                    <hr>
                    <p class="mb-2"><i class="bi bi-envelope me-2 text-muted"></i>{{ $patient->email }}</p>
                    <p class="mb-2"><i class="bi bi-telephone me-2 text-muted"></i><strong
                            class="text-primary">{{ $patient->mobile_number ?? 'N/A' }}</strong></p>
                    <p class="mb-0"><i class="bi bi-calendar me-2 text-muted"></i>Registered:
                        {{ $patient->created_at->format('M j, Y') }}
                    </p>
                </div>
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between">
                        <span>Total Appointments</span>
                        <span class="badge bg-primary">{{ $appointments->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Appointments & Prescriptions -->
        <div class="col-md-8">
            <!-- Appointments -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-check me-2"></i>Appointment History</h5>
                </div>
                <div class="card-body p-0">
                    @if($appointments->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <p class="mb-0">No appointments found.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Doctor</th>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $app)
                                        <tr>
                                            <td>Dr. {{ $app->doctor->user->name ?? 'N/A' }}</td>
                                            <td>{{ date('M j, Y', strtotime($app->appointment_date)) }}</td>
                                            <td>{{ date('h:i A', strtotime($app->time_slot)) }}</td>
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
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Prescriptions -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-file-medical me-2"></i>Prescriptions & Medicines</h5>
                </div>
                <div class="card-body">
                    @if($prescriptions->isEmpty())
                        <div class="text-center py-4 text-muted">
                            <p class="mb-0">No prescriptions found.</p>
                        </div>
                    @else
                        @foreach($prescriptions as $prescription)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <strong>Dr. {{ $prescription->doctor->user->name ?? 'N/A' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $prescription->appointment ? date('M j, Y', strtotime($prescription->appointment->appointment_date)) : 'N/A' }}
                                        </small>
                                    </div>
                                    <span class="badge badge-checked">Prescription</span>
                                </div>
                                <hr>
                                <div class="mb-2">
                                    <strong>Notes/Diagnosis:</strong>
                                    <p class="mb-0 text-muted">{{ $prescription->notes ?? 'No notes' }}</p>
                                </div>
                                <div>
                                    <strong>Medicines:</strong>
                                    <pre class="mb-0 mt-1 text-muted"
                                        style="white-space: pre-wrap; font-family: inherit;">{{ $prescription->medicines ?? 'No medicines prescribed' }}</pre>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection