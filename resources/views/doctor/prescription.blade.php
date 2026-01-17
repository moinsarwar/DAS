@extends('layouts.app')

@section('title', 'Prescription Form')

@section('content')
    <div class="row mb-4">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('doctor.appointments') }}">Appointments</a></li>
                    <li class="breadcrumb-item active">Add Prescription</li>
                </ol>
            </nav>
            <h2 class="fw-bold"><i class="bi bi-file-medical text-primary me-2"></i>Add Prescription</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <!-- Appointment Info -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Appointment Details</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Patient:</strong> {{ $appointment->patient->name }}</p>
                            <p class="mb-2"><strong>Email:</strong> {{ $appointment->patient->email }}</p>
                            <p class="mb-0"><strong>Mobile:</strong> {{ $appointment->patient->mobile_number }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>Date:</strong>
                                {{ date('F j, Y', strtotime($appointment->appointment_date)) }}</p>
                            <p class="mb-2"><strong>Time:</strong>
                                {{ date('h:i A', strtotime($appointment->time_slot)) }}
                            </p>
                            <p class="mb-0"><strong>Status:</strong> <span
                                    class="badge bg-success">{{ $appointment->status }}</span></p>
                        </div>
                        @if($appointment->vital)
                            <div class="row mt-3 border-top pt-3">
                                <div class="col-12">
                                    <h6 class="fw-bold text-primary mb-2">Vitals (Recorded by Reception)</h6>
                                    <div class="d-flex flex-wrap gap-3">
                                        <span class="badge bg-light text-dark border">To: <span
                                                class="fw-bold">{{ $appointment->vital->temperature ?? 'N/A' }}</span></span>
                                        <span class="badge bg-light text-dark border">BP: <span
                                                class="fw-bold">{{ $appointment->vital->bp ?? 'N/A' }}</span></span>
                                        <span class="badge bg-light text-dark border">Pulse: <span
                                                class="fw-bold">{{ $appointment->vital->pulse ?? 'N/A' }}</span></span>
                                        <span class="badge bg-light text-dark border">Wt: <span
                                                class="fw-bold">{{ $appointment->vital->weight ?? 'N/A' }}</span></span>
                                        <span class="badge bg-light text-dark border">Ht: <span
                                                class="fw-bold">{{ $appointment->vital->height ?? 'N/A' }}</span></span>
                                    </div>
                                    @if($appointment->vital->notes)
                                        <p class="small text-muted mt-2 mb-0"><strong>Notes:</strong>
                                            {{ $appointment->vital->notes }}</p>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Prescription Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Prescription Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('doctor.prescription.store', $appointment->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label fw-bold">Diagnosis (Problem)</label>
                            <input type="text" name="diagnosis" class="form-control"
                                value="{{ old('diagnosis', $appointment->problem ?? '') }}"
                                placeholder="Enter diagnosis (e.g. Bacterial Infection)">
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Medical Advice / Instructions</label>
                            <textarea name="notes" class="form-control" rows="3"
                                placeholder="Enter advice, precautions, and instructions...">{{ old('notes', $appointment->prescription->notes ?? '') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Prescribed Medicines</label>
                            <textarea name="medicines" class="form-control" rows="6"
                                placeholder="Enter medicines with dosage instructions...
                    Example:
                    1. Paracetamol 500mg - 1 tablet 3 times daily after meals
                    2. Amoxicillin 250mg - 1 capsule 2 times daily for 5 days">{{ $appointment->prescription->medicines ?? '' }}</textarea>
                        </div>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Save Prescription
                            </button>
                            @if($appointment->prescription)
                                <a href="{{ route('doctor.prescription.print', $appointment->id) }}" target="_blank"
                                    class="btn btn-outline-dark">
                                    <i class="bi bi-printer me-1"></i> Print
                                </a>
                            @endif
                            <a href="{{ route('doctor.appointments') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Patient History Sidebar -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Patient History</h6>
                </div>
                <div class="card-body p-0">
                    <a href="{{ route('doctor.patient.history', $appointment->patient->id) }}"
                        class="btn btn-link w-100 text-start p-3">
                        View Full Medical History →
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection