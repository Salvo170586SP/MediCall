<?php

use App\Models\Appointment;
use App\Models\Document;
use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class); // <-- verifica che ci sia

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated Admin can visit the dashboard', function () {
    Role::create(['name' => 'Admin']); // o il ruolo che la dashboard richiede

    $user = User::factory()->create();
    $user->assignRole('Admin');

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('authenticated Doctor can visit the dashboard', function () {
    Role::create(['name' => 'Doctor']);

    $user = User::factory()->create();
    $user->assignRole('Doctor');

    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('dashboard shows summary counts and only the ten most recent appointments', function () {
    Role::create(['name' => 'Admin']);
    Role::create(['name' => 'Doctor']);
    Role::create(['name' => 'Guest']);

    $admin = User::factory()->create();
    $admin->assignRole('Admin');
    $doctor = User::factory()->create(['name' => 'Dottore Dashboard']);
    $doctor->assignRole('Doctor');
    $patient = User::factory()->create(['name' => 'Paziente Dashboard']);
    $patient->assignRole('Guest');

    foreach (range(1, 11) as $daysAgo) {
        Appointment::create([
            'guest_id' => $patient->id,
            'doctor_id' => $doctor->id,
            'scheduled_at' => now()->subDays($daysAgo),
            'duration' => 30,
            'status' => 'completed',
        ]);
    }

    MedicalRecord::create([
        'guest_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'visit_date' => now()->toDateString(),
        'diagnosis' => 'Controllo di routine',
    ]);

    Document::create([
        'documentable_id' => $patient->id,
        'documentable_type' => User::class,
        'uploaded_by' => $admin->id,
        'type' => 'other',
        'title' => 'Documento dashboard',
        'file_path' => 'documents/dashboard.pdf',
        'original_filename' => 'dashboard.pdf',
        'mime_type' => 'application/pdf',
        'file_size' => 100,
    ]);

    $response = $this->actingAs($admin)->get(route('dashboard'));

    $response->assertOk()
        ->assertSee('Paziente Dashboard')
        ->assertSee('Dottore Dashboard')
        ->assertSee('1 documenti · 1 cartelle cliniche')
        ->assertSee('10 di 11')
        ->assertDontSee(now()->subDays(11)->format('d/m/Y H:i'));
});
