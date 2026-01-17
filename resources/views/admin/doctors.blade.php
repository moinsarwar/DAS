@extends('layouts.app')

@section('title', 'Doctors')

@section('content')
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold"><i class="bi bi-heart-pulse text-primary me-2"></i>Manage Doctors</h2>
            <p class="text-muted">Add, edit, and manage doctor accounts.</p>
        </div>
        <div class="col-md-4 text-end">
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                <i class="bi bi-plus-circle me-1"></i> Add New Doctor
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="mb-0 fw-bold">Doctor List</h5>
        </div>
        <div class="card-body p-0">
            @if($doctors->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-person-x" style="font-size: 3rem;"></i>
                    <p class="mt-3">No doctors added yet.</p>
                </div>
            @else
                <table class="table table-hover mb-0 w-100">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Category</th>
                            <th>Bio</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($doctors as $doctor)
                            <tr>
                                <td class="fw-bold">Dr. {{ $doctor->user->name }}</td>
                                <td>{{ $doctor->user->email }}</td>
                                <td><span class="badge bg-info">{{ $doctor->category->name }}</span></td>
                                <td class="text-muted small">{{ Str::limit($doctor->bio, 50) }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.doctors.edit', $doctor->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form id="delete-doctor-{{ $doctor->id }}"
                                            action="{{ route('admin.doctors.delete', $doctor->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="button" class="btn btn-sm btn-outline-danger"
                                                onclick="confirmAction(event, 'delete-doctor-{{ $doctor->id }}', 'Delete Doctor?', 'This will verify removing the doctor account.', 'warning')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

    <!-- Add Doctor Modal -->
    <div class="modal fade" id="addDoctorModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.doctors.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold">Add New Doctor</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control" required
                                placeholder="03XX-XXXXXXX">
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Category / Specialization</label>
                            <select name="category_id" id="category_select" class="form-select" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Bio (Optional)</label>
                            <textarea name="bio" class="form-control" rows="3"
                                placeholder="Qualifications, experience, etc."></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Consultation Fee</label>
                            <input type="number" name="fees" class="form-control" required min="0" value="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle me-1"></i> Save Doctor
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection