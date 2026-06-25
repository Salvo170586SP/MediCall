<?php

namespace App\Livewire\Pages\Admin\Patients;

use App\Models\GuestDetail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class CreatePatients extends Component
{
    public string $name = '';
    public string $email = '';
    public string $img_url = '';
    public string $phone = '';
    public string $birth_city = '';
    public string $birth_date = '';
    public string $tax_id_code = '';
    public string $gender = '';
    public string $medical_history = '';
    public string $blood_type = '';
    public string $allergies = '';


    /**
     * Valida e crea un nuovo Doctor (User + DoctorDetail)
     */
    public function addPatient(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'birth_date' => ['nullable', 'date'],
            'tax_id_code' => ['nullable', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:20', 'regex:/^[0-9]+$/'],
            'medical_history' => ['nullable', 'max:255'],
            'blood_type' => ['required', 'string'],
        ]);



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

            session()->flash('success', 'Elemento creato con successo!');
            $this->redirect(route('patients'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante la creazione: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.admin.patients.create-patients');
    }
}
