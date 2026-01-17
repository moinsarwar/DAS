@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-6">
            <div class="text-center mb-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 64px; height: 64px;">
                    <i class="bi bi-person-plus fs-2"></i>
                </div>
                <h3 class="fw-bold">Create Account</h3>
                <p class="text-muted">Join us to manage your health seamlessly</p>
            </div>

            <div class="card border-0 shadow-lg">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('register') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label text-muted small fw-bold text-uppercase">Full Name</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="bi bi-person"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0 ps-0 bg-light"
                                        placeholder="John Doe" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0 ps-0 bg-light"
                                        placeholder="name@example.com" required>
                                </div>
                                @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Mobile Number</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="bi bi-phone"></i></span>
                                    <input type="text" name="mobile_number"
                                        class="form-control border-start-0 ps-0 bg-light" placeholder="+123456789" required>
                                </div>
                                @error('mobile_number') <small class="text-danger">{{ $message }}</small> @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="bi bi-lock"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0 ps-0 bg-light"
                                        placeholder="••••••••" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold text-uppercase">Confirm Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i
                                            class="bi bi-lock-fill"></i></span>
                                    <input type="password" name="password_confirmation"
                                        class="form-control border-start-0 ps-0 bg-light" placeholder="••••••••" required>
                                </div>
                            </div>
                        </div>

                        <button type="submit"
                            class="btn btn-primary w-100 py-2 fw-bold shadow-sm mt-4 mb-3">Register</button>

                        <div class="text-center">
                            <span class="text-muted">Already have an account?</span>
                            <a href="{{ route('login') }}" class="text-decoration-none fw-bold">Sign In</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection