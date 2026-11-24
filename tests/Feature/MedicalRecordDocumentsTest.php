<?php

use App\Enums\DocumentType;
use App\Livewire\Pages\Documents\IndexDocuments;
use App\Livewire\Pages\Guest\MedicalDocuments\IndexMedicalDocuments;
use App\Livewire\Pages\Guest\UploadDocuments\IndexUploadDocuments;
use App\Models\Appointment;
use App\Models\Document;
use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
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

it('shows only documents sent by the authenticated patient', function () {
    $patient = User::factory()->create();
    $otherPatient = User::factory()->create();
    $doctor = User::factory()->create();

    $patientRecord = MedicalRecord::create([
        'guest_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'visit_date' => now()->toDateString(),
        'diagnosis' => 'Diagnosi inviata dal paziente',
    ]);
    Document::factory()->forDocumentable($patientRecord)->create([
        'uploaded_by' => $patient->id,
        'title' => 'Documento inviato dal paziente',
        'original_filename' => 'inviato-dal-paziente.pdf',
    ]);

    $receivedRecord = MedicalRecord::create([
        'guest_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'visit_date' => now()->subDay()->toDateString(),
        'diagnosis' => 'Diagnosi ricevuta dal dottore',
    ]);
    Document::factory()->forDocumentable($receivedRecord)->create([
        'uploaded_by' => $doctor->id,
        'title' => 'Documento inviato dal dottore',
        'original_filename' => 'inviato-dal-dottore.pdf',
    ]);

    $otherPatientRecord = MedicalRecord::create([
        'guest_id' => $otherPatient->id,
        'doctor_id' => $doctor->id,
        'visit_date' => now()->toDateString(),
        'diagnosis' => 'Diagnosi di altro paziente',
    ]);
    Document::factory()->forDocumentable($otherPatientRecord)->create([
        'uploaded_by' => $otherPatient->id,
        'title' => 'Documento di altro paziente',
        'original_filename' => 'altro-paziente.pdf',
    ]);

    Livewire::actingAs($patient)
        ->test(IndexUploadDocuments::class)
        ->assertSee('Diagnosi inviata dal paziente')
        ->assertDontSee('Diagnosi ricevuta dal dottore')
        ->assertDontSee('Diagnosi di altro paziente');
});

it('allows a patient to send a document to an associated doctor', function () {
    Storage::fake('public');

    $patient = User::factory()->create();
    $doctor = User::factory()->create()->assignRole('Doctor');
    $unassociatedDoctor = User::factory()->create()->assignRole('Doctor');

    Appointment::create([
        'guest_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'scheduled_at' => now()->addDay(),
        'duration' => 30,
        'type' => 'in_person',
        'status' => 'confirmed',
    ]);

    Livewire::actingAs($patient)
        ->test(IndexUploadDocuments::class)
        ->set('doctor_id', $doctor->id)
        ->set('title', 'Analisi del sangue')
        ->set('document_type', 'medical_record')
        ->set('document', UploadedFile::fake()->create('analisi.pdf', 100, 'application/pdf'))
        ->call('sendDocument')
        ->assertHasNoErrors()
        ->assertSee('Documento inviato correttamente.');

    $document = Document::query()
        ->where('uploaded_by', $patient->id)
        ->where('recipient_id', $doctor->id)
        ->first();

    expect($document)->not->toBeNull()
        ->and($document->documentable_id)->toBe($patient->id)
        ->and($document->documentable_type)->toBe(User::class)
        ->and($document->title)->toBe('Analisi del sangue')
        ->and(Storage::disk('public')->exists($document->file_path))->toBeTrue();

    Livewire::actingAs($patient)
        ->test(IndexUploadDocuments::class)
        ->set('doctor_id', $unassociatedDoctor->id)
        ->set('title', 'Documento non autorizzato')
        ->set('document_type', 'other')
        ->set('document', UploadedFile::fake()->create('non-autorizzato.pdf', 100, 'application/pdf'))
        ->call('sendDocument')
        ->assertHasErrors(['doctor_id']);

    expect(Document::query()->where('title', 'Documento non autorizzato')->exists())->toBeFalse();
});
