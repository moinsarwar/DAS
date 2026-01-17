<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'index'])->name('welcome');

// Auth Routes
// Auth Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ===============================
// Admin Routes
// ===============================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    // Categories
    Route::get('/categories', [AdminController::class, 'categories'])->name('categories');
    Route::post('/categories', [AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{id}', [AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{id}', [AdminController::class, 'deleteCategory'])->name('categories.delete');

    // Doctors
    Route::get('/doctors', [AdminController::class, 'doctors'])->name('doctors');
    Route::post('/doctors', [AdminController::class, 'storeDoctor'])->name('doctors.store');
    Route::get('/doctors/{id}/edit', [AdminController::class, 'editDoctor'])->name('doctors.edit');
    Route::put('/doctors/{id}', [AdminController::class, 'updateDoctor'])->name('doctors.update');
    Route::delete('/doctors/{id}', [AdminController::class, 'deleteDoctor'])->name('doctors.delete');

    // Receptionists
    Route::get('/receptionists', [AdminController::class, 'receptionists'])->name('receptionists');
    Route::post('/receptionists', [AdminController::class, 'storeReceptionist'])->name('receptionists.store');
    Route::delete('/receptionists/{id}', [AdminController::class, 'deleteReceptionist'])->name('receptionists.delete');

    // Appointments
    Route::get('/appointments', [AdminController::class, 'appointments'])->name('appointments');
    Route::post('/appointments/{id}/approve', [AdminController::class, 'approveAppointment'])->name('appointments.approve');
    Route::post('/appointments/{id}/deny', [AdminController::class, 'denyAppointment'])->name('appointments.deny');
    Route::post('/appointments/{id}/check', [AdminController::class, 'checkAppointment'])->name('appointments.check');
    Route::delete('/appointments/{id}', [AdminController::class, 'deleteAppointment'])->name('appointments.delete');
    Route::post('/appointments/{id}/vitals', [AdminController::class, 'storeVitals'])->name('appointments.vitals');
    Route::post('/appointments/{id}/collect', [AdminController::class, 'collectFee'])->name('appointments.collect');
    Route::post('/appointments/{id}/refund', [AdminController::class, 'refundFee'])->name('appointments.refund');

    // Patients
    Route::get('/patients', [AdminController::class, 'patients'])->name('patients');
    Route::post('/patients', [AdminController::class, 'storePatient'])->name('patients.store');
    Route::delete('/patients/{id}', [AdminController::class, 'deletePatient'])->name('patients.delete');
    Route::get('/patients/{id}', [AdminController::class, 'patientDetails'])->name('patients.details');

    // Admin Profile
    Route::get('/profile', [AdminController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AdminController::class, 'updateProfile'])->name('profile.update');

    // Clinic Settings
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});

// ===============================
// Doctor Routes
// ===============================
Route::middleware(['auth', 'doctor'])->prefix('doctor')->name('doctor.')->group(function () {
    Route::get('/dashboard', [DoctorController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [DoctorController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [DoctorController::class, 'updateProfile'])->name('profile.update');

    // Schedules
    Route::get('/schedules', [DoctorController::class, 'schedules'])->name('schedules');
    Route::post('/schedules', [DoctorController::class, 'storeSchedule'])->name('schedules.store');
    Route::put('/schedules/{id}', [DoctorController::class, 'updateSchedule'])->name('schedules.update');
    Route::delete('/schedules/{id}', [DoctorController::class, 'deleteSchedule'])->name('schedules.delete');

    // Blocked Dates
    Route::post('/blocked-dates', [DoctorController::class, 'storeBlockedDate'])->name('blocked_dates.store');
    Route::delete('/blocked-dates/{id}', [DoctorController::class, 'deleteBlockedDate'])->name('blocked_dates.delete');

    // Appointments
    Route::get('/appointments', [DoctorController::class, 'appointments'])->name('appointments');
    Route::post('/appointments/{id}/approve', [DoctorController::class, 'approveAppointment'])->name('appointments.approve');
    Route::post('/appointments/{id}/deny', [DoctorController::class, 'denyAppointment'])->name('appointments.deny');
    Route::post('/appointments/{id}/check', [DoctorController::class, 'checkAppointment'])->name('appointments.check');
    Route::delete('/appointments/{id}', [DoctorController::class, 'deleteAppointment'])->name('appointments.delete');
    Route::get('/check-new-appointments', [DoctorController::class, 'checkNewAppointments'])->name('appointments.check_new');

    // Prescription
    Route::get('/appointments/{id}/prescription', [DoctorController::class, 'showPrescriptionForm'])->name('prescription.form');
    Route::post('/appointments/{id}/prescription', [DoctorController::class, 'storePrescription'])->name('prescription.store');
    Route::get('/appointments/{id}/prescription/print', [DoctorController::class, 'printPrescription'])->name('prescription.print');

    // Patient History
    Route::get('/patient/{id}/history', [DoctorController::class, 'patientHistory'])->name('patient.history');
});

// ===============================
// Receptionist Routes
// ===============================
Route::middleware(['auth', 'receptionist'])->prefix('receptionist')->name('receptionist.')->group(function () {
    // Middleware to check role 'receptionist' can be added here or in Kernel
    // For now assuming role check is sufficient or reused

    Route::get('/dashboard', [App\Http\Controllers\ReceptionistController::class, 'dashboard'])->name('dashboard');
    Route::post('/check-patient', [App\Http\Controllers\ReceptionistController::class, 'checkPatient'])->name('check.patient');
    Route::post('/store-patient', [App\Http\Controllers\ReceptionistController::class, 'storePatient'])->name('store.patient');
    Route::get('/book/{patient}', [App\Http\Controllers\ReceptionistController::class, 'bookAppointment'])->name('book.appointment');
    Route::get('/doctor/{id}/slots', [App\Http\Controllers\ReceptionistController::class, 'getDoctorSlots'])->name('doctor.slots');
    Route::post('/book/{patient}', [App\Http\Controllers\ReceptionistController::class, 'storeAppointment'])->name('store.appointment');

    // Appointment Management
    Route::get('/appointments', [App\Http\Controllers\ReceptionistController::class, 'appointments'])->name('appointments');
    Route::post('/appointments/{id}/cancel', [App\Http\Controllers\ReceptionistController::class, 'cancelAppointment'])->name('appointments.cancel');
    Route::get('/appointments/{id}/edit', [App\Http\Controllers\ReceptionistController::class, 'editAppointment'])->name('appointments.edit');
    Route::put('/appointments/{id}', [App\Http\Controllers\ReceptionistController::class, 'updateAppointment'])->name('appointments.update');
    Route::delete('/appointments/{id}', [App\Http\Controllers\ReceptionistController::class, 'deleteAppointment'])->name('appointments.delete');

    // Receipt & Fee Collection
    Route::get('/appointments/{id}/receipt', [App\Http\Controllers\ReceptionistController::class, 'receipt'])->name('receipt');
    Route::post('/appointments/{id}/collect', [App\Http\Controllers\ReceptionistController::class, 'collectFee'])->name('appointments.collect');

    // Vitals
    // Vitals
    Route::post('/appointments/{id}/vitals', [App\Http\Controllers\ReceptionistController::class, 'storeVitals'])->name('appointments.vitals');
    Route::post('/appointments/{id}/refund', [App\Http\Controllers\ReceptionistController::class, 'refundFee'])->name('appointments.refund');
});

// ===============================
// Patient Routes
// ===============================
Route::middleware(['auth', 'patient'])->prefix('patient')->name('patient.')->group(function () {
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');

    // Profile
    Route::get('/profile', [PatientController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [PatientController::class, 'updateProfile'])->name('profile.update');

    // Doctor Details & Booking
    Route::get('/doctor/{id}', [PatientController::class, 'doctorDetails'])->name('doctor.details');
    Route::post('/book', [PatientController::class, 'bookAppointment'])->name('book');

    // Appointments
    Route::delete('/appointments/{id}', [PatientController::class, 'deleteAppointment'])->name('appointments.delete');
    Route::get('/appointments/{id}/prescription', [PatientController::class, 'viewPrescription'])->name('prescription.view');

    // Medical History
    Route::get('/history', [PatientController::class, 'myHistory'])->name('history');
});

// Notifications
Route::middleware('auth')->prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/fetch', [\App\Http\Controllers\NotificationController::class, 'fetchNotifications'])->name('fetch');
    Route::post('/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markRead'])->name('read');
    Route::post('/read-all', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('readAll');
});