<?php

namespace App\Livewire\Pages\Patients;

use App\Enums\DocumentType;
use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditMedicalRecordsForm extends Component
{
    use WithFileUploads;

    public User $patient;

    public MedicalRecord $medicalRecord;
    public ?int $doctor_id = null;
    public ?int $appintment_id = null;
    public ?string $visit_date = null;
    public string $diagnosis = '';
    public string $prescription = '';
    public string $instructions = '';
    public $file = null;
    public array $file_types = [
        'Ricetta' => DocumentType::Prescription,
        'Referto' => DocumentType::MedicalRecord,
        'Altro' => DocumentType::Other,
    ];
    public ?string $file_type = null;
    public bool $remove_existing_file = false;

    public function mount(User $patient, MedicalRecord $medicalRecord): void
    {
        $this->patient = $patient;
        abort_unless($medicalRecord->guest_id === $patient->id, 404);
        $this->medicalRecord = $medicalRecord->load('medicalRecordDocument');

        $this->file_types = [
            'Ricetta' => DocumentType::Prescription,
            'Referto' => DocumentType::MedicalRecord,
            'Altro' => DocumentType::Other,
        ];

        $this->loadRecordData();
    }

    /**
     * Validazione  di medical_record_file appena selezionato.
     */
    public function updatedFile(): void
    {
        $this->validateOnly('file', [
            'file' => 'file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ]);
    }

    protected function rules(): array
    {
        return [
            'visit_date' => ['nullable', 'date'],
            'diagnosis' => ['required', 'string'],
            'prescription' => ['nullable', 'string'],
            'instructions' => ['nullable', 'string'],
            'file_type' => ['nullable', 'required_with:file', 'string', 'in:prescription,medical_record,other'],
            'file' => 'nullable|file|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ];
    }

    protected function messages(): array
    {
        return [
            'diagnosis.required' => 'Il campo è obbligatorio.',
            'file_type.required_with' => 'Seleziona il tipo di file.',
        ];
    }

    public function resetForm(): void
    {
        $this->reset([
            'visit_date',
            'diagnosis',
            'prescription',
            'instructions',
            'file_type',
            'file',
            'remove_existing_file',
        ]);
        $this->loadRecordData();
        $this->resetValidation();
    }

    public function edit(): void
    {
        $this->validate();

        $oldFilePath = $this->medicalRecord->medicalRecordDocument?->file_path;
        $newFilePath = null;

        try {
            if ($this->file) {
                $newFilePath = $this->file->store('patients/documents', 'public');
            }

            DB::transaction(function () use ($newFilePath): void {
                $this->medicalRecord->update([
                   'visit_date' => $this->visit_date ?: null,
                    'diagnosis' => $this->diagnosis,
                    'prescription' => $this->prescription,
                    'instructions' => $this->instructions,
                ]);

                if ($newFilePath) {
                    $document = $this->medicalRecord->medicalRecordDocument;

                    if ($document) {
                        $document->update([
                            'uploaded_by' => Auth::id(),
                            'type' => $this->file_type,
                            'title' => 'Documento di '.$this->patient->name,
                            'file_path' => $newFilePath,
                            'original_filename' => $this->file->getClientOriginalName(),
                            'mime_type' => $this->file->getMimeType(),
                            'file_size' => $this->file->getSize(),
                            'is_verified' => true,
                        ]);
                    } else {
                        $this->medicalRecord->medicalRecordDocument()->create([
                            'uploaded_by' => Auth::id(),
                            'type' => $this->file_type,
                            'title' => 'Documento di '.$this->patient->name,
                            'file_path' => $newFilePath,
                            'original_filename' => $this->file->getClientOriginalName(),
                            'mime_type' => $this->file->getMimeType(),
                            'file_size' => $this->file->getSize(),
                            'is_verified' => true,
                        ]);
                    }
                } elseif ($this->remove_existing_file && $this->medicalRecord->medicalRecordDocument) {
                    $this->medicalRecord->medicalRecordDocument->forceDelete();
                }
            });
        } catch (\Throwable $exception) {
            if ($newFilePath) {
                Storage::disk('public')->delete($newFilePath);
            }

            throw $exception;
        }

        if ($oldFilePath && ($newFilePath || $this->remove_existing_file)) {
            Storage::disk('public')->delete($oldFilePath);
        }

        $this->medicalRecord->unsetRelation('medicalRecordDocument')->load('medicalRecordDocument');
        $this->dispatch('medical-record-edited');
        $this->resetForm();
    }

    private function loadRecordData(): void
    {
        $this->visit_date = $this->medicalRecord->visit_date?->toDateString();
        $this->diagnosis = $this->medicalRecord->diagnosis;
        $this->prescription = $this->medicalRecord->prescription ?? '';
        $this->instructions = $this->medicalRecord->instructions ?? '';
        $this->file_type = $this->medicalRecord->medicalRecordDocument?->type?->value;
        $this->remove_existing_file = false;
    }

    public function render()
    {
        return view('livewire.pages.patients.edit-medical-records-form');
    }
}
