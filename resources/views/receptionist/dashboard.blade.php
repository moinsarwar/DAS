@extends('layouts.app')

@section('title', 'Reception Dashboard')

@section('content')
    <div class="row g-4">
        <!-- Welcome Panel -->
        <div class="col-12">
            <div class="card bg-primary text-white border-0 shadow-sm">
                <div class="card-body p-4 d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="fw-bold mb-1">Reception Desk</h2>
                        <p class="mb-0 opacity-75">Manage patients and book appointments efficiently.</p>
                    </div>
                    <i class="bi bi-person-workspace fs-1 opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Patient Check-in Panel -->
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-heading"><i class="bi bi-search text-primary me-2"></i>Patient Check-in
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">Search for an existing patient by their CNIC or Mobile Number.</p>

                    <form id="checkPatientForm">
                        @csrf
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-light"><i class="bi bi-person-vcard"></i></span>
                            <input type="text" id="check_query" class="form-control"
                                placeholder="Enter CNIC or Mobile Number" required>
                            <button class="btn btn-primary" type="submit">Search</button>
                        </div>
                        <div id="checkResult" class="d-none mt-3">
                            <!-- Dynamic Result Here -->
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- New Patient Registration -->
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-heading"><i class="bi bi-person-plus text-success me-2"></i>New Patient
                        Registration</h5>
                </div>
                <div class="card-body">
                    <form id="registerPatientForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Patient Name" required>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">CNIC</label>
                                <input type="text" name="cnic" class="form-control" placeholder="XXXXX-XXXXXXX-X" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">Mobile Number</label>
                                <input type="text" name="mobile_number" class="form-control" placeholder="03XX-XXXXXXX"
                                    required>
                            </div>
                        </div>
                        <div class="alert alert-info small py-2">
                            <i class="bi bi-info-circle me-1"></i> MR Number will be auto-generated.
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Register & Book Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                // Check Patient
                $('#checkPatientForm').submit(function (e) {
                    e.preventDefault();
                    let query = $('#check_query').val();
                    let btn = $(this).find('button');

                    btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');

                    $.ajax({
                        url: "{{ route('receptionist.check.patient') }}",
                        method: "POST",
                        data: { _token: "{{ csrf_token() }}", query: query },
                        success: function (res) {
                            btn.prop('disabled', false).text('Search');
                            if (res.status === 'found') {
                                let html = '<div class="list-group">';
                                res.patients.forEach(p => {
                                    // Construct redirect URL manually since JS can't use route() with dynamic ID easily without placeholder
                                    let bookUrl = "{{ url('/receptionist/book') }}/" + p.id;

                                    html += `
                                                                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                                                    <div>
                                                                        <h6 class="mb-0 fw-bold">${p.name}</h6>
                                                                        <small class="text-muted">MR: ${p.mr_number} | Mobile: ${p.mobile_number}</small>
                                                                    </div>
                                                                    <a href="${bookUrl}" class="btn btn-sm btn-outline-primary">Select</a>
                                                                </div>
                                                            `;
                                });
                                html += '</div>';
                                $('#checkResult').removeClass('d-none').html(html);
                            } else {
                                $('#checkResult').removeClass('d-none').html(`
                                                            <div class="alert alert-warning">
                                                                Patient not found. Please register as a new patient.
                                                            </div>
                                                        `);
                            }
                        },
                        error: function (xhr) {
                            btn.prop('disabled', false).text('Search');
                            // Handle redirect if sent by storePatient (unlikely here but good capability)
                            if (xhr.responseJSON && xhr.responseJSON.redirect) {
                                window.location.href = xhr.responseJSON.redirect;
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: 'Error checking patient.',
                                });
                            }
                        }
                    });
                });

                // Register Patient
                $('#registerPatientForm').submit(function (e) {
                    e.preventDefault();
                    let form = $(this);
                    let btn = form.find('button[type="submit"]');

                    btn.prop('disabled', true).html('Processing...');

                    $.ajax({
                        url: "{{ route('receptionist.store.patient') }}",
                        method: "POST",
                        data: form.serialize(),
                        success: function (res) {
                            if (res.status === 'success') {
                                window.location.href = res.redirect;
                            } else {
                                btn.prop('disabled', false).text('Register & Book Appointment');
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Error registering patient.'
                                });
                            }
                        },
                        error: function (xhr) {
                            btn.prop('disabled', false).text('Register & Book Appointment');
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: xhr.responseJSON.message || 'Error occurred.'
                            });
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection