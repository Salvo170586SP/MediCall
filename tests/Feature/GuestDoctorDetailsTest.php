<?php

use App\Livewire\Pages\Guest\Doctors\DetailsDoctors;
use App\Models\DoctorDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('allows a guest to book an available doctor slot', function () {
    Role::create(['name' => 'Doctor']);
    Role::create(['name' => 'Guest']);

    $doctor = User::factory()->create()->assignRole('Doctor');
    DoctorDetail::factory()->for($doctor, 'user')->create([
        'available_hours' => ['mon' => ['09:00', '10:00']],
        'slot_duration' => 30,
        'is_active' => true,
    ]);
    $guest = User::factory()->create()->assignRole('Guest');
    $date = Carbon::now()->next(Carbon::MONDAY)->toDateString();

    Livewire::actingAs($guest)
        ->test(DetailsDoctors::class, ['doctor' => $doctor])
        ->call('selectDate', $date)
        ->set('scheduled_time', '09:00')
        ->call('save')
        ->assertHasNoErrors()
        ->assertSee('booking-success-title', false)
        ->assertSee('Visita prenotata. Il dottore riceverà la tua richiesta.');

    expect($guest->appointmentsPatient()->first())
        ->not->toBeNull()
        ->doctor_id->toBe($doctor->id)
        ->status->toBe('pending');
});
