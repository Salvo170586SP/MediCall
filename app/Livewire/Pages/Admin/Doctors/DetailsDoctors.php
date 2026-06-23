<?php

namespace App\Livewire\Pages\Admin\Doctors;

use App\Models\User;
use Livewire\Component;

class DetailsDoctors extends Component
{
    public User $doctor;

    public function mount(User $doctor)
    {
        $this->doctor = $doctor;
    }
   
    public function render()
    {
        return view('livewire.pages.admin.doctors.details-doctors');
    }
}
