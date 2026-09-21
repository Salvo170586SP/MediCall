<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class UserNotification extends Notification
{
    public function __construct(
        public string $event,
        public string $message,
        public ?int $appointmentId = null,
        public ?int $documentId = null,
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        if ($notifiable instanceof User && (
            $notifiable->guestDetail?->notifications === false
            || $notifiable->doctorDetail?->notifications === false
        )) {
            return [];
        }

        return ['database', 'broadcast'];
    }

    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return (new BroadcastMessage($this->toArray($notifiable)))
            ->onConnection('sync');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'event' => $this->event,
            'message' => $this->message,
            'appointment_id' => $this->appointmentId,
            'document_id' => $this->documentId,
        ];
    }
}
