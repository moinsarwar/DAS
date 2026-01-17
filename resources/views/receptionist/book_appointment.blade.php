@extends('layouts.app')

@section('title', 'Book Appointment')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-heading">Book Appointment</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-light border d-flex align-items-center mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-3"
                            style="width: 48px; height: 48px;">
                            <i class="bi bi-person fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-0">{{ $patient->name }}</h6>
                            <small class="text-muted">MR: {{ $patient->mr_number }} | CNIC: {{ $patient->cnic }} | Mobile:
                                {{ $patient->mobile_number }}</small>
                        </div>
                    </div>

                    <form id="bookingForm" action="{{ route('receptionist.store.appointment', $patient->id) }}"
                        method="POST">
                        @csrf
                        <input type="hidden" name="schedule_id" id="schedule_id_input">
                        <input type="hidden" name="receipt_requested" id="receipt_requested" value="0">
                        <input type="hidden" name="appointment_fee" id="appointment_fee" value="0">

                        <div class="mb-3">
                            <label class="form-label">Select Doctor</label>
                            <select name="doctor_id" id="doctor_select" class="form-select" required>
                                <option value="" disabled selected>Choose a Doctor</option>
                                @foreach($doctors as $doc)
                                    <option value="{{ $doc->id }}">{{ $doc->user->name }}
                                        ({{ $doc->category->name ?? 'General' }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="appointment_date" id="date_select" class="form-control"
                                min="{{ date('Y-m-d') }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Available Slots</label>
                            <select name="time_slot" id="slot_select" class="form-select" disabled required>
                                <option value="">Select Date & Doctor first</option>
                            </select>
                            <div id="loading" class="text-primary small mt-2 d-none">
                                <span class="spinner-border spinner-border-sm me-1"></span> Loading available slots...
                            </div>
                        </div>


                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Confirm Booking</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                let doctorId = '';
                let date = '';
                let currentFee = 0;

                $('#doctor_select').change(function () {
                    doctorId = $(this).val();
                    fetchSlots();
                });

                $('#date_select').change(function () {
                    date = $(this).val();
                    fetchSlots();
                });

                function fetchSlots() {
                    if (!doctorId || !date) return;

                    $('#loading').removeClass('d-none');
                    $('#slot_select').prop('disabled', true).html('<option>Loading...</option>');
                    $('#schedule_id_input').val('');

                    $.ajax({
                        url: `/receptionist/doctor/${doctorId}/slots`,
                        method: 'GET',
                        data: { date: date },
                        success: function (response) {
                            $('#loading').addClass('d-none');
                            let options = '<option value="" disabled selected>Select Time Slot</option>';

                            // Capture schedule_id if available
                            if (response.schedule_id) {
                                $('#schedule_id_input').val(response.schedule_id);
                            }
                            if (response.fee) {
                                currentFee = response.fee;
                            }

                            if (response.slots && response.slots.length > 0) {
                                response.slots.forEach(slot => {
                                    if (slot.is_booked) {
                                        options += `<option value="${slot.value}" disabled class="text-secondary">${slot.display} (Booked)</option>`;
                                    } else {
                                        options += `<option value="${slot.value}">${slot.display}</option>`;
                                    }
                                });
                                $('#slot_select').html(options).prop('disabled', false);
                            } else {
                                $('#slot_select').html('<option value="" disabled>No slots available for this day</option>');
                            }
                        },
                        error: function () {
                            $('#loading').addClass('d-none');
                            $('#slot_select').html('<option>Error loading slots</option>');
                        }
                    });
                }

                // Form Submission Interception
                $('#bookingForm').submit(function (e) {
                    e.preventDefault();
                    let form = this;

                    Swal.fire({
                        title: 'Booking Confirmation',
                        text: "Do you want to generate a receipt for this appointment?",
                        icon: 'question',
                        showDenyButton: true,
                        showCancelButton: true,
                        confirmButtonText: '<i class="bi bi-printer"></i> Print Receipt',
                        denyButtonText: '<i class="bi bi-check-circle"></i> Complete Booking',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // User wants receipt - ask for fee
                            Swal.fire({
                                title: 'Enter Consultation Fee',
                                input: 'number',
                                inputLabel: 'Fee Amount (PKR)',
                                inputValue: currentFee,
                                showCancelButton: true,
                                inputValidator: (value) => {
                                    if (!value) {
                                        return 'You need to write an amount!'
                                    }
                                }
                            }).then((feeResult) => {
                                if (feeResult.isConfirmed) {
                                    $('#receipt_requested').val('1');
                                    $('#appointment_fee').val(feeResult.value);
                                    form.submit();
                                }
                            });
                        } else if (result.isDenied) {
                            // Simple completion
                            $('#receipt_requested').val('0');
                            form.submit();
                        }
                    });
                });
            });
        </script>
    @endpush
@endsection