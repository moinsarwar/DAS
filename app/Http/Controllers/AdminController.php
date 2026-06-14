<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Prescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'doctors' => Doctor::count(),
            'patients' => User::where('role', 'patient')->count(),
            'appointments' => Appointment::count(),
            'pending' => Appointment::where('status', 'Pending')->count(),
            'categories' => Category::count(),
        ];
        return view('admin.dashboard', compact('stats'));
    }

    // Categories
    public function categories()
    {
        $categories = Category::withCount('doctors')->orderBy('id', 'desc')->get();
        return view('admin.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);
        
        $data = $request->only(['name', 'description', 'icon']);
        if ($request->hasFile('category_image')) {
            $path = $request->file('category_image')->store('categories', 'public');
            $data['image_path'] = $path;
        }

        Category::create($data);
        return back()->with('success', 'Category created successfully.');
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
        ]);

        $category = Category::findOrFail($id);
        $data = $request->only(['name', 'description', 'icon']);

        if ($request->boolean('remove_image')) {
            if ($category->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($category->image_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($category->image_path);
            }
            $data['image_path'] = null;
        } elseif ($request->hasFile('category_image')) {
            if ($category->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($category->image_path)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($category->image_path);
            }
            $path = $request->file('category_image')->store('categories', 'public');
            $data['image_path'] = $path;
        }

        $category->update($data);
        return back()->with('success', 'Category updated.');
    }

    public function deleteCategory($id)
    {
        $category = Category::findOrFail($id);
        if ($category->image_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($category->image_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($category->image_path);
        }
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    // Doctors
    public function doctors()
    {
        $doctors = Doctor::with('user', 'category')->orderBy('id', 'desc')->get();
        $categories = Category::all();
        return view('admin.doctors', compact('doctors', 'categories'));
    }

    public function storeDoctor(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'mobile_number' => 'required|string|max:20',
            'password' => 'required|min:8',
            'category_id' => 'required|exists:doctor_categories,id',
            'bio' => 'nullable|string',
            'fees' => 'required|numeric|min:0',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
            'role' => 'doctor',
        ]);

        Doctor::create([
            'user_id' => $user->id,
            'category_id' => $request->category_id,
            'bio' => $request->bio,
            'fees' => $request->fees,
        ]);

        return back()->with('success', 'Doctor added successfully.');
    }

    public function editDoctor($id)
    {
        $doctor = Doctor::with('user')->findOrFail($id);
        $categories = Category::all();
        return view('admin.edit-doctor', compact('doctor', 'categories'));
    }

    public function updateDoctor(Request $request, $id)
    {
        $doctor = Doctor::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $doctor->user->id,
            'mobile_number' => 'required|string|max:20',
            'category_id' => 'required|exists:doctor_categories,id',
            'bio' => 'nullable|string',
            'qualification' => 'nullable|string',
            'experience_years' => 'nullable|integer',
            'fees' => 'nullable|numeric|min:0'
        ]);

        $doctor->user->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
        ]);

        $doctor->update([
            'category_id' => $request->category_id,
            'bio' => $request->bio,
            'qualification' => $request->qualification,
            'experience_years' => $request->experience_years,
            'fees' => $request->fees,
        ]);

        return redirect()->route('admin.doctors')->with('success', 'Doctor updated.');
    }

    public function deleteDoctor($id)
    {
        $doctor = Doctor::findOrFail($id);
        $user = $doctor->user; // Get associated user to delete if needed
        $doctor->delete();
        // optionally delete the user if they rely solely on being a doctor, 
        // but typically users table is master. If we want to remove access completely:
        if ($user && $user->role === 'doctor') {
            $user->delete();
        }

        return back()->with('success', 'Doctor deleted successfully.');
    }

    // Receptionists
    public function receptionists()
    {
        $receptionists = User::where('role', 'receptionist')->orderBy('id', 'desc')->get();
        return view('admin.receptionists', compact('receptionists'));
    }

    public function storeReceptionist(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'mobile_number' => 'required|string|max:20',
            'password' => 'required|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'mobile_number' => $request->mobile_number,
            'password' => Hash::make($request->password),
            'role' => 'receptionist',
        ]);

        return back()->with('success', 'Receptionist added successfully.');
    }

    public function deleteReceptionist($id)
    {
        User::where('role', 'receptionist')->findOrFail($id)->delete();
        return back()->with('success', 'Receptionist deleted.');
    }

    // Appointments
    public function appointments(Request $request)
    {
        $query = Appointment::with(['patient', 'doctor.user', 'schedule', 'prescription'])
            ->orderBy('id', 'desc');

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('date')) {
            $query->where('appointment_date', $request->date);
        }

        $appointments = $query->get();
        $doctors = Doctor::with(['user', 'category'])->get(); // For filter dropdown

        return view('admin.appointments', compact('appointments', 'doctors'));
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

    public function approveAppointment($id)
    {
        $app = Appointment::findOrFail($id);
        $app->update(['status' => 'Approved']);
        return back()->with('success', 'Appointment Approved.');
    }

    public function denyAppointment($id)
    {
        $app = Appointment::findOrFail($id);
        $app->update(['status' => 'Denied']);
        return back()->with('success', 'Appointment Denied.');
    }

    public function checkAppointment($id)
    {
        $app = Appointment::findOrFail($id);
        $app->update(['status' => 'Checked']);
        return back()->with('success', 'Appointment marked as Checked.');
    }

    // Delete appointment (ONLY if not Checked)
    public function deleteAppointment($id)
    {
        $app = Appointment::findOrFail($id);

        if ($app->status === 'Checked') {
            return back()->with('error', 'Cannot delete a Checked appointment.');
        }

        $app->delete();
        return back()->with('success', 'Appointment deleted.');
    }

    // Patient Details & History
    public function patients()
    {
        $patients = User::where('role', 'patient')
            ->withCount('patientAppointments')
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.patients', compact('patients'));
    }

    public function storePatient(Request $request)
    {
        // Removed CNIC uniqueness check to allow families sharing CNIC or multiple records

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'cnic' => 'required|numeric|digits:13', // No dashes, 13 digits
        ]);

        $mrNumber = User::generateMrNumber();

        User::create([
            'name' => $request->name,
            'mobile_number' => $request->mobile_number,
            'cnic' => $request->cnic,
            'mr_number' => $mrNumber,
            'role' => 'patient',
        ]);

        return back()->with('success', 'Patient created successfully. MR Number: ' . $mrNumber);
    }

    public function deletePatient($id)
    {
        $patient = User::where('role', 'patient')->findOrFail($id);

        // Optional: Check if they have critical data or just cascade delete via Foreign Keys
        // For now, standard delete
        $patient->delete();

        return back()->with('success', 'Patient deleted successfully.');
    }

    public function patientDetails($id)
    {
        $patient = User::where('role', 'patient')->findOrFail($id);

        $appointments = Appointment::where('patient_id', $id)
            ->with(['doctor.user', 'schedule', 'prescription'])
            ->orderBy('id', 'desc')
            ->get();

        $prescriptions = Prescription::where('patient_id', $id)
            ->with(['doctor.user', 'appointment'])
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.patient-details', compact('patient', 'appointments', 'prescriptions'));
    }

    // Admin Profile
    public function showProfile()
    {
        $admin = auth()->user();
        return view('admin.profile', compact('admin'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'profile_photo' => 'nullable|image|max:2048',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
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
    // Collect Fee & Approve (Admin)
    public function collectFee(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $request->validate(['fee' => 'required|numeric']);

        $appointment->update([
            'fee' => $request->fee,
            'status' => 'Approved'
        ]);

        // Notifications
        $msg = 'Fee collected by Admin. Appointment is now Approved.';

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

    public function settings()
    {
        $settings = \App\Models\ClinicSetting::first();
        return view('admin.settings', compact('settings'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'landline' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'address' => 'required|string',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            
            'clinic_name' => 'nullable|string|max:255',
            'hero_title' => 'nullable|string|max:255',
            'hero_subtitle' => 'nullable|string|max:255',
            'hero_badge' => 'nullable|string|max:255',
            'hero_description' => 'nullable|string',
            'clinic_hours' => 'nullable|string|max:255',
            'clinic_days' => 'nullable|string|max:255',
            'notice_text' => 'nullable|string',
            'about_short' => 'nullable|string',
            'social_facebook' => 'nullable|url|max:255',
            'social_twitter' => 'nullable|url|max:255',
            'social_instagram' => 'nullable|url|max:255',
            'doctors_title' => 'nullable|string|max:255',
            'doctors_description' => 'nullable|string',
            'features' => 'nullable|string',
            'faqs' => 'nullable|string',
        ]);

        $settings = \App\Models\ClinicSetting::first();
        if (!$settings) {
            $settings = new \App\Models\ClinicSetting();
        }

        $settings->phone = $request->phone;
        $settings->landline = $request->landline;
        $settings->contact_email = $request->contact_email;
        $settings->address = $request->address;
        
        $settings->clinic_name = $request->clinic_name;
        $settings->hero_title = $request->hero_title;
        $settings->hero_subtitle = $request->hero_subtitle;
        $settings->hero_badge = $request->hero_badge;
        $settings->hero_description = $request->hero_description;
        $settings->clinic_hours = $request->clinic_hours;
        $settings->clinic_days = $request->clinic_days;
        $settings->notice_text = $request->notice_text;
        $settings->about_short = $request->about_short;
        $settings->social_facebook = $request->social_facebook;
        $settings->social_twitter = $request->social_twitter;
        $settings->social_instagram = $request->social_instagram;
        $settings->doctors_title = $request->doctors_title;
        $settings->doctors_description = $request->doctors_description;

        if ($request->filled('features')) {
            $settings->features = json_decode($request->features, true);
        } else {
            $settings->features = [];
        }

        if ($request->filled('faqs')) {
            $settings->faqs = json_decode($request->faqs, true);
        } else {
            $settings->faqs = [];
        }

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($settings->logo_path && \Illuminate\Support\Facades\Storage::exists('public/' . $settings->logo_path)) {
                \Illuminate\Support\Facades\Storage::delete('public/' . $settings->logo_path);
            }
            $path = $request->file('logo')->store('uploads', 'public');
            $settings->logo_path = $path;
        }

        $settings->save();

        return back()->with('success', 'Settings updated successfully.');
    }
    // Contact Messages
    public function messages()
    {
        $messages = \App\Models\ContactMessage::orderBy('created_at', 'desc')->get();
        return view('admin.messages', compact('messages'));
    }

    public function markMessageRead($id)
    {
        \App\Models\ContactMessage::findOrFail($id)->update(['is_read' => true]);
        return back()->with('success', 'Message marked as read.');
    }

    public function deleteMessage($id)
    {
        \App\Models\ContactMessage::findOrFail($id)->delete();
        return back()->with('success', 'Message deleted.');
    }

    // Dynamic Pages CMS Actions
    public function pages()
    {
        $pages = \App\Models\Page::orderBy('id', 'desc')->get();
        return view('admin.pages.index', compact('pages'));
    }

    public function createPage()
    {
        return view('admin.pages.create');
    }

    public function storePage(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug',
            'content' => 'nullable|string',
        ]);

        \App\Models\Page::create([
            'title' => $request->title,
            'slug' => \Illuminate\Support\Str::slug($request->slug),
            'content' => $request->content,
            'is_active' => $request->has('is_active'),
            'show_in_navbar' => $request->has('show_in_navbar'),
            'show_in_footer' => $request->has('show_in_footer'),
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Dynamic page created successfully.');
    }

    public function editPage($id)
    {
        $page = \App\Models\Page::findOrFail($id);
        return view('admin.pages.edit', compact('page'));
    }

    public function updatePage(Request $request, $id)
    {
        $page = \App\Models\Page::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:pages,slug,' . $id,
            'content' => 'nullable|string',
        ]);

        $page->update([
            'title' => $request->title,
            'slug' => \Illuminate\Support\Str::slug($request->slug),
            'content' => $request->content,
            'is_active' => $request->has('is_active'),
            'show_in_navbar' => $request->has('show_in_navbar'),
            'show_in_footer' => $request->has('show_in_footer'),
        ]);

        return redirect()->route('admin.pages.index')->with('success', 'Dynamic page updated successfully.');
    }

    public function deletePage($id)
    {
        \App\Models\Page::findOrFail($id)->delete();
        return back()->with('success', 'Dynamic page deleted successfully.');
    }
}
