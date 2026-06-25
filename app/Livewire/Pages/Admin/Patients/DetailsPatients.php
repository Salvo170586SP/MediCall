<?php

namespace App\Livewire\Pages\Admin\Patients;

use App\Models\User;
use Livewire\Component;

class DetailsPatients extends Component
{
    public User $patient;

    public function mount(User $patient)
    {
        $this->patient = $patient;
    }

    public function render()
    {
        return view('livewire.pages.admin.patients.details-patients');
    }
}
