<?php

namespace App\Livewire\Pages\Patients;

use App\Enums\DocumentType;
use App\Models\Document;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class EditPatients extends Component
{
    use WithFileUploads;

    public User $patient;
    public string $name = '';
    public string $img_url = '';
    public string $phone = '';
    public string $birth_city = '';
    public string $birth_date;
    public string $tax_id_code = '';
    public string $medical_history = '';
    public string $blood_type = '';
    public string $allergies = '';

    public  $identity_card = null;
    public  $fiscal_code = null;

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

    public function mount(User $patient)
    {
        $this->patient = $patient;
        $this->name = $patient->name;
        $this->phone = $patient->guestDetail->phone ?? '';
        $this->birth_date = $patient->guestDetail->birth_date
            ? Carbon::parse($patient->guestDetail->birth_date)->format('Y-m-d')
            : '';
        $this->birth_city = $patient->guestDetail->birth_city ?? '';
        $this->tax_id_code = $patient->guestDetail->tax_id_code ?? '';
        $this->medical_history = $patient->guestDetail->medical_history ?? '';
        $this->blood_type = $patient->guestDetail->blood_type ?? '';
        $this->allergies = $patient->guestDetail->allergies ?? '';
    }

    /**
     * Valida e modifica un nuovo Patient (User + GuestDetail)
     */
    public function editPatient(int $patient_id): void
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'tax_id_code' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'medical_history' => ['nullable', 'max:255'],
            'blood_type' => ['required', 'string'],
           /*  'identity_card' => ['nullable','image','mimes:jpg,jpeg,png,webp,pdf','max:2048'],
            'fiscal_code' => ['nullable','image','mimes:jpg,jpeg,png,webp,pdf','max:2048'], */
        ], [
            'name.required' => 'Il nome è obbligatorio.',
            'birth_date.date' => 'Il campo è una data',
            'tax_id_code.max' => 'Il campo deve avere max 20 caratteri',
            'phone.regex' => 'Il telefono deve contenere solo numeri.',
            'phone.max' => 'Il telefono deve contenere max 20 numeri.',
            'medical_history.max' => 'Il telefono deve contenere max 255 numeri.',
            'blood_type.required' => 'Il campo è obbligatorio.',
        ]);

        // Salva il file fisico PRIMA della transazione DB.
        // Se la transazione fallisce, va ripulito nel catch.
        $identityCardPath = null;
        if ($this->identity_card) {
            $identityCardPath = $this->identity_card->store('patients/documents', 'public');
        }

        $fiscalCodePath = null;
        if ($this->fiscal_code) {
            $fiscalCodePath = $this->fiscal_code->store('patients/documents', 'public');
        }

        try {
            $patient = User::findOrFail($patient_id);

            $patient->update([
                'name' => $this->name,
            ]);

            $patient->guestDetail->update([
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
                $existing = Document::where('documentable_id', $this->patient->id)
                    ->where('documentable_type', User::class)
                    ->where('type', DocumentType::IdentityCard)
                    ->first();

                if ($existing && $existing->file_path && Storage::disk('public')->exists($existing->file_path)) {
                    Storage::disk('public')->delete($existing->file_path);
                }

                Document::updateOrCreate(
                    [
                        'documentable_id' => $this->patient->id,
                        'documentable_type' => User::class,
                        'type' => DocumentType::IdentityCard,
                    ],
                    [
                        'uploaded_by' => auth()->id() ?? $this->patient->id,
                        'title' =>  "Carda d'identità di $patient->name", 
                        'file_path' => $identityCardPath,
                        'original_filename' => $this->identity_card->getClientOriginalName(),
                        'mime_type' => $this->identity_card->getMimeType(),
                        'file_size' => $this->identity_card->getSize(),
                        'is_verified' => true,
                    ]
                );
            }

            if ($fiscalCodePath) {
                $existing = Document::where('documentable_id', $this->patient->id)
                    ->where('documentable_type', User::class)
                    ->where('type', DocumentType::TaxCode)
                    ->first();

                if ($existing && $existing->file_path && Storage::disk('public')->exists($existing->file_path)) {
                    Storage::disk('public')->delete($existing->file_path);
                }

                Document::updateOrCreate(
                    [
                        'documentable_id' => $this->patient->id,
                        'documentable_type' => User::class,
                        'type' => DocumentType::TaxCode,
                    ],
                    [
                        'uploaded_by' => auth()->id() ?? $this->patient->id,
                        'title' => "Codice Fiscale di $patient->name", 
                        'file_path' => $fiscalCodePath,
                        'original_filename' => $this->fiscal_code->getClientOriginalName(),
                        'mime_type' => $this->fiscal_code->getMimeType(),
                        'file_size' => $this->fiscal_code->getSize(),
                        'is_verified' => true,
                    ]
                );
            }

            session()->flash('success', 'Elemento modificato con successo!');
            $this->redirect(route('patients'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante la modifica: ' . $e->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.pages.patients.edit-patients');
    }
}
