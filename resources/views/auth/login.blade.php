@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                    style="width: 64px; height: 64px;">
                    <i class="bi bi-box-arrow-in-right fs-2"></i>
                </div>
                <h3 class="fw-bold">Welcome Back</h3>
                <p class="text-muted">Sign in to access your account</p>
            </div>

            <div class="card border-0 shadow-lg">
                <div class="card-body p-4 p-md-5">
                    <form action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Email Address / CNIC</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="bi bi-person-badge"></i></span>
                                <input type="text" name="email" class="form-control border-start-0 ps-0 bg-light"
                                    placeholder="email@example.com OR XXXXXXXXXXXXX" required>
                            </div>
                            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-muted small fw-bold text-uppercase">Password / MR Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i
                                        class="bi bi-key"></i></span>
                                <input type="password" name="password" class="form-control border-start-0 ps-0 bg-light"
                                    placeholder="•••••••• OR MR-202X-XXXX" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm mb-3">Sign In</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection