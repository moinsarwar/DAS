@extends('layouts.app')

@section('title', 'Schedules')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-heading mb-1">Manage Schedule</h2>
            <p class="text-muted">Set your availability for patients to book appointments.</p>
        </div>
    </div>

    <div class="row g-4">
        <!-- Add Schedule Panel -->
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold text-heading"><i class="bi bi-plus-circle text-primary me-2"></i>Add Schedule
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('doctor.schedules.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Day of Week</label>
                            <select name="day" class="form-select" required>
                                <option value="" disabled selected>Select Day</option>
                                <option>Monday</option>
                                <option>Tuesday</option>
                                <option>Wednesday</option>
                                <option>Thursday</option>
                                <option>Friday</option>
                                <option>Saturday</option>
                                <option>Sunday</option>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Start Time</label>
                                <input type="time" name="start_time" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">End Time</label>
                                <input type="time" name="end_time" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Slot Duration (mins)</label>
                            <div class="input-group">
                                <input type="number" name="duration" class="form-control" required value="15" min="5">
                                <span class="input-group-text bg-light text-muted">min</span>
                            </div>
                            <small class="text-muted">Time allocated for each patient.</small>
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> Save Schedule
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- List Schedules Panel -->
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold text-heading"><i class="bi bi-calendar-week text-info me-2"></i>My Availability
                    </h5>
                    <span class="badge bg-primary rounded-pill">{{ $schedules->count() }} Active Slots</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Day</th>
                                    <th>Time Range</th>
                                    <th>Slot Duration</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($schedules as $s)
                                    <tr>
                                        <td>
                                            <span class="fw-bold text-heading">{{ $s->day }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clock text-muted me-2"></i>
                                                {{ date('h:i A', strtotime($s->start_time)) }} -
                                                {{ date('h:i A', strtotime($s->end_time)) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border">{{ $s->duration }} mins</span>
                                        </td>
                                        <td class="text-center">
                                            <button class="btn btn-sm btn-outline-primary me-1 edit-schedule-btn"
                                                data-id="{{ $s->id }}" data-day="{{ $s->day }}"
                                                data-start="{{ $s->start_time }}" data-end="{{ $s->end_time }}"
                                                data-duration="{{ $s->duration }}">
                                                <i class="bi bi-pencil"></i>
                                            </button>
                                            <form id="delete-schedule-{{ $s->id }}"
                                                action="{{ route('doctor.schedules.delete', $s->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmAction(event, 'delete-schedule-{{ $s->id }}', 'Remove Schedule?', 'Delete this schedule slot?', 'warning')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5">
                                            <div class="text-muted">
                                                <i class="bi bi-calendar-x fs-1"></i>
                                                <p class="mt-2">No schedules found. Add your availability to accept
                                                    appointments.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Blocked Dates Section -->
    <div class="row g-4 mt-2">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-calendar-x me-2"></i>Block Date</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('doctor.blocked_dates.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Select Date</label>
                            <input type="date" name="date" class="form-control" required
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reason (Optional)</label>
                            <input type="text" name="reason" class="form-control" placeholder="e.g. Vacation, Seminar">
                        </div>
                        <div class="d-grid">
                            <button class="btn btn-danger">
                                <i class="bi bi-slash-circle me-1"></i> Block Date
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0 fw-bold">Blocked Dates History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Date</th>
                                    <th>Reason</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($blockedDates as $block)
                                    <tr>
                                        <td class="fw-bold">{{ date('M d, Y', strtotime($block->date)) }}</td>
                                        <td>{{ $block->reason ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('doctor.blocked_dates.delete', $block->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-sm btn-outline-danger"
                                                    onclick="return confirm('Unblock this date?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">No dates blocked.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Schedule Modal -->
    <div class="modal fade" id="editScheduleModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Edit Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editScheduleForm" method="POST">
                    @csrf @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Day of Week</label>
                            <select name="day" id="edit_day" class="form-select" required>
                                <option>Monday</option>
                                <option>Tuesday</option>
                                <option>Wednesday</option>
                                <option>Thursday</option>
                                <option>Friday</option>
                                <option>Saturday</option>
                                <option>Sunday</option>
                            </select>
                        </div>
                        <div class="row mb-3">
                            <div class="col-6">
                                <label class="form-label">Start Time</label>
                                <input type="time" name="start_time" id="edit_start_time" class="form-control" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label">End Time</label>
                                <input type="time" name="end_time" id="edit_end_time" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Slot Duration (mins)</label>
                            <input type="number" name="duration" id="edit_duration" class="form-control" required min="5">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('.edit-schedule-btn').click(function () {
                    let id = $(this).data('id');
                    let day = $(this).data('day');
                    let start = $(this).data('start');
                    let end = $(this).data('end');
                    let duration = $(this).data('duration');

                    $('#edit_day').val(day);
                    $('#edit_start_time').val(start);
                    $('#edit_end_time').val(end);
                    $('#edit_duration').val(duration);

                    let updateUrl = "{{ route('doctor.schedules.update', ':id') }}";
                    updateUrl = updateUrl.replace(':id', id);
                    $('#editScheduleForm').attr('action', updateUrl);

                    $('#editScheduleModal').modal('show');
                });
            });
        </script>
    @endpush
@endsection