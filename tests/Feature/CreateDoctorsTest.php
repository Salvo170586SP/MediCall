<?php

use App\Livewire\Pages\Doctors\CreateDoctors;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('creates a doctor and dispatches a job with a valid doctor id', function () {
    Role::create(['name' => 'Doctor']);

    Livewire::test(CreateDoctors::class)
        ->set('name', 'Dottor Rossi')
        ->set('email', 'rossi@example.com')
        ->set('phone', '3331234567')
        ->set('license_number', 'LIC-001')
        ->set('bio', 'Specialista cardiologia')
        ->set('specialty', 'Cardiologia')
        ->set('sub_specialty', 'Ipertensione')
        ->set('city', 'Roma')
        ->set('slot_duration', 30)
        ->set('is_active', true)
        ->set('available_hours', [
            'mon' => ['09:00', '17:00'],
            'tue' => ['09:00', '17:00'],
            'wed' => ['09:00', '17:00'],
            'thu' => ['09:00', '17:00'],
            'fri' => ['09:00', '17:00'],
            'sat' => ['09:00', '17:00'],
            'sun' => ['09:00', '17:00'],
        ])
        ->call('addDoctor')
        ->assertSessionHas('success')
        ->assertSessionMissing('error');

    $user = User::query()->where('email', 'rossi@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->doctorDetail->code_item)->toMatch('/^D-\d{6}$/');
});
