@extends('layouts.app')

@section('title', 'Doctor Details')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="card p-4">
                <h4>Dr. {{ $doctor->user->name }}</h4>
                <p class="badge bg-info">{{ $doctor->category->name }}</p>
                <hr>
                <h6>About</h6>
                <p>{{ $doctor->bio ?? 'No bio available.' }}</p>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card p-4">
                <h4>Select Date & Time</h4>
                <hr>

                <!-- Date Selection Form -->
                <form action="{{ route('patient.doctor.details', $doctor->id) }}" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-8">
                            <label class="form-label">Choose Date</label>
                            <input type="date" name="date" class="form-control" value="{{ $date }}"
                                min="{{ date('Y-m-d') }}" onchange="this.form.submit()">
                        </div>
                    </div>
                </form>

                @if (!$schedule)
                    <div class="alert alert-warning">
                        Dr. {{ $doctor->user->name }} is <strong>on leave</strong> for the selected day
                        ({{ date('l', strtotime($date)) }}).
                    </div>
                @else
                    <div class="mb-3">
                        <h5>Available Slots for {{ date('M d, Y', strtotime($date)) }} ({{ $schedule->day }})</h5>
                        <p class="text-muted small">Slots: {{ date('h:i A', strtotime($schedule->start_time)) }} -
                            {{ date('h:i A', strtotime($schedule->end_time)) }} ({{ $schedule->duration }} mins each)
                        </p>
                    </div>

                    <div class="row">
                        @forelse($slots as $slot)
                            <div class="col-md-3 mb-3">
                                @if ($slot['is_booked'])
                                    <button class="btn btn-secondary w-100 disabled" title="Already Booked">{{ $slot['time'] }}
                                        (Booked)</button>
                                @else
                                    <form id="book-form-{{ $loop->index }}" action="{{ route('patient.book') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="doctor_id" value="{{ $doctor->id }}">
                                        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                        <input type="hidden" name="appointment_date" value="{{ $date }}">
                                        <input type="hidden" name="time_slot" value="{{ $slot['value'] }}">
                                        <button type="button" class="btn btn-outline-primary w-100"
                                            onclick="confirmBooking({{ $loop->index }}, '{{ $slot['time'] }}')">
                                            {{ $slot['time'] }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">No slots generated for this schedule.</div>
                            </div>
                        @endforelse
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function confirmBooking(index, time) {
            Swal.fire({
                title: 'Confirm Booking?',
                text: `Do you want to book an appointment at ${time}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d9488', // Medical Teal
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Yes, Book it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('book-form-' + index).submit();
                }
            })
        }
    </script>
@endpush