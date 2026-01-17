<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    public function index()
    {
        $stats = [];
        $user = Auth::user();

        if ($user) {
            if ($user->role === 'admin') {
                $stats = [
                    'doctors' => \App\Models\Doctor::count(),
                    'patients' => \App\Models\User::where('role', 'patient')->count(),
                    'revenue' => \App\Models\Appointment::sum('fee'),
                    'appointments' => \App\Models\Appointment::count(),
                ];
            } elseif ($user->role === 'doctor') {
                $doctor = $user->doctor; // Relationship might return null if not setup
                if ($doctor) {
                    $stats = [
                        'pending' => $doctor->appointments()->where('status', 'Pending')->count(),
                        'approved' => $doctor->appointments()->where('status', 'Approved')->count(),
                        'checked' => $doctor->appointments()->where('status', 'Checked')->count(),
                    ];
                }
            } elseif ($user->role === 'receptionist') {
                $today = date('Y-m-d');
                $stats = [
                    'today_booked' => \App\Models\Appointment::whereDate('appointment_date', $today)->count(),
                    'pending' => \App\Models\Appointment::where('status', 'Pending')->count(),
                    'doctors' => \App\Models\Doctor::count(),
                ];
            } elseif ($user->role === 'patient') {
                $stats = [
                    'upcoming' => \App\Models\Appointment::where('patient_id', $user->id)
                        ->where('appointment_date', '>=', date('Y-m-d'))
                        ->where('status', '!=', 'Cancelled')
                        ->count(),
                    'history' => \App\Models\Appointment::where('patient_id', $user->id)->count(),
                ];
            }
        }

        return view('welcome', compact('stats'));
    }
}
