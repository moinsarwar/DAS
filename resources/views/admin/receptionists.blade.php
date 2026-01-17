@extends('layouts.app')

@section('title', 'Receptionists')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-heading mb-1">Manage Receptionists</h2>
            <p class="text-muted">Create and manage reception staff accounts.</p>
        </div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addReceptionistModal">
            <i class="bi bi-plus-lg me-1"></i> Add Receptionist
        </button>
    </div>

    <!-- Stats or Info Panel -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center me-3"
                    style="width: 48px; height: 48px;">
                    <i class="bi bi-person-workspace fs-4"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0">Total Receptionists</h6>
                    <small class="text-muted">Active staff members</small>
                </div>
            </div>
            <h2 class="fw-bold mb-0 text-heading">{{ $receptionists->count() }}</h2>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 w-100">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Name</th>
                            <th>Contact Info</th>
                            <th>Joined Date</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($receptionists as $receptionist)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-3 text-uppercase fw-bold text-primary"
                                            style="width: 40px; height: 40px;">
                                            {{ substr($receptionist->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-bold">{{ $receptionist->name }}</h6>
                                            <span class="badge bg-light text-muted border">Role: Receptionist</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column small">
                                        <span class="mb-1"><i
                                                class="bi bi-envelope text-muted me-2"></i>{{ $receptionist->email }}</span>
                                        <span><i
                                                class="bi bi-phone text-muted me-2"></i>{{ $receptionist->mobile_number }}</span>
                                    </div>
                                </td>
                                <td>{{ $receptionist->created_at->format('M d, Y') }}</td>
                                <td class="text-end pe-4">
                                    <form id="delete-rec-{{ $receptionist->id }}"
                                        action="{{ route('admin.receptionists.delete', $receptionist->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-outline-danger"
                                            onclick="confirmAction(event, 'delete-rec-{{ $receptionist->id }}', 'Remove Receptionist?', 'This action cannot be undone!', 'error')">
                                            <i class="bi bi-trash"></i> Remove
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-person-x fs-1 opacity-50"></i>
                                        <p class="mt-2 text-muted">No receptionists found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Receptionist Modal -->
    <div class="modal fade" id="addReceptionistModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Add New Receptionist</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.receptionists.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="John Doe">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control" required
                                placeholder="reception@hospital.com">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control" required
                                placeholder="03XX-XXXXXXX">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required minlength="8"
                                placeholder="********">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection