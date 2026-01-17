@extends('layouts.app')

@section('title', 'Patient Registration')

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6 col-lg-5">
            <div class="text-center mb-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 64px; height: 64px;">
                    <i class="bi bi-person-plus-fill fs-2"></i>
                </div>
                <h3 class="fw-bold">Patient Registration</h3>
                <p class="text-muted">Register to book appointments and view your history</p>
            </div>

            <div class="card border-0 shadow-lg">
                <div class="card-body p-4 p-md-5">
                    @if(session('success_mr'))
                        <div class="text-center">
                            <div class="mb-4">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Registration Successful!</h4>
                            <p class="text-muted mb-4">Your account has been created. Please save your login details below.</p>

                            <div class="p-3 bg-light rounded mb-4 text-start border">
                                <div class="mb-2">
                                    <small class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">MR Number
                                        (Password)</small>
                                    <div class="fs-4 fw-bold text-primary">{{ session('success_mr') }}</div>
                                </div>
                                <div>
                                    <small class="text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">CNIC (Login
                                        ID)</small>
                                    <div class="fs-5 fw-medium text-dark">{{ session('cnic_display') ?? 'Your CNIC' }}</div>
                                </div>
                            </div>

                            <a href="{{ route('login') }}" class="btn btn-primary w-100 py-2 fw-bold shadow-sm">
                                Go to Login <i class="bi bi-arrow-right ms-2"></i>
                            </a>
                        </div>
                    @else
                        <form action="{{ route('register') }}" method="POST">
                            @csrf

                            <!-- Full Name -->
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">Full Name <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="bi bi-person"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 ps-0 bg-light"
                                        placeholder="John Doe" required value="{{ old('name') }}">
                                </div>
                                @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- CNIC -->
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">CNIC (Login ID) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="bi bi-card-text"></i></span>
                                    <input type="text" name="cnic" class="form-control border-start-0 ps-0 bg-light"
                                        placeholder="1234512345671" required value="{{ old('cnic') }}">
                                </div>
                                <div class="form-text small">don't use dashes e.g. 1234512345671. This will be your Login ID.
                                </div>
                                @error('cnic') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Contact Number -->
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">Contact Number <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="bi bi-telephone"></i></span>
                                    <input type="text" name="mobile_number" class="form-control border-start-0 ps-0 bg-light"
                                        placeholder="+92 300 1234567" required value="{{ old('mobile_number') }}">
                                </div>
                                @error('mobile_number') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold text-uppercase">Email Address
                                    (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0 bg-light"
                                        placeholder="john@example.com" value="{{ old('email') }}">
                                </div>
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <!-- Address -->
                            <div class="mb-4">
                                <label class="form-label text-muted small fw-bold text-uppercase">Address (Optional)</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="bi bi-geo-alt"></i></span>
                                    <textarea name="address" class="form-control border-start-0 ps-0 bg-light" rows="2"
                                        placeholder="House # 123, Street ABC...">{{ old('address') }}</textarea>
                                </div>
                                @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm mb-3">Register</button>
                        </form>

                        <div class="text-center pt-2">
                            <small class="text-muted">Already have an MR Number?</small>
                            <a href="{{ route('login') }}" class="fw-bold text-decoration-none ms-1">Login here</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection