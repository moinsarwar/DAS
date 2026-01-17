<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAppointmentBooking extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $appointment;

    public function __construct($appointment)
    {
        $this->appointment = $appointment;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'new_booking',
            'appointment_id' => $this->appointment->id,
            'message' => 'New appointment booked for ' . $this->appointment->appointment_date . ' at ' . $this->appointment->time_slot,
            'url' => route('doctor.appointments', ['status' => 'Pending']),
            'icon' => 'bi-calendar-plus',
            'color' => 'text-primary'
        ];
    }
}
