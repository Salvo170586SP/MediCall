<?php

namespace App\Livewire\Pages\Admin\Doctors;

use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class IndexDoctors extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteDoctor(int $doctor_id)
    {
        try {
            $doctor = User::findOrFail($doctor_id);

            if ($doctor) {
                $doctor->delete();
            }

            session()->flash('success', 'Elemento eliminato con successo!');
        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante la eliminazione: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $users_doctor = User::role('doctor')
            ->with('doctorDetail')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(10);


        return view('livewire.pages.admin.doctors.index-doctors', compact('users_doctor'));
    }
}
