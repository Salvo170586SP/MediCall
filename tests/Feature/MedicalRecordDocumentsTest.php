<?php

use App\Enums\DocumentType;
use App\Livewire\Pages\Guest\MedicalDocuments\IndexMedicalDocuments;
use App\Models\Document;
use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

it('associa a un referto medico solo documenti di tipo ricetta o referto', function () {
    $patient = User::factory()->create();
    $doctor = User::factory()->create();
    $medicalRecord = MedicalRecord::create([
        'guest_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'visit_date' => now()->toDateString(),
        'diagnosis' => 'Diagnosi di test',
    ]);

    $prescription = Document::factory()
        ->forDocumentable($medicalRecord)
        ->prescription()
        ->create(['uploaded_by' => $doctor->id]);
    $medicalReport = Document::factory()
        ->forDocumentable($medicalRecord)
        ->create([
            'uploaded_by' => $doctor->id,
            'type' => DocumentType::MedicalRecord,
        ]);
    Document::factory()
        ->forDocumentable($medicalRecord)
        ->photo()
        ->create(['uploaded_by' => $doctor->id]);

    expect(Schema::hasColumn('medical_records', 'file'))->toBeFalse()
        ->and($medicalRecord->documents)->toHaveCount(2)
        ->and($medicalRecord->documents->pluck('id')->all())
        ->toContain($prescription->id, $medicalReport->id);
});

it('consente al paziente di scaricare gli allegati dei propri referti', function () {
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
        ->call('downloadDocument', $document->id)
        ->assertFileDownloaded('referto.pdf');
});

it('nega il download di allegati appartenenti a un altro paziente', function () {
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
    ]);

    expect(fn () => Livewire::actingAs($patient)
        ->test(IndexMedicalDocuments::class)
        ->call('downloadDocument', $document->id))
        ->toThrow(ModelNotFoundException::class);
});
