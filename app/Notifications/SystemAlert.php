<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SystemAlert extends Notification
{
    use Queueable;

    private $data;

    /**
     * Create a new notification instance.
     * $data should contain: title, message, icon (optional), action_url (optional)
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title'      => $this->data['title'] ?? 'System Update',
            'message'    => $this->data['message'] ?? 'You have a new update.',
            'icon'       => $this->data['icon'] ?? 'fa-info-circle',
            'action_url' => $this->data['action_url'] ?? '#',
            'type'       => $this->data['type'] ?? 'system'
        ];
    }
}
