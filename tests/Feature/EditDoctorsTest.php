<?php

use App\Livewire\Pages\Doctors\EditDoctors;
use App\Models\DoctorDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

function editDoctorsAttributes(): array
{
    return [
        'name' => 'Dottore aggiornato',
        'phone' => '3331234567',
        'bio' => 'Bio aggiornata',
        'specialty' => 'Cardiologia',
        'sub_specialty' => 'Aritmologia',
        'city' => 'Roma',
        'slot_duration' => 30,
        'is_active' => true,
        'available_hours' => [
            'mon' => ['09:00', '17:00'],
            'tue' => ['09:00', '17:00'],
            'wed' => ['09:00', '17:00'],
            'thu' => ['09:00', '17:00'],
            'fri' => ['09:00', '17:00'],
            'sat' => ['09:00', '17:00'],
            'sun' => ['09:00', '17:00'],
        ],
    ];
}

it('redirects a doctor to their profile after editing', function () {
    Role::create(['name' => 'Doctor']);

    $doctor = User::factory()->create();
    $doctor->assignRole('Doctor');
    DoctorDetail::factory()->for($doctor, 'user')->create();

    Livewire::actingAs($doctor)
        ->test(EditDoctors::class, ['doctor' => $doctor])
        ->set(editDoctorsAttributes())
        ->call('editDoctor')
        ->assertRedirect(route('doctor.profile'));
});

it('redirects an admin to the doctors list after editing', function () {
    Role::create(['name' => 'Admin']);

    $admin = User::factory()->create();
    $admin->assignRole('Admin');

    $doctor = User::factory()->create();
    DoctorDetail::factory()->for($doctor, 'user')->create();

    Livewire::actingAs($admin)
        ->test(EditDoctors::class, ['doctor' => $doctor])
        ->set(editDoctorsAttributes())
        ->call('editDoctor')
        ->assertRedirect(route('doctors'));
});
