<?php

namespace App\Livewire\Components;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;
use Livewire\Component;

class AccordionNotifications extends Component
{
    /** @var array<int, array{id: string, message: mixed, read: bool, created_at: string|null}> */
    public array $notifications = [];

    public int $unreadCount = 0;

    public function mount(): void
    {
        $this->loadNotifications();
    }

    #[On('notification-received')]
    public function refreshNotifications(): void
    {
        $this->loadNotifications();
    }

    public function deleteNotification(string $notificationId): void
    {
        $this->notificationQuery()->whereKey($notificationId)->delete();
        $this->loadNotifications();
    }

    public function deleteAllNotifications(): void
    {
        $this->currentUser()->notifications()->delete();
        $this->loadNotifications();
    }

    public function markAsRead(string $notificationId): void
    {
        $notification = $this->notificationQuery()->find($notificationId);

        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    /** @return MorphMany<DatabaseNotification, User> */
    protected function notificationQuery(): MorphMany
    {
        return $this->currentUser()->notifications();
    }

    protected function currentUser(): User
    {
        /** @var User $user */
        $user = Auth::user();

        return $user;
    }

    protected function loadNotifications(): void
    {
        $notifications = $this->notificationQuery()
            ->latest()
            ->limit(30)
            ->get();

        $this->unreadCount = $notifications->whereNull('read_at')->count();
        $this->notifications = $notifications->map(fn (DatabaseNotification $notification): array => [
            'id' => $notification->id,
            'message' => $notification->data['message'] ?? 'Nuova notifica',
            'read' => $notification->read(),
            'created_at' => $notification->created_at?->diffForHumans(),
        ])
            ->all();
    }

    public function render(): View
    {
        return view('livewire.components.accordion-notifications');
    }
}
