<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $data;

    /**
     * Create a new notification instance.
     * 
     * @param array $data [message, url, icon, color, type]
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => $this->data['type'] ?? 'info',
            'message' => $this->data['message'],
            'url' => $this->data['url'] ?? '#',
            'icon' => $this->data['icon'] ?? 'bi-bell',
            'color' => $this->data['color'] ?? 'text-primary',
            'appointment_id' => $this->data['appointment_id'] ?? null
        ];
    }
}
