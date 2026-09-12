<?php

use App\Livewire\Pages\Doctors\DetailsDoctors;
use App\Models\Appointment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('updates only the selected appointment status', function () {
    Role::create(['name' => 'Guest']);

    $doctor = User::factory()->create();
    $patient = User::factory()->create()->assignRole('Guest');
    $firstAppointment = Appointment::create([
        'guest_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'scheduled_at' => now()->addDay(),
        'status' => 'pending',
    ]);
    $secondAppointment = Appointment::create([
        'guest_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'scheduled_at' => now()->addDays(2),
        'status' => 'pending',
    ]);

    Livewire::actingAs($doctor)
        ->test(DetailsDoctors::class, ['doctor' => $doctor])
        ->call('editStatusAppointment', $firstAppointment->id, 'confirmed');

    expect($firstAppointment->fresh()->status)->toBe('confirmed')
        ->and($secondAppointment->fresh()->status)->toBe('pending');
});
