<?php

use App\Livewire\Pages\Documents\IndexDocuments;
use App\Models\Appointment;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

it('shows only users that have documents in the archive list', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'name' => 'Mario Rossi',
    ]);

    $doctor = User::factory()->create([
        'name' => 'Dottor Verdi',
    ]);

    Document::factory()->create([
        'documentable_id' => $user->id,
        'documentable_type' => User::class,
        'uploaded_by' => $user->id,
        'title' => 'User document',
        'file_path' => 'documents/user.pdf',
        'original_filename' => 'user.pdf',
        'mime_type' => 'application/pdf',
        'file_size' => 100,
    ]);

    Livewire::test(IndexDocuments::class)
        ->assertSee('Mario Rossi')
        ->assertDontSee('Dottor Verdi');
});

it('shows only the patients followed by the authenticated doctor', function () {
    Role::firstOrCreate(['name' => 'Doctor', 'guard_name' => 'web']);
    $doctor = User::factory()->create()->assignRole('Doctor');
    $followedPatient = User::factory()->create(['name' => 'Paziente Seguito']);
    $unfollowedPatient = User::factory()->create(['name' => 'Paziente Non Seguito']);

    Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'guest_id' => $followedPatient->id,
    ]);

    Document::factory()->create([
        'documentable_id' => $followedPatient->id,
        'documentable_type' => User::class,
        'uploaded_by' => $doctor->id,
        'title' => 'Archivio seguito',
    ]);

    Document::factory()->create([
        'documentable_id' => $unfollowedPatient->id,
        'documentable_type' => User::class,
        'uploaded_by' => $doctor->id,
        'title' => 'Archivio non seguito',
    ]);

    Livewire::actingAs($doctor)
        ->test(IndexDocuments::class)
        ->assertSee('Paziente Seguito')
        ->assertDontSee('Paziente Non Seguito')
        ->set('selectedUserId', $followedPatient->id)
        ->assertSee('Archivio seguito')
        ->set('selectedUserId', $unfollowedPatient->id)
        ->assertDontSee('Archivio non seguito');
});

it('shows all patient archives to an authenticated admin', function () {
    Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);
    $admin = User::factory()->create()->assignRole('Admin');
    $firstPatient = User::factory()->create(['name' => 'Primo Paziente']);
    $secondPatient = User::factory()->create(['name' => 'Secondo Paziente']);

    foreach ([$firstPatient, $secondPatient] as $patient) {
        Document::factory()->create([
            'documentable_id' => $patient->id,
            'documentable_type' => User::class,
            'uploaded_by' => $admin->id,
        ]);
    }

    Livewire::actingAs($admin)
        ->test(IndexDocuments::class)
        ->assertSee('Primo Paziente')
        ->assertSee('Secondo Paziente');
});

it('shows the selected user documents and the empty state when nothing is selected', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'name' => 'Mario Rossi',
    ]);

    $otherUser = User::factory()->create([
        'name' => 'Giuseppe Verdi',
    ]);

    Document::factory()->create([
        'documentable_id' => $user->id,
        'documentable_type' => User::class,
        'uploaded_by' => $user->id,
        'title' => 'Documento Mario',
        'file_path' => 'documents/mario.pdf',
        'original_filename' => 'mario.pdf',
        'mime_type' => 'application/pdf',
        'file_size' => 100,
    ]);

    Document::factory()->create([
        'documentable_id' => $otherUser->id,
        'documentable_type' => User::class,
        'uploaded_by' => $otherUser->id,
        'title' => 'Documento Giuseppe',
        'file_path' => 'documents/giuseppe.pdf',
        'original_filename' => 'giuseppe.pdf',
        'mime_type' => 'application/pdf',
        'file_size' => 100,
    ]);

    Livewire::test(IndexDocuments::class)
        ->assertSee('Clicca su un utente per vedere i documenti')
        ->set('selectedUserId', $user->id)
        ->assertSee('Documento Mario')
        ->assertDontSee('Documento Giuseppe');
});

it('filters the selected user documents only and keeps the users list untouched', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'name' => 'Mario Rossi',
    ]);

    $otherUser = User::factory()->create([
        'name' => 'Giuseppe Verdi',
    ]);

    Document::factory()->create([
        'documentable_id' => $user->id,
        'documentable_type' => User::class,
        'uploaded_by' => $user->id,
        'title' => 'Documento Mario',
        'file_path' => 'documents/mario.pdf',
        'original_filename' => 'mario.pdf',
        'mime_type' => 'application/pdf',
        'file_size' => 100,
    ]);

    Document::factory()->create([
        'documentable_id' => $otherUser->id,
        'documentable_type' => User::class,
        'uploaded_by' => $otherUser->id,
        'title' => 'Documento Giuseppe',
        'file_path' => 'documents/giuseppe.pdf',
        'original_filename' => 'giuseppe.pdf',
        'mime_type' => 'application/pdf',
        'file_size' => 100,
    ]);

    Livewire::test(IndexDocuments::class)
        ->assertSee('Mario Rossi')
        ->assertSee('Giuseppe Verdi')
        ->set('selectedUserId', $user->id)
        ->set('search', 'Giuseppe')
        ->assertSee('Mario Rossi')
        ->assertSee('Giuseppe Verdi')
        ->assertDontSee('Documento Mario')
        ->assertDontSee('Documento Giuseppe');
});
