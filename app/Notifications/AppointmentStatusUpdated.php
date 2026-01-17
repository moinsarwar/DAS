<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentStatusUpdated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $appointment;
    public $status;

    public function __construct($appointment, $status)
    {
        $this->appointment = $appointment;
        $this->status = $status;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'status_update',
            'appointment_id' => $this->appointment->id,
            'message' => 'Your appointment status has been updated to ' . $this->status,
            'url' => route('patient.history'),
            'icon' => 'bi-info-circle',
            'color' => $this->status == 'Approved' ? 'text-success' : 'text-danger'
        ];
    }
}
