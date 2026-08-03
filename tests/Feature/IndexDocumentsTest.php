<?php

use App\Livewire\Pages\Admin\Documents\IndexDocuments;
use App\Models\DoctorDetail;
use App\Models\Document;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('shows only users that have documents in the archive list', function () {
    Storage::fake('public');

    $user = User::factory()->create([
        'name' => 'Mario Rossi',
    ]);

    $doctor = User::factory()->create([
        'name' => 'Dottor Verdi',
    ]);

    $doctorDetail = DoctorDetail::factory()->create([
        'doctor_id' => $doctor->id,
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

    Document::factory()->create([
        'documentable_id' => $doctorDetail->id,
        'documentable_type' => DoctorDetail::class,
        'uploaded_by' => $doctor->id,
        'title' => 'Doctor detail document',
        'file_path' => 'documents/doctor.pdf',
        'original_filename' => 'doctor.pdf',
        'mime_type' => 'application/pdf',
        'file_size' => 100,
    ]);

    Livewire::test(IndexDocuments::class)
        ->assertSee('Mario Rossi')
        ->assertDontSee('Dottor Verdi');
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
