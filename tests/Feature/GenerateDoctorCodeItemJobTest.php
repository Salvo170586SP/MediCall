<?php

use App\Events\DoctorCodeItemGenerated;
use App\Jobs\GenerateDoctorCodeItemJob;
use App\Models\DoctorDetail;
use App\Models\User;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;

it('assigns a unique doctor code item when the job runs', function () {
    Bus::fake();

    $user = User::factory()->create();
    $user->assignRole('Doctor');

    DoctorDetail::create([
        'doctor_id' => $user->id,
        'license_number' => 'LIC-001',
        'phone' => '123456789',
        'specialty' => 'Cardiologia',
        'city' => 'Roma',
        'available_hours' => ['mon' => ['09:00', '17:00']],
        'slot_duration' => 30,
        'is_active' => true,
    ]);

    GenerateDoctorCodeItemJob::dispatch($user->id);

    Bus::assertDispatched(GenerateDoctorCodeItemJob::class, function (GenerateDoctorCodeItemJob $job) use ($user) {
        return $job->doctorId === $user->id;
    });

    Event::fake();
    Queue::fake();

    $job = new GenerateDoctorCodeItemJob($user->id);
    $job->handle();

    $detail = $user->fresh()->doctorDetail;

    expect($detail->code_item)->not->toBeNull()
        ->and($detail->code_item)->toMatch('/^DOC-\d{6}$/');

    $secondUser = User::factory()->create();
    $secondUser->assignRole('Doctor');

    DoctorDetail::create([
        'doctor_id' => $secondUser->id,
        'license_number' => 'LIC-002',
        'phone' => '987654321',
        'specialty' => 'Dermatologia',
        'city' => 'Milano',
        'available_hours' => ['mon' => ['09:00', '17:00']],
        'slot_duration' => 30,
        'is_active' => true,
    ]);

    $secondJob = new GenerateDoctorCodeItemJob($secondUser->id);
    $secondJob->handle();

    $secondDetail = $secondUser->fresh()->doctorDetail;

    expect($secondDetail->code_item)->not->toBe($detail->code_item);
});
