@extends('layouts.app')

@section('title', 'Patient History')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.appointments') }}">Appointments</a></li>
                    <li class="breadcrumb-item active">Patient History</li>
                </ol>
            </nav>
            <h2 class="fw-bold"><i class="bi bi-clock-history text-primary me-2"></i>Patient Medical History</h2>
        </div>
    </div>

    <div class="row">
        <!-- Patient Info -->
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-person-circle me-2"></i>Patient Information</h5>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                            style="width: 80px; height: 80px;">
                            <i class="bi bi-person" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                    <h5 class="text-center fw-bold">{{ $patient->name }}</h5>
                    <hr>
                    <p class="mb-2"><i class="bi bi-envelope me-2 text-muted"></i>{{ $patient->email }}</p>
                    <p class="mb-2"><i class="bi bi-telephone me-2 text-muted"></i>{{ $patient->mobile_number ?? 'N/A' }}
                    </p>
                    <p class="mb-0"><i class="bi bi-calendar me-2 text-muted"></i>Registered:
                        {{ $patient->created_at->format('M j, Y') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Appointment History -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Appointment History with You</h5>
                </div>
                <div class="card-body p-0">
                    @if($appointments->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <p>No appointment history found.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Time</th>
                                        <th>Status</th>
                                        <th>Prescription</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($appointments as $app)
                                        <tr>
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
                                            <td>
                                                @if($app->prescription)
                                                    <button class="btn btn-sm btn-outline-info" type="button" data-bs-toggle="collapse"
                                                        data-bs-target="#prescription{{ $app->id }}">
                                                        View Details
                                                    </button>
                                                @else
                                                    <span class="text-muted small">—</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($app->prescription)
                                            <tr class="collapse" id="prescription{{ $app->id }}">
                                                <td colspan="4" class="bg-light">
                                                    <div class="p-3">
                                                        <div class="mb-3">
                                                            <strong>Notes/Diagnosis:</strong>
                                                            <p class="mb-0 mt-1">{{ $app->prescription->notes ?? 'No notes' }}</p>
                                                        </div>
                                                        <div>
                                                            <strong>Medicines:</strong>
                                                            <pre class="mb-0 mt-1"
                                                                style="white-space: pre-wrap; font-family: inherit;">{{ $app->prescription->medicines ?? 'No medicines prescribed' }}</pre>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
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