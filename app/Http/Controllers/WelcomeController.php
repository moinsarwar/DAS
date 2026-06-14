<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

class WelcomeController extends Controller
{
    private function resolveView($viewName)
    {
        $settings = \App\Models\ClinicSetting::first();
        $theme = $settings->ui_theme ?? 'default';

        if ($theme !== 'default') {
            $themeView = "themes.{$theme}.{$viewName}";
            if (view()->exists($themeView)) {
                return $themeView;
            }
        }
        
        // Fallback to default structure
        if ($viewName === 'welcome') return 'welcome';
        return "landing.{$viewName}";
    }

    public function index()
    {
        $stats = [];
        $user = Auth::user();
        $doctors = \App\Models\Doctor::with(['user', 'category', 'schedules'])->get();

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

        return view($this->resolveView('welcome'), compact('stats', 'doctors'));
    }

    public function doctors()
    {
        $categories = \App\Models\Category::with(['doctors.user', 'doctors.schedules'])
            ->has('doctors')
            ->get();
        return view($this->resolveView('doctors'), compact('categories'));
    }

    public function about()
    {
        return view($this->resolveView('about'));
    }

    public function contact()
    {
        return view($this->resolveView('contact'));
    }

    public function storeContact(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        \App\Models\ContactMessage::create($request->all());

        return back()->with('success', 'Thank you for contacting us. We will get back to you shortly.');
    }

    public function showPage($slug)
    {
        $page = \App\Models\Page::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return view($this->resolveView('page'), compact('page'));
    }
}
