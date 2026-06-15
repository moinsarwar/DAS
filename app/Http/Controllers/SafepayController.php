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
        $orderId = $request->query('order_id');
        $tracker = $request->query('tracker');
        $reference = $request->query('reference');

        if ($tracker && $orderId) {
            $appointment = Appointment::where('id', $orderId)
                ->where('transaction_id', $tracker)
                ->first();

            if ($appointment) {
                // In V3, Safepay redirects back with tracker and potentially reference.
                // It is safer to rely on webhook for actual capture, but for UX we can
                // assume success if reference is present.
                if ($reference) {
                    $appointment->payment_status = 'paid';
                    $appointment->save();
                    return redirect()->route('patient.dashboard')->with('success', 'Payment successful! Your appointment is confirmed.');
                } else {
                    // If no reference, it might just be a return. Let the webhook handle final status,
                    // but show a processing message to the user.
                    return redirect()->route('patient.dashboard')->with('info', 'Payment is being processed. Status will update shortly.');
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
        Log::info('Safepay Webhook received');

        $webhookSecret = config('services.safepay.webhook_secret');
        $signature = $request->header('X-SFPY-SIGNATURE');

        if (!$signature) {
            Log::error('Safepay Webhook missing signature');
            return response('Missing signature', 400);
        }

        $payload = $request->getContent(); // Get raw JSON payload
        
        try {
            $event = \Safepay\Webhook::constructEvent($payload, $signature, $webhookSecret);
        } catch (\Exception $e) {
            Log::error('Safepay Webhook Signature Error: ' . $e->getMessage());
            return response('Error verifying webhook signature', 400);
        }

        Log::info('Safepay Webhook Event Type: ' . $event->type);

        if ($event->type === 'payment.succeeded') {
            $payment = $event->data;
            
            // Extract tracker token from the payment event data
            $tracker = is_array($payment) && isset($payment['tracker']['token']) 
                ? $payment['tracker']['token'] 
                : (is_array($payment) && isset($payment['tracker']) ? $payment['tracker'] : null);
                
            // Fallback: If it's an object instead of array
            if (!$tracker && is_object($payment)) {
                $tracker = isset($payment->tracker->token) ? $payment->tracker->token : ($payment->tracker ?? null);
            }

            if ($tracker) {
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
            } else {
                Log::error('Safepay Webhook could not extract tracker from payload', ['data' => $payment]);
            }
        }

        return response()->json(['status' => 'ok']);
    }
}
