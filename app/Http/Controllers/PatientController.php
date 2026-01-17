<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Category;
use App\Models\Appointment;
use App\Models\Schedule;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    public function dashboard()
    {
        // Only show doctors who have at least one schedule
        $categories = Category::with([
            'doctors' => function ($q) {
                $q->has('schedules')->with('user');
            }
        ])->get();

        $myAppointments = Appointment::where('patient_id', Auth::id())
            ->with('doctor.user', 'schedule', 'prescription')
            ->orderBy('id', 'desc')
            ->get();

        return view('patient.dashboard', compact('categories', 'myAppointments'));
    }

    public function doctorDetails(Request $request, $id)
    {
        $doctor = Doctor::with('user', 'category', 'schedules')->findOrFail($id);
        $date = $request->get('date', date('Y-m-d'));
        $dayOfWeek = date('l', strtotime($date));

        // Get available days for this doctor (for disabling unavailable dates)
        $availableDays = $doctor->schedules->pluck('day')->toArray();

        $schedule = $doctor->schedules()->where('day', $dayOfWeek)->first();
        $isBlocked = $doctor->blockedDates()->where('date', $date)->exists();

        $slots = [];
        if ($schedule && !$isBlocked) {
            $start = strtotime($schedule->start_time);
            $end = strtotime($schedule->end_time);
            $duration = $schedule->duration * 60; // to seconds

            $bookedSlots = Appointment::where('doctor_id', $id)
                ->where('appointment_date', $date)
                ->pluck('time_slot')
                ->map(fn($t) => date('H:i:s', strtotime($t)))
                ->toArray();

            $currentTime = time();
            $isToday = date('Y-m-d') === $date;

            for ($i = $start; $i < $end; $i += $duration) {
                $slotTime = date('H:i:s', $i);

                // Do not show past slots if date is today
                if ($isToday && $i <= $currentTime) {
                    continue;
                }

                $slots[] = [
                    'time' => date('h:i A', $i),
                    'value' => $slotTime,
                    'is_booked' => in_array($slotTime, $bookedSlots)
                ];
            }
        }

        return view('patient.doctor-details', compact('doctor', 'slots', 'date', 'schedule', 'availableDays', 'isBlocked'));
    }

    public function bookAppointment(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'schedule_id' => 'required|exists:schedules,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);
        if ($doctor->blockedDates()->where('date', $request->appointment_date)->exists()) {
            return back()->with('error', 'The doctor is unavailable on this date.')->withInput();
        }

        // Validate that the slot is not in the past
        $slotDateTime = strtotime($request->appointment_date . ' ' . $request->time_slot);
        if ($slotDateTime <= time()) {
            return back()->with('error', 'Cannot book appointments for past time slots.')->withInput();
        }

        $isBooked = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $request->appointment_date)
            ->where('time_slot', $request->time_slot)
            ->exists();

        if ($isBooked) {
            return back()->with('error', 'This slot is already booked.');
        }

        $appointment = Appointment::create([
            'patient_id' => Auth::id(),
            'doctor_id' => $request->doctor_id,
            'schedule_id' => $request->schedule_id,
            'appointment_date' => $request->appointment_date,
            'time_slot' => $request->time_slot,
            'status' => 'Pending',
            'problem' => 'Patient Self Booking',
        ]);

        // Notify Doctor
        $appointment->doctor->user->notify(new \App\Notifications\AppointmentNotification([
            'message' => 'New appointment booked by ' . Auth::user()->name . ' for ' . $request->appointment_date,
            'url' => route('doctor.appointments', ['status' => 'Pending']),
            'icon' => 'bi-calendar-plus',
            'color' => 'text-primary',
            'type' => 'new_booking'
        ]));

        // Notify Patient (Confirmation)
        Auth::user()->notify(new \App\Notifications\AppointmentNotification([
            'message' => 'Appointment request sent to Dr. ' . $appointment->doctor->user->name,
            'url' => route('patient.history'),
            'icon' => 'bi-check-circle',
            'color' => 'text-success',
            'type' => 'booking_confirmation'
        ]));

        return redirect()->route('patient.dashboard')->with('success', 'Appointment booked successfully! Status: Pending');
    }

    // Delete appointment (ONLY if not Checked)
    public function deleteAppointment($id)
    {
        $app = Appointment::where('patient_id', Auth::id())->findOrFail($id);

        if ($app->status === 'Checked') {
            return back()->with('error', 'Cannot cancel a Checked appointment.');
        }

        // Notify Doctor about cancellation
        $app->doctor->user->notify(new \App\Notifications\AppointmentNotification([
            'message' => 'Appointment cancelled by patient ' . Auth::user()->name,
            'url' => route('doctor.appointments'),
            'icon' => 'bi-x-circle',
            'color' => 'text-danger',
            'type' => 'cancellation'
        ]));

        $app->delete();

        // Notify Patient (Visual confirmation via toast is already there, but add persistence if needed)
        // Usually, manual action doesn't need self-notification, but per requirements:
        Auth::user()->notify(new \App\Notifications\AppointmentNotification([
            'message' => 'You cancelled appointment with Dr. ' . $app->doctor->user->name,
            'url' => route('patient.history'),
            'icon' => 'bi-trash',
            'color' => 'text-muted',
            'type' => 'cancellation'
        ]));

        return back()->with('success', 'Appointment cancelled.');
    }

    // View prescription for an appointment
    public function viewPrescription($appointmentId)
    {
        $appointment = Appointment::where('patient_id', Auth::id())
            ->with('prescription', 'doctor.user')
            ->findOrFail($appointmentId);

        return view('patient.prescription', compact('appointment'));
    }

    // View my medical history
    public function myHistory()
    {
        $appointments = Appointment::where('patient_id', Auth::id())
            ->with(['doctor.user', 'schedule', 'prescription'])
            ->orderBy('id', 'desc')
            ->get();

        $prescriptions = Prescription::where('patient_id', Auth::id())
            ->with(['doctor.user', 'appointment'])
            ->orderBy('id', 'desc')
            ->get();

        return view('patient.history', compact('appointments', 'prescriptions'));
    }

    // Profile
    public function showProfile()
    {
        $user = Auth::user();
        return view('patient.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'mobile_number' => 'required|string|max:20',
            'password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $data['profile_photo'] = $path;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully.');
    }
}
