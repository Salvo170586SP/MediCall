<?php

use App\Jobs\GenerateDoctorCodeItemJob;
use App\Livewire\Pages\Admin\Doctors\CreateDoctors;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Livewire\Livewire;

it('creates a doctor and dispatches a job with a valid doctor id', function () {
    Bus::fake();

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

    Bus::assertDispatchedSync(GenerateDoctorCodeItemJob::class, function (GenerateDoctorCodeItemJob $job): bool {
        return is_int($job->doctorId) && $job->doctorId > 0;
    });

    $user = User::query()->where('email', 'rossi@example.com')->first();

    expect($user)->not->toBeNull();
    expect($user->doctorDetail->code_item)->toMatch('/^DOC-\d{6}$/');
});
