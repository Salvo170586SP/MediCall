<?php

namespace App\Livewire\Pages\Patients;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class IndexPatients extends Component
{
    use WithPagination;

    public string $search = '';


    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deletePatient(int $patient_id)
    {
        try {
            $patient = User::findOrFail($patient_id);

            if ($patient) {
                $patient->delete();
            }

            session()->flash('success', 'Elemento eliminato con successo!');
        } catch (\Exception $e) {
            session()->flash('error', 'Errore durante la eliminazione: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $users_patients = User::role('Guest')
            ->with('guestDetail')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->paginate(10);

        return view('livewire.pages.patients.index-patients', compact('users_patients'));
    }
}
