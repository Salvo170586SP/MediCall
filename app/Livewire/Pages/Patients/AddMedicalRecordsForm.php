<?php

namespace App\Livewire\Pages\Patients;

use App\Enums\DocumentType;
use App\Models\MedicalRecord;
use App\Models\User;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class AddMedicalRecordsForm extends Component
{
    use WithFileUploads;

    public User $patient;
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

    public function mount(User $patient): void
    {
        $this->patient = $patient;
        $this->file_types = [
            'Ricetta' => DocumentType::Prescription,
            'Referto' => DocumentType::MedicalRecord,
            'Altro' => DocumentType::Other,
        ];
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
        ]);
        $this->resetValidation();
    }

    public function save(): void
    {
        $this->validate();

        // Salva il file fisico PRIMA della transazione DB.
        // Se la transazione fallisce, va ripulito nel catch.
        $filePath = null;
        if ($this->file) {
            $filePath = $this->file->store('patients/documents', 'public');
        }

        $medicalRecord = MedicalRecord::create([
            'guest_id' => $this->patient->id,
            'doctor_id' => Auth::id(),
            'visit_date' => $this->visit_date ?: null,
            'diagnosis' => $this->diagnosis,
            'prescription' => $this->prescription,
            'instructions' => $this->instructions,
        ]);

        // Salva il riferimento del file collegandolo polimorficamente alla cartella clinica.
        if ($filePath) {
            $document = $medicalRecord->medicalRecordDocument()->create([
                'uploaded_by' => Auth::id(),
                'type' => $this->file_type,
                'title' => $this->file_type . ' di ' . $this->patient->name,
                'file_path' => $filePath,
                'original_filename' => $this->file->getClientOriginalName(),
                'mime_type' => $this->file->getMimeType(),
                'file_size' => $this->file->getSize(),
                'is_verified' => true, // caricata direttamente da admin, quindi già verificata
            ]);
        }

        $this->patient->notify(new UserNotification(
            'document_added',
            'Il dottor ' . Auth::user()->name . ' ha aggiunto un nuovo referto al tuo fascicolo.',
        ));

        $this->dispatch('medical-record-created');
        $this->resetForm();
    }

    public function render()
    {
        return view('livewire.pages.patients.add-medical-records-form');
    }
}
