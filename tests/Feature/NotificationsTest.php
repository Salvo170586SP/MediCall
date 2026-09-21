<?php

use App\Livewire\Components\AccordionNotifications;
use App\Livewire\Pages\Doctors\DetailsDoctors;
use App\Models\Appointment;
use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Notification;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::findOrCreate('Guest', 'web');
});

it('notifies the guest when the doctor changes an appointment', function () {
    $doctor = User::factory()->create();
    $guest = User::factory()->create();
    $appointment = Appointment::create([
        'guest_id' => $guest->id,
        'doctor_id' => $doctor->id,
        'scheduled_at' => now()->addDay(),
        'status' => 'pending',
    ]);
    Notification::fake();

    Livewire::actingAs($doctor)
        ->test(DetailsDoctors::class, ['doctor' => $doctor])
        ->call('editStatusAppointment', $appointment->id, 'confirmed');

    Notification::assertSentTo($guest, UserNotification::class, function (UserNotification $notification): bool {
        return $notification->event === 'appointment_updated';
    });
});

it('respects notification preferences for guests and doctors', function (string $detailRelation) {
    $user = User::factory()->create();
    $user->{$detailRelation}()->create(['notifications' => false]);
    $notification = new UserNotification('test', 'Test notification');

    expect($notification->via($user))->toBe([]);

    $user->{$detailRelation}->update(['notifications' => true]);

    expect($notification->via($user))->toBe(['database', 'broadcast']);
})->with(['guestDetail', 'doctorDetail']);

it('deletes only notifications belonging to the authenticated user', function () {
    $guest = User::factory()->create();
    $otherGuest = User::factory()->create();
    $notification = DatabaseNotification::create([
        'id' => (string) str()->uuid(),
        'type' => UserNotification::class,
        'notifiable_type' => User::class,
        'notifiable_id' => $guest->id,
        'data' => ['message' => 'Notifica del guest'],
    ]);
    $otherNotification = DatabaseNotification::create([
        'id' => (string) str()->uuid(),
        'type' => UserNotification::class,
        'notifiable_type' => User::class,
        'notifiable_id' => $otherGuest->id,
        'data' => ['message' => 'Notifica privata'],
    ]);

    Livewire::actingAs($guest)
        ->test(AccordionNotifications::class)
        ->call('deleteNotification', $notification->id);

    expect(DatabaseNotification::find($notification->id))->toBeNull()
        ->and(DatabaseNotification::find($otherNotification->id))->not->toBeNull();
});
