<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function dashboard()
    {
        $doctor = Auth::user()->doctor;
        $today = date('Y-m-d');

        // Today's waiting patients (Approved status for today)
        $todaysPatients = $doctor->appointments()
            ->where('appointment_date', $today)
            ->whereIn('status', ['Pending', 'Approved'])
            ->with('patient')
            ->orderBy('time_slot')
            ->get();

        $stats = [
            'schedules' => $doctor->schedules()->count(),
            'total_appointments' => $doctor->appointments()->whereDate('appointment_date', $today)->count(),
            'todays_count' => $todaysPatients->count(),
            'pending' => $doctor->appointments()->where('status', 'Pending')->count(),
        ];

        return view('doctor.dashboard', compact('stats', 'todaysPatients'));
    }

    public function schedules()
    {
        $schedules = Auth::user()->doctor->schedules()->orderBy('id', 'desc')->get();
        $blockedDates = Auth::user()->doctor->blockedDates()->orderBy('date', 'desc')->get();
        return view('doctor.schedules', compact('schedules', 'blockedDates'));
    }

    public function storeSchedule(Request $request)
    {
        $request->validate([
            'day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'duration' => 'required|integer|min:5',
        ]);

        Auth::user()->doctor->schedules()->create($request->all());

        return back()->with('success', 'Schedule added successfully.');
    }

    // Blocked Dates Management
    public function storeBlockedDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after:today',
            'reason' => 'nullable|string|max:255',
        ]);

        $doctor = Auth::user()->doctor;

        // Check uniqueness
        if ($doctor->blockedDates()->where('date', $request->date)->exists()) {
            return back()->with('error', 'This date is already blocked.');
        }

        $doctor->blockedDates()->create($request->all());

        return back()->with('success', 'Date blocked successfully.');
    }

    public function deleteBlockedDate($id)
    {
        Auth::user()->doctor->blockedDates()->findOrFail($id)->delete();
        return back()->with('success', 'Blocked date removed.');
    }

    public function updateSchedule(Request $request, $id)
    {
        $schedule = Auth::user()->doctor->schedules()->findOrFail($id);

        $request->validate([
            'day' => 'required|string',
            'start_time' => 'required',
            'end_time' => 'required',
            'duration' => 'required|integer|min:5',
        ]);

        $schedule->update($request->all());

        return back()->with('success', 'Schedule updated successfully.');
    }

    public function deleteSchedule($id)
    {
        Auth::user()->doctor->schedules()->findOrFail($id)->delete();
        return back()->with('success', 'Schedule deleted.');
    }

    // Appointments with optional date filter
    public function appointments(Request $request)
    {
        $doctor = Auth::user()->doctor;
        $date = $request->get('date');
        $status = $request->get('status');

        $query = $doctor->appointments()->with('patient', 'schedule', 'prescription');

        if ($date) {
            $query->where('appointment_date', $date);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $appointments = $query->orderBy('id', 'desc')->get();

        return view('doctor.appointments', compact('appointments', 'date'));
    }

    // Approve appointment
    public function approveAppointment($id)
    {
        $app = Auth::user()->doctor->appointments()->findOrFail($id);
        $app->update(['status' => 'Approved']);

        $app->patient->notify(new \App\Notifications\AppointmentNotification([
            'message' => 'Your appointment with Dr. ' . Auth::user()->name . ' is Approved.',
            'url' => route('patient.history'),
            'icon' => 'bi-check-circle-fill',
            'color' => 'text-success',
            'type' => 'status_update'
        ]));

        return back()->with('success', 'Appointment Approved.');
    }

    // Deny appointment
    public function denyAppointment($id)
    {
        $app = Auth::user()->doctor->appointments()->findOrFail($id);
        $app->update(['status' => 'Denied']);

        $app->patient->notify(new \App\Notifications\AppointmentNotification([
            'message' => 'Your appointment with Dr. ' . Auth::user()->name . ' was Denied.',
            'url' => route('patient.history'),
            'icon' => 'bi-x-circle-fill',
            'color' => 'text-danger',
            'type' => 'status_update'
        ]));

        return back()->with('success', 'Appointment Denied.');
    }

    // Mark as Checked
    public function checkAppointment($id)
    {
        $app = Auth::user()->doctor->appointments()->findOrFail($id);
        $app->update(['status' => 'Checked']);
        return back()->with('success', 'Appointment marked as Checked.');
    }

    // Delete appointment (ONLY if not Checked)
    public function deleteAppointment($id)
    {
        $app = Auth::user()->doctor->appointments()->findOrFail($id);

        if ($app->status === 'Checked') {
            return back()->with('error', 'Cannot delete a Checked appointment.');
        }

        // Notify Patient about deletion
        $app->patient->notify(new \App\Notifications\AppointmentNotification([
            'message' => 'Your appointment with Dr. ' . Auth::user()->name . ' was removed.',
            'url' => route('patient.history'),
            'icon' => 'bi-calendar-x',
            'color' => 'text-danger',
            'type' => 'deletion'
        ]));

        $app->delete();

        // Notify Doctor (Confirmation)
        Auth::user()->notify(new \App\Notifications\AppointmentNotification([
            'message' => 'You removed an appointment for ' . $app->patient->name,
            'url' => route('doctor.appointments'),
            'icon' => 'bi-trash',
            'color' => 'text-muted',
            'type' => 'deletion'
        ]));

        return back()->with('success', 'Appointment deleted.');
    }

    // Add prescription to a Checked appointment
    public function showPrescriptionForm($id)
    {
        $appointment = Auth::user()->doctor->appointments()
            ->with('patient', 'prescription')
            ->findOrFail($id);

        return view('doctor.prescription', compact('appointment'));
    }

    public function storePrescription(Request $request, $id)
    {
        $appointment = Auth::user()->doctor->appointments()->findOrFail($id);

        if ($appointment->status !== 'Checked') {
            return back()->with('error', 'Prescription can only be added to Checked appointments.');
        }

        $request->validate([
            'notes' => 'nullable|string', // Advice
            'medicines' => 'nullable|string',
            'diagnosis' => 'nullable|string', // Diagnosis (problem)
        ]);

        // Update Appointment Diagnosis
        $appointment->update([
            'problem' => $request->diagnosis
        ]);

        Prescription::updateOrCreate(
            ['appointment_id' => $appointment->id],
            [
                'patient_id' => $appointment->patient_id,
                'doctor_id' => $appointment->doctor_id,
                'notes' => $request->notes, // Medical Advice
                'medicines' => $request->medicines,
            ]
        );

        return redirect()->route('doctor.appointments')->with('success', 'Prescription and Diagnosis saved successfully.');
    }

    public function printPrescription($id)
    {
        $appointment = Auth::user()->doctor->appointments()
            ->with('patient', 'prescription')
            ->findOrFail($id);

        return view('doctor.print_prescription', compact('appointment'));
    }

    // View patient history
    public function patientHistory($patientId)
    {
        $doctor = Auth::user()->doctor;
        $patient = User::where('role', 'patient')->findOrFail($patientId);

        // Get all appointments of this patient with this doctor
        $appointments = Appointment::where('patient_id', $patientId)
            ->where('doctor_id', $doctor->id)
            ->with('prescription', 'schedule')
            ->orderBy('id', 'desc')
            ->get();

        return view('doctor.patient-history', compact('patient', 'appointments'));
    }

    // Profile update
    public function showProfile()
    {
        $user = Auth::user();
        $doctor = $user->doctor;
        $categories = \App\Models\Category::all();
        return view('doctor.profile', compact('user', 'doctor', 'categories'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'mobile_number' => 'required|string|max:20',
            'category_id' => 'required|exists:doctor_categories,id',
            'bio' => 'nullable|string',
            'qualification' => 'nullable|string',
            'experience_years' => 'nullable|integer',
            'fees' => 'nullable|numeric|min:0',
            'password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $userData['profile_photo'] = $path;
        }

        $user->update($userData);

        if ($user->doctor) {
            $user->doctor->update([
                'category_id' => $request->category_id,
                'bio' => $request->bio,
                'qualification' => $request->qualification,
                'experience_years' => $request->experience_years,
                'fees' => $request->fees,
            ]);
        }

        return back()->with('success', 'Profile updated successfully.');
    }

    public function checkNewAppointments(Request $request)
    {
        $doctor = Auth::user()->doctor;
        $lastCheck = $request->input('last_check');

        // Initial check returns 0 to avoid immediate alert
        if (!$lastCheck) {
            return response()->json(['new_appointments' => 0]);
        }

        // Count appointments created after last check
        $count = $doctor->appointments()
            ->where('created_at', '>', $lastCheck)
            ->count();

        return response()->json(['new_appointments' => $count]);
    }
}
