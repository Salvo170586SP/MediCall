<?php

use App\Enums\DocumentType;
use App\Livewire\Pages\Patients\EditMedicalRecordsForm;
use App\Models\Document;
use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

uses(RefreshDatabase::class);

function medicalRecordForEdit(User $patient, User $doctor): MedicalRecord
{
    return MedicalRecord::create([
        'guest_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'visit_date' => '2026-09-01',
        'diagnosis' => 'Diagnosi iniziale',
        'prescription' => 'Prescrizione iniziale',
        'instructions' => 'Istruzioni iniziali',
    ]);
}

it('precompila il form e conserva il file esistente senza nuovo upload', function () {
    Storage::fake('public');
    $patient = User::factory()->create();
    $doctor = User::factory()->create();
    $medicalRecord = medicalRecordForEdit($patient, $doctor);
    $document = Document::factory()->forDocumentable($medicalRecord)->prescription()->create([
        'uploaded_by' => $doctor->id,
        'file_path' => 'patients/documents/original.pdf',
    ]);
    Storage::disk('public')->put($document->file_path, 'original');

    Livewire::actingAs($doctor)
        ->test(EditMedicalRecordsForm::class, compact('patient', 'medicalRecord'))
        ->assertSet('visit_date', '2026-09-01')
        ->assertSet('diagnosis', 'Diagnosi iniziale')
        ->assertSet('file_type', DocumentType::Prescription->value)
        ->set('diagnosis', 'Diagnosi aggiornata')
        ->call('edit')
        ->assertHasNoErrors();

    expect($medicalRecord->refresh()->diagnosis)->toBe('Diagnosi aggiornata')
        ->and($medicalRecord->medicalRecordDocument->file_path)->toBe('patients/documents/original.pdf');
    expect(Storage::disk('public')->exists('patients/documents/original.pdf'))->toBeTrue();
});

it('sostituisce il file esistente quando ne viene selezionato uno nuovo', function () {
    Storage::fake('public');
    $patient = User::factory()->create();
    $doctor = User::factory()->create();
    $medicalRecord = medicalRecordForEdit($patient, $doctor);
    $document = Document::factory()->forDocumentable($medicalRecord)->create([
        'uploaded_by' => $doctor->id,
        'file_path' => 'patients/documents/original.pdf',
    ]);
    Storage::disk('public')->put($document->file_path, 'original');

    Livewire::actingAs($doctor)
        ->test(EditMedicalRecordsForm::class, compact('patient', 'medicalRecord'))
        ->set('file', UploadedFile::fake()->create('updated.pdf', 100, 'application/pdf'))
        ->set('file_type', DocumentType::Prescription->value)
        ->call('edit')
        ->assertHasNoErrors();

    $document->refresh();

    expect($document->file_path)->not->toBe('patients/documents/original.pdf')
        ->and($document->original_filename)->toBe('updated.pdf');
    expect(Storage::disk('public')->exists('patients/documents/original.pdf'))->toBeFalse()
        ->and(Storage::disk('public')->exists($document->file_path))->toBeTrue();
});

it('rimuove il file esistente quando viene richiesto', function () {
    Storage::fake('public');
    $patient = User::factory()->create();
    $doctor = User::factory()->create();
    $medicalRecord = medicalRecordForEdit($patient, $doctor);
    $document = Document::factory()->forDocumentable($medicalRecord)->prescription()->create([
        'uploaded_by' => $doctor->id,
        'file_path' => 'patients/documents/original.pdf',
    ]);
    Storage::disk('public')->put($document->file_path, 'original');

    Livewire::actingAs($doctor)
        ->test(EditMedicalRecordsForm::class, compact('patient', 'medicalRecord'))
        ->set('remove_existing_file', true)
        ->call('edit')
        ->assertHasNoErrors();

    expect($medicalRecord->refresh()->medicalRecordDocument)->toBeNull();
    expect(Storage::disk('public')->exists('patients/documents/original.pdf'))->toBeFalse();
});
