<?php

namespace App\Livewire\Pages\Admin\Doctors;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Arr;

class DetailsDoctors extends Component
{
    public User $doctor;
    public array $weekDays = [
        'mon' => 'Lunedì',
        'tue' => 'Martedì',
        'wed' => 'Mercoledì',
        'thu' => 'Giovedì',
        'fri' => 'Venerdì',
        'sat' => 'Sabato',
        'sun' => 'Domenica',
    ];
    public array $availableHours = [];
    public int $availableDaysCount = 0;
    
    public function mount(User $doctor)
    {
        $this->doctor = $doctor;
        $this->availableHours = $doctor->doctorDetail->available_hours ?? [];
        $this->availableDaysCount = collect($this->availableHours)
            ->filter(fn ($hours) => isset($hours[0], $hours[1]) && filled($hours[0]) && filled($hours[1]))
            ->count();
    }

    public function render()
    {
        return view('livewire.pages.admin.doctors.details-doctors');
    }
}
