@extends('layouts.app')

@section('title', 'Medical History')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <h2 class="fw-bold"><i class="bi bi-clock-history text-primary me-2"></i>My Medical History</h2>
            <p class="text-muted">View all your past appointments and prescriptions.</p>
        </div>
    </div>

    <div class="row">
        <!-- Appointments -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-check me-2"></i>Appointment History</h5>
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
                                    <th>Doctor</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($appointments as $app)
                                    <tr>
                                        <td>
                                            <div class="fw-bold">Dr. {{ $app->doctor->user->name ?? 'N/A' }}</div>
                                            <small class="text-muted">{{ $app->schedule->day ?? '' }}</small>
                                        </td>
                                        <td>
                                            {{ date('M j, Y', strtotime($app->appointment_date)) }}<br>
                                            <small class="text-muted">{{ date('h:i A', strtotime($app->time_slot)) }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $badgeClass = match($app->status) {
                                                    'Pending' => 'badge-pending',
                                                    'Approved' => 'badge-approved',
                                                    'Checked' => 'badge-checked',
                                                    'Denied' => 'badge-denied',
                                                    default => 'bg-secondary text-white'
                                                };
                                            @endphp
                                            <span class="badge {{ $badgeClass }}">{{ $app->status }}</span>
                                            @if($app->prescription)
                                                <br><a href="{{ route('patient.prescription.view', $app->id) }}" class="small">View Rx</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- Prescriptions -->
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-file-medical me-2"></i>My Prescriptions</h5>
                </div>
                <div class="card-body">
                    @if($prescriptions->isEmpty())
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-file-x" style="font-size: 3rem;"></i>
                            <p class="mt-3">No prescriptions found.</p>
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
                                </div>
                                <hr>
                                <div class="mb-2">
                                    <strong class="text-muted small">DIAGNOSIS/NOTES:</strong>
                                    <p class="mb-0">{{ $prescription->notes ?? 'No notes' }}</p>
                                </div>
                                <div>
                                    <strong class="text-muted small">MEDICINES:</strong>
                                    <pre class="mb-0 mt-1" style="white-space: pre-wrap; font-family: inherit;">{{ $prescription->medicines ?? 'No medicines prescribed' }}</pre>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
