<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ReceptionistController extends Controller
{
    public function dashboard()
    {
        return view('receptionist.dashboard');
    }

    // Search Patient by CNIC or Mobile
    public function checkPatient(Request $request)
    {
        $request->validate([
            'query' => 'required|string'
        ]);

        $query = $request->input('query');

        $patients = User::where('role', 'patient')
            ->where(function ($q) use ($query) {
                $q->where('cnic', $query)
                    ->orWhere('mobile_number', $query);
            })->orderBy('id', 'desc')->get(); // Return list instead of first()

        if ($patients->isNotEmpty()) {
            return response()->json([
                'status' => 'found',
                'patients' => $patients // Return array of matching patients
            ]);
        }

        return response()->json(['status' => 'not_found']);
    }

    // Store New Patient
    public function storePatient(Request $request)
    {
        // Allowed multiple patients with same CNIC now.
        // But MR Number separates them.

        $request->validate([
            'name' => 'required|string',
            'mobile_number' => 'required|string',
            // Removed unique:users,cnic rule
            'cnic' => 'required|numeric|digits:13',
        ]);

        // Auto-generate MR Number
        $mrNumber = User::generateMrNumber();

        $patient = User::create([
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,
            'cnic' => $request->cnic,
            'mr_number' => $mrNumber,
            'role' => 'patient',
        ]);

        return response()->json([
            'status' => 'success',
            'patient' => $patient,
            'redirect' => route('receptionist.book.appointment', $patient->id)
        ]);
    }

    // Show Booking Page for Patient
    public function bookAppointment($patientId)
    {
        $patient = User::findOrFail($patientId);
        $doctors = Doctor::with('user', 'category')->orderBy('id', 'desc')->get();
        return view('receptionist.book_appointment', compact('patient', 'doctors'));
    }

    // Get Doctor Slots for a specific date
    public function getDoctorSlots(Request $request, $doctorId)
    {
        $request->validate(['date' => 'required|date']);
        $date = $request->date;
        $dayOfWeek = date('l', strtotime($date));

        $doctor = Doctor::with('schedules')->findOrFail($doctorId);

        if ($doctor->blockedDates()->where('date', $date)->exists()) {
            return response()->json(['slots' => [], 'schedule_id' => null, 'error' => 'Doctor has blocked this date.']);
        }

        $schedule = $doctor->schedules()->where('day', $dayOfWeek)->first();

        if (!$schedule) {
            return response()->json(['slots' => [], 'schedule_id' => null]);
        }

        $start = strtotime($schedule->start_time);
        $end = strtotime($schedule->end_time);
        $duration = $schedule->duration * 60; // to seconds

        $query = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', $date);

        if ($request->has('exclude_appointment_id')) {
            $query->where('id', '!=', $request->exclude_appointment_id);
        }

        $bookedSlots = $query->pluck('time_slot')
            ->map(fn($t) => date('H:i:s', strtotime($t)))
            ->toArray();

        $slots = [];
        $currentTime = time();
        $isToday = date('Y-m-d') === $date;

        for ($i = $start; $i < $end; $i += $duration) {
            $slotTime = date('H:i:s', $i);

            // Filter past time slots if the selected date is today
            // Note: $i is a timestamp for Today if generated via strtotime("H:i:s") with no date,
            // but we should adhere to strict timestamp comparison relative to now.
            // strtotime($schedule->start_time) creates a timestamp for TODAY + TIME.
            // If the user selected a FUTURE date, this logic needs adjustment if we rely on $i for strictly time blocks.
            // However, typical usage: $i represents the time block.
            // If date is today, compare specific time block to current time.

            if ($isToday && $i <= $currentTime) {
                continue;
            }

            $slots[] = [
                'display' => date('h:i A', $i),
                'value' => $slotTime,
                'is_booked' => in_array($slotTime, $bookedSlots)
            ];
        }

        return response()->json([
            'slots' => $slots,
            'schedule_id' => $schedule->id,
            'fee' => $doctor->fees ?? 0
        ]);
    }

    // Store Appointment (Auto Approved)
    public function storeAppointment(Request $request, $patientId)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'required|exists:schedules,id',
            'appointment_date' => 'required|date',
            'time_slot' => 'required',
        ]);

        // Validate that the slot is not in the past
        $slotDateTime = strtotime($request->appointment_date . ' ' . $request->time_slot);
        if ($slotDateTime <= time()) {
            return back()->with('error', 'Cannot book appointments for past time slots.')->withInput();
        }

        if (Doctor::find($request->doctor_id)->blockedDates()->where('date', $request->appointment_date)->exists()) {
            return back()->with('error', 'Doctor is not available on this date.')->withInput();
        }

        // Check if slot is already booked (double check)
        $isBooked = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('time_slot', $request->time_slot)
            ->exists();

        if ($isBooked) {
            return back()->with('error', 'This slot is already booked. Please select another.');
        }

        $fee = null;
        $status = 'Pending';

        if ($request->receipt_requested == '1') {
            $fee = $request->appointment_fee;
            $status = 'Approved';
        }

        $appointment = Appointment::create([
            'patient_id' => $patientId,
            'doctor_id' => $request->doctor_id,
            'schedule_id' => $request->schedule_id,
            'appointment_date' => $request->appointment_date,
            'time_slot' => $request->time_slot,
            'status' => $status,
            'problem' => 'Pending Diagnosis', // Fixed default
            'fee' => $fee
        ]);

        if ($request->receipt_requested == '1') {
            return redirect()->route('receptionist.receipt', $appointment->id); // Will create this route
        }

        return redirect()->route('receptionist.dashboard')->with('success', 'Appointment booked successfully with status Approved.');
    }
    // List All Appointments
    public function appointments(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor.user', 'schedule'])
            ->orderBy('id', 'desc');

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('date')) {
            $query->where('appointment_date', $request->date);
        }

        $appointments = $query->get();
        $doctors = Doctor::with('user')->get(); // For filter dropdown

        return view('receptionist.appointments', compact('appointments', 'doctors'));
    }

    public function refundFee(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        if (!in_array($appointment->status, ['Approved', 'Checked', 'Refunded', 'Partially Refunded'])) {
            return back()->with('error', 'Refund can only be processed for Approved, Checked, or Refunded appointments.');
        }

        $request->validate(['refund_amount' => 'required|numeric|min:0.01']);

        $paid = $appointment->fee ?? 0;
        $alreadyRefunded = $appointment->refunded_amount ?? 0;
        $maxRefundable = $paid - $alreadyRefunded;
        $requestedRefund = $request->refund_amount;

        if ($requestedRefund > $maxRefundable) {
            return back()->with('error', "Cannot refund $requestedRefund. Maximum refundable limit is $maxRefundable.");
        }

        $newRefundTotal = $alreadyRefunded + $requestedRefund;

        $status = $appointment->status;
        if ($newRefundTotal >= $paid) {
            $status = 'Refunded';
        } elseif ($newRefundTotal > 0) {
            $status = 'Partially Refunded';
        }

        $appointment->update([
            'refunded_amount' => $newRefundTotal,
            'status' => $status
        ]);

        return back()->with('success', "Refund of $requestedRefund processed successfully. Total Refunded: $newRefundTotal. Status: $status");
    }

    // Cancel Appointment
    public function cancelAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);

        if ($appointment->status === 'Checked') {
            return back()->with('error', 'Cannot cancel a Checked appointment.');
        }

        $appointment->update(['status' => 'Cancelled']);

        // Notify Patient
        $appointment->patient->notify(new \App\Notifications\AppointmentNotification([
            'message' => 'Your appointment with Dr. ' . $appointment->doctor->user->name . ' has been Cancelled by Reception.',
            'url' => route('patient.history'),
            'icon' => 'bi-x-circle',
            'color' => 'text-danger',
            'type' => 'status_update'
        ]));

        // Notify Doctor
        $appointment->doctor->user->notify(new \App\Notifications\AppointmentNotification([
            'message' => 'Appointment for ' . $appointment->patient->name . ' on ' . $appointment->appointment_date . ' was Cancelled.',
            'url' => route('doctor.appointments'),
            'icon' => 'bi-x-circle',
            'color' => 'text-danger',
            'type' => 'status_update'
        ]));

        return back()->with('success', 'Appointment Cancelled successfully.');
    }

    // Edit Appointment Form
    public function editAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $doctors = Doctor::with('user', 'category')->orderBy('id', 'desc')->get();
        $patient = $appointment->patient;

        return view('receptionist.edit_appointment', compact('appointment', 'doctors', 'patient'));
    }

    // Update Appointment
    public function updateAppointment(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'required|exists:schedules,id',
            'appointment_date' => 'required|date',
            'time_slot' => 'required',
        ]);

        // Validate that the slot is not in the past (unless it wasn't changed and is already past - tricky. Better to enforce future only for updates too? User "Update an appointment... respect all validation rules". )
        // Let's enforce future unless it's the SAME slot (i.e. just updating note).

        $slotDateTime = strtotime($request->appointment_date . ' ' . $request->time_slot);

        // If date/time changed, check past constraint
        if (($request->appointment_date != $appointment->appointment_date || $request->time_slot != $appointment->time_slot) && $slotDateTime <= time()) {
            return back()->with('error', 'Cannot reschedule to a past time slot.')->withInput();
        }

        if (Doctor::find($request->doctor_id)->blockedDates()->where('date', $request->appointment_date)->exists()) {
            return back()->with('error', 'Doctor is not available on this date.')->withInput();
        }

        // Check availability (exclude current appointment)
        $isBooked = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('time_slot', $request->time_slot)
            ->where('id', '!=', $id)
            ->exists();

        if ($isBooked) {
            return back()->with('error', 'This slot is already booked. Please select another.')->withInput();
        }

        $appointment->update([
            'doctor_id' => $request->doctor_id,
            'schedule_id' => $request->schedule_id,
            'appointment_date' => $request->appointment_date,
            'time_slot' => $request->time_slot,
            // 'problem' => $request->problem // Receptionist cannot edit diagnosis
        ]);

        // Notifications
        $msg = 'Your appointment has been updated to ' . $request->appointment_date . ' at ' . date('h:i A', strtotime($request->time_slot));

        $appointment->patient->notify(new \App\Notifications\AppointmentNotification([
            'message' => $msg,
            'url' => route('patient.history'),
            'icon' => 'bi-pencil-square',
            'color' => 'text-info',
            'type' => 'status_update'
        ]));

        $appointment->doctor->user->notify(new \App\Notifications\AppointmentNotification([
            'message' => 'Appointment for ' . $appointment->patient->name . ' updated.',
            'url' => route('doctor.appointments'),
            'icon' => 'bi-pencil-square',
            'color' => 'text-info',
            'type' => 'status_update'
        ]));

        return redirect()->route('receptionist.appointments')->with('success', 'Appointment updated successfully.');
    }

    // Delete Appointment
    public function deleteAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);

        // Capture details for notification before deletion
        $patient = $appointment->patient;
        $doctorUser = $appointment->doctor->user;
        $date = $appointment->appointment_date;

        // Notify Patient
        $patient->notify(new \App\Notifications\AppointmentNotification([
            'message' => 'Your appointment with Dr. ' . $doctorUser->name . ' on ' . $date . ' has been deleted from records.',
            'url' => route('patient.history'),
            'icon' => 'bi-trash',
            'color' => 'text-danger',
            'type' => 'status_update' // or deletion
        ]));

        // Notify Doctor
        $doctorUser->notify(new \App\Notifications\AppointmentNotification([
            'message' => 'Appointment for ' . $patient->name . ' on ' . $date . ' has been removed.',
            'url' => route('doctor.appointments'),
            'icon' => 'bi-trash',
            'color' => 'text-danger',
            'type' => 'status_update'
        ]));

        $appointment->delete();

        return back()->with('success', 'Appointment deleted permanently.');
    }
    // Generate Receipt
    public function receipt($id)
    {
        $appointment = Appointment::with(['patient', 'doctor.user'])->findOrFail($id);
        return view('receptionist.receipt', compact('appointment'));
    }
    // Collect Fee & Approve
    public function collectFee(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate(['fee' => 'required|numeric']);

        $appointment->update([
            'fee' => $request->fee,
            'status' => 'Approved'
        ]);

        // Notifications
        $msg = 'Fee collected. Appointment with Dr. ' . $appointment->doctor->user->name . ' is now Approved.';

        $appointment->patient->notify(new \App\Notifications\AppointmentNotification([
            'message' => $msg,
            'url' => route('patient.history'),
            'icon' => 'bi-check-circle-fill',
            'color' => 'text-success',
            'type' => 'status_update'
        ]));

        $appointment->doctor->user->notify(new \App\Notifications\AppointmentNotification([
            'message' => 'New Approved Appointment: ' . $appointment->patient->name,
            'url' => route('doctor.appointments'),
            'icon' => 'bi-check-circle-fill',
            'color' => 'text-success',
            'type' => 'status_update'
        ]));

        return back()->with('success', 'Fee collected and Appointment Approved.');
    }

    public function storeVitals(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $data = $request->validate([
            'bp' => 'nullable|string|max:20',
            'pulse' => 'nullable|string|max:20',
            'temperature' => 'nullable|string|max:20',
            'weight' => 'nullable|string|max:20',
            'height' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        if ($appointment->vital) {
            $appointment->vital->update($data);
        } else {
            $appointment->vital()->create($data);
        }

        return back()->with('success', 'Vitals updated successfully.');
    }
}
