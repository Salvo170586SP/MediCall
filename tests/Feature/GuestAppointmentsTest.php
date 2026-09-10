<?php

use App\Livewire\Pages\Guest\Appointments\IndexAppointments;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('shows only the authenticated guest appointments and their details', function () {
    Role::create(['name' => 'Guest']);
    Role::create(['name' => 'Doctor']);

    $guest = User::factory()->create(['name' => 'Paziente Test'])->assignRole('Guest');
    $doctor = User::factory()->create(['name' => 'Dottore Test'])->assignRole('Doctor');
    $otherGuest = User::factory()->create()->assignRole('Guest');

    $appointment = Appointment::create([
        'guest_id' => $guest->id,
        'doctor_id' => $doctor->id,
        'scheduled_at' => now()->addDay(),
        'duration' => 30,
        'type' => 'video',
        'status' => 'pending',
        'reason' => 'Controllo periodico',
    ]);
    Appointment::create([
        'guest_id' => $otherGuest->id,
        'doctor_id' => $doctor->id,
        'scheduled_at' => now()->addDays(2),
        'duration' => 30,
        'type' => 'video',
        'status' => 'pending',
    ]);

    Livewire::actingAs($guest)
        ->test(IndexAppointments::class)
        ->assertSee('Dott. Dottore Test')
        ->assertSee('Controllo periodico')
        ->assertSee('1 appuntamento')
        ->assertSee("appointment-{$appointment->id}", false)
        ->assertDontSee('Non disponibile');
});
