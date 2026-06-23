<?php

namespace App\Livewire\Pages\Admin\Doctors;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class IndexDoctors extends Component
{
    use WithPagination;

    public function render()
    {
        $users_doctor  = User::role('doctor')->with('doctorDetail')->paginate(10);
        return view('livewire.pages.admin.doctors.index-doctors', compact('users_doctor'));
    }
}
