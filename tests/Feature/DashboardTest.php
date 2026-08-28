<?php

use App\Livewire\Pages\Patients\IndexPatients;
use App\Models\Appointment;
use App\Models\DoctorDetail;
use App\Models\Document;
use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
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

test('doctor can only access their own doctor profile', function () {
    Role::create(['name' => 'Admin']);
    Role::create(['name' => 'Doctor']);

    $doctor = User::factory()->create(['name' => 'Profilo Dottore']);
    $doctor->assignRole('Doctor');
    DoctorDetail::create([
        'doctor_id' => $doctor->id,
        'license_number' => 'IT-PROFILO-001',
        'specialty' => 'Cardiologia',
        'available_hours' => [],
    ]);

    $otherDoctor = User::factory()->create();
    $otherDoctor->assignRole('Doctor');

    $this->actingAs($doctor)
        ->get(route('doctor.profile'))
        ->assertOk()
        ->assertSee('Profilo Dottore');

    $this->get(route('doctors'))->assertForbidden();
    $this->get(route('doctors.details', $otherDoctor))->assertForbidden();
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

test('doctor sees only the data of their patients on the dashboard', function () {
    Role::create(['name' => 'Admin']);
    Role::create(['name' => 'Doctor']);
    Role::create(['name' => 'Guest']);

    $admin = User::factory()->create(['name' => 'Admin Dashboard']);
    $admin->assignRole('Admin');
    $doctor = User::factory()->create(['name' => 'Dottore Visibile']);
    $doctor->assignRole('Doctor');
    $otherDoctor = User::factory()->create(['name' => 'Dottore Nascosto']);
    $otherDoctor->assignRole('Doctor');
    $patient = User::factory()->create(['name' => 'Paziente Visibile']);
    $patient->assignRole('Guest');
    $otherPatient = User::factory()->create(['name' => 'Paziente Nascosto']);
    $otherPatient->assignRole('Guest');

    Appointment::create([
        'guest_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'scheduled_at' => now()->addDay(),
        'duration' => 30,
        'status' => 'confirmed',
    ]);
    Appointment::create([
        'guest_id' => $otherPatient->id,
        'doctor_id' => $otherDoctor->id,
        'scheduled_at' => now()->addDays(2),
        'duration' => 30,
        'status' => 'confirmed',
    ]);

    MedicalRecord::create([
        'guest_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'visit_date' => now()->toDateString(),
        'diagnosis' => 'Record visibile',
    ]);
    MedicalRecord::create([
        'guest_id' => $otherPatient->id,
        'doctor_id' => $otherDoctor->id,
        'visit_date' => now()->toDateString(),
        'diagnosis' => 'Record nascosto',
    ]);

    Document::create([
        'documentable_id' => $patient->id,
        'documentable_type' => User::class,
        'uploaded_by' => $doctor->id,
        'type' => 'other',
        'title' => 'Documento visibile',
        'file_path' => 'documents/visible.pdf',
        'original_filename' => 'visible.pdf',
        'mime_type' => 'application/pdf',
        'file_size' => 100,
    ]);
    Document::create([
        'documentable_id' => $otherPatient->id,
        'documentable_type' => User::class,
        'uploaded_by' => $otherDoctor->id,
        'type' => 'other',
        'title' => 'Documento nascosto',
        'file_path' => 'documents/hidden.pdf',
        'original_filename' => 'hidden.pdf',
        'mime_type' => 'application/pdf',
        'file_size' => 100,
    ]);

    $this->actingAs($doctor)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Paziente Visibile')
        ->assertSee('Dottore Visibile')
        ->assertSee('1 documenti · 1 cartelle cliniche')
        ->assertSee('1 di 1')
        ->assertDontSee('Paziente Nascosto')
        ->assertDontSee('Dottore Nascosto');

    $this->actingAs($admin)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Paziente Visibile')
        ->assertSee('Paziente Nascosto')
        ->assertSee('2 documenti · 2 cartelle cliniche')
        ->assertSee('2 di 2');
});

test('non-doctors see the complete archive on the dashboard', function () {
    Role::create(['name' => 'Admin']);
    Role::create(['name' => 'Guest']);

    $user = User::factory()->create();
    $user->assignRole('Guest');
    $firstPatient = User::factory()->create();
    $firstPatient->assignRole('Guest');
    $secondPatient = User::factory()->create();
    $secondPatient->assignRole('Guest');

    foreach ([$firstPatient, $secondPatient] as $patient) {
        Document::create([
            'documentable_id' => $patient->id,
            'documentable_type' => User::class,
            'uploaded_by' => $user->id,
            'type' => 'other',
            'file_path' => 'documents/archive.pdf',
            'original_filename' => 'archive.pdf',
            'mime_type' => 'application/pdf',
            'file_size' => 100,
        ]);
    }

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('2 documenti · 0 cartelle cliniche');
});

test('doctor sees only their patients in the patient list', function () {
    Role::create(['name' => 'Admin']);
    Role::create(['name' => 'Doctor']);
    Role::create(['name' => 'Guest']);

    $admin = User::factory()->create(['name' => 'Admin Lista Pazienti']);
    $admin->assignRole('Admin');
    $doctor = User::factory()->create();
    $doctor->assignRole('Doctor');
    $otherDoctor = User::factory()->create();
    $otherDoctor->assignRole('Doctor');
    $patient = User::factory()->create(['name' => 'Paziente del Dottore']);
    $patient->assignRole('Guest');
    $otherPatient = User::factory()->create(['name' => 'Paziente di un Altro Dottore']);
    $otherPatient->assignRole('Guest');

    Appointment::create([
        'guest_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'scheduled_at' => now()->addDay(),
        'duration' => 30,
        'status' => 'confirmed',
    ]);
    Appointment::create([
        'guest_id' => $otherPatient->id,
        'doctor_id' => $otherDoctor->id,
        'scheduled_at' => now()->addDays(2),
        'duration' => 30,
        'status' => 'confirmed',
    ]);

    Livewire::actingAs($doctor)
        ->test(IndexPatients::class)
        ->assertSee('Paziente del Dottore')
        ->assertDontSee('Paziente di un Altro Dottore');

    Livewire::actingAs($admin)
        ->test(IndexPatients::class)
        ->assertSee('Paziente del Dottore')
        ->assertSee('Paziente di un Altro Dottore');
});
