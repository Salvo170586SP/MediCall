<?php

namespace App\Livewire\Pages\Admin\Doctors;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class EditDoctors extends Component
{
    public User $doctor;
    public string $name = '';
    public string $phone = '';
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

    public function mount(User $doctor)
    {
        $this->doctor = $doctor;
        $this->name = $doctor->name;
        $this->phone = $doctor->doctorDetail->phone;
        $this->bio = $doctor->doctorDetail->bio;
        $this->specialty = $doctor->doctorDetail->specialty;
        $this->sub_specialty = $doctor->doctorDetail->sub_specialty;
        $this->city = $doctor->doctorDetail->city;
        $this->slot_duration = $doctor->doctorDetail->slot_duration;
        $this->is_active = $doctor->doctorDetail->is_active;

        $hours = $doctor->doctorDetail->available_hours ?? [];
        foreach ($hours as $day => $slots) {
            $this->available_hours[$day] = array_slice($slots, 0, 2);

            if (count($this->available_hours[$day]) < 2) {
                $this->available_hours[$day] = ['', ''];
            }
        }
    }

    /**
     * Valida e crea un nuovo Doctor (User + DoctorDetail)
     */
    public function editDoctor(): void
    {


        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
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

        // modifica il Doctor usando una transazione per garantire consistenza
        try {
            DB::transaction(function () use ($validated) {
                // Crea l'utente con ruolo Doctor
                $this->doctor->update([
                    'name' => $validated['name'],
                ]);

                // Crea i dettagli del doctor
                $this->doctor->doctorDetail->update([
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

            session()->flash('success', 'Elemento modificato con successo!');
            $this->redirect(route('doctors'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante la modifica: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.pages.admin.doctors.edit-doctors');
    }
}
