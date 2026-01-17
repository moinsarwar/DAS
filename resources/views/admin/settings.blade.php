@extends('layouts.app')

@section('title', 'Clinic Settings')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Clinic Settings</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4 text-center">
                            <label class="form-label d-block fw-bold">Current Logo</label>
                            @if($settings && $settings->logo_path)
                                <img src="{{ asset('storage/' . $settings->logo_path) }}" alt="Logo" class="img-thumbnail mb-3"
                                    style="max-height: 100px;">
                            @else
                                <div class="text-muted fst-italic mb-3">No logo uploaded</div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="phone" class="form-control"
                                value="{{ old('phone', $settings->phone ?? '') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea name="address" class="form-control" rows="3"
                                required>{{ old('address', $settings->address ?? '') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload New Logo (Optional)</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                            <div class="form-text">Recommended size: 200x200 px. Supported formats: JPG, PNG.</div>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary">Update Settings</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection