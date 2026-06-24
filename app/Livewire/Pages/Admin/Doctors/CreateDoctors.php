<?php

namespace App\Livewire\Pages\Admin\Doctors;

use App\Models\DoctorDetail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateDoctors extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $license_number = '';
    public string $bio = '';
    public string $specialty = '';
    public string $sub_specialty = '';
    public string $city = '';
    public int $slot_duration = 30;
    public bool $is_active = true;
    public array $available_hours = [
        'mon' => ['', ''],
        'tue' => ['', ''],
        'wed' => ['', ''],
        'thu' => ['', ''],
        'fri' => ['', ''],
        'sat' => ['', ''],
        'sun' => ['', ''],
    ];

    /**
     * Valida e crea un nuovo Doctor (User + DoctorDetail)
     */
    public function addDoctor(): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'license_number' => 'required|string|max:255|unique:doctor_details,license_number',
            'bio' => 'nullable|string|max:1000',
            'specialty' => 'required|string|max:255',
            'sub_specialty' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'slot_duration' => 'required|integer|in:15,20,30,45,60',
            'is_active' => 'boolean',
            'available_hours' => 'required|array|size:7',
            'available_hours.mon' => 'array|size:2',
            'available_hours.tue' => 'array|size:2',
            'available_hours.wed' => 'array|size:2',
            'available_hours.thu' => 'array|size:2',
            'available_hours.fri' => 'array|size:2',
            'available_hours.sat' => 'array|size:2',
            'available_hours.sun' => 'array|size:2',
        ]);

        // Valida che gli orari siano nel formato corretto e logici
        foreach ($validated['available_hours'] as $day => $hours) {
            if (!empty($hours[0]) || !empty($hours[1])) {
                if (empty($hours[0]) || empty($hours[1])) {
                    $this->addError("available_hours.{$day}", "Orario di apertura e chiusura obbligatori se inseriti.");
                    return;
                }

                if ($hours[0] >= $hours[1]) {
                    $this->addError("available_hours.{$day}", "L'orario di apertura deve essere prima di quello di chiusura.");
                    return;
                }
            }
        }

        // Crea il Doctor usando una transazione per garantire consistenza
        try {
            DB::transaction(function () use ($validated) {
                // Crea l'utente con ruolo Doctor
                $user = User::create([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => bcrypt(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 16)),
                ]);

                // Assegna il ruolo Doctor
                $user->assignRole('Doctor');

                // Crea i dettagli del doctor
                DoctorDetail::create([
                    'doctor_id' => $user->id,
                    'license_number' => $validated['license_number'],
                    'phone' => $validated['phone'] ?? '',
                    'specialty' => $validated['specialty'],
                    'sub_specialty' => $validated['sub_specialty'] ?? '',
                    'bio' => $validated['bio'] ?? '',
                    'city' => $validated['city'] ?? '',
                    'slot_duration' => $validated['slot_duration'],
                    'is_active' => $validated['is_active'],
                    'available_hours' => $validated['available_hours'],
                ]);
            });

            session()->flash('success', 'Elemento creato con successo!');
            $this->redirect(route('doctors'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante la creazione: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.admin.doctors.create-doctors');
    }
}
