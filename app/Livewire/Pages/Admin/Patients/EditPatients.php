<?php

namespace App\Livewire\Pages\Admin\Patients;

use App\Models\User;
use Carbon\Carbon;
use Livewire\Component;

class EditPatients extends Component
{
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

    public function mount(User $patient)
    {
        $this->patient = $patient;
        $this->name = $patient->name;
        $this->phone = $patient->guestDetail->phone;
        $this->birth_date = $patient->guestDetail->birth_date
            ? Carbon::parse($patient->guestDetail->birth_date)->format('Y-m-d')
            : '';
        $this->birth_city = $patient->guestDetail->birth_city;
        $this->tax_id_code = $patient->guestDetail->tax_id_code;
        $this->medical_history = $patient->guestDetail->medical_history;
        $this->blood_type = $patient->guestDetail->blood_type;
        $this->allergies = $patient->guestDetail->allergies;
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
        ], [
            'name.required' => 'Il nome è obbligatorio.',
            'birth_date.date' => 'Il campo è una data',
            'tax_id_code.max' => 'Il campo deve avere max 20 caratteri',
            'phone.regex' => 'Il telefono deve contenere solo numeri.',
            'phone.max' => 'Il telefono deve contenere max 20 numeri.',
            'medical_history.max' => 'Il telefono deve contenere max 255 numeri.',
            'blood_type.required' => 'Il campo è obbligatorio.',
        ]);

        try {

            $patient = User::findOrFail($patient_id);

            $patient->update([
                'name' => $this->name,
                /*  'password' => Hash::make('password'), */
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

            session()->flash('success', 'Elemento modificato con successo!');
            $this->redirect(route('patients'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante la modifica: ' . $e->getMessage());
        }
    }


    public function render()
    {
        return view('livewire.pages.admin.patients.edit-patients');
    }
}
