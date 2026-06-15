<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SafepayController extends Controller
{
    public function callback(Request $request)
    {
        // Safepay redirects here after payment attempt
        // e.g., ?order_id=123&sig=...&tracker=track_...
        
        $orderId = $request->order_id;
        $tracker = $request->tracker;
        $reference = $request->reference; // usually indicates success

        if ($tracker && $orderId) {
            $appointment = Appointment::where('id', $orderId)
                ->where('transaction_id', $tracker)
                ->first();

            if ($appointment) {
                if ($reference) {
                    $appointment->payment_status = 'paid';
                    $appointment->save();
                    return redirect()->route('patient.dashboard')->with('success', 'Payment successful! Your appointment is confirmed.');
                } else {
                    $appointment->payment_status = 'failed';
                    $appointment->save();
                    return redirect()->route('patient.dashboard')->with('error', 'Payment failed or was cancelled.');
                }
            }
        }

        return redirect()->route('patient.dashboard')->with('error', 'Invalid payment callback parameters.');
    }

    public function cancel(Request $request)
    {
        return redirect()->route('patient.dashboard')->with('error', 'Payment was cancelled.');
    }

    public function webhook(Request $request)
    {
        Log::info('Safepay Webhook received:', $request->all());

        // Basic verification (in production, verify X-SFPY-SIGNATURE)
        // For local/testing we just check if it contains valid tracker
        
        $tracker = $request->input('data.tracker.token');
        $state = $request->input('data.tracker.state'); // e.g. 'PAID', 'UNPAID'
        
        // Sometimes Safepay payload differs based on version.
        if (!$tracker) {
            // Try another payload structure
            $tracker = $request->input('tracker');
            $state = $request->input('state');
        }

        if ($tracker && $state === 'PAID') {
            $appointment = Appointment::where('transaction_id', $tracker)->first();
            if ($appointment && $appointment->payment_status !== 'paid') {
                $appointment->payment_status = 'paid';
                $appointment->save();
                Log::info('Appointment ' . $appointment->id . ' marked as paid via webhook.');
                
                // Notify doctor and patient
                $appointment->doctor->user->notify(new \App\Notifications\AppointmentNotification([
                    'message' => 'Payment received for appointment by ' . $appointment->patient->name,
                    'url' => route('doctor.appointments'),
                    'icon' => 'bi-cash',
                    'color' => 'text-success',
                    'type' => 'payment_success'
                ]));
                
                $appointment->patient->notify(new \App\Notifications\AppointmentNotification([
                    'message' => 'Payment successful for Dr. ' . $appointment->doctor->user->name,
                    'url' => route('patient.history'),
                    'icon' => 'bi-check-circle',
                    'color' => 'text-success',
                    'type' => 'payment_success'
                ]));
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
