@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="row">
        <div class="col-md-12 mb-4">
            <h2 class="fw-bold text-dark">Administrator Dashboard</h2>
            <p class="text-muted">Complete overview and management of the appointment system.</p>
        </div>
    </div>

    <div class="row">
        <!-- Doctors Card -->
        <div class="col-md-4 col-lg-2 mb-4">
            <a href="{{ route('admin.doctors') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100"
                    style="background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);">
                    <div class="card-body py-4 text-center text-white">
                        <i class="bi bi-heart-pulse mb-2" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mb-0">{{ $stats['doctors'] }}</h2>
                        <p class="small mb-0 opacity-75">Doctors</p>
                    </div>
                </div>
            </a>
        </div>
        <!-- Patients Card -->
        <div class="col-md-4 col-lg-2 mb-4">
            <a href="{{ route('admin.patients') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100"
                    style="background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);">
                    <div class="card-body py-4 text-center text-white">
                        <i class="bi bi-people mb-2" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mb-0">{{ $stats['patients'] }}</h2>
                        <p class="small mb-0 opacity-75">Patients</p>
                    </div>
                </div>
            </a>
        </div>
        <!-- Appointments Card -->
        <div class="col-md-4 col-lg-2 mb-4">
            <a href="{{ route('admin.appointments') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100"
                    style="background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);">
                    <div class="card-body py-4 text-center text-white">
                        <i class="bi bi-calendar-check mb-2" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mb-0">{{ $stats['appointments'] }}</h2>
                        <p class="small mb-0 opacity-75">Appointments</p>
                    </div>
                </div>
            </a>
        </div>
        <!-- Pending Card -->
        <div class="col-md-4 col-lg-2 mb-4">
            <a href="{{ route('admin.appointments') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100"
                    style="background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);">
                    <div class="card-body py-4 text-center text-white">
                        <i class="bi bi-hourglass-split mb-2" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mb-0">{{ $stats['pending'] }}</h2>
                        <p class="small mb-0 opacity-75">Pending</p>
                    </div>
                </div>
            </a>
        </div>
        <!-- Categories Card -->
        <div class="col-md-4 col-lg-2 mb-4">
            <a href="{{ route('admin.categories') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100"
                    style="background: linear-gradient(135deg, #5a5c69 0%, #373840 100%);">
                    <div class="card-body py-4 text-center text-white">
                        <i class="bi bi-tags mb-2" style="font-size: 2rem;"></i>
                        <h2 class="fw-bold mb-0">{{ $stats['categories'] }}</h2>
                        <p class="small mb-0 opacity-75">Categories</p>
                    </div>
                </div>
            </a>
        </div>
        <!-- Quick Actions -->
        <div class="col-md-4 col-lg-2 mb-4">
            <a href="{{ route('admin.doctors') }}" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100"
                    style="background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);">
                    <div class="card-body py-4 text-center text-white">
                        <i class="bi bi-plus-circle mb-2" style="font-size: 2rem;"></i>
                        <h6 class="fw-bold mb-0">Add Doctor</h6>
                        <p class="small mb-0 opacity-75">Quick Action</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-lightning text-warning me-2"></i>Quick Navigation</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.categories') }}" class="btn btn-outline-primary text-start">
                            <i class="bi bi-tags me-2"></i> Manage Doctor Categories
                        </a>
                        <a href="{{ route('admin.doctors') }}" class="btn btn-outline-primary text-start">
                            <i class="bi bi-heart-pulse me-2"></i> Manage Doctors
                        </a>
                        <a href="{{ route('admin.appointments') }}" class="btn btn-outline-primary text-start">
                            <i class="bi bi-calendar-check me-2"></i> View All Appointments
                        </a>
                        <a href="{{ route('admin.patients') }}" class="btn btn-outline-primary text-start">
                            <i class="bi bi-people me-2"></i> View All Patients
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-info-circle text-info me-2"></i>System Information</h5>
                </div>
                <div class="card-body">
                    <p class="mb-2"><strong>System:</strong> Doctor Appointment System (DAS)</p>
                    <p class="mb-2"><strong>Framework:</strong> Laravel</p>
                    <p class="mb-2"><strong>Database:</strong> MySQL</p>
                    <p class="mb-0"><strong>Current Date:</strong> {{ date('F j, Y') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection