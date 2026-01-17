@extends('layouts.app')

@section('title', 'Appointments')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">All Appointments</h5>
                    <a href="{{ route('receptionist.dashboard') }}" class="btn btn-primary" title="Book Appointment">
                        <i class="bi bi-calendar-plus me-1"></i> Book New Appointment
                    </a>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <form method="GET" action="{{ route('admin.appointments') }}" class="mb-4">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select name="doctor_id" class="form-select">
                                    <option value="">Filter by Doctor</option>
                                    @foreach($doctors as $doc)
                                        <option value="{{ $doc->id }}" {{ request('doctor_id') == $doc->id ? 'selected' : '' }}>
                                            Dr. {{ $doc->user->name }} ({{ $doc->category->name ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                            </div>
                            @if(request('doctor_id') || request('date'))
                                <div class="col-md-2">
                                    <a href="{{ route('admin.appointments') }}" class="btn btn-link text-danger">Clear</a>
                                </div>
                            @endif
                        </div>
                    </form>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Patient Details</th>
                                <th>Doctor</th>
                                <th>Schedule</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($appointments as $app)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $app->patient->name }}</div>
                                        <div class="small text-muted">{{ $app->patient->email }}</div>
                                        <div class="small text-primary fw-bold">{{ $app->patient->mobile_number }}</div>
                                    </td>
                                    <td>{{ $app->doctor->user->name }}</td>
                                    <td>{{ $app->schedule->day }}</td>
                                    <td>
                                        {{ $app->appointment_date }}<br>
                                        <span
                                            class="badge bg-light text-dark border">{{ date('h:i A', strtotime($app->time_slot)) }}</span>
                                    </td>
                                    <td>

                                        @php
                                            $badgeClass = match ($app->status) {
                                                'Pending' => 'badge-pending',
                                                'Approved' => 'badge-approved',
                                                'Checked' => 'badge-checked',
                                                'Denied' => 'badge-denied',
                                                'Refunded' => 'badge-danger',
                                                'Partially Refunded' => 'badge-warning',
                                                default => 'bg-secondary text-white'
                                            };
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $app->status }}</span>
                                        @if($app->fee > 0)
                                            <div class="small mt-1 text-muted">Paid: {{ $app->fee }}</div>
                                        @endif
                                        @if($app->refunded_amount > 0)
                                            <div class="small text-danger">Refunded: {{ $app->refunded_amount }}</div>
                                        @endif
                                        @if($app->fee > 0 && ($app->fee - $app->refunded_amount) > 0)
                                            <div class="small text-success fw-bold">Bal: {{ $app->fee - $app->refunded_amount }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            @if($app->status == 'Pending')
                                                <button type="button" class="btn btn-sm btn-success"
                                                    onclick="collectFee('{{ $app->id }}', '{{ $app->doctor->fees }}')">
                                                    <i class="bi bi-cash-coin"></i> Collect & Approve
                                                </button>
                                                <form action="{{ route('admin.appointments.deny', $app->id) }}" method="POST">
                                                    @csrf<button class="btn btn-sm btn-outline-danger" title="Deny">Deny</button>
                                                </form>
                                            @endif
                                            @if(in_array($app->status, ['Approved', 'Checked', 'Partially Refunded']))
                                                <a href="{{ route('receptionist.receipt', $app->id) }}" target="_blank"
                                                    class="btn btn-sm btn-outline-dark" title="Print Receipt">
                                                    <i class="bi bi-printer"></i>
                                                </a>
                                                @if(($app->fee - $app->refunded_amount) > 0)
                                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Refund Fee"
                                                        onclick="refundFee('{{ $app->id }}', '{{ $app->fee }}', '{{ $app->refunded_amount }}')">
                                                        <i class="bi bi-arrow-counterclockwise"></i> Refund
                                                    </button>
                                                @endif
                                            @endif
                                            @if($app->status == 'Approved')
                                                <form action="{{ route('admin.appointments.check', $app->id) }}" method="POST">
                                                    @csrf<button class="btn btn-sm btn-success" title="Checked">Mark
                                                        Checked</button></form>
                                            @endif

                                            <button type="button" class="btn btn-sm btn-info text-white"
                                                onclick="openVitalsModal('{{ $app->id }}', '{{ $app->vital->bp ?? '' }}', '{{ $app->vital->pulse ?? '' }}', '{{ $app->vital->temperature ?? '' }}', '{{ $app->vital->weight ?? '' }}', '{{ $app->vital->height ?? '' }}', `{{ $app->vital->notes ?? '' }}`)">
                                                <i class="bi bi-heart-pulse"></i>
                                            </button>

                                            <form id="delete-app-{{ $app->id }}"
                                                action="{{ route('admin.appointments.delete', $app->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-link text-danger p-0 ms-2"
                                                    title="Delete"
                                                    onclick="confirmAction(event, 'delete-app-{{ $app->id }}', 'Delete Appointment?', 'Are you sure?', 'error')">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Vitals Modal -->
    <div class="modal fade" id="vitalsModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="vitalsForm" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Patient Vitals</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-6">
                                <label class="form-label">Blood Pressure (BP)</label>
                                <input type="text" name="bp" id="vital_bp" class="form-control" placeholder="e.g. 120/80">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Pulse (bpm)</label>
                                <input type="text" name="pulse" id="vital_pulse" class="form-control" placeholder="e.g. 72">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Temperature (°F)</label>
                                <input type="text" name="temperature" id="vital_temp" class="form-control"
                                    placeholder="e.g. 98.6">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Weight (kg)</label>
                                <input type="text" name="weight" id="vital_weight" class="form-control"
                                    placeholder="e.g. 70">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Height</label>
                                <input type="text" name="height" id="vital_height" class="form-control"
                                    placeholder="e.g. 5'10">
                            </div>
                            <div class="col-12">
                                <label class="form-label">Notes</label>
                                <textarea name="notes" id="vital_notes" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save Vitals</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function openVitalsModal(id, bp, pulse, temp, weight, height, notes) {
                let form = document.getElementById('vitalsForm');
                form.action = `/admin/appointments/${id}/vitals`;

                document.getElementById('vital_bp').value = bp;
                document.getElementById('vital_pulse').value = pulse;
                document.getElementById('vital_temp').value = temp;
                document.getElementById('vital_weight').value = weight;
                document.getElementById('vital_height').value = height;
                document.getElementById('vital_notes').value = notes;

                new bootstrap.Modal(document.getElementById('vitalsModal')).show();
            }

            function collectFee(id, fee) {
                Swal.fire({
                    title: 'Collect Consultation Fee',
                    input: 'number',
                    inputLabel: 'Fee Amount (PKR)',
                    inputValue: fee,
                    showCancelButton: true,
                    confirmButtonText: 'Collect & Approve',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'You need to write an amount!'
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/appointments/${id}/collect`;
                        form.innerHTML = `
                                                                    @csrf
                                                                    <input type="hidden" name="fee" value="${result.value}">
                                                                `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
            function refundFee(id, paid, refunded) {
                let maxRefund = paid - refunded;
                Swal.fire({
                    title: 'Refund Fee',
                    text: `Max Refundable: ${maxRefund}`,
                    input: 'number',
                    inputLabel: 'Refund Amount (PKR)',
                    inputValue: maxRefund,
                    inputAttributes: {
                        min: 0.01,
                        max: maxRefund,
                        step: 0.01
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Process Refund',
                    confirmButtonColor: '#d33',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'You need to write an amount!'
                        }
                        if (value > maxRefund) {
                            return `Cannot refund more than ${maxRefund}`;
                        }
                        if (value <= 0) {
                            return 'Amount must be greater than 0';
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        let form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/appointments/${id}/refund`;
                        form.innerHTML = `
                                            @csrf
                                            <input type="hidden" name="refund_amount" value="${result.value}">
                                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        </script>
    @endpush
@endsection