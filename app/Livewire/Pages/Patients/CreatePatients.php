<?php

namespace App\Livewire\Pages\Patients;

use App\Enums\DocumentType;
use App\Jobs\GeneratePatientCodeItemJob;
use App\Models\Document;
use App\Models\GuestDetail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreatePatients extends Component
{
    use WithFileUploads;
    
    public string $name = '';
    public string $email = '';
    public ?string $phone = null;
    public ?string $birth_city = null;
    public ?string $birth_date = null;
    public ?string $tax_id_code = null;
    public string $gender = '';
    public ?string $medical_history = null;
    public string $blood_type = '';
    public ?string $allergies = null;
    public $identity_card = null;
    public $fiscal_code = null;

    /**
     * Validazione  di identity_card appena selezionato.
     */
    public function updatedIdentityCard(): void
    {
        $this->validateOnly('identity_card', [
            'identity_card' => 'image|mimes:jpg,jpeg,png,webp,pdf|max:2048', // max 2MB
        ]);
    }

    /**
     * Validazione  di fiscal_code appena selezionato.
     */
    public function updatedFiscalCode(): void
    {
        $this->validateOnly('fiscal_code', [
            'fiscal_code' => 'image|mimes:jpg,jpeg,png,webp,pdf|max:2048', // max 2MB
        ]);
    }

    /**
     * Valida e crea un nuovo Doctor (User + DoctorDetail)
     */
    public function addPatient(): void
    {

        // Normalizza le stringhe vuote in null PRIMA della validazione
        foreach (['phone', 'birth_city', 'birth_date', 'tax_id_code', 'medical_history', 'allergies'] as $field) {
            if ($this->$field === '') {
                $this->$field = null;
            }
        }

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'birth_date' => ['nullable', 'date'],
            'tax_id_code' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'medical_history' => ['nullable', 'max:255'],
            'blood_type' => ['required', 'string'],
            'identity_card' => 'nullable|image|mimes:jpg,jpeg,png,webp,pdf|max:2048',
            'fiscal_code' => 'nullable|image|mimes:jpg,jpeg,png,webp,pdf|max:2048',
        ]);

        // Salva il file fisico PRIMA della transazione DB.
        // Se la transazione fallisce, va ripulito nel catch.
        $identityCardPath = null;
        if ($this->identity_card) {
            $identityCardPath = $this->identity_card->store('patients/documents', 'public');
        }
        // Salva il file fisico PRIMA della transazione DB.
        // Se la transazione fallisce, va ripulito nel catch.
        $fiscalCodePath = null;
        if ($this->fiscal_code) {
            $fiscalCodePath = $this->fiscal_code->store('patients/documents', 'public');
        }

        // Crea il Doctor usando una transazione per garantire consistenza
        try {
            $patient = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make('password'),
            ]);

            $patient->assignRole('Guest');

            GuestDetail::create([
                'guest_id' => $patient->id,
                'birth_city' => $this->birth_city,
                'birth_date' => $this->birth_date,
                'tax_id_code' => $this->tax_id_code,
                'phone' => $this->phone,
                'medical_history' => $this->medical_history,
                'blood_type' => $this->blood_type,
                'allergies' => $this->allergies,
            ]);

            // Salva il riferimento della foto nella tabella documents,
            // collegata polimorficamente allo User (il dottore)
            if ($identityCardPath) {
                Document::create([
                    'documentable_id' => $patient->id,
                    'documentable_type' => User::class,
                    'uploaded_by' => auth()->id() ?? $patient->id, // chi ha caricato (admin loggato)
                    'type' => DocumentType::IdentityCard,
                    'title' => "Carta d\'identità di $patient->name",
                    'file_path' => $identityCardPath,
                    'original_filename' => $this->identity_card->getClientOriginalName(),
                    'mime_type' => $this->identity_card->getMimeType(),
                    'file_size' => $this->identity_card->getSize(),
                    'is_verified' => true, // caricata direttamente da admin, quindi già verificata
                ]);
            }
            // Salva il riferimento della foto nella tabella documents,
            // collegata polimorficamente allo User (il dottore)
            if ($fiscalCodePath) {
                Document::create([
                    'documentable_id' => $patient->id,
                    'documentable_type' => User::class,
                    'uploaded_by' => auth()->id() ?? $patient->id, // chi ha caricato (admin loggato)
                    'type' => DocumentType::TaxCode,
                    'title' => "Codice fiscale di $patient->name",
                    'file_path' => $fiscalCodePath,
                    'original_filename' => $this->fiscal_code->getClientOriginalName(),
                    'mime_type' => $this->fiscal_code->getMimeType(),
                    'file_size' => $this->fiscal_code->getSize(),
                    'is_verified' => true, // caricata direttamente da admin, quindi già verificata
                ]);
            }

            GeneratePatientCodeItemJob::dispatch($patient->id);

            session()->flash('success', 'Elemento creato con successo!');
            $this->redirect(route('patients.details', $patient->id), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante la creazione: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.patients.create-patients');
    }
}
