@extends('layouts.app')

@section('title', 'Edit Appointment')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-heading">Edit Appointment</h5>
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

                    <form action="{{ route('receptionist.appointments.update', $appointment->id) }}" method="POST">
                        @csrf
                        @method('POST') {{-- Using POST here but route might assume PUT if defined as such, actually
                        standard update is PUT/PATCH but simple POST is often fine if route matches. Wait, I will use PUT in
                        route definition and @method('PUT') here. --}}
                        <input type="hidden" name="_method" value="PUT">
                        <input type="hidden" name="schedule_id" id="schedule_id_input"
                            value="{{ $appointment->schedule_id }}">

                        <div class="mb-3">
                            <label class="form-label">Select Doctor</label>
                            <select name="doctor_id" id="doctor_select" class="form-select" required>
                                <option value="" disabled>Choose a Doctor</option>
                                @foreach($doctors as $doc)
                                    <option value="{{ $doc->id }}" {{ $doc->id == $appointment->doctor_id ? 'selected' : '' }}>
                                        {{ $doc->user->name }} ({{ $doc->category->name ?? 'General' }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" name="appointment_date" id="date_select" class="form-control"
                                min="{{ date('Y-m-d') }}" value="{{ $appointment->appointment_date }}" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Available Slots</label>
                            <select name="time_slot" id="slot_select" class="form-select" required>
                                {{-- Options populated by JS --}}
                            </select>
                            <div id="loading" class="text-primary small mt-2 d-none">
                                <span class="spinner-border spinner-border-sm me-1"></span> Loading available slots...
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Problem / Notes (Optional)</label>
                            <textarea name="problem" class="form-control" rows="2">{{ $appointment->problem }}</textarea>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('receptionist.appointments') }}" class="btn btn-secondary me-md-2">Cancel</a>
                            <button type="submit" class="btn btn-primary">Update Appointment</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                let doctorId = $('#doctor_select').val();
                let date = $('#date_select').val();
                let currentSlot = "{{ date('H:i:s', strtotime($appointment->time_slot)) }}";
                let appointmentId = "{{ $appointment->id }}";

                // Initial fetch
                if (doctorId && date) {
                    fetchSlots(currentSlot);
                }

                $('#doctor_select').change(function () {
                    doctorId = $(this).val();
                    fetchSlots();
                });

                $('#date_select').change(function () {
                    date = $(this).val();
                    fetchSlots();
                });

                function fetchSlots(preselected = null) {
                    if (!doctorId || !date) return;

                    $('#loading').removeClass('d-none');
                    $('#slot_select').prop('disabled', true).html('<option>Loading...</option>');
                    $('#schedule_id_input').val('');

                    $.ajax({
                        url: `/receptionist/doctor/${doctorId}/slots`,
                        method: 'GET',
                        data: {
                            date: date,
                            exclude_appointment_id: appointmentId
                        },
                        success: function (response) {
                            $('#loading').addClass('d-none');
                            let options = '<option value="" disabled selected>Select Time Slot</option>';

                            if (response.schedule_id) {
                                $('#schedule_id_input').val(response.schedule_id);
                                $('#slot_select').prop('disabled', false);

                                if (response.slots.length === 0) {
                                    options = '<option disabled>No slots available</option>';
                                } else {
                                    response.slots.forEach(slot => {
                                        let disabled = slot.is_booked ? 'disabled' : '';
                                        let text = slot.display + (slot.is_booked ? ' (Booked)' : '');
                                        // If preselected matches, select it
                                        let selected = (preselected && slot.value === preselected) ? 'selected' : '';

                                        options += `<option value="${slot.value}" ${disabled} ${selected}>${text}</option>`;
                                    });
                                }
                            } else {
                                options = '<option disabled>Doctor not available on this day</option>';
                            }

                            $('#slot_select').html(options);
                            // Initial re-init for Select2 if used? 
                            // Select2 is globally init on 'select' tags. 
                            // If options change, Select2 should pick it up if we trigger change, but sometimes need re-init or .trigger('change.select2')??
                            // The global script applies select2 on document.ready. 
                            // If we change DOM, we might need to refresh?
                            // Actually pure select2 on hidden select element listens to changes?
                            // No, if we replace options, we might need to trigger change.
                            // But let's assume standard behavior. 
                            // Actually, standard Select2 needs `$('#slot_select').select2(...)` again or specific event?
                            // No, usually just modifying innerHTML requires trigger 'change.select2' if value changed, but here we rebuild options.
                            // It's safer to just let it be. But if Select2 is enabled, we might not see changes unless...
                            // Actually, Select2 hides the original select. If we change original select options, Select2 doesn't auto-update.
                            // We MUST trigger update.
                            // However, the global script `$('select').select2()` only runs once.
                            // We should probably rely on manual trigger if possible.
                            // Or destroy and recreate?

                            // Let's try triggering change to notify Select2? No, Select2 needs `trigger('change')` might not suffice for new options.
                            // We might need to handle this.
                            $('#slot_select').select2({
                                theme: 'bootstrap-5',
                                width: '100%',
                                placeholder: 'Select Time Slot'
                            });
                        },
                        error: function () {
                            $('#loading').addClass('d-none');
                            $('#slot_select').prop('disabled', true).html('<option>Error loading slots</option>');
                        }
                    });
                }
            });
        </script>
    @endpush
@endsection