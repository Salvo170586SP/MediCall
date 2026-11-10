<?php

use App\Enums\DocumentType;
use App\Livewire\Pages\Documents\IndexDocuments;
use App\Livewire\Pages\Guest\MedicalDocuments\IndexMedicalDocuments;
use App\Models\Document;
use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('shows only prescriptions and medical reports in the patient archive', function () {
    $patient = User::factory()->create();
    $doctor = User::factory()->create();
    $medicalRecord = MedicalRecord::create([
        'guest_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'visit_date' => now()->toDateString(),
        'diagnosis' => 'Diagnosi di test',
    ]);

    Document::factory()
        ->forDocumentable($medicalRecord)
        ->prescription()
        ->state(['title' => 'Prescrizione del paziente'])
        ->create(['uploaded_by' => $doctor->id]);
    Document::factory()
        ->forDocumentable($medicalRecord)
        ->create([
            'uploaded_by' => $doctor->id,
            'type' => DocumentType::MedicalRecord,
            'title' => 'Referto del paziente',
        ]);
    Document::factory()
        ->forDocumentable($medicalRecord)
        ->photo()
        ->create([
            'uploaded_by' => $doctor->id,
            'title' => 'Foto non inclusa',
        ]);

    Livewire::test(IndexDocuments::class)
        ->set('selectedUserId', $patient->id)
        ->assertSee('Prescrizione del paziente')
        ->assertSee('Referto del paziente')
        ->assertDontSee('Foto non inclusa');
});

it('shows the authenticated patient their medical record documents', function () {
    Storage::fake('public');

    $patient = User::factory()->create();
    $doctor = User::factory()->create();
    $medicalRecord = MedicalRecord::create([
        'guest_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'visit_date' => now()->toDateString(),
        'diagnosis' => 'Diagnosi di test',
    ]);
    $document = Document::factory()->forDocumentable($medicalRecord)->create([
        'uploaded_by' => $doctor->id,
        'file_path' => 'documents/referto.pdf',
        'original_filename' => 'referto.pdf',
    ]);
    Storage::disk('public')->put($document->file_path, 'contenuto');

    Livewire::actingAs($patient)
        ->test(IndexMedicalDocuments::class)
        ->assertSee('referto.pdf');
});

it('hides another patients medical record documents', function () {
    $patient = User::factory()->create();
    $otherPatient = User::factory()->create();
    $doctor = User::factory()->create();
    $medicalRecord = MedicalRecord::create([
        'guest_id' => $otherPatient->id,
        'doctor_id' => $doctor->id,
        'visit_date' => now()->toDateString(),
        'diagnosis' => 'Diagnosi di test',
    ]);
    $document = Document::factory()->forDocumentable($medicalRecord)->create([
        'uploaded_by' => $doctor->id,
        'type' => DocumentType::MedicalRecord,
        'title' => 'Referto privato',
        'original_filename' => 'referto-privato.pdf',
    ]);

    Livewire::actingAs($patient)
        ->test(IndexMedicalDocuments::class)
        ->assertDontSee('Referto privato')
        ->assertDontSee('referto-privato.pdf');
});
