<?php

use App\Jobs\GenerateDoctorCodeItemJob;
use App\Models\DoctorDetail;
use App\Models\User;
use Spatie\Permission\Models\Role;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('genera il primo codice per un dottore senza code_item', function () {
    Role::create(['name' => 'Doctor']);
    $doctor = User::factory()->create();
    $doctor->assignRole('Doctor');

    $doctorDetail = DoctorDetail::create([
        'doctor_id'       => $doctor->id,
        'license_number'  => 'IT-asdasfdadsf',
        'specialty'       =>  'cardiologo',
        'available_hours' => [
            'mon' => ['09:00', '13:00'],
            'tue' => ['09:00', '13:00', '15:00', '18:00'],
            'wed' => ['09:00', '13:00'],
            'thu' => ['14:00', '18:00'],
            'fri' => ['09:00', '12:00'],
        ],
        'slot_duration'   => fake()->randomElement([20, 30, 45]),
        'is_active'       => true,
    ]);

    (new GenerateDoctorCodeItemJob($doctorDetail->doctor_id))->handle();

    expect($doctorDetail->fresh()->code_item)->toBe('D-000001');
});

test('genera un codice progressivo se esistono già altri codici', function () {
    $doctor3 = User::factory()->create();

    DoctorDetail::factory()->withCodeItem('D-000001')->create();
    DoctorDetail::factory()->withCodeItem('D-000005')->create();

    // il nuovo dottore, senza codice — anche questo con la factory
    $newDoctorDetail = DoctorDetail::factory()
        ->withoutCodeItem()
        ->create(['doctor_id' => $doctor3->id]);

    (new GenerateDoctorCodeItemJob($newDoctorDetail->doctor_id))->handle();

    expect($newDoctorDetail->fresh()->code_item)->toBe('D-000006');
});

test('non fa nulla se il dottore ha già un code_item', function () {
    $doctor = User::factory()->create();

    $doctorDetail = DoctorDetail::create([
        'doctor_id' => $doctor->id,
        'code_item' => 'D-000042',
        'license_number'  => 'IT' . fake()->unique()->numerify('######'),
        'city'            => fake()->city(),
        'phone'           => fake()->numerify('3#########'),
        'specialty'       =>  'sdfdsfsdf',
        'available_hours' => [
            'mon' => ['09:00', '13:00'],
            'tue' => ['09:00', '13:00', '15:00', '18:00'],
            'wed' => ['09:00', '13:00'],
            'thu' => ['14:00', '18:00'],
            'fri' => ['09:00', '12:00'],
        ],
        'slot_duration'   => fake()->randomElement([20, 30, 45]),
        'is_active'       => true,
    ]);

    (new GenerateDoctorCodeItemJob($doctorDetail->doctor_id))->handle();

    expect($doctorDetail->fresh()->code_item)->toBe('D-000042');
});

test('non fa nulla se il doctor_id non esiste', function () {
    $job = new GenerateDoctorCodeItemJob(doctorId: 9999);

    expect(fn() => $job->handle())->not->toThrow(\Throwable::class);
});

test('ignora codici mal formattati nel calcolo del prossimo numero', function () {
    $doctor1 = User::factory()->create();
    $doctor2 = User::factory()->create();
    $doctor3 = User::factory()->create();

    // codice "sporco" che non rispetta il pattern D-XXXXXX
    DoctorDetail::factory()->withCodeItem('INVALID')->create();
    DoctorDetail::factory()->withCodeItem('D-000010')->create();

    $newDoctorDetail = DoctorDetail::factory()
        ->withoutCodeItem()
        ->create(['doctor_id' => $doctor3->id]);

    (new GenerateDoctorCodeItemJob($newDoctorDetail->doctor_id))->handle();

    expect($newDoctorDetail->fresh()->code_item)->toBe('D-000011');
});
