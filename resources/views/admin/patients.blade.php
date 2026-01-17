@extends('layouts.app')

@section('title', 'Patients')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-heading mb-1"><i class="bi bi-people text-primary me-2"></i>All Patients</h2>
            <p class="text-muted">View and manage all registered patients.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPatientModal">
            <i class="bi bi-plus-lg me-1"></i> Add Patient
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Registered Patients</h5>
        </div>
        <div class="card-body p-0">
            @if($patients->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-people" style="font-size: 3rem;"></i>
                    <p class="mt-3">No patients registered yet.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover mb-0 w-100">
                        <thead class="table-light">
                            <tr>
                                <th>MR Number</th>
                                <th>Name</th>
                                <th>CNIC</th>
                                <th>Mobile</th>
                                <th>Appointments</th>
                                <th>Registered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patients as $patient)
                                <tr>
                                    <td><span class="badge bg-light text-dark border">{{ $patient->mr_number ?? 'N/A' }}</span></td>
                                    <td class="fw-bold">{{ $patient->name }}</td>
                                    <td>{{ $patient->cnic ?? 'N/A' }}</td>
                                    <td>
                                        <span class="text-primary fw-bold">{{ $patient->mobile_number ?? 'N/A' }}</span>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $patient->patient_appointments_count }}</span>
                                    </td>
                                    <td>{{ $patient->created_at->format('M j, Y') }}</td>
                                    <td>
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.patients.details', $patient->id) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="bi bi-eye me-1"></i> View Details
                                            </a>
                                            <form id="delete-patient-{{ $patient->id }}"
                                                action="{{ route('admin.patients.delete', $patient->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmAction(event, 'delete-patient-{{ $patient->id }}', 'Delete Patient?', 'Are you sure you want to remove this patient?', 'warning')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
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

    <!-- Add Patient Modal -->
    <div class="modal fade" id="addPatientModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add New Patient</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.patients.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="Patient Name">
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">CNIC</label>
                                <input type="text" name="cnic" class="form-control" required placeholder="XXXXX-XXXXXXX-X">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile_number" class="form-control" required
                                    placeholder="03XX-XXXXXXX">
                            </div>
                        </div>
                        <div class="alert alert-info small py-2">
                            <i class="bi bi-info-circle me-1"></i> MR Number will be auto-generated.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Patient</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection